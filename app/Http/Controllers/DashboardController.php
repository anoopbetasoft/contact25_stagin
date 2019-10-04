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
use Carbon;
use Session;
use App\Traits\TimezoneTrait;
use Ixudra\Curl\Facades\Curl;
use DateTime;


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
		$this->middleware(['auth', 'verified','checkLoginRole']);
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

		$avataricon =User::where('id',Auth::id())->get();
			/*$date = date('Y-m-d h:s:i');
		echo date('Y-m-d h:s:i', strtotime($date, '+ 30 day'));
		echo $date;*/
		$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
		$today = date('Y-m-d h:i:s');
		//$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
		$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');
		$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
		$sellerid = Auth::id();
		$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
		$noofsale = count($salecount);
		$product = Product::get();
		$friendstuff = Product::with(['userDet', 'product_type','product_sell_options','product_lend_options','product_service_option','product_subs_option','currency','friend'])
			       ->whereHas('userDet', function($q) {
			       $q->where('country','=',Auth::user()->country);
			})->whereHas('friend', function($q){
				$q->where('friend_id_2',Auth::user()->id)->where('status','1');
			})->OrwhereHas('friend2', function($q){
				$q->where('friend_id_1',Auth::user()->id)->where('status','1');
			})
			->where('user_id','!=',Auth::user()->id)
			->where('p_slug','!=','')
			->orderBy('created_at','desc')->get();	 	// ## Friend Products 
		$countryproducts = Product::with(['userDet', 'product_type','product_sell_options','product_lend_options','product_service_option','product_subs_option','currency'])
			       ->whereHas('userDet', function($q) {
			       $q->where('country','=',Auth::user()->country);
			})->get();  		// ## Country Products
		$friendrequest = friend::where('friend_id_2',Auth::user()->id)->where('status','0')->with(['user'])->get();
		$friendcount = friend::where('friend_id_1',Auth::user()->id)->Orwhere('friend_id_2',Auth::user()->id)->where('status','1')->get();
		return view('users.dashboard',compact("avataricon","daysale","currency_symbol","salecount","noofsale","friendstuff","countryproducts","friendrequest","friendcount"));
	}

	/*
	Product Listing
	*/
	public function product_list()
	{
		$user_id = Auth::id();
		$country = Auth::user()->country;
		$product_list = Product::where('user_id',$user_id)->orderBy('created_at','desc')->paginate(15);
		$avataricon =User::where('id',Auth::id())->get();
		$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
		$today = date('Y-m-d h:i:s');
		//$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
		$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');
		$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
		$sellerid = Auth::id();
		$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
		$noofsale = count($salecount);
		//echo"<pre>";print_r($product_list);die;
		return view('users.product.product_list', compact("product_list","user_id","avataricon","daysale","currency_symbol","country","salecount","noofsale"));
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
		$avataricon =User::where('id',Auth::id())->get();
		$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
		$today = date('Y-m-d h:i:s');
		//$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
		$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');
		$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
		$sellerid = Auth::id();
		$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
		$noofsale = count($salecount);
    	return view('users.product.product_add',compact("p_types","p_sell_to","p_items_option","p_service_option","p_subscription_option","p_service_option","p_room","avataricon","daysale","currency_symbol","salecount","noofsale"));
		
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
		$friend_group = friend_group::where('user_id',Auth::user()->id)->get();
		#Get Friend Group Details
		$p_room = P_room::with(['product','box'])->where('user_id',Auth::id())->get();
		$avataricon =User::where('id',Auth::id())->get();
		$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
		$today = date('Y-m-d h:i:s');
		//$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
		$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');
		$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
		$sellerid = Auth::id();
		$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
		$noofsale = count($salecount);

		if(isset($request->product_id))
		{
			$product = Product::where('id','=',$request->product_id)->get();
			return view('users.product.add_product',compact("p_types","p_sell_to","p_items_option","p_service_option","p_subscription_option","p_service_option","p_room","avataricon","daysale","currency_symbol","salecount","noofsale","product","friend_group"));
		}
		else
		{
          	return view('users.product.add_product',compact("p_types","p_sell_to","p_items_option","p_service_option","p_subscription_option","p_service_option","p_room","avataricon","daysale","currency_symbol","salecount","noofsale","friend_group"));
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
		$friend_group = friend_group::where('user_id',Auth::user()->id)->get();
		$avataricon =User::where('id',Auth::id())->get();
		$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
		$today = date('Y-m-d h:i:s');
		//$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
		$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');
		$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
		$sellerid = Auth::id();
		$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
		$noofsale = count($salecount);

		return view('users.product.add_product.product_item',compact("p_types","p_sell_to","p_items_option","p_service_option","p_subscription_option","p_service_option","p_room","avataricon","daysale","currency_symbol","salecount","noofsale","friend_group"));
	}
	/*
	add service 
	*/
	public function add_pro_service()
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
		$friend_group = friend_group::where('user_id',Auth::user()->id)->get();
		$avataricon =User::where('id',Auth::id())->get();
		$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
		$today = date('Y-m-d h:i:s');
		//$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
		$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');
		$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
		$sellerid = Auth::id();
		$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
		$noofsale = count($salecount);

		return view('users.product.add_product.product_service',compact("p_types","p_sell_to","p_items_option","p_service_option","p_subscription_option","p_service_option","p_room","avataricon","daysale","currency_symbol","salecount","noofsale","friend_group"));
	}
	/*
	add subscription 
	*/
	public function add_pro_subs()
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
		$friend_group = friend_group::where('user_id',Auth::user()->id)->get();
		$avataricon =User::where('id',Auth::id())->get();
		$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
		$today = date('Y-m-d h:i:s');
		//$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
		$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');
		$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
		$sellerid = Auth::id();
		$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
		$noofsale = count($salecount);

		return view('users.product.add_product.product_subscription',compact("p_types","p_sell_to","p_items_option","p_service_option","p_subscription_option","p_service_option","p_room","avataricon","daysale","currency_symbol","salecount","noofsale","friend_group"));
	}
	/*
	add product process
	*/
	public function product_add_process( Request $request )
	{
		$user_id = Auth::id();
		if($request['p_title']=='')
		{
			$response['success'] = 0;
            $response['message'] = 'Please Enter Product Title';
            return response()->json($response);	
		}
		else
		{
			$p_title = $request['p_title'];
		}
		$p_description = $request['p_description'];
		$p_quantity = $request['p_quantity'];
		$currency = Auth::user()->currency_code;

		
		if(isset($request['p_sell_to']) && !empty($request['p_sell_to'][0])){
		$p_sell_to = $request['p_sell_to'][0];
		}else{
			$p_sell_to = NULL;
		}

		$p_type = $request['p_type'];
		$p_price_per_optn = NULL; #PENDING

		if(isset($request['p_lend_to']) && !empty($request['p_lend_to'][0])){
			$p_item_lend_options = $request['p_lend_to'][0];
		}else{
			$p_item_lend_options = NULL;
		}
		
		
		if($p_type == 1){#item
			$service_time = '';
			$service_time_type = '';
			$p_quality = $request['p_quality'];
			$service_lead_time = '';
			if($request['p_sell_price']=='')
			{
				/*$response['success']=0;
				$response['message']='Please Enter Selling Price';
				return response()->json($response);*/
				$p_selling_price = '0';
			}
			else
			{
				$p_selling_price = $request['p_sell_price'];
			}
			if($request['p_item_lend_price']=='')
			{
				/*$response['success']=0;
				$response['message']='Please Enter Lending Price';
				return response()->json($response);*/
				$p_lend_price = '0';
 			}
			else
			{
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
			if(isset($request['newroom']) && $request['newroom']!='')
			{
				$room_id = P_room::create(['user_id'=>Auth::id(),
											'display_text'=>$request['newroom'],
											'type'=>$request['newroom'],
											'created_at'=>date('Y-m-d h:i:s'),
											'updated_at'=>date('Y-m-d h:i:s')])->id;

				if(isset($request['boxid']) && $request['boxid']!='')
				{
					 p_boxe::where('id',$request['boxid'])->update(['status'=>'1']);
					 $p_box = $request['boxid'];
				}
				else
				{
					/*$p_box = p_boxe::create(['p_rooms_id'=>$room_id,
										'box_name'=>$request['boxname'],
										'status'=>'1'])->id;*/
					$p_box = p_boxe::create(['p_rooms_id'=>$room_id,
					'box_name'=>'Box 1',
					'status'=>'1'])->id;
				}
				
			}
			/*if(isset($request['newroom']) && $request['newroom']=='')
			{
				return redirect()->back()->with('error', 'Please select Room Or Enter Room Name');
			}*/
			if($request['roomname']!='' && $request['newroom']=='')
			{
				if(isset($request['boxname']) && $request['boxname']!='')
				{
					
						if(isset($request['boxid']) && $request['boxid']!='')
						{
							p_boxe::where('id',$request['boxid'])->update(['status'=>'1']);;
							$p_box = $request['boxid'];
						}
						else
						{
							$p_box = p_boxe::create(['p_rooms_id'=>$request['roomname'],
												'box_name'=>$request['boxname'],
												'status'=>'1'])->id;
						}

					
				}
				else
				{
					/*$response['success'] = 0;
		            $response['message'] = 'Please select Box';
		            return response()->json($response);*/
		            /*$p_box = '0';*/
					//return redirect()->back()->with('error', 'Please select Box');
					$p_box = null;
				}
				$room_id = $request['roomname'];


			}
			if($request['roomname']=='' && $request['newroom']=='')
			{
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


		}elseif($p_type == 2){#service
			$p_quality = 1;
			if($request['service_time']=='')
			{
				$response['success'] = 0;
				$response['message'] = 'Please Select Service Time';
				return response()->json($response);
			}
			else
			{
				$service_time = $request['service_time'];
				if($request['service_time_type']=='hour' && $request['service_time']=='1')
				{
					$service_time_type = 'hr';
				}
				if($request['service_time_type']=='hour' && $request['service_time']!='1')
				{
					$service_time_type = 'hrs';
				}
				if($request['service_time_type']=='min')
				{
					$service_time_type = 'min';
				}
				if($request['service_time_type']=='day')
				{
					$service_time_type = 'day';
				}

				
			}
			if($request['service_lead_time']!='0')
			{
				$service_lead_time = $request['service_lead_time'];
			}
			else
			{
				$service_lead_time = '';
			}
			if($request['p_sell_price']=='')
			{
				/*$response['success']=0;
				$response['message']='Please Enter Selling Price';
				return response()->json($response);*/
				$p_selling_price = '0';
			}
			else
			{
				$p_selling_price = $request['p_sell_price'];
			}
			$p_lend_price = NULL;
			$p_subs_option = NULL;
			$p_subs_price = NULL;
			$p_radius = NULL;
			$p_radius_option = 0;
			$p_location = NULL;
			if(isset($request['p_service_repeat'])){
				$p_repeat = $request['p_service_repeat'];
			}else{
				$p_repeat = NULL;
			}
			
			$p_repeat_per_option = $request['p_service_repeat_option'];
			if(isset($request['p_service_forever'])){
				$p_repeat_forever = $request['p_service_forever'];
			}else{
				$p_repeat_forever = 0;
			}
			if(isset($request['newroom']) && $request['newroom']!='')
			{
				$room_id = P_room::create(['user_id'=>Auth::id(),
											'display_text'=>$request['newroom'],
											'type'=>$request['newroom'],
											'created_at'=>date('Y-m-d h:i:s'),
											'updated_at'=>date('Y-m-d h:i:s')])->id;

				if(isset($request['boxid']) && $request['boxid']!='')
				{
					p_boxe::where('id',$request['boxid'])->update(['status'=>'1']);
					$p_box = $request['boxid'];
				}
				else
				{
					/*$p_box = p_boxe::create(['p_rooms_id'=>$room_id,
										'box_name'=>$request['boxname'],
										'status'=>'1'])->id;*/
					$p_box = p_boxe::create(['p_rooms_id'=>$room_id,
					'box_name'=>'Box 1',
					'status'=>'1'])->id;
				}


			}
			/*if(isset($request['newroom']) && $request['newroom']=='')
			{
				return redirect()->back()->with('error', 'Please select Room Or Enter Room Name');
			}*/
			if($request['roomname']!='' && $request['newroom']=='')
			{
				if(isset($request['boxname']) && $request['boxname']!='')
				{
					
						if(isset($request['boxid']) && $request['boxid']!='')
						{
							p_boxe::where('id',$request['boxid'])->update(['status'=>'1']);
							 $p_box = $request['boxid'];
						}
						else
						{
							$p_box = p_boxe::create(['p_rooms_id'=>$request['roomname'],
												'box_name'=>$request['boxname'],
												'status'=>'1'])->id;
						}
					
				}
				else
				{
					/*$response['success'] = 0;
		            $response['message'] = 'Please select Box';
		            return response()->json($response);*/
		            /*$p_box = '0';*/
					//return redirect()->back()->with('error', 'Please select Box');
					$p_box = null;
				}
				$room_id = $request['roomname'];


			}
			if($request['roomname']=='' && $request['newroom']=='')
			{
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

			//$p_time = date('Y-m-d h:i:s', strtotime($request['p_service_time']));	
			$p_time = $request['p_service_time'];	
			//$p_location = $request['p_service_location'];
			$p_group = $request['p_service_group_price'];
			if($request['location_select']=='on')
			{
				if($request['p_service_radius']=='')
				{
					$response['success'] = 0;
		            $response['message'] = 'Please Enter Radius';
		            return response()->json($response);
				}
				else
				{
					$p_radius = $request['p_service_radius'];
				}
				if($request['p_service_radius_option']=='0')
				{
					$response['success'] = 0;
		            $response['message'] = 'Please Select Radius Option Km/Miles';
		            return response()->json($response);
				}
				else
				{
				$p_radius_option = $request['p_service_radius_option'];
				}

				if($request['p_service_location']=='')
				{
					$response['success'] = 0;
		            $response['message'] = 'Please Select Location';
		            return response()->json($response);
				}
				else
				{
					$p_location = $request['p_service_location'];
				}
			}
			$p_box = null;
			$room_id = null;
			$p_price_per_optn = null;
			
		}else{#subs
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
				if($request['p_sell_price']=='')
				{
					/*$response['success']=0;
					$response['message']='Please Enter Subscription Price';
					return response()->json($response);*/
					$p_subs_price = '0';
				}
				else
				{
					$p_subs_price = $request['p_sell_price'];
				}
				$p_repeat = $request['p_subs_repeat'];
				$p_repeat_per_option = $request['p_subs_repeat_option'];

				if(isset($request['p_subs_forever'])){
					$p_repeat_forever = $request['p_subs_forever'];
				}else{
					$p_repeat_forever = 0;
				}
				if(isset($request['newroom']) && $request['newroom']!='')
				{
				$room_id = P_room::create(['user_id'=>Auth::id(),
											'display_text'=>$request['newroom'],
											'type'=>$request['newroom'],
											'created_at'=>date('Y-m-d h:i:s'),
											'updated_at'=>date('Y-m-d h:i:s')])->id;

				if(isset($request['boxid']) && $request['boxid']!='')
				{
					 p_boxe::where('id',$request['boxid'])->update(['status'=>'1']);
					 $p_box = $request['boxid'];
				}
				else
				{
					$p_box = p_boxe::create(['p_rooms_id'=>$room_id,
										'box_name'=>'Box 1',
										'status'=>'1'])->id;
				}
				

				}
			/*if(isset($request['newroom']) && $request['newroom']=='')
			{
				return redirect()->back()->with('error', 'Please select Room Or Enter Room Name');
			}*/
			if($request['roomname']!='' && $request['newroom']=='')
			{
				if(isset($request['boxname']) && $request['boxname']!='')
				{
					
						if(isset($request['boxid']) && $request['boxid']!='')
						{
							p_boxe::where('id',$request['boxid'])->update(['status'=>'1']);
							$p_box = $request['boxid'];
						}
						else
						{
							$p_box = p_boxe::create(['p_rooms_id'=>$request['roomname'],
												'box_name'=>$request['boxname'],
												'status'=>'1'])->id;
						}
					
				}
				else
				{
					/*$response['success'] = 0;
		            $response['message'] = 'Please select Box';
		            return response()->json($response);*/
		            /*$p_box = '0';*/
					//return redirect()->back()->with('error', 'Please select Box');
					$p_box = null;
				}
				$room_id = $request['roomname'];


			}
			if($request['roomname']=='' && $request['newroom']=='')
			{
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
				
				if($request['location_select'] == 'on')
				{
					if($request['p_subs_radius_options']=='0')
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
					}
					if($request['p_subs_location']=='')
					{
						$response['success'] = 0;
			            $response['message'] = 'Please Select Location';
			            return response()->json($response);
					}
					else
					{
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


		}
		
		if(isset($request['p_service_option'])){
			$p_service_option = $request['p_service_option'];
		}else{
			$p_service_option = NULL;
		}


		#images
		$image_array = array();
		$image_names = '';
		//dd($request['p_images[]']);
		if(isset($request['p_images'])){
			$imgarr = array();
			if(isset($request['pro_img']) && !empty($request['pro_img'][0])){
				$sort_arr = explode(',', $request['pro_img'][0]);

				foreach ($sort_arr as $key => $value) {
					$imgarr[$key] = $request['p_images'][$value];
				}
			}else{
				$imgarr = $request['p_images'];
			 
			}

			foreach($imgarr as $count_img => $img){
				if($p_title!='')
				{
					$title = str_replace(' ','-',$p_title);
					$country = str_replace(' ','-',Auth::user()->country);
					$slug = $title.'-'.$country;
					$imageName = $slug.'-'.time().'_'.$user_id.'.'.'_'.$count_img.''.$img->getClientOriginalExtension();
				}
				else
				{
				$imageName = time().'_'.$user_id.'.'.'_'.$count_img.''.$img->getClientOriginalExtension();
				}
				$img->move(public_path('uploads/products'), $imageName);
				$image_array[] = $imageName;
			}
			$image_names = implode(',', $image_array);
			$p_image = $image_names;
			/*$response['message'] = 'Gone to subscription condition';
			$response['success'] = 0;
			return response()->json($response);*/
		}else{
			$p_image = $image_names;
			}
			
		//echo $p_repeat_forever;
		//die;

		$model = Product::create([
			'user_id'=> $user_id,
			'p_title'=> $p_title,
			'p_description'=> $p_description,
			'p_quantity'=> $p_quantity,
			'p_quality'=> $p_quality,
			'p_image'=> $p_image,
			'p_selling_price'=> $p_selling_price,
			'code' => $currency,
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
			'service_lead_time' => $service_lead_time,
			'service_time' => $service_time,
			'service_time_type' => $service_time_type
		]); 		
		$model->save();
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

		if(!empty($model)){
			//return redirect('myproducts')->with('message', 'Product saved successfully');
			//return view('users.product.product_list')->with('message', 'Product saved successfully');
					$response['success'] = 1;
					if((Auth::user()->sell_to_friend=='1' || Auth::user()->sell_to_neighbour=='1' || Auth::user()->sell_to_uk=='1') && (Auth::user()->lend_to_friend=='1' || Auth::user()->lend_to_neighbour=='1' || Auth::user()->lend_to_uk=='1') )
					{
			            if($p_selling_price=='0' && $p_lend_price=='0' && $p_type=='1')
			            {
			            	Session::flash('message', 'You are selling and lending this item for FREE'); 
			            	Session::flash('type','1');
			            }
			             elseif($p_selling_price!='0' && $p_lend_price=='0' && $p_type=='1')
			            {
			            	Session::flash('message', 'You are lending this item for FREE'); 
			            	Session::flash('type','1');
			            }
			            elseif($p_selling_price=='0' && $p_lend_price!='0' && $p_type=='1')
			            {
			            	Session::flash('message', 'You are selling  this item for FREE'); 
			            	Session::flash('type','1');
			            }
			            elseif($p_selling_price=='0' && $p_type!='1')
			            {
			            	Session::flash('message', 'You are selling this item for FREE');
			            }
			            else
			            {
			             Session::flash('message3','Product Saved successfully');
			           	 $response['message'] = 'Product saved successfully';
			            }
			        }
			        else
			        {
			        	Session::flash('message3','Product Saved successfully');
			           	 $response['message'] = 'Product saved successfully';
			        }
		            return response()->json($response);
		}else{
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
		$ordercheck = Order::where('seller_id','=',$userId)->where('o_completed','=','0')->get();
		$pendingordercount = count($ordercheck);
		$uDetails = User::where('id',$userId)->get();
		#get countries
		$country = Country::orderBy('c_name','ASC')->get();
		$currencies = currencies::get();
		$avataricon =User::where('id',Auth::id())->get();
		$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
		$today = date('Y-m-d h:i:s');
		$holiday = Holiday::where('user_id',$userId)->get();
		$delivery = deliverie::where('user_id',Auth::user()->id)->get();
		/*print_r($holiday);
		die;*/
		//$daysale = Order::where('seller_id',Auth::id())->where('created_at','>=',$prev_date)->where('created_at','<=',$today)->get();
		$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');
		$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
		$sellerid = Auth::id();
		$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
		$noofsale = count($salecount);

		return view('users.view_profile', compact("uDetails","userId","country","avataricon","daysale","currencies","currency_symbol","holiday","pendingordercount","salecount","noofsale","delivery"));
	}

	/*
	Edit profile
	*/
	public function delete_holiday(Request $request)
	{

		$delete_check = DB::table('holidays')->where('id','=',$request['id'])->delete();
		if($delete_check != 0){
				$response['success'] = 1;
	            $response['message'] = 'Success';
				return response()->json($response);
			}else{
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
				'user_id'=>$userId,
				'start'	=>$start, 
				'end' 	=> $end,
				'created_at'=>date('Y-m-d h:i:s'),
				'updated_at'=>date('Y-m-d h:i:s')
			]);
		if(empty($insertval)){
			$response['success'] = 0;
			$response['message'] = "There was problem in updating your changes. Please try again later";
			
		}else{
			$response['success'] = 1;
			$response['message'] = "Details updated successfully";

			$timedata = explode(' - ',$start);
            $timedata2 = explode(' - ',$end);
            $startday = date("D", strtotime($timedata[0]));
            $endday = date("D", strtotime($timedata2[0]));


            $starttime=date_parse_from_format("Y-m-d", $start);
            $startmonth=date('F', mktime(0, 0, 0, $starttime['month'], 10));
            $endtime=date_parse_from_format("Y-m-d", $end);
            $endmonth=date('F', mktime(0, 0, 0, $endtime['month'], 10));

            $startdata = explode('-',$start);
            $enddata = explode('-',$end);
           

            $starttime = $timedata[1];
            $starttime = date('g:i', strtotime($starttime));
            $endtime = $timedata2[1];
            $endtime = date('g:i', strtotime($endtime));

            $startdate = $startdata[2];
            $startyear = $startdata[0];

            $enddate = $enddata[2];
            $endyear = $enddata[0];
			$response['data'] = '<div id='.$insertval->id.'>
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
                                                                                                        
                                                <p class="text-muted" style="font-size: 13px;">'.$startday.' '.$startdate.'-'.$startmonth.'-'.$startyear.'</p>
                                                <b style="font-size: 11px;">('.$starttime.')</b> 
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
                                                 <p class="text-muted" style="font-size: 13px;">'.$endday.' '.$enddate.'-'.$endmonth.'-'.$endyear.'</p>
                                                <b style="font-size: 11px;">('.$endtime.')</b> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Column -->
                                    
                                    <div class="col-md-12 col-lg-2 col-xlg-2 col-sm-12 col-xs-12">
                                      <div class="card padding-top:0px;">
                                            <button type="button" class="btn btn-light remove_holiday" style="margin-top:25px;" data-holiday_id="23" onclick="deleteholiday('.$insertval->id.');">
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

		$userId = Auth::id();

		$name = $request['name'];
		$email = $request['email'];
		$latlng = str_replace('(','',$request['latlng']);
		$latlng = str_replace(')', '', $latlng);
		$latlng = explode(',',$latlng);
		$location = $request['location'];
		$timezone = $request['timezone'];
		if($request['latlng']=='' || $request['location']=='')
		{
			$response['success'] = 0;
			$response['message'] = 'Please set up your location';
			return response()->json($response);
		}
		#validation
		$validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email,'.$userId,
        ]);

        if ($validator->fails()) {
            $response['error'] = $validator->errors();
            return response()->json($response);
        }


        $update_arr = [];
        #image format
        if(isset($request['avatar'])){
			$imageName = time().'_'.$userId.'.'.$request['avatar']->getClientOriginalExtension();
			$request['avatar']->move(public_path('uploads/avatar'), $imageName);
			$avatar = $imageName;
			#update arr
			$update_arr = [
				'name'			=>$name,
				'email'			=>$email, 
				'avatar'		=>$avatar, 
				'avatar_type'	=> 1,
				'street_address1' 	=> $request['st_address1'],
				'street_address2' 	=> $request['st_address2'],
				'city' 			=> $request['city'],
				'state' 		=> $request['state'],
				'country'		=> $request['country'],
				'currency_code'=>$request['currency_code'],
				'pincode'		=> $request['pincode'],
				'lat'		=> $latlng[0],
				'lng'		=> $latlng[1],
				'location'	=> $location,
				'timezone'	=> $timezone
			]; 
		}else{
			#get user's 
			$update_arr = [
				'name'			=>$name,
				'email'			=>$email, 
				'street_address1' 	=> $request['st_address1'],
				'street_address2' 	=> $request['st_address2'],
				'city' 			=> $request['city'],
				'state' 		=> $request['state'],
				'country'		=> $request['country'],
				'currency_code'=>$request['currency_code'],
				'pincode'		=> $request['pincode'],
				'lat'		=> $latlng[0],
				'lng'		=> $latlng[1],
				'location'	=> $location,
				'timezone'	=> $timezone
			];
		}

		$update_val = DB::table('users')->where('id',$userId)->update($update_arr);

		if(empty($update_val) && $update_val != 0){
			$response['success'] = 0;
			$response['message'] = "There was not problem in updating your changes. Please try again later";
			
		}else{
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

		if(!isset($request['auth_val'])){
			#some error 
			$response['success'] = 0;
            $response['message'] = "Auth Value not found ";
            return response()->json($response);
		}

		#disable 2 auth
		if($request['auth_val'] == 0) {
			$check_status = User::where('id', $userId)->update(['two_way_auth' => 0]);
			if(!empty($check_status)){
				#some error 
				$response['success'] = 1;
	            $response['message'] = "Settings Updated.";
	            return response()->json($response);
			}
		}	

		#enable 2 auth and verify the mobile no.
		if($request['auth_val'] == 1) {

			#check the mobile no, if same dont ask for otp
			if(isset($request['contact_no_hidden']) && !empty($request['contact_no_hidden'])){
				$userno = trim($request['contact_no_hidden']);
			}else{
				$userno = trim($request['contact_mobile']);
			}
			$contact_no = trim($request['dial_code']).''.$userno;
			$existing_no = $Existingcode.''.$Existingcontact;

			
			if($contact_no == $existing_no){

				#same numbers
				$check_status = User::where('id', $userId)->update(['two_way_auth' => 1, 'contact_country'=>$request['contact_country']]);

				if(!empty($check_status)){
					#some error 
					$response['success'] = 1;
		            $response['message'] = "Settings Updated.";
		            return response()->json($response);
				}

			}else{
				#different no, generate sms
				#send otp to user
                $digits = 4;
                $otp_no = rand(pow(10, $digits-1), pow(10, $digits)-1);
                
                #send
                $user = new User();
                $user->sendText($contact_no,"Contact25","Dear user, your verification code is : ".$otp_no." . Use this code update your mobile number.");
                $response['success'] = 2;
                $response['message'] = "Verification code has been sent to your mobile.";
                $response['otpval'] = Hash::make($otp_no).'_tval_'.date('Y-m-d h:i:s');
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
		if(empty($request['otp']) || empty($request['otp_val'])){
            $response['success'] = 0;
            $response['message'] = "Please enter the verification code";
            return response()->json($response);
        }else{
        	#get time of otp sent
            $input = "_tval_";
            $timeOTP_sent = explode('_tval_', $request['otp_val']);
            $hash_otp = $timeOTP_sent[0];
            $otp_time_sent = $timeOTP_sent[1];

            #get current time
            $t=time();
            $current_time = date("Y-m-d h:i:s",$t);

            #get time different
            $ts1 = strtotime($otp_time_sent);
            $ts2 = strtotime($current_time);     
            $minutes_diff = ($ts2 - $ts1) / 60; 

            if($minutes_diff > 5){ # 5 minutes limit

                $response['success'] = 3;
                $response['message'] = "Verification code has expired. Please resend the code";
                return response()->json($response);
            }
            if (Hash::check($request['otp'], $hash_otp)) {
            	#update user
            	$update_arr = [
            		"contact_code"=>$request['dial_code'],
            		"contact_no"=>$request['contact_no_hidden'],
            		"two_way_auth"=>1,
            		"contact_country"=>$request['contact_country'],
            		"contact_verify_status"=>1,
            		"contact_verified_at"=>date("Y-m-d h:i:s")
            	];

            	$update_status = DB::table('users')->where('id', $userId)->update($update_arr);
                
            	if(!empty($update_status)){
            		$response['success'] = 1;
            		$response['message'] = "Settings Updated.";
                	return response()->json($response);
            	}else{
            		$response['success'] = 0;
	                $response['message'] = "Some error occured.";
	                return response()->json($response);
            	}
            }else{
                $response['success'] = 0;
                $response['message'] = "Verification code does not matches";
                return response()->json($response);
            }
        }
	}#two_auth_otp

	/*
	Listing friends products
	*/

	public  function  products_friends()
	{

		$userId = Auth::id();
		$avataricon =User::where('id',Auth::id())->get();
		$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
		$today = date('Y-m-d h:i:s');
		$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');
		$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
		try{

			$product_list = Product::with(['userDet', 'product_type','product_sell_options','product_lend_options','product_service_option','product_subs_option','currency','friend'])
			       ->whereHas('userDet', function($q) {
			       $q->where('country','=',Auth::user()->country);
			})->whereHas('friend', function($q){
				$q->where('friend_id_2',Auth::user()->id)->where('status','1');
			})->OrwhereHas('friend2', function($q){
				$q->where('friend_id_1',Auth::user()->id)->where('status','1');
			})
			->where('user_id','!=',$userId)
			->where('p_slug','!=','')
			->orderBy('created_at','desc')
			->paginate(15);	 	// ## Friend Products 
			$countryproducts = Product::with(['userDet', 'product_type','product_sell_options','product_lend_options','product_service_option','product_subs_option','currency','friend'])
			       ->whereHas('userDet', function($q) {
			       $q->where('country','=',Auth::user()->country);
			})->where('user_id','!=',$userId)
			->where('p_slug','!=','')
			->orderBy('created_at','desc')
			->paginate(15);		// ## Country Products
			/*echo '<pre>';
			print_r($product_list);
			echo '</pre>';
			die;*/
			$sellerid = Auth::id();
			$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
			$noofsale = count($salecount);
			return view('users.product.product_friend', compact("product_list","userId","avataricon","daysale","currency_symbol","salecount","noofsale","countryproducts"));

		}catch(\Exception $e){
			return $e->getMessage();
		}
	}

	// Search Bar
	public function search(Request $request)
	{
		$keyword = $request->keyword;
		$userId = Auth::id();
		$avataricon =User::where('id',Auth::id())->get();
		$stopword = search_stop_word::where('word',$keyword)->get();
		
			if(count($stopword)>'0')
			{
				$stopwordstatus = 'yes';
			}
			else
			{
				$stopwordstatus = 'no';
			}
		

		$sellercountry = User::where('id',$userId)->get();
		$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
		$today = date('Y-m-d h:i:s');
		$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');
		$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
		try{

			$product_list = Product::with(['userDet', 'product_type','product_sell_options','product_lend_options','product_service_option','product_subs_option','currency'])
			       ->whereHas('userDet', function($q) {
			       $q->where('role_id','=',2);
			})
			->where('user_id','!=',$userId)
			->where('p_slug','!=','')
			->where('p_title', 'LIKE', '%' . $keyword . '%')
			->orderBy('created_at','desc')
			->paginate(15);
			$sellerid = Auth::id();
			$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
			$noofsale = count($salecount);
			return view('users.product.search', compact("product_list","userId","avataricon","sellercountry","daysale","currency_symbol","stopwordstatus","salecount","noofsale","keyword"));

		}catch(\Exception $e){
			return $e->getMessage();
		}
	}
	/*
	Updating Sell To / Lend To
	*/
	public function updateselling(Request $request)
	{
		try
		{
			$data = $request->all();
			if(isset($data['sell_to_friend']))
			{
				$checkselling = User::where('id', Auth::id())->update(['sell_to_friend'=>$data['sell_to_friend']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		            //$response['message'] = $html_append;
		            return response()->json($response);
				}
			}
			if(isset($data['sell_to']))
			{
				/*$response['success'] = 0;
				return response()->json($response);*/
				$checkselling = friend_group::where('id',$data['id'])->update(['sell_to'=>$data['sell_to']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		            //$response['message'] = $html_append;
		            return response()->json($response);
				}
			}
			if(isset($data['lend_to']))
			{
				$checkselling = friend_group::where('id',$data['id'])->update(['lend_to'=>$data['lend_to']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		            //$response['message'] = $html_append;
		            return response()->json($response);
				}
			}
			if(isset($data['sell_to_friend_of_friend']))
			{
				$checkselling = User::where('id', Auth::id())->update(['sell_to_friend_of_friend'=>$data['sell_to_friend_of_friend']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		            //$response['message'] = $html_append;
		            return response()->json($response);
				}
			}
			if(isset($data['sell_to_neighbour']))
			{
				$checkselling = User::where('id', Auth::id())->update(['sell_to_neighbour'=>$data['sell_to_neighbour']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		           // $response['message'] = $html_append;
		            return response()->json($response);
				}
			}
			if(isset($data['sell_to_uk']))
			{
				$checkselling = User::where('id', Auth::id())->update(['sell_to_uk'=>$data['sell_to_uk']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		            //$response['message'] = $html_append;
		            return response()->json($response);
				}
			}
			if(isset($data['lend_to_friend']))
			{
				$checkselling = User::where('id', Auth::id())->update(['lend_to_friend'=>$data['lend_to_friend']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		           // $response['message'] = $html_append;
		            return response()->json($response);
				}
			}
			if(isset($data['lend_to_friend_of_friend']))
			{
				$checkselling = User::where('id', Auth::id())->update(['lend_to_friend_of_friend'=>$data['lend_to_friend_of_friend']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		            //$response['message'] = $html_append;
		            return response()->json($response);
				}
			}
			if(isset($data['lend_to_neighbour']))
			{
				$checkselling = User::where('id', Auth::id())->update(['lend_to_neighbour'=>$data['lend_to_neighbour']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		            //$response['message'] = $html_append;
		            return response()->json($response);
				}
			}
			if(isset($data['lend_to_uk']))
			{
				$checkselling = User::where('id', Auth::id())->update(['lend_to_uk'=>$data['lend_to_uk']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		            //$response['message'] = $html_append;
		            return response()->json($response);
				}
			}
		}
		catch(\Exception $e){
			$response['success'] = 0;
            $response['message'] = $e->getMessage().' '.$e->getLine();
			return response()->json($response);
		}
	}
	/* Update Communication */
	public function update_communication(Request $request)
	{

		$data = $request->all();
		if(isset($request['order_status']))
		{
		$checkselling = User::where('id', Auth::id())->update(['order_status'=>$data['order_status']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		            //$response['message'] = $html_append;
		            return response()->json($response);
				}
		}
		if(isset($request['update_message']))
		{
		$checkselling = User::where('id', Auth::id())->update(['message_status'=>$data['update_message']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		            //$response['message'] = $html_append;
		            return response()->json($response);
				}
		}
		if(isset($request['collect_status']))
		{

		$checkselling = User::where('id', Auth::id())->update(['collect_status'=>$data['collect_status']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		            //$response['message'] = $html_append;
		            return response()->json($response);
				}
		}
		if(isset($request['collection_status']))
		{
		$checkselling = User::where('id', Auth::id())->update(['collection_status'=>$data['collection_status']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		            //$response['message'] = $html_append;
		            return response()->json($response);
				}
		}
		if(isset($request['friend_status']))
		{
		$checkselling = User::where('id', Auth::id())->update(['friend_status'=>$data['friend_status']]);
				if(!empty($checkselling))
				{
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
			if(!empty($inserval))
			{
				$response['status'] = 1;
				return response()->json($response);
			}
			else
			{
				$response['status'] = 0;
				$response['message'] = 'There is error while deleting data';
				return response()->json($response);
			}
	}
	/* Add Delivery */
	public function add_delivery(Request $request)
	{
		$data = $request->all();
		if($data['delivery_provider']=='' || $data['tracking_url']=='')
		{
			$response['status'] = 0;
			$response['message'] = 'Please Enter All The Fields';
			return response()->json($response);
		}
		else
		{
			$inserval = deliverie::create(['user_id'=>Auth::user()->id,
											'delivery_provider'=>$data['delivery_provider'],
											'tracking_url'=>$data['tracking_url'],
											'price'=>$data['price']]);
			if(!empty($inserval))
			{
				$response['status'] = 1;
				$response['delivery_provider'] = $data['delivery_provider'];
				$response['tracking_url'] = $data['tracking_url'];
				if($data['price']=='')
				{
					$response['price'] = 'Free';
				}
				else
				{
					$response['price'] = $data['price'];
				}
				$response['id'] = $inserval->id; 
				return response()->json($response);
			}
			else
			{
				$response['status'] = 0;
				$response['message'] = 'There is error while inserting data';
				return response()->json($response);
			}
		}
	}
	/* Update Delivery */
	public function update_delivery(Request $request)
	{
		$data = $request->all();
		if(isset($data['inpost_status']))
		{
		$checkselling = User::where('id', Auth::id())->update(['inpost_status'=>$data['inpost_status']]);
				if(!empty($checkselling))
				{
					$response['success'] = 1;
		            //$response['message'] = $html_append;
		            return response()->json($response);
				}
		}
		if(isset($data['delivery_provider']))
		{
			$insertVal = deliverie::crete(['user_id'=>Auth::user()->id,
											'delivery_provider'=>$request['delivery_provider'],
											'tracking_url'=>$request['tracking_url']]);
			if(empty($insertval))
			{
				$response['status'] = 0;
				$response['message'] = 'There is error while inserting data';
				return response()->json($response);
			}
			else
			{
				$response['status'] = 1;
				$response['delivery_provider'] = $request['delivery_provider'];
				$response['tracking_url'] = $request['tracking_url'];
				$response['id'] = $insertVal->id; 
				return response()->json($response);
			}
		}
		$response['status'] = 0;
		$response['message'] = 'Error';
		return response()->json($response);
	}
	/* Move Box */
	public function move_box(Request $request)
	{
		$data = $request->all();
		$room_id = p_boxe::where('id',$data['move_box_id'])->get();
		$roomupdate = Product::where('p_box',$data['box_id'])->where('p_quantity','!=','0')->update(['p_box'=>$data['move_box_id'],'room_id'=>$room_id[0]['p_rooms_id']]);
		if(!empty($roomupdate))
		{
			p_boxe::where('id',$data['box_id'])->update(['status'=>'0']);
			$response['success'] = 1;
			$response['message'] = 'Updated';
			$response['roomid'] = $room_id[0]['p_rooms_id'];
			return response()->json($response);
		}
		else
		{
			$response['success'] = 0;
			$response['message'] = $roomupdate;
			return response()->json($response);
		}
	}

	/* Update Room */
	public function update_room(Request $request)
	{
		$data = $request->all();
		$roomupdate = P_room::where('id',$request['id'])->update(['display_text'=>$request['display_text']]);
		if(!empty($roomupdate))
		{
			$response['success'] = 1;
			$response['message'] = 'Updated';
			return response()->json($response);
		}
		else
		{
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
		try{

			if(empty($request->all())){

				$response['success'] = 0;
	            $response['message'] = "Some error has occured.";
	            return response()->json($response);
			}
			$request_arr = $request->all();
			$user_id = ['user_id'=> Auth::id()];
			$insert_arr = $request_arr+$user_id;
			$created_at = ['created_at'=>date('Y-m-d h:i:s'), 'updated_at'=>date('Y-m-d h:i:s')];
			$insert_arr = $insert_arr+$created_at;
			

			#check if details exists
			$check_exists = Users_opening_hr::where('user_day_name',$request->user_day_name)->where('user_id',Auth::user()->id)->get();
			if(count($check_exists) > 0){
				$response['success'] = 0;
	            $response['message'] = 'The timeslot already exists. Please select different slot.';
				return response()->json($response);
			}else{
				$insert_check = DB::table('users_opening_hrs')->insertGetId($insert_arr);
				if($insert_check){

					$html_append = '<li  id="hour_id_'.$insert_check.'" style="font-size:10px">'.ucwords($request['user_day_name']).' ('.date("g:i a", strtotime($request['user_start_time'])).' - '.date("g:i a", strtotime($request['user_end_time'])).') <span class="text-danger" data-user_hr_id="'.$insert_check.'" onclick="removeOpenHour(this);"> <i class="fa fa-trash" aria-hidden="true"></i> </span>  </li>';
					$response['success'] = 1;
		            $response['message'] = $html_append;
		            return response()->json($response);
				}else{
					$response['success'] = 0;
		            $response['message'] = "Unable to save timeslot. ";
		            return response()->json($response);
				}
			}
		}catch(\Exception $e){
			$response['success'] = 0;
            $response['message'] = $e->getMessage().' '.$e->getLine();
			return response()->json($response);
		}
	}#addOpenHours ends here

	/*
	Delete open hours
	*/
	
	public function removeOpenHours(Request $request)
	{

		try{
			#check if id exists
			$id_check = Users_opening_hr::where($request->all())->first();
			if(count($id_check) == 0){
				$response['success'] = 0;
	            $response['message'] = 'Invalid Open Hour ID. Please reload.';
				return response()->json($response);
			}

			#delete
			$delete_check = DB::table('users_opening_hrs')->where($request->all())->delete();

			if($delete_check != 0){
				$response['success'] = 1;
	            $response['message'] = 'Success';
				return response()->json($response);
			}else{
				$response['success'] = 0;
	            $response['message'] = 'Some error occured while deleting the time slot.';
				return response()->json($response);
			}
		}catch(\Exception $e){
			$response['success'] = 0;
            $response['message'] = $e->getMessage().' '.$e->getLine();
			return response()->json($response);
		}
	}
	/*
	Add Service Hours
	*/
	public function addServiceHours(Request $request)
	{
		try{

			if(empty($request->all())){

				$response['success'] = 0;
	            $response['message'] = "Some error has occured.";
	            return response()->json($response);
			}
			$request_arr = $request->all();
			$user_id = ['user_id'=> Auth::id()];
			$insert_arr = $request_arr+$user_id;
			$created_at = ['created_at'=>date('Y-m-d h:i:s'), 'updated_at'=>date('Y-m-d h:i:s')];
			$insert_arr = $insert_arr+$created_at;
			

			#check if details exists
			$check_exists = service_opening_hr::where('user_day_name',$request->user_day_name)->where('user_id',Auth::user()->id)->get();
			if(count($check_exists) > 0){
				$response['success'] = 0;
	            $response['message'] = 'The timeslot already exists. Please select different slot.';
				return response()->json($response);
			}else{
				$insert_check = DB::table('service_opening_hrs')->insertGetId($insert_arr);
				if($insert_check){

					$html_append = '<p  id="hour_id_'.$insert_check.'" style="font-size:15px">'.ucwords($request['user_day_name']).' ('.date("g:i a", strtotime($request['user_start_time'])).' - '.date("g:i a", strtotime($request['user_end_time'])).') <span class="text-danger" data-user_hr_id="'.$insert_check.'" onclick="removeServiceHour(this);"> <i class="fa fa-trash" aria-hidden="true"></i> </span>  </p>';
					$response['success'] = 1;
		            $response['message'] = $html_append;
		            return response()->json($response);
				}else{
					$response['success'] = 0;
		            $response['message'] = "Unable to save timeslot. ";
		            return response()->json($response);
				}
			}
		}catch(\Exception $e){
			$response['success'] = 0;
            $response['message'] = $e->getMessage().' '.$e->getLine();
			return response()->json($response);
		}
	}
	/*
	Delete Service hours
	*/
	
	public function removeServiceHours(Request $request)
	{

		try{
			#check if id exists
			$id_check = service_opening_hr::where($request->all())->first();
			if(count($id_check) == 0){
				$response['success'] = 0;
	            $response['message'] = 'Invalid Open Hour ID. Please reload.';
				return response()->json($response);
			}

			#delete
			$delete_check = DB::table('service_opening_hrs')->where($request->all())->delete();

			if($delete_check != 0){
				$response['success'] = 1;
	            $response['message'] = 'Success';
				return response()->json($response);
			}else{
				$response['success'] = 0;
	            $response['message'] = 'Some error occured while deleting the time slot.';
				return response()->json($response);
			}
		}catch(\Exception $e){
			$response['success'] = 0;
            $response['message'] = $e->getMessage().' '.$e->getLine();
			return response()->json($response);
		}
	}
	/*
	product page
	*/
	public  function  products_page(Request $request, $slug)
	{
		try{
			$productid = base64_decode($request->id);
			//$productid = $request->id;
			$slug = substr($slug, strpos($slug, "buy-") + 4);    
			/*print_r($slug);die;*/

			$userId = Auth::id();

			#basic product details
			$product_details = Product::with(['userDet', 'product_type','product_sell_options','product_lend_options','product_service_option','product_subs_option'])
				->where('id','=',$productid)
				->get();

			$user_id = $product_details[0]->user_id;

			#collect hours
			if($product_details[0]['p_type']=='2')
			{
				$open_hrs = service_opening_hr::where('user_id', $user_id )->get();
			}
			else
			{
				$open_hrs = Users_opening_hr::where('user_id', $user_id )->get();
			}
			$sellercountry = User::where('id',$user_id)->get();
			$holiday = Holiday::where('user_id',$user_id)->get();

			if(count($holiday)>'0')
			{
				foreach($holiday as $holidays)
				{


					$holiday_start = explode(' ',$holidays->start);
					$holiday_end = explode(' ',$holidays->end);
					
					if((date('Y-m-d') >= $holiday_start[0] && date('Y-m-d') <= $holiday_end[0] ))
						{   // If holiday is not equal to current date in the loop
							$dateafterholiday = date('Y-m-d',strtotime($holiday_end[0]. ' + 1 days'));
						}
						else
						{
							$dateafterholiday = date('Y-m-d');
						}
				}
			}
			else
			{
				$dateafterholiday = date('Y-m-d');
			}
			#current time and day
			if($product_details[0]['p_type']=='2')
			{
				if($product_details[0]['service_lead_time']!='')
				{
					$date_diff = '0';
					$date_diff = $date_diff/(60 * 60 * 24);
					$lead_time = $product_details[0]['service_lead_time'] - $date_diff;
					if($lead_time > 0)
					{
						$current_day = date('w',strtotime("+".$lead_time." days"));
					}
					else
					{
						$current_day = date('w',strtotime($dateafterholiday));
					}
					
				}
				else
				{
					$current_day = date('w',strtotime($dateafterholiday));
				}
			}
			else
			{
				$current_day = date('w',strtotime($dateafterholiday));
			}
			$current_time = date('h:i:s');

			#collect times
			if(count($open_hrs) > 0){
				$delivery_message_heading = '';
				
				$available_shop_times = array();
				$timeSlot = array();

				foreach ($open_hrs as $key => $value) {
					$available_shop_times[$key] = $value['user_day'];
					$timeSlot[$value['user_day']]['start_time'] = date("g:ia", strtotime($value['user_start_time']));
					$timeSlot[$value['user_day']]['end_time'] = date("g:ia", strtotime($value['user_end_time']));
				}

				$nearest_day = array();
				$smallest = array();
				$last = null;
				$available_day = 0;
				foreach ($available_shop_times as $key => $value) {

					if($current_day == $value && $product_details[0]['service_lead_time']!=''){
							$day_name = date('l', strtotime("Sunday + $current_day Days"));
							$date_diff = abs(strtotime(date('Y-m-d'))-strtotime(date('Y-m-d')));
							$date_diff = $date_diff/(60 * 60 * 24);
							$lead_time = $product_details[0]['service_lead_time'] - $date_diff;
							if($lead_time > 0)
							{

								$main_heading_day = 'This '.$day_name;
								$available_day = $value; // found it, return quickly
							}
							else
							{
								$main_heading_day = 'Today';
								$available_day = $value; // found it, return quickly
							}
				       
					}
					/*elseif($current_day != $value && $product_details[0]['service_lead_time']!=''){
							$day_name = date('l', strtotime("Sunday + $current_day Days"));
							$main_heading_day = 'This '.$day_name;
							$available_day = $value; // found it, return quickly
					}*/
					elseif($current_day == $value && $product_details[0]['service_lead_time']==''){
						$available_day = $value;
						$main_heading_day = 'Today';
						}

					else{
						if ($value > $current_day) {
				            $available_day = $value; // found it, return quickly
				        }
					}
				}
				if($available_day == 0){
					$available_day = min($available_shop_times);
				}
				#get time slot
				if($available_day==$value)
				{
					$minute = date('i',strtotime(date('Y-m-d h:i:s')));
					   if(strtotime(date('h:i:s A',strtotime($timeSlot[$available_day]['start_time']))) < strtotime(date('h:i:s A')))
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
					       


					 
				}
				
				$timeslot_heading = $timeSlot[$available_day];
				
				if(!isset($main_heading_day)){
					$day_name = date('l', strtotime("Sunday + $available_day Days"));
					$main_heading_day = 'This '.$day_name;
				}
			}else{
				$delivery_message_heading = "No time slot provided yet.";
			}


			#collection/delivery times
			$class_array = ['text-info', 'text-warning', 'text-success' ,'text-purple', 'text-danger']; 
			$collect_dates = array();


			#collect two months period
			$current_date =  date('Y-m-d');
			$last_date = date('Y-m-d', strtotime('+2 month'));

			if($product_details[0]['service_lead_time']!='')
			{
				$date_diff = abs(strtotime(date('Y-m-d'))-strtotime(date('Y-m-d')));
					$date_diff = $date_diff/(60 * 60 * 24);
					$lead_time = $product_details[0]['service_lead_time'] - $date_diff;
					if($lead_time > 0)
					{
						$begin 		= new \DateTime( date('Y-m-d',strtotime('+'.$lead_time.' days')) );    // For adding service lead time
					}
					else
					{
						$begin 		= new \DateTime( date('Y-m-d') );   // For adding service lead time
					}
			}
			else
			{
				$begin 		= new \DateTime( date('Y-m-d') );
			}
			$end 		= new \DateTime( date('Y-m-d', strtotime('+2 month')) );
			$end 		= $end->modify( '+1 day' ); 
			$interval 	= new \DateInterval('P1D');
			$period 	= new \DatePeriod($begin, $interval ,$end);
			

			if(count($open_hrs) > 1){
				$open_hrs_status = 1; #available

				foreach ($open_hrs as $key => $info) {

					$key_count_same = 0;
					$key_count_diff = 0;
					foreach ($period as $key => $value) {
						if(count($holiday)=='0')
						{
							$holidayblank = '1';
						}
						else
						{
							$holidayblank = '0';
						}
						
							$date = $value->format('Y-m-d');
							if(count($holiday)>'0')
						{
							foreach($holiday as $holidays)
							{


								$holiday_start = explode(' ',$holidays->start);
								$holiday_end = explode(' ',$holidays->end);
								
								if(($date > $holiday_start[0] && $date > $holiday_end[0] && $date != $holiday_start[0] && $date != $holiday_end[0]) || ($date < $holiday_start[0] && $date < $holiday_end[0] && $date != $holiday_start[0] && $date != $holiday_end[0]))
									{   // If holiday is not equal to current date in the loop
											


						if($info['user_day'] == 7){
							$info['user_day'] = 0;
						}
						if($product_details[0]['service_lead_time']!='')
						{
							$date_diff = abs(strtotime(date('Y-m-d'))-strtotime(date('Y-m-d')));
							$date_diff = $date_diff/(60 * 60 * 24);
							$lead_time = $product_details[0]['service_lead_time'] - $date_diff;
							if($lead_time > 0)
							{
								$start_day = date('w',strtotime('+'.$lead_time. ' days'));
							}
							else
							{
								$start_day = date('w');
							}

						}
						else
						{	
							$start_day = date('w');
						}
						if(trim($info['user_day']) == $start_day && $key == 0){
							if($start_day!=date('w'))
							{
								$show_day = 'This '.date('l', $start_day);
							}
							else
							{
								if($product_details[0]['service_lead_time']!='')
								{
								$date_diff = abs(strtotime(date('Y-m-d'))-strtotime(date('Y-m-d')));
								$date_diff = $date_diff/(60 * 60 * 24);
								dd($product_details[0]['service_lead_time']);
								$lead_time = $product_details[0]['service_lead_time'] - $date_diff;
								}
								else
								{
									$lead_time = 0;
								}
								if($lead_time > 0)
								{
									$show_day = 'This '.date('l',strtotime("Sunday + $start_day Days"));
								}
								else
								{
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
								'start_time'=>$info['user_start_time'], 
								'end_time'=>$info['user_end_time'], 
								'date' => date('d M y ', strtotime($date)), 
								'raw_date' => date('Y-m-d', strtotime($date)), 
							];
						}elseif(date('w', strtotime($date)) == trim($info['user_day']) && $key > 0 ){
							if($key_count_same == 0){
								$day_display = 'This '.date('l', strtotime($date));
							}else{
								$day_display = date('l', strtotime($date));
							}
							$collect_dates[$key] = [
								'day' => $day_display, 
								'start_time'=>$info['user_start_time'], 
								'end_time'=>$info['user_end_time'],
								'date' => date('d M y ',  strtotime($date)), 
								'raw_date' => date('Y-m-d',  strtotime($date)), 
							];
							$key_count_same ++;
						}elseif(trim($info['user_day']) == date('w', strtotime($date)) && $key != 0){
							if($key_count_diff == 0){
								$day_display = 'This '.date('l', strtotime(''.trim($info['user_day_name']).''));
							}else{
								$day_display = date('l', strtotime(''.trim($info['user_day_name']).''));
							}
							$collect_dates[$key] = [
								'day' => $day_display, 
								'start_time'=>$info['user_start_time'], 
								'end_time'=>$info['user_end_time'], 
								'date' => date('d M y ',  strtotime($date)), 
								'raw_date' => date('Y-m-d',  strtotime($date)), 
							];
							$key_count_diff ++;
						}
						
								} // Holiday COndition ends here 

							}  // HOliday Loop Ends Here

					}  // IF HOLIDAY ARRAY IS NOT EMPTY
					else  // IF HOLIDAY ARRAY IS EMPTY
					{
						if($info['user_day'] == 7){
							$info['user_day'] = 0;
						}
						if($product_details[0]['service_lead_time']!='')
						{
							$date_diff = abs(strtotime(date('Y-m-d'))-strtotime(date('Y-m-d')));
							$date_diff = $date_diff/(60 * 60 * 24);
							$lead_time = $product_details[0]['service_lead_time'] - $date_diff;
							if($lead_time > 0)
							{
								$start_day = date('w',strtotime('+'.$lead_time. ' days'));
							}
							else
							{
								$start_day = date('w');
							}
						}
						else
						{	
							$start_day = date('w');
						}

						if(trim($info['user_day']) == $start_day && $key == 0){
							/*if($start_day!=date('w'))
							{
								$show_day = 'This '.date('l', $start_day);
							}
							else
							{
								$show_day = 'Today';
							}*/
							if($key_count_same == 0){
								$day_display = 'This '.date('l', strtotime($date));
							}else{
								$day_display = date('l', strtotime($date));
							}
							$collect_dates[$key] = [
								'day' => $day_display, 
								'start_time'=>$info['user_start_time'], 
								'end_time'=>$info['user_end_time'], 
								'date' => date('d M y ', strtotime($date)), 
								'raw_date' => date('Y-m-d', strtotime($date)), 
							];
						}elseif(date('w', strtotime($date)) == trim($info['user_day']) && $key > 0 ){
							if($key_count_same == 0){
								$day_display = 'This '.date('l', strtotime($date));
							}else{
								$day_display = date('l', strtotime($date));
							}
							$collect_dates[$key] = [
								'day' => $day_display, 
								'start_time'=>$info['user_start_time'], 
								'end_time'=>$info['user_end_time'],
								'date' => date('d M y ',  strtotime($date)), 
								'raw_date' => date('Y-m-d',  strtotime($date)), 
							];
							$key_count_same ++;
						}elseif(trim($info['user_day']) == date('w', strtotime($date)) && $key != 0){
							if($key_count_diff == 0){
								$day_display = 'This '.date('l', strtotime(''.trim($info['user_day_name']).''));
							}else{
								$day_display = date('l', strtotime(''.trim($info['user_day_name']).''));
							}
							$collect_dates[$key] = [
								'day' => $day_display, 
								'start_time'=>$info['user_start_time'], 
								'end_time'=>$info['user_end_time'], 
								'date' => date('d M y ',  strtotime($date)), 
								'raw_date' => date('Y-m-d',  strtotime($date)), 
							];
							$key_count_diff ++;
						}
					}


						
				 }   
				}

				usort($collect_dates, function($a, $b) {
				    return strtotime($a['date']) - strtotime($b['date']);
				});

				$collect_dates = array_unique($collect_dates, SORT_REGULAR);

			}elseif(count($open_hrs) == 1 ){

				$open_hrs_status = 1; #available
				$key_count = 0;
				foreach ($period as $key => $value) {
					
					$date = $value->format('Y-m-d');
					if(count($holiday)>0)
					{
					foreach($holiday as $holidays)
						{
							$holiday_start = explode(' ',$holidays->start);
							$holiday_end = explode(' ',$holidays->end);
							
							if(($date > $holiday_start[0] && $date > $holiday_end[0] && $date != $holiday_start[0] && $date != $holiday_end[0]) || ($date < $holiday_start[0] && $date < $holiday_end[0] && $date != $holiday_start[0] && $date != $holiday_end[0]))
								{   // If holiday is not equal to current date in the loop


					
					if($open_hrs[0]->user_day == 7){
						$open_hrs[0]->user_day = 0;
					}

					if(date('w', strtotime($date)) == $open_hrs[0]->user_day && $key == 0){
						$collect_dates[$key] = [
							'day' => 'Today', 
							'start_time'=>$open_hrs[0]->user_start_time, 
							'end_time'=>$open_hrs[0]->user_end_time, 
							'date' => date('d M y ', strtotime($date)), 
							'raw_date' => date('Y-m-d', strtotime($date)), 
						];
					}elseif(date('w', strtotime($date)) == $open_hrs[0]->user_day && $key > 0){
						if($key_count == 0){
							$day_display = 'This '.date('l', strtotime($date));
						}else{
							$day_display = date('l', strtotime($date));
						}
						$collect_dates[$key] = [
							'day' => $day_display, 
							'start_time'=>$open_hrs[0]->user_start_time, 
							'end_time'=>$open_hrs[0]->user_end_time, 
							'date' => date('d M y ',  strtotime($date)), 
							'raw_date' => date('Y-m-d',  strtotime($date)), 
						];
						$key_count ++;
					}  
					}  // Holiday condition ends here 
					}  // Holiday Loop ends here  
				}  // IF HOLIDAY ARRAY IS NOT EMPTY
					else   //IF HOLIDAY ARRAY IS EMPTY
					{
						if($open_hrs[0]->user_day == 7){
						$open_hrs[0]->user_day = 0;
					}

					if(date('w', strtotime($date)) == $open_hrs[0]->user_day && $key == 0){
						$collect_dates[$key] = [
							'day' => 'Today', 
							'start_time'=>$open_hrs[0]->user_start_time, 
							'end_time'=>$open_hrs[0]->user_end_time, 
							'date' => date('d M y ', strtotime($date)), 
							'raw_date' => date('Y-m-d', strtotime($date)), 
						];
					}elseif(date('w', strtotime($date)) == $open_hrs[0]->user_day && $key > 0){
						if($key_count == 0){
							$day_display = 'This '.date('l', strtotime($date));
						}else{
							$day_display = date('l', strtotime($date));
						}
						$collect_dates[$key] = [
							'day' => $day_display, 
							'start_time'=>$open_hrs[0]->user_start_time, 
							'end_time'=>$open_hrs[0]->user_end_time, 
							'date' => date('d M y ',  strtotime($date)), 
							'raw_date' => date('Y-m-d',  strtotime($date)), 
						];
						$key_count ++;
					}  
					}  // IF HOLIDAY ARRAY IS EMPTY
				}

				

				usort($collect_dates, function($a, $b) {
				    return strtotime($a['date']) - strtotime($b['date']);
				});

				$collect_dates = array_unique($collect_dates, SORT_REGULAR);
			}else{
				$open_hrs_status = 0; #unavailable
			}
			
			#color

			$color_class= [
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
			$color_values= [
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
			$braintree_customer_id = User_card::where('user_id',$userId)->pluck('braintree_customer_id');
			if(count($braintree_customer_id) > 0){
				$braintree_customer_id = $braintree_customer_id[0];
				#get client token

				$clientToken = Braintree_ClientToken::generate([
				    "customerId" => $braintree_customer_id
				]);
				
			}else{
				$braintree_customer_id = '';
				$clientToken = "";
			}

			#get the user's id of the product
			$avataricon =User::where('id',Auth::id())->get();
			$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
			$today = date('Y-m-d h:i:s');
			$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');
			$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
			$sellerid = Auth::id();
			$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
			$noofsale = count($salecount);
			return view('users.product.product_page', compact("userId","product_details","sellercountry",'delivery_message_heading','main_heading_day','timeslot_heading','collect_dates','open_hrs_status','color_class','braintree_customer_id','clientToken','color_values',"avataricon","daysale","currency_symbol","salecount","noofsale"));

		}catch(\Exception $e){
			return $e->getMessage().' '.$e->getLine();
		}
	}

	/*
	placed Order lists
	*/
	public function success(Request $request)
	{
		try{
			$userId = Auth::id();
			$request->order_id = base64_decode($request->order_id);
			//$request = $request->all();
			$orderdetails = Order::with(['sellerDetails','userDetails','product_type','product_details','currency'])
							->where('id',$request->order_id)
							->get();

			//echo "<pre>";print_r($orderdetails);die;				
			//return view('users.order.my_order',compact('orderdetails'));
			$avataricon =User::where('id',Auth::id())->get();
			$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
			$today = date('Y-m-d h:i:s');
			$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');
			$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
			$sellerid = Auth::id();
			$salecount = $salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
			$noofsale = count($salecount);
			  return view('users.order.success',compact('orderdetails',"avataricon","daysale","currency_symbol","salecount","noofsale"));
		}catch(\Exception $e){
			return $e->getMessage();
		}
	}
	public function my_order(Request $request)
	{

		try{
			$userId = Auth::id();
			$orderdetails = Order::with(['sellerDetails','userDetails','product_type','product_details','currency'])
							->where('user_id',$userId)
							->get();
			//echo "<pre>";print_r($orderdetails);die;	
			$avataricon =User::where('id',Auth::id())->get();
			$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
			$today = date('Y-m-d h:i:s');
			$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');
			$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();	
			$sellerid = Auth::id();
			$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
			$noofsale = count($salecount);		
			return view('users.order.my_order',compact('orderdetails',"avataricon","daysale","currency_symbol","salecount","noofsale"));
			  //return view('users.order.my_order',compact('orderdetails'));
		}catch(\Exception $e){
			return $e->getMessage();
		}
	} #my_order

	/*
	Sales lists
	*/
	public function my_sales(Request $request)
	{

		try{
			$seller_id = Auth::id();
			$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
			$today = date('Y-m-d h:i:s');
			$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');
			$soldItems = Order::with(['sellerDetails','userDetails','product_type','product_details','currency'])
							->where('seller_id',$seller_id)
							->get();
			// echo "<pre>";print_r($soldItems);die;
			$avataricon =User::where('id',Auth::id())->get();	
			$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
			$sellerid = Auth::id();
			$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
			$noofsale = count($salecount);			
			return view('users.sales.my_sales',compact('soldItems',"avataricon","daysale","currency_symbol","salecount","noofsale"));
		}catch(\Exception $e){
			return $e->getMessage();
		}
	} #my_sales
	public function inpost_label(Request $request)
	{
		$order = Order::where('id','=',$request->id)->get();
		$buyer_information = User::where('id',$order[0]['user_id'])->get();
		$customerEmail = 'antony@contact25.com'; 


			// -- TEST TOKEN / LOCATION -- ///
			$token = 'fcefb034514b32722173c0480f05d107c6e4cd5ac8e39700f17cf96f2371ba3d'; 
			$location = 'stage-api-uk';
			
			$data = array
						( 	
							'sender_phone' => Auth::user()->contact_no, 
							'customer_reference' => $request->id, 
							'parcel' => array('size' => 'A'),
							'sender_email'=>Auth::user()->email,
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
								'first_name' => $buyer_information[0]['name'], 
								'flat_no' => null, 
								'last_name' => null, 
								'phone' => $buyer_information[0]['contact_no'], 
								'post_code' => $buyer_information[0]['pincode'], 
								'province' => null, 
								'street' => $buyer_information[0]['street_address1']
								
							),
							
				);
#die(var_dump($data));


			$baseUrl = 'https://'.$location.'.easypack24.net/v4/'; 
			//$path = '/customers/'.$customerEmail.'/parcels'; 
			$path = '/customers/'.$buyer_information[0]['email'].'/returns'; 
			$headers = array( "Authorization: Bearer $token", "Content-Type: application/json" ); 

			/*$ch = curl_init($baseUrl.$path); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
			$rawResponse = curl_exec($ch); 
			dd($rawResponse);
			curl_close($ch);*/

			 $response = Curl::to($baseUrl.$path)
        ->withData( $data)
        ->post();
		dd($response);
	}
	public function distance($sellerid,$buyerid)
	{
		$sellerlocation = User::where('id',$sellerid)->get();
		$buyerlocation = User::where('id',$buyerid)->get();

		$response = Curl::to("https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$sellerlocation[0]['lat'].",".$sellerlocation[0]['lng']."&destinations=".$buyerlocation[0]['lat'].",".$buyerlocation[0]['lng']."&departure_time=now&key=AIzaSyD08Emygz5W4HKOZXvogXKb5zYjA8ZRMaQ")
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

		$updateval = User::where('id',Auth::user()->id)->update(['box_preference'=>$request->box_preference]);
		if(!empty($updateval))
		{
			$response['success'] = 1;
			return response()->json($response);
		}
		else
		{
			$response['success'] = 0;
			$response['message'] = 'Error';
			return response()->json($response);
		}
	}
	public function find_friend(Request $request)
	{
		$country = Auth::user()->country;
		$friendsearch = User::Where('email', 'like', '%' . $request->email . '%')->Orwhere('contact_no', 'like', '%' . $request->email . '%')->where('country',$country)->get();
		$response['message'] = '';
		if(count($friendsearch)>0)
		{
			$response['success'] = 1;
			foreach($friendsearch as $search)
			{
				if($search->id!=Auth::user()->id)
				{
				$friendproduct = Product::where('user_id',$search->id)->where('p_quantity','!=','0')->get();
				$response['message'] .= '<tr id="friend'.$search->id.'"><td class="txt-oflo">'.$search->name.'<span class="badge badge-warning badge-pill" style="cursor: pointer;"><i class="fas fa-user-plus"></i> '.count($friendproduct).'</span></td>';
				$friendrequestcheck = friend::where('friend_id_2',$search->id)->where('friend_id_1',Auth::user()->id)->get();
				if(count($friendrequestcheck)=='0')
				{
					$friendrequestcheck = friend::where('friend_id_1',$search->id)->where('friend_id_2',Auth::user()->id)->get();
				}
				/*$friendrequestcheck = friend::where(function ($query) {
									    $query->where('friend_id_2', $search->id)
									        ->where('friend_id_1',Auth::user()->id);
									})->orWhere(function($query) {
									    $query->where('friend_id_1', $search->id)
									        ->where('friend_id_2',Auth::user()->id);	
									})->get();*/
				
				if($search->friend_status=='1' && count($friendrequestcheck)=='0' )
				{
					$response['message'].='<td><span class="text-success" id="sendbuttontext'.$search->id.'"><button type="button" class="btn btn-secondary btn-rounded" id="searchfriendrequest'.$search->id.'" onclick="sendfriendrequest('.$search->id.')"> <i class="far fa-heart"></i> Invite</button> / <button type="button" class="btn btn-secondary btn-rounded" id="ignorefriend'.$search->id.'" onclick="removefriend('.$search->id.')"> <i class="fa fa-times"></i> Ignore</button></span></td>';
                }
                if(count($friendrequestcheck)>'0' && $friendrequestcheck[0]['status']=='0')
                {
                	$response['message'].='<td><span class="text-success" id="sendbuttontext'.$search->id.'"><button type="button" class="btn btn-secondary btn-rounded"><i class="far fa-heart"></i> Pending</button></span></td>';
				}
				if(count($friendrequestcheck)>'0' && $friendrequestcheck[0]['status']=='1' )
                {
                	$response['message'].='<td><span class="text-success" id="sendbuttontext'.$search->id.'"><button type="button" class="btn btn-secondary btn-rounded"><i class="far fa-heart"></i> Friends</button></span></td>';
				}
					$response['message'].='</tr>';
			}
			}
		}
		else
		{
			$response['success'] = 1;
			$response['message'] .= '<tr><td></td><td><p style=text-align:center></p></td></tr>';
		}

		return response()->json($response);
	}
	public function send_friend_request(Request $request)
	{
		$friend_id_1 = Auth::user()->id;
		$friend_id_2 = $request->senderid;
		$inserval = friend::create(['friend_id_1'=>$friend_id_1,
									'friend_id_2'=>$friend_id_2,
									 'status'=>'0'])->id;
		if(!empty($inserval))
		{
			$response['success'] = 1;
			$response['message'] = '<button type="button" class="btn btn-secondary btn-rounded"><i class="far fa-heart"></i> Pending</button>';
		}
		else
		{
			$response['success'] = 0;
		}
		return response()->json($response);

	}
	public function accept_friend_request(Request $request)
	{
		$updateval = friend::where('id',$request->id)->update(['status'=>'1']);
		if(!empty($updateval))
		{
			$response['success'] = 1;
			$response['message'] = 'You are now friends';
		}
		else
		{
			$response['success'] = 0;
			$response['message'] = 'Error';
		}
		return response()->json($response);
	}
	public function delete_friend_request(Request $request)
	{
		$updateval = friend::where('id',$request->id)->delete();
		if(!empty($updateval))
		{	User::where('id',$request->friend_id_1)->increment('reject_count', 1);
			$response['success'] = 1;
			$response['message'] = 'Friend Request Removed';
		}
		else
		{
			$response['success'] = 0;
			$response['message'] = 'Error';
		}
		return response()->json($response);
	}
	public function create_group(Request $request)
	{
		$friendlist = $request->friend;
		$users = implode(',',$friendlist);
		$user_id = Auth::user()->id;
		$group_name = $request->group_name;
		$insertval = friend_group::create(['user_id'=>$user_id,
											'group_name'=>$group_name,
											'users'=>$users]);
		if(!empty($insertval))
		{
			$response['status'] = 0;
			return back();
		}
		else
		{
			return back();
		}

	}
	public function delete_group(Request $request)
	{
		$delete = friend_group::where('id',$request->id)->delete();
		if(!empty($delete))
		{
			$response['success'] = '1';

		}
		else
		{
			$response['success'] = '0';
			$response['message'] = 'Error while deleting data';
		}
		return response()->json($response);
	}
	public function terms()
	{
		$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
		$today = date('Y-m-d h:i:s');
		$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
		$noofsale = count($salecount);
		$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
		$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');

		return view('users.terms',compact("currency_symbol","daysale","noofsale","salecount"));
	}
	public function privacy()
	{
		$prev_date = date('Y-m-d h:i:s', strtotime("-30 days"));
		$today = date('Y-m-d h:i:s');
		$salecount = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.o_completed','=','0')->where('orders.seller_id',Auth::id())->get();
		$noofsale = count($salecount);
		$currency_symbol = DB::table('users')->join('currencies','currencies.code','=','users.currency_code')->where('users.id','=',Auth::user()->id)->get();
		$daysale = DB::table('orders')->join('products','products.id','=','orders.o_product_id')->where('orders.seller_id',Auth::id())->where('orders.created_at','>=',$prev_date)->where('orders.created_at','<=',$today)->where('orders.o_completed','=','1')->sum('products.p_selling_price');

		return view('users.privacy',compact("currency_symbol","daysale","noofsale","salecount"));

	}
}
