<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;


class UserDataController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    // Creating user here...
    public function createUser(Request $request)
    {

        // Validating the request parameters...
        $this->validate($request, [
            'name'      => 'required|alpha_dash',
            'email'     => 'required|email|unique:users',
            'password'  => 'required'
        ]);

        $user = User::where('email', '=', $request->email)->first();

        // checking if user already exists...
        if ($user !== null) {
            return response()->json([
                'error' => "Email already exists...",
                'data' => $user
            ]);

        }

        // creating new user with User model...
         $user = new User;

         $user->name = $request->name;

         $user->email = $request->email;

         $user->password = app('hash')->make($request->password);

         $user->save();

         // respose for user creation...
         return response()->json([
             'success' => "User Created Successfully...",
             'newuser' => $user,
         ]);
    }



    // show user according to email entered....
    public function readUser(Request $request)
    {
         // Validating the request parameters...
        $this->validate($request, [
            'email'     => 'required|email',
        ]);

        $user = User::where('email', '=', $request->email)->first();

        // checking if user exists...
        if ($user !== null) {
            return response()->json([
                'success' => "User Found...",
                'data' => $user
            ]);

        }

        // respose if user not found...
         return response()->json([
             'error' => "User with this email account has not found...",
             'email' => $request->email,
         ]);
    }


    // Update user by email only...
    public function updateUser(Request $request)
    {
        // Validating the request parameters...
        $this->validate($request, [
            'email'     => 'required|email',
            'name'      => 'required|alpha_dash'
        ]);

        $user = User::where('email', '=', $request->email)->first();

        // checking if user exists...
        if ($user !== null) {

            $userupdate = User::where('email', $request->email)->update(['name'=> $request->name]);

            if ($userupdate) {
                return response()->json([
                    'success' => "User updated successfully...",
                    'data' => $user
                ]);
            }

        }

        // respose if user not found...
         return response()->json([
             'error' => "No user found with this email...",
             'newuser' => $request->email
         ]);
    }

    // Delete user by email only...
    public function deleteUser(Request $request)
    {
        // Validating the request parameters...
        $this->validate($request, [
            'email'     => 'required|email',
        ]);

        $user = User::where('email', '=', $request->email)->delete();

        // checking if user exists...
        if ($user) {
                return response()->json([
                    'success' => "User deleted successfully...",
                    'data' => $user
                ]);
        }

        // respose if user not found...
         return response()->json([
             'error' => "No user found with this email...",
             'newuser' => $request->email
         ]);
    }



}
