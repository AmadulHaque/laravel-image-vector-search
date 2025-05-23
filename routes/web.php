<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/products');
Route::resource('products', ProductController::class)->only(['index', 'create','store']);
Route::post('products/search', [ProductController::class, 'search'])->name('products.search');
Route::post('/weaviate/init', [ProductController::class, 'initWeaviate'])->name('weaviate.init');

Route::get('/test-weaviate', function() {
    $url = env('WEAVIATE_URL');
    $apiKey = env('WEAVIATE_API_KEY');

    try {
        $client = new \GuzzleHttp\Client();

        $response = $client->get("$url/v1/schema", [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ]
        ]);

        return response()->json([
            'status' => 'success',
            'weaviate_url' => $url,
            'response' => json_decode($response->getBody(), true)
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'weaviate_url' => $url,
            'error' => $e->getMessage()
        ], 500);
    }
});
