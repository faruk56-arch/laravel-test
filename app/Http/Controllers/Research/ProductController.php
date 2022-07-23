<?php

namespace App\Http\Controllers\Research;

use App\Exceptions\UnauthorizedResourceException;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Research;
use Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Research  $research
     * @return LengthAwarePaginator|JsonResponse
     * @throws UnauthorizedResourceException
     */
    public function index(Research $research)
    {
        if ($research->user_id !== Auth::id()) {
            throw new UnauthorizedResourceException();
        }
        if (! $research->status === 'finished' || ! $research->products()) {
            throw new ModelNotFoundException();
        }

        return $research->products()->paginate();
    }

    /**
     * Display the specified resource.
     *
     * @param  Research  $research
     * @param  Product  $product
     * @return Collection|\Illuminate\Database\Eloquent\Model
     */
    public function show(Research $research, Product $product)
    {
        if ($research->status === 'finished') {
            return $product;
        }
        throw new ModelNotFoundException();
    }
}
