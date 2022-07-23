<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\User;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            'auth:api',
            [
                'except' => [
                    'login',
                    'register',
                    'emailValidation',
                ],
            ]
        );
    }

    //end __construct()

    /**
     * @param User    $user
     * @param Request $request
     */
    public function update(User $user, Request $request)
    {
        $user->update($request->all());
        if ($this->isMerchantParticulier($user)) {
            $this->updateMerchant($request, $user);
        }

        return $user;
    }

    //end update()

    public function changeViewSettings(Request $request)
    {
        Auth::user()->setMeta($request->type, $request->value);
    }

    //end changeViewSettings()

    public function register(Request $request)
    {
        $request->validate(['email' => 'required|email|unique:users']);
        $user = User::make($request->all());
        $user->saveQuietly();
        if ($request->merchantId) {
            $this->addUserToMerchant($request, $user);
        }

        event('eloquent.created: App\Models\User', $user);
        if ($request->loginAfterSignUp == false) {
            return response()->json(
                [
                    'id' => $user->id,
                ],
                201
            );
        }

        $token = $this->guard()->login($user);

        return $this->respondWithToken($token);
    }

    //end register()

    public function refresh()
    {
        try {
            if ($token = $this->guard()->refresh()) {
                return $this->respondWithToken($token);
            }
            // TODO: Affiche une page blanche, renvoyer vers la page login.
            return response()->json(['error' => 'refresh_token_error'], 401);
        } catch (TokenExpiredException $exception) {
            // TODO: Affiche une page blanche, renvoyer vers la page login.
            return response()->json(['success' => false], 401);
        }
    }

    //end refresh()

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        $user = User::withTrashed()->where('email', $request->email)->first();

        if ($user && $user->deleted_at) {
            return response()->json(['error' => 'Votre compte a été supprimé ou bloqué par l\'administrateur.'], 401);
        }

        return response()->json(['error' => 'Identifiants incorrects.'], 401);
    }

    //end login()

    public function logout()
    {
        $this->guard()->logout();

        return response()->json(
            [
                'status' => 'success',
                'msg'    => 'Logged out Successfully.',
            ],
            200
        );
    }

    //end logout()

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json(
            [
                'status' => 'success',
                'data'   => $this->guard()->user(),
            ],
            200
        );
    }

    //end me()

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json(
            [
                'access_token' => $token,
                'token_type'   => 'bearer',
                'expires_in'   => ($this->guard()->factory()->getTTL() * 60),
            ],
            200
        )->header('Authorization', $token)->withCookie(
            cookie(
                'access_token',
                $token,
                auth()->guard()->factory()->getTTL()
            )
        );
    }

    //end respondWithToken()

    private function guard()
    {
        return Auth::guard();
    }

    //end guard()

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     */
    private function addUserToMerchant(Request $request, User $user): void
    {
        $merchant = Merchant::find($request->merchantId);
        $user->country_id = $merchant->country_id;
        $user->address = $merchant->merchant_address;
        $user->region = $merchant->region;
        $user->city = $merchant->merchant_city;
        $user->phone = $merchant->phone;
        $user->zip = $merchant->merchant_zip;
        $user->merchant_role = 1;
        $user->save();
        $merchant->user()->save($user);
    }

    //end addUserToMerchant()

    /**
     * @param \App\Models\User $user
     *
     * @return bool
     */
    private function isMerchantParticulier(User $user): bool
    {
        return $user->merchant()->exists()
            && $user->merchant->merchant_type !== 'Pro';
    }

    //end isMerchantParticulier()

    private function updateMerchant(Request $request, User $user)
    {
        if ($request->has('lastname')) {
            $user->merchant->merchant_name = $user->firstname.' '.$request->lastname;
        }

        if ($request->has('firstname')) {
            $user->merchant->merchant_name = $request->firstname.' '.$user->lastname;
        }

        if ($request->has('address')) {
            $user->merchant->merchant_address = $request->address;
        }

        if ($request->has('country_id')) {
            $user->merchant->country_id = $request->country_id;
        }

        if ($request->has('zip')) {
            $user->merchant->merchant_zip = $request->zip;
        }

        if ($request->has('city')) {
            $user->merchant->merchant_city = $request->city;
        }

        if ($request->has('region')) {
            $user->merchant->region = $request->region;
        }

        $user->merchant->save();
    }

    //end updateMerchant()

    public function emailValidation(Request $request, User $user, $email)
    {
        $user->email = $email;
        $user->removeMeta('new_email');
        $user->saveQuietly();

        return response()->json();
    }

    //end emailValidation()

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $request->validate(['password' => 'required|confirmed|min:6']);

        /*
            @var User $user
        */
        $user = auth()->user();

        if (Hash::check($request->current_password, $user->password) === false
        ) {
            return response()->json(
                ['errors' => ['current_password' => 'Mot de passe actuel incorrect.']],
                400
            );
        }

        $user->update(['password' => $request->password]);

        return response()->json();
    }

    //end updatePassword()

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Exception
     */
    public function destroy(Request $request): void
    {
        /*
            @var User $user
        */
        $user = auth()->user();

        $user->delete();
    }

    //end destroy()
}//end class
