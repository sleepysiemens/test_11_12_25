<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class ProductService
{
    const ON_PAGE_COUNT = 10;

    public static function getProducts(array $filters = []): array
    {
        $search = $filters['q'] ?? null;
        $priceFrom = $filters['price_from'] ?? null;
        $priceTo = $filters['price_to'] ?? null;
        $categoryId = $filters['category_id'] ?? null;
        $inStock = Arr::has($filters, 'in_stock') ? $filters['in_stock'] : 'not set';
        $ratingFrom = $filters['rating_from'] ?? null;

        return Product::query()
            ->select([
                'id',
                'name',
                'price',
                'category_id',
                'in_stock',
                'rating',
                'created_at',
                'updated_at',
            ])
            # q
            ->when($search, function (Builder $q) use ($search) {
                $q->where(fn (Builder $q_) =>
                    $q_->where('name', '=', $search)
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search")
                    ->orWhere('name', 'like', "$search%")
                );
            })
            # price_from
            ->when($priceFrom, function (Builder $q) use ($priceFrom) {
                $q->where('price', '>=', $priceFrom);
            })
            # price_to
            ->when($priceFrom, function (Builder $q) use ($priceTo) {
                $q->where('price', '<=', $priceTo);
            })
            #category_id
            ->when($categoryId, function (Builder $q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            #in_stock
            ->when($inStock !== 'not set', function (Builder $q) use ($inStock) {
                $q->where('in_stock', $inStock);
            })
            #rating_from
            ->when($ratingFrom, function (Builder $q) use ($ratingFrom) {
                $q->where('rating', '>=', $ratingFrom);
            })
            ->paginate(self::ON_PAGE_COUNT)
            ->toArray();
    }
}
