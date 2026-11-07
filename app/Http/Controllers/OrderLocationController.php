<?php

namespace App\Http\Controllers;

use App\Models\OrderLocation;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderLocationController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderLocation::with('product');

        if ($request->filled('product_id')) {
            $query->where('product_id', (int) $request->input('product_id'));
        }

        if ($request->filled('search')) {
            $search = trim($request->string('search'));
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($pq) use ($search) {
                        $pq->where('name', 'like', "%{$search}%")
                            ->orWhere('category', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    });
            });
        }

        $locations = $query->latest()->get();
        $products = Product::orderBy('name')->get();

        $totalLocations = OrderLocation::count();
        $mappedProductsCount = OrderLocation::distinct('product_id')->count('product_id');
        $totalOrderedQty = (int) OrderLocation::sum('quantity');

        $mapMarkers = $locations->filter(fn (OrderLocation $loc) => $loc->product !== null)->map(function (OrderLocation $loc) {
            return [
                'id' => $loc->id,
                'latitude' => (float) $loc->latitude,
                'longitude' => (float) $loc->longitude,
                'customer_name' => $loc->customer_name ?: 'Pelanggan UMKM',
                'quantity' => (int) $loc->quantity,
                'address' => $loc->address ?: 'Koordinat tercatat di peta',
                'product_id' => $loc->product->id,
                'product_code' => 'P'.str_pad((string) $loc->product->id, 3, '0', STR_PAD_LEFT),
                'product_name' => $loc->product->name,
                'product_category' => $loc->product->category ?: 'Umum',
                'product_description' => $loc->product->description ?: 'Produk unggulan UMKM.',
                'product_price_formatted' => 'Rp'.number_format((float) $loc->product->price, 0, ',', '.'),
                'product_stock' => number_format((int) $loc->product->stock, 0, ',', '.').' pcs',
            ];
        })->values();

        return view('map.index', compact(
            'locations',
            'products',
            'totalLocations',
            'mappedProductsCount',
            'totalOrderedQty',
            'mapMarkers'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'address' => ['nullable', 'string', 'max:500'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        OrderLocation::create($validated);

        return redirect()->route('map.index')
            ->with('success', 'Titik lokasi pemesanan produk berhasil ditambahkan ke peta!');
    }

    public function destroy(OrderLocation $orderLocation)
    {
        $orderLocation->delete();

        return redirect()->route('map.index')
            ->with('success', 'Titik lokasi pemesanan berhasil dihapus!');
    }
}
