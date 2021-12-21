<?php

namespace App\Repositories;

use App\Jobs\ImportData;
use JsonMachine\JsonMachine;

class UploadRepository
{
    public $file, $profiles;

    /**
     * Receives uploaded file
     */
    public function getFile($file)
    {
        $this->file = $file;
        $this->profiles = [];
        $this->typeOfFile();
    }

    /**
     * Checks file MIME type to determine which method to call
     */
    public function typeOfFile()
    {
        $fileType = $this->file->getClientMimeType();
        
        switch ($fileType) {
            case "application/json":
                $this->parseJson();
                break;
            case 'text/csv':
            case 'application/vnd.ms-excel':
                $this->parseCsv();
                break;
            case 'application/xml':
            case 'text/xml':
                $this->parseXml();
                break;
            
            default:
                $this->returnBackNoSupport();
        }
    }

    /**
     * Parse JSON file
     */
    public function parseJson()
    {
        // Parse JSON and extract records
        $this->profiles = JsonMachine::fromFile($this->file);
        $this->uploadData();
    }

    /**
     * Parse CSV file
     */
    public function parseCsv()
    {
        // Parse CSV and extract records
        return redirect()->back()->withError('CSV not supported');
    }

    /**
     * Parse XML file
     */
    public function parseXml()
    {
        // Parse XML and extract records
        return redirect()->back()->withError('XML not supported');
    }

    /**
     * Process parsed file
     */
    public function uploadData()
    {
        try {
            foreach($this->profiles as $profile) {
                $raw = json_encode($profile, JSON_PRETTY_PRINT);
                // JSON decode in associative array for PHP
                $profile = json_decode($raw, true);
                // Dispatch job for queues
                // Job file located at app > Jobs > ImportData.php
                dispatch(new ImportData($profile));
            }
            return redirect()->back()->withSuccess('File uploaded, and records queued.');
        } catch (\Throwable $e) {
            return redirect()->back()->withError('File could not be processed.');
        }
    }

    /**
     * Return to initial view with error message
     */
    public function returnBackNoSupport()
    {
        return redirect()->back()->withError('File type not supported');
    }
}