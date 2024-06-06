<?php

namespace App\Services\Statistics;

use DB;

class CostsService 
{
    private $year;
    private $month;

    public function __construct(int $year = null, int $month = null) 
    {
        $this->year = $year;
        $this->month = $month;
    }


    public function getSpending()
    {
        $spending = DB::table('archives')
                ->whereYear('created_at', $this->year)
                ->select(DB::raw('sum((size/1048576) * 0.00005) as data'), DB::raw("MONTH(created_at) month"))
                ->groupBy('month')
                ->orderBy('month')
                ->get(); 
        
        $data = [];

        for($i = 1; $i <= 12; $i++) {
            $data[$i] = 0;
        }

        foreach ($spending as $row) {	
            $month = $row->month;
            $data[$month] = number_format((float)$row->data, 2, '.', '');            	   
        }
        
        return $data;
    }


    public function getTotalCostCurrentYear()
    {   
        $data = DB::table('archives')
                    ->whereYear('created_at', $this->year)
                    ->select(DB::raw('sum((size/1048576) * 0.00005) as data'))
                    ->get();  

        $cost = get_object_vars($data[0]);
             
        return $cost['data'];

       
    }


    public function getTotalCostCurrentMonth()
    {   
        $data= DB::table('archives')
                        ->whereMonth('created_at', $this->month)
                        ->select(DB::raw('sum((size/1048576) * 0.00005) as data'))
                        ->get();   
        
        $cost = get_object_vars($data[0]);

        return $cost['data'];
    }


    public function getTotalCostPastMonth()
    {   
        $date = \Carbon\Carbon::now();
        $pastMonth =  $date->subMonth()->format('m');

        $data= DB::table('archives')
                        ->whereMonth('created_at', $pastMonth)
                        ->select(DB::raw('sum((size/1048576) * 0.00005) as data'))
                        ->get();   
        
        $cost = get_object_vars($data[0]);       

        return $cost['data'];
    }
}