<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\User;
use App\Role;
use App\P_items_option;
use App\Product;
use App\P_sell_option;
use App\P_service_option;
use App\P_subscription_option;
use App\P_type;
use App\P_room;
use App\Users_opening_hr;
use App\Country;
use App\User_card;
use App\Order;
use App\currencies;
use App\Holiday;
use App\p_boxe;
use App\search_stop_word;
use App\deliverie;
use App\friend;
use App\friend_group;
use App\service_opening_hr;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
	    Artisan::call('config:cache');
        return view('home');
    }
    public function home()
    {
        
        $friendstuff = Product::with(['userDet', 'product_type','product_sell_options','product_lend_options','product_service_option','product_subs_option','currency'])->get();
       return view('home',compact('friendstuff'));
    }
}
