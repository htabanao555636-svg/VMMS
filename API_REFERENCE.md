# Quick API Reference - Payment Workflow

## 🚀 Quick Start

### Display QR Code
```blade
<img src="{{ $serviceRequest->getQrCodeUrl() }}" width="300" alt="Payment QR">
```

### Show Payment Status Badge
```blade
@php $helper = new \App\Helpers\PaymentStatusHelper(); @endphp
<span class="badge bg-{{ $helper->getPaymentStatusColor($status) }}">
    {{ $helper->getPaymentStatusLabel($status) }}
</span>
```

### Get Available Actions for Status
```blade
@php $actions = \App\Helpers\PaymentStatusHelper::getAvailableActions($status); @endphp
```

---

## 📦 ServiceRequest Model

### Methods
```php
$serviceRequest->getQrCodeData()      // Returns: https://app.com/payment/123
$serviceRequest->getQrCodeUrl()       // Returns: QR code image URL
$serviceRequest->generateQrCodeSvg()  // Returns: SVG string (if package installed)
```

### Properties
```php
$serviceRequest->payment_status        // ENUM: pending|verified|balance_pending|paid|rejected
$serviceRequest->proof_of_payment      // String: path to downpayment proof
$serviceRequest->full_payment_proof    // String: path to full payment proof
$serviceRequest->payment_type          // ENUM: downpayment|full
$serviceRequest->total_amount          // Decimal: total cost
$serviceRequest->downpayment_amount    // Decimal: downpayment only
$serviceRequest->remaining_balance     // Decimal: amount still due
$serviceRequest->downpayment_percentage// Int: 20|25|30|50
```

---

## 📋 PaymentStatusHelper Class

### Static Methods

#### getPaymentStatusIcon($status)
Returns Font Awesome icon class
```php
PaymentStatusHelper::getPaymentStatusIcon('downpayment_pending')  // 'hourglass-start'
PaymentStatusHelper::getPaymentStatusIcon('balance_pending')      // 'hourglass-half'
PaymentStatusHelper::getPaymentStatusIcon('fully_paid')           // 'check-double'
```

#### getPaymentStatusColor($status)
Returns Bootstrap badge color
```php
PaymentStatusHelper::getPaymentStatusColor('downpayment_pending')  // 'warning'
PaymentStatusHelper::getPaymentStatusColor('fully_paid')           // 'success'
```

#### getPaymentStatusLabel($status)
Returns human-readable label
```php
PaymentStatusHelper::getPaymentStatusLabel('downpayment_pending')  // 'Downpayment Pending'
PaymentStatusHelper::getPaymentStatusLabel('balance_pending')      // 'Balance Payment Pending'
```

#### getPaymentStatusDescription($status)
Returns description of status
```php
PaymentStatusHelper::getPaymentStatusDescription('balance_pending')
// 'Awaiting admin verification of full payment proof'
```

#### getAvailableActions($status)
Returns array of available actions
```php
PaymentStatusHelper::getAvailableActions('downpayment_pending')
// ['verify', 'reject']

PaymentStatusHelper::getAvailableActions('balance_pending')
// ['verify', 'reject']

PaymentStatusHelper::getAvailableActions('fully_paid')
// ['view']
```

#### getActionButton($action)
Returns button configuration
```php
PaymentStatusHelper::getActionButton('verify')
// ['label' => '✅ Verify', 'class' => 'btn-success', 'icon' => 'check']

PaymentStatusHelper::getActionButton('reject')
// ['label' => '❌ Reject', 'class' => 'btn-danger', 'icon' => 'times']
```

#### getNextStatus($currentStatus, $isFullPayment = false)
Returns next status in workflow
```php
PaymentStatusHelper::getNextStatus('downpayment_pending')  // 'downpayment_verified'
PaymentStatusHelper::getNextStatus('balance_pending')      // 'fully_paid'
```

---

## 🛣️ Routes

### Customer Routes

#### Upload downpayment proof
```php
Route::post('/customer/service-requests/{serviceRequest}/upload-proof')
```
**Method:** `ServiceRequestController@uploadProof`
**Params:** `proof_of_payment` (image)
**Result:** Sets payment_status = 'downpayment_pending'

#### Upload full payment proof (NEW)
```php
Route::post('/customer/service-requests/{serviceRequest}/upload-full-payment-proof')
```
**Method:** `ServiceRequestController@uploadFullPaymentProof`
**Params:** `full_payment_proof` (image)
**Result:** Sets payment_status = 'balance_pending'
**Guard:** Only if downpayment_verified

### Staff Routes

#### View billing dashboard
```php
GET /staff/billing
```

