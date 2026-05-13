<style>
  /* VMMS Landing Page Styles */
  .vmms-primary { color: #1D9E75; }
  .vmms-primary-bg { background-color: #1D9E75; }
  .vmms-primary-hover:hover { color: #0F6E56; }

  .vmms-navbar {
    background-color: #fff;
    border-bottom: 1px solid #f0f0f0;
    padding: 1rem 0;
    position: sticky;
    top: 0;
    z-index: 50;
  }

  .vmms-navbar-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
  }

  .vmms-logo {
    font-size: 1.5rem;
    font-weight: 700;
    letter-spacing: -0.5px;
  }

  .vmms-logo-accent {
    color: #1D9E75;
  }

  .vmms-nav-links {
    display: flex;
    gap: 2rem;
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .vmms-nav-links a {
    text-decoration: none;
    color: #333;
    font-size: 0.95rem;
    transition: color 0.2s;
  }

  .vmms-nav-links a:hover {
    color: #1D9E75;
  }

  .vmms-nav-auth {
    display: flex;
    gap: 1rem;
    align-items: center;
  }

  .vmms-nav-text {
    font-size: 0.9rem;
    color: #555;
  }

  .vmms-btn {
    padding: 0.5rem 1.25rem;
    border: none;
    border-radius: 0.375rem;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
    transition: all 0.2s;
  }

  .vmms-btn-primary {
    background-color: #1D9E75;
    color: white;
  }

  .vmms-btn-primary:hover {
    background-color: #0F6E56;
    color: white;
  }

  .vmms-btn-secondary {
    background-color: transparent;
    color: #333;
    border: 1px solid #ddd;
  }

  .vmms-btn-secondary:hover {
    background-color: #f9f9f9;
  }

  .vmms-hero {
    padding: 5rem 1.5rem;
    text-align: center;
    max-width: 1200px;
    margin: 0 auto;
  }

  .vmms-hero-headline {
    font-size: 3rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    color: #1a1a1a;
  }

  @media (max-width: 768px) {
    .vmms-hero-headline {
      font-size: 2rem;
    }
  }

  .vmms-hero-subheading {
    font-size: 1.125rem;
    color: #666;
    margin-bottom: 3rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
  }

  .vmms-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
  }

  .vmms-stat-pill {
    background-color: #f9f9f9;
    padding: 1.5rem 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
  }

  .vmms-stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1D9E75;
    margin-bottom: 0.5rem;
  }

  .vmms-stat-label {
    font-size: 0.9rem;
    color: #666;
  }

  .vmms-hero-cta {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
  }

  .vmms-section {
    padding: 4rem 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
  }

  .vmms-section-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 3rem;
    color: #1a1a1a;
  }

  .vmms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
  }

  .vmms-card {
    background-color: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 0.5rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
  }

  .vmms-card:hover {
    border-color: #1D9E75;
    box-shadow: 0 4px 6px rgba(29, 158, 117, 0.1);
  }

  .vmms-card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: #1a1a1a;
  }

  .vmms-card-description {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 1rem;
    flex-grow: 1;
  }

  .vmms-tag {
    display: inline-block;
    background-color: #E8F5E9;
    color: #1D9E75;
    padding: 0.25rem 0.75rem;
    border-radius: 0.25rem;
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 0.75rem;
  }

  .vmms-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1D9E75;
  }

  .vmms-search-container {
    margin-bottom: 2rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
  }

  .vmms-search-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #ddd;
    border-radius: 0.375rem;
    font-size: 0.95rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
  }

  .vmms-search-input:focus {
    outline: none;
    border-color: #1D9E75;
    box-shadow: 0 0 0 3px rgba(29, 158, 117, 0.1);
  }

  .vmms-card.vmms-hidden {
    display: none;
  }

  .vmms-empty-state {
    text-align: center;
    padding: 3rem 1.5rem;
    color: #999;
  }

  .vmms-empty-state-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
  }

  .vmms-empty-state-text {
    font-size: 1.1rem;
  }

  .vmms-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 2rem;
  }

  .vmms-step {
    text-align: center;
    padding: 1.5rem;
  }

  .vmms-step-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 3rem;
    height: 3rem;
    background-color: #1D9E75;
    color: white;
    border-radius: 50%;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 auto 1.5rem;
  }

  .vmms-step-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: #1a1a1a;
  }

  .vmms-step-description {
    font-size: 0.9rem;
    color: #666;
    line-height: 1.5;
  }

  .vmms-cta-banner {
    background-color: #E1F5EE;
    padding: 4rem 1.5rem;
    text-align: center;
  }

  .vmms-cta-banner-content {
    max-width: 600px;
    margin: 0 auto;
  }

  .vmms-cta-banner-headline {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #1a1a1a;
  }

  .vmms-cta-banner-text {
    font-size: 1rem;
    color: #555;
    margin-bottom: 2rem;
  }

  .vmms-footer {
    background-color: #f9f9f9;
    border-top: 1px solid #e5e5e5;
    padding: 2rem 1.5rem;
    font-size: 0.9rem;
    color: #666;
  }

  .vmms-footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    flex-wrap: wrap;
    gap: 1rem;
  }

  @media (max-width: 768px) {
    .vmms-navbar-content {
      flex-direction: column;
      gap: 1.5rem;
    }

    .vmms-nav-links {
      gap: 1rem;
      font-size: 0.9rem;
    }

    .vmms-hero {
      padding: 3rem 1.5rem;
    }

    .vmms-hero-headline {
      font-size: 1.75rem;
    }

    .vmms-hero-cta {
      flex-direction: column;
    }

    .vmms-hero-cta .vmms-btn {
      width: 100%;
      text-align: center;
    }

    .vmms-section {
      padding: 2.5rem 1.5rem;
    }

    .vmms-section-title {
      font-size: 1.5rem;
    }

    .vmms-grid {
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }

    .vmms-footer-content {
      justify-content: center;
    }
  }
