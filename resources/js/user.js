/**
 * Vehicle Maintenance Management System
 * user.js - Comprehensive UI integration with form validation, payment calculations, and request management
 */

document.addEventListener('DOMContentLoaded', () => {
  console.log('🚀 VMMS Frontend Initialization Started...');

  // ============================================================================
  // CONFIG & CONSTANTS
  // ============================================================================
  const CONFIG = {
    MAX_FILE_SIZE: 2 * 1024 * 1024, // 2MB
    ALLOWED_FILE_TYPES: ['image/jpeg', 'image/png'],
    ALLOWED_FILE_EXTENSIONS: ['.jpg', '.jpeg', '.png'],
    API_ENDPOINTS: {
      SERVICES: '/api/services',
      WHEELER_CATEGORIES: '/api/wheeler-categories',
      SUBMIT_REQUEST: '/service-request'
    }
  };

  // ============================================================================
  // UTILITY FUNCTIONS
  // ============================================================================

  function showNotification(message, type = 'error') {
    console.log(`[${type.toUpperCase()}]`, message);
    if (type === 'success') {
      alert(`✅ ${message}`);
    } else if (type === 'error') {
      alert(`❌ ${message}`);
    } else if (type === 'info') {
      alert(`ℹ️ ${message}`);
    }
  }

  function toggleBodyScroll(disabled = true) {
    document.body.style.overflow = disabled ? 'hidden' : '';
  }

  function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  function formatCurrency(amount) {
    return `₱ ${parseFloat(amount).toFixed(2)}`;
  }

  function getFormFieldValue(fieldName) {
    const field = document.querySelector(`[name="${fieldName}"]`);
    return field ? field.value : '';
  }

  function setFormFieldValue(fieldName, value) {
    const field = document.querySelector(`[name="${fieldName}"]`);
    if (field) field.value = value;
  }

  // ============================================================================
  // MODAL MANAGEMENT
  // ============================================================================

  function setupModalOverlay(triggerId, overlayId, closeId, onOpen = null) {
    const trigger = document.querySelector(`#${triggerId}`);
    const overlay = document.querySelector(`#${overlayId}`);
    const closeBtn = document.querySelector(`#${closeId}`);

    if (!trigger || !overlay || !closeBtn) {
      console.warn(`⚠️ Modal elements not found for: ${triggerId}`);
      return null;
    }

    function openModal() {
      console.log(`📖 Opening modal: ${overlayId}`);
      overlay.classList.add('show');
      toggleBodyScroll(true);
      if (typeof onOpen === 'function') onOpen();
    }

    function closeModal() {
      console.log(`📖 Closing modal: ${overlayId}`);
      overlay.classList.remove('show');
      toggleBodyScroll(false);
    }

    trigger.addEventListener('click', (e) => {
      if (e.preventDefault) e.preventDefault();
      openModal();
    });

    closeBtn.addEventListener('click', closeModal);

    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) closeModal();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && overlay.classList.contains('show')) {
        closeModal();
      }
    });

    return { openModal, closeModal, overlay, trigger };
  }

  // ============================================================================
  // ABOUT US MODAL
  // ============================================================================

  setupModalOverlay('aboutUsLink', 'aboutUsOverlay', 'aboutUsClose');
  console.log('✅ About Us modal initialized');

  // ============================================================================
  // SERVICE DESCRIPTION MODAL
  // ============================================================================

  const serviceModal = setupModalOverlay(
    'serviceModalOverlay',
    'serviceModalOverlay',
    'serviceModalClose'
  );

  if (serviceModal) {
    const kpiBoxes = document.querySelectorAll('.kpi-box');
    const modalTitle = document.querySelector('#serviceModalTitle');
    const modalDescription = document.querySelector('#serviceModalDescription');

    if (kpiBoxes.length > 0) {
      kpiBoxes.forEach((box) => {
        box.style.cursor = 'pointer';
        box.addEventListener('click', () => {
          const service = box.dataset.service || 'Service';
          const description = box.dataset.description || 'No description available';
          
          if (modalTitle) modalTitle.textContent = service;
          if (modalDescription) modalDescription.textContent = description;
          
          serviceModal.openModal();
        });
      });
      console.log(`✅ Service description modal initialized (${kpiBoxes.length} services)`);
    }
  }

  // ============================================================================
  // SELECT PLACEHOLDER STYLING
  // ============================================================================

  const selectPlaceholders = document.querySelectorAll('.form-select-placeholder');

  function updateSelectColor(select) {
    select.style.color = select.value === '' ? 'var(--text-light)' : 'var(--text)';
  }

  selectPlaceholders.forEach(select => {
    updateSelectColor(select);
    select.addEventListener('change', function () {
      updateSelectColor(this);
    });
  });

  if (selectPlaceholders.length > 0) {
    console.log(`✅ Select placeholder styling initialized`);
  }

  // ============================================================================
  // SERVICE REQUEST FORM - MAIN MODULE
  // ============================================================================

  const FORM_ELEMENTS = {
    form: document.querySelector('#serviceRequestForm'),
    overlay: document.querySelector('#requestFormOverlay'),
    openBtn: document.querySelector('#sendRequestBtn'),
    closeBtn: document.querySelector('#closeFormBtn'),
    submitBtn: null, // Will reference element from form

    // Services
    servicesContainer: document.querySelector('#servicesContainer'),
    servicesTotal: document.querySelector('#servicesTotal'),

    // Payment fields
    totalAmount: document.querySelector('#totalAmount'),
    downpaymentAmount: document.querySelector('#downpaymentAmount'),
    remainingBalance: document.querySelector('#remainingBalance'),
    fullPaymentAmount: document.querySelector('#fullPaymentAmount'),
    downpaymentPercent: document.querySelector('#downpaymentPercent'),
    proofLabel: document.querySelector('#proofLabel'),
    downpaymentRow: document.querySelector('#downpaymentRow'),
    balanceRow: document.querySelector('#balanceRow'),
    fullPaymentRow: document.querySelector('#fullPaymentRow'),

    // File upload
    proofFileInput: document.querySelector('#proofFile'),
    filePreview: document.querySelector('#filePreview'),
    previewImage: document.querySelector('#previewImage'),

    // Form fields (for reference)
    vehicleType: null,
    vehicleName: null,
    ownerName: null,
    ownerEmail: null,
    ownerContact: null,
    vehicleModel: null,
    vehicleRegistration: null,
    address: null,
    requestType: null,
    downpaymentPercentage: null
  };

  if (!FORM_ELEMENTS.form || !FORM_ELEMENTS.overlay) {
    console.error('❌ Service request form elements not found. Halting form initialization.');
  } else {
    console.log('✅ Form elements found. Initializing service request form...');

    // ========================================================================
    // SERVICE LOADING
    // ========================================================================

    async function loadServices(wheelerCategoryId = null) {
  try {
    if (FORM_ELEMENTS.servicesContainer) {
      FORM_ELEMENTS.servicesContainer.innerHTML = '<div class="loading-text">Loading services...</div>';
    }

    const endpoint = wheelerCategoryId
      ? `/api/services/by-category/${wheelerCategoryId}`
      : CONFIG.API_ENDPOINTS.SERVICES;

    console.log('🔄 Fetching services from:', endpoint);
    const response = await fetch(endpoint);

    if (!response.ok) {
      throw new Error(`HTTP Error: ${response.status}`);
    }

    const data = await response.json();

    if (!data.success || !Array.isArray(data.data) || data.data.length === 0) {
      if (FORM_ELEMENTS.servicesContainer) {
        FORM_ELEMENTS.servicesContainer.innerHTML = wheelerCategoryId
          ? '<p class="text-danger">No services available for this vehicle type.</p>'
          : '<p class="text-danger">No services available at the moment.</p>';
      }
      console.warn('⚠️ No services available');
      updatePaymentSummary(); // reset totals to ₱0 when list clears
      return;
    }

    const services = data.data;
    console.log(`✅ Loaded ${services.length} services`);

    if (FORM_ELEMENTS.servicesContainer) {
      FORM_ELEMENTS.servicesContainer.innerHTML = services
        .map(service => `
          <label class="checkbox-item">
            <input 
              type="checkbox" 
              name="services[]" 
              value="${service.id}" 
              data-price="${service.price}" 
              class="service-checkbox"
              data-service-name="${service.name}"
            >
            <span>${service.name} - ${formatCurrency(service.price)}</span>
          </label>
        `)
        .join('');

      const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
      serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
          updatePaymentSummary();
          logSelectedServices();
        });
      });

      updatePaymentSummary(); // reset totals after checkboxes re-render
    }
  } catch (error) {
    console.error(' Error loading services:', error);
    if (FORM_ELEMENTS.servicesContainer) {
      FORM_ELEMENTS.servicesContainer.innerHTML = '<p class="text-danger">Failed to load services. Please refresh and try again.</p>';
    }
    showNotification('Failed to load services. Please refresh the page.', 'error');
  }
}

