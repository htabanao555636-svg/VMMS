@extends('Layouts.user')

@section('content')
<div class="content-header">
    <h1 class="page-title">Service Request #{{ $serviceRequest->id }}</h1>
    <a href="{{ route('customer.service-request') }}" class="btn btn-secondary">Back to Requests</a>
</div>

<div class="content-box">
    <!-- QR Code Section -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Payment Tracking QR Code</h5>
                    <img src="{{ $serviceRequest->getQrCodeUrl() }}" alt="Payment QR Code" width="150" class="img-fluid mb-2">
                    <small class="text-muted d-block">Scan to track payment status</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Details -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h3>Vehicle Information</h3>
            <ul class="list-unstyled">
                <li><strong>Vehicle Name:</strong> {{ $serviceRequest->vehicle_name }}</li>
                <li><strong>Model:</strong> {{ $serviceRequest->vehicle_model }}</li>
                <li><strong>Type:</strong> {{ $serviceRequest->vehicle_type }}</li>
                <li><strong>Registration:</strong> {{ $serviceRequest->vehicle_registration }}</li>
                <li><strong>Address:</strong> {{ $serviceRequest->address }}</li>
                <li><strong>Request Type:</strong> {{ ucfirst($serviceRequest->request_type) }}</li>
            </ul>
        </div>
        <div class="col-md-6">
            <h3>Request Status</h3>
            @php
                $helper = new \App\Helpers\PaymentStatusHelper();
                $paymentStatusColor = $helper->getPaymentStatusColor($serviceRequest->payment_status);
                $paymentStatusLabel = $helper->getPaymentStatusLabel($serviceRequest->payment_status);
                $paymentStatusIcon = $helper->getPaymentStatusIcon($serviceRequest->payment_status);
            @endphp
            <ul class="list-unstyled">
                <li>
                    <strong>Service Status:</strong>
                    <span class="badge bg-{{ $serviceRequest->status === 'completed' ? 'success' : ($serviceRequest->status === 'pending' ? 'warning' : ($serviceRequest->status === 'cancelled' ? 'danger' : 'info')) }}">
                        <i class="fas fa-{{ $serviceRequest->status === 'completed' ? 'check-circle' : ($serviceRequest->status === 'pending' ? 'hourglass-start' : 'spinner') }}"></i>
                        {{ ucfirst($serviceRequest->status) }}
                    </span>
                </li>
                <li>
                    <strong>Payment Status:</strong>
                    <span class="badge bg-{{ $paymentStatusColor }}">
                        <i class="fas fa-{{ $paymentStatusIcon }}"></i>
                        {{ $paymentStatusLabel }}
                    </span>
                </li>
                <li><strong>Requested Date:</strong> {{ \Carbon\Carbon::parse($serviceRequest->created_at)->format('M d, Y') }}</li>
                @if ($serviceRequest->completed_date)
                    <li><strong>Completed Date:</strong> {{ \Carbon\Carbon::parse($serviceRequest->completed_date)->format('M d, Y') }}</li>
                @endif
                @if ($serviceRequest->mechanic)
                    <li><strong>Assigned Mechanic:</strong> {{ $serviceRequest->mechanic->name }}</li>
                @endif
            </ul>
        </div>
    </div>

    <!-- Services -->
    <div class="row mb-4">
        <div class="col-12">
            <h3>Services</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($serviceRequest->services as $service)
                        <tr>
                            <td>{{ $service->name }}</td>
                            <td>₱{{ number_format($service->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h3>Payment Information</h3>
            <ul class="list-unstyled">
                <li><strong>Total Amount:</strong> ₱{{ number_format($serviceRequest->total_amount, 2) }}</li>
                <li><strong>Downpayment Amount:</strong> ₱{{ number_format($serviceRequest->downpayment_amount, 2) }}</li>
                <li><strong>Downpayment %:</strong> {{ $serviceRequest->downpayment_percentage }}%</li>
                <li><strong>Remaining Balance:</strong> <strong class="text-danger">₱{{ number_format($serviceRequest->remaining_balance, 2) }}</strong></li>
            </ul>
        </div>
        <div class="col-md-6">
            <h3>Proof of Payment</h3>
            @if ($serviceRequest->proof_of_payment)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $serviceRequest->proof_of_payment) }}" alt="Proof of Payment" class="img-fluid" style="max-width: 300px;">
                </div>
            @else
                <p class="text-muted">No proof of payment uploaded yet.</p>
            @endif

            @if ($serviceRequest->payment_status === 'rejected')
                <div class="alert alert-danger">
                    <strong>Payment Rejected</strong>
                    @if ($serviceRequest->staff_notes)
                        <p>{{ $serviceRequest->staff_notes }}</p>
                    @endif
                </div>
                <form action="{{ route('customer.payments.resubmit', $serviceRequest) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    <div class="form-group">
                        <label for="proof_of_payment">Upload New Proof of Payment</label>
                        <input type="file" class="form-control @error('proof_of_payment') is-invalid @enderror" id="proof_of_payment" name="proof_of_payment" required accept="image/*">
                        @error('proof_of_payment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Resubmit Proof</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Balance Payment Section - Show when service is completed and has balance pending -->
    @if ($serviceRequest->status === 'completed' && $serviceRequest->payment_status === 'downpayment_verified' && $serviceRequest->remaining_balance > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-credit-card"></i> Pay Remaining Balance
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>Your service has been completed! Please pay the remaining balance:</p>
                        
                        <div class="alert alert-info">
                            <strong>Amount Due:</strong> ₱{{ number_format($serviceRequest->remaining_balance, 2) }}
                        </div>

                        <form action="{{ route('customer.service-request.upload-full-payment-proof', $serviceRequest) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="full_payment_proof" class="form-label">Upload Payment Proof</label>
                                <input type="file" class="form-control @error('full_payment_proof') is-invalid @enderror" id="full_payment_proof" name="full_payment_proof" required accept="image/*">
                                <small class="text-muted">JPG or PNG, max 2MB</small>
                                @error('full_payment_proof')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload"></i> Upload Payment Proof
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($serviceRequest->status === 'completed' && $serviceRequest->payment_status === 'balance_pending')
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-info">
                    <div class="card-header bg-info">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-hourglass-half"></i> Balance Payment Pending Verification
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>Your balance payment proof has been received and is awaiting admin verification.</p>
                        @if ($serviceRequest->full_payment_proof)
                            <div>
                                <strong>Submitted Proof:</strong>
                                <img src="{{ asset('storage/' . $serviceRequest->full_payment_proof) }}" alt="Full Payment Proof" class="img-fluid" style="max-width: 300px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @elseif ($serviceRequest->status === 'completed' && $serviceRequest->payment_status === 'fully_paid')
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <strong>Payment Complete!</strong> Your service and payment are fully completed.
                </div>
            </div>
        </div>
    @endif

    <!-- Notes -->
    @if ($serviceRequest->notes || $serviceRequest->staff_notes)
        <div class="row">
            <div class="col-12">
                <h3>Notes</h3>
                @if ($serviceRequest->notes)
                    <div class="mb-2">
                        <strong>Your Notes:</strong>
                        <p>{{ $serviceRequest->notes }}</p>
                    </div>
                @endif
                @if ($serviceRequest->staff_notes)
                    <div class="alert alert-info">
                        <strong>Staff Notes:</strong>
                        <p>{{ $serviceRequest->staff_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
            </ul>
        </div>
        <div class="col-md-6">
            <h3>Proof of Payment</h3>
            @if ($serviceRequest->proof_of_payment)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $serviceRequest->proof_of_payment) }}" alt="Proof of Payment" class="img-fluid" style="max-width: 300px;">
                </div>
            @else
                <p class="text-muted">No proof of payment uploaded yet.</p>
            @endif

            @if ($serviceRequest->payment_status === 'downpayment_rejected')
                <div class="alert alert-danger">
                    <strong>Payment Rejected</strong>
                    @if ($serviceRequest->staff_notes)
                        <p>{{ $serviceRequest->staff_notes }}</p>
                    @endif
                </div>
                <form action="{{ route('customer.payments.resubmit', $serviceRequest) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    <div class="form-group">
                        <label for="proof_of_payment">Upload New Proof of Payment</label>
                        <input type="file" class="form-control @error('proof_of_payment') is-invalid @enderror" id="proof_of_payment" name="proof_of_payment" required accept="image/*">
                        @error('proof_of_payment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Resubmit Proof</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Notes -->
    @if ($serviceRequest->notes || $serviceRequest->staff_notes)
        <div class="row">
            <div class="col-12">
                <h3>Notes</h3>
                @if ($serviceRequest->notes)
                    <div class="mb-2">
                        <strong>Your Notes:</strong>
                        <p>{{ $serviceRequest->notes }}</p>
                    </div>
                @endif
                @if ($serviceRequest->staff_notes)
                    <div class="alert alert-info">
                        <strong>Staff Notes:</strong>
                        <p>{{ $serviceRequest->staff_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
