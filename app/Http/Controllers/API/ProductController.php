<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Throwable;

class ProductController extends Controller
{

    public function __construct(protected ProductService $productService) {}
    public function getProducts(): JsonResponse
    {
        $query = request()->query();

        $rules = [
            'q'           => ['string', 'nullable'],
            'price_from'  => ['numeric', 'min:0', 'nullable'],
            'price_to'    => ['numeric', 'min:0', 'nullable'],
            'category_id' => ['int', 'nullable'],
            'in_stock'    => ['boolean', 'nullable'],
            'rating_from' => ['numeric', 'nullable', 'min:0'],
            'sort'        => ['string', 'nullable']
        ];

        try {
            validator($query, $rules)->validate();

        } catch (Throwable $e) {
            return response()->json($e->getMessage(), $e->getCode() ?: 400, [], JSON_PRETTY_PRINT);
        }

        return response()->json($this->productService::getProducts($query), 200, [], JSON_PRETTY_PRINT);
    }
}
