@extends('layouts.admin')

@section('content')
<section id="billing-management">

    {{-- Header --}}
    <div class="billing-header">
        <div>
            <h1 class="page-title">Billing Management</h1>
            <p class="page-subtitle">Verify and manage customer payments</p>
        </div>
        <div class="summary-chips">
            <div class="chip chip-pending">
                <span class="chip-dot"></span>
                <span class="chip-label">Pending</span>
                <span class="chip-count">{{ $summary['pending'] ?? 0 }}</span>
            </div>
            <div class="chip chip-verified">
                <span class="chip-dot"></span>
                <span class="chip-label">Verified</span>
                <span class="chip-count">{{ $summary['verified'] ?? 0 }}</span>
            </div>
            <div class="chip chip-rejected">
                <span class="chip-dot"></span>
                <span class="chip-label">Rejected</span>
                <span class="chip-count">{{ $summary['rejected'] ?? 0 }}</span>
            </div>
            <div class="chip chip-collected">
                <span class="chip-dot"></span>
                <span class="chip-label">Fully Paid</span>
                <span class="chip-count">{{ $summary['fully_paid'] ?? 0 }}</span>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif

    {{-- TABS --}}
    <div class="billing-tabs">
        <button class="billing-tab active" onclick="switchTab('downpayment', this)">
            <i class="fas fa-file-invoice-dollar"></i>
            Downpayment Verification
            @if(($summary['pending'] ?? 0) > 0)
                <span class="tab-badge">{{ $summary['pending'] }}</span>
            @endif
        </button>
        <button class="billing-tab" onclick="switchTab('balance', this)">
            <i class="fas fa-hand-holding-usd"></i>
            Balance & Full Payment
            @if(($summary['verified'] ?? 0) > 0)
                <span class="tab-badge tab-badge-green">{{ $summary['verified'] }}</span>
            @endif
        </button>
        <button class="billing-tab" onclick="switchTab('fullypaid', this)">
            <i class="fas fa-check-double"></i>
            Fully Paid
            @if(($summary['fully_paid'] ?? 0) > 0)
                <span class="tab-badge tab-badge-blue">{{ $summary['fully_paid'] }}</span>
            @endif
        </button>
    </div>

    {{-- TAB 1: DOWNPAYMENT VERIFICATION --}}
    <div id="tab-downpayment" class="tab-content active">
        <div class="billing-section">
            <div class="section-title">Verify Downpayments</div>
            
            <!-- Search & Filter -->
            <div class="filter-section">
                <form method="GET" action="{{ route('staff.billing') }}" class="filter-form">
                    <input type="hidden" name="section" value="dp">
                    <div class="filter-group">
                        <input type="text" name="dp_search" placeholder="Search customer..." 
                               class="filter-input" value="{{ request('dp_search') }}">
                        <select name="dp_status" class="filter-select">
                            <option value="">All Statuses</option>
                            <option value="unpaid" {{ request('dp_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="downpayment_pending" {{ request('dp_status') === 'downpayment_pending' ? 'selected' : '' }}>Pending</option>
                            <option value="downpayment_verified" {{ request('dp_status') === 'downpayment_verified' ? 'selected' : '' }}>Verified</option>
                            <option value="rejected" {{ request('dp_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <button type="submit" class="btn-filter"><i class="fas fa-filter"></i></button>
                    </div>
                </form>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($downpaymentBillings as $billing)
                        <tr>
                            <td><strong>#{{ $billing->id }}</strong></td>
                            <td>
                                <span>{{ $billing->customer->name ?? 'N/A' }}</span><br>
                                <small class="text-muted">{{ $billing->customer->email ?? '' }}</small>
                            </td>
                            <td><strong>₱{{ number_format($billing->downpayment_amount ?? 0, 2) }}</strong></td>
                            <td>
                                @if($billing->payment_type === 'full' && $billing->payment_status === 'downpayment_pending')
                                    <span class="badge badge-primary">💰 Full Payment - Pending</span>
                                @elseif($billing->payment_type === 'downpayment' && $billing->payment_status === 'downpayment_pending')
                                    <span class="badge badge-warning">⏳ Downpayment Pending</span>
                                @else
                                    <span class="badge badge-{{ 
                                        $billing->payment_status === 'downpayment_verified' ? 'success' :
                                        ($billing->payment_status === 'rejected' ? 'danger' : 'info')
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $billing->payment_status)) }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $billing->created_at->format('M d, Y') }}</td>
                    <td>
                        @if($billing->payment_status === 'downpayment_pending')
                        <form method="POST" action="{{ route('staff.billing.verify', $billing) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-action success" title="Verify">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('staff.billing.reject', $billing) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-action danger" title="Reject" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No downpayment records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($downpaymentBillings, 'links'))
                {{ $downpaymentBillings->links('components.pagination') }}
            @endif
        </div>
    </div>

    {{-- TAB 2: BALANCE & FULL PAYMENT --}}
    <div id="tab-balance" class="tab-content">
        <div class="billing-section">
            <div class="section-title">Balance & Full Payment Collection</div>
            
            <!-- Search & Filter -->
            <div class="filter-section">
                <form method="GET" action="{{ route('staff.billing') }}" class="filter-form">
                    <input type="hidden" name="section" value="bal">
                    <div class="filter-group">
                        <input type="text" name="bal_search" placeholder="Search customer..." 
                               class="filter-input" value="{{ request('bal_search') }}">
                        <select name="bal_status" class="filter-select">
                            <option value="">All Statuses</option>
                            <option value="downpayment_verified" {{ request('bal_status') === 'downpayment_verified' ? 'selected' : '' }}>DP Verified (Balance Due)</option>
                            <option value="balance_pending"      {{ request('bal_status') === 'balance_pending'      ? 'selected' : '' }}>Balance Pending (Awaiting Verification)</option>
                            <option value="fully_paid"           {{ request('bal_status') === 'fully_paid'           ? 'selected' : '' }}>Fully Paid</option>
                        </select>
                        <button type="submit" class="btn-filter"><i class="fas fa-filter"></i></button>
                    </div>
                </form>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Customer</th>
                            <th>Downpayment</th>
                            <th>Balance</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($balanceBillings as $billing)
                        <tr>
                            <td><strong>#{{ $billing->id }}</strong></td>
                            <td>
                                <span>{{ $billing->customer->name ?? 'N/A' }}</span><br>
                                <small class="text-muted">{{ $billing->customer->email ?? '' }}</small>
                            </td>
                            <td>
                                <strong>₱{{ number_format($billing->downpayment_amount ?? 0, 2) }}</strong>
                            </td>
                            <td>
                                <strong class="amount">₱{{ number_format($billing->remaining_balance ?? 0, 2) }}</strong>
                            </td>
                            <td><strong>₱{{ number_format($billing->total_amount ?? 0, 2) }}</strong></td>
                            <td>
                                @php
                                    $ps = $billing->payment_status ?? 'unpaid';
                                @endphp
                                @switch($ps)
                                    @case('downpayment_pending')
                                        <span class="badge badge-warning">🟡 DP Pending</span>
                                    @break
                                    @case('downpayment_verified')
                                        <span class="badge badge-info">🟢 DP Verified</span>
                                    @break
                                    @case('balance_pending')
                                        <span class="badge badge-warning">⏳ Balance Pending</span>
                                    @break
                                    @case('fully_paid')
                                        <span class="badge badge-success">✅ Fully Paid</span>
                                    @break
                                    @case('rejected')
                                        <span class="badge badge-danger">❌ Rejected</span>
                                    @break
                                    @default
                                        <span class="badge badge-secondary">🔴 {{ ucfirst(str_replace('_', ' ', $ps)) }}</span>
                                @endswitch
                            </td>
                            <td>{{ $billing->created_at->format('M d, Y') }}</td>
                            <td>
                                @php
                                    $ps = $billing->payment_status ?? 'unpaid';
                                @endphp
                                @if($ps === 'downpayment_verified' && $billing->remaining_balance > 0 && !$billing->full_payment_proof)
                                    <span class="text-muted text-sm">Awaiting Customer Payment</span>
                                @elseif($ps === 'balance_pending' && $billing->full_payment_proof)
                                    <form method="POST" action="{{ route('staff.billing.collect', $billing) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-action success" title="Approve Full Payment">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                @elseif($ps === 'fully_paid')
                                    <span class="badge badge-success">Paid</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No balance collection records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($balanceBillings, 'links'))
                {{ $balanceBillings->links('components.pagination') }}
            @endif
        </div>
    </div>

    {{-- TAB 3: FULLY PAID --}}
    <div id="tab-fullypaid" class="tab-content">
        <div class="billing-section">
            <div class="section-title">Fully Paid Records</div>
            
            <!-- Search & Filter -->
            <div class="filter-section">
                <form method="GET" action="{{ route('staff.billing') }}" class="filter-form">
                    <input type="hidden" name="section" value="fp">
                    <div class="filter-group">
                        <input type="text" name="fp_search" placeholder="Search customer..." 
                               class="filter-input" value="{{ request('fp_search') }}">
                        <button type="submit" class="btn-filter"><i class="fas fa-filter"></i></button>
                    </div>
                </form>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Paid Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fullyPaidBillings as $billing)
                        <tr>
                            <td><strong>#{{ $billing->id }}</strong></td>
                            <td>
                                <span>{{ $billing->customer->name ?? 'N/A' }}</span><br>
                                <small class="text-muted">{{ $billing->customer->email ?? '' }}</small>
                            </td>
                            <td><strong>₱{{ number_format($billing->total_amount ?? 0, 2) }}</strong></td>
                            <td>
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Fully Paid
                                </span>
                            </td>
                            <td>{{ $billing->updated_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No fully paid records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($fullyPaidBillings, 'links'))
                {{ $fullyPaidBillings->links('components.pagination') }}
            @endif
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
function switchTab(tabName, button) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.billing-tab').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById('tab-' + tabName).classList.add('active');
    button.classList.add('active');
}
</script>
@endsection
