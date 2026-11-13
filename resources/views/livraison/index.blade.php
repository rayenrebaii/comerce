@extends('Admin')

@section('content')
<div class="wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="col-6 h2" style="font-weight: bold; color: #004085;">
                    Deliveries List
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Deliveries</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Filters -->
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('livraison.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="email" class="form-control" placeholder="Filtrer par email" value="{{ request('email') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="name" class="form-control" placeholder="Filtrer par nom" value="{{ request('name') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">-- Statut --</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>En attente</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Livré</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-block">Appliquer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des commandes</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" id="success-message">
                            {{ session('success') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger" id="error-message">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nom du client</th>
                                <th>Email</th>
                                <th>Date de commande</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commandes as $commande)
                                <tr>
                                    <td>{{ $commande->user->name }}</td>
                                    <td>{{ $commande->user->email }}</td>
                                    <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $commande->livraison ? 'success' : 'warning' }}">
                                            {{ $commande->livraison ? 'Livré' : 'En attente' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('livraison.show', $commande->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                        @if (!$commande->livraison)
                                            <form action="{{ route('livraison.updateStatus', $commande->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i> Livrer
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer">
                    <div class="d-flex flex-column align-items-center mt-3">
                        <div class="d-flex">
                            @if ($commandes->onFirstPage())
                                <span class="btn btn-secondary disabled">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </span>
                            @else
                                <a href="{{ $commandes->previousPageUrl() }}" class="btn btn-primary mx-2">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            @endif

                            @if ($commandes->hasMorePages())
                                <a href="{{ $commandes->nextPageUrl() }}" class="btn btn-primary mx-2">
                                    <i class="fas fa-chevron-right"></i> Next
                                </a>
                            @else
                                <span class="btn btn-secondary disabled">
                                    <i class="fas fa-chevron-right"></i> Next
                                </span>
                            @endif
                        </div>

                        <div class="mt-2">
                            <span>Page {{ $commandes->currentPage() }} / {{ $commandes->lastPage() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Hide success or error messages after 4 seconds
    setTimeout(function() {
        var successMessage = document.getElementById('success-message');
        var errorMessage = document.getElementById('error-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }
    }, 2000);
</script>

@endsection
