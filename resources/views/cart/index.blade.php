@extends('Admin')

@section('content')
<div class="wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="col-6 h2" style="font-weight: bold; color: #004085;">
                My Cart
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">My Cart                        </li>
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
                    <!-- Bouton Passer Commande -->
                    <form action="{{ route('passer.commande') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Place Order
                        </button>
                    </form>
                </div>
            </div>
            <div class="col mb-3">
                 <!-- Clear Cart Button -->
                 <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-trash"></i> Vider le panier
                            </button>
                        </form>
            </div>

            @if($cartItems->isEmpty())
    <div class="alert alert-success" role="alert" id="empty-cart-alert">
    Your shopping cart is empty.
    </div>
@else
                <div class="row">
                    <div class="col">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Prix</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td>{{ $item->product->product_name }}</td>
                                        <td>
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity_available }}" required>
                                                <button type="submit" class="btn btn-success">Update</button>
                                            </form>
                                        </td>
                                        <td>{{ number_format($item->product->product_price * (1 - $item->product->discount / 100), 2) }} TND</td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash-alt"></i> Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                       

                        <!-- Pagination (if needed) -->
                        <div class="d-flex justify-content-between mt-3">
                            <!-- Add pagination controls if your cart requires it -->
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
<script>
    // Masquer l'alerte après 10 secondes (10 000 millisecondes)
    setTimeout(function() {
        var alert = document.getElementById('empty-cart-alert');
        if (alert) {
            alert.style.display = 'none';
        }
    },  2000);
</script>
@endsection
