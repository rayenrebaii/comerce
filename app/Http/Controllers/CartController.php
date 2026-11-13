<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->get();
        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:produits,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $cartItem = CartItem::where('user_id', Auth::id())
        ->where('product_id', $request->product_id)
        ->first();

    if ($cartItem) {
        $cartItem->quantity += $request->quantity;
        $cartItem->save();
    } else {
        $cartItem = CartItem::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);
    }

    return response()->json(['success' => true]);
}


    

    public function destroy($id)
{
    $cartItem = CartItem::findOrFail($id);
    $cartItem->delete();

    return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
}

    public function update(Request $request, $id)
{
    $request->validate([
        'quantity' => 'required|integer|min:1',
    ]);

    $cartItem = CartItem::findOrFail($id);
    $cartItem->quantity = $request->quantity;
    $cartItem->save();

    return redirect()->route('cart.index')->with('success', 'Quantité mise à jour avec succès!');
}

public function clear()
{
    $userId = auth()->id();
    CartItem::where('user_id', $userId)->delete(); // Supprime tous les articles du panier

    return redirect()->route('cart.index')->with('success', 'Panier vidé avec succès!');
}
}
