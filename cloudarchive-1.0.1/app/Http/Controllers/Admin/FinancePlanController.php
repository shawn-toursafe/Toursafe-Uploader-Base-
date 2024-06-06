<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\LicenseController;
use Illuminate\Http\Request;
use App\Models\Plan;
use DataTables;

class FinancePlanController extends Controller
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

        if($verify['status']!=true){
            die('Your license is invalid or not activated, please contact support.');
        }

        if ($request->ajax()) {
            $data = Plan::all()->sortByDesc("created_at");          
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>                                            
                                            <a href="'. route("admin.finance.plan.show", $row["id"] ). '"><i class="fa-solid fa-file-invoice-dollar table-action-buttons edit-action-button" title="View Plan"></i></a>
                                            <a href="'. route("admin.finance.plan.edit", $row["id"] ). '"><i class="fa-solid fa-file-pen table-action-buttons view-action-button" title="Update Plan"></i></a>
                                            <a class="deletePlanButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Plan"></i></a>
                                        </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span class="font-weight-bold">'.date_format($row["created_at"], 'd M Y').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-status', function($row){
                        $custom_priority = '<span class="cell-box plan-'.strtolower($row["status"]).'">'.ucfirst($row["status"]).'</span>';
                        return $custom_priority;
                    })
                    ->addColumn('frequency', function($row){
                        $custom_status = '<span class="cell-box payment-'.strtolower($row["payment_frequency"]).'">'.ucfirst($row["payment_frequency"]).'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-cost', function($row){
                        $custom_cost = '<span class="font-weight-bold">'.$row["price"] . ' ' . $row["currency"].'</span>';
                        return $custom_cost;
                    })
                    ->addColumn('custom-storage', function($row){
                        $custom_storage = '<span class="font-weight-bold">'.$this->formatSize($row["storage_total"] * 1000000).'</span>';
                        return $custom_storage;
                    })
                    ->addColumn('custom-name', function($row){
                        $custom_name = '<span class="font-weight-bold">'.$row["plan_name"].'</span>';
                        return $custom_name;
                    })
                    ->addColumn('custom-featured', function($row){
                        $icon = ($row['featured'] == true) ? '<i class="fa-solid fa-circle-check text-success fs-16"></i>' : '<i class="fa-solid fa-circle-xmark fs-16"></i>';
                        $custom_featured = '<span class="font-weight-bold">'.$icon.'</span>';
                        return $custom_featured;
                    })
                    ->rawColumns(['actions', 'custom-status', 'created-on', 'custom-cost', 'frequency', 'custom-storage', 'custom-name', 'custom-featured'])
                    ->make(true);
                    
        }

        return view('admin.finance.plans.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.finance.plans.create');
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
            'plan-status' => 'required',
            'plan-name' => 'required',
            'cost' => 'required|numeric',
            'currency' => 'required',
            'storage' => 'required|integer',
            'duration' => 'required'
        ]);


        $plan = new Plan([
            'paypal_gateway_plan_id' => request('paypal_gateway_plan_id'),
            'stripe_gateway_plan_id' => request('stripe_gateway_plan_id'),
            'paystack_gateway_plan_id' => request('paystack_gateway_plan_id'),
            'razorpay_gateway_plan_id' => request('razorpay_gateway_plan_id'),
            'status' => request('plan-status'),
            'plan_name' => request('plan-name'),
            'price' => request('cost'),
            'currency' => request('currency'),
            'storage_total' => request('storage'),
            'payment_frequency' => request('duration'),
            'primary_heading' => request('primary-heading'),
            'featured' => request('featured'),
            'expedited_request' => request('expedited'),
            'standard_request' => request('standard'),
            'plan_features' => request('features'),
        ]); 
               
        $plan->save();            

        return redirect()->route('admin.finance.plans')->with("success", "New subscription plan has been created successfully");
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $id)
    {
        return view('admin.finance.plans.show', compact('id'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $id)
    {
        return view('admin.finance.plans.edit', compact('id'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Plan $id)
    {
        if ($this->api->api_url != 'https://license.berkine.space/') {
            return redirect()->back();
        }

        request()->validate([
            'plan-status' => 'required',
            'plan-name' => 'required',
            'cost' => 'required|numeric',
            'currency' => 'required',
            'storage' => 'required|integer',
            'duration' => 'required',
        ]);

        $id->update([
            'paypal_gateway_plan_id' => request('paypal_gateway_plan_id'),
            'stripe_gateway_plan_id' => request('stripe_gateway_plan_id'),
            'paystack_gateway_plan_id' => request('paystack_gateway_plan_id'),
            'razorpay_gateway_plan_id' => request('razorpay_gateway_plan_id'),
            'status' => request('plan-status'),
            'plan_name' => request('plan-name'),
            'price' => request('cost'),
            'currency' => request('currency'),
            'storage_total' => request('storage'),
            'payment_frequency' => request('duration'),
            'primary_heading' => request('primary-heading'),
            'featured' => request('featured'),
            'expedited_request' => request('expedited'),
            'standard_request' => request('standard'),
            'plan_features' => request('features'),
        ]); 
           

        return redirect()->route('admin.finance.plans')->with("success", "Selected plan has been updated successfully");
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

            $plan = Plan::find(request('id'));

            if($plan) {

                $plan->delete();

                return response()->json('success');

            } else{
                return response()->json('error');
            } 
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

        return round($size, $precision) .' '. $units[$pow]; 
    }
}
