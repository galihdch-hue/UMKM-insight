<?php

namespace App\Http\Controllers;

use App\Models\OrderLocation;
use App\Models\Product;

class UmkmDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Product::whereNotNull('category')
                ->where('category', '<>', '')
                ->distinct()
                ->count('category'),
            'average_price' => (float) (Product::avg('price') ?? 0),
            'price_total' => (float) Product::sum('price'),
            'stock_total' => (int) Product::sum('stock'),
        ];

        $latestProducts = Product::latest()
            ->take(5)
            ->get(['id', 'name', 'category', 'price', 'stock']);

        $priceLabels = $latestProducts->pluck('name')->values();
        $priceValues = $latestProducts->map(fn ($p) => (float) $p->price)->values();

        $categoryGroup = Product::selectRaw("COALESCE(NULLIF(category, ''), 'Umum') as category_name, COUNT(*) as total")
            ->groupBy('category_name')
            ->get();

        $categoryLabels = $categoryGroup->pluck('category_name')->values();
        $categoryValues = $categoryGroup->pluck('total')->map(fn ($v) => (int) $v)->values();

        $mapPoints = OrderLocation::with('product')
            ->orderBy('longitude')
            ->get()
            ->filter(fn (OrderLocation $loc) => $loc->product !== null)
            ->map(function (OrderLocation $loc) {
                return [
                    'name' => $loc->product->name.' ('.$loc->customer_name.')',
                    'lat' => (float) $loc->latitude,
                    'lng' => (float) $loc->longitude,
                    'info' => ($loc->product->description ?: $loc->product->category).' — Pesanan: '.$loc->quantity.' pcs',
                ];
            })
            ->values();

        return view('umkm.dashboard', compact(
            'stats',
            'latestProducts',
            'priceLabels',
            'priceValues',
            'categoryLabels',
            'categoryValues',
            'mapPoints'
        ));
    }
}
