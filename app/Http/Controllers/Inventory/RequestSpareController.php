<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\RequestSpare;
use App\Models\Inventory\Stock;
use App\Models\Sparepart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RequestSpareController extends Controller
{
    public function RequestSpare(Request $request)
    {
        $request->validate([
            'terminal_id' => 'required|integer',
            'stock_id' => 'required|integer',
            'spare_qty' => 'required|integer',
            'ticket_no' => 'required|string',
            'remark' => 'nullable|string',
            'request_date' => 'nullable|date',
            'engineer_id' => 'nullable|integer',
        ]);

        // Use a database transaction
        DB::beginTransaction();
        try {
            // Lock the request_spares table for reading and writing to prevent race condition
            $latestRequest = DB::table('request_spares')->lockForUpdate()->orderBy('id', 'desc')->first();

            // Generate a new custom request_id
            $newIdNumber = $latestRequest ? intval(substr($latestRequest->request_id, 2)) + 1 : 1;
            // Ensure that request_id does not exceed RQ100000
            if ($newIdNumber > 100000) {
                throw new \Exception('Maximum request_name limit reached.');
            }

            $newRequestId = 'RQ' . str_pad($newIdNumber, 5, '0', STR_PAD_LEFT);

            // Create the new record
            $spare = RequestSpare::create([
                'request_name' => $newRequestId,
                'terminal_id' => $request->input('terminal_id'),
                'stock_id' => $request->input('stock_id'),
                'spare_qty' => $request->input('spare_qty'),
                'ticket_no' => $request->input('ticket_no'),
                'engineer_id' => $request->input('engineer_id'),
                'remark' => $request->input('remark'),
                'request_date' => $request->input('request_date'),
            ]);

            // Commit the transaction
            DB::commit();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => "Stock items added successfully.",
                'data' => $spare
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "An error occurred while adding stock items.",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function all(): JsonResponse
    {
        $data = DB::table('request_spares AS rs')
        ->leftJoin('terminals AS t', 't.id', '=', 'rs.terminal_id')
        ->leftJoin('spareparts AS sp', 'sp.id', '=', 'rs.sparepart_id')
        ->leftJoin('engineers AS e', 'e.id', '=', 'rs.engineer_id')
        ->leftJoin('statuses AS st', 'st.id', '=', 'rs.status_id')
        ->select(
            DB::raw('MAX(rs.id) AS rs_id'),
            'rs.request_name',
            DB::raw('SUM(rs.spare_qty) AS total_spare_qty'),
            DB::raw('SUM(rs.stock_qty) AS total_stock_qty'),
            'rs.spare_qty',
            'rs.stock_qty',
            'rs.terminal_id',
            't.install_date',
            'rs.engineer_id',
            'rs.remark',
            'rs.request_date',
            'rs.ticket_no',
            'rs.sparepart_id',
            'st.status_name',
        )
        ->groupBy(
            'rs.spare_qty',
            'rs.terminal_id',
            't.install_date',
            'rs.request_name',
            'rs.stock_qty',
            'rs.engineer_id',
            'rs.remark',
            'rs.request_date',
            'rs.ticket_no',
            'rs.sparepart_id',
            'st.status_name',
        )
        ->orderBy('rs_id')
        ->get();
        $formattedData = [];

        foreach ($data as $item) {
            if (!isset($formattedData[$item->request_name])) {
                $formattedData[$item->request_name] = [
                    'request_name' => $item->request_name,
                    'terminal_id' => $item->terminal_id,
                    'status_name' => $item->status_name,
                    'ticket_no' => $item->ticket_no,
                    'engineer_id' => $item->engineer_id,
                    'remark' => $item->remark,
                    'install_date' => $item->install_date,
                    'request_date' => $item->request_date,
                    'request_data' => [],
                    'sum_spare_qty' => 0,
                ];
            }

             $formattedData[$item->request_name]['sum_spare_qty'] += $item->total_spare_qty;
             $sparepartId = $item->sparepart_id;
             $found = false;

             foreach ($formattedData[$item->request_name]['request_data'] as &$data) {
                 if ($data['sparepart_id'] == $sparepartId) {
                     $data['spare_qty'] += $item->total_spare_qty;
                     $data['stock_qty'] += $item->total_stock_qty;
                     $found = true;
                     break;
                 }
             }

             if (!$found) {
                 $formattedData[$item->request_name]['request_data'][] = [
                     'sparepart_id' => $sparepartId,
                     'spare_qty' => $item->total_spare_qty,
                     'stock_qty' => $item->total_stock_qty,
                     // 'serial_part' => $item->serial_part
                 ];
             }
        }

        $formattedData = array_values($formattedData);

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $formattedData
        ];
    return response()->json($response, 200);
    }

    public function RequestMultiple1(Request $request)
    {
        $data = $request->all();  // Get all input data
        Log::info('Received Request Data:', $data);
        $responses = [];
        DB::beginTransaction();

        // Lock the request_spares table for reading and writing to prevent race condition
        $latestRequest = DB::table('request_spares')->lockForUpdate()->orderBy('id', 'desc')->first();

        // Generate a new custom request_id
        $newIdNumber = $latestRequest ? intval(substr($latestRequest->request_id, 2)) + 1 : 1;
        // Ensure that request_id does not exceed RQ100000
        if ($newIdNumber > 100000) {
            throw new \Exception('Maximum request_id limit reached.');
        }

        $newRequestId = 'RQ' . str_pad($newIdNumber, 5, '0', STR_PAD_LEFT);

        try {
            foreach ($data as $item) {
                // Validate each item individually
                $validator = Validator::make($item, [
                    'terminal_id' => 'required|integer',
                    'stock_id' => 'required|integer',
                    'spare_qty' => 'required|integer',
                    'ticket_no' => 'required|string',
                    'remark' => 'nullable|string',
                    'request_date' => 'nullable|date',
                    'engineer_id' => 'nullable|integer',
                ]);
                // Create the new record
                $spare = RequestSpare::create([
                    'request_name' => $newRequestId,
                    'terminal_id' => $item['terminal_id'],
                    'stock_id' => $item['stock_id'],
                    'spare_qty' => $item['spare_qty'],
                    'ticket_no' => $item['ticket_no'],
                    'engineer_id' => $item['engineer_id'],
                    'remark' => $item['remark'],
                    'request_date' => $item['request_date'],
                ]);

                $responses[] = [
                    'success' => true,
                    'message' => "Stock items added successfully for request ID: $newRequestId",
                    'data' => $spare
                ];
            }

            DB::commit();

            // Return success response
            return response()->json($responses, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "An error occurred while adding stock items.",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function RequestMultiple(Request $request) {
        // Log incoming request for debugging
        Log::info('Received request data: ', $request->all());

        // Decode the incoming JSON data
        $data = $request->json()->all();
        $responses = [];
        $errors = [];

        // Lock the request_spares table for reading and writing to prevent race condition
        $latestRequest = DB::table('request_spares')->lockForUpdate()->orderBy('id', 'desc')->first();

        // Generate a new custom request_id
        $newIdNumber = $latestRequest ? intval(substr($latestRequest->request_name, 2)) + 1 : 1;
        // Ensure that request_id does not exceed RQ100000
        if ($newIdNumber > 100000) {
            throw new \Exception('Maximum request_id limit reached.');
        }

        $newRequestId = 'RQ' . str_pad($newIdNumber, 5, '0', STR_PAD_LEFT);

        try {
            foreach ($data as $index => $item) {
                // Validate each item individually
                $validator = Validator::make($item, [
                    'terminal_id' => 'required|integer',
                    'sparepart_id' => 'required|integer',
                    'spare_qty' => 'required|integer',
                    'ticket_no' => 'required|string',
                    'remark' => 'nullable|string',
                    'request_date' => 'nullable|date',
                    'engineer_id' => 'nullable|integer',
                ]);

                if ($validator->fails()) {
                    $errors[] = [
                        'index' => $index,
                        'errors' => $validator->errors()->toArray(),
                    ];
                    continue;
                }

                $requestItems[] = [
                    'sparepart_id' => $item['sparepart_id'],
                    'spare_qty' => $item['spare_qty'],
                ];

                // Create the new record
                RequestSpare::create([
                    'request_name' => $newRequestId,
                    'terminal_id' => $item['terminal_id'],
                    'sparepart_id' => $item['sparepart_id'],
                    'spare_qty' => $item['spare_qty'],
                    'ticket_no' => $item['ticket_no'],
                    'engineer_id' => $item['engineer_id'],
                    'remark' => $item['remark'],
                    'request_date' => $item['request_date'],
                ]);

                if (!empty($errors)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation errors occurred.',
                        'errors' => $errors,
                    ], 422);
                }

                $responses = [
                    'success' => true,
                    'message' => "Stock items added successfully for request ID: $newRequestId",
                    'data' => [
                        'request_name' => $newRequestId,
                        'terminal_id' => $data[0]['terminal_id'],
                        'ticket_no' => $data[0]['ticket_no'],
                        'engineer_id' => $data[0]['engineer_id'],
                        'remark' => $data[0]['remark'],
                        'request_data' => $requestItems
                    ]
                ];
            }

            DB::commit();

            // Return success response
            return response()->json($responses, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => "An error occurred while adding stock items.",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function ApproveRequest(Request $request)
    {
        $request_id = $request->input('request_name');
        $stock_ids = $request->input('stock_ids');

            // Validate request_id and stock_ids
        if (!$request_id || !is_array($stock_ids) || empty($stock_ids)) {
            return response()->json(['message' => 'Invalid request_id or stock_ids'], 400);
        }

        // Check if all stock_ids exist in the database
        $valid_stock_ids = DB::table('stocks')
            ->whereIn('id', $stock_ids)
            ->pluck('id')
            ->toArray();

        if (count($valid_stock_ids) !== count($stock_ids)) {
            return response()->json(['message' => 'Please select stock'], 400);
        }

        // Begin a transaction
        DB::beginTransaction();

        try {
            $spareParts = DB::table('request_spares AS rs')
            ->where('rs.request_name', $request_id)
            ->get(['rs.spare_qty', 'rs.id']);

            foreach ($spareParts as $sparePart) {
                DB::table('request_spares AS rs')
                    ->where('rs.id', $sparePart->id)
                    ->update([
                        'rs.status_id' => 3,
                        'rs.stock_qty' => $sparePart->spare_qty
                    ]);
            }

            // Fetch and aggregate request data
            $request_data = DB::table('request_spares AS rs')
                ->leftJoin('statuses AS st', 'st.id', '=', 'rs.status_id')
                ->select(
                    'rs.sparepart_id',
                    DB::raw('SUM(rs.spare_qty) as total_spare_qty')
                )
                ->where('rs.request_name', $request_id)
                ->groupBy('rs.sparepart_id')
                ->get()
                ->keyBy('sparepart_id');

            // Fetch and aggregate stock data
            $stock_data = DB::table('stocks AS s')
                ->join('spareparts AS sp', 'sp.id', '=', 's.sparepart_id')
                ->whereIn('s.id', $stock_ids)
                ->select(
                    's.id as stock_id',
                    's.sparepart_id',
                    's.used_qty as stock_quantity',
                    's.using_qty'
                )
                ->get();

            $result = [];

            foreach ($stock_data as $stock) {
                $sparepart_id = $stock->sparepart_id;
                if (isset($request_data[$sparepart_id])) {
                    $request_qty = $request_data[$sparepart_id]->total_spare_qty;

                    // Ensure stock quantity is sufficient
                    if ($stock->stock_quantity >= $request_qty) {
                        // Update the stock quantity
                        DB::table('stocks')
                            ->where('id', $stock->stock_id)
                            ->update([
                                'used_qty' => $stock->stock_quantity - $request_qty,
                                'using_qty' => $stock->using_qty + $request_qty
                            ]);

                        $result[] = [
                            'sparepart_id' => $sparepart_id,
                            'remaining_quantity' => $stock->stock_quantity - $request_qty,
                        ];
                    } else {
                        // Insufficient stock quantity
                        throw new \Exception("Insufficient stock for sparepart_id $sparepart_id");
                    }
                } else {
                    // No request for this sparepart_id
                    $result[] = [
                        'sparepart_id' => $sparepart_id,
                        'remaining_quantity' => $stock->stock_quantity,
                    ];
                }
            }

            // Commit the transaction
            DB::commit();

            // Return response
            return response()->json([
                'success' => true,
                'message' => 'Request approved successfully',
                'result' => $result
            ]);

        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollback();
            // Handle the exception
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve request: ' . $e->getMessage()
            ], 500);
        }
    }

    public function RejectRequest(Request $request)
    {
        $request_id = $request->input('request_name');

        $data = DB::table('request_spares AS rs')
        ->where('rs.request_name', $request_id)
        ->update(['rs.status_id' => 4]);

        return response()->json([
            'success' => true,
            'message' => 'Request rejected successfully',
            'data' => $data
        ]);
    }

    public function allRequest()
    {
        $data = DB::table('request_spares AS rs')
        ->leftJoin('terminals AS t', 't.id', '=', 'rs.terminal_id')
        // ->leftJoin('stocks AS s', 's.id', '=', 'rs.stock_id')
        ->leftJoin('spareparts AS sp', 'sp.id', '=', 'rs.sparepart_id')
        ->leftJoin('engineers AS e', 'e.id', '=', 'rs.engineer_id')
        ->leftJoin('statuses AS st', 'st.id', '=', 'rs.status_id')
        ->where('rs.status_id', 3)
        ->where('rs.request_status_id', 1)
        ->select(
            DB::raw('MAX(rs.id) AS rs_id'),
            'rs.request_name',
            'rs.stock_qty',
            'rs.spare_qty',
            'rs.terminal_id',
            't.install_date',
            'rs.engineer_id',
            'rs.remark',
            'rs.request_date',
            'rs.ticket_no',
            'rs.sparepart_id',
            'st.status_name',
            // 's.used_qty'
        )
        ->groupBy(
            'rs.spare_qty',
            'rs.terminal_id',
            't.install_date',
            'rs.request_name',
            'rs.stock_qty',
            'rs.engineer_id',
            'rs.remark',
            'rs.request_date',
            'rs.ticket_no',
            'rs.sparepart_id',
            'st.status_name',
            // 's.used_qty'
        )
        ->orderBy('rs_id')
        ->get();
        $formattedData = [];

        foreach ($data as $item) {
            if (!isset($formattedData[$item->request_name])) {
                $formattedData[$item->request_name] = [
                    'request_name' => $item->request_name,
                    'terminal_id' => $item->terminal_id,
                    'status_name' => $item->status_name,
                    'ticket_no' => $item->ticket_no,
                    'engineer_id' => $item->engineer_id,
                    'remark' => $item->remark,
                    'install_date' => $item->install_date,
                    'request_date' => $item->request_date,
                    'request_data' => [],
                    'sum_spare_qty' => 0
                ];
            }

            $formattedData[$item->request_name]['sum_spare_qty'] += $item->spare_qty;
            $formattedData[$item->request_name]['request_data'][] = [
                'sparepart_id' => $item->sparepart_id,
                'spare_qty' => $item->spare_qty,
                // 'stock' => $item->stock_qty,
                'stock_qty' => $item->stock_qty,
                // 'serial_part' => $item->serial_part
            ];
        }

        $formattedData = array_values($formattedData);

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $formattedData
        ];
    return response()->json($response, 200);
    }

    public function OneRequest( $request_id)
    {
        $data = DB::table('request_spares AS rs')
        ->leftJoin('spareparts AS sp', 'sp.id', '=', 'rs.sparepart_id')
        ->where('rs.request_status_id', 1)
        ->select(
            'rs.id as request_id',
            'rs.request_name',
            'rs.stock_qty',
            'rs.spare_qty',
            'rs.sparepart_id',
            'sp.sparepart_name',
            'sp.part_number',
            'sp.serialized'
        )
        ->where('rs.request_name', $request_id)
        ->get();

        $response = [
            'success' => true,
            'message' => "OK",
            'data' => $data
        ];
        return response()->json($response, 200);
    }

}
