<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\ParentController;

// use App\Models\Attendance;
use App\Models\HR\Attendance;
use App\Services\HR\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends ParentController
{
    public function __construct(
        Attendance $attendance,
        AttendanceService $attendanceService,
    )
    {
        $this->model = $attendance;
        $this->service = $attendanceService;
    }
    public function all(): JsonResponse
    {
        return parent::all();
    }
    public function dataTable(Request $request, $query = null): JsonResponse
    {
        $query = DB::table('attendances as a')
            ->join('users', 'a.user_id', '=', 'users.id')
            ->join('leave_annuals', 'leave_annuals.id', '=', 'a.leave_annual_id')
            ->join('leaves', 'leaves.id', '=', 'leave_annuals.leave_id')
            ->join('statuses', 'statuses.id', '=', 'a.status_id')
            ->selectRaw("*, a.id as attendances_id,  (select users.name from users where a.accept_id=users.id) as accept_name,
            (select users.name from users where a.approve_id=users.id) as approve_name,
            AGE(to_date::date, from_date::date) as leave_token
            ")
            ->orderBy('a.id', 'desc')
            ->orderBy('a.date_request', 'asc');

        return parent::dataTable($request, $query);
    }
    public function approveLeave($id)
    {
        $res = DB::table('attendances AS a')
            ->leftJoin('users AS u', 'a.accept_id', '=', 'u.id')
            ->leftJoin('users AS u2', 'a.approve_id', '=', 'u2.id')
            ->join('users AS u1', 'a.user_id', '=', 'u1.id')
            ->join('leave_annuals AS la', 'la.id', '=', 'a.leave_annual_id')
            ->join('leaves AS l', 'l.id', '=', 'la.leave_id')
            ->join('statuses AS s', 's.id', '=', 'a.status_id')
            ->selectRaw("
                a.*, l.leave_name, s.status_name,
                CONCAT(u1.last_name, ' ', u1.first_name) AS user_name,
                CONCAT(u2.last_name, ' ', u2.first_name) AS approve_name,
                CONCAT(u.last_name, ' ', u.first_name) AS accept_name,
                CASE
                    WHEN a.to_date::date = a.from_date::date AND a.shiftime IN ('Morning', 'Afternoon') THEN 'Half Day'
                    WHEN a.to_date::date = a.from_date::date THEN '1 Day'
                    ELSE CAST(a.to_date::date - a.from_date::date + 1 AS VARCHAR) || ' Days'
                END AS leave_duration
            ")
            ->where(function ($query) use ($id) {
                $query->where('a.approve_id', $id)
                    ->orWhere('a.accept_id', $id);
            })
            ->orderByDesc('a.id')
            ->orderBy('a.date_request')
            ->get();
        $response = [
                'success' => true,
                'message' => "OK",
                'data' => $res
        ];
        return response()->json($response, 200);

    }
    public function myleave($id)
    {
        $res = DB::table('attendances AS a')
            ->leftJoin('users AS u', 'a.accept_id', '=', 'u.id')
            ->leftJoin('users AS u2', 'a.approve_id', '=', 'u2.id')
            ->join('users AS u1', 'a.user_id', '=', 'u1.id')
            ->join('leave_annuals AS la', 'la.id', '=', 'a.leave_annual_id')
            ->join('leaves AS l', 'l.id', '=', 'la.leave_id')
            ->join('statuses AS s', 's.id', '=', 'a.status_id')
            ->selectRaw("
                a.*, l.leave_name, s.status_name,
                CONCAT(u1.last_name, ' ', u1.first_name) AS user_name,
                CONCAT(u2.last_name, ' ', u2.first_name) AS approve_name,
                CONCAT(u.last_name, ' ', u.first_name) AS accept_name,
                CASE
                    WHEN a.to_date::date = a.from_date::date AND a.shiftime IN ('Morning', 'Afternoon') THEN 'Half Day'
                    WHEN a.to_date::date = a.from_date::date THEN '1 Day'
                    ELSE CAST(a.to_date::date - a.from_date::date + 1 AS VARCHAR) || ' Days'
                END AS leave_duration
            ")
            ->where('a.user_id', '=', $id)
            ->orderByDesc('a.id')
            ->orderBy('a.date_request')
            ->get();
            $response = [
                'success' => true,
                'message' => "OK",
                'data' => $res
            ];
            return response()->json($response, 200);
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
    public function store(Request $request)
    {
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            return response()->json(['message' => 'Image uploaded successfully']);
        } else {
            return response()->json(['message' => 'No image selected']);
        }
    }
    public function upload(Request $request) {
        $image = $request->file('attachment');
        $imageName = time() . '.' . $image->extension();
        $image->storeAs('public', $imageName);
        $atten = Attendance::create([
            'attachment' => $imageName,
        ]);

        $response = [
            'path' => '/storage/' . $imageName,
            'data' => $atten,
            'message' => "Success"
        ];
        return response()->json($response, 200);
    }

    public function applyLeave(Request $request)
    {
        if ($request->hasFile('attachment')) {
            $request->validate([
                'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'shiftime' => 'nullable|in:Morning,Afternoon,Full Day',
            ]);
            $image = $request->file('attachment');
            $imageName = time() . '.' . $image->extension();
            $image->storeAs('public', $imageName);
            $attendanceData = [
                'user_id' => $request->input('user_id'),
                'leave_annual_id' => $request->input('leave_annual_id'),
                'date_request' => $request->input('date_request'),
                'from_date' => $request->input('from_date'),
                'to_date'  => $request->input('to_date'),
                'reason' => $request->input('reason'),
                'shiftime' => $request->input('shiftime'),
                'attachment' => $imageName,
            ];

            if ($request->input('approve_id') !== null) {
                $attendanceData['approve_id'] = $request->input('approve_id');
            }

            if ($request->input('accept_id') !== null) {
                $attendanceData['accept_id'] = $request->input('accept_id');
            }

            $atten = Attendance::create($attendanceData);

            $response = [
                'path' => '/storage/' . $imageName,
                'data' => $atten,
                'message' => "Success"
            ];
            return response()->json($response, 200);
        } else {
            $request->validate([
                'shiftime' => 'nullable|in:Morning,Afternoon,Full Day',
            ]);
            $attendanceData = [
                'user_id' => $request->input('user_id'),
                'leave_annual_id' => $request->input('leave_annual_id'),
                'date_request' => $request->input('date_request'),
                'from_date' => $request->input('from_date'),
                'to_date'  => $request->input('to_date'),
                'reason' => $request->input('reason'),
                'shiftime' => $request->input('shiftime'),
            ];

            if ($request->input('approve_id') !== null) {
                $attendanceData['approve_id'] = $request->input('approve_id');
            }

            if ($request->input('accept_id') !== null) {
                $attendanceData['accept_id'] = $request->input('accept_id');
            }

            $atten = Attendance::create($attendanceData);

            $response = [
                'data' => $atten,
                'message' => "Success"
            ];
            return response()->json($response, 200);
        }
    }

    public function pending(Request $request) {
        $user = $request->input('accept_id'); // id user approve leave
        $leave = $request->input('id'); // leave id

        //action change status
        $data = DB::table('attendances')
            ->where('id', $leave)
            ->where('accept_id', $user)
            ->update(['status_id' => 2
        ]);

        return response()->json([
            'message' => 'First approved successfully',
            'data' => $data
        ]);

    }

    public function approve(Request $request) {
        $user = $request->input('approve_id'); // id user approve leave
        $leave = $request->input('id'); // leave id

        //action change status
        $data = DB::table('attendances')
            ->where('id', $leave)
            ->where('approve_id', $user)
            ->update(['status_id' => 3
        ]);

        return response()->json([
            'message' => 'Second approved successfully',
            'data' => $data
        ]);
    }

    public function reject(Request $request) {
        $des = $request->input('desc_reject');
        $leave = $request->input('id');

        $request->validate([
            'desc_reject' => 'required',
        ]);

        $data = DB::table('attendances')
                ->where('id', $leave)
                ->update([
                    'status_id' => 4,
                    'desc_reject' => $des
            ]);

        return response()->json([
            'message' => 'User rejected successfully',
            'data' => $data
        ]);
    }

    // test
    public function leaveView1()
    {
        $query = "
            SELECT
            a.user_id,
            a.leave_annual_id,
            u.first_name,
            u.last_name,
            la.created_at,
            d.sort_name,
            la.credit_leave,
            la.leave_id,
            COALESCE(
                (
                    SELECT SUM(
                        CASE
                            WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                            WHEN a2.shiftime = 'Full day' THEN 1
                            ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                        END
                    )
                    FROM attendances AS a2
                    WHERE a2.user_id = a.user_id
                        AND a2.leave_annual_id = a.leave_annual_id
                        AND a2.status_id = 3
                ),
                0
            ) AS leaves_taken,
            (la.credit_leave - COALESCE(
                (
                    SELECT SUM(
                        CASE
                            WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                            WHEN a2.shiftime = 'Full day' THEN 1
                            ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                        END
                    )
                    FROM attendances AS a2
                    WHERE a2.user_id = a.user_id
                        AND a2.leave_annual_id = a.leave_annual_id
                        AND a2.status_id = 3
                ),
                0
            )) AS leave_balance
        FROM
            users AS u
            JOIN departments AS d ON d.id = u.dept_id
            JOIN leave_annuals AS la ON la.user_id = u.id
            JOIN leaves AS l ON l.id = la.leave_id
            LEFT JOIN attendances AS a ON a.user_id = u.id
                AND a.leave_annual_id = la.id
                AND a.status_id = 3
            LEFT JOIN statuses AS s ON s.id = a.status_id
        WHERE
            s.id = 3
        GROUP BY
            a.user_id,
            a.leave_annual_id,
            u.first_name,
            u.last_name,
            la.created_at,
            d.sort_name,
            la.credit_leave,
            la.leave_id
        ";

        $results = DB::select($query);
            $response = [
                'success' => true,
                'message' => "OK",
                'data' => $results
            ];
        return response()->json($response, 200);
    }

    public function leaveView()
    {
        $query = "
        SELECT DISTINCT
            la.user_id,
            l.leave_name,
            a.leave_annual_id,
            DATE_TRUNC('month', a.date_request) AS month,
            u.first_name,
            u.last_name,
            la.created_at,
            d.sort_name,
            la.credit_leave,
            la.leave_id,
         COALESCE(
                    (
                        SELECT SUM(
                            CASE
                                WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                                WHEN a2.shiftime = 'Full day' THEN 1
                                ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                            END
                        )
                        FROM attendances AS a2
                        WHERE a2.user_id = a.user_id
                            AND a2.leave_annual_id = a.leave_annual_id
                            AND a2.status_id = 3
                    ),
                    0
                ) AS leaves_taken,
        (la.credit_leave - COALESCE(
            (
                SELECT SUM(
                    CASE
                        WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                        WHEN a2.shiftime = 'Full day' THEN 1
                        ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                    END
                )
                FROM attendances AS a2
                WHERE a2.user_id = a.user_id
                    AND a2.leave_annual_id = a.leave_annual_id
                    AND a2.status_id = 3
            ),
            0
        )) AS leave_balance
        FROM
            users AS u
            INNER JOIN departments AS d ON d.id = u.dept_id
            INNER JOIN leave_annuals AS la ON la.user_id = u.id
            INNER JOIN leaves AS l ON l.id = la.leave_id
            LEFT JOIN attendances AS a ON a.user_id = u.id AND a.leave_annual_id = la.id
        ORDER BY
            la.user_id;
            ";

            $results = DB::select($query);
                $response = [
                    'success' => true,
                    'message' => "OK",
                    'data' => $results
                ];
            return response()->json($response, 200);
    }

    public function Remaining($id)
    {
        $query = "
        SELECT
            a.user_id,
            l.leave_name,
            a.leave_annual_id,
            la.created_at,
            d.sort_name,
            la.credit_leave,
            la.leave_id,
            COALESCE(
                (
                    SELECT SUM(
                        CASE
                            WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                            WHEN a2.shiftime = 'Full day' THEN 1
                            ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                        END
                    )
                    FROM attendances AS a2
                    WHERE a2.user_id = a.user_id
                        AND a2.leave_annual_id = a.leave_annual_id
                        AND a2.status_id = 3
                ),
                0
            ) AS leaves_taken,
            (la.credit_leave - COALESCE(
                (
                    SELECT SUM(
                        CASE
                            WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                            WHEN a2.shiftime = 'Full day' THEN 1
                            ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                        END
                    )
                    FROM attendances AS a2
                    WHERE a2.user_id = a.user_id
                        AND a2.leave_annual_id = a.leave_annual_id
                        AND a2.status_id = 3
                ),
                0
            )) AS leave_balance
        FROM
            users AS u
            JOIN departments AS d ON d.id = u.dept_id
            JOIN leave_annuals AS la ON la.user_id = u.id
            JOIN leaves AS l ON l.id = la.leave_id
            LEFT JOIN attendances AS a ON a.user_id = u.id
                AND a.leave_annual_id = la.id
                AND a.status_id = 3
            LEFT JOIN statuses AS s ON s.id = a.status_id
        WHERE
            s.id = 3
            AND a.user_id = :id
        GROUP BY
        a.user_id,
        l.leave_name,
            a.leave_annual_id,
            la.created_at,
            d.sort_name,
            la.credit_leave,
            la.leave_id;
        ";

        $res = DB::select($query, ['id' => $id]);
        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $res
        ];
        return response()->json($response, 200);
    }

    public function LeaveBalance($id)
    {
        $annual = "
                SELECT DISTINCT
                la.user_id,
                l.leave_name,
                a.leave_annual_id,
                la.credit_leave,
                la.leave_id,
                COALESCE(
                            (
                                SELECT SUM(
                                    CASE
                                        WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                                        WHEN a2.shiftime = 'Full day' THEN 1
                                        ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                                    END
                                )
                                FROM attendances AS a2
                                WHERE a2.user_id = a.user_id
                                    AND a2.leave_annual_id = a.leave_annual_id
                                    AND a2.status_id = 3
                            ),
                            0
                        ) AS leaves_taken,
                (la.credit_leave - COALESCE(
                    (
                        SELECT SUM(
                            CASE
                                WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                                WHEN a2.shiftime = 'Full day' THEN 1
                                ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                            END
                        )
                        FROM attendances AS a2
                        WHERE a2.user_id = a.user_id
                            AND a2.leave_annual_id = a.leave_annual_id
                            AND a2.status_id = 3
                    ),
                    0
                )) AS leave_balance
                FROM
                    users AS u
                    INNER JOIN user_roles AS ur ON ur.id = u.role_id
                    INNER JOIN leave_annuals AS la ON la.user_id = u.id
                    INNER JOIN leaves AS l ON l.id = la.leave_id
                    LEFT JOIN attendances AS a ON a.user_id = u.id AND a.leave_annual_id = la.id
                    WHERE  la.user_id = :id AND l.id = 2

                ORDER BY
                    la.user_id;
        ";
        $sick = "
                SELECT DISTINCT
                la.user_id,
                l.leave_name,
                a.leave_annual_id,
                la.credit_leave,
                la.leave_id,
                COALESCE(
                            (
                                SELECT SUM(
                                    CASE
                                        WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                                        WHEN a2.shiftime = 'Full day' THEN 1
                                        ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                                    END
                                )
                                FROM attendances AS a2
                                WHERE a2.user_id = a.user_id
                                    AND a2.leave_annual_id = a.leave_annual_id
                                    AND a2.status_id = 3
                            ),
                            0
                        ) AS leaves_taken,
                (la.credit_leave - COALESCE(
                    (
                        SELECT SUM(
                            CASE
                                WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                                WHEN a2.shiftime = 'Full day' THEN 1
                                ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                            END
                        )
                        FROM attendances AS a2
                        WHERE a2.user_id = a.user_id
                            AND a2.leave_annual_id = a.leave_annual_id
                            AND a2.status_id = 3
                    ),
                    0
                )) AS leave_balance
                FROM
                    users AS u
                    INNER JOIN user_roles AS ur ON ur.id = u.role_id
                    INNER JOIN leave_annuals AS la ON la.user_id = u.id
                    INNER JOIN leaves AS l ON l.id = la.leave_id
                    LEFT JOIN attendances AS a ON a.user_id = u.id AND a.leave_annual_id = la.id
                    WHERE  la.user_id = :id AND l.id = 1

                ORDER BY
                    la.user_id;
        ";
        $unpaid = "
                SELECT DISTINCT
                la.user_id,
                l.leave_name,
                a.leave_annual_id,
                la.credit_leave,
                la.leave_id,
                COALESCE(
                            (
                                SELECT SUM(
                                    CASE
                                        WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                                        WHEN a2.shiftime = 'Full day' THEN 1
                                        ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                                    END
                                )
                                FROM attendances AS a2
                                WHERE a2.user_id = a.user_id
                                    AND a2.leave_annual_id = a.leave_annual_id
                                    AND a2.status_id = 3
                            ),
                            0
                        ) AS leaves_taken,
                (la.credit_leave - COALESCE(
                    (
                        SELECT SUM(
                            CASE
                                WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                                WHEN a2.shiftime = 'Full day' THEN 1
                                ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                            END
                        )
                        FROM attendances AS a2
                        WHERE a2.user_id = a.user_id
                            AND a2.leave_annual_id = a.leave_annual_id
                            AND a2.status_id = 3
                    ),
                    0
                )) AS leave_balance
                FROM
                    users AS u
                    INNER JOIN user_roles AS ur ON ur.id = u.role_id
                    INNER JOIN leave_annuals AS la ON la.user_id = u.id
                    INNER JOIN leaves AS l ON l.id = la.leave_id
                    LEFT JOIN attendances AS a ON a.user_id = u.id AND a.leave_annual_id = la.id
                    WHERE  la.user_id = :id AND l.id = 3

                ORDER BY
                    la.user_id;
        ";

        $data = DB::table('users')
            ->join('user_roles', 'user_roles.id', '=', 'users.role_id')
            ->select('users.last_name','users.first_name', 'user_roles.role_name')
            ->where('users.id', $id)
            ->get();

        $leave_al = DB::select($annual,['id' => $id]);
        $leave_sl = DB::select($sick, ['id' => $id]);
        $leave_ul = DB::select($unpaid, ['id' => $id]);

        $response = [
            'success' => true,
            'message' => "OK",
            'user' => $data,
            'leave_al' => $leave_al,
            'leave_sl' => $leave_sl,
            'leave_ul' => $leave_ul,
        ];
        return response()->json($response, 200);
    }

    public function MonthExport($month)
    {
        $query = "
        SELECT DISTINCT
        la.user_id,
        l.leave_name,
        a.leave_annual_id,
     DATE_TRUNC('month', a.date_request) AS month,
        u.first_name,
        u.last_name,
        la.created_at,
        d.sort_name,
        la.credit_leave,
        la.leave_id,
         COALESCE(
                    (
                        SELECT SUM(
                            CASE
                                WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                                WHEN a2.shiftime = 'Full day' THEN 1
                                ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                            END
                        )
                        FROM attendances AS a2
                        WHERE a2.user_id = a.user_id
                            AND a2.leave_annual_id = a.leave_annual_id
                            AND a2.status_id = 3
                    ),
                    0
                ) AS leaves_taken,
        (la.credit_leave - COALESCE(
            (
                SELECT SUM(
                    CASE
                        WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                        WHEN a2.shiftime = 'Full day' THEN 1
                        ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                    END
                )
                FROM attendances AS a2
                WHERE a2.user_id = a.user_id
                    AND a2.leave_annual_id = a.leave_annual_id
                    AND a2.status_id = 3
            ),
            0
        )) AS leave_balance
        FROM
            users AS u
            INNER JOIN departments AS d ON d.id = u.dept_id
            INNER JOIN leave_annuals AS la ON la.user_id = u.id
            INNER JOIN leaves AS l ON l.id = la.leave_id
            LEFT JOIN attendances AS a ON a.user_id = u.id AND a.leave_annual_id = la.id
            WHERE DATE_TRUNC('month', a.date_request) = :data
        ORDER BY
            la.user_id;
            ";
            $res = DB::select($query, ['data' => $month]);
            // $results = DB::select($query);
                $response = [
                    'success' => true,
                    'message' => "OK",
                    'data' => $res
                ];
            return response()->json($response, 200);
    }

    public function YearExport($year)
    {
        $query = "
        SELECT DISTINCT
        la.user_id,
        l.leave_name,
        a.leave_annual_id,
     DATE_TRUNC('month', a.date_request) AS month,
        u.first_name,
        u.last_name,
        la.created_at,
        d.sort_name,
        la.credit_leave,
        la.leave_id,
         COALESCE(
                    (
                        SELECT SUM(
                            CASE
                                WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                                WHEN a2.shiftime = 'Full day' THEN 1
                                ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                            END
                        )
                        FROM attendances AS a2
                        WHERE a2.user_id = a.user_id
                            AND a2.leave_annual_id = a.leave_annual_id
                            AND a2.status_id = 3
                    ),
                    0
                ) AS leaves_taken,
        (la.credit_leave - COALESCE(
            (
                SELECT SUM(
                    CASE
                        WHEN a2.shiftime IN ('Morning', 'Afternoon') THEN 0.5
                        WHEN a2.shiftime = 'Full day' THEN 1
                        ELSE CAST(a2.to_date::date - a2.from_date::date + 1 AS INTEGER)
                    END
                )
                FROM attendances AS a2
                WHERE a2.user_id = a.user_id
                    AND a2.leave_annual_id = a.leave_annual_id
                    AND a2.status_id = 3
            ),
            0
        )) AS leave_balance
        FROM
            users AS u
            INNER JOIN departments AS d ON d.id = u.dept_id
            INNER JOIN leave_annuals AS la ON la.user_id = u.id
            INNER JOIN leaves AS l ON l.id = la.leave_id
            LEFT JOIN attendances AS a ON a.user_id = u.id AND a.leave_annual_id = la.id
            WHERE DATE_PART('year', a.date_request) = 2023
        ORDER BY
            la.user_id;
            ";
            $res = DB::select($query, ['data' => $year]);
            // $results = DB::select($query);
                $response = [
                    'success' => true,
                    'message' => "OK",
                    'data' => $res
                ];
            return response()->json($response, 200);
    }
}
