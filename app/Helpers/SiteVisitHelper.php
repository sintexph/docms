<?php

namespace App\Helpers;

use App\SiteVisit;
use DB;

class SiteVisitHelper 
{
    public static function save_visit()
    {
        if(config('app.env')=="production")
        {
            $ip_address=\Request::getClientIp();
            
            $cache_visit=\Cache::get('v-user-'.$ip_address);

            $site_visit=SiteVisit::whereDate('created_at',\Carbon\Carbon::now())->first();
            
            if($site_visit==null)
            {
                $site_visit=new SiteVisit;
            }

            if($cache_visit==null)
            {
                # Add cache visit for 24 hours
                \Cache::put('v-user-'.$ip_address, 'visited', \Carbon\Carbon::now()->setTime(0,0,0)->addDays(1));
                $site_visit->count+=1;
                $site_visit->save();
            }
            
            return $site_visit->count;
        }

    }


    public static function statistic_source($startDate,$endDate)
    {
        $visits=SiteVisit::whereBetween('created_at',[$startDate,$endDate])->orderBy('created_at','asc');

        return $visits;
    }

    /**
     * GET THE TRAFFIC OF THE CURRENT WEEK BY DAY
     */
    public static function weekdays($specific_date=null)
    {
        
        if($specific_date==null)
            $specific_date=\Carbon\Carbon::now(); 

        $startDate=clone $specific_date;
        $endDate=clone $specific_date;
      
        $startDate->startOfWeek();
        $endDate->endOfWeek();

        $visits=static::statistic_source($startDate,$endDate)->get();

        $visited_array=$visits->map(function($visit){
            return [
                'day'=>$visit->created_at->format('l'),
                'count'=>$visit->count,
            ];
        });


        # Change the start date of filling in based on the data on the database
        if(count($visits)!=0)
            $startDate=$visits[count($visits)-1]->created_at->addDays(1);

        # Fill the remaining days that was not covered by the database
        $period = new \DatePeriod(
            $startDate,
            new \DateInterval('P1D'),
            $endDate
        );
        foreach ($period as $key => $value) {
            $visited_array[]=[
                'day'=>$value->format('l'),
                'count'=>0,
            ];
        }

        return $visited_array;
    }
    /**
     * GET THE TRAFFIC OF THE CURRENT WEEK BY DAY
     */
    public static function days_of_month($specific_date=null)
    {
        if($specific_date==null)
            $specific_date=\Carbon\Carbon::now(); 

        $startDate=clone $specific_date;
        $endDate=clone $specific_date;
      
        $startDate->startOfMonth();
        $endDate->endOfMonth(); 

        $visits=static::statistic_source($startDate,$endDate)->get();

        $visits=$visits->map(function($visit){
            return [
                'day'=>$visit->created_at->toFormattedDateString(),
                'count'=>$visit->count,
            ];
        });

        return $visits;
    }


    public static function weekdays_total($specific_date=null)
    {
        if($specific_date==null)
            $specific_date=\Carbon\Carbon::now();  
            
        $startDate=clone $specific_date;
        $endDate=clone $specific_date;
      
        $startDate->startOfWeek();
        $endDate->endOfWeek(); 

        $visit=static::statistic_source($startDate,$endDate)
        ->select([DB::raw("sum(count) as total")])
        ->first();

        return number_format($visit->total,0);
    }

    public static function days_of_month_total($specific_date=null)
    {
        if($specific_date==null)
            $specific_date=\Carbon\Carbon::now(); 

        $startDate=clone $specific_date;
        $endDate=clone $specific_date;
      
        $startDate->startOfMonth();
        $endDate->endOfMonth(); 

        $visit=static::statistic_source($startDate,$endDate)
        ->select([DB::raw("sum(count) as total")])
        ->first();
        
        return number_format($visit->total,0);
    }

    public static function total_visit()
    {
        $visit=SiteVisit::select([DB::raw("sum(count) as total")])->first();
        return number_format($visit->total,0);
    }
}
