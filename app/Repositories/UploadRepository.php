<?php

namespace App\Repositories;

use App\Jobs\ImportData;
use JsonMachine\JsonMachine;

class UploadRepository
{
    public $file, $profiles;

    public function getFile($file)
    {
        $this->file = $file;
        $this->profiles = [];
        $this->typeOfFile();
    }

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

    public function parseJson()
    {
        // Parse JSON and extract records
        $this->profiles = JsonMachine::fromFile($this->file);
        $this->uploadData();
    }

    public function parseCsv()
    {
        // Parse CSV and extract records
        return redirect()->back()->withError('CSV not supported');
    }

    public function parseXml()
    {
        // Parse XML and extract records
        return redirect()->back()->withError('XML not supported');
    }

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

    public function returnBackNoSupport()
    {
        return redirect()->back()->withError('File type not supported');
    }
}