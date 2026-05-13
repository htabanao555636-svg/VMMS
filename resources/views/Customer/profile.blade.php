@extends('Layouts.user')

@section('content')
<div class="content-header">
    <h1 class="page-title">My Profile</h1>
</div>

<div class="content-box">
    <h2>Profile Information</h2>
    <form method="POST" action="{{ route('customer.profile.update') }}" class="form">
        @csrf
        @method('PATCH')
        
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" value="{{ Auth::user()->phone }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
@endsection
