<?php

namespace Database\Seeders;

use App\Models\Issue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IssueSeeder extends Seeder
{
    public function run(){
        DB::table('issues')->delete();
        $issue = [
                [
                    'issue_name' => 'Receipt jam',
                ],
                [
                    'issue_name' => 'LCD screen issue',
                ],
                [
                    'issue_name' => 'Unable to open safe door',
                ],
                [
                    'issue_name' => 'Safe door',
                ],
                [
                    'issue_name' => 'Camera Issue',
                ],
                [
                    'issue_name' => 'Error Cassette',
                ],
                [
                    'issue_name' => 'Cash stuck',
                ],
                [
                    'issue_name' => 'Error amount',
                ],
                [
                    'issue_name' => 'Stuck Screen',
                ],
                [
                    'issue_name' => 'Bank note reader error',
                ],
                [
                    'issue_name' => 'Error can not deposit',
                ],
                [
                    'issue_name' => 'HDD issue',
                ],
                [
                    'issue_name' => 'Error Sensor',
                ],
                [
                    'issue_name' => 'Cash surplus and shortage',
                ],
                [
                    'issue_name' => 'DCT error',
                ],
                [
                    'issue_name' => 'Error special eclectic',
                ],
                [
                    'issue_name' => 'Error dispenser',
                ],
                [
                    'issue_name' => 'Escrow Error',
                ],
                [
                    'issue_name' => 'Error Android board',
                ],
                [
                    'issue_name' => 'Fake note',
                ],
                [
                    'issue_name' => 'Detect wrong serial number',
                ],
                [
                    'issue_name' => 'Error transport',
                ],
                [
                    'issue_name' => 'Show wrong amount',
                ],
                [
                    'issue_name' => 'Not accept denomination',
                ],
                [
                    'issue_name' => 'Wrong denomination',
                ],
                [
                    'issue_name' => 'Error I/O',
                ],
                [
                    'issue_name' => 'Transaction decline',
                ],
                [
                    'issue_name' => 'Error baffle door',
                ],
                [
                    'issue_name' => 'Error reject box',
                ],
                [
                    'issue_name' => 'Receipt printer error',
                ],
                [
                    'issue_name' => 'Operator door error',
                ],
                [
                    'issue_name' => 'Network card error',
                ],
                [
                    'issue_name' => 'Application hang',
                ],
                [
                    'issue_name' => 'Error password of combination',
                ],
                [
                    'issue_name' => 'Camera Error',
                ],
                [
                    'issue_name' => 'Card Reader Error',
                ],
                [
                    'issue_name' => 'Card Jammed',
                ],
                [
                    'issue_name' => 'Cash Rejected and Torn',
                ],
                [
                    'issue_name' => 'Show Black Screen',
                ],
                [
                    'issue_name' => 'Cash Jam',
                ],
                [
                    'issue_name' => 'Application Error',
                ],
                [
                    'issue_name' => 'Connection Error',
                ],
                [
                    'issue_name' => 'Power Supply Error',
                ],
                [
                    'issue_name' => 'AFD Controller Board Error',
                ],
                [
                    'issue_name' => 'Shutter Error',
                ],
                [
                    'issue_name' => 'Stacker Error',
                ],
                [
                    'issue_name' => 'Motor Error',
                ],
                [
                    'issue_name' => 'Belt Spoil',
                ],
                [
                    'issue_name' => 'EPP Error',
                ],
                [
                    'issue_name' => 'FIL Error',
                ],
                [
                    'issue_name' => 'Picker Error',
                ],
                [
                    'issue_name' => 'Stripper Wheel/Take Away Wheel Error',
                ],
                [
                    'issue_name' => 'PC Error',
                ],
            ];
        Issue::insert($issue);
    }
}
