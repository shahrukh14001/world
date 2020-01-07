<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function auth(Request $request) {
        if (\request()->has(['email', 'password'])) {
            $user = User::query()->where('email', $request->all()['email'])->first();
            if ($user && Hash::check(\request()->all()['password'], $user->password) ){
                return response()->json([
                    'Code'      => 200,
                    'Message'   => 'You have logged in successfully',
                    'Results'   => [
                        'user_id'   => $user->id,
                        'name'      => $user->name,
                        'mobile'    => $user->mobile,
                        'user_type' => $user->user_type,
                        'status'    => $user->status
                    ]
                ]);
            }
        }

        return response()->json([
            'Code'      => 403,
            'Messages'  => 'These credential do not match to our records',
            'Results'   => [],
        ]);
    }
}
