<?php

namespace App\Http\Controllers;

use App\Models\HR\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{

    public function register(Request $request){
        //validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'gender' => 'required|in:male,female',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            // 'start_date'=> 'date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->error()
            ];
            return response()->json($response, 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $user_role = Role::where(['name' => 'user'])->first();
        if($user_role) {
            $user->assignRole($user_role);
        }

        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['roles'] = $user->roles->pluck('name') ?? [];
        $success['permission'] = $user->permissions->pluck('name') ?? [];
        $success['roles_permissions'] = $user->getPermissionsViaRoles()-> pluck(['name']) ?? [];
        $success['name'] = $user->first_name;

        $response = [
            'success' => true,
            'data' => $success,
            'message' => "User register successfully!!"
        ];

        return response()->json($response, 200);
    }

    public function login(Request $request){
        if (Auth::attempt([
            'name' => $request->name,
            'password' => $request->password])) {
                // $user = Auth::user();
                $user = $request->user();
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['name'] = $user->name;

                $response = [
                    'success' => true,
                    'data' => $success,
                    'message' => "User login successfully!!"
                ];
                return response()->json($response, 200);
        }else {
            $response = [
                'success' => false,
                'message' => 'Unauthorised'
            ];
            return response()->json($response);
        }
    }
    public function change_password(Request $request) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:6'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validations fails',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        if(Hash::check($request->old_password, $user->password)) {
            $user->update([
                'password'=>Hash::make($request->password)
            ]);
            return response()->json([
                'message'=>'Password successfully updated'
            ],200);
        }else {
            return response()->json([
                'message'=>'Old password does not matched'
            ],400);
        }
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),

        ]);
    }
}
