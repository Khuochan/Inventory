<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\ReturnSpare;
use App\Models\Inventory\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReturnSpareController extends Controller
{
    public function JobSheet(Request $request)
    {
        // Log the incoming request data
        Log::info('Received request data: ', $request->all());

        // Validate the request data
        $request->validate([
            'user_id'=> 'required|integer',
            'request_id' => 'required|integer',
            'sparepart_id' => 'required|integer',
            'spare_qty' => 'required|integer',
            'usage_id' => 'required|integer',
            'return_date' => 'nullable|date',
            'defect_id' => 'nullable|integer',
            'warehouse_id' => 'required|integer',
            'serial_part' => 'nullable|string',
        ]);

        DB::table('request_spares')
        ->where('id',$request->input('request_id'))
        ->update([
            'request_status_id' => 2,
        ]);


        // Use a database transaction
        DB::beginTransaction();
        try {
            // Lock the return_spares table for reading and writing to prevent race condition
            $latestRequest = DB::table('return_spares')->lockForUpdate()->orderBy('id', 'desc')->first();

            // Generate a new custom return_id
            $newIdNumber = $latestRequest ? intval(substr($latestRequest->return_id, 3)) + 1 : 1;

            // Ensure that return_id does not exceed RT100000
            if ($newIdNumber > 100000) {
                throw new \Exception('Maximum return_id limit reached.');
            }

            $newReturnId = 'JOB' . str_pad($newIdNumber, 5, '0', STR_PAD_LEFT);

            // Create the new record
            $spare = ReturnSpare::create([
                'return_id' => $newReturnId,
                'user_id' =>  $request->input('user_id'),
                'request_id' => $request->input('request_id'),
                'sparepart_id' => $request->input('sparepart_id'),
                'serial_part' => $request->input('serial_part') ?? null,
                'usage_id' => $request->input('usage_id'),
                'spare_qty' => $request->input('spare_qty'),
                'warehouse_id' => $request->input('warehouse_id'),
                'defect_id' => $request->input('defect_id'),
                'remark' => $request->input('remark'),
                'return_date' => $request->input('return_date'),
            ]);

            // Log the newly created record
            Log::info('Created ReturnSpare: ', $spare->toArray());

            // Commit the transaction
            DB::commit();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => "Create job sheet successfully for Job Sheet ID: $newReturnId",
                'data' => $spare
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Log the error message
            Log::error('Error adding stock items: ', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => "An error occurred while adding stock items.",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function ReturnSpare(Request $request)
    {
        // Log the incoming request data
        Log::info('Received request data: ', $request->all());

        // Validate the request data
        $request->validate([
            'request_id' => 'required|integer',
            'sparepart_id' => 'required|integer',
            'spare_qty' => 'required|integer',
            'usage_id' => 'required|integer',
            'return_date' => 'nullable|date',
            'defect_id' => 'nullable|integer',
            'warehouse_id' => 'required|integer',
            'serial_part' => 'nullable|string',
        ]);

        DB::table('request_spares')
        ->where('id',$request->input('request_id'))
        ->update([
            'request_status_id' => 2,
        ]);


        // Use a database transaction
        DB::beginTransaction();
        try {

            if ($request->input('defect_id') == 2) {
                DB::table('stocks')
                ->where('sparepart_id', $request->input('sparepart_id'))
                ->where('condition_id', 1)
                ->update([
                    'using_qty' => DB::raw('using_qty - ' . $request->input('spare_qty')),
                    'quantity' => DB::raw('quantity - ' . $request->input('spare_qty')),
                    'used_qty' => DB::raw('used_qty - ' . $request->input('spare_qty'))
                ]);
                $spare = Stock::create([
                    'request_id' => $request->input('request_id'),
                    'sparepart_id' => $request->input('sparepart_id'),
                    'serial_part' => $request->input('serial_part') ?? null,
                    'usage_id' => $request->input('usage_id'),
                    'condition_id' => 2,
                    'quantity' => 0,
                    'used_qty' => 0,
                    'warehouse_id' => $request->input('warehouse_id'),
                    'defect_id' => $request->input('defect_id'),
                    'remark' => $request->input('remark'),
                    'return_date' => $request->input('return_date'),
                    'broken_qty' => $request->input('spare_qty')
                ]);
            }else {
                DB::table('stocks')
                ->where('sparepart_id', $request->input('sparepart_id'))
                ->where('condition_id', 1)
                ->update([
                    'using_qty' => DB::raw('using_qty - ' . $request->input('spare_qty'))
                ]);
                  // Create the new record
                $spare =  Stock::create([
                    'request_id' => $request->input('request_id'),
                    'sparepart_id' => $request->input('sparepart_id'),
                    'serial_part' => $request->input('serial_part') ?? null,
                    'usage_id' => $request->input('usage_id'),
                    'condition_id' => 1,
                    'quantity' => 0,
                    'used_qty' => $request->input('spare_qty'),
                    'warehouse_id' => $request->input('warehouse_id'),
                    'defect_id' => $request->input('defect_id'),
                    'remark' => $request->input('remark'),
                    'broken_qty' => 0,
                    'return_date' => $request->input('return_date'),
                ]);
            }

            // Log the newly created record
            Log::info('Created ReturnSpare: ', $spare->toArray());

            // Commit the transaction
            DB::commit();

            // Return success response
            return response()->json([
                'success' => true,
                'message' =>  "Return spare successfully",
                'data' => $spare
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            // Log the error message
            Log::error('Error adding stock items: ', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => "An error occurred while adding stock items.",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function all()
    {
        $data = DB::table('return_spares AS rs')
        ->leftJoin('spareparts AS sp', 'sp.id', '=', 'rs.sparepart_id')
        ->leftJoin('request_spares AS r', 'r.id', '=', 'rs.request_id' )
        ->leftJoin('users AS u', 'u.id', '=', 'rs.user_id')
        ->leftJoin('statuses AS st', 'st.id', '=', 'rs.status_id')
        ->leftJoin('warehouses AS w', 'w.id', '=', 'rs.warehouse_id')
        ->select(
            'rs.return_id',
            'rs.spare_qty',
            'rs.engineer_id',
            'rs.remark',
            'rs.return_date',
            'sp.sparepart_name',
            'st.status_name',
            'rs.serial_part',
            'u.first_name',
            'w.warehouse_name',
            'rs.id',
            'r.ticket_no'
        )
        ->orderBy('rs.id')
        ->get();


        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $data
        ];
     return response()->json($response, 200);
    }

    public function CloseJob(Request $request)
    {
        $request->validate([
            'return_id' => 'required|integer',
            'date_repaire' => 'required',
            'engineer_id' => 'required|integer',
            'status_id' => 'required|integer',
            'follow_up' => 'nullable|string'
        ]);

        $return_id = $request->input('return_id');
        $engineer_id = $request->input('engineer_id');
        $status_id = $request->input('status_id');
        $follow_up = $request->input('follow_up');
        $date_repaire = $request->input('date_repaire');

        // Fetch the return date from the database
        $return_date = DB::table('return_spares')
            ->where('id', $return_id)
            ->value('return_date');

        if (!$return_date) {
            return response()->json(['message' => 'Invalid return_id'], 400);
        }

        // Validate that date_repaire is not before return_date
        if (strtotime($date_repaire) < strtotime($return_date)) {
            return response()->json(['message' => 'The repair date cannot be before the return date'], 422);
        }

        DB::table('return_spares')
        ->where('id', $return_id)
        ->update([
            'engineer_id' => $engineer_id,
            'status_id' => $status_id,
            'follow_up' => $follow_up,
            'date_repaire' => $date_repaire
        ]);

         // Return success response
         return response()->json([
            'success' => true,
            'message' =>  "Update successfully",
        ], 200);
    }

    public function ListRepaire( )
    {
        $data = DB::table('return_spares AS rs')
        ->leftJoin('spareparts AS sp', 'sp.id', '=', 'rs.sparepart_id')
        ->leftJoin('warehouses AS w', 'w.id', '=', 'rs.warehouse_id')
        ->select(
            'rs.id',
            'rs.return_id',
            'rs.spare_qty',
            'rs.sparepart_id',
            'sp.sparepart_name',
            'sp.part_number',
            'rs.status_id',
            'rs.serial_part',
            'w.warehouse_name',
            'rs.warehouse_id',
            'rs.defect_id',
        )
        ->whereIn('rs.status_id', [5, 6])
        ->get();

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $data
        ];
        return response()->json($response, 200);

    }

    public function StockTransfer(Request $request)
    {
        $request->validate([
            'sparepart_id' => 'required|integer',
            'spare_qty' => 'required|integer',
            'warehouse_id' => 'required|integer',
            'serial_part' => 'nullable|string|max:255',
            'remark' => 'nullable|string',
            'defect_id' => 'nullable|integer',
            'staus_id' => 'nullable|integer'
        ]);

        $id = $request->input('id');

        if ($request->input('defect_id') == 1 && $request->input('status_id') == 5) {
            DB::table('stocks')
            ->where('sparepart_id', $request->input('sparepart_id'))
            ->where('condition_id', 1)
            ->update([
                'using_qty' => DB::raw('using_qty - ' . $request->input('spare_qty'))
            ]);
            $spare = Stock::create([
                'sparepart_id' => $request->input('sparepart_id'),
                'serial_part' => $request->input('serial_part') ?? null,
                'condition_id' => 1,
                'quantity' => 0,
                'used_qty' => $request->input('spare_qty'),
                'warehouse_id' => $request->input('warehouse_id'),
                'defect_id' => $request->input('defect_id'),
                'remark' => $request->input('remark'),
                'return_date' => $request->input('return_date'),
                'broken_qty' => 0
            ]);


        }else {
            DB::table('stocks')
            ->where('sparepart_id', $request->input('sparepart_id'))
            ->where('condition_id', 1)
            ->update([
                'using_qty' => DB::raw('using_qty - ' . $request->input('spare_qty')),
                'quantity' => DB::raw('quantity - ' . $request->input('spare_qty')),
                'used_qty' => DB::raw('used_qty - ' . $request->input('spare_qty'))
            ]);

            $spare = Stock::create([
                'sparepart_id' => $request->input('sparepart_id'),
                'serial_part' => $request->input('serial_part') ?? null,
                'condition_id' => 2,
                'quantity' => 0,
                'used_qty' => 0,
                'warehouse_id' => $request->input('warehouse_id'),
                'defect_id' => $request->input('defect_id'),
                'remark' => $request->input('remark'),
                'return_date' => $request->input('return_date'),
                'broken_qty' => $request->input('spare_qty')
            ]);
        }


        DB::table('return_spares')
        ->where('id', $id)
        ->update([
            'status_id' => 8,
        ]);



        $response = [
            'success' => true,
            'message' => "Stock Transfer Successfully",
            'data' => $spare
        ];
        return response()->json($response, 200);

    }
}
