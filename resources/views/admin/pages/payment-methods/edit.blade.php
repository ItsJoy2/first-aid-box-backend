@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Edit Payment Method: {{ $paymentMethod->name }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.payment-methods.update', $paymentMethod->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $paymentMethod->name) }}">
                    @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="account_no" class="form-label">Account Number</label>
                    <input type="text" name="account_no" id="account_no" class="form-control" value="{{ old('account_no', $paymentMethod->account_no) }}">
                    @error('account_no')<div class="text-danger">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description', $paymentMethod->description) }}</textarea>
                    @error('description')<div class="text-danger">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="status" id="status" class="form-check-input" value="1" {{ old('status', true) ? 'checked' : '' }}>
                    <label for="status" class="form-check-label">Active</label>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
