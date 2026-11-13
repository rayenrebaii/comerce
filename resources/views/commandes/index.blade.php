@extends('Admin')

@section('content')
<div class="wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="col-6 h2" style="font-weight: bold; color: #004085;">
                    Order List
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success" id="success-message">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger" id="error-message">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product(s)</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commandes as $commande)
                                <tr>
                                    
                                    <td>
                                        @foreach($commande->products as $product)
                                            {{ $product->product_name }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($commande->products as $product)
                                            {{ $product->pivot->quantity }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($commande->products as $product)
                                            {{ number_format($product->pivot->price, 2) }}€<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($commande->status == 0)
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($commande->status == 1)
                                            <span class="badge badge-success">Confirmed</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            @if($commande->status == 0)
                                                <form action="{{ route('commandes.updateStatus', $commande->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="1">
                                                    <button type="submit" class="btn btn-success ml-1">Confirm</button>
                                                </form>
                                            @endif

                                            @if($commande->status != 1)
                                                <form action="{{ route('commandes.destroy', $commande->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger ml-1">Cancel</button>
                                                </form>
                                            @endif

                                            <a href="{{ route('commandes.facture', $commande->id) }}" class="btn btn-primary ml-1">Print</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
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
    <!-- /.content -->
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
