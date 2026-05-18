<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  /* ── RESPONSIVE NAVBAR ── */
  .vmms-navbar {
    position: sticky;
    top: 0;
    z-index: 999;
    background-color: #fff;
    border-bottom: 1px solid #e5e7eb;
    height: 62px;
    display: flex;
    align-items: center;
    padding: 0 2rem;
  }

  .vmms-navbar-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    position: relative;
  }

  .vmms-nav-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    gap: 1rem;
    position: relative;
  }

  .vmms-logo {
    font-size: 1.25rem;
    font-weight: 700;
    letter-spacing: -0.3px;
    white-space: nowrap;
    margin-right: auto;
  }

  .vmms-logo-accent { color: #1D9E75; }

  .vmms-nav-links {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 2.5rem;
  }

  .vmms-nav-links li {
    display: list-item;
  }

  .vmms-nav-links a {
    text-decoration: none;
    color: #374151;
    font-size: 0.9375rem;
    transition: color 0.2s;
    white-space: nowrap;
  }

  .vmms-nav-links a:hover { color: #1D9E75; }

  .vmms-nav-right {
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .vmms-nav-user-name {
    font-size: 0.9rem;
    color: #374151;
  }

  .vmms-hamburger {
    display: none;
    font-size: 1.4rem;
    cursor: pointer;
    color: #374151;
    background: none;
    border: none;
    padding: 0;
    margin: 0;
  }

  .vmms-btn {
    padding: 0.5rem 1.25rem;
    border-radius: 0.375rem;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
  }

  .vmms-btn-primary { background-color: #1D9E75; color: white; }
  .vmms-btn-primary:hover { background-color: #0F6E56; color: white; }

  .vmms-btn-outline {
    background-color: transparent;
    color: #374151;
    border: 1px solid #d1d5db;
  }

  .vmms-btn-outline:hover {
    background-color: #f9fafb;
    border-color: #1D9E75;
  }

  /* ── DESKTOP (min 769px) ── */
  @media (min-width: 769px) {
    .vmms-navbar-wrapper {
      flex-direction: row;
    }

    .vmms-nav-top {
      width: auto;
      flex: 1;
    }

    .vmms-logo {
      margin-right: 0;
    }

    .vmms-nav-links {
      flex: 1;
      justify-content: center;
      gap: 2.5rem;
      position: static !important;
      background: transparent !important;
      border: none !important;
      top: auto !important;
      display: flex !important;
    }

    .vmms-nav-right {
      display: flex !important;
    }

    .vmms-hamburger {
      display: none !important;
    }
  }

  /* ── MOBILE (max 768px) ── */
  @media (max-width: 768px) {
    .vmms-navbar {
      height: 56px;
      padding: 0 1rem;
      position: relative;
    }

    .vmms-navbar-wrapper {
      flex-direction: column;
      align-items: stretch;
    }

    .vmms-nav-top {
      width: 100%;
      gap: 0.5rem;
    }

    .vmms-logo {
      font-size: 1.1rem;
      margin-right: auto;
    }

    .vmms-hamburger {
      display: block !important;
      order: 2;
      margin-left: auto;
    }

    .vmms-nav-right {
      display: none !important;
    }

    .vmms-nav-links {
      display: none;
      position: absolute;
      top: 56px;
      left: 0;
      right: 0;
      flex-direction: column;
      background-color: #fff;
      border-bottom: 1px solid #e5e7eb;
      padding: 0;
      margin: 0;
      gap: 0;
      z-index: 998;
      border-radius: 0;
    }

    .vmms-nav-links.active {
      display: flex !important;
    }

    .vmms-nav-links li {
      border-bottom: 1px solid #f3f4f6;
      padding: 0;
      margin: 0;
    }

    .vmms-nav-links li:last-child {
      border-bottom: none;
    }

    .vmms-nav-links a {
      display: block;
      padding: 0.75rem 1rem;
      font-size: 0.875rem;
    }

    .vmms-btn {
      padding: 0.35rem 0.75rem;
      font-size: 0.78rem;
    }
  }

  /* ── SMALL MOBILE (max 480px) ── */
  @media (max-width: 480px) {
    .vmms-navbar {
      height: 52px;
      padding: 0 0.75rem;
    }

    .vmms-logo {
      font-size: 1rem;
    }

    .vmms-hamburger {
      font-size: 1.2rem;
    }

    .vmms-btn {
      padding: 0.3rem 0.6rem;
      font-size: 0.72rem;
    }
  }

  /* ── HERO ── */
  .vmms-hero {
    padding: 5rem 1.5rem;
    text-align: center;
    max-width: 1200px;
    margin: 0 auto;
}
  .vmms-hero-headline {
    font-size: 2.75rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1rem;
    color: #1f2937;
  }

  .vmms-hero-subheading {
    font-size: 1.125rem;
    color: #6b7280;
    margin-bottom: 2.5rem;
    max-width: 650px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
  }

  .vmms-stats {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
  }

  .vmms-stat-pill {
    border: 1px solid #d1d5db;
    padding: 0.75rem 1.5rem;
    border-radius: 9999px;
    font-size: 0.9rem;
    color: #374151;
    background-color: #fff;
  }

  .vmms-hero-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
  }

  /* ── SECTIONS ── */
  .vmms-section {
    padding: 4rem 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
  }

  .vmms-section-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1D9E75;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
  }

  .vmms-section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 2.5rem;
  }

  .vmms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
  }

  .vmms-card {
    background-color: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    transition: all 0.2s;
  }

  .vmms-card:hover {
    border-color: #1D9E75;
    box-shadow: 0 4px 6px rgba(29,158,117,0.1);
  }

  .vmms-card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: #1f2937;
  }

  .vmms-card-description {
    font-size: 0.875rem;
    color: #9ca3af;
  }

  .vmms-service-tag {
    display: inline-block;
    background-color: #d1fae5;
    color: #047857;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
  }

  .vmms-service-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1D9E75;
    margin-top: 1rem;
  }

  .vmms-search-container {
    margin-bottom: 2rem;
    display: flex;
    justify-content: center;
  }

  .vmms-search-input {
    width: 100%;
    max-width: 400px;
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.95rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
  }

  .vmms-search-input:focus {
    outline: none;
    border-color: #1D9E75;
    box-shadow: 0 0 0 3px rgba(29,158,117,0.1);
  }

  .vmms-empty-state {
    text-align: center;
    padding: 3rem;
    color: #d1d5db;
    font-size: 0.95rem;
  }

  /* ── HOW IT WORKS ── */
  .vmms-steps {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 2rem;
  }

  .vmms-step { text-align: center; }

  .vmms-step-badge {
    width: 3rem;
    height: 3rem;
    border: 1.5px solid #1D9E75;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-weight: 700;
    color: #1D9E75;
    font-size: 1.25rem;
  }

  .vmms-step-title {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: #1f2937;
  }

  .vmms-step-description {
    font-size: 0.875rem;
    color: #9ca3af;
    line-height: 1.5;
  }

  .vmms-section-alt { background-color: #f9fafb; }

  /* ── CTA BANNER ── */
  .vmms-cta-banner {
    background-color: #E1F5EE;
    padding: 4rem 1.5rem;
    text-align: center;
  }

  .vmms-cta-banner-content { max-width: 600px; margin: 0 auto; }

  .vmms-cta-headline {
    font-size: 1.875rem;
    font-weight: 700;
    color: #085041;
    margin-bottom: 1rem;
  }

  .vmms-cta-subtext {
    font-size: 1rem;
    color: #0F6E56;
    margin-bottom: 2rem;
  }

  /* ── FOOTER ── */
  .vmms-footer {
    border-top: 1px solid #e5e7eb;
    padding: 2rem 1.5rem;
    font-size: 0.8125rem;
    color: #aaa;
  }

  .vmms-footer-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
  }

  .vmms-hidden { display: none; }

  /* ── PAGE RESPONSIVE STYLES ── */
  @media (max-width: 768px) {
    .vmms-hero { padding: 2rem 1.25rem; }

    .vmms-hero-headline {
      font-size: 1.6rem;
      line-height: 1.25;
    }

    .vmms-hero-subheading {
      font-size: 0.95rem;
      margin-bottom: 1.5rem;
    }

    .vmms-stats {
      flex-direction: column;
      align-items: center;
      gap: 10px;
      margin-bottom: 2rem;
    }

    .vmms-stat-pill {
      width: 100%;
      max-width: 320px;
      text-align: center;
      padding: 0.65rem 1rem;
      font-size: 0.85rem;
    }

    .vmms-hero-buttons {
      flex-direction: column;
      align-items: center;
      gap: 10px;
    }

    .vmms-hero-buttons .vmms-btn {
      width: 100%;
      max-width: 320px;
      text-align: center;
      padding: 0.75rem 1rem;
      font-size: 0.95rem;
    }

    .vmms-section { padding: 2rem 1.25rem; }

    .vmms-section-title {
      font-size: 1.4rem;
      margin-bottom: 1.5rem;
    }

    .vmms-grid { grid-template-columns: 1fr; }

    .vmms-steps {
      grid-template-columns: 1fr;
      gap: 1.5rem;
    }

    .vmms-cta-headline { font-size: 1.5rem; }
    .vmms-cta-subtext { font-size: 0.9rem; }

    .vmms-footer-content {
      flex-direction: column;
      text-align: center;
      gap: 6px;
    }
  }

  @media (max-width: 480px) {
    .vmms-hero-headline { font-size: 1.4rem; }
    .vmms-hero-subheading { font-size: 0.875rem; }
    .vmms-section-title { font-size: 1.25rem; }
    .vmms-stat-pill { font-size: 0.8rem; padding: 0.55rem 0.9rem; }
  }
