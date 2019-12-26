<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/home', 'HomeController@home')->name('home');
#Google Map
Route::post('/fetchproducts','HomeController@fetchproducts')->name('fetchproducts');
Route::get('/logout', function(){
      Auth::logout();
  	  return redirect('/login');
});

//Route::post('/logout', 'LoginController@logout');
Route::get('/', 'HomeController@index')->middleware('guest')->name('homepage');
Route::post('/newnearme','HomeController@newnearme')->middleware('guest')->name('newnearme');


Route::get('/configcache', function () {
    $exitCode = Artisan::call('config:cache');
    print_r($exitCode);
});

Auth::routes(['verify' => true]);

#for users
Route::group(['middleware' => ['checkLoginRole']], function () {
    # My Money
    Route::get('/my_money','MoneyController@my_money')->name('my_money');
    #Create Merchant Account
    Route::post('/create_merchant_account','MoneyController@create_merchant_account')->name('create_merchant_account');
    #dashboard
	Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
	#Terms Page
	Route::get('/terms', 'DashboardController@terms')->name('terms');
    #Communication
    Route::get('/communication','DashboardController@communication')->name('communication');
    #token for twilio chat
    Route::POST('/token','TokenController@generate')->name('generatetoken');
	#Privacy Policy Page
	Route::get('/privacy','DashboardController@privacy')->name('privacy');
	#Find Friend
	Route::post('/find-friend', 'DashboardController@find_friend')->name('find-friend');
	#send friend request
	Route::post('/send-friend-request','DashboardController@send_friend_request')->name('send-friend-request');

	#accept friend request
	Route::post('/accept-friend-request','DashboardController@accept_friend_request')->name('accept-friend-request');
	#delete friend request
	Route::post('/delete-friend-request','DashboardController@delete_friend_request')->name('delete-friend-request');
	#Create Group
	Route::post('/create-group','DashboardController@create_group')->name('create-group');
	#Delete Group
	Route::post('/delete-group','DashboardController@delete_group')->name('delete-group');
	#for products
	Route::get('/myproducts', 'DashboardController@product_list')->name('myproducts');
	#Product List Ajax
	Route::post('/product_list_ajax','DashboardController@product_list_ajax')->name('product_list_ajax');
	#Product Box List Ajax
	Route::post('/product_box_list_ajax','DashboardController@product_box_list_ajax')->name('product_box_list_ajax');
	#Product according to box
	Route::post('/product_box_ajax','DashboardController@product_box_ajax')->name('product_box_ajax');
	#Product According to All Boxes
	Route::post('/product_all_box_ajax','DashboardController@product_all_box_ajax')->name('product_all_box_ajax');
	 //Route::get('/product_add','DashboardController@product_add')->name('product_add');
	 //Route::get('/product_add/{id}','DashboardController@product_add')->name('product_add');
	Route::get('/add_product/','DashboardController@add_product')->name('add_product');
	Route::get('/add_product/{product_id}','DashboardController@add_product')->name('edit_product');

	Route::match(['get','post'], '/product_add','DashboardController@product_add')->name('product_add');
	Route::match(['get','post'], '/product_add_process','DashboardController@product_add_process')->name('product_add_process');
	Route::post('/product_add_process', 'DashboardController@product_add_process')->name('product_add_process');

	#Product Update
	Route::post('/updateproduct','DashboardController@updateproduct')->name('updateproduct');

	#for profi;e
	Route::get('/view_profile', 'DashboardController@view_profile')->name('view_profile');
	Route::post('/edit_profile', 'DashboardController@edit_profile')->name('edit_profile');
	Route::post('/add_holiday','DashboardController@add_holiday')->name('add_holiday');
	Route::post('/delete_holiday','DashboardController@delete_holiday')->name('delete_holiday');

	#For Return Options
    Route::post('/update_return_option','DashboardController@update_return_option')->name('update_return_option');

	#enable 2 way auth
	Route::post('/two_auth', 'DashboardController@two_auth')->name('two_auth');
	Route::post('/two_auth_otp', 'DashboardController@two_auth_otp')->name('two_auth_otp');

	#user shop listing
    Route::get('/shop/{user_id}', 'DashboardController@shop_listing')->name('shop_listing');
	#product listing
	Route::get('/products', 'DashboardController@products_friends')->name('products_friends');

	#Searching Bar
	//Route::post('/search','DashboardController@search')->name('search');
    Route::match(['get', 'post'],'/search','DashboardController@search')->name('search');

	#product page
	//Route::get('/products_page/{product_id}/{user_id}', 'DashboardController@products_page')->name('products_page');
	//Route::get('/product/{slug}', 'DashboardController@products_page')->name('products_page');
	Route::get('{slug}-{country}/{id}/', 'DashboardController@products_page')->where('slug', '^(?!slug).*$')->name('products_page');

	#Update Selling / Sell To / Lend To
	Route::post('/updateselling', 'DashboardController@updateselling')->name('updateselling');

	#Update Room
	Route::post('/update_room', 'DashboardController@update_room')->name('updateroom');
	#Move Box
	Route::post('/move_box', 'DashboardController@move_box')->name('movebox');

	#Update Communication
	Route::post('/update_communication','DashboardController@update_communication')->name('updatecommunication');
	#Update Delivery
	Route::post('/update_delivery','DashboardController@update_delivery')->name('updatedelivery');
	#Update Delivery Option For Customer 
	Route::post('/updatedeliveryoption','DashboardController@updatedeliveryoption')->name('updatedeliveryoption');
	#Add Delivery
	Route::post('/add_delivery','DashboardController@add_delivery')->name('adddelivery');
	#Delete Delivery
	Route::post('/delete_delivery','DashboardController@delete_delivery')->name('deletedelivery');
	#Update Return Status Address & Return Label
	Route::post('/update_return_status','DashboardController@update_return_status')->name('update_return_status');
	#opening hours
	Route::post('/addOpenHours', 'DashboardController@addOpenHours')->name('addOpenHours');
	Route::post('/removeOpenHours', 'DashboardController@removeOpenHours')->name('removeOpenHours');
	#Service Hours
	Route::post('/addServiceHours', 'DashboardController@addServiceHours')->name('addServiceHours');
	Route::post('/removeServiceHours', 'DashboardController@removeServiceHours')->name('removeServiceHours');

	#Distance Calculation & Time
	Route::get('/distance/{sellerid}/{buyerid}','DashboardController@distance')->name('distance');

	#Update Box Preference
	Route::post('/update_box_preference','DashboardController@update_box_preference')->name('updateboxpreference');

	#payment round1
	Route::get('/payment/process', 'PaymentsController@process')->name('payment.process');

	#save order
	Route::post('/saveOrder', 'PaymentsController@saveOrder')->name('saveOrder');

	#Free Order
	Route::post('/freeorder', 'PaymentsController@freeorder')->name('freeorder');

	#Pay With Credit
    Route::post('/paywithcredit','PaymentsController@paywithcredit')->name('paywithcredit');

	#list all my Orders
	Route::get('/my_order', 'DashboardController@my_order')->name('my_order');

	#list all my subscription
    Route::get('/my_subscription','DashboardController@my_subscription')->name('my_subscription');

    #list all subscribed users
    Route::get('/subscribed_users','DashboardController@subscribed_users')->name('subscribed_users');
	# success page display after successfull payment
	Route::get('/success/{order_id}', 'DashboardController@success')->name('success');

	# If the order is placed from credit amount
    Route::get('/success/{order_id}/{discount}', 'DashboardController@success')->name('success');

	#list all my sales
	Route::get('/my_sales', 'DashboardController@my_sales')->name('my_sales');
	#list all return requests
    Route::get('/return_request','DashboardController@return_request')->name('return_request');
    #accept inpost return request
    Route::post('/accept_return_request_inpost','DashboardController@accept_return_request_inpost')->name('accept_return_request_inpost');
    #accept return request
    Route::post('/accept_return_request','DashboardController@accept_return_request')->name('accept_return_request');
    #decline return request
    Route::post('/decline_return_request','DashboardController@decline_return_request')->name('decline_return_request');
    #refund return request
    Route::post('/refund_return_request','DashboardController@refund_return_request')->name('refund_return_request');
	#sales sorting ajax
    Route::post('/my_sales_ajax', 'DashboardController@my_sales_ajax')->name('my_sales_ajax');
	#seller cancel order 
	Route::post('/cancelorder','DashboardController@cancelorder')->name('cancelorder');
	# Update Tracking No
	Route::post('/update_tracking_link','DashboardController@update_tracking_link')->name('update_tracking_link');
	#seller complete order
	Route::post('/completeorder','DashboardController@completeorder')->name('completeorder');
	#seller collected order 
	Route::post('/collectorder','DashboardController@collectorder')->name('collectorder');
	#buyer cancel order
	Route::post('/buyercancelorder','DashboardController@buyercancelorder')->name('buyercancelorder');
	#buyer return order
    Route::post('/buyerreturnorder','DashboardController@buyerreturnorder')->name('buyerreturnorder');
	#buyer cancel subscription
    Route::post('/buyercancelsubscription','DashboardController@buyercancelsubscription')->name('buyercancelsubscription');
	#buyer claim it not delivered
    Route::post('/claimitnotdelivered','DashboardController@claimitnotdelivered')->name('claimitnotdelivered');
	#claim as delivered by buyer
    Route::post('/buyerclaimdelivered','DashboardController@buyerclaimdelivered')->name('buyerclaimdelivered');
	#INPOST LABEL
	Route::get('/inpost_label/{id}', 'DashboardController@inpost_label')->name('inpost-label');
	#Cancel Label
    Route::get('/cancel_label/{parcel_id}','DashboardController@cancel_label')->name('cancel_label');
    #View Label
    Route::get('/view_label/{parcel_id}','DashboardController@view_label')->name('view_label');
	#INPOST RETURN
	Route::post('/update_inpost_return', 'DashboardController@update_inpost_return')->name('update_inpost_return');

	#new product page
	//Route::get('/add_product', 'DashboardController@add_product')->name('add_product');

	#product page - item
	Route::get('/add_pro_item', 'DashboardController@add_pro_item')->name('add_pro_item');


	#product page - service
	Route::get('/add_pro_service', 'DashboardController@add_pro_service')->name('add_pro_service');
    Route::get('/add_pro_service/{product_id}', 'DashboardController@add_pro_service')->name('edit_pro_service');

	#product page - subscription
	Route::get('/add_pro_subs', 'DashboardController@add_pro_subs')->name('add_pro_subs');
    Route::get('/add_pro_subs/{product_id}', 'DashboardController@add_pro_subs')->name('edit_pro_subs');
    #Google Map
    Route::post('/fetchlocationproduct','DashboardController@fetchlocationproduct')->name('fetchlocationproduct');
    #accept friend request through QR CODE
    Route::post('/qr/{friend_id_1}/{friend_id_2}','DashboardController@qrcode')->name('qrcode');
	
});

