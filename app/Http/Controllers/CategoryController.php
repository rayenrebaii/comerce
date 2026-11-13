<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Fonction pour afficher la liste des catégories
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->paginate(5);
        return view('Category.index', compact('categories'));
    }

    // Fonction pour afficher le formulaire de création
    public function create()
    {
        return view("Category.create");
    }

    // Fonction pour stocker une nouvelle catégorie
    public function store(Request $request)
    {
        Category::create($request->all());
        return redirect()->route('categories.index')->with('info', 'Category created with success');
    }

    // Fonction pour afficher une catégorie
    public function show(Category $category)
    {
        return view('Category.show', compact('category'));
    }

    // Fonction pour afficher le formulaire d'édition
    public function edit(Category $category)
    {
        return view('Category.edit', compact('category'));
    }

    // Fonction pour mettre à jour une catégorie
    public function update(Request $request, Category $category)
    {
        $category->update($request->all());
        return redirect()->route('categories.index')->with('info', 'Category modified with success');
    }

    // Fonction pour supprimer une catégorie
    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('info', 'Category deleted with success');
    }
}
