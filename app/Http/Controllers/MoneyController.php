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
use Braintree_MerchantAccount;
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
use App\merchant_detail;
use Carbon;
use Session;
use App\Traits\TimezoneTrait;
use Ixudra\Curl\Facades\Curl;
use DateTime;
use JFuentesTgn\OcrSpace\OcrAPI;

class MoneyController extends Controller
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
    /* My Money */
    public function my_money(Request $request)
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
        $system_setting = system_setting::where('status','1')->get();
        $pending_money = Credit_detail::where('user_id',Auth::user()->id)->where('status','0')->with(['currencysymbol'])->get();
        $available_money = User::where('id',$user_id)->get();
        $paid_details = paid_detail::where('user_id',$user_id)->get();
        $merchant_detail = merchant_detail::where('user_id',$user_id)->get();
        $userdetails = User::where('id',$user_id)->get();
        //echo"<pre>";print_r($product_list);die;

        return view('users.money.my_money', compact("product_list", "user_id", "avataricon", "daysale", "currency_symbol", "country", "salecount", "noofsale", "decimal_place","system_setting","pending_money","available_money","paid_details","merchant_detail","userdetails"));
    }
    public function create_merchant_account(Request $request)
    {
        $merchantAccountParams = [
            'individual' => [
                'firstName' => $request['firstName'],
                'lastName' => $request['lastName'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'dateOfBirth' => $request['dateOfBirth'],
                'ssn' => $request['ssn'],
                'address' => [
                    'streetAddress' => $request['streetAddress'],
                    'locality' => $request['locality'],
                    'region' => 'IN',
                    'postalCode' => $request['postalCode']
                ]
            ],
            'business' => [
                'legalName' => $request['legalName'],
                'dbaName' => $request['dbaName'],
                'taxId' => $request['taxId'],
                'address' => [
                    'streetAddress' => $request['streetAddress2'],
                    'locality' => $request['locality2'],
                    'region' => 'IN',
                    'postalCode' => $request['postalCode2']
                ]
            ],
            'funding' => [
                'descriptor' => '',
                'destination' => $request['destination'],
                'email' => $request['email2'],
                'mobilePhone' => $request['mobilePhone'],
                'accountNumber' => $request['accountNumber'],
                'routingNumber' => $request['routingNumber']
            ],
            'tosAccepted' => true,
            'masterMerchantAccountId' => 'contact25_GBP',
            'id' => ""
        ];
        $result = Braintree_MerchantAccount::create($merchantAccountParams);
        dd($result);
        $response['status'] = $result;

        return response()->json($response);
    }
}
