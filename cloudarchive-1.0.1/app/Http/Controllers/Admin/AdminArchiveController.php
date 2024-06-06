<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use App\Services\Statistics\StorageUsageService;
use App\Models\Archive;
use App\Models\User;
use DataTables;

class AdminArchiveController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = new LicenseController();
    }


    /**
     * Display Archive Dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $verify = $this->api->verify_license();

        if($verify['status']!=true){
            die('Your license is invalid or not activated, please contact support.');
        } 

        $storage_usage = new StorageUsageService();

        $usage_data = [
            'glacier_current_month' => $this->formatSize($storage_usage->getCurrentMonthGlacierUsage()),
            'deep_archive_current_month' => $this->formatSize($storage_usage->getCurrentMonthDeepArchiveUsage()),
            'free_storage_current_month' => $this->formatSize($storage_usage->getCurrentMonthFreeStorageUsage()),
            'paid_storage_current_month' => $this->formatSize($storage_usage->getCurrentMonthPaidStorageUsage()),
            'glacier_current_year' => $this->formatSize($storage_usage->getCurrentYearGlacierUsage()),
            'deep_archive_current_year' => $this->formatSize($storage_usage->getCurrentYearDeepArchiveUsage()),
            'free_storage_current_year' => $this->formatSize($storage_usage->getCurrentYearFreeStorageUsage()),
            'paid_storage_current_year' => $this->formatSize($storage_usage->getCurrentYearPaidStorageUsage()),
        ];
        
        $chart_data['storage_usage'] = json_encode($storage_usage->getTotalStorageUsageChart());

        $total_used = $this->formatSize($storage_usage->getTotalStorageUsed());
        $total_used_current_year = $this->formatSize($storage_usage->getTotalStorageUsedCurrentYear());
        $total_allocated = $this->formatSize($storage_usage->getTotalStorageAllocated() * 1000000);

        $total_storage = ($storage_usage->getTotalStorageAllocated() > 0) ? $storage_usage->getTotalStorageAllocated() * 1000000 : 1;

        $progress = [
            'zip' => ($storage_usage->getTotalZipSize() / $total_storage) * 100,
            'document' => ($storage_usage->getTotalDocumentSize() / $total_storage) * 100,
            'media' => ($storage_usage->getTotalMediaSize() / $total_storage) * 100,
            'other' => ($storage_usage->getTotalOtherSize() / $total_storage) * 100,
        ];

        return view('admin.archives.dashboard.index', compact('chart_data', 'usage_data', 'progress', 'total_used', 'total_allocated', 'total_used_current_year'));
    }


    /**
     * Display Archive Results
     *
     * @return \Illuminate\Http\Response
     */
    public function listArchives(Request $request)
    {
        if ($request->ajax()) {
            $data = Archive::select('archives.*', 'users.name', 'users.email', 'users.profile_photo_path')->join('users', 'users.id', '=', 'archives.user_id')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>
                                        <a class="downloadArchiveButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-cloud-arrow-down table-action-buttons edit-action-button" title="Download Archive"></i></a>
                                        <a class="requestArchiveButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-timer table-action-buttons request-action-button" title="Request Archive"></i></a>
                                        <a href="'. route("admin.archive.show", $row["id"] ). '"><i class="fa-solid fa-cabinet-filing  table-action-buttons view-action-button" title="View Archive"></i></a>
                                        <a class="deleteArchiveButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark  table-action-buttons delete-action-button" title="Delete Archive"></i></a>
                                    </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span class="font-weight-bold">'.date_format($row["created_at"], 'd M Y').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-plan-type', function($row){
                        $custom_plan = '<span class="cell-box plan-'.strtolower($row["subscription"]).'">'.ucfirst($row["subscription"]).'</span>';
                        return $custom_plan;
                    })
                    ->addColumn('user', function($row){
                        if ($row['profile_photo_path']) {
                            $path = asset($row['profile_photo_path']);
                        } else {
                            $path = URL::asset('img/users/avatar.png');
                        }

                        $user = '<div class="d-flex">
                                    <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" src="' . $path . '"></div>
                                    <div class="widget-user-name"><span class="font-weight-bold">'. $row['name'] .'</span><br><span class="text-muted">'.$row["email"].'</span></div>
                                </div>';
                        return $user;
                    })
                    ->addColumn('custom-type', function($row){
                        $custom_type = ($row['archive_type'] == 'GLACIER') ? '<span class="font-weight-bold glacier cell-box">Glacier</span>' : '<span class="font-weight-bold deep-archive cell-box">Deep Archive</span>';
                        return $custom_type;
                    })  
                    ->addColumn('custom-name', function($row){
                        $icon = '<div class="file-placeholder-container">
                                    <span class="file-placeholder-text text-center">'.$row['file_ext'].'</span>
                                    <svg width="30px" height="35px" fill="currentColor" viewBox="0 0 38 51" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="file-placeholder"><path d="M22.1666667,13.546875 L22.1666667,0 L2.375,0 C1.05885417,0 0,1.06582031 0,2.390625 L0,48.609375 C0,49.9341797 1.05885417,51 2.375,51 L35.625,51 C36.9411458,51 38,49.9341797 38,48.609375 L38,15.9375 L24.5416667,15.9375 C23.2354167,15.9375 22.1666667,14.8617187 22.1666667,13.546875 Z M38,12.1423828 L38,12.75 L25.3333333,12.75 L25.3333333,0 L25.9369792,0 C26.5703125,0 27.1739583,0.249023438 27.6192708,0.697265625 L37.3072917,10.4589844 C37.7526042,10.9072266 38,11.5148437 38,12.1423828 Z"></path></svg>';
                                '</div>';
                        $custom_name = $icon . '<span class="font-weight-bold">'.$row['file_name'].'</span>';
                        return $custom_name;
                    })  
                    ->addColumn('custom-request', function($row){

                        $requested = ($row['download_requested']) ? '<i class="fa-solid fa-timer table-info-button text-info fs-20"></i>' : '<i class="fa-solid fa-ban table-info-button fs-20"></i>';
                        return $requested;
                    })
                    ->addColumn('custom-downloadable', function($row){

                        $download = ($row['downloadable']) ? '<i class="fa-solid fa-circle-check table-info-button green fs-20"></i>' : '<i class="fa-solid fa-circle-xmark red table-info-button fs-20"></i>';
                        return $download;
                    })
                    ->addColumn('custom-size', function($row){
                        $size = '<span class="font-weight-bold">'.$this->formatSize($row["size"]).'</span>';
                        return $size;
                    })
                    ->addColumn('custom-format', function($row){
                        $custom_format = '<span class="font-weight-bold">'.strtoupper($row["file_ext"]).'</span>';
                        return $custom_format;
                    })
                    ->rawColumns(['actions', 'custom-plan-type', 'created-on', 'user', 'custom-name', 'custom-request', 'custom-downloadable', 'custom-size', 'custom-type', 'custom-format'])
                    ->make(true);
                    
        }

        return view('admin.archives.results.index');
    }


    /**
     * Display selected result details
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Archive $id)
    {   
        $user = User::find($id->user_id);

        return view('admin.archives.results.show', compact('id', 'user'));
    }


    /**
     * Provide download link if archive is downloadable
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {
        if ($request->ajax()) {

            $archive = Archive::where('id', request('id'))->firstOrFail();  

            if ($archive) {

                if ($archive->downloadable) {
                    $data['status'] = 'success';
                    $data['url'] = $archive->archive_url;
                    return $data;
                } else {
                    $data['status'] = 'error';
                    $data['message'] = 'Selected archive is not ready for downloading yet, make sure you have submitted a request to download this archive first';
                    return $data;
                }
                        
            } else {
                $data['status'] = 'error';
                $data['message'] = 'Selected archive file was not found, please open a support request';
                return $data;
            }
        }              
    }   


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {

            $result = Archive::where('id', request('id'))->firstOrFail();  

            $result->delete();

            return response()->json('success');    
        }     
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
