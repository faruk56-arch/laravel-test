<?php

namespace App\Repositories;

use App\Models\Research;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ResearchRepository extends BaseRepository
{
    /**
     * {@inheritdoc}
     */
    public function model()
    {
        return Research::class;
    }

    public function getFromUserWithTerms($userId)
    {
        $results = $this->model->with('terms')->where('user_id', $userId)->get();
        if ($results->isEmpty()) {
            throw new ModelNotFoundException;
        }

        return response()->json([
                'data'=> $results,
            ], 200);
    }

    public function getProductsWithMerchants($researchId)
    {
        return response()->json([
                'data'=>$this->model->with('products.merchant')->where('id', $researchId)->get(),
        ], 200);
    }

    public function getStatus($id)
    {
        return response()->json([
                'data'=>$this->model->findOrFail($id)->status,
            ], 200);
    }
}
