<?php

namespace App\Http\Controllers;

use App\Models\Product;

class UmkmAnalyticsController extends Controller
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
            'highest_price' => (float) (Product::max('price') ?? 0),
        ];

        $topProducts = Product::orderByDesc('price')
            ->take(6)
            ->get();

        $priceLabels = $topProducts->pluck('name')->values();
        $priceValues = $topProducts->map(fn ($p) => (float) $p->price)->values();

        $categorySummary = Product::selectRaw("COALESCE(NULLIF(category, ''), 'Umum') as category, COUNT(*) as product_count, AVG(price) as average_price, SUM(stock) as total_stock")
            ->groupBy('category')
            ->orderByDesc('product_count')
            ->get();

        $categoryLabels = $categorySummary->pluck('category')->values();
        $categoryValues = $categorySummary->pluck('product_count')->map(fn ($v) => (int) $v)->values();

        return view('umkm.analytics', compact(
            'stats',
            'topProducts',
            'categorySummary',
            'priceLabels',
            'priceValues',
            'categoryLabels',
            'categoryValues'
        ));
    }
}
