<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductImageController extends Controller
{
    public function show(Product $product)
    {
        $image = DB::table('product_images')->where('product_id', $product->id)->first();
        abort_unless($image, 404);

        return response($image->content)
            ->header('Content-Type', $image->mime_type)
            ->header('Cache-Control', 'public, max-age=31536000, immutable')
            ->header('X-Content-Type-Options', 'nosniff');
    }
}
