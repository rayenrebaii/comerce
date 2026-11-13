@extends('Admin')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="col-6 text-center h2" style="font-weight: bold; color: #004085; margin-left: auto; margin-right: auto;">
            Add Product
        </div>
        <div>
            <a class="btn" href="{{ route('produits.index') }}" style="background-color: #17a2b8; color: white;">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('produits.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="product_name">Product Name:</label>
                    <div class="input-group">
                        <input type="text" name="product_name" required id="product_name" class="form-control" value="{{ old('product_name') }}" placeholder="Enter the name of the product">
                    </div>
                    @error('product_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="product_description">Description:</label>
                    <div class="input-group">
                        <input type="text" name="product_description" required id="product_description" class="form-control" value="{{ old('product_description') }}" placeholder="Enter a description of the product">
                    </div>
                    @error('product_description')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="product_price">Price:</label>
                    <div class="input-group">
                        <input type="number" name="product_price" required id="product_price" class="form-control" value="{{ old('product_price') }}" placeholder="Enter the price of the product" step="0.01">
                    </div>
                    @error('product_price')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="quantity_available">Quantity Available:</label>
                    <div class="input-group">
                        <input type="number" name="quantity_available" required id="quantity_available" class="form-control" value="{{ old('quantity_available') }}" placeholder="Enter the available quantity">
                    </div>
                    @error('quantity_available')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="product_img">Image URL:</label>
                    <div class="input-group">
                        <input type="text" name="product_img" id="product_img" class="form-control" value="{{ old('product_img') }}" placeholder="Enter the image URL">
                    </div>
                    @error('product_img')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="discount">Discount:</label>
                    <div class="input-group">
                        <input type="number" name="discount" id="discount" class="form-control" value="{{ old('discount') }}" placeholder="Enter discount (0-99)" max="99" min="0">
                    </div>
                    @error('discount')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="category_id">Category:</label>
                    <div class="input-group">
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->nom_categorie }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('category_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

    </div>

    <div class="card-footer" style="display: flex; justify-content: flex-end;">
        <button class="btn btn-primary" type="submit" style="margin-left: 10px;">
            <i class="fas fa-plus"></i> Submit
        </button>
    </div>
        </form>
</div>

@endsection
