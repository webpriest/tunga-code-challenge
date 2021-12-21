<?php

namespace App\Http\Controllers;

use App\Jobs\ImportData;
use Illuminate\Http\Request;
use JsonMachine\JsonMachine;

class ProfileController extends Controller
{
    /**
     * Display upload form
     * 
     * @return void
     */
    public function index()
    {
        return view('index');
    }

    public function store(Request $request)
    {
        if($request->hasFile('data_file')) {
            $profiles = JsonMachine::fromFile($request->file('data_file'));
                
            try {
                // Loop through parsed records
                foreach($profiles as $record) {
                    // JSON encode every record and format with white space and line returns
                    $raw = json_encode($record, JSON_PRETTY_PRINT);
                    // JSON decode in associative array for PHP
                    $record = json_decode($raw, true);
                    // Dispatch job for queues
                    // Job file located at app > Jobs > ImportData.php
                    dispatch(new ImportData($record));
                }
            }
            catch(Throwable $e) {
                
                report($e);

                return false;
            }
        }
    }
}
