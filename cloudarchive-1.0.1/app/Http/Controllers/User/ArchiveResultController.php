<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use App\Services\Statistics\StorageUsageService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Archive;
use DataTables;

class ArchiveResultController extends Controller
{
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

        $storage_usage = new StorageUsageService();

        if($verify['status']!=true){
            die('Your license is invalid or not activated, please contact support.');
        }

        if ($request->ajax()) {
            $data = Archive::where('user_id', Auth::user()->id)->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>                                            
                                            <a class="downloadArchiveButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-cloud-arrow-down table-action-buttons edit-action-button" title="Download Archive"></i></a>
                                            <a class="requestArchiveButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-timer table-action-buttons request-action-button" title="Request Archive"></i></a>
                                            <a href="'. route("user.archive.list.show", $row["id"] ). '"><i class="fa-solid fa-cabinet-filing table-action-buttons view-action-button" title="View Archive"></i></a>
                                            <a class="deleteArchiveButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Archive"></i></a>
                                        </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span class="font-weight-bold">'.date_format($row["created_at"], 'd M Y, H:i:s').'</span>';
                        return $created_on;
                    })    
                    ->addColumn('custom-format', function($row){
                        $custom_format = '<span class="font-weight-bold">'.strtoupper($row["file_ext"]).'</span>';
                        return $custom_format;
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
                    ->rawColumns(['actions', 'created-on', 'custom-request', 'custom-downloadable', 'custom-size', 'custom-format', 'custom-type', 'custom-name'])
                    ->make(true);
                    
        }

        $storage = [
            'total' => $storage_usage->getTotalAchives(),
            'downloadable' => $storage_usage->getTotalDownloadableArchives(),
            'requested' => $storage_usage->getTotalRequestedArchives(),
        ];

        return view('user.archives.index', compact('storage'));
    }


    /** 
     * Display all downlodables archives.
     *
     * @return \Illuminate\Http\Response
     */
    public function listDownloadables(Request $request)
    {   
        if ($request->ajax()) {
            $data = Archive::where('user_id', Auth::user()->id)->where('downloadable', true)->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>                                            
                                            <a class="downloadArchiveButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-cloud-arrow-down table-action-buttons edit-action-button" title="Download Archive"></i></a>
                                            <a class="deleteArchiveDownloadButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Archive"></i></a>
                                        </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span class="font-weight-bold">'.date_format($row["created_at"], 'd M Y, H:i:s').'</span>';
                        return $created_on;
                    })    
                    ->addColumn('custom-format', function($row){
                        $custom_format = '<span class="font-weight-bold">'.strtoupper($row["file_ext"]).'</span>';
                        return $custom_format;
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
                    ->addColumn('custom-downloadable', function($row){

                        $download = ($row['downloadable']) ? '<i class="fa-solid fa-circle-check table-info-button green fs-20" title="Archive File is Ready to Download"></i>' : '<i class="fa-solid fa-circle-xmark red table-info-button fs-20"></i>';
                        return $download;
                    })
                    ->addColumn('custom-size', function($row){
                        $size = '<span class="font-weight-bold">'.$this->formatSize($row["size"]).'</span>';
                        return $size;
                    })
                    ->rawColumns(['actions', 'created-on', 'custom-downloadable', 'custom-size', 'custom-format', 'custom-type', 'custom-name'])
                    ->make(true);
                    
        }

    }


    /** 
     * Display all download requested archives.
     *
     * @return \Illuminate\Http\Response
     */
    public function listRequested(Request $request)
    {   
        if ($request->ajax()) {
            $data = Archive::where('user_id', Auth::user()->id)->where('download_requested', true)->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>                                           
                                            <a class="deleteArchiveRequestButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Archive"></i></a>
                                        </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span class="font-weight-bold">'.date_format($row["created_at"], 'd M Y, H:i:s').'</span>';
                        return $created_on;
                    })    
                    ->addColumn('custom-format', function($row){
                        $custom_format = '<span class="font-weight-bold">'.strtoupper($row["file_ext"]).'</span>';
                        return $custom_format;
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

                        $requested = ($row['download_requested']) ? '<i class="fa-solid fa-timer table-info-button text-info fs-20" title="Request for Download is Processing"></i>' : '<i class="fa-solid fa-ban table-info-button fs-20"></i>';
                        return $requested;
                    })
                    ->addColumn('custom-size', function($row){
                        $size = '<span class="font-weight-bold">'.$this->formatSize($row["size"]).'</span>';
                        return $size;
                    })
                    ->rawColumns(['actions', 'created-on', 'custom-request', 'custom-size', 'custom-format', 'custom-type', 'custom-name'])
                    ->make(true);
                    
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Archive $id)
    {
        if ($id->user_id == Auth::user()->id){

            return view('user.archives.show', compact('id'));     

        } else{
            return redirect()->route('user.archive.list');
        }
      
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

                if ($archive->user_id == Auth::user()->id){

                    if ($archive->downloadable) {
                        $data['status'] = 'success';
                        $data['url'] = $archive->archive_url;
                        return $data;
                    } else {
                        $data['status'] = 'error';
                        $data['message'] = 'Selected archive is not ready for downloading yet, make sure you have submitted a request to download this archive first';
                        return $data;
                    }
                        
        
                } else{
                    $data['status'] = 'error';
                    $data['message'] = 'This archive does not belong to you';
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
     * Format storage space to readable format
     */
    private function formatSize($size, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
        $size = max($size, 0); 
        $pow = floor(($size ? log($size) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
        
        $size /= pow(1024, $pow);

        return round($size, $precision) . $units[$pow]; 
    }
}
