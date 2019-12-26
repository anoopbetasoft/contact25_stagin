<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use \Torann\GeoIP\Facades\GeoIP;
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
        //$this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
	    /*Artisan::call('config:cache');
        return view('home');*/
        $friendstuff = Product::with(['userDet', 'product_type','product_sell_options','product_lend_options','product_service_option','product_subs_option','currency'])->get();
        $location = geoip($_SERVER['REMOTE_ADDR']);
        return view('welcome',compact('location'));
    }
    public function home()
    {
        
        $friendstuff = Product::with(['userDet', 'product_type','product_sell_options','product_lend_options','product_service_option','product_subs_option','currency'])->get();
        $location = geoip($_SERVER['REMOTE_ADDR']);
        //dd($location);
        //echo '<pre/>';
        //print_r($location);
        //echo $location['lat'];
        //die;

       return view('home',compact('friendstuff','location'));
    }
    public function fetchproducts(Request $request)
    {
        $request = $request->all();
        $country = $request['country'];
         //dd($request['country']);
        //$request = $request->all();
        $friendstuff = Product::with(['userDet'])
            ->whereHas('userDet', function ($q) use ($country) {
                $q->where('country', '=', $country);
            })
            ->where('p_slug', '!=', '')
            ->orderBy('created_at', 'desc')->get();
        if(count($friendstuff)=='0')
        {
            $friendstuff = Product::with(['userDet'])
                ->where('p_slug', '!=', '')
                ->orderBy('created_at', 'desc')->get();
        }
        $data = array();
        foreach($friendstuff as $key => $products)
        {

            if($products['p_location']!='')
            {
                $location = explode(',',$products['p_location']);
                $lat = $location[0];
                $lng = $location[1];
            }
            else
            {
                $lat = '';
                $lng = '';
            }

                if(empty($products['p_image'])) {
                    $data[$key]['image'] = 'assets/images/logo-balls.png';
                }
                else
                {
                    $p_img_arr = explode(',', $products->p_image);
                    $data[$key]['image'] = 'uploads/products/'.$p_img_arr[0];
                }
                $data[$key]['title'] = $products->p_title;
                if($lat!='')
                {
                    $data[$key]['lat'] = $lat;
                    $data[$key]['lng'] = $lng;
                }
                else
                {
                    $data[$key]['lat'] = $products['userDet']['lat'];
                    $data[$key]['lng'] = $products['userDet']['lng'];
                }
            if ($products['p_type'] == '3') {
                if ($products['p_subs_price'] != '' && $products['p_subs_option'] != '') {
                    if ($products['p_subs_option'] == '1') {
                        $data[$key]['price'] = '<i class="fa fa-refresh"></i>&nbsp;' . $products->currency->symbol . '' . $products['p_subs_price'] . '/Day';
                    }
                    if ($products['p_subs_option'] == '2') {
                        $data[$key]['price'] = '<i class="fa fa-refresh"></i>&nbsp;' . $products->currency->symbol . '' . $products['p_subs_price'] . '/Week';
                    }
                    if ($products['p_subs_option'] == '3') {
                        $data[$key]['price'] = '<i class="fa fa-refresh"></i>&nbsp;' . $products->currency->symbol . '' . $products['p_subs_price'] . '/Month';
                    }
                    if ($products['p_subs_option'] == '4') {
                        $data[$key]['price'] = '<i class="fa fa-refresh"></i>&nbsp;' . $products->currency->symbol . '' . $products['p_subs_price'] . '/Year';
                    }
                    $data[$key]['lendprice'] = '';
                } else {
                    $data[$key]['price'] = '';
                    $data[$key]['lendprice'] = '';
                }

            }
            if ($products['p_type'] != '3') {
                if ($products['p_selling_price'] != '' && $products['p_price_per_optn'] != '') {

                    $data[$key]['price'] = '<i class="fa fa-tag"></i>&nbsp;' . $products->currency->symbol . '' . $products['p_selling_price'];

                    if ($products['p_price_per_optn'] == '1') {
                        $data[$key]['lendprice'] = '<i class="fa fa-refresh m-l-5"></i>&nbsp;' . $products->currency->symbol . '' . $products['p_lend_price'] . '/Day';
                    }
                    if ($products['p_price_per_optn'] == '2') {
                        $data[$key]['lendprice'] = '<i class="fa fa-refresh m-l-5"></i>&nbsp;' . $products->currency->symbol . '' . $products['p_lend_price'] . '/Week';
                    }
                    if ($products['p_price_per_optn'] == '3') {
                        $data[$key]['lendprice'] = '<i class="fa fa-refresh m-l-5"></i>&nbsp;' . $products->currency->symbol . '' . $products['p_lend_price'] . '/Month';
                    }
                    if ($products['p_price_per_optn'] == '4') {
                        $data[$key]['lendprice'] = '<i class="fa fa-refresh m-l-5"></i>&nbsp;' . $products->currency->symbol . '' . $products['p_lend_price'] . '/Year';
                    }

                }
                if ($products['p_selling_price'] != '0' && $products['p_price_per_optn'] == '') {
                    $data[$key]['price'] = '<i class="fa fa-tag"></i>&nbsp;' . $products->currency->symbol . '' . $products['p_selling_price'];
                    $data[$key]['lendprice'] = '';
                } else {
                    $data[$key]['price'] = '';
                    $data[$key]['lendprice'] = '';
                }
            }
                $p_slug =  "buy-".$products->p_slug;
                $country = $request['country'];
                $id = $products->id;
                $country = str_replace(' ','-',$country);
                $encoded = base64_encode($products->id);
                $data[$key]['link'] = url($p_slug.'-'.$country.'/'.$encoded);
            }

        return response()->json($data);
        /*  $data['data'] = 'djflfjdksa';
          return response()->json($data);*/
    }
}
