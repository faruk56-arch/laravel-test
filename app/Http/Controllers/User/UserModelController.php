<?php

namespace App\Http\Controllers\User;

use App\Filters\UserModelFilter;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  User  $user
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserModelFilter $filter, User $user)
    {
        return UserModel::where('user_id', $user->id)
            ->with('researches.part', 'modele.brand')
            ->filter($filter)->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(User $user, Request $request)
    {
        $userModel = UserModel::create(
            [
                'user_id' => $user->id, 'modele_id' => $request->model,
                'car_name' => $request->car_name,
                'year' => $request->modele_year,
                'version' => $request->version,
            ]
        );

        return $userModel->load(['researches.part', 'modele.brand']);
    }

    /**
     * Display the specified resource.
     *
     * @param  UserModel  $userModel
     *
     * @return UserModel
     */
    public function show(User $user, UserModel $userModel)
    {
        return $userModel->load([
            'researches.part', 'modele.brand', 'researches.products',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  UserModel  $userModel
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, UserModel $userModel)
    {
        $userModel->update([
            'car_name' => $request->car_name, 'model_id' => $request->model_id, 'year' => $request->modele_year, 'version' => $request->version,

        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @param  UserModel  $userModel
     *
     * @return JsonResponse
     */
    public function destroy(User $user, UserModel $userModel)
    {
        foreach ($userModel->researches()->get() as $research) {
            $research->delete();
        }
        $userModel->delete();

        return response()->json(null, 204);
    }
}
