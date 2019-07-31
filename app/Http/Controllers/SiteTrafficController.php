<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SiteVisitHelper;
use Illuminate\Support\Facades\Input;

class SiteTrafficController extends Controller
{
    public function index()
    {
        return view('traffic.index');
    }

    public function get_weekdays_traffic()
    {
        $specific_date=Input::get('sd');
        if(!empty($specific_date))
            $specific_date=new \Carbon\Carbon($specific_date);

        return json_encode(SiteVisitHelper::weekdays($specific_date));
    }
    public function get_days_of_month()
    {
        $specific_date=Input::get('sd');
        if(!empty($specific_date))
            $specific_date=new \Carbon\Carbon($specific_date);


        return json_encode(SiteVisitHelper::days_of_month($specific_date));
    }


    public function total_traffics()
    {
        $specific_date=Input::get('sd');
        if(!empty($specific_date))
            $specific_date=new \Carbon\Carbon($specific_date);

        return json_encode([
            'total'=>SiteVisitHelper::total_visit(),
            'total_weekdays'=>SiteVisitHelper::weekdays_total($specific_date),
            'total_days_month'=>SiteVisitHelper::days_of_month_total($specific_date),
        ]);
    }

    public function reset()
    {
        SiteVisit::delete();

        return response()->json(['message'=>'Site traffic has been successfully cleared!']);
    }
}
