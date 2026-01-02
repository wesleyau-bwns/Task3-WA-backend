<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    // List all merchants
    public function index()
    {
        return response()->json(Merchant::all());
    }

    // Show a merchant
    public function show($id)
    {
        return response()->json(Merchant::findOrFail($id));
    }

    // Show merchant products
    public function products($id)
    {
        $merchant = Merchant::findOrFail($id);
        return response()->json($merchant->products);
    }

    // Add new product
    public function addProduct(Request $request, $id)
    {
        $merchant = Merchant::findOrFail($id);
        $product = $merchant->products()->create($request->all());
        return response()->json($product, 201);
    }
}
