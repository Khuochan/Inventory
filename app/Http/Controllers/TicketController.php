<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\PMReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TicketsImport;
use App\Models\CsvData;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TicketController extends Controller
{

    public function getAll() {
        $ticket = Ticket::all();
        return response()->json($ticket->toJson());
    }

    public function index()
    {
        $data = DB::table('tickets')->orderBy('ticket_id', 'DES')
                ->get();
        return view('import_execl', compact('data'));
    }
    public function processCsv(Request $request)
    {
        // Retrieve the uploaded file from the request
        $uploadedFile = $request->file('file');

        if ($uploadedFile) {
            // Open the uploaded file
            $file = fopen($uploadedFile->getRealPath(), 'r');

            if ($file) {
                // Skip the header row if it exists
                fgetcsv($file);

                while (($row = fgetcsv($file)) !== false) {
                    if (count($row) >= 13) {
                        try {
                            DB::table('tickets')->insert([
                                'ticket_id' => $row[0],
                                'diagnosis' => $row[1],
                                'terminal_id' => $row[2],
                                'atm_id' => $row[3],
                                'created_time' => $row[4],
                                'end_time' => $row[5],
                                'down_time' => $row[6],
                                'model' => $row[7],
                                'atm_type' => $row[8],
                                'atm_classification' => $row[9],
                                'atm_down' => $row[10],
                                'issue' => $row[12],
                                'action_taken' => $row[11],
                            ]);
                        } catch (\Exception $e) {
                            // Handle the exception or log the error message for debugging
                            echo "Error: " . $e->getMessage();
                        }
                    }
                }
                fclose($file);
            } else {
                echo "Failed to open the CSV file.";
            }
        } else {
            echo "No file uploaded.";
        }
    }

    public function importCSV(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048' // Adjust max file size if necessary
        ]);

        // Run the import logic
        $result = $this->run($request->file('file'));

        // Return the result as a JSON response
        return response()->json(['message' => $result]);
    }

    public function run(UploadedFile $file): string
    {
        try {
            \DB::beginTransaction(); // Start a database transaction

            $csvData = fopen($file->getPathname(), 'r'); // Open the uploaded CSV file

            $transRaw = true; // Skip the first row if it contains headers
            $rowCount = 0;

            while (($row = fgetcsv($csvData)) !== false) {
                $rowCount++; // Increment the row count for logging purposes

                if (!$transRaw) {
                    // Truncate values that exceed the maximum length for each column
                    $row = array_map(function ($value) {
                        return Str::substr($value, 0, 1000);
                    }, $row);

                    // Insert a new Ticket record for each row of CSV data
                    Ticket::create([
                        'ticket_id' => $row[0],
                        'diagnosis' => $row[1],
                        'terminal_id' => $row[2],
                        'atm_id' => $row[3],
                        'created_time' => $row[4],
                        'end_time' => $row[5],
                        'down_time' => $row[6],
                        'model' => $row[7],
                        'atm_type' => $row[8],
                        'atm_classification' => $row[9],
                        'atm_down' => $row[10],
                        'issue' => $row[12],
                        'action_taken' => $row[11],
                    ]);
                }
                $transRaw = false; // Set to false after processing the first row
            }

            fclose($csvData); // Close the file handler

            \DB::commit(); // Commit the transaction

            // Log the number of rows imported
            Log::info("Imported $rowCount rows from CSV file.");

            return 'Data imported successfully';
        } catch (\Exception $e) {
            \DB::rollBack(); // Rollback the transaction if an exception occurs

            // Log the error
            Log::error('Error importing data: ' . $e->getMessage());

            return 'Error importing data: ' . $e->getMessage();
        }
    }

    public function importPM(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file1' => 'required|mimes:csv,txt|max:2048' // Adjust max file size if necessary
        ]);

        // Run the import logic
        $result = $this->convert($request->file('file1'));

        // Return the result as a JSON response
        return response()->json(['message' => $result]);
    }

    public function convert(UploadedFile $file): string
    {
        try {
            \DB::beginTransaction(); // Start a database transaction

            $csvData = fopen($file->getPathname(), 'r'); // Open the uploaded CSV file

            $transRaw = true; // Skip the first row if it contains headers
            $rowCount = 0;

            while (($row = fgetcsv($csvData)) !== false) {
                $rowCount++; // Increment the row count for logging purposes

                if (!$transRaw) {
                    // Truncate values that exceed the maximum length for each column
                    $row = array_map(function ($value) {
                        return Str::substr($value, 0, 1000); // Truncate to 255 characters
                    }, $row);

                    // Insert a new Ticket record for each row of CSV data
                    PMReport::create([
                        'pm_id' => $row[0],
                        'diagnosis' => $row[1],
                        'terminal_id' => $row[2],
                        'atm_id' => $row[3],
                        'date' => $row[4],
                        'end_time' => $row[5],
                    ]);
                }
                $transRaw = false; // Set to false after processing the first row
            }

            fclose($csvData); // Close the file handler

            \DB::commit(); // Commit the transaction

            // Log the number of rows imported
            Log::info("Imported $rowCount rows from CSV file.");

            return 'Data imported successfully';
        } catch (\Exception $e) {
            \DB::rollBack(); // Rollback the transaction if an exception occurs

            // Log the error
            Log::error('Error importing data: ' . $e->getMessage());

            return 'Error importing data: ' . $e->getMessage();
        }
    }
}

