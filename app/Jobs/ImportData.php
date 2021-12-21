<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Profile;
use App\Utils\DateFormatter;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ImportData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $record;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($record)
    {
        $this->record = $record;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get age on profile
        // DateFormatter is a custom class format date for DB and calculate age
        $age = DateFormatter::getAge($this->record['date_of_birth']);
        
        /**
         * Option to include test for three identical digits in credit card
         * if($age > 17 && $age < 66 && CardDigitChecker::identicalDigits($this->record['credit_card']['number'])) {
         */

        // Check that age is between 18 and 65
        if ($age > 17 && $age < 66) {

            // Create instance of the profile model
            $profile = new Profile;
            $profile->name = $this->record['name'];
            $profile->address = $this->record['address'];
            $profile->description = $this->record['description'];
            $profile->email = $this->record['email'];
            $profile->interest = $this->record['interest'];
            $profile->account = $this->record['account'];
            $profile->date_of_birth = DateFormatter::dbFormat($this->record['date_of_birth']);
            $profile->checked = $this->record['checked'] ? 1 : 0;
            $profile->save();
    
            // Add credit card record for the profile
            $profile->credit_card()->create([
                'name' => $this->record['credit_card']['name'],
                'type' => $this->record['credit_card']['type'],
                'number' => $this->record['credit_card']['number'],
                'expirationDate' => $this->record['credit_card']['expirationDate'],
            ]);
        }
    }
}
