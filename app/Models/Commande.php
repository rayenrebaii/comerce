<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'status', // Ajout du champ 'status'
        'livraison', 
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec les produits
    public function products()
    {
        return $this->belongsToMany(Product::class, 'commande_product')->withPivot('quantity', 'price');
    }
    // Dans le modèle Commande
public function produits()
{
    return $this->belongsToMany(Product::class, 'commande_product');
}


    // Scopes pour filtrer les commandes par statut
    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 1);
    }
    // Scope pour les commandes non livrées
public function scopePendingDelivery($query)
{
    return $query->where('livraison', 0);
}

// Scope pour les commandes livrées
public function scopeDelivered($query)
{
    return $query->where('livraison', 1);
}

    
}
