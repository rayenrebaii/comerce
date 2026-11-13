<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;
    protected $fillable=[
        'nom_categorie',
        'description'
    ];
    //Relation avec la table Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    // Dans le modèle Category
public function produits()
{
    return $this->hasMany(Product::class);
}

}

