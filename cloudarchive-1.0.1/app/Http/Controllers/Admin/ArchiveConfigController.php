<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use DB;


class ArchiveConfigController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = new LicenseController();
    }

    /**
     * Display TTS configuration settings
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $verify = $this->api->verify_license();

        if($verify['status']!=true){
            die('Your license is invalid or not activated, please contact support.');
        }

        return view('admin.archives.configuration.index');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if ($this->api->api_url != 'https://license.berkine.space/') {
            return redirect()->back();
        }

        request()->validate([
            'default-storage-size' => 'required|integer|min:0',
            'maximum-upload-limit-subscriber' => 'required|integer|min:1',
            'maximum-upload-limit-user' => 'required|integer|min:1',
            'maximum-upload-quantity-subscriber' => 'required|integer|min:1',
            'maximum-upload-quantity-user' => 'required|integer|min:1',
            'multipart-chunk-size' => 'required|integer|min:5',
            'download-days' => 'required|integer|min:1',
            'storage-type' => 'required',

            'set-aws-access-key' => 'required',
            'set-aws-secret-access-key' => 'required',
            'set-aws-region' => 'required',
            'set-aws-bucket' => 'required',
        ]);    

        $this->storeConfiguration('ARCHIVE_SETTINGS_DEFAULT_STORAGE_SIZE', request('default-storage-size'));
        $this->storeConfiguration('ARCHIVE_SETTINGS_UPLOAD_LIMIT_SUBSCRIBER', request('maximum-upload-limit-subscriber'));
        $this->storeConfiguration('ARCHIVE_SETTINGS_UPLOAD_LIMIT_USER', request('maximum-upload-limit-user'));
        $this->storeConfiguration('ARCHIVE_SETTINGS_UPLOAD_QUANTITY_SUBSCRIBER', request('maximum-upload-quantity-subscriber'));
        $this->storeConfiguration('ARCHIVE_SETTINGS_UPLOAD_QUANTITY_USER', request('maximum-upload-quantity-user'));
        $this->storeConfiguration('ARCHIVE_SETTINGS_MULTIPART_CHUNK_SIZE', request('multipart-chunk-size'));
        $this->storeConfiguration('ARCHIVE_SETTINGS_DOWNLOAD_DAYS', request('download-days'));
        $this->storeConfiguration('ARCHIVE_SETTINGS_STORAGE_TYPE', request('storage-type'));

        $this->storeConfiguration('AWS_ACCESS_KEY_ID', request('set-aws-access-key'));
        $this->storeConfiguration('AWS_SECRET_ACCESS_KEY', request('set-aws-secret-access-key'));
        $this->storeConfiguration('AWS_DEFAULT_REGION', request('set-aws-region'));
        $this->storeConfiguration('AWS_BUCKET', request('set-aws-bucket')); 

        return redirect()->back()->with('success', 'Settings were successfully updated');       
    }


    /**
     * Record in .env file
     */
    private function storeConfiguration($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {

            file_put_contents($path, str_replace(
                $key . '=' . env($key), $key . '=' . $value, file_get_contents($path)
            ));

        }
    }
}
