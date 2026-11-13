<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportProduits; 

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer tous les produits avec leur catégorie associée, paginés par 5
        $produits = Product::with('category')
    ->orderBy('created_at', 'desc') // Trier les produits par date de création (les plus récents d'abord)
    ->paginate(5);
        return view('Product.index', compact('produits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Récupérer toutes les catégories pour le formulaire de création
        $categories = Category::all(); 
        return view('Product.create', compact('categories')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valider les données entrantes
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'product_price' => 'required|numeric',
            'quantity_available' => 'required|integer',
            'product_img' => 'nullable|string',
            'discount' => 'nullable|integer|min:0|max:99',
            'category_id' => 'required|exists:categories,id',
        ]);
    
        // Créer le produit
        Product::create($request->all());
    
        // Rediriger vers la liste des produits avec un message de succès
        return redirect()->route('produits.index')->with('info', 'Product created with success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $produit)
    {
        return view('Product.show', compact('produit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $produit)
    {
        $categories = Category::all();
        return view('Product.edit', compact('produit', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $produit)
{
    // Validation des données entrantes
    $request->validate([
        'product_name' => 'required|string|max:255',
        'product_description' => 'nullable|string',
        'product_price' => 'required|numeric',
        'quantity_available' => 'required|integer',
        'discount' => 'nullable|integer|min:0|max:99',
        'category_id' => 'nullable|exists:categories,id',
    ]);

    // Mettre à jour le produit
    $produit->update($request->all());
    return redirect()->route('produits.index')->with('info', 'Product updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $produit)
 
    {
        $produit->delete();
        return back()->with('info', 'Produit deleted with success');
    }

    ////////////////
    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv',
    ]);

    // Chemin vers le fichier
    $filePath = $request->file('file')->store('imports'); // Assurez-vous que le dossier 'imports' existe dans 'storage/app/'

    // Ouvrir le fichier CSV
    if (($handle = fopen(storage_path('app/' . $filePath), 'r')) !== FALSE) {
        $isFirstRow = true; // Flag pour vérifier si c'est la première ligne
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if ($isFirstRow) {
                $isFirstRow = false; // Passer la première ligne
                continue;
            }

            // Crée une nouvelle instance de Product
            Product::create([
                'product_name' => $data[0],
                'product_description' => $data[1],
                'product_price' => $data[2],
                'quantity_available' => $data[3],
                'product_img' => $data[4],
                'discount' => $data[5],
                
            ]);
        }
        fclose($handle);
    }

    return redirect()->back()->with('info', 'File successfully imported');
}

public function clientIndex(Request $request)
{
    $categories = Category::all(); // Récupère toutes les catégories

    $produits = Product::query();

    // Filtrage par catégorie
    if ($request->has('category') && $request->category != 'all') {
        $produits->where('category_id', $request->category);
    }

    // Recherche par nom
    if ($request->has('search')) {
        $produits->where('product_name', 'like', '%' . $request->search . '%');
    }

    $produits = $produits->paginate(9)->appends([
        'category' => $request->category, // Conserve la catégorie sélectionnée
        'search' => $request->search      // Conserve la recherche
    ]);

    return view('Product.clientproduit', compact('produits', 'categories'));
}









    
    
}
