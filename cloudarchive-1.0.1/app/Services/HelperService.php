<?php

namespace App\Services;

use App\Models\Archive;
use DB;

class HelperService 
{
    public static function getTotalStorageFormatted()
    {   

        $value = self::formatSize(auth()->user()->storage_total + auth()->user()->storage_referral);

        return $value;
    }


    public static function getTotalUsedStorageFormatted()
    {
        $total_size = Archive::select(DB::raw("sum(size) as data"))
                ->where('user_id', auth()->user()->id)
                ->get();  

        $value = self::formatSizeAll($total_size[0]['data']);
        
        return $value;
    }


    public static function formatSize($total)
    {
        $units = ['MB', 'GB', 'TB'];
        for ($i = 0; $total >= 1000; $i++) {
            $total /= 1000;
        }

        return round($total, 1) . $units[$i];
    }


    public static function formatSizeAll($total)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $total >= 1000; $i++) {
            $total /= 1000;
        }

        return round($total, 1) . $units[$i];
    }


}