// ─── attach vehicle type → services filter ───────────────────────────────────
function attachVehicleTypeListener() {
  const vehicleTypeSelect = document.querySelector('select[name="vehicle_type"]');
  if (!vehicleTypeSelect) return;

  vehicleTypeSelect.addEventListener('change', function () {
    const selectedId = this.value || null;
    console.log(`Vehicle type changed → category id: ${selectedId}`);
    if (selectedId) {
      loadServices(selectedId);
    } else {
      loadServices(null); // load all services if no category selected
    }
  });

  console.log('Vehicle type filter listener attached');
}

    function logSelectedServices() {
      const selected = document.querySelectorAll('.service-checkbox:checked');
      const services = Array.from(selected).map(cb => ({
        id: cb.value,
        name: cb.dataset.serviceName,
        price: parseFloat(cb.dataset.price)
      }));
      console.log('📋 Selected Services:', services);
    }

    // ========================================================================
    // WHEELER CATEGORIES LOADING
    // ========================================================================

    async function loadWheelerCategories() {
      try {
        const vehicleTypeSelect = document.querySelector('select[name="vehicle_type"]');
        if (!vehicleTypeSelect) {
          console.warn('⚠️ Vehicle type select not found');
          return;
        }

        console.log('🔄 Fetching wheeler categories from API...');
        const response = await fetch(CONFIG.API_ENDPOINTS.WHEELER_CATEGORIES);

        if (!response.ok) {
          throw new Error(`HTTP Error: ${response.status}`);
        }

        const data = await response.json();
        
        if (!data.success || !Array.isArray(data.data) || data.data.length === 0) {
          console.warn('⚠️ No wheeler categories available');
          return;
        }

        console.log(`✅ Loaded ${data.data.length} wheeler categories`);

        // Clear existing options except the placeholder
        const options = vehicleTypeSelect.querySelectorAll('option');
        options.forEach(option => {
          if (option.value !== '') {
            option.remove();
          }
        });

        // Add new options from API
        data.data.forEach(category => {
          const option = document.createElement('option');
          option.value = category.id;
          option.textContent = category.name;
          option.title = category.description || category.name;
          vehicleTypeSelect.appendChild(option);
        });

        console.log('✅ Vehicle type dropdown populated dynamically');
      } catch (error) {
        console.error('❌ Error loading wheeler categories:', error);
        console.warn('⚠️ Wheeler categories will remain as default options');
      }
    }

    // ========================================================================
    // PAYMENT SUMMARY CALCULATIONS
    // ========================================================================

    function updatePaymentSummary() {
      const selectedServices = document.querySelectorAll('.service-checkbox:checked');
      const downpaymentRadio = document.querySelector('input[name="downpayment_percentage"]:checked');
      const isFull = document.querySelector('input[name="payment_type"][value="full"]')?.checked;

      // Calculate total
      let total = 0;
      selectedServices.forEach(checkbox => {
        total += parseFloat(checkbox.dataset.price);
      });

      // Update services total
      if (FORM_ELEMENTS.servicesTotal) {
        FORM_ELEMENTS.servicesTotal.textContent = formatCurrency(total);
      }
      if (FORM_ELEMENTS.totalAmount) {
        FORM_ELEMENTS.totalAmount.textContent = formatCurrency(total);
      }

      if (isFull) {
        // Full payment mode
        if (FORM_ELEMENTS.downpaymentPercent) {
          FORM_ELEMENTS.downpaymentPercent.textContent = '100';
        }
        if (FORM_ELEMENTS.downpaymentAmount) {
          FORM_ELEMENTS.downpaymentAmount.textContent = formatCurrency(total);
        }
        if (FORM_ELEMENTS.fullPaymentAmount) {
          FORM_ELEMENTS.fullPaymentAmount.textContent = formatCurrency(total);
        }
        if (FORM_ELEMENTS.remainingBalance) {
          FORM_ELEMENTS.remainingBalance.textContent = formatCurrency(0);
        }
        console.log(`💰 Payment Summary (FULL): Total=${formatCurrency(total)}, Remaining=₱ 0.00`);
      } else if (downpaymentRadio) {
        // Downpayment mode
        const percentage = parseFloat(downpaymentRadio.value);
        if (FORM_ELEMENTS.downpaymentPercent) {
          FORM_ELEMENTS.downpaymentPercent.textContent = percentage;
        }

        const downpayment = total * (percentage / 100);
        const remaining = total - downpayment;

        if (FORM_ELEMENTS.downpaymentAmount) {
          FORM_ELEMENTS.downpaymentAmount.textContent = formatCurrency(downpayment);
        }
        if (FORM_ELEMENTS.remainingBalance) {
          FORM_ELEMENTS.remainingBalance.textContent = formatCurrency(remaining);
        }

        console.log(`💰 Payment Summary: Total=${formatCurrency(total)}, Downpayment=${formatCurrency(downpayment)}, Remaining=${formatCurrency(remaining)}`);
      } else {
        if (FORM_ELEMENTS.downpaymentPercent) {
          FORM_ELEMENTS.downpaymentPercent.textContent = '0';
        }
        if (FORM_ELEMENTS.downpaymentAmount) {
          FORM_ELEMENTS.downpaymentAmount.textContent = formatCurrency(0);
        }
        if (FORM_ELEMENTS.remainingBalance) {
          FORM_ELEMENTS.remainingBalance.textContent = formatCurrency(total);
        }
      }
    }

    // Toggle payment type (downpayment vs full)
    window.togglePaymentType = function() {
      const isFull = document.querySelector('input[name="payment_type"][value="full"]').checked;
      const downpaymentSection = document.getElementById('downpaymentSection');
      const downpaymentRow = document.getElementById('downpaymentRow');
      const balanceRow = document.getElementById('balanceRow');
      const fullPaymentRow = document.getElementById('fullPaymentRow');
      const proofLabel = document.getElementById('proofLabel');
      const downpaymentRadios = document.querySelectorAll('input[name="downpayment_percentage"]');

      if (isFull) {
        // Switch to full payment
        downpaymentSection.style.display = 'none';
        downpaymentRow.style.display = 'none';
        balanceRow.style.display = 'none';
        fullPaymentRow.style.display = 'flex';
        proofLabel.textContent = 'Proof of Full Payment *';

        // Remove required from downpayment radios
        downpaymentRadios.forEach(r => r.required = false);

        // Uncheck all downpayment radios
        downpaymentRadios.forEach(r => r.checked = false);
      } else {
        // Switch to downpayment
        downpaymentSection.style.display = 'block';
        downpaymentRow.style.display = 'flex';
        balanceRow.style.display = 'flex';
        fullPaymentRow.style.display = 'none';
        proofLabel.textContent = 'Proof of Downpayment *';
      }

      // Recalculate payment summary
      updatePaymentSummary();
    };

    // Attach payment calculation listeners
    const downpaymentRadios = document.querySelectorAll('input[name="downpayment_percentage"]');
    downpaymentRadios.forEach(radio => {
      radio.addEventListener('change', updatePaymentSummary);
    });

    // Attach payment type listeners
    const paymentTypeRadios = document.querySelectorAll('input[name="payment_type"]');
    paymentTypeRadios.forEach(radio => {
      radio.addEventListener('change', updatePaymentSummary);
    });

    // ========================================================================
    // FILE UPLOAD HANDLING
    // ========================================================================

    function validateFile(file) {
      // Check file type
      if (!CONFIG.ALLOWED_FILE_TYPES.includes(file.type)) {
        showNotification(`Invalid file type. Allowed: ${CONFIG.ALLOWED_FILE_EXTENSIONS.join(', ')}`, 'error');
        return false;
      }

      // Check file size
      if (file.size > CONFIG.MAX_FILE_SIZE) {
        showNotification(`File size exceeds 2MB limit. Current: ${(file.size / 1024 / 1024).toFixed(2)}MB`, 'error');
        return false;
      }

      return true;
    }

    function displayFilePreview(file) {
      const reader = new FileReader();
      reader.onload = (event) => {
        if (FORM_ELEMENTS.previewImage) {
          FORM_ELEMENTS.previewImage.src = event.target.result;
          if (FORM_ELEMENTS.filePreview) {
            FORM_ELEMENTS.filePreview.style.display = 'flex';
          }
        }
      };
      reader.readAsDataURL(file);
    }

    if (FORM_ELEMENTS.proofFileInput) {
      FORM_ELEMENTS.proofFileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
          if (validateFile(file)) {
            displayFilePreview(file);
            console.log(`📎 File selected: ${file.name} (${(file.size / 1024).toFixed(2)}KB)`);
          } else {
            FORM_ELEMENTS.proofFileInput.value = '';
          }
        }
      });
    }

    // Reset file upload function
    window.resetFileUpload = function() {
      if (FORM_ELEMENTS.proofFileInput) {
        FORM_ELEMENTS.proofFileInput.value = '';
      }
      if (FORM_ELEMENTS.filePreview) {
        FORM_ELEMENTS.filePreview.style.display = 'none';
        if (FORM_ELEMENTS.previewImage) {
          FORM_ELEMENTS.previewImage.src = '';
        }
      }
      console.log('🔄 File upload reset');
    };

    // ========================================================================
    // FORM VALIDATION
    // ========================================================================

    function validateFormData() {
      const errors = [];

      // Required text fields
      const vehicleType = getFormFieldValue('vehicle_type');
      const vehicleName = getFormFieldValue('vehicle_name');
      const ownerName = getFormFieldValue('owner_name');
      const vehicleRegistration = getFormFieldValue('vehicle_registration');
      const ownerContact = getFormFieldValue('owner_contact');
      const vehicleModel = getFormFieldValue('vehicle_model');
      const ownerEmail = getFormFieldValue('owner_email');
      const address = getFormFieldValue('address');
      const requestType = getFormFieldValue('request_type');

      if (!vehicleType) errors.push('Vehicle Type is required');
      if (!vehicleName) errors.push('Vehicle Name is required');
      if (!ownerName) errors.push('Owner Name is required');
      if (!vehicleRegistration) errors.push('Vehicle Registration Number is required');
      if (!ownerContact) errors.push('Owner Contact is required');
      if (!vehicleModel) errors.push('Vehicle Model is required');
      if (!address) errors.push('Address is required');
      if (!requestType) errors.push('Request Type is required');

      // Email validation
      if (!ownerEmail) {
        errors.push('Owner Email is required');
      } else if (!validateEmail(ownerEmail)) {
        errors.push('Invalid email format');
      }

      // Service selection
      const selectedServices = document.querySelectorAll('input[name="services[]"]:checked');
      if (selectedServices.length === 0) {
        errors.push('Please select at least one service');
      }

      // Check payment type
      const isFull = document.querySelector('input[name="payment_type"][value="full"]')?.checked;

      // Downpayment selection - only required if NOT full payment
      if (!isFull) {
        const downpaymentRadio = document.querySelector('input[name="downpayment_percentage"]:checked');
        if (!downpaymentRadio) {
          errors.push('Please select a downpayment percentage');
        }
      }

      // File validation
      const proofFile = document.querySelector('input[name="proof_of_payment"]');
      if (!proofFile || !proofFile.files.length) {
        errors.push('Proof of Payment is required');
      }

      return {
        isValid: errors.length === 0,
        errors: errors
      };
    }

    // ========================================================================
    // FORM SUBMISSION
    // ========================================================================

    FORM_ELEMENTS.form.addEventListener('submit', async (e) => {
      e.preventDefault();
      console.log('📤 Form submission initiated...');

      // Validate form
      const validation = validateFormData();
      if (!validation.isValid) {
        const errorMessage = validation.errors.join('\n');
        console.error('❌ Validation failed:\n', validation.errors);
        showNotification(`Validation Error:\n\n${errorMessage}`, 'error');
        return;
      }

      // Create FormData for file upload
      const formData = new FormData(FORM_ELEMENTS.form);

      // Show loading state
      const submitButton = FORM_ELEMENTS.form.querySelector('button[type="submit"]');
      if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = '⏳ Submitting...';
      }

      try {
        console.log('🚀 Sending request to server...');
        const response = await fetch(CONFIG.API_ENDPOINTS.SUBMIT_REQUEST, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
              },
                credentials: 'include',
                body: formData,
            });

        const data = await response.json();

        if (response.ok && data.success) {
          console.log('✅ Request submitted successfully!', data);
          
          // Show success message with request ID
          const requestId = data.service_request_id || 'N/A';
          showNotification(
            `Service request submitted successfully!\n\nRequest ID: ${requestId}\n\nYou will receive updates via email.`,
            'success'
          );

          // Reset form and close modal
          FORM_ELEMENTS.form.reset();
          resetFileUpload();
          updatePaymentSummary();

          // Close form modal
          if (FORM_ELEMENTS.overlay) {
            FORM_ELEMENTS.overlay.classList.remove('show');
            toggleBodyScroll(false);
          }

          console.log(`✅ Form reset and modal closed. Request ID: ${requestId}`);

          // Redirect to My Services page after 1.5 seconds
          setTimeout(() => {
            window.location.href = '/customer/services';
          }, 1500);
        } else {
          const errorMessage = data.message || 'Failed to submit request. Please try again.';
          console.error('❌ Server error:', data);
          showNotification(errorMessage, 'error');
        }
      } catch (error) {
        console.error('❌ Network error during form submission:', error);
        showNotification('Network error. Please check your connection and try again.', 'error');
      } finally {
        // Restore submit button
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.textContent = 'Submit Request';
        }
      }
    });

    // ========================================================================
    // FORM MODAL MANAGEMENT
    // ========================================================================

    async function openRequestForm() {
      console.log('📖 Opening service request form');
      if (FORM_ELEMENTS.overlay) {
        FORM_ELEMENTS.overlay.classList.add('show');
      }
      toggleBodyScroll(true);

      await loadWheelerCategories(); // wait for dropdown to populate first
      attachVehicleTypeListener();   // then attach the change listener
      
      loadServices();
      loadWheelerCategories();
    }

    function closeRequestForm() {
      console.log('📖 Closing service request form');
      if (FORM_ELEMENTS.overlay) {
        FORM_ELEMENTS.overlay.classList.remove('show');
      }
      toggleBodyScroll(false);
    }

    if (FORM_ELEMENTS.openBtn) {
      FORM_ELEMENTS.openBtn.addEventListener('click', openRequestForm);
    }

    if (FORM_ELEMENTS.closeBtn) {
      FORM_ELEMENTS.closeBtn.addEventListener('click', closeRequestForm);
    }

    if (FORM_ELEMENTS.overlay) {
      FORM_ELEMENTS.overlay.addEventListener('click', (e) => {
        if (e.target === FORM_ELEMENTS.overlay) {
          closeRequestForm();
        }
      });
    }

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && FORM_ELEMENTS.overlay && FORM_ELEMENTS.overlay.classList.contains('show')) {
        closeRequestForm();
      }
    });

    console.log('✅ Service request form initialized successfully');
  }

  console.log('🎉 VMMS Frontend Initialization Complete!');
});