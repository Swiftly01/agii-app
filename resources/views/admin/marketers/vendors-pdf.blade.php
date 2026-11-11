<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Vendors added by {{ $marketer ? ($marketer->user?->full_name ?: 'marketer') : 'marketers' }}</title>
    <style>
        @page { margin: 24px 28px; } 
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #1f2937; }
        h1 { font-size: 16px; margin: 0 0 4px; }
        .meta { color: #6b7280; margin-bottom: 10px; }
        .summary { margin-bottom: 12px; }
        .summary span { display: inline-block; margin-right: 18px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #94c953; color: #fff; text-align: left; padding: 6px 5px; font-size: 9px; }
        td { padding: 5px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        tr:nth-child(even) td { background: #f8fafc; }
        .muted { color: #6b7280; }
        .no-print { margin-bottom: 12px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    @if ($printMode)
        <div class="no-print">
            <button onclick="window.print()">Print / Save as PDF</button>
        </div>
    @endif

    <h1>Vendors added by {{ $marketer ? ($marketer->user?->full_name ?: 'marketer') : 'marketers' }}</h1>
    <div class="meta">
        Generated {{ $generatedAt->format('M d, Y H:i') }} by {{ $generatedBy }}
        @if ($marketer) &middot; Referral code {{ $marketer->referral_code }} @endif
    </div>

    <div class="summary">
        <span><strong>{{ $vendors->count() }}</strong> vendors</span>
        <span><strong>{{ $vendors->where('products_count', '>', 0)->count() }}</strong> with products</span>
        <span><strong>{{ $vendors->filter(fn ($v) => $v->activeSubscription)->count() }}</strong> with active subscription</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Vendor</th>
                <th>Business</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Location</th>
                <th>Approval</th>
                <th>Products</th>
                <th>Subscription</th>
                <th>Added By</th>
                <th>Date Added</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vendors as $vendor)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $vendor->full_name }}</td>
                    <td>{{ $vendor->business_name ?: '-' }}</td>
                    <td>{{ $vendor->email ?: '-' }}</td>
                    <td>{{ $vendor->phone ?: '-' }}</td>
                    <td>{{ collect([$vendor->city, $vendor->state])->filter()->implode(', ') ?: '-' }}</td>
                    <td>{{ $vendor->vendor_status ? ucfirst($vendor->vendor_status) : '-' }}</td>
                    <td>{{ $vendor->products_count }}</td>
                    <td>{{ $vendor->activeSubscription ? 'Active' : 'None' }}</td>
                    <td>
                        {{ $vendor->marketer?->user?->full_name ?: '-' }}
                        @if ($vendor->marketer)<br><span class="muted">{{ $vendor->marketer->referral_code }}</span>@endif
                    </td>
                    <td>{{ $vendor->created_at?->format('M d, Y') ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="11" class="muted" style="text-align:center; padding:18px;">No vendors have been added yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if ($printMode)
        <script>window.addEventListener('load', function () { window.print(); });</script>
    @endif
</body>
</html>