#### Verify payment
```php
POST /staff/billing/{serviceRequest}/verify
```
**Verifies:** Current proof_of_payment or full_payment_proof
**Result:** Transitions to next state (verified or fully_paid)

#### Reject payment
```php
POST /staff/billing/{serviceRequest}/reject
```
**Params:** `rejection_reason` (string)
**Result:** Sets payment_status = 'rejected' or reverts if balance_pending

---

## 💾 Billing Model

### Scopes
```php
Billing::pending()              // WHERE payment_status = 'downpayment_pending'
Billing::verified()             // WHERE payment_status = 'downpayment_verified'
Billing::balancePending()       // WHERE payment_status = 'balance_pending'
Billing::rejected()             // WHERE payment_status = 'rejected'
Billing::fullyPaid()            // WHERE payment_status = 'fully_paid'
```

### Accessors
```php
$billing->is_verified           // bool: downpayment_verified or fully_paid?
$billing->is_fully_paid         // bool: fully_paid?
$billing->has_remaining_balance // bool: remaining_balance > 0?
$billing->payment_status_label  // string: human-readable status
```

---

## 🎯 Complete Usage Example

### Display in Blade
```blade
@php
    $helper = new \App\Helpers\PaymentStatusHelper();
    $status = $serviceRequest->payment_status;
@endphp

<div class="payment-card">
    <!-- QR Code -->
    <img src="{{ $serviceRequest->getQrCodeUrl() }}" width="200">
    
    <!-- Status Badge -->
    <span class="badge bg-{{ $helper->getPaymentStatusColor($status) }}">
        <i class="fas fa-{{ $helper->getStatusIcon($status) }}"></i>
        {{ $helper->getPaymentStatusLabel($status) }}
    </span>
    
    <!-- Description -->
    <p>{{ $helper->getPaymentStatusDescription($status) }}</p>
    
    <!-- Amount Info -->
    <table>
        <tr>
            <td>Total:</td>
            <td>₱{{ number_format($serviceRequest->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td>Downpayment:</td>
            <td>₱{{ number_format($serviceRequest->downpayment_amount, 2) }}</td>
        </tr>
        <tr>
            <td>Balance:</td>
            <td>₱{{ number_format($serviceRequest->remaining_balance, 2) }}</td>
        </tr>
    </table>
    
    <!-- Upload Balance Form (if applicable) -->
    @if ($status === 'downpayment_verified' && $serviceRequest->remaining_balance > 0)
        <form method="POST" 
              action="{{ route('customer.service-request.upload-full-payment-proof', $serviceRequest) }}"
              enctype="multipart/form-data">
            @csrf
            <input type="file" name="full_payment_proof" accept="image/*" required>
            <button type="submit">Upload Payment Proof</button>
        </form>
    @endif
    
    <!-- Action Buttons -->
    @php $actions = $helper->getAvailableActions($status); @endphp
    <div class="actions">
        @foreach ($actions as $action)
            @php $btn = $helper->getActionButton($action); @endphp
            <button class="btn {{ $btn['class'] }}">
                <i class="fas fa-{{ $btn['icon'] }}"></i>
                {{ $btn['label'] }}
            </button>
        @endforeach
    </div>
</div>
```

---

## 🔍 Status Reference Table

| Status | Color | Icon | Label | Actions |
|--------|-------|------|-------|---------|
| downpayment_pending | warning | hourglass-start | Downpayment Pending | Verify, Reject |
| downpayment_verified | info | check-circle | Downpayment Verified | (collect balance) |
| balance_pending | secondary | hourglass-half | Balance Payment Pending | Verify, Reject |
| fully_paid | success | check-double | Fully Paid | View |
| rejected | danger | times-circle | Rejected | Resubmit |

---

## 🐛 Common Issues & Solutions

### QR Code Not Displaying
**Issue:** `getQrCodeUrl()` returns null
**Solution:** Ensure `APP_URL` is set in `.env`

### Payment Status Not Updating
**Issue:** Status stays as pending
**Solution:** Check that admin/staff is verifying through the correct controller method

### Cannot Upload Full Payment
**Issue:** Route returns 403
**Solution:** Verify that downpayment_status = 'downpayment_verified'

### Balance Not Collecting
**Issue:** Staff cannot collect balance
**Solution:** Customer must first upload full payment proof, triggering balance_pending state

---

## 📞 Support

For detailed implementation examples, see:
- `PAYMENT_WORKFLOW_GUIDE.md` - Full examples with HTML
- `PAYMENT_WORKFLOW_DIAGRAMS.md` - Visual flowcharts
- `IMPLEMENTATION_COMPLETE.md` - Detailed changelog
- `README_PAYMENT_WORKFLOW.md` - Quick reference

---

Generated: April 18, 2026
