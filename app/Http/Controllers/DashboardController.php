<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use Input;
use Redirect;
use Response;
use DB;
use View;
use Guard;
use Braintree_Transaction;
use Braintree_Customer;
use Braintree_ClientToken;
use Config;
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
use App\cancel_orders_reason;
use App\system_setting;
use App\return_setting;
use App\Credit_detail;
use App\Credit_history;
use App\refund_history;
use App\return_history;
use App\orders_log;
use App\paid_detail;
use Carbon;
use Session;
use App\Traits\TimezoneTrait;
use Ixudra\Curl\Facades\Curl;
use DateTime;
use JFuentesTgn\OcrSpace\OcrAPI;


class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    use TimezoneTrait;

    //verifytimezone(Auth::user()->timezone);
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'checkLoginRole']);
        $this->middleware(function ($request, $next) {

            // fetch your session here

            $this->verifytimezone(Auth::user()->timezone);
            return $next($request);

        });
        /*$this->verifytimezone(Auth::user()->timezone);*/
        #get users' detail
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /*if(date('I',time()))
		{
			echo 'We are in DST!';
			die;
		}
		else
		{
			echo 'We are not in DST!';
			die;
		}*/
        $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
        $avataricon = User::where('id', Auth::id())->get();
        /*$date = date('Y-m-d h:s:i');
		echo date('Y-m-d h:s:i', strtotime($date, '+ 30 day'));
		echo $date;*/
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        //$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        $sellerid = Auth::id();
        $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
        $noofsale = count($salecount);
        $product = Product::get();
        $friendstuff = Product::with(['userDet', 'product_type', 'product_sell_options', 'product_lend_options', 'product_service_option', 'product_subs_option', 'currency', 'friend'])
            ->whereHas('userDet', function ($q) {
                $q->where('country', '=', Auth::user()->country);
            })->whereHas('friend', function ($q) {
                $q->where('friend_id_2', Auth::user()->id)->where('status', '1');
            })->OrwhereHas('friend2', function ($q) {
                $q->where('friend_id_1', Auth::user()->id)->where('status', '1');
            })
            ->where('user_id', '!=', Auth::user()->id)
            ->where('p_slug', '!=', '')
            ->orderBy('created_at', 'desc')->get();        // ## Friend Products
        $countryproducts = Product::with(['userDet', 'product_type', 'product_sell_options', 'product_lend_options', 'product_service_option', 'product_subs_option', 'currency'])
            ->whereHas('userDet', function ($q) {
                $q->where('country', '=', Auth::user()->country);
            })->where('user_id', '!=', Auth::user()->id)->get();        // ## Country Products
        $friendrequest = friend::where('friend_id_2', Auth::user()->id)->where('status', '0')->with(['user'])->get();
        $friendcount = friend::where('friend_id_1', Auth::user()->id)->Orwhere('friend_id_2', Auth::user()->id)->where('status', '1')->get();
        return view('users.dashboard', compact("avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "friendstuff", "countryproducts", "friendrequest", "friendcount", "decimal_place"));
    }

    # QR CODE SCANNER
    public function qrcode(Request $request)
    {
        /*$friend_id_1 = $request->friend_id_1;
        $friend_id_2 = $request->friend_id_2;

        friend::create(['friend_id_1' => $friend_id_1, 'friend_id_2' => $friend_id_2, 'status' => '1']);*/
        dd('here');

    }

    /*
	Product Listing
	*/
    public function product_list()
    {
        $user_id = Auth::id();
        $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
        $country = Auth::user()->country;
        $product_list = Product::where('user_id', $user_id)->orderBy('created_at', 'desc')->paginate(15);
        $avataricon = User::where('id', Auth::id())->get();
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        //$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        $sellerid = Auth::id();
        $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
        $noofsale = count($salecount);
        //echo"<pre>";print_r($product_list);die;
        return view('users.product.product_list', compact("product_list", "user_id", "avataricon", "daysale", "currency_symbol", "country", "salecount", "noofsale", "decimal_place"));
    }

    public function fetchlocationproduct()
    {
        if(Auth::user()->lat=='')
        {
            $data['error'] = '1';
            $data['message'] = 'Please set location in My Profile to check products near you on the map';
        }
        else {
            $data['error'] = '0';
            $friendstuff = Product::with(['userDet', 'currency'])
                ->whereHas('userDet', function ($q) {
                    $q->where('country', '=', Auth::user()->country);
                })
                ->where('user_id', '!=', Auth::user()->id)
                ->where('p_slug', '!=', '')
                ->where('code', Auth::user()->currency_code)
                ->orderBy('created_at', 'desc')->get();
            $data = array();
            if (count($friendstuff) > '0') {
                foreach ($friendstuff as $key => $products) {

                    if ($products['p_location'] != '') {
                        $location = explode(',', $products['p_location']);
                        $lat = $location[0];
                        $lng = $location[1];
                    } else {
                        $lat = '';
                        $lng = '';
                    }
                    $earthRadius = 3959;
                    if ($lat != '') {
                        $latFrom = deg2rad($lat);
                        $lonFrom = deg2rad($lng);
                    } else {
                        $latFrom = deg2rad($products['userDet']['lat']);
                        $lonFrom = deg2rad($products['userDet']['lng']);
                    }

                    $latTo = deg2rad(Auth::user()->lat);
                    $lonTo = deg2rad(Auth::user()->lng);

                    $latDelta = $latTo - $latFrom;
                    $lonDelta = $lonTo - $lonFrom;

                    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                    $radius = $angle * $earthRadius;
                    if ($radius < 5) {
                        if (empty($products['p_image'])) {
                            $data[$key]['image'] = 'assets/images/logo-balls.png';
                        } else {
                            $p_img_arr = explode(',', $products['p_image']);
                            $data[$key]['image'] = 'uploads/products/' . $p_img_arr[0];
                        }
                        $data[$key]['title'] = $products->p_title;
                        if ($lat != '') {
                            $data[$key]['lat'] = $lat;
                            $data[$key]['lng'] = $lng;
                            $data[$key]['status'] = '0'; // Means use service latitude & longitude
                            $data[$key]['user_id'] = $products['user_id'];
                        } else {
                            $data[$key]['lat'] = $products['userDet']['lat'];
                            $data[$key]['lng'] = $products['userDet']['lng'];
                            $data[$key]['status'] = '1';  // Means use users latitude & longitude
                            $data[$key]['user_id'] = $products['user_id'];
                        }
                        $p_slug = "buy-" . $products->p_slug;
                        $country = Auth::user()->country;
                        $id = $products->id;
                        $country = str_replace(' ', '-', $country);
                        $encoded = base64_encode($products->id);
                        $data[$key]['link'] = url($p_slug . '-' . $country . '/' . $encoded);
                        $data[$key]['shop_link'] = url('shop/' . base64_encode($products['user_id']));
                        $data[$key]['p_type'] = $products['p_type'];
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
                    }
                }
                if (count($data) < 20) {
                    foreach ($friendstuff as $key => $products) {
                        if ($products['p_location'] != '') {
                            $location = explode(',', $products['p_location']);
                            $lat = $location[0];
                            $lng = $location[1];
                        } else {
                            $lat = '';
                            $lng = '';
                        }
                        $earthRadius = 3959;

                        $latTo = deg2rad(Auth::user()->lat);
                        $lonTo = deg2rad(Auth::user()->lng);

                        $latDelta = $latTo - $latFrom;
                        $lonDelta = $lonTo - $lonFrom;

                        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
                        $radius = $angle * $earthRadius;
                        if ($radius < 20) {
                            if (empty($products['p_image'])) {
                                $data[$key]['image'] = 'assets/images/logo-balls.png';
                            } else {
                                $p_img_arr = explode(',', $products['p_image']);
                                $data[$key]['image'] = 'uploads/products/' . $p_img_arr[0];
                            }
                            if ($lat != '') {
                                $data[$key]['lat'] = $lat;
                                $data[$key]['lng'] = $lng;
                                $data[$key]['status'] = '0'; // Means use service latitude & longitude
                                $data[$key]['user_id'] = $products['user_id'];
                            } else {
                                $data[$key]['lat'] = $products['userDet']['lat'];
                                $data[$key]['lng'] = $products['userDet']['lng'];
                                $data[$key]['status'] = '1';  // Means use users latitude & longitude
                                $data[$key]['user_id'] = $products['user_id'];
                            }
                            $data[$key]['title'] = $products->p_title;
                            if ($lat != '') {
                                $data[$key]['lat'] = $lat;
                                $data[$key]['lng'] = $lng;
                                $data[$key]['status'] = '0'; // Means use service latitude & longitude
                                $data[$key]['user_id'] = $products['user_id'];
                            } else {
                                $data[$key]['lat'] = $products['userDet']['lat'];
                                $data[$key]['lng'] = $products['userDet']['lng'];
                                $data[$key]['status'] = '1';  // Means use users latitude & longitude
                                $data[$key]['user_id'] = $products['user_id'];
                            }
                            $p_slug = "buy-" . $products->p_slug;
                            $country = Auth::user()->country;
                            $id = $products->id;
                            $country = str_replace(' ', '-', $country);
                            $encoded = base64_encode($products->id);
                            $data[$key]['link'] = url($p_slug . '-' . $country . '/' . $encoded);
                            $data[$key]['shop_link'] = url('shop/' . base64_encode($products['user_id']));
                            $data[$key]['p_type'] = $products['p_type'];
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
                        }
                    }
                }
                if (count($data) < 20) {
                    foreach ($friendstuff as $key => $products) {
                        if ($products['p_location'] != '') {
                            $location = explode(',', $products['p_location']);
                            $lat = $location[0];
                            $lng = $location[1];
                        } else {
                            $lat = '';
                            $lng = '';
                        }

                        if (empty($products['p_image'])) {
                            $data[$key]['image'] = 'assets/images/logo-balls.png';
                        } else {
                            $p_img_arr = explode(',', $products['p_image']);
                            $data[$key]['image'] = 'uploads/products/' . $p_img_arr[0];
                        }
                        $data[$key]['title'] = $products->p_title;
                        if ($lat != '') {
                            $data[$key]['lat'] = $lat;
                            $data[$key]['lng'] = $lng;
                            $data[$key]['status'] = '0'; // Means use service latitude & longitude
                            $data[$key]['user_id'] = $products['user_id'];
                        } else {
                            $data[$key]['lat'] = $products['userDet']['lat'];
                            $data[$key]['lng'] = $products['userDet']['lng'];
                            $data[$key]['status'] = '1';  // Means use users latitude & longitude
                            $data[$key]['user_id'] = $products['user_id'];
                        }
                        $p_slug = "buy-" . $products->p_slug;
                        $country = Auth::user()->country;
                        $id = $products->id;
                        $country = str_replace(' ', '-', $country);
                        $encoded = base64_encode($products->id);
                        $data[$key]['link'] = url($p_slug . '-' . $country . '/' . $encoded);
                        $data[$key]['shop_link'] = url('shop/' . base64_encode($products['user_id']));
                        $data[$key]['p_type'] = $products['p_type'];
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
                    }

                }
            }
        }
        // dd(count($data));
        // dd(count($data));
        //dd($data);
        //dd($data);
        return response()->json($data);
        /*  $data['data'] = 'djflfjdksa';
          return response()->json($data);*/
    }

    /*
	Add product view
	*/

    public function product_add(Request $request)
    {

        #get product type
        $p_types = P_type::get();
        //print_r($p_types);die;
        #get product sell to option
        $p_sell_to = P_sell_option::get();
        #get lend option
        $p_items_option = P_items_option::get();
        #get service options
        $p_service_option = P_service_option::get();
        #get subscription options
        $p_subscription_option = P_subscription_option::get();
        #get room details
        $p_room = P_room::get();
        $avataricon = User::where('id', Auth::id())->get();
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        //$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        $sellerid = Auth::id();
        $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
        $noofsale = count($salecount);
        return view('users.product.product_add', compact("p_types", "p_sell_to", "p_items_option", "p_service_option", "p_subscription_option", "p_service_option", "p_room", "avataricon", "daysale", "currency_symbol", "salecount", "noofsale"));

    }

    /* Product Update */
    public function updateproduct(Request $request)
    {
        $decimal_place = currencies::where('code', $request['code'])->get();
        if ($request['p_selling_price'] != 'not') {
            $request['p_selling_price'] = number_format((float)$request['p_selling_price'], $decimal_place[0]['decimal_places'], '.', '');
            $updatecheck = Product::where('id', $request['id'])->update(['p_selling_price' => $request['p_selling_price'], 'price' => $request['p_selling_price'], 'p_quantity' => $request['p_quantity']]);
            if (!empty($updatecheck)) {
                $response['success'] = '1';
                $response['p_selling_price'] = $request['p_selling_price'];
                return response()->json($response);
            }

        }
        if ($request['p_subs_price'] != 'not') {
            $request['p_subs_price'] = number_format((float)$request['p_subs_price'], $decimal_place[0]['decimal_places'], '.', '');
            $updatecheck = Product::where('id', $request['id'])->update(['p_subs_price' => $request['p_subs_price'], 'price' => $request['p_subs_price'], 'p_quantity' => $request['p_quantity']]);
            if (!empty($updatecheck)) {
                $response['success'] = '1';
                $response['p_subs_price'] = $request['p_subs_price'];
                return response()->json($response);
            }
        }


    }

    /*
	add prodict_temporary
	*/
    public function add_product(Request $request)
    {
        #get product type
        $p_types = P_type::get();
        //echo "<pre>";print_r($p_types[0]->type);die;
        #get product sell to option
        $p_sell_to = P_sell_option::get();
        #get lend option
        $p_items_option = P_items_option::get();
        #get service options
        $p_service_option = P_service_option::get();
        #get subscription options
        $p_subscription_option = P_subscription_option::get();
        #get room details
        $friend_group = friend_group::where('user_id', Auth::user()->id)->get();
        #Get Friend Group Details
        $p_room = P_room::with(['product', 'box'])->where('user_id', Auth::id())->get();
        #Get Delivery Options
        $delivery = deliverie::where('user_id', Auth::user()->id)->where('status', '1')->get();
        $avataricon = User::where('id', Auth::id())->get();
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        //$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        $sellerid = Auth::id();
        $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
        $noofsale = count($salecount);

        if (isset($request->product_id)) {
            $product_id = base64_decode($request->product_id);
            $product = Product::where('id', '=', $product_id)->get();
            return view('users.product.add_product', compact("p_types", "p_sell_to", "p_items_option", "p_service_option", "p_subscription_option", "p_service_option", "p_room", "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "product", "friend_group", "delivery"));
        } else {
            return view('users.product.add_product', compact("p_types", "p_sell_to", "p_items_option", "p_service_option", "p_subscription_option", "p_service_option", "p_room", "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "friend_group", "delivery"));
        }


    }

    /*
	add item
	*/
    public function add_pro_item()
    {
        #get product type
        $p_types = P_type::get();
        //echo "<pre>";print_r($p_types[0]->type);die;
        #get product sell to option
        $p_sell_to = P_sell_option::get();
        #get lend option
        $p_items_option = P_items_option::get();
        #get service options
        $p_service_option = P_service_option::get();
        #get subscription options
        $p_subscription_option = P_subscription_option::get();
        #get room details
        $p_room = P_room::get();
        #Get Friend Group Details
        $delivery = deliverie::where('user_id', Auth::user()->id)->where('status', '1')->get();
        $friend_group = friend_group::where('user_id', Auth::user()->id)->get();
        $avataricon = User::where('id', Auth::id())->get();
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        //$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        $sellerid = Auth::id();
        $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
        $noofsale = count($salecount);

        return view('users.product.add_product.product_item', compact("p_types", "p_sell_to", "p_items_option", "p_service_option", "p_subscription_option", "p_service_option", "p_room", "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "friend_group", "delivery"));
    }

    /*
	add service
	*/
    public function add_pro_service(Request $request)
    {

        #get product type
        $p_types = P_type::get();
        //echo "<pre>";print_r($p_types[0]->type);die;
        #get product sell to option
        $p_sell_to = P_sell_option::get();
        #get lend option
        $p_items_option = P_items_option::get();
        #get service options
        $p_service_option = P_service_option::get();

        #get subscription options
        $p_subscription_option = P_subscription_option::get();
        #get room details
        $p_room = P_room::get();
        #Get Friend Groups
        $delivery = deliverie::where('user_id', Auth::user()->id)->where('status', '1')->get();
        $friend_group = friend_group::where('user_id', Auth::user()->id)->get();
        $avataricon = User::where('id', Auth::id())->get();
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        //$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        $sellerid = Auth::id();
        $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
        $noofsale = count($salecount);
        if (isset($request->product_id)) {
            $product = Product::where('id', '=', $request->product_id)->get();
            return view('users.product.add_product.product_service', compact("p_types", "p_sell_to", "p_items_option", "p_service_option", "p_subscription_option", "p_service_option", "p_room", "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "friend_group", "product", "delivery"));
        } else {
            return view('users.product.add_product.product_service', compact("p_types", "p_sell_to", "p_items_option", "p_service_option", "p_subscription_option", "p_service_option", "p_room", "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "friend_group", "delivery"));
        }

    }

    /*
	add subscription
	*/
    public function add_pro_subs(Request $request)
    {
        #get product type
        $p_types = P_type::get();
        //echo "<pre>";print_r($p_types[0]->type);die;
        #get product sell to option
        $p_sell_to = P_sell_option::get();
        #get lend option
        $p_items_option = P_items_option::get();
        #get service options
        $p_service_option = P_service_option::get();
        #get subscription options
        $p_subscription_option = P_subscription_option::get();
        #get room details
        $p_room = P_room::get();
        #Get Friend Groups
        $delivery = deliverie::where('user_id', Auth::user()->id)->where('status', '1')->get();
        $friend_group = friend_group::where('user_id', Auth::user()->id)->get();
        $avataricon = User::where('id', Auth::id())->get();
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        //$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        $sellerid = Auth::id();
        $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
        $noofsale = count($salecount);
        if (isset($request->product_id)) {
            $product = Product::where('id', '=', $request->product_id)->get();
            return view('users.product.add_product.product_subscription', compact("p_types", "p_sell_to", "p_items_option", "p_service_option", "p_subscription_option", "p_service_option", "p_room", "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "friend_group", "product", "delivery"));
        } else {
            return view('users.product.add_product.product_subscription', compact("p_types", "p_sell_to", "p_items_option", "p_service_option", "p_subscription_option", "p_service_option", "p_room", "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "friend_group", "delivery"));
        }
    }

    /*
	add product process
	*/
    public function product_add_process(Request $request)
    {
        //dd($request->serviceoption);
        //dd($request['pro_img']);
        //dd($request['p_type']);
        //dd($request['p_sell_price']);
        //dd($request['roomname']);
        $user_id = Auth::id();
        if ($request['p_quantity'] == '') {
            $response['success'] = '0';
            $response['message'] = 'Please Enter Quantity';
            return response()->json($response);
        }
        if ($request['delivery_option'] != '' && Auth::user()->delivery_option == '0' && $request['p_type'] == '1') {
            $delivery_option = implode(',', $request['delivery_option']);
        } elseif ($request['delivery_option'] == '' && Auth::user()->delivery_option == '0' && $request['p_type'] == '1' && Auth::user()->inpost_status == '0' && $request['deliveryvalues'] > '0') {
            $response['success'] = '0';
            $response['message'] = 'Please Select Delivery Option';
            return response()->json($response);
        } else {
            $delivery_option = '0';
        }
        if ($request['p_title'] == '') {
            $response['success'] = 0;
            $response['message'] = 'Please Enter Product Title';
            return response()->json($response);
        } else {
            $p_title = $request['p_title'];
        }
        $p_description = $request['p_description'];
        $p_quantity = $request['p_quantity'];
        $currency = Auth::user()->currency_code;


        if (isset($request['p_sell_to']) && !empty($request['p_sell_to'][0])) {
            $p_sell_to = $request['p_sell_to'][0];
        } else {
            $p_sell_to = NULL;
        }

        $p_type = $request['p_type'];
        $p_price_per_optn = NULL; #PENDING

        if (isset($request['p_lend_to']) && !empty($request['p_lend_to'][0])) {
            $p_item_lend_options = $request['p_lend_to'][0];
            //dd($p_item_lend_options);
        } else {
            $p_item_lend_options = NULL;
        }


        if ($p_type == 1) {#item
            $service_time = '';
            $service_time_type = '';
            $p_quality = $request['p_quality'];
            $service_lead_time = '';
            if ($request['p_sell_price'] == '') {
                /*$response['success']=0;
				$response['message']='Please Enter Selling Price';
				return response()->json($response);*/
                $p_selling_price = '0';
            } else {
                $p_selling_price = $request['p_sell_price'];
            }
            if ($request['p_item_lend_price'] == '') {
                /*$response['success']=0;
				$response['message']='Please Enter Lending Price';
				return response()->json($response);*/
                $p_lend_price = '0';
            } else {
                $p_lend_price = $request['p_item_lend_price'];
            }
            $p_subs_option = NULL;
            $p_subs_price = NULL;
            $p_repeat = NULL;
            $p_repeat_per_option = 1;
            $p_repeat_forever = 0;
            $p_time = NULL;
            $p_location = NULL;
            $p_group = NULL;
            $p_radius = NULL;
            $p_radius_option = 0;
            if (isset($request['newroom']) && $request['newroom'] != '') {
                $room_id = P_room::create(['user_id' => Auth::id(),
                    'display_text' => $request['newroom'],
                    'type' => $request['newroom'],
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s')])->id;

                if (isset($request['boxid']) && $request['boxid'] != '') {
                    p_boxe::where('id', $request['boxid'])->update(['status' => '1']);
                    $p_box = $request['boxid'];
                } else {
                    /*$p_box = p_boxe::create(['p_rooms_id'=>$room_id,
										'box_name'=>$request['boxname'],
										'status'=>'1'])->id;*/
                    $p_box = p_boxe::create(['p_rooms_id' => $room_id,
                        'box_name' => 'Box 1',
                        'status' => '1'])->id;
                }

            }
            /*if(isset($request['newroom']) && $request['newroom']=='')
			{
				return redirect()->back()->with('error', 'Please select Room Or Enter Room Name');
			}*/
            if ($request['roomname'] != '' && $request['newroom'] == '') {
                if (isset($request['boxname']) && $request['boxname'] != '') {

                    if (isset($request['boxid']) && $request['boxid'] != '') {
                        p_boxe::where('id', $request['boxid'])->update(['status' => '1']);;
                        $p_box = $request['boxid'];
                    } else {
                        $p_box = p_boxe::create(['p_rooms_id' => $request['roomname'],
                            'box_name' => $request['boxname'],
                            'status' => '1'])->id;
                    }


                } else {
                    /*$response['success'] = 0;
		            $response['message'] = 'Please select Box';
		            return response()->json($response);*/
                    /*$p_box = '0';*/
                    //return redirect()->back()->with('error', 'Please select Box');
                    if (isset($request['boxid']) && $request['boxid'] != '') {
                        p_boxe::where('id', $request['boxid'])->update(['status' => '1']);;
                        $p_box = $request['boxid'];
                    } else {
                        $p_box = null;
                    }
                }
                $room_id = $request['roomname'];


            }
            if ($request['roomname'] == '' && $request['newroom'] == '') {
                /*$response['success'] = 0;
		            $response['message'] = 'Please select room or Enter room';
		            return response()->json($response);*/
                /*$room_id = '0';
		            $p_box = '0';*/
                //return redirect()->back()->with('error', 'Please select room or Enter room name');
                /*$p_box = 'NULL';
		            $room_id = 'NULL';*/
                $p_box = null;
                $room_id = null;
            }
            $p_price_per_optn = $request['p_price_per_optn']; #for lending


        } elseif ($p_type == 2) {#service
            $p_quality = 1;
            if ($request['service_time'] == '') {
                $response['success'] = 0;
                $response['message'] = 'Please Select Service Time';
                return response()->json($response);
            } else {
                $service_time = $request['service_time'];
                if ($request['service_time_type'] == 'hour' && $request['service_time'] == '1') {
                    $service_time_type = 'hr';
                }
                if ($request['service_time_type'] == 'hour' && $request['service_time'] != '1') {
                    $service_time_type = 'hrs';
                }
                if ($request['service_time_type'] == 'min') {
                    $service_time_type = 'min';
                }
                if ($request['service_time_type'] == 'day') {
                    $service_time_type = 'day';
                }


            }
            if ($request['service_lead_time'] != '0') {
                $service_lead_time = $request['service_lead_time'];
            } else {
                $service_lead_time = '';
            }
            if ($request['p_sell_price'] == '') {
                /*$response['success']=0;
				$response['message']='Please Enter Selling Price';
				return response()->json($response);*/
                $p_selling_price = '0';
            } else {
                $p_selling_price = $request['p_sell_price'];
            }
            $p_lend_price = NULL;
            $p_subs_option = NULL;
            $p_subs_price = NULL;
            $p_radius = NULL;
            $p_radius_option = 0;
            $p_location = NULL;
            if (isset($request['p_service_repeat'])) {
                $p_repeat = $request['p_service_repeat'];
            } else {
                $p_repeat = NULL;
            }

            $p_repeat_per_option = $request['p_service_repeat_option'];
            if (isset($request['p_service_forever'])) {
                $p_repeat_forever = $request['p_service_forever'];
            } else {
                $p_repeat_forever = 0;
            }
            //$p_time = date('Y-m-d h:i:s', strtotime($request['p_service_time']));
            $p_time = $request['p_service_time'];
            //$p_location = $request['p_service_location'];
            $p_group = $request['p_service_group_price'];
            if ($request['location_select'] == 'on') {
                if ($request['p_service_radius'] == '') {
                    $response['success'] = 0;
                    $response['message'] = 'Please Enter Radius';
                    return response()->json($response);
                } else {
                    $p_radius = $request['p_service_radius'];
                }
                if ($request['p_service_radius_option'] == '0') {
                    $response['success'] = 0;
                    $response['message'] = 'Please Select Radius Option Km/Miles';
                    return response()->json($response);
                } else {
                    $p_radius_option = $request['p_service_radius_option'];
                }

                if ($request['p_service_location'] == '') {
                    $response['success'] = 0;
                    $response['message'] = 'Please Select Location';
                    return response()->json($response);
                } else {
                    $p_location = $request['p_service_location'];
                }
            }
            if (isset($request['newroom']) && $request['newroom'] != '') {
                $room_id = P_room::create(['user_id' => Auth::id(),
                    'display_text' => $request['newroom'],
                    'type' => $request['newroom'],
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s')])->id;

                if (isset($request['boxid']) && $request['boxid'] != '') {
                    p_boxe::where('id', $request['boxid'])->update(['status' => '1']);
                    $p_box = $request['boxid'];
                } else {
                    /*$p_box = p_boxe::create(['p_rooms_id'=>$room_id,
										'box_name'=>$request['boxname'],
										'status'=>'1'])->id;*/
                    $p_box = p_boxe::create(['p_rooms_id' => $room_id,
                        'box_name' => 'Box 1',
                        'status' => '1'])->id;
                }


            }
            /*if(isset($request['newroom']) && $request['newroom']=='')
			{
				return redirect()->back()->with('error', 'Please select Room Or Enter Room Name');
			}*/
            if ($request['roomname'] != '' && $request['newroom'] == '') {
                if (isset($request['boxname']) && $request['boxname'] != '') {

                    if (isset($request['boxid']) && $request['boxid'] != '') {
                        p_boxe::where('id', $request['boxid'])->update(['status' => '1']);
                        $p_box = $request['boxid'];
                    } else {
                        $p_box = p_boxe::create(['p_rooms_id' => $request['roomname'],
                            'box_name' => $request['boxname'],
                            'status' => '1'])->id;
                    }

                } else {
                    /*$response['success'] = 0;
		            $response['message'] = 'Please select Box';
		            return response()->json($response);*/
                    /*$p_box = '0';*/
                    //return redirect()->back()->with('error', 'Please select Box');
                    if (isset($request['boxid']) && $request['boxid'] != '') {
                        p_boxe::where('id', $request['boxid'])->update(['status' => '1']);;
                        $p_box = $request['boxid'];
                    } else {
                        $p_box = null;
                    }
                }
                $room_id = $request['roomname'];


            }
            if ($request['roomname'] == '' && $request['newroom'] == '') {
                /*$response['success'] = 0;
		            $response['message'] = 'Please select room or Enter room';
		            return response()->json($response);*/
                /*$room_id = '0';
		            $p_box = '0';*/
                //return redirect()->back()->with('error', 'Please select room or Enter room name');
                /*$p_box = 'NULL';
		            $room_id = 'NULL';*/
                $p_box = null;
                $room_id = null;
            }


            $p_box = null;
            $room_id = null;
            $p_price_per_optn = null;

        } else {#subs
            $service_time = '';
            $service_time_type = '';
            $p_quality = 1;
            $p_selling_price = NULL;
            $p_lend_price = NULL;
            $p_radius = NULL;
            $p_location = NULL;
            $p_radius_option = 0;
            $service_lead_time = '';
            $p_subs_option = $request['p_subscription_option'];
            $subscriptionoptions = explode('-', $p_subs_option);   // To convert string 1-2-3.... to array

            if ($request['p_sell_price'] == '') {
                /*$response['success']=0;
					$response['message']='Please Enter Subscription Price';
					return response()->json($response);*/
                $p_subs_price = '0';
            } else {
                $p_subs_price = $request['p_sell_price'];
            }
            $p_repeat = $request['p_subs_repeat'];
            $p_repeat_per_option = $request['p_subs_repeat_option'];
            if ($request['Time'] == 'on') {
                if ($request['p_subs_time'] == '') {
                    $response['success'] = 0;
                    $response['message'] = 'Please Enter Start Time';
                    return response()->json($response);
                }
                if ($request['p_subs_repeat'] == '') {
                    $response['success'] = 0;
                    $response['message'] = 'Please Enter Repeat Option';
                    return response()->json($response);
                }
            }
            if ($request['Group'] == 'on') {
                if ($request['p_subs_group'] == '') {
                    $response['success'] = 0;
                    $response['message'] = 'Please Enter Price For Group Session';
                    return response()->json($response);
                }
            }
            if (isset($request['p_subs_forever'])) {
                if ($request['p_subs_forever'] == 'on') {
                    $p_repeat_forever = '1';
                }
            } else {
                $p_repeat_forever = 0;
            }
            if ($request['Location'] == 'on') {
                /*if($request['p_subs_radius_options']=='0')
                {
                    $response['success'] = 0;
                    $response['message'] = 'Please Select Radius Option Km/Miles';
                    return response()->json($response);
                }
                else
                {
                    $p_radius_option = $request['p_subs_radius_options'];
                }
                if($request['p_subs_radius']=='')
                {
                    $response['success'] = 0;
                    $response['message'] = 'Please Enter Radius';
                    return response()->json($response);
                }
                else
                {
                    $p_radius = $request['p_subs_radius'];
                }*/
                if ($request['p_subs_location'] == '') {
                    $response['success'] = 0;
                    $response['message'] = 'Please Select Location';
                    return response()->json($response);
                } else {
                    $p_location = $request['p_subs_location'];
                }
            }


            $p_time = $request['p_subs_time'];

            $p_group = $request['p_subs_group'];
            //$p_radius = $request['p_subs_radius'];
            //$p_radius_option = $request['p_subs_radius_options'];
            /*$p_box = null;
            $room_id = $room;*/
            $p_price_per_optn = $request['p_sub_per_optn']; #for subscription
            if (isset($request['newroom']) && $request['newroom'] != '') {
                $room_id = P_room::create(['user_id' => Auth::id(),
                    'display_text' => $request['newroom'],
                    'type' => $request['newroom'],
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_at' => date('Y-m-d h:i:s')])->id;

                if (isset($request['boxid']) && $request['boxid'] != '') {
                    p_boxe::where('id', $request['boxid'])->update(['status' => '1']);
                    $p_box = $request['boxid'];
                } else {
                    $p_box = p_boxe::create(['p_rooms_id' => $room_id,
                        'box_name' => 'Box 1',
                        'status' => '1'])->id;
                }


            }
            /*if(isset($request['newroom']) && $request['newroom']=='')
			{
				return redirect()->back()->with('error', 'Please select Room Or Enter Room Name');
			}*/
            if ($request['roomname'] != '' && $request['newroom'] == '') {
                if (isset($request['boxname']) && $request['boxname'] != '') {

                    if (isset($request['boxid']) && $request['boxid'] != '') {
                        p_boxe::where('id', $request['boxid'])->update(['status' => '1']);
                        $p_box = $request['boxid'];
                    } else {
                        $p_box = p_boxe::create(['p_rooms_id' => $request['roomname'],
                            'box_name' => $request['boxname'],
                            'status' => '1'])->id;
                    }

                } else {
                    /*$response['success'] = 0;
		            $response['message'] = 'Please select Box';
		            return response()->json($response);*/
                    /*$p_box = '0';*/
                    //return redirect()->back()->with('error', 'Please select Box');
                    if (isset($request['boxid']) && $request['boxid'] != '') {
                        p_boxe::where('id', $request['boxid'])->update(['status' => '1']);;
                        $p_box = $request['boxid'];
                    } else {
                        $p_box = null;
                    }
                }
                $room_id = $request['roomname'];


            }
            if ($request['roomname'] == '' && $request['newroom'] == '') {
                /*$response['success'] = 0;
		            $response['message'] = 'Please select room or Enter room';
		            return response()->json($response);*/
                /*$room_id = '0';
		            $p_box = '0';*/
                //return redirect()->back()->with('error', 'Please select room or Enter room name');
                /*$p_box = 'NULL';
		            $room_id = 'NULL';*/
                $p_box = null;
                $room_id = null;
            }


        }

        if (isset($request['serviceoption'])) {
            $p_service_option = $request['serviceoption'];
        } else {
            $p_service_option = NULL;
        }


        #images
        $image_array = array();
        $image_names = '';
        /*  dd($request['p_images']->getClientOriginalName());*/
        /*dd($files[0]);*/
        //dd($request['old_images']);
        if (isset($request['p_images'])) {
            //dd('here');
            $imgarr = array();
            if (isset($request['pro_img']) && !empty($request['pro_img'][0])) {
                $sort_arr = explode(',', $request['pro_img'][0]);
                //dd($request['p_images']);

                foreach ($sort_arr as $key => $value) {
                    $imgarr[$key] = $request['p_images'][$key];
                    //dd($request['p_images'][$key]);
                }
            } else {
                $imgarr = $request['p_images'];

            }
            //dd($request['p_images']);

            foreach ($imgarr as $count_img => $img) {
                if ($p_title != '') {
                    $title = str_replace(' ', '-', $p_title);
                    $country = str_replace(' ', '-', Auth::user()->country);
                    $slug = $title . '-' . $country;
                    $imageName = $slug . '-' . time() . '_' . $user_id . '.' . '_' . $count_img . '' . $img->getClientOriginalExtension();
                } else {
                    $imageName = time() . '_' . $user_id . '.' . '_' . $count_img . '' . $img->getClientOriginalExtension();
                }
                $img->move(public_path('uploads/products'), $imageName);
                $image_array[] = $imageName;
            }
            $image_names = implode(',', $image_array);
            //dd($image_names);
            $p_image = $image_names;
            /*$response['message'] = 'Gone to subscription condition';
			$response['success'] = 0;
			return response()->json($response);*/
        }
        if (isset($request['old_images'])) {
            //dd('here');
            if (!isset($request['p_images'])) {
                //$newimagearray = explode(',',$p_image);
                $oldimagearray = $request['old_images'];
                // $newimage = array_merge($newimagearray,$oldimagearray);
                $p_image = implode(',', $oldimagearray);
                //dd($p_image);
            } else {
                $newimagearray = explode(',', $p_image);
                $oldimagearray = $request['old_images'];
                $newimage = array_merge($newimagearray, $oldimagearray);
                $p_image = implode(',', $newimage);
                //dd($p_image);
            }
        }
        if (!isset($request['p_images']) && !isset($request['old_images'])) {
            $p_image = $image_names;
        }

        //echo $p_repeat_forever;
        //die;
        if ($p_subs_price == NULL) {
            $price = $p_selling_price;
        }
        if ($p_selling_price == NULL) {
            $price = $p_subs_price;
        }
        $decimal_place = currencies::where('code', $currency)->get();
        $p_subs_price = number_format((float)$p_subs_price, $decimal_place[0]['decimal_places'], '.', '');
        $p_lend_price = number_format((float)$p_lend_price, $decimal_place[0]['decimal_places'], '.', '');
        $p_selling_price = number_format((float)$p_selling_price, $decimal_place[0]['decimal_places'], '.', '');
        $price = number_format((float)$price, $decimal_place[0]['decimal_places'], '.', '');
        if (isset($request['productid'])) {
            $model = Product::where('id', $request['productid'])->update([
                'user_id' => $user_id,
                'p_title' => $p_title,
                'p_description' => $p_description,
                'p_quantity' => $p_quantity,
                'p_quality' => $p_quality,
                'p_image' => $p_image,
                'p_selling_price' => $p_selling_price,
                'code' => $currency,
                'p_price_per_optn' => $p_price_per_optn,
                'p_type' => $p_type,
                'p_sell_to' => $p_sell_to,
                'p_item_lend_options' => $p_item_lend_options,
                'p_lend_price' => $p_lend_price,
                'p_service_option' => $p_service_option,
                'p_subs_option' => $p_subs_option,
                'p_subs_price' => $p_subs_price,
                'p_repeat' => $p_repeat,
                'p_repeat_forever' => $p_repeat_forever,
                'p_time' => $p_time,
                'p_location' => $p_location,
                'p_group' => $p_group,
                'p_radius' => $p_radius,
                'p_radius_option' => $p_radius_option,
                'p_repeat_per_option' => $p_repeat_per_option,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
                'p_box' => $p_box,
                'room_id' => $room_id,
                'service_lead_time' => $service_lead_time,
                'service_time' => $service_time,
                'service_time_type' => $service_time_type,
                'p_delivery_option' => $delivery_option,
                'price' => $price
            ]);
        } else {
            $model = Product::create([
                'user_id' => $user_id,
                'p_title' => $p_title,
                'p_description' => $p_description,
                'p_quantity' => $p_quantity,
                'p_quality' => $p_quality,
                'p_image' => $p_image,
                'p_selling_price' => $p_selling_price,
                'code' => $currency,
                'p_price_per_optn' => $p_price_per_optn,
                'p_type' => $p_type,
                'p_sell_to' => $p_sell_to,
                'p_item_lend_options' => $p_item_lend_options,
                'p_lend_price' => $p_lend_price,
                'p_service_option' => $p_service_option,
                'p_subs_option' => $p_subs_option,
                'p_subs_price' => $p_subs_price,
                'p_repeat' => $p_repeat,
                'p_repeat_forever' => $p_repeat_forever,
                'p_time' => $p_time,
                'p_location' => $p_location,
                'p_group' => $p_group,
                'p_radius' => $p_radius,
                'p_radius_option' => $p_radius_option,
                'p_repeat_per_option' => $p_repeat_per_option,
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
                'p_box' => $p_box,
                'room_id' => $room_id,
                'service_lead_time' => $service_lead_time,
                'service_time' => $service_time,
                'service_time_type' => $service_time_type,
                'p_delivery_option' => $delivery_option,
                'price' => $price
            ]);
            $model->save();
        }
        /*if($p_type == 2)
		{
			if($request['service_start_sun']!='0' && $request['service_end_sun']!='0')
			{
				service_opening_hr::create(['user_id'=>Auth::user()->id,
											'product_id'=>$model->id,
											'user_day'=>'7',
											'user_day_name'=>'sunday',
											'user_start_time'=>$request['service_start_sun'],
											'user_end_time'=>$request['service_end_sun'],
											'created_at'=>date('Y-m-d h:i:s'),
											'updated_at'=>date('Y-m-d h:i:s')]);
			}
			if($request['service_start_mon']!='0' && $request['service_end_mon']!='0')
			{
				service_opening_hr::create(['user_id'=>Auth::user()->id,
											'product_id'=>$model->id,
											'user_day'=>'1',
											'user_day_name'=>'monday',
											'user_start_time'=>$request['service_start_mon'],
											'user_end_time'=>$request['service_end_mon'],
											'created_at'=>date('Y-m-d h:i:s'),
											'updated_at'=>date('Y-m-d h:i:s')]);
			}
			if($request['service_start_tue']!='0' && $request['service_end_tue']!='0')
			{
				service_opening_hr::create(['user_id'=>Auth::user()->id,
											'product_id'=>$model->id,
											'user_day'=>'2',
											'user_day_name'=>'tuesday',
											'user_start_time'=>$request['service_start_tue'],
											'user_end_time'=>$request['service_end_tue'],
											'created_at'=>date('Y-m-d h:i:s'),
											'updated_at'=>date('Y-m-d h:i:s')]);
			}
			if($request['service_start_wed']!='0' && $request['service_end_wed']!='0')
			{
				service_opening_hr::create(['user_id'=>Auth::user()->id,
											'product_id'=>$model->id,
											'user_day'=>'3',
											'user_day_name'=>'wednesday',
											'user_start_time'=>$request['service_start_wed'],
											'user_end_time'=>$request['service_end_wed'],
											'created_at'=>date('Y-m-d h:i:s'),
											'updated_at'=>date('Y-m-d h:i:s')]);
			}
			if($request['service_start_thu']!='0' && $request['service_end_thu']!='0')
			{
				service_opening_hr::create(['user_id'=>Auth::user()->id,
											'product_id'=>$model->id,
											'user_day'=>'4',
											'user_day_name'=>'thursday',
											'user_start_time'=>$request['service_start_thu'],
											'user_end_time'=>$request['service_end_thu'],
											'created_at'=>date('Y-m-d h:i:s'),
											'updated_at'=>date('Y-m-d h:i:s')]);
			}
			if($request['service_start_fri']!='0' && $request['service_end_fri']!='0')
			{
				service_opening_hr::create(['user_id'=>Auth::user()->id,
											'product_id'=>$model->id,
											'user_day'=>'5',
											'user_day_name'=>'friday',
											'user_start_time'=>$request['service_start_fri'],
											'user_end_time'=>$request['service_end_fri'],
											'created_at'=>date('Y-m-d h:i:s'),
											'updated_at'=>date('Y-m-d h:i:s')]);
			}
			if($request['service_start_sat']!='0' && $request['service_end_sat']!='0')
			{
				service_opening_hr::create(['user_id'=>Auth::user()->id,
											'product_id'=>$model->id,
											'user_day'=>'6',
											'user_day_name'=>'saturday',
											'user_start_time'=>$request['service_start_sat'],
											'user_end_time'=>$request['service_end_sat'],
											'created_at'=>date('Y-m-d h:i:s'),
											'updated_at'=>date('Y-m-d h:i:s')]);
			}
		}*/


        /*$insertVal = DB::table('products')->insert([

		   	'user_id'=> $user_id,
			'p_title'=> $p_title,
			'p_description'=> $p_description,
			'p_quantity'=> $p_quantity,
			'p_quality'=> $p_quality,
			'p_image'=> $p_image,
			'p_selling_price'=> $p_selling_price,
			'p_price_per_optn'=> $p_price_per_optn,
			'p_type'=> $p_type,
			'p_sell_to'=> $p_sell_to,
			'p_item_lend_options'=> $p_item_lend_options,
			'p_lend_price'=> $p_lend_price,
			'p_service_option'=> $p_service_option,
			'p_subs_option'=> $p_subs_option,
			'p_subs_price'=> $p_subs_price,
			'p_repeat'=> $p_repeat,
			'p_repeat_forever'=> $p_repeat_forever,
			'p_time'=> $p_time,
			'p_location'=> $p_location,
			'p_group'=> $p_group,
			'p_radius'=> $p_radius,
			'p_radius_option'=> $p_radius_option,
	        'p_repeat_per_option'=>$p_repeat_per_option,
	        'created_at'=>date('Y-m-d h:i:s'),
	        'updated_at'=>date('Y-m-d h:i:s'),
	        'p_box' => $p_box,
			'room_id' => $room_id,
			'p_slug' =>$slug,
		]);*/

        if (!empty($model)) {
            //return redirect('myproducts')->with('message', 'Product saved successfully');
            //return view('users.product.product_list')->with('message', 'Product saved successfully');
            $response['success'] = 1;
            if ((Auth::user()->sell_to_friend == '1' || Auth::user()->sell_to_neighbour == '1' || Auth::user()->sell_to_uk == '1') && (Auth::user()->lend_to_friend == '1' || Auth::user()->lend_to_neighbour == '1' || Auth::user()->lend_to_uk == '1')) {
                if ($p_selling_price == '0' && $p_lend_price == '0' && $p_type == '1') {
                    Session::flash('message', 'You are selling and lending this item for FREE');
                    Session::flash('type', '1');
                } elseif ($p_selling_price != '0' && $p_lend_price == '0' && $p_type == '1') {
                    Session::flash('message', 'You are lending this item for FREE');
                    Session::flash('type', '1');
                } elseif ($p_selling_price == '0' && $p_lend_price != '0' && $p_type == '1') {
                    Session::flash('message', 'You are selling  this item for FREE');
                    Session::flash('type', '1');
                } elseif ($p_selling_price == '0' && $p_type == '2') {
                    Session::flash('message', 'You are selling this item for FREE');
                } elseif ($p_subs_price == '0' && $p_type == '3') {
                    Session::flash('message', 'You are selling this item for FREE');
                } else {
                    Session::flash('message3', 'Product Saved successfully');
                    $response['message'] = 'Product saved successfully';
                }
            } else {
                Session::flash('message3', 'Product Saved successfully');
                $response['message'] = 'Product saved successfully';
            }
            return response()->json($response);
        } else {
            //return redirect()->back()->with('error', 'Some error occured. Please try again later');
            $response['success'] = 0;
            $response['message'] = 'Error Occured';
            return response()->json($response);

        }
    }


    /*
	Leave impersonate
	*/

    public function impersonate_leave()
    {
        //echo "hi!";die;
        Auth::user()->leaveImpersonation();
        return redirect('/customers');
    }

    /*
	edit profile
	*/

    public function view_profile()
    {
        //$this->verifytimezone(Auth::user()->timezone);
        $userId = Auth::id();
        #get users' detail
        /*$data = Holiday::get();
		print_r($data);
		die;*/
        $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
        $ordercheck = Order::where('seller_id', '=', $userId)->where('o_completed', '=', '0')->get();
        $purchasecheck = Order::where('user_id', '=', $userId)->where('o_completed', '=', '0')->get();
        $creditcheck = Credit_detail::where('user_id',$userId)->where('status','0')->get();
        $walletcheck = User::where('id',$userId)->get();
        $pendingordercount = count($ordercheck);
        $pendingpurchasecount = count($purchasecheck);
        $creditcheckcount = count($creditcheck);
        $uDetails = User::where('id', $userId)->get();
        #get countries
        $country = Country::orderBy('c_name', 'ASC')->get();
        $currencies = currencies::get();
        $avataricon = User::where('id', Auth::id())->get();
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        $holiday = Holiday::where('user_id', $userId)->get();
        $delivery = deliverie::where('user_id', Auth::user()->id)->get();
        /*print_r($holiday);
		die;*/
        //$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        $sellerid = Auth::id();
        $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
        $noofsale = count($salecount);

        return view('users.view_profile', compact("uDetails", "userId", "country", "avataricon", "daysale", "currencies", "currency_symbol", "holiday", "pendingordercount", "salecount", "noofsale", "delivery", "decimal_place", "pendingpurchasecount","creditcheckcount","walletcheck"));
    }

    /*
	Edit profile
	*/
    /* Update Return Option */
    public function update_return_option(Request $request)
    {
        if (isset($request['refundrequest_status'])) {
            User::where('id', Auth::user()->id)->update(['refundrequest_status' => $request['refundrequest_status'], 'refundrequest_value' => $request['refundrequest_value'], 'refundrequestdamage_status' => $request['refundrequestdamage_status'], 'refundrequestdamage_value' => $request['refundrequestdamage_value']]);
            $response['success'] = '1';
            return response()->json($response);
        }

    }

    public function delete_holiday(Request $request)
    {

        $delete_check = DB::table('holidays')->where('id', '=', $request['id'])->delete();
        if ($delete_check != 0) {
            $response['success'] = 1;
            $response['message'] = 'Success';
            return response()->json($response);
        } else {
            $response['success'] = 0;
            $response['message'] = 'Some error occured while deleting the Holiday';
            return response()->json($response);
        }
    }

    public function add_holiday(Request $request)
    {

        $userId = Auth::id();


        $start = $request['start'];
        $end = $request['end'];

        $insertval = Holiday::create([
            'user_id' => $userId,
            'start' => $start,
            'end' => $end,
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s')
        ]);
        if (empty($insertval)) {
            $response['success'] = 0;
            $response['message'] = "There was problem in updating your changes. Please try again later";

        } else {
            $response['success'] = 1;
            $response['message'] = "Details updated successfully";

            $timedata = explode(' - ', $start);
            $timedata2 = explode(' - ', $end);
            $startday = date("D", strtotime($timedata[0]));
            $endday = date("D", strtotime($timedata2[0]));


            $starttime = date_parse_from_format("Y-m-d", $start);
            $startmonth = date('F', mktime(0, 0, 0, $starttime['month'], 10));
            $endtime = date_parse_from_format("Y-m-d", $end);
            $endmonth = date('F', mktime(0, 0, 0, $endtime['month'], 10));

            $startdata = explode('-', $start);
            $enddata = explode('-', $end);


            $starttime = $timedata[1];
            $starttime = date('g:i', strtotime($starttime));
            $endtime = $timedata2[1];
            $endtime = date('g:i', strtotime($endtime));

            $startdate = $startdata[2];
            $startyear = $startdata[0];

            $enddate = $enddata[2];
            $endyear = $enddata[0];
            $response['data'] = '<div id=' . $insertval->id . '>
                        <div class="row m-t-40">
                        
                                    <!-- Column -->
                                    <div class="col-md-5 col-lg-5 col-xlg-5 col-sm-6 col-xs-2" style="width: auto;  min-width: 0;">
                                        <div class="card">
                                           <div class="ribbon-vwrapper" style="padding-right:0px; padding-bottom:0px;">
                                    <div class="ribbon ribbon-bookmark ribbon-vertical-l ribbon-info" style="height: 115px;">
                                        <i class="mdi mdi-airplane-takeoff"></i>
                                            </div>
                                            <div>
                                               <h3 class="text-purple">Start</h3>
                                                                                                        
                                                <p class="text-muted" style="font-size: 13px;">' . $startday . ' ' . $startdate . '-' . $startmonth . '-' . $startyear . '</p>
                                                <b style="font-size: 11px;">(' . $starttime . ')</b> 
                                            </div>
                                            </div>
                                        </div>  
                                    </div>
                                    
                                    <!-- Column -->
                                    
                                    <div class="col-md-5 col-lg-5 col-xlg-5 col-sm-6 col-xs-2" style="width: auto;  min-width: 0;">
                                        <div class="card">
                                            <div class="ribbon-vwrapper" style="padding-right:0px; padding-bottom:0px;">
                                    <div class="ribbon ribbon-bookmark ribbon-vertical-l ribbon-danger" style="height: 115px;">
                                        <i class="mdi mdi-airplane-landing"></i>
                                    </div>
                                                <div>
                                                <h3 class="text-primary">End</h3>
                                                 <p class="text-muted" style="font-size: 13px;">' . $endday . ' ' . $enddate . '-' . $endmonth . '-' . $endyear . '</p>
                                                <b style="font-size: 11px;">(' . $endtime . ')</b> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Column -->
                                    
                                    <div class="col-md-12 col-lg-2 col-xlg-2 col-sm-12 col-xs-12">
                                      <div class="card padding-top:0px;">
                                            <button type="button" class="btn btn-light remove_holiday" style="margin-top:25px;" data-holiday_id="23" onclick="deleteholiday(' . $insertval->id . ');">
                                        <i class="ti-close" aria-hidden="true"></i>
                                        </button>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>';

        }
        return response()->json($response);

    }

    public function edit_profile(Request $request)
    {
        //print_r($request->all());die;
        if($request['country2']!='')
        {
            $request['country'] = $request['country2'];
        }
        $userId = Auth::id();

        $name = $request['name'];
        $email = $request['email'];
        $latlng = str_replace('(', '', $request['latlng']);
        $latlng = str_replace(')', '', $latlng);
        $latlng = explode(',', $latlng);
        $location = $request['location'];
        $timezone = $request['timezone'];
        if ($request['latlng'] == '' || $request['location'] == '') {
            $response['success'] = 0;
            $response['message'] = 'Please set up your location';
            return response()->json($response);
        }
        #validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email,' . $userId,
        ]);

        if ($validator->fails()) {
            $response['success'] = 0;
            $response['error'] = $validator->errors();
            return response()->json($response);
        }


        $update_arr = [];
        #image format
        if (isset($request['avatar'])) {
            $imageName = time() . '_' . $userId . '.' . $request['avatar']->getClientOriginalExtension();
            $request['avatar']->move(public_path('uploads/avatar'), $imageName);
            $avatar = $imageName;
            #update arr
            if ($request['country'] != 'united kingdom' || $request['currency_code'] != 'GBP') {         // If user don't select united kingdom or GBP Currency  then inpost status and inpost return is 0
                $update_arr = [
                    'name' => $name,
                    'email' => $email,
                    'avatar' => $avatar,
                    'avatar_type' => 1,
                    'street_address1' => $request['st_address1'],
                    'street_address2' => $request['st_address2'],
                    'city' => $request['city'],
                    'state' => $request['state'],
                    'country' => $request['country'],
                    'currency_code' => $request['currency_code'],
                    'pincode' => $request['pincode'],
                    'inpost_status' => '0',
                    'inpost_return' => '0',
                    'lat' => $latlng[0],
                    'lng' => $latlng[1],
                    'location' => $location,
                    'timezone' => $timezone
                ];
            } else {
                $update_arr = [
                    'name' => $name,
                    'email' => $email,
                    'avatar' => $avatar,
                    'avatar_type' => 1,
                    'street_address1' => $request['st_address1'],
                    'street_address2' => $request['st_address2'],
                    'city' => $request['city'],
                    'state' => $request['state'],
                    'country' => $request['country'],
                    'currency_code' => $request['currency_code'],
                    'pincode' => $request['pincode'],
                    'lat' => $latlng[0],
                    'lng' => $latlng[1],
                    'location' => $location,
                    'timezone' => $timezone
                ];
            }
        } else {
            #get user's
            if ($request['country'] != 'united kingdom' || $request['currency_code'] != 'GBP') {         // If user don't select united kingdom or GBP Currency  then inpost status and inpost return is 0
                $update_arr = [
                    'name' => $name,
                    'email' => $email,
                    'street_address1' => $request['st_address1'],
                    'street_address2' => $request['st_address2'],
                    'city' => $request['city'],
                    'state' => $request['state'],
                    'country' => $request['country'],
                    'currency_code' => $request['currency_code'],
                    'pincode' => $request['pincode'],
                    'inpost_status' => '0',
                    'inpost_return' => '0',
                    'lat' => $latlng[0],
                    'lng' => $latlng[1],
                    'location' => $location,
                    'timezone' => $timezone
                ];
            } else {

                $update_arr = [
                    'name' => $name,
                    'email' => $email,
                    'street_address1' => $request['st_address1'],
                    'street_address2' => $request['st_address2'],
                    'city' => $request['city'],
                    'state' => $request['state'],
                    'country' => $request['country'],
                    'currency_code' => $request['currency_code'],
                    'pincode' => $request['pincode'],
                    'lat' => $latlng[0],
                    'lng' => $latlng[1],
                    'location' => $location,
                    'timezone' => $timezone
                ];
            }
        }

        $update_val = DB::table('users')->where('id', $userId)->update($update_arr);

        if (empty($update_val)) {
            $response['success'] = 1;
            $response['message'] = "Details updated successfully";

        } else {
            $response['success'] = 1;
            $response['message'] = "Details updated successfully";

        }
        return response()->json($response);
    }

    /*
	save 2 auth value
	*/

    public function two_auth(Request $request)
    {
        //echo "hello"; die;

        $userId = Auth::id();
        $Existingcode = Auth::user()->contact_code;
        $Existingcontact = Auth::user()->contact_no;

        //print_r($request->all());die;

        if (!isset($request['auth_val'])) {
            #some error
            $response['success'] = 0;
            $response['message'] = "Auth Value not found ";
            return response()->json($response);
        }

        #disable 2 auth
        if ($request['auth_val'] == 0) {
            $check_status = User::where('id', $userId)->update(['two_way_auth' => 0]);
            if (!empty($check_status)) {
                #some error
                $response['success'] = 1;
                $response['message'] = "Settings Updated.";
                return response()->json($response);
            }
        }

        #enable 2 auth and verify the mobile no.
        if ($request['auth_val'] == 1) {

            #check the mobile no, if same dont ask for otp
            if (isset($request['contact_no_hidden']) && !empty($request['contact_no_hidden'])) {
                $userno = trim($request['contact_no_hidden']);
            } else {
                $userno = trim($request['contact_mobile']);
            }
            $contact_no = trim($request['dial_code']) . '' . $userno;
            $existing_no = $Existingcode . '' . $Existingcontact;


            if ($contact_no == $existing_no) {

                #same numbers
                $check_status = User::where('id', $userId)->update(['two_way_auth' => 1, 'contact_country' => $request['contact_country']]);

                if (!empty($check_status)) {
                    #some error
                    $response['success'] = 1;
                    $response['message'] = "Settings Updated.";
                    return response()->json($response);
                }

            } else {
                #different no, generate sms
                #send otp to user
                $digits = 4;
                $otp_no = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

                #send
                $user = new User();
                $user->sendText($contact_no, "Contact25", "Dear user, your verification code is : " . $otp_no . " . Use this code update your mobile number.");
                $response['success'] = 2;
                $response['message'] = "Verification code has been sent to your mobile.";
                $response['otpval'] = Hash::make($otp_no) . '_tval_' . date('Y-m-d h:i:s');
                $response['contact_no_hidden'] = $userno;
                //$response['original_otp'] = $otp_no;
                return response()->json($response);
            }
        }
    } #two_auth

    /*
	save 2 auth value
	*/

    public function two_auth_otp(Request $request)
    {


        $userId = Auth::id();
        if (empty($request['otp']) || empty($request['otp_val'])) {
            $response['success'] = 0;
            $response['message'] = "Please enter the verification code";
            return response()->json($response);
        } else {
            #get time of otp sent
            $input = "_tval_";
            $timeOTP_sent = explode('_tval_', $request['otp_val']);
            $hash_otp = $timeOTP_sent[0];
            $otp_time_sent = $timeOTP_sent[1];

            #get current time
            $t = time();
            $current_time = date("Y-m-d h:i:s", $t);

            #get time different
            $ts1 = strtotime($otp_time_sent);
            $ts2 = strtotime($current_time);
            $minutes_diff = ($ts2 - $ts1) / 60;

            if ($minutes_diff > 5) { # 5 minutes limit

                $response['success'] = 3;
                $response['message'] = "Verification code has expired. Please resend the code";
                return response()->json($response);
            }
            if (Hash::check($request['otp'], $hash_otp)) {
                #update user
                $update_arr = [
                    "contact_code" => $request['dial_code'],
                    "contact_no" => $request['contact_no_hidden'],
                    "two_way_auth" => 1,
                    "contact_country" => $request['contact_country'],
                    "contact_verify_status" => 1,
                    "contact_verified_at" => date("Y-m-d h:i:s")
                ];

                $update_status = DB::table('users')->where('id', $userId)->update($update_arr);

                if (!empty($update_status)) {
                    $response['success'] = 1;
                    $response['message'] = "Settings Updated.";
                    return response()->json($response);
                } else {
                    $response['success'] = 0;
                    $response['message'] = "Some error occured.";
                    return response()->json($response);
                }
            } else {
                $response['success'] = 0;
                $response['message'] = "Verification code does not matches";
                return response()->json($response);
            }
        }
    }#two_auth_otp

    /*
	Listing friends products
	*/
    /* product listing according to user */
    public function shop_listing(Request $request)
    {
        $user_id = base64_decode($request->user_id);
        $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
        $country = Auth::user()->country;
        $product_list = Product::where('user_id', $user_id)->orderBy('created_at', 'desc')->paginate(15);
        $avataricon = User::where('id', Auth::id())->get();
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        //$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        $sellerid = Auth::id();
        $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
        $noofsale = count($salecount);
        //echo"<pre>";print_r($product_list);die;
        return view('users.product.shop_product_list', compact("product_list", "user_id", "avataricon", "daysale", "currency_symbol", "country", "salecount", "noofsale", "decimal_place"));

    }

    public function products_friends()
    {

        $userId = Auth::id();
        $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
        $avataricon = User::where('id', Auth::id())->get();
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        try {

            $product_list = Product::with(['userDet', 'product_type', 'product_sell_options', 'product_lend_options', 'product_service_option', 'product_subs_option', 'currency', 'friend'])
                ->whereHas('userDet', function ($q) {
                    $q->where('country', '=', Auth::user()->country)->where('currency_code', '=', Auth::user()->currency_code);
                })->whereHas('friend', function ($q) {
                    $q->where('friend_id_2', Auth::user()->id)->where('status', '1');
                })->OrwhereHas('friend2', function ($q) {
                    $q->where('friend_id_1', Auth::user()->id)->where('status', '1');
                })
                ->where('code', Auth::user()->currency_code)
                ->where('user_id', '!=', $userId)
                ->where('p_slug', '!=', '')
                ->orderBy('created_at', 'desc')
                ->paginate(15);        // ## Friend Products
            $countryproducts = Product::with(['userDet', 'product_type', 'product_sell_options', 'product_lend_options', 'product_service_option', 'product_subs_option', 'currency', 'friend'])
                ->whereHas('userDet', function ($q) {
                    $q->where('country', '=', Auth::user()->country)->where('currency_code', '=', Auth::user()->currency_code);
                })->where('user_id', '!=', $userId)
                ->where('p_slug', '!=', '')
                ->orderBy('created_at', 'desc')
                ->paginate(15);        // ## Country Products
            /*echo '<pre>';
			print_r($product_list);
			echo '</pre>';
			die;*/
            $sellerid = Auth::id();
            $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
            $noofsale = count($salecount);
            return view('users.product.product_friend', compact("product_list", "userId", "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "countryproducts", "decimal_place"));

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    // Search Bar
    public function search(Request $request)
    {
        //$keyword = explode(' ',$request->keyword);
        $keyword = $request->keyword;
        $userId = Auth::id();
        $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
        $avataricon = User::where('id', Auth::id())->get();
        $stopword = search_stop_word::where('word', $keyword)->get();
        $searchkeyword = explode(' ', $keyword);

        if (count($stopword) > '0') {
            $stopwordstatus = 'yes';
        } else {
            $stopwordstatus = 'no';
        }
        $sellercountry = User::where('id', $userId)->get();
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        try {
            $product_list = Product::with(['userDet', 'product_type', 'product_sell_options', 'product_lend_options', 'product_service_option', 'product_subs_option', 'currency'])
                ->whereHas('userDet', function ($q) {
                    $q->where('role_id', '=', 2);
                })->where('user_id', '!=', $userId)
                ->where('p_slug', '!=', '')
                ->where('code', Auth::user()->currency_code)
                ->where(function ($q) use ($searchkeyword) {
                    foreach ($searchkeyword as $key => $searchword) {
                        $q->orwhere('p_title', 'LIKE', '%' . $searchword . '%');
                        $q->orwhere('p_description', 'LIKE', '%' . $searchword . '%');
                    }
                })
                //->where('p_title', 'LIKE', '%' . $keyword . '%');

                // $product_list->orwhere('p_description','LIKE','%' . $keyword . '%')
                ->orderBy('updated_at', 'asc')
                ->paginate(15);
            //dd($product_list);
            $sellerid = Auth::id();
            $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
            $noofsale = count($salecount);
            return view('users.product.search', compact("product_list", "userId", "avataricon", "sellercountry", "daysale", "currency_symbol", "stopwordstatus", "salecount", "noofsale", "keyword", "decimal_place"));

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    #My Stuff Ajax
    public function product_list_ajax(Request $request)
    {
        if ($request['sort_by'] == 'new') {
            $user_id = Auth::id();
            $country = Auth::user()->country;
            $product_list = Product::where('user_id', $user_id)->where('p_quality', '1')->get();
        } elseif ($request['sort_by'] == 'likenew') {
            $user_id = Auth::id();
            $country = Auth::user()->country;
            $product_list = Product::where('user_id', $user_id)->where('p_quality', '2')->get();
        } elseif ($request['sort_by'] == 'good') {
            $user_id = Auth::id();
            $country = Auth::user()->country;
            $product_list = Product::where('user_id', $user_id)->where('p_quality', '3')->get();
        } elseif ($request['sort_by'] == 'ok') {
            $user_id = Auth::id();
            $country = Auth::user()->country;
            $product_list = Product::where('user_id', $user_id)->where('p_quality', '4')->get();
        } elseif ($request['sort_by'] == 'item') {
            $user_id = Auth::id();
            $country = Auth::user()->country;
            $product_list = Product::where('user_id', $user_id)->where('p_type', '1')->get();
        } elseif ($request['sort_by'] == 'service') {
            $user_id = Auth::id();
            $country = Auth::user()->country;
            $product_list = Product::where('user_id', $user_id)->where('p_type', '2')->get();
        } elseif ($request['sort_by'] == 'subscription') {
            $user_id = Auth::id();
            $country = Auth::user()->country;
            $product_list = Product::where('user_id', $user_id)->where('p_type', '3')->get();
        } elseif ($request['sort_by'] == 'pricelowtohigh') {
            $user_id = Auth::id();
            $country = Auth::user()->country;
            $product_list = Product::where('user_id', $user_id)->orderBy('price', 'asc')->get();
        } elseif ($request['sort_by'] == 'pricehightolow') {
            $user_id = Auth::id();
            $country = Auth::user()->country;
            $product_list = Product::where('user_id', $user_id)->orderBy('price', 'desc')->get();
        } elseif ($request['sort_by'] == 'atoz') {
            $user_id = Auth::id();
            $country = Auth::user()->country;
            $product_list = Product::where('user_id', $user_id)->orderBy('p_title', 'asc')->get();
        } elseif ($request['sort_by'] == 'ztoa') {
            $user_id = Auth::id();
            $country = Auth::user()->country;
            $product_list = Product::where('user_id', $user_id)->orderBy('p_title', 'desc')->get();
        } elseif ($request['sort_by'] == 'allroom') {
            $user_id = Auth::id();
            $country = Auth::user()->country;
            $product_list = Product::where('user_id', $user_id)->where('room_id', '!=', '')->get();
        } else {
            $user_id = Auth::id();
            $country = Auth::user()->country;
            $product_list = Product::where('user_id', $user_id)->where('room_id', $request['sort_by'])->get();
        }
        return view('users.product.product_list_ajax', compact("product_list"));
    }

    /* Product Box List */
    public function product_box_list_ajax(Request $request)
    {
        $box = p_boxe::where('p_rooms_id', $request->room_id)->get();
        return view('users.product.product_list.box', compact("box"));
    }

    /* Product according to box */
    public function product_box_ajax(Request $request)
    {
        $user_id = Auth::id();
        $country = Auth::user()->country;
        $product_list = Product::where('user_id', $user_id)->where('p_box', $request['box_id'])->get();
        return view('users.product.product_list_ajax', compact("product_list"));
    }

    /* Product according to all box */
    public function product_all_box_ajax(Request $request)
    {
        $user_id = Auth::id();
        $country = Auth::user()->country;
        $product_list = Product::where('user_id', $user_id)->where('room_id', $request['room_id'])->get();
        return view('users.product.product_list_ajax', compact("product_list"));
    }

    /*
	Updating Sell To / Lend To
	*/
    public function updateselling(Request $request)
    {
        try {
            $data = $request->all();
            if (isset($data['sell_to_friend'])) {
                $checkselling = User::where('id', Auth::id())->update(['sell_to_friend' => $data['sell_to_friend']]);
                if (!empty($checkselling)) {
                    $response['success'] = 1;
                    //$response['message'] = $html_append;
                    return response()->json($response);
                }
            }
            if (isset($data['sell_to'])) {
                /*$response['success'] = 0;
				return response()->json($response);*/
                $checkselling = friend_group::where('id', $data['id'])->update(['sell_to' => $data['sell_to']]);
                if (!empty($checkselling)) {
                    $response['success'] = 1;
                    //$response['message'] = $html_append;
                    return response()->json($response);
                }
            }
            if (isset($data['lend_to'])) {
                $checkselling = friend_group::where('id', $data['id'])->update(['lend_to' => $data['lend_to']]);
                if (!empty($checkselling)) {
                    $response['success'] = 1;
                    //$response['message'] = $html_append;
                    return response()->json($response);
                }
            }
            if (isset($data['sell_to_friend_of_friend'])) {
                $checkselling = User::where('id', Auth::id())->update(['sell_to_friend_of_friend' => $data['sell_to_friend_of_friend']]);
                if (!empty($checkselling)) {
                    $response['success'] = 1;
                    //$response['message'] = $html_append;
                    return response()->json($response);
                }
            }
            if (isset($data['sell_to_neighbour'])) {
                $checkselling = User::where('id', Auth::id())->update(['sell_to_neighbour' => $data['sell_to_neighbour']]);
                if (!empty($checkselling)) {
                    $response['success'] = 1;
                    // $response['message'] = $html_append;
                    return response()->json($response);
                }
            }
            if (isset($data['sell_to_uk'])) {
                $checkselling = User::where('id', Auth::id())->update(['sell_to_uk' => $data['sell_to_uk']]);
                if (!empty($checkselling)) {
                    $response['success'] = 1;
                    //$response['message'] = $html_append;
                    return response()->json($response);
                }
            }
            if (isset($data['lend_to_friend'])) {
                $checkselling = User::where('id', Auth::id())->update(['lend_to_friend' => $data['lend_to_friend']]);
                if (!empty($checkselling)) {
                    $response['success'] = 1;
                    // $response['message'] = $html_append;
                    return response()->json($response);
                }
            }
            if (isset($data['lend_to_friend_of_friend'])) {
                $checkselling = User::where('id', Auth::id())->update(['lend_to_friend_of_friend' => $data['lend_to_friend_of_friend']]);
                if (!empty($checkselling)) {
                    $response['success'] = 1;
                    //$response['message'] = $html_append;
                    return response()->json($response);
                }
            }
            if (isset($data['lend_to_neighbour'])) {
                $checkselling = User::where('id', Auth::id())->update(['lend_to_neighbour' => $data['lend_to_neighbour']]);
                if (!empty($checkselling)) {
                    $response['success'] = 1;
                    //$response['message'] = $html_append;
                    return response()->json($response);
                }
            }
            if (isset($data['lend_to_uk'])) {
                $checkselling = User::where('id', Auth::id())->update(['lend_to_uk' => $data['lend_to_uk']]);
                if (!empty($checkselling)) {
                    $response['success'] = 1;
                    //$response['message'] = $html_append;
                    return response()->json($response);
                }
            }
        } catch (\Exception $e) {
            $response['success'] = 0;
            $response['message'] = $e->getMessage() . ' ' . $e->getLine();
            return response()->json($response);
        }
    }

    /* Update Communication */
    public function update_communication(Request $request)
    {

        $data = $request->all();
        if (isset($request['order_status'])) {
            $checkselling = User::where('id', Auth::id())->update(['order_status' => $data['order_status']]);
            if (!empty($checkselling)) {
                $response['success'] = 1;
                //$response['message'] = $html_append;
                return response()->json($response);
            }
        }
        if (isset($request['update_message'])) {
            $checkselling = User::where('id', Auth::id())->update(['message_status' => $data['update_message']]);
            if (!empty($checkselling)) {
                $response['success'] = 1;
                //$response['message'] = $html_append;
                return response()->json($response);
            }
        }
        if (isset($request['collect_status'])) {

            $checkselling = User::where('id', Auth::id())->update(['collect_status' => $data['collect_status']]);
            if (!empty($checkselling)) {
                $response['success'] = 1;
                //$response['message'] = $html_append;
                return response()->json($response);
            }
        }
        if (isset($request['collection_status'])) {
            $checkselling = User::where('id', Auth::id())->update(['collection_status' => $data['collection_status']]);
            if (!empty($checkselling)) {
                $response['success'] = 1;
                //$response['message'] = $html_append;
                return response()->json($response);
            }
        }
        if (isset($request['friend_status'])) {
            $checkselling = User::where('id', Auth::id())->update(['friend_status' => $data['friend_status']]);
            if (!empty($checkselling)) {
                $response['success'] = 1;
                //$response['message'] = $html_append;
                return response()->json($response);
            }
        }
    }

    /* Delete Delivery */
    public function delete_delivery(Request $request)
    {
        $data = $request->all();
        $inserval = deliverie::find($data['id'])->delete();
        if (!empty($inserval)) {
            $response['status'] = 1;
            return response()->json($response);
        } else {
            $response['status'] = 0;
            $response['message'] = 'There is error while deleting data';
            return response()->json($response);
        }
    }

    /* Add Delivery */
    public function add_delivery(Request $request)
    {
        $data = $request->all();
        $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
        if ($data['delivery_provider'] == '' || $data['tracking_url'] == '') {
            $response['status'] = 0;
            $response['message'] = 'Please Enter All The Fields';
            return response()->json($response);
        } else {
            $inserval = deliverie::create(['user_id' => Auth::user()->id,
                'delivery_provider' => $data['delivery_provider'],
                'tracking_url' => $data['tracking_url'],
                'description' => $data['description'],
                'price' => $data['price']]);
            if (!empty($inserval)) {
                $response['status'] = 1;
                $response['delivery_provider'] = $data['delivery_provider'];
                $response['tracking_url'] = $data['tracking_url'];
                $response['description'] = $data['description'];
                if ($data['price'] == '') {
                    $response['price'] = 'Free';
                } else {
                    $data['price2'] = $data['price'];
                    $data['price'] = number_format($data['price'], $decimal_place[0]['decimal_places']);
                    $data['price'] = str_replace('.00', '', $data['price']);
                    $response['price'] = $data['price'];
                    $response['price2'] = $data['price2'];
                }
                $response['id'] = $inserval->id;
                return response()->json($response);
            } else {
                $response['status'] = 0;
                $response['message'] = 'There is error while inserting data';
                return response()->json($response);
            }
        }
    }

    /* Create Delivery */
    public function update_delivery(Request $request)
    {
        $data = $request->all();
        $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
        if (isset($data['inpost_status'])) {
            $checkselling = User::where('id', Auth::id())->update(['inpost_status' => $data['inpost_status']]);
            if (!empty($checkselling)) {
                $response['success'] = 1;
                //$response['message'] = $html_append;
                return response()->json($response);
            }
        }
        if (isset($data['delivery_provider'])) {
            $insertVal = deliverie::where('id', $request['id'])->update(['user_id' => Auth::user()->id, 'delivery_provider' => $request['delivery_provider'],
                'tracking_url' => $request['tracking_url'], 'price' => $request['price'], 'description' => $request['description'], 'status' => $request['status']]);

            if (empty($insertVal)) {
                $response['status'] = 0;
                $response['message'] = 'There is error while updating data';
                return response()->json($response);
            } else {
                $response['status'] = 1;
                $response['delivery_provider'] = $request['delivery_provider'];
                $response['tracking_url'] = $request['tracking_url'];
                $response['description'] = $request['description'];
                $request['price'] = number_format($request['price'], $decimal_place[0]['decimal_places']);
                $request['price'] = str_replace('.00', '', $request['price']);
                $response['price'] = $request['price'];
                $response['id'] = $request['id'];
                $response['deliverystatus'] = $request['status'];
                return response()->json($response);
            }
        }
        $response['status'] = 0;
        $response['message'] = 'Error';
        return response()->json($response);
    }

    /* Update Delivery */
    public function updatedelivery(Request $request)
    {

    }

    /* Update Delivery Option For Customer */
    public function updatedeliveryoption(Request $request)
    {
        $updatedeliveryoption = User::where('id', Auth::user()->id)->update(['delivery_option' => $request['delivery_option']]);
        if (!empty($updatedeliveryoption)) {
            $response['success'] = '1';
            return response()->json($response);
        }
    }

    /* Update Return Status Address & Label */
    public function update_return_status(Request $request)
    {
        $return_address = $request->return_address;
        $return_label_status = $request->return_label_status;
        $updatereturnstatus = User::where('id', Auth::user()->id)->update(['return_address' => $return_address, 'return_label_status' => $return_label_status]);
        if (!empty($updatereturnstatus)) {
            $response['success'] = '1';
            return response()->json($response);
        } else {
            $response['success'] = '0';
            $response['message'] = 'Error while updating';
            return response()->json($response);
        }
    }

    /* Update INPOST RETURN */
    public function update_inpost_return(Request $request)
    {
        $inpost_return = $request->inpost_return;
        $updatereturnstatus = User::where('id', Auth::user()->id)->update(['inpost_return' => $inpost_return]);
        if (!empty($updatereturnstatus)) {
            $response['success'] = '1';
            return response()->json($response);
        } else {
            $response['success'] = '0';
            $response['message'] = 'Error while updating';
            return response()->json($response);
        }

    }

    /* Move Box */
    public function move_box(Request $request)
    {
        $data = $request->all();
        $room_id = p_boxe::where('id', $data['move_box_id'])->get();
        $roomupdate = Product::where('p_box', $data['box_id'])->where('p_quantity', '!=', '0')->update(['p_box' => $data['move_box_id'], 'room_id' => $room_id[0]['p_rooms_id']]);
        if (!empty($roomupdate)) {
            p_boxe::where('id', $data['box_id'])->update(['status' => '0']);
            $response['success'] = 1;
            $response['message'] = 'Updated';
            $response['roomid'] = $room_id[0]['p_rooms_id'];
            return response()->json($response);
        } else {
            $response['success'] = 0;
            $response['message'] = $roomupdate;
            return response()->json($response);
        }
    }

    /* Update Room */
    public function update_room(Request $request)
    {
        $data = $request->all();
        $roomupdate = P_room::where('id', $request['id'])->update(['display_text' => $request['display_text']]);
        if (!empty($roomupdate)) {
            $response['success'] = 1;
            $response['message'] = 'Updated';
            return response()->json($response);
        } else {
            $response['success'] = 0;
            $response['message'] = 'Error';
            return response()->json($response);
        }
    }

    /*
	adding opening hours
	*/
    public function addOpenHours(Request $request)
    {
        try {

            if (empty($request->all())) {

                $response['success'] = 0;
                $response['message'] = "Some error has occured.";
                return response()->json($response);
            }
            $request_arr = $request->all();
            $user_id = ['user_id' => Auth::id()];
            $insert_arr = $request_arr + $user_id;
            $created_at = ['created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s')];
            $insert_arr = $insert_arr + $created_at;

            #check if details exists
            $check_exists = Users_opening_hr::where('user_day_name', $request->user_day_name)->where('user_id', Auth::user()->id)->get();
            if (count($check_exists) > 0) {
                if (count($check_exists) > 0) {             // If day match in the service opening hours
                    $status = 0; // variable to check input time slot is not in the service opening hours.
                    if ($check_exists[0]['break_time'] > '0')  // if multiple slot is in the service opening hours
                    {
                        $start_time = explode(',', $check_exists[0]['user_start_time']); // To seperate start times into array
                        $end_time = explode(',', $check_exists[0]['user_end_time']); // TO seperate end times into array
                        for ($i = 0; $i < count($start_time); $i++)   // Loop to check every slot time with start time and end time in service opening hours
                        {
                            if ((strtotime($request['user_start_time']) > strtotime($start_time[$i]) && strtotime($request['user_start_time']) > strtotime($end_time[$i]) && strtotime($request['user_end_time']) > strtotime($start_time[$i]) && strtotime($request['user_end_time']) > strtotime($end_time[$i])) || ((strtotime($request['user_start_time']) < strtotime($start_time[$i]) && strtotime($request['user_start_time']) < strtotime($end_time[$i]) && strtotime($request['user_end_time']) < strtotime($start_time[$i]) && strtotime($request['user_end_time']) < strtotime($end_time[$i])))) {   // checking the start time and end time is not in the range of slot time in service opening hours

                                $status++;      // Means slot not in the range of saved time slot
                            }
                        }
                    } else            // if single slot is in the service hours
                    {
                        if ((strtotime($request['user_start_time']) > strtotime($check_exists[0]['user_start_time']) && strtotime($request['user_start_time']) > strtotime($check_exists[0]['user_end_time']) && strtotime($request['user_end_time']) > strtotime($check_exists[0]['user_start_time']) && strtotime($request['user_end_time']) > strtotime($check_exists[0]['user_end_time'])) || ((strtotime($request['user_start_time']) < strtotime($check_exists[0]['user_start_time']) && strtotime($request['user_start_time']) < strtotime($check_exists[0]['user_end_time']) && strtotime($request['user_end_time']) < strtotime($check_exists[0]['user_start_time']) && strtotime($request['user_end_time']) < strtotime($check_exists[0]['user_end_time'])))) {   // checking the start time and end time is not in the range of slot time in service opening hours

                            $status = 1;        // Means slot not in the range of saved time slot
                        } else {
                            $status = 0;        // Means is in the range of saved time slot
                        }
                    }
                    if ($check_exists[0]['break_time'] > '0') {
                        $start_time = explode(',', $check_exists[0]['user_start_time']);
                    } else {
                        $start_time = 0;
                    }
                    if (($status == count($start_time) && $check_exists[0]['break_time'] > '0') || ($status == '1' && $check_exists[0]['break_time'] == '0'))        // If the time slot is not in the range of saved time slot
                    {

                        $user_start_time = $check_exists[0]['user_start_time'] . ',' . $request['user_start_time'];
                        $user_end_time = $check_exists[0]['user_end_time'] . ',' . $request['user_end_time'];
                        $request_arr = ['user_day' => $request['user_day'], 'user_day_name' => $request['user_day_name'], 'user_start_time' => $user_start_time, 'user_end_time' => $user_end_time];
                        $user_id = ['user_id' => Auth::id()];
                        $insert_arr = $request_arr + $user_id;
                        $created_at = ['created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s')];
                        $insert_arr = $insert_arr + $created_at;
                        //$insert_check = DB::table('service_opening_hrs')->insertGetId($insert_arr);
                        $insert_check = Users_opening_hr::where('id', $check_exists[0]['id'])->update($insert_arr);
                        Users_opening_hr::where('id', $check_exists[0]['id'])->increment('break_time', 1);
                        if ($insert_check) {
                            $filterstarttime = explode(',', $user_start_time);
                            $filterendtime = explode(',', $user_end_time);
                            for ($j = 0; $j < count($filterstarttime); $j++) {
                                $filterstarttime[$j] = date("g:i a", strtotime($filterstarttime[$j]));
                                $filterendtime[$j] = date("g:i a", strtotime($filterendtime[$j]));
                                $filtered[$j] = $filterstarttime[$j] . ' - ' . $filterendtime[$j];
                            }
                            $filteredfinal = implode(',', $filtered);
                            //$filteredendtime = implode(',',$filterendtime);

                            $html_append = '<li  id="hour_id_' . $check_exists[0]['id'] . '" style="font-size:10px">' . ucwords($request['user_day_name']) . ' (' . $filteredfinal . ') <span class="text-danger" data-user_hr_id="' . $check_exists[0]['id'] . '" onclick="removeOpenHour(this);"> <i class="fa fa-trash" aria-hidden="true"></i> </span>  </li>';
                            $response['success'] = 1;
                            $response['message'] = $html_append;
                            $response['type'] = 'update';
                            $response['id'] = $check_exists[0]['id'];
                            return response()->json($response);
                        } else {
                            $response['success'] = 0;
                            $response['message'] = "Unable to save timeslot. ";
                            return response()->json($response);
                        }
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'This timeslot already exist or is in the range of previous time slot.';
                        //$response['message'] = date('H:i:s');
                        return response()->json($response);
                    }
                }
            } else {
                $insert_check = DB::table('users_opening_hrs')->insertGetId($insert_arr);
                if ($insert_check) {

                    $html_append = '<li  id="hour_id_' . $insert_check . '" style="font-size:10px">' . ucwords($request['user_day_name']) . ' (' . date("g:i a", strtotime($request['user_start_time'])) . ' - ' . date("g:i a", strtotime($request['user_end_time'])) . ') <span class="text-danger" data-user_hr_id="' . $insert_check . '" onclick="removeOpenHour(this);"> <i class="fa fa-trash" aria-hidden="true"></i> </span>  </li>';
                    $response['success'] = 1;
                    $response['message'] = $html_append;
                    return response()->json($response);
                } else {
                    $response['success'] = 0;
                    $response['message'] = "Unable to save timeslot. ";
                    return response()->json($response);
                }
            }
        } catch (\Exception $e) {
            $response['success'] = 0;
            $response['message'] = $e->getMessage() . ' ' . $e->getLine();
            return response()->json($response);
        }
    }#addOpenHours ends here

    /*
	Delete open hours
	*/

    public function removeOpenHours(Request $request)
    {

        try {
            #check if id exists
            $id_check = Users_opening_hr::where($request->all())->first();
            if (count($id_check) == 0) {
                $response['success'] = 0;
                $response['message'] = 'Invalid Open Hour ID. Please reload.';
                return response()->json($response);
            }

            #delete
            $delete_check = DB::table('users_opening_hrs')->where($request->all())->delete();

            if ($delete_check != 0) {
                $response['success'] = 1;
                $response['message'] = 'Success';
                return response()->json($response);
            } else {
                $response['success'] = 0;
                $response['message'] = 'Some error occured while deleting the time slot.';
                return response()->json($response);
            }
        } catch (\Exception $e) {
            $response['success'] = 0;
            $response['message'] = $e->getMessage() . ' ' . $e->getLine();
            return response()->json($response);
        }
    }

    /*
	Add Service Hours
	*/
    public function addServiceHours(Request $request)
    {
        try {

            if (empty($request->all())) {

                $response['success'] = 0;
                $response['message'] = "Some error has occured.";
                return response()->json($response);
            }
            $request_arr = $request->all();
            $user_id = ['user_id' => Auth::id()];
            $insert_arr = $request_arr + $user_id;
            $created_at = ['created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s')];
            $insert_arr = $insert_arr + $created_at;


            #check if details exists
            $check_exists = service_opening_hr::where('user_day_name', $request->user_day_name)->where('user_id', Auth::user()->id)->get();
            if (count($check_exists) > 0) {             // If day match in the service opening hours
                $status = 0; // variable to check input time slot is not in the service opening hours.
                if ($check_exists[0]['break_time'] > '0')  // if multiple slot is in the service opening hours
                {
                    $start_time = explode(',', $check_exists[0]['user_start_time']); // To seperate start times into array
                    $end_time = explode(',', $check_exists[0]['user_end_time']); // TO seperate end times into array
                    for ($i = 0; $i < count($start_time); $i++)   // Loop to check every slot time with start time and end time in service opening hours
                    {
                        if ((strtotime($request['user_start_time']) > strtotime($start_time[$i]) && strtotime($request['user_start_time']) > strtotime($end_time[$i]) && strtotime($request['user_end_time']) > strtotime($start_time[$i]) && strtotime($request['user_end_time']) > strtotime($end_time[$i])) || ((strtotime($request['user_start_time']) < strtotime($start_time[$i]) && strtotime($request['user_start_time']) < strtotime($end_time[$i]) && strtotime($request['user_end_time']) < strtotime($start_time[$i]) && strtotime($request['user_end_time']) < strtotime($end_time[$i])))) {   // checking the start time and end time is not in the range of slot time in service opening hours

                            $status++;      // Means slot not in the range of saved time slot
                        }
                    }
                } else            // if single slot is in the service hours
                {
                    if ((strtotime($request['user_start_time']) > strtotime($check_exists[0]['user_start_time']) && strtotime($request['user_start_time']) > strtotime($check_exists[0]['user_end_time']) && strtotime($request['user_end_time']) > strtotime($check_exists[0]['user_start_time']) && strtotime($request['user_end_time']) > strtotime($check_exists[0]['user_end_time'])) || ((strtotime($request['user_start_time']) < strtotime($check_exists[0]['user_start_time']) && strtotime($request['user_start_time']) < strtotime($check_exists[0]['user_end_time']) && strtotime($request['user_end_time']) < strtotime($check_exists[0]['user_start_time']) && strtotime($request['user_end_time']) < strtotime($check_exists[0]['user_end_time'])))) {   // checking the start time and end time is not in the range of slot time in service opening hours

                        $status = 1;        // Means slot not in the range of saved time slot
                    } else {
                        $status = 0;        // Means is in the range of saved time slot
                    }
                }
                if ($check_exists[0]['break_time'] > '0') {
                    $start_time = explode(',', $check_exists[0]['user_start_time']);
                } else {
                    $start_time = 0;
                }
                if (($status == count($start_time) && $check_exists[0]['break_time'] > '0') || ($status == '1' && $check_exists[0]['break_time'] == '0'))        // If the time slot is not in the range of saved time slot
                {

                    $user_start_time = $check_exists[0]['user_start_time'] . ',' . $request['user_start_time'];
                    $user_end_time = $check_exists[0]['user_end_time'] . ',' . $request['user_end_time'];
                    $request_arr = ['user_day' => $request['user_day'], 'user_day_name' => $request['user_day_name'], 'user_start_time' => $user_start_time, 'user_end_time' => $user_end_time];
                    $user_id = ['user_id' => Auth::id()];
                    $insert_arr = $request_arr + $user_id;
                    $created_at = ['created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s')];
                    $insert_arr = $insert_arr + $created_at;
                    //$insert_check = DB::table('service_opening_hrs')->insertGetId($insert_arr);
                    $insert_check = service_opening_hr::where('id', $check_exists[0]['id'])->update($insert_arr);
                    service_opening_hr::where('id', $check_exists[0]['id'])->increment('break_time', 1);
                    if ($insert_check) {
                        $filterstarttime = explode(',', $user_start_time);
                        $filterendtime = explode(',', $user_end_time);
                        for ($j = 0; $j < count($filterstarttime); $j++) {
                            $filterstarttime[$j] = date("g:i a", strtotime($filterstarttime[$j]));
                            $filterendtime[$j] = date("g:i a", strtotime($filterendtime[$j]));
                            $filtered[$j] = $filterstarttime[$j] . ' - ' . $filterendtime[$j];
                        }
                        $filteredfinal = implode(',', $filtered);
                        //$filteredendtime = implode(',',$filterendtime);

                        $html_append = '<p  id="service_hour_id_' . $check_exists[0]['id'] . '" style="font-size:15px">' . ucwords($request['user_day_name']) . ' (' . $filteredfinal . ') <span class="text-danger" data-user_hr_id="' . $check_exists[0]['id'] . '" onclick="removeServiceHour(this);"> <i class="fa fa-trash" aria-hidden="true"></i> </span>  </p>';
                        $response['success'] = 1;
                        $response['message'] = $html_append;
                        $response['type'] = 'update';
                        $response['id'] = $check_exists[0]['id'];
                        return response()->json($response);
                    } else {
                        $response['success'] = 0;
                        $response['message'] = "Unable to save timeslot. ";
                        return response()->json($response);
                    }
                } else {
                    $response['success'] = 0;
                    $response['message'] = 'This timeslot already exist or is in the range of previous time slot.';
                    //$response['message'] = date('H:i:s');
                    return response()->json($response);
                }
            } else {
                $insert_check = DB::table('service_opening_hrs')->insertGetId($insert_arr);
                if ($insert_check) {

                    $html_append = '<p  id="service_hour_id_' . $insert_check . '" style="font-size:15px">' . ucwords($request['user_day_name']) . ' (' . date("g:i a", strtotime($request['user_start_time'])) . ' - ' . date("g:i a", strtotime($request['user_end_time'])) . ') <span class="text-danger" data-user_hr_id="' . $insert_check . '" onclick="removeServiceHour(this);"> <i class="fa fa-trash" aria-hidden="true"></i> </span>  </p>';
                    $response['success'] = 1;
                    $response['message'] = $html_append;
                    $response['type'] = 'insert';
                    return response()->json($response);
                } else {
                    $response['success'] = 0;
                    $response['message'] = "Unable to save timeslot. ";
                    return response()->json($response);
                }
            }
        } catch (\Exception $e) {
            $response['success'] = 0;
            $response['message'] = $e->getMessage() . ' ' . $e->getLine();
            return response()->json($response);
        }
    }

    /*
	Delete Service hours
	*/

    public function removeServiceHours(Request $request)
    {

        try {
            #check if id exists
            $id_check = service_opening_hr::where($request->all())->first();
            if (count($id_check) == 0) {
                $response['success'] = 0;
                $response['message'] = 'Invalid Open Hour ID. Please reload.';
                return response()->json($response);
            }

            #delete
            $delete_check = DB::table('service_opening_hrs')->where($request->all())->delete();

            if ($delete_check != 0) {
                $response['success'] = 1;
                $response['message'] = 'Success';
                return response()->json($response);
            } else {
                $response['success'] = 0;
                $response['message'] = 'Some error occured while deleting the time slot.';
                return response()->json($response);
            }
        } catch (\Exception $e) {
            $response['success'] = 0;
            $response['message'] = $e->getMessage() . ' ' . $e->getLine();
            return response()->json($response);
        }
    }

    /*
	product page
	*/
    public function products_page(Request $request, $slug)
    {
        try {
            $productid = base64_decode($request->id);
            //$productid = $request->id;
            $orders = Order::where('o_product_id', $productid)->get();
            $slug = substr($slug, strpos($slug, "buy-") + 4);
            /*print_r($slug);die;*/

            $userId = Auth::id();

            #basic product details
            $product_details = Product::with(['userDet', 'product_type', 'product_sell_options', 'product_lend_options', 'product_service_option', 'product_subs_option'])->where('id', '=', $productid)
                ->get();

            $user_id = $product_details[0]->user_id;
            if ($product_details[0]->p_quantity < '1') {
                return redirect()->route('products_friends');
            }

            #collect hours
            if ($product_details[0]['p_type'] == '2') {
                $open_hrs = service_opening_hr::where('user_id', $user_id)->orderby('user_day')->get();
            } else {
                $open_hrs = Users_opening_hr::where('user_id', $user_id)->orderby('user_day')->get();
            }
            $sellercountry = User::where('id', $user_id)->get();
            $holiday = Holiday::where('user_id', $user_id)->get();

            if (count($holiday) > '0') {
                foreach ($holiday as $holidays) {


                    $holiday_start = explode(' ', $holidays->start);
                    $holiday_end = explode(' ', $holidays->end);
                    if ((date('Y-m-d', strtotime($this->summertime())) >= $holiday_start[0] && date('Y-m-d', strtotime($this->summertime())) <= $holiday_end[0])) {   // If holiday is not equal to current date in the loop
                        $dateafterholiday = date('Y-m-d', strtotime($holiday_end[0] . ' + 1 days'));
                    } else {
                        $dateafterholiday = date('Y-m-d', strtotime($this->summertime()));
                    }
                }
            } else {
                $dateafterholiday = date('Y-m-d', strtotime($this->summertime()));
            }
            #current time and day
            if ($product_details[0]['p_type'] == '2') {
                if ($product_details[0]['service_lead_time'] != '') {
                    $date_diff = '0';
                    $date_diff = $date_diff / (60 * 60 * 24);
                    $lead_time = $product_details[0]['service_lead_time'] - $date_diff;
                    if ($lead_time > 0) {
                        $summertime = date('Y-m-d H:i:s', strtotime($this->summertime() . ' + ' . $lead_time . ' days'));
                        $current_day = date('w', strtotime($summertime));
                    } else {
                        $current_day = date('w', strtotime($dateafterholiday));
                    }
                } else {
                    $current_day = date('w', strtotime($dateafterholiday));
                }
            } else {
                $current_day = date('w', strtotime($dateafterholiday));
            }
            $current_time = date('h:i:s');
            #collect times
            if (count($open_hrs) > 0) {
                $delivery_message_heading = '';

                $available_shop_times = array();
                $timeSlot = array();

                /*$multipleuserday = array();
				foreach($open_hrs as $multiplekey => $multipleslots)
				{

				}*/
                //dd($current_day);
                foreach ($open_hrs as $key => $value) {

                    $available_shop_times[$key] = $value['user_day'];
                    $timeSlot[$value['user_day']]['break_time'] = $value['break_time']; // For checking multiple slot
                    if ($value['break_time'] > '0') {
                        $filterstarttime = explode(',', $value['user_start_time']);
                        $filterendtime = explode(',', $value['user_end_time']);
                        for ($j = 0; $j < count($filterendtime); $j++) {
                            $timeSlot[$value['user_day']]['start_time'][$j] = date("g:ia", strtotime($filterstarttime[$j]));
                            $timeSlot[$value['user_day']]['end_time'][$j] = date("g:ia", strtotime($filterendtime[$j]));
                        }
                        usort($timeSlot[$value['user_day']]['start_time'], function ($time1, $time2) {
                            if (strtotime($time1) > strtotime($time2))
                                return 1;
                            else if (strtotime($time1) < strtotime($time2))
                                return -1;
                            else
                                return 0;
                        });
                        usort($timeSlot[$value['user_day']]['end_time'], function ($time1, $time2) {
                            if (strtotime($time1) > strtotime($time2))
                                return 1;
                            else if (strtotime($time1) < strtotime($time2))
                                return -1;
                            else
                                return 0;
                        });
                        $timeSlot[$value['user_day']]['start_time'] = implode(',', $timeSlot[$value['user_day']]['start_time']);
                        $timeSlot[$value['user_day']]['end_time'] = implode(',', $timeSlot[$value['user_day']]['end_time']);
                    } else {
                        $timeSlot[$value['user_day']]['start_time'] = date("g:ia", strtotime($value['user_start_time']));
                        $timeSlot[$value['user_day']]['end_time'] = date("g:ia", strtotime($value['user_end_time']));
                    }

                }


                $nearest_day = array();
                $smallest = array();
                $last = null;
                $available_day = 0;
                foreach ($available_shop_times as $key => $value) {
                    if ($timeSlot[$value]['break_time'] > '0') {
                        $starttime = explode(',', $timeSlot[$value]['end_time']);
                        $timeSlot[$value]['fix_end_time'] = implode(',', $starttime);
                        $timeSlot[$value]['end_time'] = $starttime[$timeSlot[$value]['break_time']];
                    }

                    if ($current_day == $value && $product_details[0]['service_lead_time'] != '' && strtotime(date("Y-m-d H:i:s", strtotime(($timeSlot[$value]['end_time'])))) >= strtotime(date("Y-m-d H:i:s", strtotime($this->summertime())))) {                 // Code changable after multiple time slot match for two end_time with ||
                        $day_name = date('l', strtotime("Sunday + $current_day Days"));
                        //$date_diff = abs(strtotime(date('Y-m-d'))-strtotime(date('Y-m-d')));
                        $date_diff = 0;
                        $date_diff = $date_diff / (60 * 60 * 24);
                        $lead_time = $product_details[0]['service_lead_time'] - $date_diff;
                        if ($lead_time > 0) {
                            if ($lead_time > '7') {
                                $main_heading_day = $day_name;
                                $available_day = $value; // found it, return quickly
                            } else {
                                $main_heading_day = 'This ' . $day_name;
                                $available_day = $value; // found it, return quickly
                            }
                        } else {
                            $main_heading_day = 'Today';
                            $available_day = $value; // found it, return quickly
                        }

                    }
                    /*elseif($current_day != $value && $product_details[0]['service_lead_time']!=''){
							$day_name = date('l', strtotime("Sunday + $current_day Days"));
							$main_heading_day = 'This '.$day_name;
							$available_day = $value; // found it, return quickly
					}*/
                    //echo date("Y-m-d H:i:s",strtotime($timeSlot[$value]['end_time']));
                    //echo '<br>';
                    //echo date("Y-m-d H:i:s",strtotime($this->summertime()));

                    elseif ($current_day == $value && $product_details[0]['service_lead_time'] == '' && strtotime(date("Y-m-d H:i:s", strtotime(($timeSlot[$value]['end_time'])))) >= strtotime(date("Y-m-d H:i:s", strtotime($this->summertime())))) {                                 // Changable after multiple time slot end_time with ||
                        $available_day = $value;
                        $main_heading_day = 'Today';
                    } /* elseif ($current_day < $value && $available_day == '0') {
                       // dd($available_day);
                        //dd(date('Y-m-d',strtotime($timeSlot[$value]['end_time'])));
                        //dd('here');
                        $day_name = date('l', strtotime("Sunday + $value Days"));
                        $available_day = $value;
                        $main_heading_day = 'This ' . $day_name;
                    }*/
                    elseif ($current_day < $value && $available_day == '0') {
                        // dd($available_day);
                        //dd(date('Y-m-d',strtotime($timeSlot[$value]['end_time'])));
                        //dd('here');
                        $day_name = date('l', strtotime("Sunday + $value Days"));
                        //$available_day = $value;
                        $available_day = $value;
                        $main_heading_day = 'This ' . $day_name;
                    }
                    /*elseif($current_day > $value && $available_day=='0'){
                        $day_name = date('l', strtotime("Sunday + $value Days"));
                        //$available_day = $value;
                        $available_day = $value;
                        $main_heading_day = 'This ' . $day_name;
                    }*/
                    //echo date("Y-m-d H:i:s",strtotime($timeSlot[$value]['end_time']));
                    //echo '<br>';
                    //echo date("Y-m-d H:i:s",strtotime($this->summertime()));
                    /*else{
						if ($value > $current_day) {
				            $available_day = $value; // found it, return quickly
				        }
					}*/
                }
                //dd($available_day);
                //dd($available_shop_times);
                //dd($available_shop_times);
                if ($available_day == 0) {
                    $available_day = min($available_shop_times);
                }
                $timeslot_heading = $timeSlot[$available_day];
                //dd($available_day);
                #get time slot
                //dd($available_day);
                //dd($current_day);
                /*if($available_day==$value)
				{
					$minute = date('i',strtotime($this->summertime()));
					dd(date('Y-m-d',strtotime(date('l',strtotime($available_day)))));
					   if(strtotime(date('Y-m-d H:i:s',strtotime($timeSlot[$available_day]['start_time']))) < strtotime(date('Y-m-d H:i:s',strtotime($this->summertime()))) && strtotime(date('Y-m-d')) == strtotime(date('Y-m-d',strtotime(date('l',strtotime($available_day))))))
					    {


					            $minute_diff = 60 - $minute;
					            $newtime = date('h:0:0');
					            //$start = new \DateTimeImmutable($newtime);
					            if($minute_diff<11)
					            {
					                //$timeSlot[$available_day]['start_time'] = $start->add(new DateInterval('PT2H'));
					                $timeSlot[$available_day]['start_time'] = date('g:00a',strtotime('+2 hours'));

					            }
					            else
					            {
					                //$timeSlot[$available_day]['start_time'] = $start->add(new DateInterval('PT1H'));
					                $timeSlot[$available_day]['start_time'] = date('g:00a',strtotime('+1 hours'));

					            }
					    }
					  /* elseif(strtotime(date('Y-m-d h:i:s A',strtotime($timeSlot[$available_day]['start_time']))) > strtotime(date('Y-m-d h:i:s A',strtotime($this->summertime()))))
                       {

                       }




				}*/
                //dd($main_heading_day);
                //dd($available_day);
                if (!isset($main_heading_day)) {
                    //dd($available_day);
                    $day_name = date('l', strtotime("Sunday+ $available_day Days"));
                    $main_heading_day = 'This ' . $day_name;
                }
            } else {
                $delivery_message_heading = "No time slot provided yet.";
            }


            #collection/delivery times
            $class_array = ['text-info', 'text-warning', 'text-success', 'text-purple', 'text-danger'];
            $collect_dates = array();


            #collect two months period
            $current_date = date('Y-m-d');
            $last_date = date('Y-m-d', strtotime('+2 month'));

            if ($product_details[0]['service_lead_time'] != '') {
                //$date_diff = abs(strtotime(date('Y-m-d'))-strtotime(date('Y-m-d')));
                $date_diff = 0;
                $date_diff = $date_diff / (60 * 60 * 24);
                $lead_time = $product_details[0]['service_lead_time'] - $date_diff;
                if ($lead_time > 0) {
                    $begin = new \DateTime(date('Y-m-d', strtotime('+' . $lead_time . ' days')));    // For adding service lead time
                } else {
                    $begin = new \DateTime(date('Y-m-d', strtotime($this->summertime())));   // For adding service lead time
                }
            } else {
                $begin = new \DateTime(date('Y-m-d', strtotime($this->summertime())));
            }
            $end = new \DateTime(date('Y-m-d', strtotime('+2 month')));
            $end = $end->modify('+1 day');
            $interval = new \DateInterval('P1D');
            $period = new \DatePeriod($begin, $interval, $end);


            if (count($open_hrs) > 1) {
                $open_hrs_status = 1; #available

                foreach ($open_hrs as $key => $info) {

                    $key_count_same = 0;
                    $key_count_diff = 0;
                    foreach ($period as $key => $value) {
                        if (count($holiday) == '0') {
                            $holidayblank = '1';
                        } else {
                            $holidayblank = '0';
                        }

                        $date = $value->format('Y-m-d');
                        if (count($holiday) > '0') {
                            foreach ($holiday as $holidays) {


                                $holiday_start = explode(' ', $holidays->start);
                                $holiday_end = explode(' ', $holidays->end);

                                if (($date > $holiday_start[0] && $date > $holiday_end[0] && $date != $holiday_start[0] && $date != $holiday_end[0]) || ($date < $holiday_start[0] && $date < $holiday_end[0] && $date != $holiday_start[0] && $date != $holiday_end[0])) {   // If holiday is not equal to current date in the loop


                                    if ($info['user_day'] == 7) {
                                        $info['user_day'] = 0;
                                    }
                                    if ($product_details[0]['service_lead_time'] != '') {
                                        //$date_diff = abs(strtotime(date('Y-m-d'))-strtotime(date('Y-m-d')));
                                        $date_diff = 0;
                                        $date_diff = $date_diff / (60 * 60 * 24);
                                        $lead_time = $product_details[0]['service_lead_time'] - $date_diff;
                                        if ($lead_time > 0) {
                                            $start_day = date('w', strtotime('+' . $lead_time . ' days'));
                                        } else {
                                            $start_day = date('w', strtotime($this->summertime()));
                                        }

                                    } else {
                                        $start_day = date('w', strtotime($this->summertime()));
                                    }
                                    //dd(date('w'));
                                    //dd($start_day);
                                    if (trim($info['user_day']) == $start_day && $key == 0) {
                                        if ($start_day != date('w')) {
                                            // $show_day = 'This ' . date('l', $start_day);
                                            $show_day = 'This ' . date('l', strtotime("Sunday + $start_day Days"));
                                        } else {
                                            if ($product_details[0]['service_lead_time'] != '') {
                                                $date_diff = abs(strtotime(date('Y-m-d')) - strtotime(date('Y-m-d')));
                                                $date_diff = $date_diff / (60 * 60 * 24);
                                                //dd($product_details[0]['service_lead_time']);
                                                $lead_time = $product_details[0]['service_lead_time'] - $date_diff;
                                            } else {
                                                $lead_time = 0;
                                            }
                                            if ($lead_time > 0) {
                                                //$show_day = 'This ' . date('l', strtotime("Sunday + $start_day Days"));
                                                $show_day = 'This ' . date('l', $start_day);
                                                //$show_day = 'This ' . date('l',strtotime("Sunday + $start_day Days"));
                                            } else {
                                                $show_day = 'Today';
                                            }
                                            //$show_day = 'Today';
                                        }
                                        /*if($key_count_same == 0){
								$day_display = 'This '.date('l', strtotime($date));
							}else{
								$day_display = date('l', strtotime($date));
							}*/
                                        $collect_dates[$key] = [
                                            'day' => $show_day,
                                            'start_time' => $info['user_start_time'],
                                            'end_time' => $info['user_end_time'],
                                            'break_time' => $info['break_time'],
                                            'date' => date('d M y', strtotime($date)),
                                            'raw_date' => date('Y-m-d', strtotime($date)),
                                        ];
                                    } elseif (date('w', strtotime($date)) == trim($info['user_day']) && $key > 0) {
                                        if ($key_count_same == 0) {
                                            $day_display = 'This ' . date('l', strtotime($date));
                                            //$day_display = 'This ' . date('l',strtotime("Sunday + $date Days"));
                                            //$day_display = 'This ' . date('l',strtotime("Sunday + $date Days"));
                                        } else { // Changed
                                            $day_display = date('l', strtotime($date));
                                            //$day_display = 'This ' . date('l',strtotime("Sunday + $date Days"));
                                            //$day_display = 'This ' . date('l',strtotime("Sunday + $date Days"));
                                        }
                                        $collect_dates[$key] = [
                                            'day' => $day_display,
                                            'start_time' => $info['user_start_time'],
                                            'end_time' => $info['user_end_time'],
                                            'break_time' => $info['break_time'],
                                            'date' => date('d M y', strtotime($date)),
                                            'raw_date' => date('Y-m-d', strtotime($date)),
                                        ];
                                        $key_count_same++;
                                    } elseif (trim($info['user_day']) == date('w', strtotime($date)) && $key != 0) {
                                        if ($key_count_diff == 0) {
                                            $day_display = 'This ' . date('l', strtotime('' . trim($info['user_day_name']) . ''));
                                            //$day_display = 'This ' . date('l',strtotime("Sunday + ". trim($info['user_day_name']) ." Days"));
                                        } else {
                                            $day_display = date('l', strtotime('' . trim($info['user_day_name']) . ''));
                                            // $day_display = 'This ' . date('l',strtotime("Sunday + ". trim($info['user_day_name']) ." Days"));
                                        }
                                        $collect_dates[$key] = [
                                            'day' => $day_display,
                                            'start_time' => $info['user_start_time'],
                                            'end_time' => $info['user_end_time'],
                                            'break_time' => $info['break_time'],
                                            'date' => date('d M y', strtotime($date)),
                                            'raw_date' => date('Y-m-d', strtotime($date)),
                                        ];
                                        $key_count_diff++;
                                    }

                                } // Holiday COndition ends here

                            }  // HOliday Loop Ends Here

                        }  // IF HOLIDAY ARRAY IS NOT EMPTY
                        else  // IF HOLIDAY ARRAY IS EMPTY
                        {
                            if ($info['user_day'] == 7) {
                                $info['user_day'] = 0;
                            }
                            if ($product_details[0]['service_lead_time'] != '') {
                                $date_diff = abs(strtotime(date('Y-m-d')) - strtotime(date('Y-m-d')));
                                $date_diff = $date_diff / (60 * 60 * 24);
                                $lead_time = $product_details[0]['service_lead_time'] - $date_diff;
                                if ($lead_time > 0) {
                                    $start_day = date('w', strtotime('+' . $lead_time . ' days'));
                                } else {
                                    $start_day = date('w');
                                }
                            } else {
                                $start_day = date('w');
                            }
                            /*date('l',strtotime("Sunday + '.trim($info['user_day_name']).' Days"));
                            echo 'Day:'.(date('l', strtotime('' . trim($info['user_day_name']) . '')));*/
                            if (trim($info['user_day']) == $start_day && $key == 0) {
                                /*if($start_day!=date('w'))
							{
								$show_day = 'This '.date('l', $start_day);
							}
							else
							{
								$show_day = 'Today';
							}*/
                                if ($key_count_same == 0) {
                                    //$day_display = 'This ' . date('l', strtotime($date));
                                    //$day_display = 'This ' . date('l',strtotime("Sunday + $date Days"));
                                    $day_display = 'Today';
                                } else {
                                    $day_display = 'This ' . date('l', strtotime($date));
                                    //$day_display = 'This ' . date('l',strtotime("Sunday + $date Days"));
                                }
                                $collect_dates[$key] = [
                                    'day' => $day_display,
                                    'start_time' => $info['user_start_time'],
                                    'end_time' => $info['user_end_time'],
                                    'break_time' => $info['break_time'],
                                    'date' => date('d M y', strtotime($date)),
                                    'raw_date' => date('Y-m-d', strtotime($date)),
                                ];
                            } elseif (date('w', strtotime($date)) == trim($info['user_day']) && $key > 0) {
                                if ($key_count_same == 0) {
                                    $day_display = 'This ' . date('l', strtotime($date));
                                    //$day_display = 'This ' . date('l',strtotime("Sunday + $date Days"));
                                } else {
                                    $day_display = date('l', strtotime($date));
                                    //$day_display = 'This ' . date('l',strtotime("Sunday + $date Days"));
                                }
                                $collect_dates[$key] = [
                                    'day' => $day_display,
                                    'start_time' => $info['user_start_time'],
                                    'end_time' => $info['user_end_time'],
                                    'break_time' => $info['break_time'],
                                    'date' => date('d M y', strtotime($date)),
                                    'raw_date' => date('Y-m-d', strtotime($date)),
                                ];
                                $key_count_same++;
                            } elseif (trim($info['user_day']) == date('w', strtotime($date)) && $key != 0) {
                                if ($key_count_diff == 0) {
                                    $day_display = 'This ' . date('l', strtotime('' . trim($info['user_day_name']) . ''));
                                } else {
                                    $day_display = date('l', strtotime('' . trim($info['user_day_name']) . ''));
                                }
                                $collect_dates[$key] = [
                                    'day' => $day_display,
                                    'start_time' => $info['user_start_time'],
                                    'end_time' => $info['user_end_time'],
                                    'break_time' => $info['break_time'],
                                    'date' => date('d M y', strtotime($date)),
                                    'raw_date' => date('Y-m-d', strtotime($date)),
                                ];
                                $key_count_diff++;
                            }
                        }

                    }
                }

                usort($collect_dates, function ($a, $b) {
                    return strtotime($a['date']) - strtotime($b['date']);
                });

                $collect_dates = array_unique($collect_dates, SORT_REGULAR);

            } elseif (count($open_hrs) == 1) {

                $open_hrs_status = 1; #available
                $key_count = 0;
                foreach ($period as $key => $value) {

                    $date = $value->format('Y-m-d');
                    if (count($holiday) > 0) {
                        foreach ($holiday as $holidays) {
                            $holiday_start = explode(' ', $holidays->start);
                            $holiday_end = explode(' ', $holidays->end);

                            if (($date > $holiday_start[0] && $date > $holiday_end[0] && $date != $holiday_start[0] && $date != $holiday_end[0]) || ($date < $holiday_start[0] && $date < $holiday_end[0] && $date != $holiday_start[0] && $date != $holiday_end[0])) {   // If holiday is not equal to current date in the loop


                                if ($open_hrs[0]->user_day == 7) {
                                    $open_hrs[0]->user_day = 0;
                                }

                                if (date('w', strtotime($date)) == $open_hrs[0]->user_day && $key == 0) {
                                    $collect_dates[$key] = [
                                        'day' => 'Today',
                                        'start_time' => $open_hrs[0]->user_start_time,
                                        'end_time' => $open_hrs[0]->user_end_time,
                                        'break_time' => $open_hrs[0]->break_time,
                                        'date' => date('d M y ', strtotime($date)),
                                        'raw_date' => date('Y-m-d', strtotime($date)),
                                    ];
                                } elseif (date('w', strtotime($date)) == $open_hrs[0]->user_day && $key > 0) {
                                    if ($key_count == 0) {
                                        $day_display = 'This ' . date('l', strtotime($date));
                                        //$day_display = 'This ' . date('l',strtotime("Sunday + $date Days"));
                                    } else {
                                        $day_display = date('l', strtotime($date));
                                        //$day_display = 'This ' . date('l',strtotime("Sunday + $date Days"));
                                    }
                                    $collect_dates[$key] = [
                                        'day' => $day_display,
                                        'start_time' => $open_hrs[0]->user_start_time,
                                        'end_time' => $open_hrs[0]->user_end_time,
                                        'break_time' => $open_hrs[0]->break_time,
                                        'date' => date('d M y', strtotime($date)),
                                        'raw_date' => date('Y-m-d', strtotime($date)),
                                    ];
                                    $key_count++;
                                }
                            }  // Holiday condition ends here
                        }  // Holiday Loop ends here
                    }  // IF HOLIDAY ARRAY IS NOT EMPTY
                    else   //IF HOLIDAY ARRAY IS EMPTY
                    {
                        if ($open_hrs[0]->user_day == 7) {
                            $open_hrs[0]->user_day = 0;
                        }

                        if (date('w', strtotime($date)) == $open_hrs[0]->user_day && $key == 0) {
                            $collect_dates[$key] = [
                                'day' => 'Today',
                                'start_time' => $open_hrs[0]->user_start_time,
                                'end_time' => $open_hrs[0]->user_end_time,
                                'break_time' => $open_hrs[0]->break_time,
                                'date' => date('d M y', strtotime($date)),
                                'raw_date' => date('Y-m-d', strtotime($date)),
                            ];
                        } elseif (date('w', strtotime($date)) == $open_hrs[0]->user_day && $key > 0) {
                            if ($key_count == 0) {
                                $day_display = 'This ' . date('l', strtotime($date));
                                //$day_display = 'This ' . date('l',strtotime("Sunday + $date Days"));
                            } else {
                                $day_display = date('l', strtotime($date));
                                //$day_display = 'This ' . date('l',strtotime("Sunday + $date Days"));
                            }
                            $collect_dates[$key] = [
                                'day' => $day_display,
                                'start_time' => $open_hrs[0]->user_start_time,
                                'end_time' => $open_hrs[0]->user_end_time,
                                'break_time' => $open_hrs[0]->break_time,
                                'date' => date('d M y', strtotime($date)),
                                'raw_date' => date('Y-m-d', strtotime($date)),
                            ];
                            $key_count++;
                        }
                    }  // IF HOLIDAY ARRAY IS EMPTY
                }


                usort($collect_dates, function ($a, $b) {
                    return strtotime($a['date']) - strtotime($b['date']);
                });

                $collect_dates = array_unique($collect_dates, SORT_REGULAR);
            } else {
                $open_hrs_status = 0; #unavailable
            }
            #color

            $color_class = [
                'text-info',
                'text-success',
                'text-danger',
                'text-warning',
                'text-purple',
                'text-info',
                'text-success',
                'text-danger',
                'text-warning',
                'text-purple',
            ];
            $color_values = [
                'blue',
                'green',
                'red',
                'yellow',
                'purple',
                'blue',
                'green',
                'red',
                'yellow',
                'purple',
            ];


            #get braintree details
            $braintree_customer_id = User_card::where('user_id', $userId)->pluck('braintree_customer_id');
            if (count($braintree_customer_id) > 0) {
                $braintree_customer_id = $braintree_customer_id[0];
                #get client token

                $clientToken = Braintree_ClientToken::generate([
                    "customerId" => $braintree_customer_id
                ]);

            } else {
                $braintree_customer_id = '';
                $clientToken = "";
            }

            #get the user's id of the product
            $avataricon = User::where('id', Auth::id())->get();
            $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
            $today = date('Y-m-d h:i:s');
            $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
            $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
            $sellerid = Auth::id();
            $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
            $noofsale = count($salecount);
            $systemsetting = system_setting::where('status', '1')->get();
            $wallet = Auth::user()->wallet;
            $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
            return view('users.product.product_page', compact("userId", "product_details", "sellercountry", 'delivery_message_heading', 'main_heading_day', 'timeslot_heading', 'collect_dates', 'open_hrs_status', 'color_class', 'braintree_customer_id', 'clientToken', 'color_values', "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "orders", "systemsetting", "wallet", "decimal_place"));

        } catch (\Exception $e) {
            return $e->getMessage() . ' ' . $e->getLine();
        }
    }

    /*
	placed Order lists
	*/
    public function success(Request $request)
    {
        try {
            $userId = Auth::id();
            $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
            $request->order_id = base64_decode($request->order_id);
            //$request = $request->all();

            $orderdetails = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'deliveryprovider'])
                ->where('id', $request->order_id)
                ->get();

            //echo "<pre>";print_r($orderdetails);die;
            //return view('users.order.my_order',compact('orderdetails'));
            $avataricon = User::where('id', Auth::id())->get();
            $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
            $today = date('Y-m-d h:i:s');
            $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
            $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
            $sellerid = Auth::id();
            $salecount = $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
            $noofsale = count($salecount);
            //dd($request['discount']);
            if (isset($request['discount'])) {
                $discount = base64_decode($request['discount']);
                return view('users.order.success', compact('orderdetails', "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "discount", "decimal_place"));
            } else {
                return view('users.order.success', compact('orderdetails', "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "decimal_place"));
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function my_order(Request $request)
    {

        try {
            $userId = Auth::id();
            $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
            $orderdetails = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link', 'deliveryprovider'])
                ->where('user_id', $userId)->orderby('id', 'desc')
                ->get();

            //echo "<pre>";print_r($orderdetails);die;
            $avataricon = User::where('id', Auth::id())->get();
            $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
            $today = date('Y-m-d h:i:s');
            $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
            $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
            $sellerid = Auth::id();
            $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
            $noofsale = count($salecount);
            $system_setting = system_setting::where('status', '1')->get();
            $return_setting = return_setting::where('status', '1')->get();
            return view('users.order.my_order', compact('orderdetails', "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "decimal_place", "system_setting", "return_setting"));
            //return view('users.order.my_order',compact('orderdetails'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    } #my_order

    /* List all subscriptions  */
    public function my_subscription(Request $request)
    {
        try {
            $userId = Auth::id();
            $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
            $orderdetails = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link', 'deliveryprovider'])
                ->where('user_id', $userId)->where('o_product_type', '3')->orderby('id', 'desc')
                ->get();
            //echo "<pre>";print_r($orderdetails);die;
            $avataricon = User::where('id', Auth::id())->get();
            $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
            $today = date('Y-m-d h:i:s');
            $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
            $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
            $sellerid = Auth::id();
            $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
            $noofsale = count($salecount);
            $system_setting = system_setting::where('status', '1')->get();
            return view('users.subscription.subscription', compact('orderdetails', "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "decimal_place", "system_setting"));
            //return view('users.order.my_order',compact('orderdetails'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function subscribed_users(Request $request)
    {
        try {
            $userId = Auth::id();
            $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
            $orderdetails = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link', 'deliveryprovider'])
                ->where('seller_id', $userId)->where('o_product_type', '3')->orderby('id', 'desc')
                ->get();
            //echo "<pre>";print_r($orderdetails);die;
            $avataricon = User::where('id', Auth::id())->get();
            $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
            $today = date('Y-m-d h:i:s');
            $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
            $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
            $sellerid = Auth::id();
            $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
            $noofsale = count($salecount);
            $system_setting = system_setting::where('status', '1')->get();
            return view('users.subscription.subscribed_users', compact('orderdetails', "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "decimal_place", "system_setting"));
            //return view('users.order.my_order',compact('orderdetails'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    /*
	Sales lists
	*/
    public function my_sales(Request $request)
    {
        try {
            $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
            $seller_id = Auth::id();
            $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
            $today = date('Y-m-d h:i:s');
            $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
            $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])
                ->where('seller_id', $seller_id)->where('o_cancelled', '0')->where('o_collect_status', '0')->where('o_completed', '0')->where('o_returned', '0')->where('o_dispatched', '0')
                ->orderBy('o_collection_time', 'ASC')
                ->get();
            // echo "<pre>";print_r($soldItems);die;
            $avataricon = User::where('id', Auth::id())->get();
            $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
            $sellerid = Auth::id();
            $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
            $noofsale = count($salecount);
            $delivery = deliverie::where('user_id', Auth::user()->id)->get();
            return view('users.sales.my_sales', compact('soldItems', "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "delivery", "decimal_place"));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    } #my_sales

    public function my_sales_ajax(Request $request) // For Filtering in my orders
    {
        $seller_id = Auth::id();
        $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
        if ($request['sort_by'] == 'all') {
            if ($request['fixfilter'] == 'Pending') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_cancelled', '0')->where('o_collect_status', '0')->where('o_completed', '0')->where('o_returned', '0')->where('o_dispatched', '0')
                    ->orderBy('o_collection_time', 'ASC')->get();
            }
            if ($request['fixfilter'] == 'Dispatched') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_cancelled', '0')->where('o_collect_status', '0')->where('o_completed', '0')->where('o_returned', '0')->where('o_dispatched', '1')
                    ->orderBy('o_collection_time', 'ASC')->get();
            }
            if ($request['fixfilter'] == 'Cancelled') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_cancelled', '1')->orderBy('o_collection_time', 'ASC')->get();
            }
            if ($request['fixfilter'] == 'Completed') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_completed', '1')->orderBy('o_collection_time', 'ASC')->get();
            }
        }
        if ($request['sort_by'] == 'item') {
            if ($request['fixfilter'] == 'Pending') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_product_type', '1')->where('o_cancelled', '0')->where('o_collect_status', '0')->where('o_completed', '0')->where('o_returned', '0')->where('o_dispatched', '0')
                    ->orderBy('o_collection_time', 'ASC')->get();
                /*   $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_product_type', '1')->where('o_cancelled', '0')->orderBy('o_collection_time', 'ASC')->get();*/

            }
            if ($request['fixfilter'] == 'Dispatched') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_product_type', '1')->where('o_cancelled', '0')->where('o_collect_status', '0')->where('o_completed', '0')->where('o_returned', '0')->where('o_dispatched', '1')
                    ->orderBy('o_collection_time', 'ASC')->get();
            }
            if ($request['fixfilter'] == 'Cancelled') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_product_type', '1')->where('o_cancelled', '1')->orderBy('o_collection_time', 'ASC')->get();
            }
            if ($request['fixfilter'] == 'Completed') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_product_type', '1')->where('o_completed', '1')->orderBy('o_collection_time', 'ASC')->get();
            }

        }
        if ($request['sort_by'] == 'service') {
            if ($request['fixfilter'] == 'Pending') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_product_type', '2')->where('o_cancelled', '0')->where('o_collect_status', '0')->where('o_completed', '0')->where('o_returned', '0')->orderBy('o_collection_time', 'ASC')->get();
            }
            if ($request['fixfilter'] == 'Dispatched') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_cancelled', '0')->where('o_collect_status', '0')->where('o_completed', '0')->where('o_returned', '0')->where('o_dispatched', '1')->where('o_product_type', '2')
                    ->orderBy('o_collection_time', 'ASC')->get();
            }
            if ($request['fixfilter'] == 'Cancelled') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_product_type', '2')->where('o_cancelled', '1')->orderBy('o_collection_time', 'ASC')->get();
            }
            if ($request['fixfilter'] == 'Completed') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_product_type', '2')->where('o_completed', '1')->orderBy('o_collection_time', 'ASC')->get();
            }

        }
        if ($request['sort_by'] == 'subscription') {
            if ($request['fixfilter'] == 'Pending') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_product_type', '3')->where('o_cancelled', '0')->where('o_collect_status', '0')->where('o_completed', '0')->where('o_returned', '0')->orderBy('o_collection_time', 'ASC')->get();
            }
            if ($request['fixfilter'] == 'Dispatched') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_cancelled', '0')->where('o_collect_status', '0')->where('o_completed', '0')->where('o_returned', '0')->where('o_dispatched', '1')->where('o_product_type', '3')
                    ->orderBy('o_collection_time', 'ASC')->get();
            }
            if ($request['fixfilter'] == 'Cancelled') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_product_type', '3')->where('o_cancelled', '1')->orderBy('o_collection_time', 'ASC')->get();
            }
            if ($request['fixfilter'] == 'Completed') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_product_type', '3')->where('o_completed', '1')->orderBy('o_collection_time', 'ASC')->get();
            }
        }
        if ($request['sort_by'] == 'collection') {
            if ($request['fixfilter'] == 'Pending') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_collection_time', '!=', '')->where('o_product_type', '1')->where('o_cancelled', '0')->where('o_collect_status', '0')->where('o_completed', '0')->where('o_returned', '0')->orderBy('o_collection_time', 'ASC')->get();
            }
            if ($request['fixfilter'] == 'Dispatched') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_cancelled', '0')->where('o_collect_status', '0')->where('o_completed', '0')->where('o_returned', '0')->where('o_dispatched', '1')->where('o_product_type', '1')
                    ->orderBy('o_collection_time', 'ASC')->get();
            }
            if ($request['fixfilter'] == 'Cancelled') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_collection_time', '!=', '')->where('o_product_type', '1')->where('o_cancelled', '1')->orderBy('o_collection_time', 'ASC')
                    ->get();
            }
            if ($request['fixfilter'] == 'Completed') {
                $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_collection_time', '!=', '')->where('o_product_type', '1')->where('o_completed', '1')->orderBy('o_collection_time', 'ASC')
                    ->get();
            }

        }
        if ($request['sort_by'] == 'pending') {
            $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])
                ->where('seller_id', $seller_id)->where('o_cancelled', '0')->where('o_collect_status', '0')->where('o_completed', '0')->where('o_returned', '0')->where('o_dispatched', '0')->orderBy('o_collection_time', 'ASC')
                ->get();
        }
        if ($request['sort_by'] == 'dispatched') {
            $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])->where('seller_id', $seller_id)->where('o_returned', '0')->where('o_dispatched', '1')
                ->orderBy('o_collection_time', 'ASC')->get();
        }
        if ($request['sort_by'] == 'cancelled') {
            $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])
                ->where('seller_id', $seller_id)->where('o_cancelled', '1')
                ->orderBy('o_collection_time', 'ASC')
                ->get();
        }
        if ($request['sort_by'] == 'completed') {
            $soldItems = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link'])
                ->where('seller_id', $seller_id)->where('o_completed', '1')
                ->orderBy('o_collection_time', 'ASC')
                ->get();
        }
        // echo "<pre>";print_r($soldItems);die;
        $avataricon = User::where('id', Auth::id())->get();
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        $sellerid = Auth::id();
        $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
        $noofsale = count($salecount);
        $delivery = deliverie::where('user_id', Auth::user()->id)->get();
        return view('users.sales.my_sales_ajax', compact('soldItems', "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "delivery", "decimal_place"));

    }

    public function return_request(Request $request)
    {
        try {
            $userId = Auth::id();
            $decimal_place = currencies::where('code', Auth::user()->currency_code)->get();
            //$orderdetails = return_history::with(['Order'])->get();
            $orderdetails = Order::with(['sellerDetails', 'userDetails', 'product_type', 'product_details', 'currency', 'tracking_link', 'deliveryprovider'])
                ->where('seller_id', $userId)->orderby('id', 'desc')
                ->get();

            //echo "<pre>";print_r($orderdetails);die;
            $avataricon = User::where('id', Auth::id())->get();
            $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
            $today = date('Y-m-d h:i:s');
            $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
            $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
            $sellerid = Auth::id();
            $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
            $noofsale = count($salecount);
            $system_setting = system_setting::where('status', '1')->get();
            $return_setting = return_setting::where('status', '1')->get();
            return view('users.return.return_request', compact('orderdetails', "avataricon", "daysale", "currency_symbol", "salecount", "noofsale", "decimal_place", "system_setting", "return_setting"));
            //return view('users.order.my_order',compact('orderdetails'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function accept_return_request_inpost(Request $request) // accept return request and provide label or address
    {

    }
    public function accept_return_request(Request $request) // accept return request and provide label or address
    {
        if(Auth::user()->return_address=='')
        {
            return_history::where('id',$request->id)->update(['return_status'=>'1','return_address'=>Auth::user()->street_address_1]);
            $response['status'] = '1';
        }
        else
        {
            return_history::where('id',$request->id)->update(['return_status'=>'1','return_address'=>Auth::user()->return_address]);
            $response['status'] = '1';
        }
        return response()->json($response);


    }
    public function decline_return_request(Request $request) // decline return request
    {
        return_history::where('id',$request->id)->update(['return_status'=>'0']);
        $response['status'] = '1';
        return response()->json($response);
    }
    public function refund_return_request(Request $request)
    {
        return_history::where('id',$request->id)->update(['return_status'=>'3']);
        $return = return_history::where('id',$request->id)->get();
        $order = Order::where('id',$return[0]['order_id'])->get();
        User::where('id',$order[0]['user_id'])->increment('wallet',$order[0]['o_total']);
        Credit_detail::where('order_id',$order[0]['id'])->delete();
        Credit_history::where('order_id',$order[0]['id'])->delete();
        Order::where('id',$order[0]['id'])->update(['o_returned'=>'1','o_returned_date'=>date('Y-m-d H:i:s')]);
        $currency_symbol = currencies::where('code',$order[0]['o_currency'])->get();
        $refundeddate = date('d F Y hA');
        //$response['message'] = $currency_symbol[0]['symbol'].$order[0]['o_total'].' Refunded '.$refundeddate;
        refund_history::create(['user_id'=>$order[0]['user_id'],'order_id'=>$order[0]['id'],'currency_symbol'=>$currency_symbol[0]['symbol'],'amount'=>$order[0]['o_total']]);
        $refundeddate = date('d F Y hA',strtotime(date('Y-m-d H:i:s')));
        $response['message'] = 'We have refunded your account. In this case, there is no need to send us anything back.';
        $response['status'] = '1';
        $response['message2'] = 'Return Request successful '.$currency_symbol[0]['symbol'].$order[0]['o_total'].'Refunded '.$refundeddate;
        return response()->json($response);
    }

    public function inpost_label(Request $request)
    {
        $order = Order::where('id', '=', $request->id)->get();
        $buyer_information = User::where('id', $order[0]['user_id'])->get();
        $customerEmail = 'antony@contact25.com';


        // -- TEST TOKEN / LOCATION -- ///
        /*$token = 'fcefb034514b32722173c0480f05d107c6e4cd5ac8e39700f17cf96f2371ba3d';
			$location = 'stage-api-uk';*/
        $token = '4980a88399ab8c6724da8310bf18bca94b5317631a7f9c3102b9d1751f47d6e7';
        $location = 'api-uk';
        // $token = 'fcefb034514b32722173c0480f05d107c6e4cd5ac8e39700f17cf96f2371ba3d';
        //$location = 'stage-api-uk';

        //$baseUrl = 'https://api-uk.easypack24.net/v4';
        $baseUrl = 'https://' . $location . '.easypack24.net/v4/';
        //$path = '/customers/'.$customerEmail.'/parcels';

        $data = array
        (
            'sender_phone' => Auth::user()->contact_no,
            'customer_reference' => $request->id,
            'parcel' => array('size' => 'A'),
            'sender_email' => Auth::user()->email,
            'return_address' => array(
                'building_no' => '56',
                'city' => 'Leeds',
                'first_name' => 'Antony',
                'last_name' => 'Vila',
                'post_code' => 'LS16 5NH',
                'street' => 'Weetwood Lane'

            ),

            'target_address' => array(

                'building_no' => $buyer_information[0]['street_address1'],
                'city' => $buyer_information[0]['city'],
                'company_name' => $buyer_information[0]['name'],
                'email' => $buyer_information[0]['email'],
                'target_machine_id' => $buyer_information[0]['name'],
                'flat_no' => null,
                'last_name' => null,
                'receiver_phone' => $buyer_information[0]['contact_no'],
                'post_code' => $buyer_information[0]['pincode'],
                'province' => null,
                'street' => $buyer_information[0]['street_address1']

            ),

        );
        //dd(json_encode($data));
#die(var_dump($data));


        $baseUrl = 'https://' . $location . '.easypack24.net/v4/';
        //$path = '/customers/'.$customerEmail.'/parcels';
        $path = '/customers/antony@contact25.com/returns';
        $headers = array("Authorization: Bearer $token", "Content-Type: application/json");

        $ch = curl_init($baseUrl . $path);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $rawResponse = curl_exec($ch);
        //print_r(json_decode($rawResponse));
        curl_close($ch);
        $inpostid[] = json_decode($rawResponse);
        //echo '</pre>';
        //print_r($inpostid);
        foreach ($inpostid as $inpostdetail) {
            //dd($inpostdetail->_embedded->parcel->id);
            // dd($inpostdetail);
            dd($inpostdetail);
            if ($inpostdetail->id != '') {
                Order::where('id', '=', $request->id)->update(['o_dispatched' => '1', 'o_dispatched_date' => date('Y-m-d H:i:s'), 'o_parcel_id' => $inpostdetail->_embedded->parcel->id]);
                orders_log::create(['order_id' => $request->id, 'order_type' => 'item']);
                $path = '/parcels/' . $inpostdetail->_embedded->parcel->id . '/sticker';
                $ch = curl_init($baseUrl . $path);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $rawResponse = curl_exec($ch);
                dd($rawResponse);
                $filename = $inpostdetail->id . '_1.pdf';   // stickerid_1 -> parcel label
                file_put_contents(public_path('uploads/label') . '/' . $filename, base64_decode($rawResponse));
                curl_close($ch);
                file_get_contents(public_path('uploads/label') . '/' . $filename, base64_decode($rawResponse));
            }
        }


        /* $response = Curl::to($baseUrl.$path)
        ->withData($data)
        ->post();*/
        // dd($response);
    }

    public function cancel_label(Request $request)
    {
        $token = '4980a88399ab8c6724da8310bf18bca94b5317631a7f9c3102b9d1751f47d6e7';
        $location = 'api-uk';
        $parcelId = $request['parcel_id'];
        $baseUrl = 'https://' . $location . '.easypack24.net/v4/';
        $path = '/parcels/' . $parcelId . '/cancel';
        $headers = array(
            'Authorization: Bearer ' . $token
        );
        $ch = curl_init($baseUrl . $path);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $rawResponse = curl_exec($ch);
        curl_close($ch);
        dd(json_decode($rawResponse));
    }

    public function view_label(Request $request)
    {
        $token = '4980a88399ab8c6724da8310bf18bca94b5317631a7f9c3102b9d1751f47d6e7';
        $location = 'api-uk';
        $parcelId = $request['parcel_id'];
        $baseUrl = 'https://' . $location . '.easypack24.net/v4/';
        $path = '/parcels/' . $parcelId . '/sticker';
        $headers = array(
            "Authorization: Bearer $token"
        );
        $ch = curl_init() or die (curl_error($ch));
        $ch = curl_init($baseUrl . $path);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $rawResponse = curl_exec($ch);
        $filename = $parcelId . '_1.pdf';
        file_put_contents(public_path('uploads/label') . '/' . $filename, base64_decode($rawResponse));
        curl_close($ch);

    }

    public function distance($sellerid, $buyerid)
    {
        $sellerlocation = User::where('id', $sellerid)->get();
        $buyerlocation = User::where('id', $buyerid)->get();

        $response = Curl::to("https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $sellerlocation[0]['lat'] . "," . $sellerlocation[0]['lng'] . "&destinations=" . $buyerlocation[0]['lat'] . "," . $buyerlocation[0]['lng'] . "&departure_time=now&key=AIzaSyD08Emygz5W4HKOZXvogXKb5zYjA8ZRMaQ")
            ->get();
        //$response = Curl::to('http://www.foo.com/bar')
        //->withData( array( 'foz' => 'baz' ) )
        //->post();


        dd($response);

        /*$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$sellerlocation[0]['lat'].",".$sellerlocation[0]['lng']."&destinations=".$buyerlocation[0]['lat'].",".$buyerlocation[0]['lng']."&departure_time=now&key=AIzaSyD08Emygz5W4HKOZXvogXKb5zYjA8ZRMaQ";

		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		    $response = curl_exec($ch);
		    curl_close($ch);
		    $response_a = json_decode($response, true);
		    $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
		    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];


		    dd($response_a);*/
    }

    public function update_box_preference(Request $request)
    {

        $updateval = User::where('id', Auth::user()->id)->update(['box_preference' => $request->box_preference]);
        if (!empty($updateval)) {
            $response['success'] = 1;
            return response()->json($response);
        } else {
            $response['success'] = 0;
            $response['message'] = 'Error';
            return response()->json($response);
        }
    }

    public function find_friend(Request $request)
    {
        $country = Auth::user()->country;
        $friendsearch = User::Where('email', 'like', '%' . $request->email . '%')->Orwhere('contact_no', 'like', '%' . $request->email . '%')->where('country', $country)->get();
        $response['message'] = '';
        if (count($friendsearch) > 0) {
            $response['success'] = 1;
            foreach ($friendsearch as $search) {
                if ($search->id != Auth::user()->id) {
                    $friendproduct = Product::where('user_id', $search->id)->where('p_quantity', '!=', '0')->get();
                    $response['message'] .= '<tr id="friend' . $search->id . '"><td class="txt-oflo">' . $search->name . '<span class="badge badge-warning badge-pill" style="cursor: pointer;"><i class="fas fa-user-plus"></i> ' . count($friendproduct) . '</span></td>';
                    $friendrequestcheck = friend::where('friend_id_2', $search->id)->where('friend_id_1', Auth::user()->id)->get();
                    if (count($friendrequestcheck) == '0') {
                        $friendrequestcheck = friend::where('friend_id_1', $search->id)->where('friend_id_2', Auth::user()->id)->get();
                    }
                    /*$friendrequestcheck = friend::where(function ($query) {
									    $query->where('friend_id_2', $search->id)
									        ->where('friend_id_1',Auth::user()->id);
									})->orWhere(function($query) {
									    $query->where('friend_id_1', $search->id)
									        ->where('friend_id_2',Auth::user()->id);
									})->get();*/

                    if ($search->friend_status == '1' && count($friendrequestcheck) == '0') {
                        $response['message'] .= '<td><span class="text-success" id="sendbuttontext' . $search->id . '"><button type="button" class="btn btn-secondary btn-rounded" id="searchfriendrequest' . $search->id . '" onclick="sendfriendrequest(' . $search->id . ')"> <i class="far fa-heart"></i> Invite</button> / <button type="button" class="btn btn-secondary btn-rounded" id="ignorefriend' . $search->id . '" onclick="removefriend(' . $search->id . ')"> <i class="fa fa-times"></i> Ignore</button></span></td>';
                    }
                    if (count($friendrequestcheck) > '0' && $friendrequestcheck[0]['status'] == '0') {
                        $response['message'] .= '<td><span class="text-success" id="sendbuttontext' . $search->id . '"><button type="button" class="btn btn-secondary btn-rounded"><i class="far fa-heart"></i> Pending</button></span></td>';
                    }
                    if (count($friendrequestcheck) > '0' && $friendrequestcheck[0]['status'] == '1') {
                        $response['message'] .= '<td><span class="text-success" id="sendbuttontext' . $search->id . '"><button type="button" class="btn btn-secondary btn-rounded"><i class="far fa-heart"></i> Friends</button></span></td>';
                    }
                    $response['message'] .= '</tr>';
                }
            }
        } else {
            $response['success'] = 1;
            $response['message'] .= '<tr><td></td><td><p style=text-align:center></p></td></tr>';
        }

        return response()->json($response);
    }

    public function send_friend_request(Request $request)
    {
        $friend_id_1 = Auth::user()->id;
        $friend_id_2 = $request->senderid;
        $inserval = friend::create(['friend_id_1' => $friend_id_1,
            'friend_id_2' => $friend_id_2,
            'status' => '0'])->id;
        if (!empty($inserval)) {
            $response['success'] = 1;
            $response['message'] = '<button type="button" class="btn btn-secondary btn-rounded"><i class="far fa-heart"></i> Pending</button>';
        } else {
            $response['success'] = 0;
        }
        return response()->json($response);

    }

    public function accept_friend_request(Request $request)
    {
        $updateval = friend::where('id', $request->id)->update(['status' => '1']);
        if (!empty($updateval)) {
            $response['success'] = 1;
            $response['message'] = 'You are now friends';
        } else {
            $response['success'] = 0;
            $response['message'] = 'Error';
        }
        return response()->json($response);
    }

    public function delete_friend_request(Request $request)
    {
        $updateval = friend::where('id', $request->id)->delete();
        if (!empty($updateval)) {
            User::where('id', $request->friend_id_1)->increment('reject_count', 1);
            $response['success'] = 1;
            $response['message'] = 'Friend Request Removed';
        } else {
            $response['success'] = 0;
            $response['message'] = 'Error';
        }
        return response()->json($response);
    }

    public function create_group(Request $request)
    {
        $friendlist = $request->friend;
        if ($friendlist != '') {
            $users = implode(',', $friendlist);
            $user_id = Auth::user()->id;
            $group_name = $request->group_name;
            $insertval = friend_group::create(['user_id' => $user_id,
                'group_name' => $group_name,
                'users' => $users]);
            if (!empty($insertval)) {
                $response['status'] = 0;
                return back();
            } else {
                return back();
            }


        } else {
            $response['status'] = 0;
            return back();
        }
    }

    public function delete_group(Request $request)
    {
        $delete = friend_group::where('id', $request->id)->delete();
        if (!empty($delete)) {
            $response['success'] = '1';

        } else {
            $response['success'] = '0';
            $response['message'] = 'Error while deleting data';
        }
        return response()->json($response);
    }

    public function terms()
    {
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
        $noofsale = count($salecount);
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');

        return view('users.terms', compact("currency_symbol", "daysale", "noofsale", "salecount"));
    }
    public function communication(Request $request)
    {
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
        $noofsale = count($salecount);
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');
        return view('users.Chat.communication', compact("currency_symbol", "daysale", "noofsale", "salecount"));
    }

    public function privacy()
    {
        $prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
        $today = date('Y-m-d h:i:s');
        $salecount = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.o_completed', '=', '0')->where('orders.seller_id', Auth::id())->get();
        $noofsale = count($salecount);
        $currency_symbol = DB::table('users')->join('currencies', 'currencies.code', '=', 'users.currency_code')->where('users.id', '=', Auth::user()->id)->get();
        $daysale = DB::table('orders')->join('products', 'products.id', '=', 'orders.o_product_id')->where('orders.seller_id', Auth::id())->where('orders.created_at', '>=', $prev_date)->where('orders.created_at', '<=', $today)->where('orders.o_completed', '=', '1')->sum('products.p_selling_price');

        return view('users.privacy', compact("currency_symbol", "daysale", "noofsale", "salecount"));

    }

    public function cancelorder(Request $request)
    {
        /* $start_date = date('Y-m-01 00:00:01', strtotime('-1 months'));
         $end_date = date('Y-m-t 00:00:01', strtotime('-1 months'));
         $salecount = Order::where('seller_id', Auth::user()->id)->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->get();
        if (count($salecount) > '50')  // If sale volume is greater than 50
         {
             Order::where('id', $request->id)->update(['o_cancelled' => '1']);
             $limit = count($salecount) * 0.10; // calculating limit for suspending account
             $reason = cancel_orders_reason::create(['user_id' => Auth::user()->id,
                 'order_id' => $request->id,
                 'reason' => $request->reason]);
             User::where('id', Auth::user()->id)->increment('sale_cancel_count', 1);
             if (Auth::user()->sale_cancel_count >= $limit) {
                 User::where('id', Auth::user()->id)->update(['active_status' => '0']);
                 $response['status'] = '2';
                 $response['message'] = url('/logout');
                 return response()->json($response);
             } else {
                 $response['status'] = '1';
                 $response['message'] = 'Order Cancelled Successfully';
                 return response()->json($response);
             }

         } else    // If sale volume is less than 50
         {
             Order::where('id', $request->id)->update(['o_cancelled' => '1']);
             if (Auth::user()->sale_cancel_count > '3') {
                 User::where('id', Auth::user()->id)->update(['active_status' => '0']);
                 $response['status'] = '2';
                 $response['message'] = url('/logout');
                 return response()->json($response);
             } else {
                 User::where('id', Auth::user()->id)->increment('sale_cancel_count', 1);
                 cancel_orders_reason::create(['user_id' => Auth::user()->id,
                     'order_id' => $request->id,
                     'reason' => $request->reason]);
                 $response['status'] = '1';
                 $response['message'] = 'Order Cancelled Successfully';
                 return response()->json($response);
             }
         }*/
        $systemsetting = system_setting::where('status', '1')->get();
        $usercanceledorder = Order::where('seller_id', Auth::user()->id)->where('o_cancelled', '1')->get();
        $usertotalorders = Order::where('seller_id', Auth::user()->id)->where(function ($query) {
            $query->orwhere('o_completed', '1')
                ->orwhere('o_delivered', '1');
        })->get();
        $claiminglimit = $systemsetting[0]['product_cancel_limit_seller'] / 100;  // TO calculate the percentage
        $limit = count($usertotalorders) * $claiminglimit;  // To calculate the limit for claiming not delivered
        //dd($usernonclaimedorder);
        if (count($usercanceledorder) <= intval($limit))  // if limit is greater than total no of claimed not delivered then claim it
        {
            $order = Order::where('id', $request['id'])->get();
            User::where('id', $order[0]['user_id'])->increment('wallet', $order[0]['o_total']);
            Order::where('id', $request['id'])->update(['o_cancelled' => '1', 'o_cancelled_date' => date('Y-m-d H:i:s')]);
            orders_log::create(['order_id' => $request['id'], 'order_type' => 'item']);
            Credit_detail::where('order_id', $order[0]['id'])->delete();
            Credit_history::where('order_id', $order[0]['id'])->delete();
            $reason = cancel_orders_reason::create(['user_id' => Auth::user()->id,
                'order_id' => $request->id,
                'reason' => $request->reason]);
            $response['status'] = '1';
            $response['message'] = 'Order Cancelled';
            return response()->json($response);
        } else  // show the message that claiming limit is exceed
        {
            $response['status'] = '0';
            $response['message'] = 'You cannot cancel order because the limit is exceeded';
            return response()->json($response);
        }
    }

    public function completeorder(Request $request)
    {
        $completeorder = Order::where('id', $request->id)->update(['o_completed' => '1', 'o_completed_on' => date('Y-m-d h:i:s')]);
        orders_log::create(['order_id' => $request->id, 'order_type' => 'service']);
        if (!empty($completeorder)) {

            $response['success'] = '1';
            return response()->json($response);
        } else {
            $response['success'] = '0';
            $response['message'] = 'Error while updating data';
            return response()->json($response);
        }
    }

    public function collectorder(Request $request)
    {
        Order::where('id', $request->id)->update(['o_collect_status' => '1']);
        orders_log::create(['order_id' => $request->id, 'order_type' => 'item']);
        $response['success'] = '1';
        return response()->json($response);
    }
    public function buyerreturnorder(Request $request)
    {
        $returnsetting = return_setting::where('status','1')->get();
        $returnlimit = $returnsetting[0]['product_returning_limit']/100;  // TO calculate the percentage
        $totalsucessorders =  $usertotalorders = Order::where('user_id',Auth::user()->id)->where(function ($query) {
            $query->orwhere('o_collect_status','1')
                ->orwhere('o_completed','1')
                ->orwhere('o_delivered','1');
        })->get();
        $totalreturned = return_history::where('buyer_id',Auth::user()->id)->get();
        $usertotalorders = return_history::where('order_id',$request->id)->get();  // to fetch total return request by buyer
        $userreturnedorder = Order::where('user_id',Auth::user()->id)->where(function ($query) {
            $query->orwhere('o_returned','1');
        })->get();  // to fetch total returned item by buyer
        $limit = count($totalsucessorders) * $returnlimit;  // To calculate the limit for claiming not delivered
        if($request->return_type=='0' || $request->return_reason=='')
        {
            $response['status'] = '0';
            $response['message'] = 'Please Enter All The Fields';
        }
        else if(count($totalreturned) >= $limit)
        {
            $response['status'] = '0';
            $response['message'] = 'Your return limit is exceed';
        }
        else
        {
            $returncheck = return_history::where('order_id',$request->id)->get();
            $order = Order::where('id',$request->id)->get();
            if(count($returncheck) > '0') // Means user already requested for return
            {
                $response['status'] = '0';
                if($returncheck[0]['return_status']=='0')
                {
                    $response['message'] = 'You have already placed a return request and its declined';
                }
            }
            else
            {

                return_history::create(['buyer_id'=>Auth::user()->id,'order_id'=>$request->id,'return_type'=>$request->return_type,'return_reason'=>$request->return_reason,'return_status'=>'2']);
                if($request->return_type=='1') // means case for returnless refund
                {
                    $returnlessrefundlimit = User::where('id',$order[0]['seller_id'])->get();
                    if($returnlessrefundlimit[0]['refundrequestdamage_status']=='1' && $order[0]['o_total']<=$returnlessrefundlimit[0]['refundrequestdamage_value']) // means amount is less than auto return refund for damaged item
                    {
                        return_history::where('order_id',$request->id)->update(['return_status'=>'3']);
                        User::where('id',$order[0]['user_id'])->increment('wallet',$order[0]['o_total']);
                        Credit_detail::where('order_id',$request->id)->delete();
                        Credit_history::where('order_id',$request->id)->delete();
                        Order::where('id',$request->id)->update(['o_returned'=>'1','o_returned_date'=>date('Y-m-d H:i:s')]);
                        $currency_symbol = currencies::where('code',$order[0]['o_currency'])->get();
                        $refundeddate = date('d F Y hA');
                        //$response['message'] = $currency_symbol[0]['symbol'].$order[0]['o_total'].' Refunded '.$refundeddate;
                       refund_history::create(['user_id'=>Auth::user()->id,'order_id'=>$request->id,'currency_symbol'=>Auth::user()->currency_symbol,'amount'=>$order[0]['o_total']]);
                        $refundeddate = date('d F Y hA',strtotime(date('Y-m-d H:i:s')));
                        $response['message'] = 'We have refunded your account. In this case, there is no need to send us anything back.';
                        $response['status'] = '1';
                        $response['message2'] = '<button class="btn btn-success" id="status"'.$request->id.'>Return Request successful '.$currency_symbol[0]['symbol'].$order[0]['o_total'].'Refunded '.$refundeddate.'</button>';
                    }
                    else  // means amount is greater than auto return refund limit for damaged item
                    {
                        return_history::where('order_id',$request->id)->update(['return_status'=>'2']);
                        //User::where('id',$order[0]['user_id'])->increment('wallet',$order[0]['o_total']);
                       // Credit_detail::where('order_id',$request->id)->delete();
                       // Credit_history::where('order_id',$request->id)->delete();
                       // Order::where('id',$request->id)->update(['o_returned'=>'1','o_returned_date'=>date('Y-m-d H:i:s')]);
                      //  $currency_symbol = currencies::where('code',$order[0]['o_currency'])->get();
                        $refundeddate = date('d F Y hA');
                        //$response['message'] = $currency_symbol[0]['symbol'].$order[0]['o_total'].' Refunded '.$refundeddate;
                       // refund_history::create(['user_id'=>Auth::user()->id,'order_id'=>$request->id,'currency_symbol'=>Auth::user()->currency_symbol,'amount'=>$order[0]['o_total']]);
                      //  $refundeddate = date('d F Y hA',strtotime(date('Y-m-d H:i:s')));
                        $response['message'] = 'Request Pending From Seller';
                        $response['status'] = '2';
                       /* $response['message2'] = '<button class="btn  btn-success" id="status"'.$request->id.'>Return Request successful '.$currency_symbol[0]['symbol'].$order[0]['o_total'].'Refunded '.$refundeddate.'</button>';
                        $response['status'] = '2';*/
                    }
                }
                if($request->return_type=='2') // means case for return refund
                {
                    $returnlessrefundlimit = User::where('id',$order[0]['seller_id'])->get();
                    if($returnlessrefundlimit[0]['refundrequest_status']=='1' && $order[0]['o_total']<=$returnlessrefundlimit[0]['refundrequest_value']) // means amount is less than auto return refund for  item
                    {
                        return_history::where('order_id',$request->id)->update(['return_status'=>'3']);
                        User::where('id',$order[0]['user_id'])->increment('wallet',$order[0]['o_total']);
                        Credit_detail::where('order_id',$request->id)->delete();
                        Credit_history::where('order_id',$request->id)->delete();
                        Order::where('id',$request->id)->update(['o_returned'=>'1','o_returned_date'=>date('Y-m-d H:i:s')]);
                        $currency_symbol = currencies::where('code',$order[0]['o_currency'])->get();
                        $refundeddate = date('d F Y hA');
                        //$response['message'] = $currency_symbol[0]['symbol'].$order[0]['o_total'].' Refunded '.$refundeddate;
                        refund_history::create(['user_id'=>Auth::user()->id,'order_id'=>$request->id,'currency_symbol'=>Auth::user()->currency_symbol,'amount'=>$order[0]['o_total']]);
                        $refundeddate = date('d F Y hA',strtotime(date('Y-m-d H:i:s')));
                        $response['message'] = 'We have refunded your account. In this case, there is no need to send us anything back.';
                        $response['status'] = '1';
                        $response['message2'] = '<button class="btn btn-success" id="status"'.$request->id.'>Return Request successful '.$currency_symbol[0]['symbol'].$order[0]['o_total'].'Refunded '.$refundeddate.'</button>';
                    }
                    else
                    {
                        return_history::where('order_id',$request->id)->update(['return_status'=>'2']);
                        $response['status'] = '2';
                        $response['message']= 'Request Pending From Seller';
                    }
                }

            }
        }
        //Order::where('id', $request->id)->update(['o_cancelled' => '1']);
        $order = Order::where('id',$request->id)->get();
        //Credit_detail::where('order_id',$request->id)->delete();
        //Credit_history::where('order_id',$request->id)->delete();
        /*cancel_orders_reason::create(['user_id' => Auth::user()->id,
            'order_id' => $request->id,
            'reason' => $request->reason]);*/
        //$productid = Order::where('id', $request->id)->get();
       /* $old_quantity = Product::where('id', $productid[0]['o_product_id'])->value('p_quantity');
        $new_quantity = $old_quantity + $productid[0]['o_quantity'];
        Product::where('id', $productid[0]['o_product_id'])->update(['p_quantity' => $new_quantity]);
        User::where('id',$order[0]['id'])->increment('wallet',$order[0]['o_total']);*/
        return response()->json($response);
    }
    public function buyercancelorder(Request $request)
    {
        Order::where('id', $request->id)->update(['o_cancelled' => '1']);
        orders_log::where('order_id',$request->id)->update(['order_id'=>$request->id,'order_type'=>'item']);
        $order = Order::where('id',$request->id)->get();
        Credit_detail::where('order_id',$request->id)->delete();
        Credit_history::where('order_id',$request->id)->delete();
        cancel_orders_reason::create(['user_id' => Auth::user()->id,
            'order_id' => $request->id,
            'reason' => $request->reason]);
        $productid = Order::where('id', $request->id)->get();
        $old_quantity = Product::where('id', $productid[0]['o_product_id'])->value('p_quantity');
        $new_quantity = $old_quantity + $productid[0]['o_quantity'];
        Product::where('id', $productid[0]['o_product_id'])->update(['p_quantity' => $new_quantity]);
        User::where('id',$order[0]['id'])->increment('wallet',$order[0]['o_total']);

        $response['status'] = '1';
        $response['message'] = 'Cancelled Successfully';
        return response()->json($response);
    }
    public function buyercancelsubscription(Request $request)
    {
        Order::where('id', $request->id)->update(['subs_status' => '0']);
        $order = Order::where('id',$request->id)->get();
        //Credit_detail::where('order_id',$request->id)->delete();
        //Credit_history::where('order_id',$request->id)->delete();
        //cancel_orders_reason::create(['user_id' => Auth::user()->id,
           // 'order_id' => $request->id,
          //  'reason' => $request->reason]);
        $productid = Order::where('id', $request->id)->get();
        $old_quantity = Product::where('id', $productid[0]['o_product_id'])->value('p_quantity');
        $new_quantity = $old_quantity + $productid[0]['o_quantity'];
        Product::where('id', $productid[0]['o_product_id'])->update(['p_quantity' => $new_quantity]);
        //User::where('id',$order[0]['id'])->increment('wallet',$order[0]['o_total']);

        $response['status'] = '1';
        $response['message'] = 'Subscription Cancelled Successfully';
        return response()->json($response);
    }
    public function buyerclaimdelivered(Request $request)
    {
        $systemsetting = system_setting::where('status','1')->get();
        //$usernonclaimedorder = Order::where('user_id',Auth::user()->id)->where('o_not_delivered','1')->get();
        //$usertotalorders = Order::where('user_id',Auth::user()->id)->orwhere('o_collect_status','1')->orwhere('o_completed','1')->orwhere('o_delivered','1')->get();
       Order::where('id',$request['id'])->update(['o_delivered'=>'1','o_delivered_date'=>date('Y-m-d H:i:s')]);
       orders_log::create(['order_id'=>$request['id'],'order_type'=>'item']);
       $response['status'] = '1';
       return response()->json($response);
    }
    public function claimitnotdelivered(Request $request)
    {
        $systemsetting = system_setting::where('status','1')->get();
        $usernonclaimedorder = Order::where('user_id',Auth::user()->id)->where('o_not_delivered','1')->get();
        $usertotalorders = Order::where('user_id',Auth::user()->id)->where(function ($query) {
            $query->orwhere('o_collect_status','1')
                ->orwhere('o_completed','1')
                ->orwhere('o_delivered','1');
        })->get();
        $claiminglimit = $systemsetting[0]['product_not_delivered_limit']/100;  // TO calculate the percentage
        $limit = count($usertotalorders) * $claiminglimit;  // To calculate the limit for claiming not delivered
        //dd($usernonclaimedorder);
        if(count($usernonclaimedorder) <= intval($limit))  // if limit is greater than total no of claimed not delivered then claim it
        {
            $order = Order::where('id',$request['id'])->get();
            User::where('id',$order[0]['user_id'])->increment('wallet',$order[0]['o_total']);
            Order::where('id',$request['id'])->update(['o_not_delivered'=>'1','o_not_delivered_date'=>date('Y-m-d H:i:s')]);
            Credit_detail::where('order_id',$order[0]['id'])->delete();
            Credit_history::where('order_id',$order[0]['id'])->delete();
            $currency_symbol = currencies::where('code',$order[0]['o_currency'])->get();
            refund_history::create(['user_id'=>$order[0]['user_id'],'order_id'=>$order[0]['id'],'currency_symbol'=>$currency_symbol[0]['symbol'],'amount'=>$order[0]['o_total'],'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
            $response['status'] = '1';
            $response['message'] = 'Order Claimed as not delivered and refund initiated in your wallet';
            $refundeddate = date('d F Y hA');
            $response['message2'] = 'Not Delivered '.$currency_symbol[0]['symbol'].$order[0]['o_total'].' Refunded '.$refundeddate;
            return response()->json($response);
        }
        else  // show the message that claiming limit is exceed
        {
            $response['status'] = '0';
            $response['message'] = 'You cannot claim as not delivered because the limit is exceeded';
            return response()->json($response);
        }
    }

    public function update_tracking_link(Request $request)
    {
        /*$image = $request->file('image');
       $name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('uploads/products');
        $image->move($destinationPath, $name);*/
        //$this->save();
        //$parsedText = Ocr::recognize($destinationPath.'/'.$name);
        //$parsedText = OCR::baidu()->idcard($destinationPath.'/'.$name);
        //\OCR::scan($destinationPath.'/'.$name);
        /*$response = $ocr->parseImageFile($destinationPath.'/'.$name);
		print_r($response);
		die;*/
        $o_tracking_link = $request['o_tracking_link'];
        $o_tracking_no = $request['o_tracking_no'];
        $id = $request['id'];
        if (!empty($o_tracking_no) && !empty($o_tracking_link)) {
            $updatetrackingno = Order::where('id', $id)->update(['o_tracking_no' => $o_tracking_no, 'o_tracking_link' => $o_tracking_link, 'o_dispatched' => '1', 'o_dispatched_date' => date('Y-m-d h:i:s')]);
            orders_log::create(['order_id'=>$id,'order_type'=>'item']);
            $response['success'] = '1';
            $response['tracking_no'] = $o_tracking_no;
            return response()->json($response);
        } else {
            $response['success'] = '0';
            $response['message'] = 'Please Enter required information';
            return response()->json($response);
        }
    }

}
