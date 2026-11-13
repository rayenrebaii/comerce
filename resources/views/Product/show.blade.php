@extends('Admin')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="col-6 text-center h2" style="font-weight: bold; color: #004085; margin-left: auto; margin-right: auto;">
            Product Details
        </div>
        <div>
            <a class="btn" style="background-color: #17a2b8; color: white;" href="{{ route('produits.index') }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <div class="col-3 h5 font-weight-bold">Product Name:</div>
            <div class="col text-primary">{{ $produit->product_name }}</div> <!-- Valeur en bleu -->
        </div>
        <div class="row mb-3">
            <div class="col-3 h5 font-weight-bold">Description:</div>
            <div class="col text-primary">{{ $produit->product_description }}</div> <!-- Valeur en bleu -->
        </div>
        <div class="row mb-3">
            <div class="col-3 h5 font-weight-bold">Price:</div>
            <div class="col text-primary">{{ $produit->product_price }} $</div> <!-- Valeur en bleu -->
        </div>
        <div class="row mb-3">
            <div class="col-3 h5 font-weight-bold">Quantity Available:</div>
            <div class="col text-primary">{{ $produit->quantity_available }}</div> <!-- Valeur en bleu -->
        </div>
        <div class="row mb-3">
            <div class="col-3 h5 font-weight-bold">Discount:</div>
            <div class="col text-primary">{{ $produit->discount }} %</div> <!-- Valeur en bleu -->
        </div>
        <div class="row mb-3">
            <div class="col-3 h5 font-weight-bold">Category:</div>
            <div class="col text-primary">{{ $produit->category ? $produit->category->nom_categorie : 'No Category' }}</div> <!-- Valeur en bleu -->
        </div>
        <div class="row mb-3">
            <div class="col-3 h5 font-weight-bold">Product Image:</div>
            <div class="col text-primary">
                <img src="{{ $produit->product_img }}" alt="{{ $produit->product_name }}" style="max-width: 200px; height: auto;">
            </div> <!-- Affichage de l'image du produit -->
        </div>
    </div>
</div>
@endsection
