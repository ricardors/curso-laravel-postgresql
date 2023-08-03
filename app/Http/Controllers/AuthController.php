<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]); //login, register methods won't go through the api guard
    }
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ];

        $messages = [
            'email.required' => 'required',
            'email.email' => 'not-email',
            'password.required' => 'required',
            'password.string' => 'not-string',
            'password.min' => 'less-min',
        ];

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            $messages = $validation->errors()->toArray();

            $key = key($messages);
            $value = implode('', reset($messages));

            // Handles the validation messages for fields to add the error code
            $message = $this->customValidationMessage($key, $value);

             //insert logs
            //  $insert_log = array();
            //  $insert_log["IP"] = $request->ip();
            //  $insert_log["ACAO"] = 'Login';
            //  $insert_log["TABELA"] = 'usuarios';
            //  $insert_log["CODIGO"] = $message['code'];
            //  $insert_log["MENSAGEM"] = $message['message'];
            //  $insert_log["VALORES"] = "array([DADOS] =>" . json_encode($request->all()) . ")";
            //  Log::create($insert_log);
 
            return response()->json($message);

        }

        if (!$token = auth()->attempt($validation->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = FacadesJWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function getaccount()
    {
        return response()->json(auth()->user());
    }


    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 //mention the guard name inside the auth fn
        ]);
    }

    // Handles the validation return message to include the codes
    public function customValidationMessage($key, $value)
    {
        if ($key == 'email') {

            if ($value == 'required') {

                $message = [
                    'code' => 423,
                    'message' => 'The email field is required.'
                ];
            } else if ($value == 'not-email') {

                $message = [
                    'code' => 423,
                    'message' => 'The email must be a valid email address.'
                ];
            }
        } else if ($key == 'password') {

            if ($value == 'required') {

                $message = [
                    'code' => 423,
                    'message' => 'The password field is required.'
                ];
            } else if ($value == 'not-string') {

                $message = [
                    'code' => 423,
                    'message' => 'The password must be a string.'
                ];
            } else if ($value == 'less-min') {

                $message = [
                    'code' => 423,
                    'message' => 'The password must have more than 6 digits.'
                ];
            }
        }

        return $message;
    }
}
