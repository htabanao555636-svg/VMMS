@extends('Layouts.user')

@section('content')
<style>
  body > nav.navbar,
  nav.navbar.navbar-expand-lg.navbar-light.bg-light,
  main > footer.bg-light {
    display: none !important;
  }

  body, main {
    margin: 0 !important;
    padding: 0 !important;
  }

  /* ── NAVBAR ── */
  .vmms-navbar {
    position: fixed;
    top: 0; left: 0; right: 0;
    width: 100%;
    z-index: 9999;
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    padding: 0;
    box-sizing: border-box;
  }

  .vmms-navbar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 64px;
    padding: 0 2rem;
    max-width: 1400px;
    margin: 0 auto;
    position: relative;
  }

  .vmms-logo {
    font-size: 1.25rem;
    font-weight: 700;
    letter-spacing: -0.3px;
    flex-shrink: 0;
  }

  .vmms-logo-accent { color: #1D9E75; }

  .vmms-nav-links {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 2.5rem;
    flex: 1;
    justify-content: center;
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
    flex-shrink: 0;
  }

  .vmms-hamburger {
    display: none;
    font-size: 1.4rem;
    cursor: pointer;
    color: #374151;
    background: none;
    border: none;
    padding: 4px;
    flex-shrink: 0;
  }

  /* Mobile dropdown menu */
  .vmms-mobile-menu {
    display: none;
    flex-direction: column;
    background: #fff;
    border-top: 1px solid #e5e7eb;
    border-bottom: 1px solid #e5e7eb;
    padding: 0;
    margin: 0;
    list-style: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.08);
  }

  .vmms-mobile-menu.open {
    display: flex;
  }

  .vmms-mobile-menu li {
    border-bottom: 1px solid #f3f4f6;
  }

  .vmms-mobile-menu li:last-child { border-bottom: none; }

  .vmms-mobile-menu a {
    display: block;
    padding: 0.75rem 1.5rem;
    font-size: 0.9rem;
    color: #374151;
    text-decoration: none;
    transition: background 0.2s, color 0.2s;
  }

  .vmms-mobile-menu a:hover {
    background: #f9fafb;
    color: #1D9E75;
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
  .vmms-btn-outline:hover { background-color: #f9fafb; border-color: #1D9E75; }
  .vmms-btn-lg { padding: 0.75rem 1.75rem; font-size: 1rem; }

  /* ── HERO ── */
  .vmms-hero {
    padding-top: calc(64px + 5rem);
    padding-bottom: 5rem;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
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
    display: flex;
    flex-direction: column; 
    min-height: 200px;       
}

  .vmms-card:hover {
    border-color: #1D9E75;
    box-shadow: 0 4px 6px rgba(29,158,117,0.1);
  }

  .vmms-card-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.75rem; color: #1f2937; }
  .vmms-card-description { font-size: 0.875rem; color: #9ca3af; }

  .vmms-service-tag {
    display: inline-block;
    background-color: #d1fae5;
    color: #047857;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    align-self: flex-start;  /* add this */
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

  .vmms-empty-state { text-align: center; padding: 3rem; color: #d1d5db; font-size: 0.95rem; }

  .vmms-steps {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 2rem;
  }

  .vmms-step { text-align: center; }

  .vmms-step-badge {
    width: 3rem; height: 3rem;
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

  .vmms-step-title { font-size: 1rem; font-weight: 700; margin-bottom: 0.75rem; color: #1f2937; }
  .vmms-step-description { font-size: 0.875rem; color: #9ca3af; line-height: 1.5; }

  .vmms-section-alt { background-color: #f9fafb; }

  .vmms-cta-banner { background-color: #E1F5EE; padding: 4rem 1.5rem; text-align: center; }
  .vmms-cta-banner-content { max-width: 600px; margin: 0 auto; }
  .vmms-cta-headline { font-size: 1.875rem; font-weight: 700; color: #085041; margin-bottom: 1rem; }
  .vmms-cta-subtext { font-size: 1rem; color: #0F6E56; margin-bottom: 2rem; }

  .vmms-footer { border-top: 1px solid #e5e7eb; padding: 2rem 1.5rem; font-size: 0.8125rem; color: #aaa; }
  .vmms-footer-content {
    max-width: 1200px; margin: 0 auto;
    display: flex; justify-content: space-between;
    align-items: center; flex-wrap: wrap; gap: 1rem;
  }

  .vmms-hidden { display: none; }

  /* ── RESPONSIVE ── */
  @media (max-width: 768px) {
    .vmms-navbar-inner { height: 56px; padding: 0 1rem; }
    .vmms-hero { padding-top: calc(56px + 2rem); padding-bottom: 2rem; }
    .vmms-hero-headline { font-size: 1.6rem; }
    .vmms-hero-subheading { font-size: 0.95rem; margin-bottom: 1.5rem; }

    .vmms-nav-links { display: none !important; }
    .vmms-nav-right { display: none !important; }
    .vmms-hamburger { display: block !important; }

    .vmms-stats { flex-direction: column; align-items: center; gap: 10px; margin-bottom: 2rem; }
    .vmms-stat-pill { width: 100%; max-width: 320px; text-align: center; padding: 0.65rem 1rem; font-size: 0.85rem; }
    .vmms-hero-buttons { flex-direction: column; align-items: center; gap: 10px; }
    .vmms-hero-buttons .vmms-btn { width: 100%; max-width: 320px; text-align: center; }
    .vmms-section { padding: 2rem 1.25rem; }
    .vmms-section-title { font-size: 1.4rem; margin-bottom: 1.5rem; }
    .vmms-grid { grid-template-columns: 1fr; }
    .vmms-steps { grid-template-columns: 1fr; gap: 1.5rem; }
    .vmms-cta-headline { font-size: 1.5rem; }
    .vmms-cta-subtext { font-size: 0.9rem; }
    .vmms-footer-content { flex-direction: column; text-align: center; gap: 6px; }
  }

  @media (max-width: 480px) {
    .vmms-logo { font-size: 1rem; }
    .vmms-hamburger { font-size: 1.2rem; }
    .vmms-btn { padding: 0.35rem 0.75rem; font-size: 0.8rem; }
    .vmms-hero-headline { font-size: 1.4rem; }
    .vmms-hero-subheading { font-size: 0.875rem; }
    .vmms-section-title { font-size: 1.25rem; }
    .vmms-stat-pill { font-size: 0.8rem; padding: 0.55rem 0.9rem; }
  }
</style>

<!-- NAVBAR -->
<nav class="vmms-navbar">
  <div class="vmms-navbar-inner">
    <div class="vmms-logo">V<span class="vmms-logo-accent">MM</span>S</div>

    <ul class="vmms-nav-links">
      <li><a href="#categories">Services</a></li>
      <li><a href="#how-it-works">How it works</a></li>
      <li><a href="#services">Pricing</a></li>
    </ul>

    <div class="vmms-nav-right">
      @guest
        <a href="{{ route('login') }}" class="vmms-btn vmms-btn-outline">Login</a>
        <a href="{{ route('register') }}" class="vmms-btn vmms-btn-primary">Get started</a>
      @endguest
      @auth
        <span style="font-size:0.9rem;color:#374151;">{{ Auth::user()->name }}</span>
        <a href="{{ route('customer.dashboard') }}" class="vmms-btn vmms-btn-primary">Dashboard</a>
      @endauth
    </div>

    <button class="vmms-hamburger" id="vmmsHamburger" aria-label="Toggle menu">
      <i class="fas fa-bars"></i>
    </button>
  </div>

  <!-- Mobile Menu -->
  <ul class="vmms-mobile-menu" id="vmmsMobileMenu">
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
      <a href="{{ route('register') }}" class="vmms-btn vmms-btn-primary vmms-btn-lg">Request a service</a>
      <a href="{{ route('login') }}" class="vmms-btn vmms-btn-outline vmms-btn-lg">Login to book</a>
    @endguest
    @auth
      <button type="button"
        onclick="document.getElementById('requestFormOverlay').style.display='flex'"
        class="vmms-btn vmms-btn-primary vmms-btn-lg"
        style="border:none; cursor:pointer;">
        Request a service
      </button>
    @endauth
  </div>
</section>

<!-- Categories -->
<section id="categories" class="vmms-section vmms-section-alt">
    <div class="vmms-section-label">What we offer</div>
    <h2 class="vmms-section-title">Services for every vehicle</h2>

    <div class="vmms-grid">
        @forelse ($categories as $category)
            <div class="vmms-card" style="display:flex; flex-direction:column; gap:10px; padding:1.25rem;">
                <div style="width:40px; height:40px; background:#d1fae5; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                    @if(str_contains(strtolower($category->name), '2'))
                        <i class="fas fa-motorcycle" style="color:#047857; font-size:16px;"></i>
                    @elseif(str_contains(strtolower($category->name), '3'))
                        <i class="fas fa-truck-pickup" style="color:#047857; font-size:16px;"></i>
                    @else
                        <i class="fas fa-car" style="color:#047857; font-size:16px;"></i>
                    @endif
                </div>
                <div>
                    <h3 class="vmms-card-title" style="margin-bottom:4px;">{{ $category->name }}</h3>
                    <p class="vmms-card-description">{{ $category->description }}</p>
                </div>
                @if($category->services_count ?? $category->services->count() ?? null)
                    <div style="margin-top:auto; font-size:11px; font-weight:600; color:#1D9E75;">
                        {{ $category->services_count ?? $category->services->count() }} services available
                    </div>
                @endif
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
    <input type="text" id="vmmsServiceSearch" class="vmms-search-input"
      placeholder="Search services..." aria-label="Search services"/>
  </div>

  <div class="vmms-grid">
    @forelse ($services as $service)
      <div class="vmms-card vmms-service-card" data-name="{{ strtolower($service->name) }}">
          <div class="vmms-service-tag">{{ $service->wheelerCategory->name ?? 'General' }}</div>
          <h3 class="vmms-card-title">{{ $service->name }}</h3>
          <p class="vmms-card-description">{{ $service->description }}</p>
          <div style="margin-top: auto; display: flex; justify-content: flex-end;">
              <div class="vmms-service-price">₱{{ number_format($service->price, 2) }}</div>
          </div>
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
  // Service search
  const searchInput  = document.getElementById('vmmsServiceSearch');
  const serviceCards = document.querySelectorAll('.vmms-service-card');

  if (searchInput) {
    searchInput.addEventListener('input', function(e) {
      const term = e.target.value.toLowerCase().trim();
      serviceCards.forEach(card => {
        card.classList.toggle('vmms-hidden', !card.getAttribute('data-name').includes(term));
      });
    });
  }

  // Hamburger menu
  const hamburger   = document.getElementById('vmmsHamburger');
  const mobileMenu  = document.getElementById('vmmsMobileMenu');

  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', e => {
      e.stopPropagation();
      mobileMenu.classList.toggle('open');
    });

    mobileMenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => mobileMenu.classList.remove('open'));
    });

    document.addEventListener('click', e => {
      if (!e.target.closest('.vmms-navbar')) mobileMenu.classList.remove('open');
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth > 768) mobileMenu.classList.remove('open');
    });
  }
</script>
@endsection