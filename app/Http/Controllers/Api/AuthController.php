<?php

namespace App\Http\Controllers\Api;

use App\Classes\CustomHelpers;
use App\Classes\ResponseWrapper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        //
    }


    /**
     * Verify credentials and get access.
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {

        $returned_data = ResponseWrapper::Start();

        /**
         * Validate Request
         */
        $validator = Validator::make($request->all(), [
            'auth_id' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $returned_data['code'] = 422;
            $returned_data['error_type'] = "validation_error";
            $returned_data['message'] = $validator->errors()->first();
            return ResponseWrapper::End($returned_data);
        }


        $user = null;
        $userExists = User::where('auth_id', '=', $request->get('auth_id'))->first();
        if($userExists !== null) {
            if(Hash::check($request->get('password'), $userExists['password'])){
                $user = User::where('auth_id', '=', $request->get('auth_id'))->first(['id', 'auth_id']);
            } else {
                $returned_data['error_type'] = "wrong_credentials";
                $returned_data['message'] = "Wrong credentials!";
                return ResponseWrapper::End($returned_data);
            }
        }


        if($user !== null){

            $tokenKey = $user["auth_id"];
            if($userIp = CustomHelpers::getIPAddress()){
                $tokenKey .= $userIp;
            }

            $returned_data['results'] = [
                "access_token" => $user->createToken($tokenKey)->plainTextToken,
                "id"=>$user->id
            ];
            $returned_data['message'] = "Login successful";

        } else {
            $returned_data['error_type'] = "account_not_found";
            $returned_data['message'] = "account not found";
        }

        return ResponseWrapper::End($returned_data);

    }




    /**
     * Verify request and create new user and get access.
     *
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {

        $returned_data = ResponseWrapper::Start();

        /**
         * Validate Request
         */
        $validator = Validator::make($request->all(), [
            'auth_id' => 'required|string|unique:users,auth_id',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {

            $returned_data['code'] = 422;
            $returned_data['error_type'] = "validation_error";

            $passwordLength = strlen($request->input('password'));
            if($passwordLength < 6){
                $returned_data['message'] = 'password must be at-least 6 digits';
            } else {
                $returned_data['message'] = $validator->errors()->first();
            }

            return ResponseWrapper::End($returned_data);
        }


        $authId = trim($request->input('auth_id'));
        $isEmail = false;
        if(filter_var($authId, FILTER_VALIDATE_EMAIL)){
            $isEmail = true;
        }

        DB::beginTransaction();
        try {

            /**
             * Create new user
             */
            $user = User::create([
                'auth_id'  => $authId,
                'password' => Hash::make($request->input('password')),
            ]);


            /**
             * Create user profile
             */
            $user->profile()->create([
                'user_id' => $user->id,
                'mobile_number' => !$isEmail ? $authId : null,
                'email' => $isEmail ? $authId : null
            ]);


            $tokenKey = $user["auth_id"];
            if($userIp = CustomHelpers::getIPAddress()){
                $tokenKey .= $userIp;
            }

            /**
             * Create new session and return data
             */
            $returned_data['results'] = [
                "access_token" => $user->createToken($tokenKey)->plainTextToken,
                "id"=>$user->id
            ];
            $returned_data['message'] = "Registration successful";

            DB::commit();

        } catch (\Exception $exception){

            $returned_data['code'] = 422;
            $returned_data['error_type'] = "registration_error";
            $returned_data['message'] = $exception->getMessage();

            DB::rollBack();
        }

        return ResponseWrapper::End($returned_data);

    }

    /**
     * Clear user token and revoke access.
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $returned_data = ResponseWrapper::Start();

        if($request->user()->currentAccessToken()->delete()){
            $returned_data['results'] = true;
            $returned_data['message'] = "Logout successful";
        }

        return ResponseWrapper::End($returned_data);
    }


}