</style>

<!-- NAVBAR -->
<nav class="vmms-navbar">
  <div class="vmms-navbar-wrapper">

    <div class="vmms-nav-top">
      <div class="vmms-logo">
        V<span class="vmms-logo-accent">MM</span>S
      </div>

      <div class="vmms-nav-right">
        @guest
          <a href="{{ route('login') }}" class="vmms-btn vmms-btn-outline">Login</a>
          <a href="{{ route('register') }}" class="vmms-btn vmms-btn-primary">Get started</a>
        @endguest

        @auth
          <span>{{ Auth::user()->name }}</span>
          <a href="{{ route('customer.dashboard') }}" class="vmms-btn vmms-btn-primary">Dashboard</a>
        @endauth
      </div>

      <div class="vmms-hamburger" id="vmmsHamburger">
        <i class="fas fa-bars"></i>
      </div>
    </div>

    <!-- MOBILE MENU -->
    <ul class="vmms-nav-links" id="vmmsNavLinks">
      <li><a href="#categories">Services</a></li>
      <li><a href="#how-it-works">How it works</a></li>
      <li><a href="#services">Pricing</a></li>

      @guest
        <li><a href="{{ route('login') }}">Login</a></li>
        <li><a href="{{ route('register') }}">Get started</a></li>
      @endguest

      @auth
        <li><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
      @endauth
    </ul>

  </div>
