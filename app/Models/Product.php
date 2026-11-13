<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'produits'; 
    protected $fillable = [
        'product_name',
        'product_description',
        'product_price',
        'quantity_available',
        'product_img',
        'discount',
        'category_id'
    ];

    //Relation avec la table Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function commandes()
    {
        return $this->belongsToMany(Commande::class)->withPivot('quantity', 'price');
    }
    
}
