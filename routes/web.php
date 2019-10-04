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

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');
Route::get('/home', 'HomeController@home')->name('home')->middleware('guest');

Route::get('/configcache', function () {
    $exitCode = Artisan::call('config:cache');
    print_r($exitCode);
});

Auth::routes(['verify' => true]);

#for users
Route::group(['middleware' => ['checkLoginRole']], function () {
	Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
	#Terms Page
	Route::get('/terms', 'DashboardController@terms')->name('terms');

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
	Route::get('/myproducts', 'DashboardController@product_list');
	 //Route::get('/product_add','DashboardController@product_add')->name('product_add');
	 //Route::get('/product_add/{id}','DashboardController@product_add')->name('product_add');
	Route::get('/add_product/','DashboardController@add_product')->name('add_product');
	Route::get('/add_product/{product_id}','DashboardController@add_product')->name('edit_product');

	Route::match(['get','post'], '/product_add','DashboardController@product_add')->name('product_add');
	Route::match(['get','post'], '/product_add_process','DashboardController@product_add_process')->name('product_add_process');
	Route::post('/product_add_process', 'DashboardController@product_add_process')->name('product_add_process');

	#for profi;e
	Route::get('/view_profile', 'DashboardController@view_profile')->name('view_profile');
	Route::post('/edit_profile', 'DashboardController@edit_profile')->name('edit_profile');
	Route::post('/add_holiday','DashboardController@add_holiday')->name('add_holiday');
	Route::post('/delete_holiday','DashboardController@delete_holiday')->name('delete_holiday');

	#enable 2 way auth
	Route::post('/two_auth', 'DashboardController@two_auth')->name('two_auth');
	Route::post('/two_auth_otp', 'DashboardController@two_auth_otp')->name('two_auth_otp');

	#product listing
	Route::get('/products', 'DashboardController@products_friends')->name('products_friends');

	#Searching Bar
	Route::post('/search','DashboardController@search')->name('search');

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
	#Add Delivery
	Route::post('/add_delivery','DashboardController@add_delivery')->name('adddelivery');
	#Delete Delivery
	Route::post('/delete_delivery','DashboardController@delete_delivery')->name('deletedelivery');
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

	#list all my Orders
	Route::get('/my_order', 'DashboardController@my_order')->name('my_order');

	# success page display after successfull payment
	Route::get('/success/{order_id}', 'DashboardController@success')->name('success');

	#list all my sales
	Route::get('/my_sales', 'DashboardController@my_sales')->name('my_sales');
	#INPOST LABEL
	Route::get('/inpost_label/{id}', 'DashboardController@inpost_label')->name('inpost-label');

	#new product page
	//Route::get('/add_product', 'DashboardController@add_product')->name('add_product');

	#product page - item
	Route::get('/add_pro_item', 'DashboardController@add_pro_item')->name('add_pro_item');


	#product page - service
	Route::get('/add_pro_service', 'DashboardController@add_pro_service')->name('add_pro_service');

	#product page - subscription
	Route::get('/add_pro_subs', 'DashboardController@add_pro_subs')->name('add_pro_subs');
	
});

#for admin
Route::group(['middleware' => ['checkAdminRole']], function () {
	Route::get('/admin', 'AdminController@index')->name('admin');
	Route::get('/account-settings', 'AdminController@account_setting')->name('account-settings');
	Route::post('/up_account_setting', 'AdminController@up_account_setting')->name('up_account_setting');
	Route::get('/customers', 'AdminController@customer_listing');
	Route::post('/admin_two_auth', 'AdminController@admin_two_auth')->name('admin_two_auth');
	Route::post('/admin_two_auth_otp', 'AdminController@admin_two_auth_otp')->name('admin_two_auth_otp');
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
