<?php

namespace App\Services\Statistics;

use Illuminate\Support\Facades\Auth;
use App\Models\Result;
use DB;

class UserUsageMonthlyService 
{
    private $month;

    public function __construct(int $month)
    {
        $this->month = $month;
    }




    public function getTotalAudioFiles()
    {
        $audio_files = Result::select(DB::raw("count(result_url) as data"))
                ->whereMonth('created_at', $this->month)
                ->where('user_id', Auth::user()->id)
                ->get();  
        
        return $audio_files;
    }



}