#for admin
Route::group(['middleware' => ['checkAdminRole']], function () {
	Route::get('/admin', 'AdminController@index')->name('admin');
	Route::get('/account-settings', 'AdminController@account_setting')->name('account-settings');
	Route::post('/up_account_setting', 'AdminController@up_account_setting')->name('up_account_setting');
	Route::get('/customers', 'AdminController@customer_listing');
	Route::post('/admin_two_auth', 'AdminController@admin_two_auth')->name('admin_two_auth');
	Route::post('/admin_two_auth_otp', 'AdminController@admin_two_auth_otp')->name('admin_two_auth_otp');
	Route::get('/system_setting', 'AdminController@system_setting')->name('system_setting');
	Route::post('/system_setting', 'AdminController@system_setting')->name('system_setting_update');
});

#register
Route::post('/register', 'Auth\RegisterController@register')->name('register.store');
Route::post('/register_otp/', 'Auth\RegisterController@register_otp')->name('register.otp');
#login
Route::post('/login', 'Auth\LoginController@login')->name('login.store');
Route::post('/login_otp/', 'Auth\LoginController@login_otp')->name('login.otp');

#reset password email
Route::post('/forgot', 'Auth\ResetPasswordController@forgotEmail')->name('reset.forgot');

#test privacy policy
/*Route::get('privacy', function () {
    return 'privacy policy';
});*/

#social logins
Route::get('/redirect/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('/callback/{provider}', 'Auth\LoginController@handleProviderCallback');

#impersonate
Route::get ( 'impersonate/{user_id}', 'AdminController@impersonate' );
Route::get ( 'impersonate_leave', 'DashboardController@impersonate_leave' );
