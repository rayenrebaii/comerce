@extends('Admin')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="col-6 text-center h2" style="font-weight: bold; color: #004085; margin-left: auto; margin-right: auto;">
            Edit Product
        </div>
        <div>
            <a class="btn" href="{{ route('produits.index') }}" style="background-color: #17a2b8; color: white;">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <form action="{{ route('produits.update', $produit) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="product_name">Product Name:</label>
                    <input id="product_name" type="text" name="product_name" class="form-control" value="{{ old('product_name', $produit->product_name) }}">
                    @error('product_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="product_description">Description:</label>
                    <input type="text" id="product_description" name="product_description" class="form-control" value="{{ old('product_description', $produit->product_description) }}">
                    @error('product_description')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="product_price">Price:</label>
                    <input type="number" id="product_price" name="product_price" class="form-control" value="{{ old('product_price', $produit->product_price) }}">
                    @error('product_price')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="quantity_available">Quantity Available:</label>
                    <input type="number" id="quantity_available" name="quantity_available" class="form-control" value="{{ old('quantity_available', $produit->quantity_available) }}">
                    @error('quantity_available')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="discount">Discount:</label>
                    <input type="number" id="discount" name="discount" class="form-control" value="{{ old('discount', $produit->discount) }}">
                    @error('discount')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="discount">Image URL:</label>
                    <input type="text" id="product_img" name="product_img" class="form-control" value="{{ old('product_img', $produit->product_img) }}">
                    @error('product_img')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category_id">Category:</label>
                    <select id="category_id" name="category_id" class="form-control">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $produit->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->nom_categorie }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card-footer" style="display: flex; justify-content: flex-end;">
            <button class="btn btn-primary" type="submit" style="margin-left: 10px;">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </form>
</div>

@endsection
