@extends('layouts.app')

@section('title', 'Create Email Campaign')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h1>Create Email Campaign</h1>

            <form action="{{ route('bulk-email.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Campaign Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label">Email Subject</label>
                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject"
                        name="subject" value="{{ old('subject') }}" required>
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="body" class="form-label">Email Body</label>
                    <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="10"
                        required>{{ old('body') }}</textarea>
                    @error('body')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text"> Recipient name will be automatically inserted if
                        available.</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Campaign</button>
                    <a href="{{ route('bulk-email.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
