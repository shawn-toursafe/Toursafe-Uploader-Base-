<?php

namespace App\Services\Statistics;

use Illuminate\Support\Facades\Auth;
use App\Models\Archive;
use App\Models\User;
use DB;

class StorageUsageService 
{
    /**
     * Total usage per user id
     */
    public function getTotalUsage($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $total_size = Archive::select(DB::raw("sum(size) as data"))
                ->where('user_id', $user_id)
                ->get();  
        
        return $total_size[0]['data'];
    }


    /**
     * Total storage space used by all users
     */
    public function getTotalStorageUsed()
    {
        $total_size = Archive::select(DB::raw("sum(size) as data"))
                ->get();  
        
        return $total_size[0]['data'];
    }


     /**
     * Total allocated storage space for all users
     */
    public function getTotalStorageAllocated()
    {
        $total_size = User::select(DB::raw("sum(storage_total + storage_referral) as data"))
                ->get();  
        
        return $total_size[0]['data'];
    }


    /**
     * Current year total usage per user id
     */
    public function getTotalUsageCurrentYear($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $total_size = Archive::select(DB::raw("sum(size) as data"))
                ->where('user_id', $user_id)
                ->whereYear('created_at', date('Y'))
                ->get();  
        
        return $total_size[0]['data'];
    }


    /**
     * Current year total used by all users
     */
    public function getTotalStorageUsedCurrentYear()
    {
        $total_size = Archive::select(DB::raw("sum(size) as data"))
                ->whereYear('created_at', date('Y'))
                ->get();  
        
        return $total_size[0]['data'];
    }


    /**
     * Chart data - total usage during current year split by month by user id
     */
    public function getStorageUsageChart($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $standard_chars = Archive::select(DB::raw("sum(size/1000000) as data"), DB::raw("MONTH(created_at) month"))
                ->whereYear('created_at', date('Y'))
                ->where('user_id', $user_id)
                ->groupBy('month')
                ->orderBy('month')
                ->get()->toArray();  
        
        $data = [];

        for($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($standard_chars as $row) {				            
            $month = $row['month'];
            $data[$month] = intval($row['data']);
        }
        
        return $data;
    }


    /**
     * Chart data - total usage during current year split by month for all users
     */
    public function getTotalStorageUsageChart($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $standard_chars = Archive::select(DB::raw("sum(size/1000000) as data"), DB::raw("MONTH(created_at) month"))
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()->toArray();  
        
        $data = [];

        for($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($standard_chars as $row) {				            
            $month = $row['month'];
            $data[$month] = intval($row['data']);
        }
        
        return $data;
    }


    /**
     * Total archived files per user id
     */
    public function getTotalAchives($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $total_archives = Archive::select(DB::raw("count(id) as data"))
                ->where('user_id', $user_id)
                ->get();  
        
        return $total_archives;
    }


    /**
     * Ready to download files per user id
     */
    public function getTotalDownloadableArchives($user = null)
    {   
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $downloadable_archives = Archive::select(DB::raw("count(id) as data"))
                ->where('user_id', $user_id)
                ->where('downloadable', true)
                ->get();  
        
        return $downloadable_archives;
    }

    
    /**
     * Active download requests per user id
     */
    public function getTotalRequestedArchives($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $requested_archives = Archive::select(DB::raw("count(id) as data"))
                ->where('user_id', $user_id)
                ->where('download_requested', true)
                ->get();  
        
        return $requested_archives;
    }


    /**
     * Total zip file type size per user id
     */
    public function getZipSize($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $zip_size = Archive::select(DB::raw("sum(size) as data"))
                ->where('user_id', $user_id)
                ->where('file_type', 'zip')
                ->get();  
        
        return $zip_size[0]['data'];
    }


    /**
     * Total document file type size per user id
     */
    public function getDocumentSize($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $document_size = Archive::select(DB::raw("sum(size) as data"))
                ->where('user_id', $user_id)
                ->where('file_type', 'document')
                ->get();  
        
        return $document_size[0]['data'];
    }


    /**
     * Total media file type size per user id
     */
    public function getMediaSize($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $media_size = Archive::select(DB::raw("sum(size) as data"))
                ->where('user_id', $user_id)
                ->where('file_type', 'media')
                ->get();  
        
        return $media_size[0]['data'];
    }


    /**
     * Total other file type size per user id
     */
    public function getOtherSize($user = null)
    {
        $user_id = (is_null($user)) ? Auth::user()->id : $user;

        $other_size = Archive::select(DB::raw("sum(size) as data"))
                ->where('user_id', $user_id)
                ->where('file_type', 'other')
                ->get();  
        
        return $other_size[0]['data'];
    }


    /**
     * Total zip file type size
     */
    public function getTotalZipSize()
    {
        $zip_size = Archive::select(DB::raw("sum(size) as data"))
                ->where('file_type', 'zip')
                ->get();  
        
        return $zip_size[0]['data'];
    }


    /**
     * Total document file type size 
     */
    public function getTotalDocumentSize()
    {
        $document_size = Archive::select(DB::raw("sum(size) as data"))
                ->where('file_type', 'document')
                ->get();  
        
        return $document_size[0]['data'];
    }


    /**
     * Total media file type size 
     */
    public function getTotalMediaSize()
    {
        $media_size = Archive::select(DB::raw("sum(size) as data"))
                ->where('file_type', 'media')
                ->get();  
        
        return $media_size[0]['data'];
    }


    /**
     * Total other file type size 
     */
    public function getTotalOtherSize()
    {
        $other_size = Archive::select(DB::raw("sum(size) as data"))
                ->where('file_type', 'other')
                ->get();  
        
        return $other_size[0]['data'];
    }


    /**
     * Current Month Glacier Storage Usage 
     */
    public function getCurrentMonthGlacierUsage()
    {
        $other_size = Archive::select(DB::raw("sum(size) as data"))
                ->whereMonth('created_at', date('m'))        
                ->where('archive_type', 'glacier')
                ->get();  
        
        return $other_size[0]['data'];
    }


    /**
     * Current Month Deep Archive Storage Usage 
     */
    public function getCurrentMonthDeepArchiveUsage()
    {
        $other_size = Archive::select(DB::raw("sum(size) as data"))
                ->whereMonth('created_at', date('m'))        
                ->where('archive_type', 'deep_archive')
                ->get();  
        
        return $other_size[0]['data'];
    }


    /**
     * Current Month Free Storage Usage 
     */
    public function getCurrentMonthFreeStorageUsage()
    {
        $other_size = Archive::select(DB::raw("sum(size) as data"))
                ->whereMonth('created_at', date('m'))        
                ->where('subscription', 'free')
                ->get();  
        
        return $other_size[0]['data'];
    }


    /**
     * Current Month Paid Storage Usage 
     */
    public function getCurrentMonthPaidStorageUsage()
    {
        $other_size = Archive::select(DB::raw("sum(size) as data"))
                ->whereMonth('created_at', date('m'))        
                ->where('subscription', 'paid')
                ->get();  
        
        return $other_size[0]['data'];
    }


    /**
     * Current Year Glacier Storage Usage 
     */
    public function getCurrentYearGlacierUsage()
    {
        $other_size = Archive::select(DB::raw("sum(size) as data"))
                ->whereYear('created_at', date('Y'))       
                ->where('archive_type', 'glacier')
                ->get();  
        
        return $other_size[0]['data'];
    }


    /**
     * Current Year Deep Archive Storage Usage 
     */
    public function getCurrentYearDeepArchiveUsage()
    {
        $other_size = Archive::select(DB::raw("sum(size) as data"))
                ->whereYear('created_at', date('Y'))      
                ->where('archive_type', 'deep_archive')
                ->get();  
        
        return $other_size[0]['data'];
    }


    /**
     * Total Glacier Storage Usage 
     */
    public function getTotalGlacierUsage()
    {
        $other_size = Archive::select(DB::raw("sum(size) as data"))
                ->whereYear('created_at', date('Y'))       
                ->where('archive_type', 'glacier')
                ->get();  
        
        return $other_size[0]['data'];
    }


    /**
     * Total Deep Archive Storage Usage 
     */
    public function getTotalDeepArchiveUsage()
    {
        $other_size = Archive::select(DB::raw("sum(size) as data"))    
                ->where('archive_type', 'deep_archive')
                ->get();  
        
        return $other_size[0]['data'];
    }


    /**
     * Current Year Free Storage Usage 
     */
    public function getCurrentYearFreeStorageUsage()
    {
        $other_size = Archive::select(DB::raw("sum(size) as data"))
                ->whereYear('created_at', date('Y'))    
                ->where('subscription', 'free')
                ->get();  
        
        return $other_size[0]['data'];
    }


    /**
     * Current Year Paid Storage Usage 
     */
    public function getCurrentYearPaidStorageUsage()
    {
        $other_size = Archive::select(DB::raw("sum(size) as data"))
                ->whereYear('created_at', date('Y'))       
                ->where('subscription', 'paid')
                ->get();  
        
        return $other_size[0]['data'];
    }
}