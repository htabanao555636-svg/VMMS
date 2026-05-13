<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Maintenance System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>
<body>
    <!-- Navbar -->
     @if(!Request::is('/'))
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        <div class="container-fluid px-4">
            <!-- Navbar Brand/Left Content -->
            <div class="navbar-left">
                <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                    <i class="fas fa-home"></i> Home
                </a>
                <a class="nav-link" href="#" id="aboutUsLink">About Us</a>
            </div>
        @endif
            <!-- Navbar Right Content (No Bootstrap Collapse) -->
            <div class="navbar-right ms-auto">
                @guest
                    <!-- <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a> -->
                @endguest

                @auth
                    @if (Auth::user()->role === 'customer')
                        <div class="nav-links-auth">
                            <a href="{{ route('customer.payments') }}" class="nav-auth-link">Payment History</a>
                            <a href="{{ route('customer.services') }}" class="nav-auth-link">My Services</a>
                            <a href="{{ route('customer.payables') }}" class="nav-auth-link">My Payables</a>
                        </div>

                        <div class="user-dropdown-auth" id="userDropdownAuth">
                            <div class="user-dropdown-trigger" onclick="toggleDropdown(event)">
                                <span class="username-text">Hi, {{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="user-dropdown-menu" id="userDropdownMenu" style="display:none;">
                                <a href="{{ route('customer.payments') }}" class="dropdown-menu-item">
                                    <i class="fas fa-receipt"></i> Payment History
                                </a>
                                <a href="{{ route('customer.services') }}" class="dropdown-menu-item">
                                    <i class="fas fa-tools"></i> My Services
                                </a>
                                <a href="{{ route('customer.payables') }}" class="dropdown-menu-item">
                                    <i class="fas fa-credit-card"></i> My Payables
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('auth.logout') }}" method="POST" style="margin:0;padding:0;">
                                    @csrf
                                    <button type="submit" class="dropdown-menu-item logout-item"
                                            style="width:100%;border:none;background:none;cursor:pointer;
                                                text-align:left;padding:0.75rem 1rem;color:#dc3545;
                                                display:flex;align-items:center;gap:0.75rem;">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif     
                @endauth         
            </div>               
        </div>                 
    </nav>             

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Optional Footer -->
    <footer class="bg-light text-center py-3 mt-5">
        <small>&copy; {{ date('Y') }} Vehicle Maintenance System. All rights reserved.</small>
    </footer>

    <!-- Global Modals -->

    <!-- Service Description Modal -->
    <div class="service-modal-overlay" id="serviceModalOverlay">
        <div class="service-modal-card">
            <div class="service-modal-header">
                <h3 id="serviceModalTitle"></h3>
                <button class="service-modal-close" id="serviceModalClose">&times;</button>
            </div>
            <div class="service-modal-body">
                <p id="serviceModalDescription"></p>
            </div>
        </div>
    </div>

    <!-- Service Request Form Modal -->
    <div class="request-form-overlay" id="requestFormOverlay">
        <div class="request-form-card">
            <h3 class="form-title">Fill the Service Request Form</h3>
            
            <form id="serviceRequestForm">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Vehicle Type</label>
                        <select class="form-control form-select-placeholder" name="vehicle_type" required>
                            <option value="">-- Select Vehicle Type --</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Vehicle Name</label>
                        <input type="text" class="form-control" name="vehicle_name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Owner Fullname</label>
                            <input type="text" class="form-control" name="owner_name" value="{{ Auth::user()?->name ?? '' }}" readonly>                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Vehicle Registration Number</label>
                        <input type="text" class="form-control" name="vehicle_registration" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Owner Contact #</label>
                        <input type="tel" class="form-control" name="owner_contact" value="{{ Auth::user()?->phone ?? '' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Vehicle Model</label>
                        <input type="text" class="form-control" name="vehicle_model" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Owner Email</label>
                        <input type="email" class="form-control" name="owner_email" value="{{ Auth::user()?->email ?? '' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Services *</label>
                        <div class="services-checkboxes" id="servicesContainer">
                            <div class="loading-text">Loading services...</div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" name="address" rows="4" required></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Request Type</label>
                        <select class="form-control form-select-placeholder" name="request_type" required>
                            <option value="">-- Select Request Type --</option>
                            <option value="drop-off">Drop Off</option>
                            <option value="pickup">Pickup</option>
                        </select>
                    </div>
                </div>

                <!-- Services Total -->
                <div class="form-row">
                    <div class="form-group full-width">
                        <div class="services-total-box">
                            <div class="total-row">
                                <span class="total-label">Selected Services Total:</span>
                                <span class="total-amount" id="servicesTotal">₱ 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Option Toggle -->
                <div class="form-row">
                    <div class="form-group full-width">
                        <label class="form-label">Payment Option *</label>
                        <div class="payment-option-toggle">
                            <label class="radio-item">
                                <input type="radio" name="payment_type" value="downpayment" checked onchange="togglePaymentType()">
                                <span>Downpayment</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="payment_type" value="full" onchange="togglePaymentType()">
                                <span>Full Payment</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Downpayment Section -->
                <div class="form-row" id="downpaymentSection">
                    <div class="form-group full-width">
                        <label class="form-label">Down Payment *</label>
                        <div class="downpayment-options">
                            <label class="radio-item">
                                <input type="radio" name="downpayment_percentage" value="20" data-percentage="20">
                                <span>20%</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="downpayment_percentage" value="25" data-percentage="25">
                                <span>25%</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="downpayment_percentage" value="30" data-percentage="30">
                                <span>30%</span>
                            </label>
                            <label class="radio-item">
                                <input type="radio" name="downpayment_percentage" value="50" data-percentage="50">
                                <span>50%</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="form-row">
                    <div class="form-group full-width">
                        <div class="payment-summary-box">
                            <div class="summary-row">
                                <span class="summary-label">Total Amount:</span>
                                <span class="summary-value" id="totalAmount">₱ 0.00</span>
                            </div>
                            <div class="summary-row" id="downpaymentRow">
                                <span class="summary-label">Downpayment (<span id="downpaymentPercent">0</span>%):</span>
                                <span class="summary-value" id="downpaymentAmount">₱ 0.00</span>
                            </div>
                            <div class="summary-row" id="fullPaymentRow" style="display:none;">
                                <span class="summary-label">Full Payment Amount:</span>
                                <span class="summary-value" id="fullPaymentAmount" style="color: #28a745;">₱ 0.00</span>
                            </div>
                            <div class="summary-row summary-highlight" id="balanceRow">
                                <span class="summary-label">Remaining Balance:</span>
                                <span class="summary-value" id="remainingBalance">₱ 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code Display -->
                <div class="form-row">
                    <div class="form-group full-width" style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 8px;">
                        <label class="form-label" style="display: block; margin-bottom: 15px;">
                            <i class="fas fa-qrcode"></i> Payment Tracking QR Code
                        </label>
                        <div style="display: inline-block; background: white; padding: 15px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            <p style="font-size: 12px; color: #666; margin-bottom: 10px; margin-top: 0;">
                                Screenshot or save this QR code as your payment reference
                            </p>
                            <img src="{{ asset('images/payment-qr.jpg') }}" 
                                 alt="Payment QR Code" 
                                 style="width: 250px; height: 250px; border-radius: 4px;">
                            <p style="font-size: 11px; color: #999; margin-top: 10px; margin-bottom: 0;">
                                Scan this code to track your payment status
                            </p>
                        </div>
                    </div>
                </div>

                 <!-- Proof Upload -->
                <div class="form-row">
                    <div class="form-group full-width">
                        <label class="form-label" id="proofLabel">Proof of Downpayment *</label>
                        <div class="file-upload-box">
                            <input type="file" id="proofFile" name="proof_of_payment" class="form-control" accept=".jpg,.jpeg,.png" required>
                            <small class="form-text text-muted">Accepted formats: JPG, PNG. Max size: 2MB</small>
                            <div class="file-preview" id="filePreview" style="display: none;">
                                <img id="previewImage" src="" alt="Preview" class="preview-img">
                                <button type="button" class="btn-remove-file" onclick="resetFileUpload();">✕</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                    <button type="button" class="btn btn-dark" id="closeFormBtn">Close</button>
                </div>
            </form>
        </div>
    </div>

    <!-- About Us Modal -->
    <div class="about-us-overlay" id="aboutUsOverlay">
        <div class="about-us-card">
            <div class="about-us-header">
                <h2>About Us</h2>
                <button class="about-us-close" id="aboutUsClose">&times;</button>
            </div>
            <div class="about-us-body">
                <h3>Vehicle Maintenance Management System</h3>
                <p>Welcome to our comprehensive vehicle maintenance solution. We are dedicated to providing the highest quality service for all types of vehicles.</p>
                <h4>Our Mission</h4>
                <p>To deliver exceptional vehicle maintenance services that keep your vehicles running smoothly and safely on the road.</p>
                <h4>Our Services</h4>
                <ul>
                    <li>Oil Changes</li>
                    <li>Engine Tune Up</li>
                    <li>Vehicle Overhaul</li>
                    <li>Tire Replacement</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Dropdown Script -->
    <script>
    function toggleDropdown(e) {
        e.stopPropagation();
        const menu = document.getElementById('userDropdownMenu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    document.addEventListener('click', function() {
        const menu = document.getElementById('userDropdownMenu');
        if (menu) menu.style.display = 'none';
    });
    </script>

    <script>
        {!! file_get_contents(resource_path('js/user.js')) !!}
    </script>
    </body>
    </html>