</style>

<!-- Navbar -->
<nav class="vmms-navbar">
  <div class="vmms-navbar-content">
    <div class="vmms-logo">
      VMMS <span class="vmms-logo-accent">+</span>
    </div>
    <ul class="vmms-nav-links">
      <li><a href="#services">Services</a></li>
      <li><a href="#how-it-works">How it works</a></li>
      <li><a href="#categories">Categories</a></li>
    </ul>
    <div class="vmms-nav-auth">
      @guest
        <a href="{{ route('login') }}" class="vmms-btn vmms-btn-secondary">Login</a>
        <a href="{{ route('register') }}" class="vmms-btn vmms-btn-primary">Get Started</a>
      @endguest
      @auth
        <span class="vmms-nav-text">Welcome, <strong>{{ Auth::user()->name }}</strong></span>
        <a href="{{ route('customer.dashboard') }}" class="vmms-btn vmms-btn-primary">Go to Dashboard</a>
      @endauth
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="vmms-hero">
  <h1 class="vmms-hero-headline">
    Your vehicle deserves <span class="vmms-primary">expert maintenance</span>
  </h1>
  <p class="vmms-hero-subheading">
    Professional maintenance services for your vehicle. Fast, reliable, and affordable.
  </p>

  <div class="vmms-stats">
    <div class="vmms-stat-pill">
      <div class="vmms-stat-number">500+</div>
      <div class="vmms-stat-label">Vehicles Serviced</div>
    </div>
    <div class="vmms-stat-pill">
      <div class="vmms-stat-number">98%</div>
      <div class="vmms-stat-label">Satisfaction Rate</div>
    </div>
    <div class="vmms-stat-pill">
      <div class="vmms-stat-number">24h</div>
      <div class="vmms-stat-label">Quick Turnaround</div>
    </div>
  </div>

  <div class="vmms-hero-cta">
    @guest
      <a href="{{ route('register') }}" class="vmms-btn vmms-btn-primary">Request a Service</a>
      <a href="{{ route('login') }}" class="vmms-btn vmms-btn-secondary">Login to Book</a>
    @endguest
    @auth
      <a href="{{ route('customer.dashboard') }}" class="vmms-btn vmms-btn-primary">Go to My Dashboard</a>
    @endauth
  </div>
</section>

