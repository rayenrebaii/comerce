@extends('Admin')

@section('content')
<div class="content-header" style="background-image: url('https://res.cloudinary.com/dwg2nov2v/image/upload/v1733587369/back_f7zk71.png'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 100vh; display: flex; justify-content: center; align-items: center;">
    <div class="d-flex flex-column justify-content-center align-items-center" style="background-color: rgba(255, 255, 255, 0.9); padding: 30px; border-radius: 20px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); max-width: 600px;">
        <img src="https://res.cloudinary.com/dwg2nov2v/image/upload/v1733587531/logo_sans_back_lix8ew.png" alt="Logo" style="width: 100px; height: auto; margin-bottom: 20px;">
        
        <!-- Display different welcome messages based on user role -->
        @if(Auth::user()->role == 1)
            <h1 style="font-size: 2.5rem; font-weight: bold; color: #004085; text-align: center; margin-bottom: 20px;">{{ __('Welcome Admin!') }}</h1>
            <p style="font-size: 1.2rem; color: #333; text-align: center;">{{ __('Welcome Dear Admin') }} <strong>{{ Auth::user()->name }}</strong>!</p>
        @else
            <h1 style="font-size: 2.5rem; font-weight: bold; color: #004085; text-align: center; margin-bottom: 20px;">{{ __('Welcome to Virtual Fashion!') }}</h1>
            <p style="font-size: 1.2rem; color: #333; text-align: center;">{{ __('Welcome Dear Customer') }} <strong>{{ Auth::user()->name }}</strong>!</p>
        @endif

        <p style="font-size: 1rem; color: #555; text-align: center;">{{ __('You are successfully logged in.') }}</p>

        <!-- Action buttons -->
        <div class="d-flex justify-content-center mt-4">
            @if(Auth::user()->role == 0)
                <!-- Only show "Shop" button for customers -->
                <a href="{{ route('client.produits') }}" class="btn btn-primary mx-2" style="width: 150px; font-size: 1rem;">
                    <i class="fas fa-store"></i> {{ __('Shop') }}
                </a>
            @elseif(Auth::user()->role == 1)
                <!-- Only show "Dashboard" button for admins -->
                <a href="{{ route('dashboard.index') }}" class="btn btn-success mx-2" style="width: 150px; font-size: 1rem;">
                    <i class="fas fa-chart-line"></i> {{ __('Dashboard') }}
                </a>
            @endif
            <a href="{{ route('logout') }}" class="btn btn-danger mx-2" style="width: 150px; font-size: 1rem;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>
@endsection
