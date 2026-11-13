@extends('Admin')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="col-6 text-center h2" style="font-weight: bold; color: #004085; margin-left: auto; margin-right: auto;">
            Category Details
        </div>
        <div >
            <a class="btn" style="background-color: #17a2b8; color: white;" href="{{ route('categories.index') }}">
                <i class="fas fa-arrow-left" ></i> Back
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <div class="col-3 h5 font-weight-bold">Category Name:</div>
            <div class="col text-primary">{{ $category->nom_categorie }}</div> <!-- Valeur en bleu -->
        </div>
        <div class="row mb-3">
            <div class="col-3 h5 font-weight-bold">Description:</div>
            <div class="col text-primary">{{ $category->description }}</div> <!-- Valeur en bleu -->
        </div>
    </div>
</div>
@endsection
