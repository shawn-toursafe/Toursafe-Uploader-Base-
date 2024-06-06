<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use App\Services\Statistics\StorageUsageService;
use App\Services\Statistics\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Aws\S3\S3Client;   
use App\Models\Archive;
use Carbon\Carbon;
use App\Models\Plan;
use DataTables;

class UploadController extends Controller
{
    private $api;
    private $client;
    private $use;

    public function __construct()
    {
        $this->api = new LicenseController();
        $this->use = new UserService();

        $this->client = new S3Client([
            'credentials' => [													
               'key'    => config('services.aws.key'),						
               'secret' => config('services.aws.secret'),			
           ],
           'version' => 'latest',	
           'signature_version' => 'v4',	
           'region'  => config('services.aws.region')
       ]);

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

        if ($request->ajax()) {
            $data = Archive::where('user_id', Auth::user()->id)->whereDate('created_at', Carbon::today())->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>                                            
                                            <a class="downloadArchiveButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-cloud-arrow-down table-action-buttons edit-action-button" title="Download Archive"></i></a>
                                            <a class="requestArchiveButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-timer table-action-buttons request-action-button" title="Request Archive"></i></a>
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

        return view('user.upload.index');
    }


    /**
	*
	* Initiate MultiPart Uploaded
	* @param - File Metadata
	* @return - MultiPart UploadID and Key(object) Name
	*
	*/
	public function initiateUpload(Request $request) {
        
        $verify = $this->use->verify_license();

        if($verify['status']!=true){
            return false;
        }

        if ($request->ajax()) {

            $storage_usage = new StorageUsageService();
            $output = [];   

            # Check allowed file size before uploading
            if (auth()->user()->group == 'user') {
                if (($request->size / 1000000) > config('settings.upload_limit_user')) {
                    $output['status'] = 'error';
                    $output['message'] = 'File size is larger than what is allowed in your subscription tier';
                    return $output;
                }
            } else {
                if (($request->size / 1000000) > config('settings.upload_limit_subscriber')) {
                    $output['status'] = 'error';
                    return $output;
                }
            }

            # Check if you have enough storage available before uploading
            if ((($storage_usage->getTotalUsage() + $request->size) / 1000000 > (auth()->user()->storage_total + auth()->user()->storage_referral)) ) {
                $output['status'] = 'error';
                $output['message'] = 'Not enough available space in your storage, consider upgrading your subscription plan or deleting old archives';
                return $output;
            }

            $key = strtoupper(Str::random(20));
            $extension = pathinfo($request->name, PATHINFO_EXTENSION);
            $name = $key . '.' . $extension;

            $result = $this->client->createMultipartUpload([
				'Bucket' => config('services.aws.bucket'),				# Bucket Name
				'Key' => $name,						                    # S3 Object Name
				'StorageClass' => $request->storage,  					# S3 Storage Type - Can be one of the follwoing: GLACIER|DEEP_ARCHIVE
				'ACL' => 'public-read', 								# S3 Object Access Control List - Can be one of the following: private|public-read|public-read-write|authenticated-read|aws-exec-read|bucket-owner-read|bucket-owner-full-control
				'ContentDisposition' => 'attachment',					# Allows you to download the file without opening it in the browser
        		'ContentType' => "$request->type"				        # File format
		    ]);

            $output['upload_id'] = $result->get('UploadId');
            $output['key'] = $result->get('Key');
            $output['original_key'] = $request->name;
            $output['size'] = $request->size;
            $output['archive_type'] = $request->storage;
            $output['status'] = 'success';

            return $output;
        }
	}


    /**
	*
	* Create UploadPart Link for each file chunk
	* @param - User inputData and contentLength, partNumber
	* @return - partNumber and it's associated url
	*
	*/
	public function createParts(Request $request) {

        if ($request->ajax()) {
            $result = $this->client->getCommand('UploadPart', array(		
				'Bucket' => config('services.aws.bucket'),				            # Bucket Name
        		'Key' => "$request->key",									        # File Name in S3
	            'UploadId' => "$request->upload_id",						        # Multipart Upload ID
	            'PartNumber' => $request->part_number,								# Part Number of the chunk
	            'ContentLength' => $request->content_length							# Size of the chunk that will be uploaded
            ));

            #Give it at least 24 hours for large chunk uploads
            $response = $this->client->createPresignedRequest($result,"+24 hours");

            $output = [];
            $output['partnumber'] = $request->part_number;
            $output['url'] = (string)$response->getUri();

            return $output;
        }
	}


    /**
	*
	* Complete Multipart Upload (list and combine parts)
	* @param - User inputData and contentLength, partNumber
	* @return - completion status code
	*
	*/
	public function completeUpload(Request $request) {

        if ($request->ajax()) {
            $listParts = $this->client->listParts([
                'Bucket' => config('services.aws.bucket'),
                'Key' => "$request->key",
                'UploadId' => "$request->upload_id"
            ]);

            $result = $this->client->completeMultipartUpload([
                        'Bucket' => config('services.aws.bucket'),
                        'Key' => "$request->key",
                        'UploadId' => "$request->upload_id",
                        'MultipartUpload' => [
                            "Parts"=>$listParts["Parts"],
                ]
            ]);

            $extension = pathinfo($request->key, PATHINFO_EXTENSION);
            $plan = (is_null(auth()->user()->plan_id)) ? 'free' : 'paid';
            $file_type = '';

            switch ($extension) {
                case 'zip':
                case 'rar':
                case '7z':
                case 'iso':
                case 'tar':
                case 'bz2':
                case 'tar.gz':
                    $file_type = 'zip';
                    break;
                case 'docx':
                case 'xlsx':
                case 'pdf':
                case 'txt':
                case 'ppt':
                    $file_type = 'document';
                    break;
                case 'mp3':
                case 'wav':
                case 'mp4':
                case 'avi':
                case 'jpg':
                case 'png':
                case 'jpeg':
                case 'ico':
                case 'gif':
                case 'mpg':
                    $file_type = 'media';
                    break;
                default:
                    $file_type = 'other';
                    break;
            }

            $output = [];
            $output['status'] = $result['@metadata']['statusCode'];

            if ($output['status'] == 200) {
                $archive = new Archive([
                    'user_id' => auth()->user()->id,
                    'file_name' => $request->original_key,
                    'archive_id' => $result["Key"],
                    'archive_url' => $result["ObjectURL"],
                    'file_ext' => $extension,
                    'size' => $request->size,
                    'archive_type' => $request->archive_type,
                    'file_type' => $file_type,
                    'subscription' => $plan,
                    'downloadable' => false,
                    'download_requested' => false,
                ]);

                $archive->save();
            }

            return $output;
        }
	}


    /**
	*
	* In case if cancelled, abort multiupload 
	* @param - User inputData 
	* @return - status code
	*
	*/
	public function cancelUpload(Request $request, $key, $uploadId) {

        if ($request->ajax()) {
            $result = $this->client->abortMultipartUpload([
                'Bucket' => config('services.aws.bucket'),
                'Key' => "$request->key",
                'UploadId' => "$request->upload_id"
           ]);
   
   
           $output = [];
           $output['status'] = $result;
   
           return $output;
        }    
    }



    /**
	*
	* Get File Download Link
	* @param - file id in DB
	* @return - file link
	*
	*/
	public function getFileLink(Request $request) {

        if ($request->ajax()) {

            $archive = Archive::find($request->id)->first();

            if ($archive) {
                $result = $this->client->getObjectUrl(config('services.aws.bucket'), $archive->file_name);

                return $result;
            }
           
        }

	}


    /**
     * Submit archive retrival request
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function retrieveArchive(Request $request)
    {
        if ($request->ajax()) {

            $archive = Archive::where('id', request('id'))->firstOrFail();  

            if ($archive->user_id == Auth::user()->id){

                if ($archive->download_requested) {
                    $data['status'] = 'success';
                    $data['message'] = 'Archive restore is already in progress';
                    return $data;

                } else {
                    if ($archive->archive_type == 'DEEP_ARCHIVE' && request('tier') == 'Expedited') {
                        $data['status'] = 'error';
                        $data['message'] = 'Deep Archive Storage does not support Expedited Retrieval option';
                        return $data;

                    } else {

                        if (config('settings.free_download_request') == 'disable') {         
                            if (auth()->user()->group != 'admin') {
 
                                if (request('tier') == 'Expedited') {
    
                                    if (auth()->user()->group == 'user') {
                                        $data['status'] = 'error';
                                        $data['message'] = 'You are allowed to use only Bulk retrieval tier, please subscribe to use other retrieval options';
                                        return $data;
                                    } else {
                                        $plan = Plan::where('id', auth()->user()->plan_id)->first();
                                        if (!$plan->expedited_request) {
                                            $data['status'] = 'error';
                                            $data['message'] = 'Your subscription plan does not allow Expedited retrieval tier requests, please use other retrieval options';
                                            return $data;
                                        }
                                    }
        
                                } elseif (request('tier') == 'Standard') {
                                    
                                    if (auth()->user()->group == 'user') {
                                        $data['status'] = 'error';
                                        $data['message'] = 'You are allowed to use only Bulk retrieval tier, please subscribe to use other retrieval options';
                                        return $data;
                                    } else {
                                        $plan = Plan::where('id', auth()->user()->plan_id)->first();
                                        if (!$plan->standard_request) {
                                            $data['status'] = 'error';
                                            $data['message'] = 'Your subscription plan does not allow Standard retrieval tier requests, please use other retrieval options';
                                            return $data;
                                        }
                                    }
                                }
                            }               
                        }

                        $result = $this->client->restoreObject([
                            'Bucket' => config('services.aws.bucket'),
                            'Key' => $archive->archive_id,
                            'RestoreRequest' => [
                                'Days' => (integer)config('settings.download_days'),
                                'GlacierJobParameters' => [
                                    'Tier' => request('tier'),
                                ],
                             
                            ],
                        ]);

                        if ($result["@metadata"]["statusCode"] == 202) {
                            $data['status'] = 'success';
                            $data['message'] = 'Archive restore request has been successfully submitted';

                            $archive->update(['download_requested' => true]);

                            return $data;
                        } else if ($result["@metadata"]["statusCode"] == 200){
                            $data['status'] = 'success';
                            $data['message'] = 'Selected archive file is already available for download';
                            return $data;
                        } else {
                            $data['status'] = 'error';
                            $data['message'] = 'There was an error while initiating archive restore process, please try again';
                            return $data;
                        }
                    }
                }   
    
            } else{
                $data['status'] = 'error';
                $data['message'] = 'Selected Archived File does not belong to you';
                return $data;
            } 
        }              
    }


    /**
     * Submit archive retrival request
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function retrieveArchiveAdmin(Request $request)
    {
        if ($request->ajax()) {

            $archive = Archive::where('id', request('id'))->firstOrFail();  

            if ($archive->download_requested) {
                $data['status'] = 'success';
                $data['message'] = 'Archive restore is already in progress';
                return $data;

            } else {

                if ($archive->archive_type == 'DEEP_ARCHIVE' && request('tier') == 'Expedited') {
                    $data['status'] = 'error';
                    $data['message'] = 'Deep Archive Storage does not support Expedited Retrieval option';
                    return $data;

                } else {

                    $result = $this->client->restoreObject([
                        'Bucket' => config('services.aws.bucket'),
                        'Key' => $archive->archive_id,
                        'RestoreRequest' => [
                            'Days' => (integer)config('settings.download_days'),
                            'GlacierJobParameters' => [
                                'Tier' => request('tier'),
                            ],
                            
                        ],
                    ]);

                    if ($result["@metadata"]["statusCode"] == 202) {
                        $data['status'] = 'success';
                        $data['message'] = 'Archive restore request has been successfully submitted';

                        $archive->update(['download_requested' => true]);

                        return $data;
                    } else if ($result["@metadata"]["statusCode"] == 200){
                        $data['status'] = 'success';
                        $data['message'] = 'Selected archive file is already available for download';
                        return $data;
                    } else {
                        $data['status'] = 'error';
                        $data['message'] = 'There was an error while initiating archive restore process, please try again';
                        return $data;
                    }
                }
            }   
        }              
    }


    /**
     * Check if archive is ready to download
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkDownloadable($id)
    {
        $archive = Archive::where('id', $id)->first();

        $result = $this->client->headObject([
            'Bucket' => config('services.aws.bucket'),
            'Key' => $archive->archive_id,
        ]);
        

        if ($result["@metadata"]["statusCode"] == 200) {
            $response = explode(',', $result["Restore"]);
            $request = explode('=', $response[0]);
            $ongoing_request = str_replace('"', '', $request[1]);
            $data = [];

            if ($ongoing_request === 'true') {               
                $data['ongoing'] = true;
                return $data;

            } else {
                $clean_time = str_replace('"', '', $response[2]);
                $time = strtotime($clean_time);
                $expiry_date = date("Y-m-d 00:00:00", $time);

                $data['ongoing'] = false;
                $data['expiry_date'] = $expiry_date;
                return $data;
            }
        }
    }

    /**
	*
	* Delete File
	* @param - file id in DB
	* @return - confirmation
	*
	*/
	public function delete(Request $request) {

        if ($request->ajax()) {

            $archive = Archive::where('id', request('id'))->first(); 

            if ($archive->user_id == Auth::user()->id){

                $this->client->deleteObject([
					'Bucket' => config('services.aws.bucket'), 
					'Key' => $archive->archive_id
				]);

                $archive->delete();

                return response()->json('success');    
    
            } else{
                return response()->json('error');
            }            
        }
	}


    /**
     * Initial settings for file uploader
     *
     * @param  $request
     * @return \Illuminate\Http\Response
     */
    public function settings(Request $request)
    {
        if ($request->ajax()) {

            $data['file_size'] = (auth()->user()->group == 'user') ? config('settings.upload_limit_user') : config('settings.upload_limit_subscriber');
            $data['file_quantity'] = (auth()->user()->group == 'user') ? config('settings.upload_quantity_user') : config('settings.upload_quantity_subscriber');
            $data['part_size'] = config('settings.multipart_chunk_size') * 1024 * 1024;

            return $data;
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
