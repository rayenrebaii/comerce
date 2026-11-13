<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Imports\ImportProduits;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LivraisonController;


Route::get('/', function () {
    return redirect()->route('login'); // Redirection vers la page d'inscription
});



// Routes de ressource pour les catégories
Route::resource('categories', CategoryController::class);

// Routes de ressource pour les produits
Route::resource('produits', ProductController::class);

Route::post('/import', [ProductController::class, 'import'])->name('products.import');





Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/client/produits', [ProductController::class, 'clientIndex'])->name('client.produits');

Route::middleware(['auth'])->group(function () {
    Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
    Route::post('/panier/ajouter', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/panier/{id}', [CartController::class, 'update'])->name('cart.update'); // Ajoutez cette route
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/panier/passer-commande', [CartController::class, 'passerCommande'])->name('passer.commande');
    Route::delete('/panier/vider', [CartController::class, 'clear'])->name('cart.clear'); // Ajoutez cette route
    
});

Route::middleware(['auth'])->group(function () {
    Route::resource('commandes', CommandeController::class);
    Route::post('/commandes/passer', [CommandeController::class, 'passerCommande'])->name('passer.commande');
// routes/web.php
Route::patch('/commandes/{id}/updateStatus', [CommandeController::class, 'updateStatus'])->name('commandes.updateStatus');
Route::delete('/commandes/{id}', [CommandeController::class, 'destroy'])->name('commandes.destroy');

    Route::get('/commandes/{id}/facture', [CommandeController::class, 'facture'])->name('commandes.facture');

});


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::prefix('livraison')->name('livraison.')->group(function () {
    Route::get('/', [LivraisonController::class, 'index'])->name('index');
    Route::get('/{id}', [LivraisonController::class, 'show'])->name('show');
    Route::patch('/{id}/status', [LivraisonController::class, 'updateStatus'])->name('updateStatus');
});










