@extends('Admin')

@section('title', 'Commande Détails')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="h2 text-center" style="font-weight: bold; color: #004085; margin-left: auto; margin-right: auto;">
        Order details        #{{ $commande->id }}
        </div>
        <div>
            <a class="btn" style="background-color: #17a2b8; color: white;" href="{{ route('livraison.index') }}">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Client Information -->
        <div class="row mb-3">
            <div class="col-3 h5 font-weight-bold">Customer name            :</div>
            <div class="col text-primary">{{ $commande->user->name }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-3 h5 font-weight-bold">Customer Email:</div>
            <div class="col text-primary">{{ $commande->user->email }}</div>
        </div>

        <!-- Delivery Status -->
        <div class="row mb-3">
            <div class="col-3 h5 font-weight-bold">Delivery status
            :</div>
            @if ($commande->livraison)
                <span class="col text-primary">Delivered</span>
            @else
                <span class="col text-primary">Pending</span>
            @endif
        </div>

        <!-- Products Ordered -->
        <h4 class="font-weight-bold">Produits commandés</h4>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit price</th>
                    <th>Discount (%)</th>
                    <th>Total after discount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($commande->products as $product)
                <tr>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>{{ number_format($product->pivot->price, 2) }} TND</td>
                    <td>{{ $product->discount ?? 0 }}%</td>
                    <td>{{ number_format($product->pivot->quantity * $product->pivot->price * (1 - ($product->discount ?? 0) / 100), 2) }} TND</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Montant total et TVA -->
        <h4 class="font-weight-bold">Order Summary</h4>
        <table class="table">
            <tr>
                <th>Total HT:</th>
                <td>{{ number_format($totalHT, 2) }} TND</td>
            </tr>
            <tr>
                <th>TVA (19%):</th>
                <td>{{ number_format($tva, 2) }} TND</td>
            </tr>
            <tr>
                <th>Total TTC:</th>
                <td>{{ number_format($totalTTC, 2) }} TND</td>
            </tr>
        </table>
    </div>
</div>
@endsection
