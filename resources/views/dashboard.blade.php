@extends('Admin')

@section('content')
<!-- Main content -->
<div class="wrapper ">
    <section class="content">
        <div class="container-fluid">
            <!-- Première ligne avec 3 sections -->
            <div class="row">
                <!-- Total Utilisateurs -->
                <div class="col-lg-4 col-6">
                <div class="small-box  shadow-lg rounded" style="background-color: #c5ace8;">
                        <div class="inner">
                            <h3 class="text-center">{{ $totalCategories }}</h3>
                            <p class="text-center">Total Categories</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-th-list"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Produits -->
                <div class="col-lg-4 col-6">
                    <div class="small-box  shadow-lg rounded" style="background-color: #e8e6a5;">
                        <div class="inner">
                            <h3 class="text-center">{{ $totalProducts }}</h3>
                            <p class="text-center">Total Products</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Commandes Validées -->
                <div class="col-lg-4 col-6">
                <div class="small-box  shadow-lg rounded" style="background-color: #acbbe8;">
                        <div class="inner">
                            <h3 class="text-center">{{ $totalUsers }}</h3>
                            <p class="text-center">Registered Customers</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deuxième ligne avec 3 sections -->
            <div class="row">
                <!-- Total Catégories -->
                <div class="col-lg-4 col-6">
                
                    <div class="small-box  shadow-lg rounded" style="background-color: #bbe8b5;">
                        <div class="inner">
                            <h3 class="text-center">{{ $totalOrders }}</h3>
                            <p class="text-center">Total Validated Orders</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>

                <!-- Total des Revenus -->
                <div class="col-lg-4 col-6">
                    <div class="small-box  shadow-lg rounded" style="background-color: #f0b884;">
                        <div class="inner">
                            <h3 class="text-center">{{ number_format($totalRevenue, 2) }} €</h3>
                            <p class="text-center">Total Revenue</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>

                <!-- Client le plus fidèle -->
                <div class="col-lg-4 col-6">
                    <div class="small-box  shadow-lg rounded" style="background-color: #eb9da2;">
                        <div class="inner">
                            <h3 class="text-center">{{ $topClientName }}</h3>
                            <p class="text-center">Top Client with  {{ $topClientOrders }} Orders</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Diagramme circulaire des ventes par catégorie et Tableau des produits en rupture de stock -->
            <div class="row mt-5">
                <!-- Pie chart (sales by category) -->
                <div class="col-md-6">
                    <div class="card card-outline ">
                        <div class="card-header "style="background-color: #ee7078;">
                        <h4 class="text-center">Sales Distribution by Category</h4>
                        </div>
                        <div class="card-body "style="background-color: #f0bcbf;">
                            <canvas id="salesPieChart" class="img-fluid" style="max-width: 80%; height: auto; margin: 0 auto; display: block;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Table for Low Stock Products -->
                <div class="col-md-6">
                    <div class="card card-outline ">
                        <div class="card-header "style="background-color: #ee7078;">
                            <h4 class="text-center">Low Stock Products</h4>
                        </div>
                        <div class="card-body "style="background-color: #f0bcbf;">
                            <!-- Tableau -->
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom du Produit</th>
                                        <th>Quantité Disponible</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lowStockProducts as $product)
                                    <tr>
                                        <td>{{ $product->product_name }}</td>
                                        <td>
                                            @if ($product->quantity_available < 5)
                                            <span class="badge bg-danger">{{ $product->quantity_available }}</span>
                                            @else
                                            <span class="badge bg-warning">{{ $product->quantity_available }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                           <!-- Pagination -->
<div class="d-flex flex-column align-items-center mt-3">
    <div class="d-flex">
        @if ($lowStockProducts->onFirstPage())
            <span class="btn btn-secondary disabled">
                <i class="fas fa-chevron-left"></i> Previous
            </span>
        @else
            <a href="{{ $lowStockProducts->previousPageUrl() }}" class="btn btn-primary mx-2">
                <i class="fas fa-chevron-left"></i> Previous
            </a>
        @endif

        @if ($lowStockProducts->hasMorePages())
            <a href="{{ $lowStockProducts->nextPageUrl() }}" class="btn btn-primary mx-2">
                <i class="fas fa-chevron-right"></i> Next
            </a>
        @else
            <span class="btn btn-secondary disabled">
                <i class="fas fa-chevron-right"></i> Next
            </span>
        @endif
    </div>
</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-header " style="background-color: #ee7078;">
            <h4 class="text-center">Order Delivery Status</h4>
            </div>
            <div class="card-body "style="background-color: #f0bcbf;">
                <!-- Canvas où l'histogramme sera affiché -->
                <canvas id="ordersHistogram"></canvas>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-header "style="background-color: #ee7078;">
            <h4 class="text-center">Order Validation Status</h4>
            </div>
            <div class="card-body  "style="background-color: #f0bcbf;">
                <!-- Canvas où l'histogramme sera affiché -->
                <canvas id="statusHistogram"></canvas>
            </div>
        </div>
    </div>
</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    const salesData = @json($categorySales);

    // Données pour le graphique circulaire
    const labels = salesData.map(data => data.category_name);
    const percentages = salesData.map(data => data.sale_percentage);

    // Couleurs pour le graphique
    const chartColors = [
        'rgba(232, 230, 165, 0.7)',
        'rgba(172, 187, 232, 0.7)',  // #acbbe8
        'rgba(187, 232, 181, 0.7)',  // #bbe8b5
        'rgba(240, 184, 132, 0.7)' ,
        'rgba(197, 172, 232, 0.7)',  // #f0b884
    ];

    // Graphique circulaire (Pie Chart)
    const ctxPie = document.getElementById('salesPieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: percentages,
                backgroundColor: chartColors.slice(0, salesData.length),
                borderColor: chartColors.slice(0, salesData.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false, // Masquer la légende
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.raw.toFixed(2) + '%';
                        }
                    }
                },
                datalabels: {
                    display: true, // Afficher les labels
                    color: 'black', // Couleur du texte
                    align: 'center', // Centrer le texte dans chaque segment
                    font: {
                        weight: 'bold', // Gras
                        size: 14 // Taille de la police
                    },
                    formatter: (value, context) => {
                        return context.chart.data.labels[context.dataIndex]; // Afficher le nom de la catégorie
                    }
                }
            }
        }
    });
