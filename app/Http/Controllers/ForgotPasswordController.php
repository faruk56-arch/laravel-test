<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ForgotPassword;
use App\Notifications\PasswordResetConfirmation;
use App\Notifications\SetPassword;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Str;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
            $user = $this->getUserBy('email', $request->email);
            if (! $user->password) {
                $user->notify(new SetPassword($user->password_reset_token));
                return response(['message' => 'User not activated yet.'], 400);
            }
            $token = Str::uuid();
            $user->update(['password_reset_token' => $token]);
            $user->notify(new ForgotPassword($token));

            return response(
                ['message' => 'Reset password link sent on your email id.'],
                200
            );
        } catch (ModelNotFoundException $e) {
            return response(['message' => 'User cannot be found.'], 400);
        }
    }

    public function reset(Request $request)
    {
        try {
            $request->validate([
                'token'    => 'required|string',
                'password' => 'required|string|confirmed',
            ]);
            $user = $this->getUserBy('password_reset_token', $request->token);
            $user->update($request->all());
            $user->update(['password_reset_token' => null]);
            if (! $request->firstname && ! $request->_merchant) {
                $user->notify(new PasswordResetConfirmation);
            }

            return response(
                [
                    'message' => 'Password has been successfully changed.',
                    'user_id' => $user->id,
                ],
                200
            );
        } catch (ModelNotFoundException $e) {
            return response(['message' => 'User cannot be found.'], 400);
        }
    }

    public function tokenValid(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required|string',
            ]);
            $this->getUserBy('password_reset_token', $request->token);

            return response(['message' => 'Token is valid.'], 200);
        } catch (ModelNotFoundException $e) {
            return response(['message' => 'User cannot be found.'], 400);
        }
    }

    /**
     * @param $field
     * @param $value
     *
     * @return \App\Models\User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getUserBy($field, $value)
    {
        return User::where($field, $value)
            ->firstOrFail();
    }
}
