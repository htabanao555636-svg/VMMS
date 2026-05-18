@extends('Layouts.user')

@section('content')
<div class="content-header">
    <h1 class="page-title">My Service Requests</h1>
</div>

<div class="content-box">
    <h2>Your Service Requests</h2>
    
    @if ($serviceRequests->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Vehicle</th>
                        <th>Services</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($serviceRequests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>{{ $request->vehicle_name }} ({{ $request->vehicle_model }})</td>
                            <td>
                                @foreach ($request->services as $service)
                                    <span class="badge bg-info">{{ $service->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge bg-{{ $request->status === 'completed' ? 'success' : ($request->status === 'pending' ? 'warning' : 'info') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>₱{{ number_format($request->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $request->payment_status === 'downpayment_verified' ? 'success' : 'warning' }}">
                                    {{ ucfirst(str_replace('_', ' ', $request->payment_status)) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($request->created_at)->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('customer.service-request.show', $request) }}" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $serviceRequests->links() }}
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <p>You have not submitted any service requests yet.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Submit a Service Request</a>
        </div>
    @endif
</div>
@endsection
