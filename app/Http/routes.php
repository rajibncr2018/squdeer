<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



Route::get('/', function () {
    return view('welcome');
});


/*
|-------------------------------------------------------------------------
|API Routes
|------------------------------------------------------------------------
*/
Route::group(['prefix'=>'api'],function(){
	
    Route::post('/registration-step1','Api\UsersController@registration_step1');
    Route::post('/registration','Api\UsersController@registration');
    Route::any('/emailverification/{id}','Api\UsersController@emailverification');
    Route::post('/registration-step2','Api\UsersController@registration_step2');
    Route::post('/login','Api\UsersController@login');
    Route::post('/logout','Api\UsersController@logout');

    Route::post('/add_staff','Api\StaffsController@add_staff');
    Route::post('/staff_list','Api\StaffsController@staff_list');

});



/*
|-------------------------------------------------------------------------
|Website Routes
|------------------------------------------------------------------------
*/
Route::group(['prefix'=>''],function(){
	
    Route::get('/login','Website\UsersController@login');
    Route::any('/','Website\UsersController@registration');
    Route::get('/registration-step1','Website\UsersController@registration_step1');
    Route::get('/registration-step2','Website\UsersController@registration_step2');
    Route::get('/dashboard','Website\UsersController@dashboard');
    Route::get('/logout', 'Website\UsersController@logout');
    Route::any('/thank-you','Website\UsersController@thank_you');
    


    //Only for link purpose

    Route::get('/calendar','Website\UsersController@calendar');
    Route::get('/gift-certificates','Website\UsersController@gift_certificates');
    Route::get('/marketing-discount-coupons','Website\UsersController@marketing_discount_coupons');
    Route::get('/offers','Website\UsersController@offers');
    Route::get('/reports','Website\UsersController@reports');
    Route::get('/client-database','Website\UsersController@client_database');
    Route::get('/staff-details','Website\UsersController@staff_details');
    Route::get('/booking-options','Website\UsersController@booking_options');
    Route::get('/booking-rules','Website\UsersController@booking_rules');
    Route::get('/booking-policies','Website\UsersController@booking_policies');
    Route::get('/notification-settings','Website\UsersController@notification_settings');
    Route::get('/email-confirmation','Website\UsersController@email_confirmation');
    Route::get('/settings-membership','Website\UsersController@settings_membership');
    Route::get('/integration','Website\UsersController@integration');
    Route::get('/settings-business-hours','Website\UsersController@settings_business_hours');
    Route::get('/business-details1','Website\UsersController@business_details1');
    Route::get('/business-details2','Website\UsersController@business_details2');
    Route::get('/invitees','Website\UsersController@invitees');
    Route::get('/services','Website\UsersController@services');
	Route::get('/payment-options','Website\UsersController@payment_options');
    Route::get('/invoice','Website\UsersController@invoice');
    Route::get('/create-invoice','Website\UsersController@create_invoice');
    Route::get('/invoice-details','Website\UsersController@invoice_details');
    Route::get('/invite-contacts','Website\UsersController@invite_contacts');

});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware'=>'auth', 'prefix'=>'admin'],function(){ 
	//For backend
	Route::get('/dashboard', 'Admin\AdminController@index');
	Route::get('/change-password', 'Admin\AdminController@change_password');
	Route::post('/update-password', 'Admin\AdminController@update_password');
	
	
	Route::get('/profile-block-unblock', 'Admin\AdminController@profile_block_unblock');
	Route::get('/verify-multiple-profile', 'Admin\AdminController@verify_multiple_profile');
	Route::get('/block-multiple-profile', 'Admin\AdminController@block_multiple_profile');
	
	Route::get('/category', 'Admin\AdminController@category');
	Route::get('/add-category/{any?}', 'Admin\AdminController@add_category');
	Route::post('/modify-category', 'Admin\AdminController@modify_category');

	Route::get('/country/{any?}', 'Admin\AdminController@country');
	Route::get('/add-country/{any?}', 'Admin\AdminController@add_country');
	Route::post('/modify-country', 'Admin\AdminController@modify_country');
    
	Route::get('/profession/{any?}', 'Admin\AdminController@profession');
	Route::get('/add-profession/{any?}', 'Admin\AdminController@add_profession');
	Route::post('/modify-profession', 'Admin\AdminController@modify_profession');
	
    Route::post('/update-password', 'Admin\AdminController@update_password');
    Route::post('/update-password', 'Admin\AdminController@update_password');

    Route::any('/update-status', 'Admin\AdminController@update_status');
    Route::get('/delete-single-entity', 'Admin\AdminController@delete_single_entity');
	Route::get('/delete-multiple-entity', 'Admin\AdminController@delete_multiple_entity');

	Route::get('/currency/{any?}', 'Admin\AdminController@currency');
    Route::get('/add-currency/{any?}', 'Admin\AdminController@add_currency');
    Route::post('/modify-currency', 'Admin\AdminController@modify_currency');

    //Route::post('/change-status', 'Admin\AdminController@change_status');

}); 


Route::get('admin/login', 'Admin\AdminController@login');
Route::get('admin/logout', 'Admin\AdminController@logout');
Route::post('admin/check-login', 'Admin\AdminController@check_login');



