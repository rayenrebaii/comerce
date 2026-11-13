<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\CartItem;
use App\Models\Product; // Modèle pour les produits
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommandeController extends Controller
{
    // Afficher toutes les commandes du client
    public function index()
    {
        // Récupérer les commandes du client avec les produits associés et les paginer
        $commandes = Commande::with('products')
            ->where('user_id', Auth::id())
            ->paginate(5); // 10 commandes par page

        return view('commandes.index', compact('commandes'));
    }

    // Passer une commande en utilisant les items du panier
    public function passerCommande(Request $request)
    {
        $userId = auth()->id(); // Récupère l'ID de l'utilisateur authentifié
        $cartItems = CartItem::where('user_id', $userId)->get(); // Récupère les items du panier

        if ($cartItems->isEmpty()) {
            return redirect()->route('commandes.index')->with('error', 'Votre panier est vide.');
        }

        // Créez une nouvelle commande
        $commande = Commande::create([
            'user_id' => $userId,
            'total' => $cartItems->sum(function ($item) {
                return $item->product->product_price * (1 - $item->product->discount / 100) * $item->quantity;
            }),
        ]);

        // Ajoutez chaque article du panier à la commande et supprimez-le du panier
        foreach ($cartItems as $item) {
            $commande->products()->attach($item->product_id, [
                'quantity' => $item->quantity,
                'price' => $item->product->product_price * (1 - $item->product->discount / 100), // Prix après réduction
            ]);
            $item->delete(); // Supprimez l'élément du panier
        }

        return redirect()->route('commandes.index')->with('success', 'Commande passée avec succès!');
    }

    // Enregistrer une nouvelle commande (en cas de passage direct sans panier)
    public function store(Request $request)
    {
        // Validation des données de la requête
        $request->validate([
            'product_id' => 'required|exists:produits,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
        ]);

        // Création de la commande
        Commande::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return redirect()->route('commandes.index')->with('success', 'Commande passée avec succès !');
    }

    // Supprimer une commande
   

    // Afficher la facture d'une commande
    public function facture($id)
{
    // Récupérer la commande avec les produits associés
    $commande1 = Commande::with('user')->find($id);
    $commande = Commande::with('products')->findOrFail($id);

    // Initialiser les totaux
    $totalAvantDiscount = 0; // Somme des prix sans réduction
    $totalApresDiscount = 0; // Somme des prix avec réduction

    foreach ($commande->products as $product) {
        // Prix total initial (avant réduction)
        $prixTotalProduit = $product->pivot->quantity * $product->pivot->price;
        $totalAvantDiscount += $prixTotalProduit;

        // Appliquer le discount
        $discount = $product->discount ?? 0; // Par défaut 0 si pas de discount
        $prixApresDiscount = $prixTotalProduit * (1 - ($discount / 100)); // Appliquer la réduction
        $totalApresDiscount += $prixApresDiscount;
    }

    // Calculer la TVA (19%) sur le total après discount
    $tva = $totalApresDiscount * 0.19;

    // Calculer le montant total à payer (total après réduction + TVA)
    $totalAPayer = $totalApresDiscount + $tva;
    // Récupérer les informations du client à partir de la relation user
    $clientName = $commande1->user->name ?? 'Nom indisponible';
    $clientEmail = $commande1->user->email ?? 'Email indisponible';

    return view('commandes.facture', compact(
        'commande',
        'totalAvantDiscount',  // Total avant réduction
        'totalApresDiscount',  // Total après réduction
        'tva',                 // Montant de la TVA
        'totalAPayer',          // Montant total à payer
        'commande1',
        'clientName',
        'clientEmail'
    ));
}

    

    // Mettre à jour le statut d'une commande
   // App/Http/Controllers/CommandeController.php
   public function updateStatus(Request $request, $id)
{
    $commande = Commande::with('products')->findOrFail($id);

    if ($request->status == 1) { // Si le statut est "confirmé"
        foreach ($commande->products as $product) {
            // Vérifiez que la quantité disponible est suffisante
            if ($product->quantity_available < $product->pivot->quantity) {
                return redirect()->route('commandes.index')->with('error', "Stock insuffisant pour le produit : {$product->product_name}");
            }

            // Réduisez la quantité disponible
            $product->quantity_available -= $product->pivot->quantity;
            $product->save();
        }
    }

    // Mettre à jour le statut de la commande
    $commande->status = $request->status;
    $commande->save();

    return redirect()->route('commandes.index')->with('success', 'Statut de la commande mis à jour avec succès !');
}


   public function destroy($id)
{
    $commande = Commande::findOrFail($id);
    $commande->delete();

    return redirect()->route('commandes.index')->with('success', 'Commande annulée et supprimée avec succès !');
}

   

}
