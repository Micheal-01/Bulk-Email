@extends('layouts.app')

@section('title', 'Email Recipients')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Email Recipients</h1>
        <div>
            <a href="{{ route('recipients.create') }}" class="btn btn-primary">Add Recipient</a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                Import CSV
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">{{ $recipients->total() }}</h5>
                    <p class="card-text">Total Recipients</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success">{{ $recipients->where('is_active', true)->count() }}</h5>
                    <p class="card-text">Active</p>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Subscribed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recipients as $recipient)
                    <tr>
                        <td>{{ $recipient->name ?? '-' }}</td>
                        <td>{{ $recipient->email }}</td>
                        <td>
                            <span class="badge bg-{{ $recipient->is_active ? 'success' : 'secondary' }}">
                                {{ $recipient->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $recipient->subscribed_at ? $recipient->subscribed_at->format('Y-m-d') : '-' }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary">Edit</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No recipients found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $recipients->links() }}

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Recipients from CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('recipients.bulk-import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="csv_file" class="form-label">CSV File</label>
                            <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv"
                                required>
                            <div class="form-text">
                                CSV should have columns: email, name (optional)<br>
                                Example: email,name<br>
                                john@example.com,John Doe
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
