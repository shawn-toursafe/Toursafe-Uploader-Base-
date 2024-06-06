<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\User\UploadController;
use App\Models\Archive;

class ProcessDownloadTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrieve:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check retrieval archives';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Clean locally stored audio result files based on the set date.
     *
     * @return int
     */
    public function handle()
    {
        $aws = new UploadController();

        # Get all requested archives
        $archives = Archive::where('download_requested', true)->get();
        
        foreach($archives as $row) {

            # Check if archive is ready to download
            $result = $aws->checkDownloadable($row['id']);

            if (!$result['ongoing']) {            

                    $row->update([
                        'downloadable' => true,
                        'download_requested' => false,
                        'valid_until' => $result['expiry_date'],
                    ]);
            
            }
        }
    }
}
