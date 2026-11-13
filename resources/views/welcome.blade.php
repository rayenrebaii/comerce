@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Bienvenue {{ Auth::user()->name }}</h1>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Déconnexion</button>
    </form>
</div>
@endsection
