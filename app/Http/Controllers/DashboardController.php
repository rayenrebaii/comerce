<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Commande;
use App\Models\Category;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total des utilisateurs sauf ceux ayant le rôle client (role = 0)
        $totalUsers = User::where('role', '!=', 1)->count();
    
        // Total des produits
        $totalProducts = Product::count();
    
        // Total des commandes validées
        $totalOrders = Commande::where('status', 1)->count();
    
        // Total des catégories
        $totalCategories = Category::count();
    
        // Calcul du total des revenus
        $totalRevenue = Commande::where('status', 1)->sum('total');
    
        // Trouver le client le plus fidèle
        $topClient = Commande::select('user_id', DB::raw('count(*) as total_orders'))
                             ->groupBy('user_id')
                             ->orderByDesc('total_orders')
                             ->first();
    
        $topClientName = $topClient ? User::find($topClient->user_id)->name : 'Aucun client trouvé';
        $topClientOrders = $topClient ? $topClient->total_orders : 0;
    
        // Calcul du total des ventes par catégorie
        $categorySales = Category::select(
            'categories.nom_categorie as category_name',
            DB::raw('SUM(commandes_products.quantity * produits.product_price) as total_sales')
        )
        ->join('produits', 'categories.id', '=', 'produits.category_id')
        ->join('commande_product as commandes_products', 'produits.id', '=', 'commandes_products.product_id')
        ->join('commandes', 'commandes_products.commande_id', '=', 'commandes.id')
        ->where('commandes.status', 1)
        ->groupBy('categories.nom_categorie')
        ->get();
    
        // Calcul du total des ventes sur le site
        $totalSiteSales = $categorySales->sum('total_sales');
    
        // Calcul du taux de vente par catégorie
        foreach ($categorySales as $category) {
            $category->sale_percentage = $totalSiteSales > 0 ? ($category->total_sales / $totalSiteSales) * 100 : 0;
        }
    
        // Récupérer les 5 produits avec les plus grandes quantités disponibles
        $topProducts = Product::orderBy('quantity_available', 'desc')->take(5)->get();
    
        // Produits avec faible stock
        $lowStockProducts = Product::where('quantity_available', '<', 10)
        ->orderBy('quantity_available', 'asc')
        ->paginate(4);

        //Nombre de commandes livrées et non livrées
        $deliveredOrders = Commande::where('livraison', 1)->count();
        $undeliveredOrders = Commande::where('livraison', 0)->count();

        // Nombre de commandes livrées (statut = 1) et non livrées (statut = 0)
$livre = Commande::where('status', 1)->count();  // Commandes livrées
$nonLivre = Commande::where('status', 0)->count();  // Commandes non livrées

    
        
    
        // Passer les données à la vue
        return view('dashboard', compact(
            'totalUsers', 
            'totalProducts', 
            'totalOrders',
            'totalCategories',
            'totalRevenue',
            'topClientName',
            'topClientOrders',
            'categorySales', 
            'topProducts', 
            'lowStockProducts',
            'deliveredOrders',
            'undeliveredOrders',
            'livre', 
            'nonLivre'
           
        ));
    }
}
