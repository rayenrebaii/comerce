<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;

class LivraisonController extends Controller
{
    // Affichage de toutes les commandes
    public function index(Request $request)
    {
        // Récupérer les filtres depuis la requête
        $email = $request->query('email');
        $name = $request->query('name');
        $status = $request->query('status');
        $sort = $request->query('sort', 'created_at'); // Tri par date de création par défaut
        $order = $request->query('order', 'desc');

        // Construire la requête avec des filtres dynamiques
        $commandes = Commande::with(['user', 'products'])
            ->when($email, function ($query, $email) {
                return $query->whereHas('user', function ($q) use ($email) {
                    $q->where('email', 'like', "%$email%");
                });
            })
            ->when($name, function ($query, $name) {
                return $query->whereHas('user', function ($q) use ($name) {
                    $q->where('name', 'like', "%$name%");
                });
            })
            ->when($status !== null, function ($query) use ($status) {
                return $query->where('livraison', $status);
            })
            ->orderBy($sort, $order)
            ->paginate(6); // Pagination

        return view('livraison.index', compact('commandes'));
    }

    // Affichage des détails d'une commande
    public function show($id)
    {
        // Récupérer la commande avec les produits et l'utilisateur associé
        $commande = Commande::with(['user', 'products'])->findOrFail($id);

        $totalHT = 0; // Total HT (avant TVA)
        $totalTTC = 0; // Total TTC (avec TVA)
        $tva = 0; // TVA

        // Calcul du total HT (prix après remise) pour chaque produit
        foreach ($commande->products as $product) {
            // Calcul du prix après remise pour chaque produit (prix * quantité * (1 - remise))
            $prixAjuste = $product->pivot->price * (1 - ($product->discount ?? 0) / 100);
            $totalHT += $prixAjuste * $product->pivot->quantity; // Somme des prix après remise

            // Calcul du total TTC pour chaque produit (prix après remise * quantité)
            $totalTTC += $prixAjuste * $product->pivot->quantity;
        }

        // Calcul de la TVA sur le total HT (ici, TVA à 19%)
        $tva = $totalHT * 0.19;

        // Calcul du total TTC (Total HT + TVA)
        $totalTTC = $totalHT + $tva;

        // Passer les valeurs calculées à la vue
        return view('livraison.show', compact('commande', 'totalHT', 'totalTTC', 'tva'));
    }

    public function updateStatus($id)
    {
        $commande = Commande::findOrFail($id);

        // Mettre à jour le statut de la livraison
        $commande->livraison = true;
        $commande->save();

        // Retourner à la liste des commandes avec un message de succès
        return redirect()->route('livraison.index')->with('success', 'La commande a été livrée avec succès.');
    }
}

