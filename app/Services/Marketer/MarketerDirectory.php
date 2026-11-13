<?php

namespace App\Services\Marketer;

use App\Enums\MarketerStatus;
use App\Models\CommissionTransaction;
use App\Models\Marketer;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MarketerDirectory
{
    // Lists marketers with vendor, product and commission figures aggregated in one query
    public function paginate(?string $search = null, ?MarketerStatus $status = null, int $perPage = 20): LengthAwarePaginator
    {
        return Marketer::query()
            ->with('user:id,first_name,last_name,email,phone')
            ->when($search, fn (Builder $query) => $this->applySearch($query, $search))
            ->when($status, fn (Builder $query) => $query->where('status', $status))
            ->withCount([
                'referredVendors',
                'referredVendors as vendors_with_products_count' => fn (Builder $query) => $query->whereHas('products'),
            ])
            ->withSum(['commissionTransactions as total_commission' => fn (Builder $query) => $this->countable($query)], 'commission_amount')
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    // Platform-wide figures for the summary cards
    public function totals(): array
    {
        return [
            'marketers' => Marketer::count(),
            'pending' => Marketer::where('status', MarketerStatus::Pending)->count(),
            'vendors' => User::where('user_type', 'vendor')->whereIn('referred_by', Marketer::select('user_id'))->count(),
            'earnings' => (float) Marketer::sum('total_earnings'),
            'commission' => (float) $this->countable(CommissionTransaction::query())->sum('commission_amount'),
        ];
    }

    // One marketer's referred vendors, each with product count, subscription and the commission they generated
    public function referrals(Marketer $marketer, int $perPage = 20): LengthAwarePaginator
    {
        $referrals = $marketer->referredVendors()
            ->withCount('products')
            ->with('activeSubscription')
            ->latest()
            ->paginate($perPage);

        $commissions = $this->commissionByVendor($marketer, $referrals->pluck('id'));

        $referrals->getCollection()->each(fn (User $vendor) => $vendor->commission_earned = (float) ($commissions[$vendor->id] ?? 0));

        return $referrals;
    }

    // Every vendor a marketer added (all marketers when none is given), newest first, for the PDF export
    public function vendorsForExport(?Marketer $marketer = null): Collection
    {
        $vendors = User::query()
            ->where('user_type', 'vendor')
            ->when(
                $marketer,
                fn (Builder $query) => $query->where('referred_by', $marketer->user_id),
                fn (Builder $query) => $query->whereIn('referred_by', Marketer::select('user_id')),
            )
            ->withCount('products')
            ->with('activeSubscription')
            ->latest()
            ->get();

        // Attach the referring marketer to each vendor with a single extra query
        $marketers = Marketer::with('user:id,first_name,last_name')
            ->whereIn('user_id', $vendors->pluck('referred_by')->unique())
            ->get()
            ->keyBy('user_id');

        // A single marketer's export also shows the commission each referral generated
        $commissions = $marketer ? $this->commissionByVendor($marketer, $vendors->pluck('id')) : collect();

        return $vendors->each(function (User $vendor) use ($marketers, $commissions, $marketer) {
            $vendor->setAttribute('marketer', $marketers->get($vendor->referred_by));
            $vendor->setAttribute('commission_earned', $marketer ? (float) ($commissions[$vendor->id] ?? 0) : null);
        });
    }

    // Cancelled commissions never reach the marketer so they stay out of every total
    private function countable(Builder $query): Builder
    {
        return $query->where('status', '!=', CommissionTransaction::STATUS_CANCELLED);
    }

    private function commissionByVendor(Marketer $marketer, $vendorIds)
    {
        return $this->countable($marketer->commissionTransactions()->getQuery())
            ->whereIn('vendor_id', $vendorIds)
            ->selectRaw('vendor_id, SUM(commission_amount) as total')
            ->groupBy('vendor_id')
            ->pluck('total', 'vendor_id');
    }

    private function applySearch(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('referral_code', 'like', "%{$search}%")
                ->orWhereHas('user', fn (Builder $user) => $user
                    ->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%"));
        });
    }
}
