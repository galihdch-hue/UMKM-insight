<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $numericId = preg_replace('/^P0*/i', '', $search);

            $query->where(function ($q) use ($search, $numericId) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");

                if (is_numeric($numericId)) {
                    $q->orWhere('id', (int) $numericId);
                }
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        if ($request->filled('max_price') && is_numeric($request->input('max_price'))) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        if ($request->filled('stock_status')) {
            match ($request->string('stock_status')->toString()) {
                'available' => $query->where('stock', '>', 20),
                'low' => $query->whereBetween('stock', [1, 20]),
                'out' => $query->where('stock', '<=', 0),
                default => null,
            };
        }

        $products = $query->orderBy('id')->paginate(10)->withQueryString();

        $categories = Product::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $totalProducts = Product::count();
        $totalStock = (int) Product::sum('stock');
        $totalInventoryValue = (float) Product::selectRaw('COALESCE(SUM(price * stock), 0) as total')->value('total');

        return view('products.index', compact(
            'products',
            'categories',
            'totalProducts',
            'totalStock',
            'totalInventoryValue'
        ));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show(Product $product)
    {
        return redirect()->route('products.edit', $product);
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