<!-- Categories Section -->
<section id="categories" class="vmms-section">
  <h2 class="vmms-section-title">Service Categories</h2>

  @forelse ($categories as $category)
    <div class="vmms-grid">
      <div class="vmms-card">
        <div class="vmms-card-title">{{ $category->name }}</div>
        <div class="vmms-card-description">{{ $category->description }}</div>
      </div>
    @empty
    <div class="vmms-empty-state">
      <div class="vmms-empty-state-icon">📋</div>
      <div class="vmms-empty-state-text">No service categories available yet.</div>
    </div>
    @endforelse
  </div>
</section>

<!-- Services Section -->
<section id="services" class="vmms-section">
  <h2 class="vmms-section-title">Our Services</h2>

  <div class="vmms-search-container">
    <input
      type="text"
      id="vmmsServiceSearch"
      class="vmms-search-input"
      placeholder="Search services..."
      aria-label="Search services"
    />
  </div>

  @forelse ($services as $service)
    <div class="vmms-grid">
      <div class="vmms-card vmms-service-card" data-name="{{ strtolower($service->name) }}">
        <div class="vmms-tag">{{ $service->wheelerCategory->name ?? 'General' }}</div>
        <div class="vmms-card-title">{{ $service->name }}</div>
        <div class="vmms-card-description">{{ $service->description }}</div>
        <div class="vmms-price">₱{{ number_format($service->price, 2) }}</div>
      </div>
    @empty
    <div class="vmms-empty-state">
      <div class="vmms-empty-state-icon">🔧</div>
      <div class="vmms-empty-state-text">No services available yet.</div>
    </div>
    @endforelse
  </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="vmms-section">
  <h2 class="vmms-section-title">How It Works</h2>

  <div class="vmms-steps">
    <div class="vmms-step">
      <div class="vmms-step-number">1</div>
      <div class="vmms-step-title">Submit a Request</div>
      <div class="vmms-step-description">
        Fill out the service form with your vehicle details and maintenance needs.
      </div>
    </div>

    <div class="vmms-step">
      <div class="vmms-step-number">2</div>
      <div class="vmms-step-title">Choose Payment</div>
      <div class="vmms-step-description">
        Pay a downpayment (20–50%) or the full amount upfront.
      </div>
    </div>

    <div class="vmms-step">
      <div class="vmms-step-number">3</div>
      <div class="vmms-step-title">Payment Verification</div>
      <div class="vmms-step-description">
        Our admin team reviews and verifies your proof of payment.
      </div>
    </div>

    <div class="vmms-step">
      <div class="vmms-step-number">4</div>
      <div class="vmms-step-title">Drop Off or Pickup</div>
      <div class="vmms-step-description">
        Bring your vehicle in or request a pickup from your location.
      </div>
    </div>

    <div class="vmms-step">
      <div class="vmms-step-number">5</div>
      <div class="vmms-step-title">Track & Collect</div>
      <div class="vmms-step-description">
        Monitor your service progress and collect when it's complete.
      </div>
    </div>
  </div>
</section>

<!-- CTA Banner -->
<section class="vmms-cta-banner">
  <div class="vmms-cta-banner-content">
    <h2 class="vmms-cta-banner-headline">Ready to get started?</h2>
    <p class="vmms-cta-banner-text">
      Schedule your vehicle maintenance today and experience professional service.
    </p>
    @guest
      <a href="{{ route('register') }}" class="vmms-btn vmms-btn-primary">Create Your Account</a>
    @endguest
    @auth
      <a href="{{ route('customer.dashboard') }}" class="vmms-btn vmms-btn-primary">Go to Dashboard</a>
    @endauth
  </div>
</section>

<!-- Footer -->
<footer class="vmms-footer">
  <div class="vmms-footer-content">
    <div>VMMS - Vehicle Maintenance Management System</div>
    <div>Davao, Philippines · © {{ date('Y') }}</div>
  </div>
</footer>

<script>
  // Real-time service search filter
  const searchInput = document.getElementById('vmmsServiceSearch');
  const serviceCards = document.querySelectorAll('.vmms-service-card');

  if (searchInput) {
    searchInput.addEventListener('input', function (e) {
      const searchTerm = e.target.value.toLowerCase().trim();

      serviceCards.forEach(function (card) {
        const serviceName = card.getAttribute('data-name');

        if (serviceName.includes(searchTerm)) {
          card.classList.remove('vmms-hidden');
        } else {
          card.classList.add('vmms-hidden');
        }
      });
    });
  }
</script>
