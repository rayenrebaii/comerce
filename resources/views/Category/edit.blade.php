@extends('Admin')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="col-6 text-center h2" style="font-weight: bold; color: #004085; margin-left: auto; margin-right: auto;">
            Edit category
        </div>
        <div>
            <a class="btn" href="{{ route('categories.index') }}" style="background-color: #17a2b8; color: white;">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('put')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nom_categorie">Category Name:</label>
                    <div class="input-group">
                        <input id="nom_categorie" type="text" name="nom_categorie" class="form-control" value="{{ $category->nom_categorie }}">
                    </div>
                    @error('nom_categorie')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="description">Description:</label>
                    <div class="input-group">
                        <input type="text" id="description" name="description" class="form-control" value="{{ $category->description }}">
                    </div>
                    @error('description')
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
