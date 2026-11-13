@extends('Admin')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="col-6 text-center h2" style="font-weight: bold; color: #004085; margin-left: auto; margin-right: auto;">
            Add category
        </div>
        <div>
            <a class="btn" href="{{ route('categories.index') }}" style="background-color: #17a2b8; color: white;">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nom_categorie">Category Name:</label>
                    <div class="input-group">
                        <input type="text" name="nom_categorie" required id="nom_categorie" class="form-control" value="{{ old('nom_categorie') }}" placeholder="Enter the name of the category">
                    </div>
                    @error('nom_categorie')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="description">Description:</label>
                    <div class="input-group">
                        <input type="text" name="description" required id="description" class="form-control" value="{{ old('description') }}" placeholder="Enter a description of the category">
                    </div>
                    @error('description')
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
