@extends('Admin')

@section('content')
   <!-- Content Header (Page header) -->
   <div class="content-header">
        <div class="container-fluid">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="col-6 h2" style="font-weight: bold; color: #004085;">
                    Shop
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>

                    <li class="breadcrumb-item active">Shop</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
  
  <!-- Formulaire de recherche -->
  <div class="container" style="background-color: #f5f5f5; padding: 20px; border-radius: 15px;">
    <div class="row">
        <form method="GET" action="{{ route('client.produits') }}" class="col-md-12">
            <div class="row" >
                <div class="col-md-4">
                    <!-- Sélecteur de catégorie -->
                    <select name="category" class="form-control" onchange="this.form.submit()">
                        <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->nom_categorie }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <!-- Champ de recherche par nom de produit -->
                    <input type="text" name="search" class="form-control" placeholder="Search by product name"
                        value="{{ request('search') }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-4">
                    <!-- Bouton de recherche -->
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"> Search</i></button>
                </div>
            </div>
        </form>
    </div>
  </div>

  <!-- Liste des produits -->
  <div class="container" style="background-color: #f5f5f5; padding: 20px; border-radius: 15px;">
    <div class="row">
        @foreach($produits as $produit)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm" style="border-radius: 15px; overflow: hidden; transition: transform 0.3s;">
                <div class="card-body position-relative p-3 text-center">

                    <!-- Taux de remise affiché sur l'image -->
                    @if($produit->discount > 0)
                    <div style="position: absolute; top: 10px; left: 10px; background-color: red; color: white; padding: 5px 10px; border-radius: 50px; font-weight: bold;">
                        -{{ $produit->discount }}%
                    </div>
                    @endif

                    <!-- Image du produit -->
                    <a href="#" data-toggle="modal" data-target="#imageModal-{{ $produit->id }}">
                        <img src="{{ $produit->product_img }}" class="card-img-top mb-3" alt="{{ $produit->product_name }}" style="height: 220px; object-fit: cover; border-radius: 15px;">
                    </a>

                    <!-- Nom du produit -->
                    <h3 class="mt-3">{{ $produit->product_name }}</h3>

                    <!-- Description du produit limité à 60 caractères -->
                    <p class="card-text">
                        {{ Str::limit($produit->product_description, 60) }}
                        <a href="#" class="text-primary" data-toggle="modal" data-target="#imageModal-{{ $produit->id }}">...see more</a>
                    </p>

                    <!-- Prix initial et prix après discount -->
                    <p class="card-text">
                        <span style="text-decoration: line-through; color: red;">{{ $produit->product_price }} TND</span>
                        <strong style="color: green;">{{ $produit->product_price - ($produit->product_price * ($produit->discount / 100)) }} TND</strong>
                    </p>

                    <!-- Quantité disponible -->
                    <p class="card-text text-center">
                        <i class="fas fa-boxes"></i> <strong>Quantité disponible : </strong>{{ $produit->quantity_available }}
                    </p>

                    <!-- Sélecteur de quantité et bouton d'ajout au panier -->
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="number" class="form-control me-2" id="quantite-{{ $produit->id }}" min="1" max="{{ $produit->quantity_available }}" value="1" style="width: 70px;">
                        <button type="button" class="btn btn-primary d-flex align-items-center justify-content-center" style="padding: 10px 20px; border-radius: 30px; transition: background-color 0.3s;" onclick="ajouterAuPanier({{ $produit->id }})">
                        add to cart
                            <i class="fas fa-shopping-cart ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal pour afficher l'image et la description complète -->
        <div class="modal fade" id="imageModal-{{ $produit->id }}" tabindex="-1" aria-labelledby="imageModalLabel-{{ $produit->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-lg shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="imageModalLabel-{{ $produit->id }}">{{ $produit->product_name }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Image Section -->
                    <div class="col-md-6 text-center">
                        <img src="{{ $produit->product_img }}" alt="{{ $produit->product_name }}" class="img-fluid rounded shadow" style="max-height: 500px; object-fit: contain;">
                    </div>

                    <!-- Description Section -->
                    <div class="col-md-6">
                        <p class="text-muted" style="font-size: 1.1rem; line-height: 1.6;">
                            {{ $produit->product_description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



        @endforeach
    </div>
  </div>

  <!-- Pagination -->
  <div class="d-flex flex-column align-items-center mt-4">
      <div class="d-flex">
          @if ($produits->onFirstPage())
              <span class="btn btn-secondary disabled">
                  <i class="fas fa-chevron-left"></i> Previous
              </span>
          @else
              <a href="{{ $produits->appends(['category' => request('category'), 'search' => request('search')])->previousPageUrl() }}" class="btn btn-primary mx-2">
                  <i class="fas fa-chevron-left"></i> Previous
              </a>
          @endif

          @if ($produits->hasMorePages())
              <a href="{{ $produits->appends(['category' => request('category'), 'search' => request('search')])->nextPageUrl() }}" class="btn btn-primary mx-2">
                  <i class="fas fa-chevron-right"></i> Next
              </a>
          @else
              <span class="btn btn-secondary disabled">
                  <i class="fas fa-chevron-right"></i> Next
              </span>
          @endif
      </div>

      <div class="mt-3">
          <span>Page {{ $produits->currentPage() }} / {{ $produits->lastPage() }}</span>
      </div>
  </div>

  <script>
      function ajouterAuPanier(produitId) {
          var quantite = document.getElementById('quantite-' + produitId).value;

          if (quantite < 1) {
              alert("Veuillez sélectionner une quantité valide.");
              return;
          }

          $.ajax({
              url: "{{ route('cart.add') }}",
              type: "POST",
              data: {
                  product_id: produitId,
                  quantity: quantite,
                  _token: "{{ csrf_token() }}"
              },
              success: function(response) {
                  if (response.success) {
                      alert(response.message);
                      // Optionnel : Actualiser la vue pour refléter le stock
                  } else {
                      alert("Erreur: " + response.message);
                  }
              },
              error: function(xhr) {
                  alert("Erreur lors de l'ajout au panier.");
              }
          });
      }
  </script>
@endsection
