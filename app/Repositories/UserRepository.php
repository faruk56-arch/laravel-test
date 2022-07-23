<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository extends BaseRepository
{
    /**
     * {@inheritdoc}
     */
    public function model()
    {
        return User::class;
    }

    public function changeCarName($userId, $carId, $carName)
    {
        try {
            $cars = User::find($userId)->cars();

            return response()->json([
                'data'=> $cars->updateExistingPivot($carId, ['car_name'=>$carName]),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'msg' => 'La requête a renvoyé une array vide',
                'error'=> $e->getMessage(),
            ], 404);
        } catch (\PDOException $e) {
            return response()->json([
                'msg' => 'Problème de base de données',
                'error'=>$e->getMessage(),
            ], 500);
        }
    }
}
