<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Archive;
use Carbon\Carbon;

class ProcessExpirationDateTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'downloadable:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Control downloadable archive expiration dates';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
         # Get all requested archives
         $archives = Archive::where('downloadable', true)->get();
        
         foreach($archives as $row) {

            $result = Carbon::createFromFormat('Y-m-d H:i:s', $row['valid_until'])->isPast();
 
             if ($result) {            
                
                $row->update([
                    'downloadable' => false,
                    'valid_until' => null,
                ]);
             
             } 
         }
    }
}