</nav>

<!-- Hero -->
<section class="vmms-hero">
  <h1 class="vmms-hero-headline">
    Your vehicle deserves <span style="color:#1D9E75">expert maintenance</span>
  </h1>
  <p class="vmms-hero-subheading">
    Fast, reliable, and transparent vehicle maintenance. Book in minutes and track your vehicle in real time.
  </p>

  <div class="vmms-stats">
    <div class="vmms-stat-pill">500+ Vehicles serviced</div>
    <div class="vmms-stat-pill">98% Customer satisfaction</div>
    <div class="vmms-stat-pill">24h Average turnaround</div>
  </div>

  <div class="vmms-hero-buttons">
    @guest
      <a href="{{ route('register') }}" class="vmms-btn vmms-btn-primary">Request a service</a>
      <a href="{{ route('login') }}" class="vmms-btn vmms-btn-outline">Login to book</a>
    @endguest
    @auth
      <a href="{{ route('customer.dashboard') }}" class="vmms-btn vmms-btn-primary">Go to my dashboard</a>
    @endauth
  </div>
</section>

<!-- Categories -->
<section id="categories" class="vmms-section vmms-section-alt">
  <div class="vmms-section-label">What we offer</div>
  <h2 class="vmms-section-title">Services for every vehicle</h2>

  @forelse ($categories as $category)
    <div class="vmms-grid">
      <div class="vmms-card">
        <h3 class="vmms-card-title">{{ $category->name }}</h3>
        <p class="vmms-card-description">{{ $category->description }}</p>
      </div>
    @empty
    <div class="vmms-empty-state">No categories available yet.</div>
    @endforelse
  </div>
