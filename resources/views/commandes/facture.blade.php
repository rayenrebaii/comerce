@extends('Admin')

@section('content')
<style>
     .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            width: 80px;  /* Taille du logo */
            height: 80px;
            border-radius: 50%; /* Arrondir le logo */
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1); /* Ajout d'ombre au logo */
        }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="invoice p-3 mb-3">
                    <div class="row">
                        <div class="col-12">
                            <h4>
                                <i class="fas fa-globe"></i> Virtual Fashion
                                <small class="float-right">Date: {{ date('d/m/Y') }}</small>
                            </h4>
                        </div>
                    </div>

                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                        FROM 
                            <address>
                                <strong>Virtual Fashion</strong><br>
                                Baker Street, London<br>
                                London, 221B<br>
                                Phone number : 01 23 45 67 89<br>
                                Email: Virtual.Fashion@business.com
                            </address>
                        </div>
                        <div class="col-sm-4 invoice-col">
                            TO
                            <address>
        <strong>Client: {{ $clientName }}</strong><br>
        Email: {{ $clientEmail }}<br>
    </address>
                        </div>
                        <div class="col-sm-4 invoice-col">
                            <b>Facture #{{ $commande->id }}</b><br>
                            <br>
                            <div class="logo-container">
                            <img src="https://res.cloudinary.com/dwg2nov2v/image/upload/v1733588039/back_blanc_uqujoo.jpg" alt="Logo">
                        </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th>Product                                         </th>
                                        <th>Quantity</th>
                                        
                                        <th>Unit price                                        </th>
                                        <th>Discount (%)</th>
                                        <th>Total after discount
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commande->products as $product)
                                    <tr>
                                    <td>{{ $product->product_name }}</td>
                                        <td>{{ $product->pivot->quantity }}</td>
                                        
                                        <td>{{ number_format($product->pivot->price, 2) }} TND</td>
                                        <td>{{ $product->discount ?? 0 }}%</td>
                                        <td>
                                            {{ number_format(
                                                $product->pivot->quantity * $product->pivot->price * (1 - ($product->discount ?? 0) / 100),
                                                2
                                            ) }} TND
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <!-- Si nécessaire, ajouter d'autres détails -->
                        </div>
                        <div class="col-6">
                        <p class="lead">Amount due                        {{ date('d/m/Y', strtotime(now())) }}</p>

                            <div class="table-responsive">
                                <table class="table">
                                    
                                    <tr>
                                        <th>Total HT:</th>
                                        <td>{{ number_format($totalApresDiscount, 2) }} TND</td>
                                    </tr>
                                    <tr>
                                        <th>TVA (19%):</th>
                                        <td>{{ number_format($tva, 2) }} TND</td>
                                    </tr>
                                    <tr>
                                        <th>Total TTC:</th>
                                        <td>{{ number_format($totalAPayer, 2) }} TND</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row no-print">
                        <div class="col-12">
                            <button style="float :right;" class="btn btn-default" onclick="window.print();"><i class="fas fa-print"></i> Imprimer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
