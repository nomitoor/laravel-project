<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use JWTAuth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class APIController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first(),
                'success' => false
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if ($user !== null) {
            if ($user->email_verified_at == null) {
                return response()->json(['error' => 'Please verify your account to login'], 401);                
            }
        } else {
            return response()->json(['error' => 'Please register first in order to login to the applciation!'], 401);
        }


        if (!$token = auth('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|bail|between:2,100',
            'email' => 'required|string|email|bail|max:100|unique:users',
            'password' => 'required|string|confirmed|bail|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first(),
                'success' => false
            ]);
        }

        $verification_code = mt_rand(1000, 9999);
        $user = User::create(array_merge($validator->validated(), ['password' => bcrypt($request->password), 'verification_code' => $verification_code]));


        $token = JWTAuth::fromUser($user);
        $data = [
            'name' => $request->name,
            'subject' => 'Email Verification',
            'email' => $request->email,
            'code' => $verification_code
        ];

        $this->sendMail($data);

        return response()->json([
            'token' =>  $token,
            'message' => 'User successfully registered, Please check your mail and verify your account',
            'user' => $user,
            'status' => '201',
            'success' => true
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth('api')->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'user' => auth('api')->user(),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'status' => '200',
            'message' => 'Succesfully logged in!',
            'success' => false
        ]);
    }

    public function sendMail($data)
    {

        Mail::send('emails.email_varification', $data, function ($message) use ($data) {
            $message->to($data['email'], $data['name'])->subject($data['subject']);
            $message->from('vappcorespl@gmail.com', 'Email Verification');
        });
    }

    public function verify_code(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user !== null) {
            if (User::where('verification_code', '=', $request->verification_code)->first()) {
                User::where('email', $request->email)->update(array('email_verified_at' => Carbon::now()->toDateTimeString()));
                return response()->json([
                    'success' => true,
                    'message' => 'Your account is been verified',
                    'status' => 200
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'verification code is not correct',
                'status' => 400
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'your email is not present in our database',
            'status' => 400

        ]);
    }

    public function check_varification(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user->email_verified_at !== null) {
            return response()->json([
                'success' => true,
                'message' => 'verified',
                'status' => 200
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'not verified',
            'status' => 400
        ]);
    }


    public function forget_password(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'email' => "required|email",
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first());
        } else {
            $url  = url()->full();

            $token = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

            $data = [
                'subject' => 'Email Verification',
                'email' => $request->email,
                'token' => $token,
                'name' => $request->name
            ];

            User::where('email', $request->email)->update(array('rest_token' => $token));
            Mail::send('emails.forgot_password_email', $data, function ($message) use ($data) {
                $message->to($data['email'], $data['name'])->subject($data['subject']);
                $message->from('vappcorespl@gmail.com', 'Email Verification');
            });
            $arr = array("status" => 200, "message" => 'Email sent succesfully', 'success' => true);
        }
        return response()->json($arr);
    }

    public function reset_password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->email) {
            if (User::where('rest_token', '=', $request->token)->first()) {

                User::where('email', $request->email)->update(array('password' => bcrypt($request->password)));

                return response()->json([
                    'success' => true,
                    'message' => 'Login with your new password, your password has been reseted',
                    'status' => 200
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'token code is not correct',
                'status' => 400
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'your email is not present in our database',
            'status' => 400
        ]);
    }
}