</script>


<!-- Ajouter un conteneur pour l'histogramme -->


<script>
    // Récupérer les données des commandes envoyées depuis le backend
    var deliveredOrders = {{ $deliveredOrders }};
    var undeliveredOrders = {{ $undeliveredOrders }};
    
    // Créer l'histogramme avec Chart.js
    var ctx = document.getElementById('ordersHistogram').getContext('2d');
    var ordersHistogram = new Chart(ctx, {
        type: 'bar',  // Type d'histogramme
        data: {
            labels: ['Delivered', 'Not Delivered'], // Étiquettes pour les barres
            datasets: [{
                data: [deliveredOrders, undeliveredOrders], // Données à afficher
                backgroundColor: ['rgba(187, 232, 181, 0.7)', 'rgba(240, 184, 132, 0.7)'], // Couleurs pour les barres
                borderColor: ['rgba(187, 232, 181, 0.7)', 'rgba(240, 184, 132, 0.7)'], // Couleurs pour les bordures
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,  // Rend le graphique responsive
            plugins: {
                legend: {
                    display: false // Masque la légende
                }
            },
            scales: {
                y: {
                    beginAtZero: true, // Commence le graphique à zéro
                    ticks: {
                        stepSize: 1 // Espacement entre les barres
                    }
                }
            }
        }
    });
</script>



<!-- Ajouter un conteneur pour l'histogramme -->


<script>
    // Récupérer les données des commandes envoyées depuis le backend
    var livre = {{ $livre }};  // Commandes livrées
    var nonLivre = {{ $nonLivre }};  // Commandes non livrées
    
    // Créer l'histogramme avec Chart.js
    var ctx = document.getElementById('statusHistogram').getContext('2d');
    var statusHistogram = new Chart(ctx, {
        type: 'bar',  // Type d'histogramme
        data: {
            labels: ['Confirmed', 'Not confirmed'], // Étiquettes pour les barres
            datasets: [{
                data: [livre, nonLivre], // Données à afficher
                backgroundColor: ['rgba(187, 232, 181, 0.7)', 'rgba(240, 184, 132, 0.7)'], // Couleurs pour les barres
                borderColor: ['rgba(187, 232, 181, 0.7)', 'rgba(240, 184, 132, 0.7)'], // Couleurs pour les bordures
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,  // Rend le graphique responsive
            plugins: {
                legend: {
                    display: false // Masque la légende
                }
            },
            scales: {
                y: {
                    beginAtZero: true, // Commence le graphique à zéro
                    ticks: {
                        stepSize: 1 // Espacement entre les barres
                    }
                }
            }
        }
    });
</script>



@endsection
