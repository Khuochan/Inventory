<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\ParentController;
use App\Models\HR\User;
use App\Models\HR\UserRole;
use App\Services\UserRoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRoleController extends ParentController
{
    public function __construct(
        UserRole $userRole,
        // UserRoleService $userRoleService
    )
    {
        $this->model = $userRole;
        // $this->service = $userRoleService;
    }
    public function all(): JsonResponse
    {
        return parent::all();
    }
    public function dataTable(Request $request, $query = null): JsonResponse
    {
        return parent::dataTable($request, $query);
    }
    public function create(Request $request): JsonResponse
   {
        return parent::create($request);
   }
   public function update(Request $request, $id): JsonResponse
   {
       return parent::update($request, $id);
   }
   public function delete($id): JsonResponse
   {
       return parent::delete($id);
   }
   public function listTable()
   {
         $data = DB::select('SELECT * FROM user_roles ORDER BY id');

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $data
        ];
        return response()->json($response, 200);
   }

   public function RoleUser()
   {
        $data = DB::table('users')
            ->join('user_roles', 'user_roles.id', '=', 'users.role_id')
            ->select('*', 'users.id as user_id')
            ->orderBy('users.id')->get();
        // return $data;

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $data
        ];
        return response()->json($response, 200);
    }

    public function User()
    {
        $data = DB::table('users')
            ->join('user_roles', 'user_roles.id', '=', 'users.role_id')
            ->select('users.id as user_id', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS full_name"))
            ->where('user_roles.id', '!=', 1)
            ->where('user_roles.id', '!=', 2)
            ->where('user_roles.id', '!=', 3)
            ->orderBy('users.id')
            ->get();
         // return $data;
         $response = [
             'success' => true,
             'message' => "OK",
             'data' => $data
         ];
         return response()->json($response, 200);
    }

    public function Director()
    {
        $data = DB::table('users')
            ->join('user_roles', 'user_roles.id', '=', 'users.role_id')
            ->select('users.id as user_id', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS full_name"))
            ->where('user_roles.id', '=', 3)
            ->orderBy('users.id')
            ->get();
         // return $data;
         $response = [
             'success' => true,
             'message' => "OK",
             'data' => $data
         ];
         return response()->json($response, 200);
    }

    public function UpdateUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->update([
            'role_id' => $request->input('role_id'),
            'name' => $request->input('name'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);

        return response()->json(['success' => true]);
    }

    public function assignRole(Request $request)
    {
        $this->validate($request, [
            'permission' => 'required',
            'id' => 'required',
        ]);

        return UserRole::where('id', $request->id)->update([
            'permission' => $request->permission,
        ]);
    }
}
