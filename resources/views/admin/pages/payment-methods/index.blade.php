


@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Payment Methods Management</h3>
            <div class="card-tools">
                <a href="{{ route('admin.payment-methods.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Payment Methods
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('admin.layouts.partials.__alerts')

            <table class="table table-striped table-hover table-head-bg-primary mt-4">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Account No</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentMethods as $method)
                    <tr>
                        <td>{{ $method->name }}</td>
                        <td>{{ $method->account_no }}</td>
                        <td>{{ $method->description }}</td>
                        <td>
                             <span class="badge {{ $method->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $method->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            @include('admin.pages.payment-methods.partials.__actions')
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No Payment Methods found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $paymentMethods->links('admin.layouts.partials.__pagination') }}
        </div>
    </div>
</div>
@endsection
