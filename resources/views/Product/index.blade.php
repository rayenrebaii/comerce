@extends('Admin')

@section('content')
<div class="wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="col-6 h2" style="font-weight: bold; color: #004085;">
                    Product List
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col text-right">
                    <a class="btn btn-primary" href="{{ route('produits.create') }}">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                </div>
            </div>

            @if(session()->has('info'))
            <div class="alert alert-success" id="success-message">
                {{ session('info') }}
            </div>
            @endif

            <!-- Form for Importing Data -->
            <div class="card mb-4">
                <div class="card-header" >
                <h4 class="card-title text-center font-weight-bold" >Import Products</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="custom-file mb-3">
        <input type="file" name="file" id="file" class="custom-file-input" required>
        <label class="custom-file-label" for="file" id="file-label">Choose a file</label>
    </div>

                        <button style="float : right;" type="submit" class="btn btn-dark btn-sm"><i class="fas fa-file-import"></i>  Import Product Liste</button>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity Available</th>
                                <th>Discount</th>
                                <th>Category</th>
                                <th class="text-center" colspan="3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produits as $produit)
                            <tr>
                                <td>{{ $produit->product_name }}</td>
                                <td>{{ $produit->product_price }} $</td>
                                <td>{{ $produit->quantity_available }}</td>
                                <td>{{ $produit->discount }} %</td>
                                <td>{{ $produit->category ? $produit->category->nom_categorie : 'No Category' }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-info" href="{{ route('produits.show', $produit) }}">
                                            <i class="fas fa-eye"></i> Show
                                        </a>

                                        <a class="btn btn-success ml-1" href="{{ route('produits.edit', $produit) }}">
                                            <i class="fas fa-edit"></i> Modify
                                        </a>

                                        <form action="{{ route('produits.destroy', $produit) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" style="display:inline;">
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
                            @if ($produits->onFirstPage())
                                <span class="btn btn-secondary disabled">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </span>
                            @else
                                <a href="{{ $produits->previousPageUrl() }}" class="btn btn-primary mx-2">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            @endif

                            @if ($produits->hasMorePages())
                                <a href="{{ $produits->nextPageUrl() }}" class="btn btn-primary mx-2">
                                    <i class="fas fa-chevron-right"></i> Next
                                </a>
                            @else
                                <span class="btn btn-secondary disabled">
                                    <i class="fas fa-chevron-right"></i> Next
                                </span>
                            @endif
                        </div>

                        <div class="mt-2">
                            <span>Page {{ $produits->currentPage() }} / {{ $produits->lastPage() }}</span>
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
    }, 2000); // 2000 millisecondes = 2 secondes
</script>
<script>
    // Script pour mettre à jour l'affichage du nom du fichier sélectionné
    document.getElementById('file').addEventListener('change', function(event) {
        var input = event.target;
        var label = document.getElementById('file-label');
        
        // Vérifie si un fichier est sélectionné
        if (input.files && input.files.length > 0) {
            label.textContent = input.files[0].name; // Affiche le nom du fichier sélectionné
        } else {
            label.textContent = 'No file chosen'; // Aucun fichier sélectionné
        }
    });
</script>

@endsection