</section>

<!-- Services -->
<section id="services" class="vmms-section">
  <div class="vmms-section-label">Browse services</div>
  <h2 class="vmms-section-title">Find the right service for your vehicle</h2>

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
        <div class="vmms-service-tag">{{ $service->wheelerCategory->name ?? 'General' }}</div>
        <h3 class="vmms-card-title">{{ $service->name }}</h3>
        <p class="vmms-card-description">{{ $service->description }}</p>
        <div class="vmms-service-price">₱{{ number_format($service->price, 2) }}</div>
      </div>
    @empty
    <div class="vmms-empty-state">No services available at the moment.</div>
    @endforelse
  </div>
</section>

<!-- How It Works -->
<section id="how-it-works" class="vmms-section vmms-section-alt">
  <div class="vmms-section-label">Simple process</div>
  <h2 class="vmms-section-title">How it works</h2>

  <div class="vmms-steps">
    <div class="vmms-step">
      <div class="vmms-step-badge">1</div>
      <h3 class="vmms-step-title">Submit a request</h3>
      <p class="vmms-step-description">Fill out the service form with your vehicle details and preferred schedule</p>
    </div>
    <div class="vmms-step">
      <div class="vmms-step-badge">2</div>
      <h3 class="vmms-step-title">Choose your payment</h3>
      <p class="vmms-step-description">Pay a downpayment of 20–50% or pay the full service amount upfront</p>
    </div>
    <div class="vmms-step">
      <div class="vmms-step-badge">3</div>
      <h3 class="vmms-step-title">Payment verification</h3>
      <p class="vmms-step-description">Admin reviews and verifies your uploaded proof of payment</p>
    </div>
    <div class="vmms-step">
      <div class="vmms-step-badge">4</div>
      <h3 class="vmms-step-title">Drop off or pickup</h3>
      <p class="vmms-step-description">Bring your vehicle to our shop or request a pickup service</p>
    </div>
    <div class="vmms-step">
      <div class="vmms-step-badge">5</div>
      <h3 class="vmms-step-title">Track & collect</h3>
      <p class="vmms-step-description">Monitor your service progress and collect your vehicle once completed</p>
    </div>
  </div>
</section>

<!-- CTA Banner -->
<section class="vmms-cta-banner">
  <div class="vmms-cta-banner-content">
    <h2 class="vmms-cta-headline">Ready to get your vehicle serviced?</h2>
    <p class="vmms-cta-subtext">Create an account and submit your first service request in under 2 minutes.</p>
    @guest
      <a href="{{ route('register') }}" class="vmms-btn vmms-btn-primary">Request a service now</a>
    @endguest
    @auth
      <a href="{{ route('customer.dashboard') }}" class="vmms-btn vmms-btn-primary">Go to my dashboard</a>
    @endauth
  </div>
</section>

<!-- Footer -->
<footer class="vmms-footer">
  <div class="vmms-footer-content">
    <div>VMMS · Vehicle Maintenance Management System</div>
    <div>Davao, Philippines · © {{ date('Y') }}</div>
  </div>
</footer>

<script>
  // ── SERVICE SEARCH ──
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

  // ── HAMBURGER MENU ──
  const hamburger = document.getElementById('vmmsHamburger');
  const navLinks = document.getElementById('vmmsNavLinks');

  if (hamburger && navLinks) {
    // Toggle menu on hamburger click
    hamburger.addEventListener('click', (e) => {
      e.stopPropagation();
      navLinks.classList.toggle('active');
    });

    // Close menu when clicking a link
    document.querySelectorAll('#vmmsNavLinks a').forEach(link => {
      link.addEventListener('click', () => {
        navLinks.classList.remove('active');
      });
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.vmms-navbar')) {
        navLinks.classList.remove('active');
      }
    });
  }
</script>
