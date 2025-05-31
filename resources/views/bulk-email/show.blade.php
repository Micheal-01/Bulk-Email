@extends('layouts.app')

@section('title', 'Campaign: ' . $campaign->name)

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h1>{{ $campaign->name }}</h1>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Campaign Details</h5>
                    <span
                        class="badge bg-{{ $campaign->status === 'sent' ? 'success' : ($campaign->status === 'sending' ? 'warning' : 'secondary') }} fs-6">
                        {{ ucfirst($campaign->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Subject:</dt>
                        <dd class="col-sm-9">{{ $campaign->subject }}</dd>

                        <dt class="col-sm-3">Created:</dt>
                        <dd class="col-sm-9">{{ $campaign->created_at->format('Y-m-d H:i:s') }}</dd>

                        @if ($campaign->sent_at)
                            <dt class="col-sm-3">Sent:</dt>
                            <dd class="col-sm-9">{{ $campaign->sent_at->format('Y-m-d H:i:s') }}</dd>
                        @endif
                    </dl>

                    <div class="mt-3">
                        <strong>Email Body:</strong>
                        <div class="border p-3 mt-2 bg-light">
                            {!! nl2br(e($campaign->body)) !!}
                        </div>
                    </div>

                    <div class="mt-3 d-flex gap-2">
                        @if ($campaign->status === 'sending')
                            <form action="{{ route('bulk-email.complete', $campaign) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm"
                                    onclick="return confirm('Mark this campaign as complete?')">
                                    Mark as Complete
                                </button>
                            </form>
                        @endif

                        @if (in_array($campaign->status, ['draft', 'sent']))
                            <form action="{{ route('bulk-email.destroy', $campaign) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this campaign?')">
                                    Delete Campaign
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            @if ($campaign->status === 'draft')
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Send Campaign</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('bulk-email.send', $campaign) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Select Recipients</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="select_all">
                                    <label class="form-check-label" for="select_all">
                                        <strong>Select All ({{ $recipients->count() }} recipients)</strong>
                                    </label>
                                </div>
                                <hr>
                                <div style="max-height: 300px; overflow-y: auto;">
                                    @foreach ($recipients as $recipient)
                                        <div class="form-check">
                                            <input class="form-check-input recipient-checkbox" type="checkbox"
                                                name="recipient_ids[]" value="{{ $recipient->id }}"
                                                id="recipient_{{ $recipient->id }}">
                                            <label class="form-check-label" for="recipient_{{ $recipient->id }}">
                                                {{ $recipient->name ?? $recipient->email }}
                                                @if ($recipient->name)
                                                    <small class="text-muted">({{ $recipient->email }})</small>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success"
                                onclick="return confirm('Are you sure you want to send this campaign?')">
                                Send Campaign
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <dl>
                        <dt>Total Recipients:</dt>
                        <dd>{{ $stats['total_recipients'] }}</dd>

                        <dt>Sent:</dt>
                        <dd class="text-success">{{ $stats['sent_count'] }}</dd>

                        <dt>Failed:</dt>
                        <dd class="text-danger">{{ $stats['failed_count'] }}</dd>

                        <dt>Pending:</dt>
                        <dd class="text-warning">{{ $stats['pending_count'] }}</dd>

                        <dt>Success Rate:</dt>
                        <dd>{{ $stats['success_rate'] }}%</dd>
                    </dl>

                    @if ($stats['total_recipients'] > 0)
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ ($stats['sent_count'] / $stats['total_recipients']) * 100 }}%"></div>
                            <div class="progress-bar bg-danger" role="progressbar"
                                style="width: {{ ($stats['failed_count'] / $stats['total_recipients']) * 100 }}%"></div>
                        </div>
                        <small class="text-muted">Green: Sent, Red: Failed</small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('select_all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.recipient-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
@endsection
