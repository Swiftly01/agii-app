<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class RequeueMarketerProducts extends Command
{
    protected $signature = 'marketer:requeue-products {--dry-run : Only show how many products would change}';

    protected $description = 'Send live products of marketer-referred vendors back to pending so an admin can review them';

    public function handle(): int
    {
        $query = Product::query()
            ->where('status', 'active')
            ->whereNull('reviewed_by')
            ->whereHas('user', fn ($user) => $user->whereNotNull('referred_by'));

        $count = (clone $query)->count();

        if ($this->option('dry-run')) {
            $this->info("{$count} product(s) would be sent back to pending.");

            return self::SUCCESS;
        }

        $query->update(['status' => 'pending']);
        $this->info("{$count} product(s) sent back to pending.");

        return self::SUCCESS;
    }
}
