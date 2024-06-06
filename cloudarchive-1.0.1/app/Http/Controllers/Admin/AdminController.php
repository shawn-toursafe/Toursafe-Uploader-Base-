<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use App\Services\Statistics\CostsService;
use App\Services\Statistics\PaymentsService;
use App\Services\Statistics\RegistrationService;
use App\Services\Statistics\UserRegistrationMonthlyService;
use App\Services\Statistics\StorageUsageService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;

class AdminController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = new LicenseController();
    }

    /**
     * Display admin dashboard
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
        $month = $request->input('month', date('m'));

        $cost = new CostsService($year, $month);
        $payment = new PaymentsService($year, $month);
        $registration = new RegistrationService($year, $month);
        $user_registration = new UserRegistrationMonthlyService($month);
        $storage_usage = new StorageUsageService();
       
        $total_data_monthly = [
            'new_users_current_month' => $registration->getNewUsersCurrentMonth(),
            'new_users_past_month' => $registration->getNewUsersPastMonth(),
            'new_subscribers_current_month' => $registration->getNewSubscribersCurrentMonth(),
            'new_subscribers_past_month' => $registration->getNewSubscribersPastMonth(),
            'income_current_month' => $payment->getTotalPaymentsCurrentMonth(),
            'income_past_month' => $payment->getTotalPaymentsPastMonth(),
            'spending_current_month' => $cost->getTotalCostCurrentMonth(),
            'spending_past_month' => $cost->getTotalCostPastMonth(),
        ];

        $total_data_yearly = [
            'total_new_users' => $registration->getNewUsersCurrentYear(),
            'total_new_subscribers' => $registration->getNewSubscribersCurrentYear(),
            'total_income' => $payment->getTotalPaymentsCurrentYear(),
            'total_spending' => $cost->getTotalCostCurrentYear(),
        ];
        
        $chart_data['total_new_users'] = json_encode($registration->getAllUsers());
        $chart_data['monthly_new_users'] = json_encode($user_registration->getRegisteredUsers());
        $chart_data['total_income'] = json_encode($payment->getPayments());

        $percentage['users_current'] = json_encode($registration->getNewUsersCurrentMonth());
        $percentage['users_past'] = json_encode($registration->getNewUsersPastMonth());
        $percentage['subscribers_current'] = json_encode($registration->getNewSubscribersCurrentMonth());
        $percentage['subscribers_past'] = json_encode($registration->getNewSubscribersPastMonth());
        $percentage['income_current'] = json_encode($payment->getTotalPaymentsCurrentMonth());
        $percentage['income_past'] = json_encode($payment->getTotalPaymentsPastMonth());
        $percentage['spending_current'] = json_encode($cost->getTotalCostCurrentMonth());
        $percentage['spending_past'] = json_encode($cost->getTotalCostPastMonth());

        $chart_data['storage_usage'] = json_encode($storage_usage->getTotalStorageUsageChart());

        $total_used = $this->formatSize($storage_usage->getTotalStorageUsed());
        $total_allocated = $this->formatSize($storage_usage->getTotalStorageAllocated() * 1000000);

        $total_storage = ($storage_usage->getTotalStorageAllocated() > 0) ? $storage_usage->getTotalStorageAllocated() * 1000000 : 1;

        $progress = [
            'glacier' => ($storage_usage->getTotalGlacierUsage() / $total_storage) * 100,
            'deep_archive' => ($storage_usage->getTotalDeepArchiveUsage() / $total_storage) * 100,
        ];

        $result = User::latest()->take(5)->get();
        $transaction = User::select('users.id', 'users.email', 'users.name', 'users.profile_photo_path', 'payments.*')->join('payments', 'payments.user_id', '=', 'users.id')->orderBy('payments.created_at', 'DESC')->take(5)->get();       

        return view('admin.dashboard.index', compact('total_data_monthly', 'total_data_yearly', 'chart_data', 'percentage', 'total_used', 'total_allocated', 'progress', 'result', 'transaction'));
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
