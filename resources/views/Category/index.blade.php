@extends('Admin')

@section('content')
<div class="wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="col-6 h2" style="font-weight: bold; color: #004085;">
                    Category List
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
    <a href="{{ route('home') }}">Home</a>
</li>

                        <li class="breadcrumb-item active">Categories</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col text-right"> <!-- Aligns button to the right -->
                    <a class="btn btn-primary" href="{{ route('categories.create') }}">
                        <i class="fas fa-plus"></i> Add Category
                    </a>
                </div>
            </div>

            @if(session()->has('info'))
            <div class="alert alert-success" id="success-message">
                {{ session('info') }}
            </div>
            @endif

            <div class="row">
                <div class="col">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Id </th>
                                <th>Category Name</th>
                                <th>Description</th>
                                <th class="text-center" colspan="3">Action</th> <!-- Changed colspan to 3 to include Show -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->nom_categorie }}</td>
                                <td>{{ $category->description }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group"> <!-- Group buttons -->
                                        <!-- Show Button -->
                                        <a class="btn btn-info" href="{{ route('categories.show', $category) }}">
                                            <i class="fas fa-eye"></i> Show
                                        </a>

                                        <!-- Modify Button -->
                                        <a class="btn btn-success ml-1" href="{{ route('categories.edit', $category) }}">
                                            <i class="fas fa-edit"></i> Modify
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger ml-1"> <!-- Added margin class -->
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="d-flex flex-column align-items-center mt-3">
                        <div class="d-flex">
                            @if ($categories->onFirstPage())
                                <span class="btn btn-secondary disabled">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </span>
                            @else
                                <a href="{{ $categories->previousPageUrl() }}" class="btn btn-primary mx-2">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            @endif

                            @if ($categories->hasMorePages())
                                <a href="{{ $categories->nextPageUrl() }}" class="btn btn-primary mx-2">
                                    <i class="fas fa-chevron-right"></i> Next 
                                </a>
                            @else
                                <span class="btn btn-secondary disabled">
                                    <i class="fas fa-chevron-right"></i> Next 
                                </span>
                            @endif
                        </div>

                        <div class="mt-2">
                            <span>Page {{ $categories->currentPage() }} / {{ $categories->lastPage() }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<script>
    // Masquer le message de succès après 4 secondes
    setTimeout(function() {
        var successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 2000); // 4000 millisecondes = 4 secondes
</script>

@endsection
