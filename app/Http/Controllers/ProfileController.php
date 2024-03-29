<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UploadRepository;

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

    /**
     * Process uploaded file
     * 
     * @return void
     */
    public function store(Request $request, UploadRepository $uploads)
    {
        // Dependency inject with UploadRepository service container
        // Test if request has file
        if($request->hasFile('data_file')) {
            $file = $request->file('data_file');
            if($file) {
                // Pass file to service container
                $uploads->getFile($request->file('data_file'));
                return redirect()->back();
            }
            else {
                return redirect()->back()->withError("File could not be uploaded.");
            }
        }
        else {
            return redirect()->back()->withError("Please choose a file.");
        }
    }
}
