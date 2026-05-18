@extends('Layouts.user')

@section('content')
<section class="categories-page">
    <div class="container py-5">
        <!-- Page Header -->
        <div class="page-header mb-5">
            <h1 class="display-4 fw-bold mb-2">Vehicle Categories</h1>
            <p class="lead text-muted">Browse vehicle types we service and find the right maintenance for your vehicle</p>
        </div>

        <!-- Wheeler Categories Grid -->
        <div class="categories-grid">
            @forelse($categories as $category)
            <div class="category-card">
                <div class="category-card-icon">
                    <i class="fas fa-{{ getWheelerIcon($category->name) }}"></i>
                </div>

                <div class="category-card-body">
                    <h3 class="category-name">{{ $category->name }}</h3>
                    <p class="category-description">{{ $category->description ?? 'Professional maintenance services' }}</p>
                    
                    <a href="{{ route('user.services') }}" class="btn btn-category-explore">
                        <i class="fas fa-arrow-right"></i> View Services
                    </a>
                </div>
            </div>
            @empty
            <div class="no-categories">
                <i class="fas fa-inbox"></i>
                <h3>No Categories Available</h3>
                <p>Vehicle categories are not available at the moment</p>
            </div>
            @endforelse
        </div>

        <!-- Quick Stats Section -->
        @if($categories->count() > 0)
        <section class="stats-section mt-5">
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="stat-content">
                        <h4>{{ $categories->count() }}</h4>
                        <p>Vehicle Types</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stat-content">
                        <h4>Premium</h4>
                        <p>Services Available</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h4>Certified</h4>
                        <p>Mechanics</p>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </div>
</section>

<style>
    .categories-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .page-header {
        text-align: center;
        padding: 40px 0;
    }

    .page-header h1 {
        color: #2c3e50;
        font-weight: 800;
    }

    .page-header .lead {
        font-size: 18px;
        color: #7f8c8d;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }

    .category-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        display: flex;
        flex-direction: column;
        cursor: pointer;
    }

    .category-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .category-card-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 45px 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .category-card-icon::before {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -50px;
        right: -50px;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0); }
        50% { transform: translate(20px, -20px); }
    }

    .category-card-icon i {
        font-size: 52px;
        color: white;
        z-index: 1;
        transition: transform 0.4s ease;
    }

    .category-card:hover .category-card-icon i {
        transform: scale(1.1) rotate(5deg);
    }

    .category-card-body {
        padding: 30px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .category-name {
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 12px 0;
    }

    .category-description {
        font-size: 14px;
        color: #666;
        margin: 0 0 25px 0;
        flex-grow: 1;
        line-height: 1.7;
    }

    .btn-category-explore {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        align-self: flex-start;
    }

    .btn-category-explore:hover {
        transform: translateX(5px);
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .no-categories {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 40px;
        color: #7f8c8d;
    }

    .no-categories i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
        color: #667eea;
    }

    .no-categories h3 {
        color: #2c3e50;
        font-size: 28px;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .no-categories p {
        font-size: 16px;
        color: #7f8c8d;
    }

    /* Stats Section */
    .stats-section {
        background: white;
        border-radius: 14px;
        padding: 40px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
    }

    .stat-content h4 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
    }

    .stat-content p {
        margin: 5px 0 0 0;
        font-size: 14px;
        color: #7f8c8d;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 25px 0;
        }

        .page-header h1 {
            font-size: 28px;
        }

        .categories-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .category-card-icon {
            padding: 35px 15px;
        }

        .category-card-icon i {
            font-size: 40px;
        }

        .category-card-body {
            padding: 20px;
        }

        .category-name {
            font-size: 18px;
        }

        .stats-section {
            padding: 20px;
        }

        .stats-container {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }
</style>

@php
    function getWheelerIcon($wheelerName) {
        $icons = [
            '2-Wheeler' => 'motorcycle',
            '2-Wheelers' => 'motorcycle',
            '3-Wheeler' => 'gopuram',
            '3-Wheelers' => 'gopuram',
            '4-Wheeler' => 'car',
            '4-Wheelers' => 'car',
            'Heavy Vehicles' => 'truck',
            'Heavy Vehicle' => 'truck',
        ];
        
        return $icons[$wheelerName] ?? 'tools';
    }
@endphp
@endsection
