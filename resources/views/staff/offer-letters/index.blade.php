@extends(auth()->check() && auth()->user()->staffProfile ? 'layout.staff' : 'layout.marketer')

@section('title', 'My Offer Letters')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">My Offer Letters</h3>
                    <div class="card-tools">
                        <span class="badge badge-light">
                            Total: {{ $documents->total() }}
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($documents->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No offer letters found</h4>
                            <p class="text-muted">Your offer letters will appear here once they are sent by HR.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Effective Date</th>
                                        <th>Sent Date</th>
                                        <th>Sent By</th>
                                        <th>File Size</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $document)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $document->title }}</strong>
                                            </td>
                                            <td>
                                                {{ date('d M, Y', strtotime($document->effective_date)) }}
                                            </td>
                                            <td>
                                                {{ $document->created_at->format('d M, Y h:i A') }}
                                            </td>
                                            <td>
                                                {{ $document->reviewer->name ?? 'System' }}
                                            </td>
                                            <td>
                                                {{ round($document->file_size / 1024, 2) }} KB
                                            </td>
                                            <td>
                                                <a href="{{ route('staff.offer-letters.view', $document->id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   target="_blank"
                                                   title="View">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                
                                                <a href="{{ route('staff.offer-letters.download', $document->id) }}" 
                                                   class="btn btn-sm btn-success"
                                                   title="Download">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $documents->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection