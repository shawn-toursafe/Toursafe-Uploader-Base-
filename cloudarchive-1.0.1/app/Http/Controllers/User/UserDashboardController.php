<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use App\Services\Statistics\UserUsageYearlyService;
use App\Services\Statistics\StorageUsageService;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\User;
use DB;


class UserDashboardController extends Controller
{
    use Notifiable;

    private $api;

    public function __construct()
    {
        $this->api = new LicenseController();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {         
        $verify = $this->api->verify_license();

        if($verify['status']!=true){
            die('Your license is invalid or not activated, please contact support.');
        }
                
        $year = $request->input('year', date('Y'));

        $storage_usage = new StorageUsageService();

        $storage = [
            'available' => $this->formatSize(auth()->user()->storage_total * 1000000),
            'total' => $storage_usage->getTotalAchives(),
            'downloadable' => $storage_usage->getTotalDownloadableArchives(),
            'requested' => $storage_usage->getTotalRequestedArchives(),
        ];
        
        $chart_data['storage_usage'] = json_encode($storage_usage->getStorageUsageChart());

        if (!is_null(auth()->user()->plan_id)) {
            $subscription = Subscription::where('user_id', auth()->user()->id)->where('status', 'Active')->first();
        } else {
            $subscription = false;
        }

        $user_subscription = ($subscription) ? Plan::where('id', auth()->user()->plan_id)->first() : 'free';     
        
        $storage_used = $this->formatSize($storage_usage->getTotalUsage());
        $storage_used_current_year = $this->formatSize($storage_usage->getTotalUsageCurrentYear());
        $user_storage_size = $this->formatSize((auth()->user()->storage_total * 1000000) + (auth()->user()->storage_referral * 1000000));
        
        $user_storage = (auth()->user()->storage_total > 0) ? (auth()->user()->storage_total * 1000000) + (auth()->user()->storage_referral * 1000000) : 1;

        $progress = [
            'subscription' => ($storage_usage->getTotalUsage() / $user_storage) * 100,
            'zip' => ($storage_usage->getZipSize() / $user_storage) * 100,
            'document' => ($storage_usage->getDocumentSize() / $user_storage) * 100,
            'media' => ($storage_usage->getMediaSize() / $user_storage) * 100,
            'other' => ($storage_usage->getOtherSize() / $user_storage) * 100,
        ];

        return view('user.dashboard.index', compact('chart_data', 'storage', 'user_subscription', 'user_storage_size', 'storage_used', 'storage_used_current_year', 'progress'));           
    }


    /**
     * Format storage space to readable format
     */
    private function formatSize($size, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
        $size = max($size, 0); 
        $pow = floor(($size ? log($size) : 0) / log(1000)); 
        $pow = min($pow, count($units) - 1); 
        
        $size /= pow(1000, $pow);

        return round($size, $precision) . $units[$pow]; 
    }
}
