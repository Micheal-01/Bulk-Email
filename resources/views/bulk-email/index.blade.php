@extends('layouts.app')

@section('title', 'Email Campaigns')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Email Campaigns</h1>
        <a href="{{ route('bulk-email.create') }}" class="btn btn-primary">Create Campaign</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Recipients</th>
                    <th>Sent</th>
                    <th>Failed</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $campaign)
                    <tr>
                        <td>{{ $campaign->name }}</td>
                        <td>{{ $campaign->subject }}</td>
                        <td>
                            <span
                                class="badge bg-{{ $campaign->status === 'sent' ? 'success' : ($campaign->status === 'sending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </td>
                        <td>{{ $campaign->total_recipients }}</td>
                        <td>{{ $campaign->sent_count }}</td>
                        <td>{{ $campaign->failed_count }}</td>
                        <td>{{ $campaign->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('bulk-email.show', $campaign) }}"
                                class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No campaigns found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $campaigns->links() }}
@endsection
