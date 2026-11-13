{{-- Approve and reject buttons for one marketer, needs $marketer --}}
<div class="btn-group btn-group-sm" role="group">
    @unless ($marketer->isApproved())
        <form action="{{ route('admin.marketers.approve', $marketer) }}" method="POST"
            onsubmit="return confirm('Approve this marketer?');">
            @csrf
            <button type="submit" class="btn btn-sm btn-success" title="Approve marketer">
                <i class="fas fa-check"></i>
            </button>
        </form>
    @endunless
    @unless ($marketer->isRejected())
        <button type="button" class="btn btn-sm btn-outline-danger" title="Reject marketer"
            onclick="openMarketerRejectModal({{ $marketer->id }})">
            <i class="fas fa-times"></i>
        </button>
    @endunless
</div>
