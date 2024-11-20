<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\ParentController;
use App\Imports\UserImport;
use App\Models\HR\Department;
use App\Models\HR\Leave;
use App\Models\HR\Staff;
use App\Models\HR\User;
use App\Services\HR\StaffService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;

class StaffController extends ParentController
{
    public function __construct(
        Staff $staff,
        StaffService $staffServices
    )
    {
        $this->model = $staff;
        $this->service = $staffServices;
    }
    public function countUsers()
    {
        $usersCount = User::join('user_roles', 'user_roles.id', '=', 'users.role_id')
        ->whereNotIn('users.role_id', [1, 3])->count();
        $deptCount = Department::count();
        $leaveCount = Leave::count();
        $count = DB::table('attendances')
           ->whereDate('created_at', '=', date('Y-m-d'))
           ->count();

        $response = [
            'success' => true,
            'message' => "OK",
            'User' => $usersCount,
            "Dept" => $deptCount,
            'Leave' => $leaveCount,
            "day" => $count
        ];
        return response()->json($response, 200);
    }

    public function create(Request $request): JsonResponse
    {
        $request['password'] =bcrypt($request['password']);
         return parent::create($request);
    }
    public function all(): JsonResponse
    {
        return parent::all();
    }
   public function update(Request $request, $id): JsonResponse
   {
        $request['password'] =bcrypt($request['password']);
       return parent::update($request, $id);
   }
   public function delete($id): JsonResponse
   {
       return parent::delete($id);
   }
   public function getALL(): JsonResponse
   {
        return parent::getWithRelation(['departments']);
   }
   public function ListTable()
   {
        $data = DB::table('departments')
            ->join('users', 'users.dept_id', '=', 'departments.id')
            ->join('user_roles', 'user_roles.id', '=', 'users.role_id')
            ->orderBy('users.id')
            ->where('users.active', '=', true)
            ->select('users.*', 'departments.dept_name', 'user_roles.role_name')
            ->get();

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $data
        ];
        return response()->json($response, 200);
   }

   public function dataTable(Request $request, $query = null): JsonResponse
   {
        $query = DB::table('departments')
            ->join('users', 'users.dept_id', '=', 'departments.id')
            ->join('user_roles', 'user_roles.id', '=', 'users.role_id')
            ->select("users.*","departments.*", 'users.id as user_id','role_name')
            ->orderBy('user_id');
       return parent::dataTable($request, $query);
   }

   public function getStaff($id)
   {
        $staff = DB::table('users')
        ->leftJoin('departments', 'departments.id', '=', 'users.dept_id')
        ->join('user_roles', 'user_roles.id', '=', 'users.role_id')
        ->where('users.id', $id)
        ->select("*", 'users.id as user_id')->get();

        return $staff;
   }

   public function reset($id, Request $request )
    {
        // Validate the request data
        $request->validate([
            'password' => 'required|min:6',
        ]);
        // Retrieve the user from the database
        $user = User::find($id);

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password reset successfully.']);
    }

    public function notification($id)
    {
        $result = DB::table('users')
        ->join('attendances', 'attendances.user_id', '=', 'users.id')
        ->where(function ($query) {
            $query->where('attendances.status_id', 1)
                ->orWhere('attendances.status_id', 2);
        })
        ->where(function ($query) use ($id) {
            $query->where('attendances.approve_id', $id)
                ->orWhere('attendances.accept_id', $id);
        })
        ->orderByDesc('attendances.id')
        ->orderBy('attendances.date_request')
        ->select("*")
        ->get();

        return $result;
    }

    public function uploadImg(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $user = User::find($request->input('id'));
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $image = $request->file('image');
        $imageName = time() . '.' . $image->extension();
        $image->storeAs('public', $imageName);

        $user->image = $imageName;
        $user->save();

        $response = [
            'path' => '/storage/' . $imageName,
            'data' => $user,
            'message' => 'Success',
        ];
        return response()->json($response, 200);
    }

    public function active($id){
        //action change status
        $data = DB::table('users')
            ->where('id', $id)
            ->update(['active' => false
        ]);

        return response()->json([
            'message' => 'user is inactive',
            'data' => $data
        ]);
    }

    public function test(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        if ($request->file('file')->isValid()) {
            $path = $request->file('file')->store('uploads');
            $data = array_map('str_getcsv', file(storage_path('app/' . $path)));

            // Remove header row
            array_shift($data);

            foreach ($data as $row) {
                if (count($row) >= 9) {
                    User::create([
                        'last_name' => $row[0],
                        'first_name' => $row[1],
                        'name' => $row[2],
                        'family_status' => $row[3],
                        'role_id' => $row[4],
                        'gender' => $row[5],
                        'email' => $row[6],
                        'phone' => $row[7],
                        'address' => $row[8],
                    ]);
                }
            }
            $users = User::all();

            foreach ($users as $user) {
                echo 'Name: ' . $user->name . ', Email: ' . $user->email . "\n";
            }
            // echo "File uploaded and data inserted successfully.";
        } else {
            echo "File upload failed.";
        }
    }

    // data not load in database

    public function import(Request $request)
    {
        $file = $request->file('file');

        try {
            Excel::import(new UserImport, $file);
            $importedData = User::all();
            // echo "success";
            return response()->json(['success' => 'Import completed.', 'importedData' => $importedData]);
        } catch (\Exception $e) {
            // Handle the exception or log the error message for debugging
            echo "Error: " . $e->getMessage();
        }
    }

}
