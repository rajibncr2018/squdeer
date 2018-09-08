<?php 
/**
 * @Author : NCRTS
 * User Controller for Website
 * 
 */
namespace App\Http\Controllers\Website;
use App\Http\Requests;
use App\Http\Controllers\BaseApiController as ApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\TablesController;
use Validator;
use Session;
use Cookie;

use DateTime;

class UsersController extends ApiController {

	function __construct(Request $input){

		parent::__construct($input);

	}

	//***** User Login *****// 
	public function login()
	{
        // Check User Login. If logged in redirect to dashboard //
		$authdata = $this->website_login_checked();
		if((!empty($authdata['user_no']) || $authdata['user_no'] > 0 ) && !empty($authdata['user_request_key'])){
			$this->remove_all_cookies(); // for manualy cookie remove testing
			return redirect('/dashboard');
		}
        return view('website.user.login.login');
	}

	//***** logout section *****//

	
	//***** logout section *****//
	public function logout()
	{
		// Check User Login. If not logged in redirect to login page //
		$authdata = $this->website_login_checked();
		if((empty($authdata['user_no']) || ($authdata['user_no']<=0)) || (empty($authdata['user_request_key']))){
			return redirect('/login');
		}
		// API call //
		$post_data = $authdata;
		//print_r($post_data); die();
		$url_func_name="logout";
		$return = $this->curl_call($url_func_name,$post_data);
		// Check response status. If success return data //
		if(isset($return->response_status)){
			return redirect('/login');
		}
		else{
			$this->remove_all_cookies();
			return $return;
		}
	}


	//***** User Registartion *****//
	public function registration(Request $data)
	{
		$email = $data->input('email');
		if($email)
		{
			$condition = array(
                        array('email','=',$email),
                    );
        	$checkEmail = $this->common_model->fetchData('user',$condition);
        	if(!empty($checkEmail))
        	{
        		\Session::flash('error_message', "Email already exist."); 
                return redirect('/');
        	}
        	else
        	{
        		setcookie('new_email', $email, time() + (86400 * 30), "/");
        		return redirect('registration-step1');
        	}
		}
        return view('website.user.registration.registration');
	}


	//***** User Registartion step 1 *****//

	public function registration_step1(Request $data)
	{
		$data['professions'] = $this->master_data_list($table=$this->tableObj->tableNameProfession);
		$data['country'] = $this->master_data_list($table=$this->tableObj->tableNameCountry);
        return view('website.user.registration.registration1', $data);
	}

	//***** User Registartion step 2 *****//
	public function registration_step2()
	{
		$conditions = array(
                        array('is_blocked', '=', 0),
                    );
		$data['category'] = $this->master_data_list($table=$this->tableObj->tableNameCategory);
		$data['currency'] = $this->master_data_list($table=$this->tableObj->tableNameCurrency);
        return view('website.user.registration.registration2',$data);
	}

	
	//***** Thank You *****//
	public function thank_you()
	{
		$data = array();
		return view('website.user.thank_you')->with($data);
	}

	public function dashboard()
	{
		
		// Check User Login. If not logged in redirect to login page //
		$authdata = $this->website_login_checked();
		if((empty($authdata['user_no']) || ($authdata['user_no']<=0)) || (empty($authdata['user_request_key'])))
		{
			return redirect('/login');
		}

		return view('website.user.dashboard.dashboard');
	}

	public function business_contact_info()
	{
		// Check User Login. If logged in redirect to dashboard //
		$authdata = $this->website_login_checked();
		if((!empty($authdata['user_no']) || $authdata['user_no'] > 0 ) && !empty($authdata['user_request_key'])){
			$userData = $this->master_data_list($table=$this->tableObj->tableNameUser);	
			$country = $this->master_data_list($table=$this->tableObj->tableNameCountry);
			$data['professions'] = $this->master_data_list($table=$this->tableObj->tableNameProfession);


			$user_id = $_COOKIE['sqd_user_no'];

			$condition = array(
	                        array('id','=',$user_id),
	                    );

			$userDetails = $this->common_model->fetchData($this->tableObj->tableNameUser,$condition);

			/*$country_name = array_search($country_name,$country);
			print_r($country_name); die();*/
			$data['country'] = $country;
			$data['userDetails'] = $userDetails;
			//$this->remove_all_cookies(); // for manualy cookie remove testing
			return view('website.business-contact-info', $data);
		}
        return view('website.user.login.login');
	}

	public function business_logo_social_network()
	{
		// Check User Login. If logged in redirect to dashboard //
		$authdata = $this->website_login_checked();
		if((!empty($authdata['user_no']) || $authdata['user_no'] > 0 ) && !empty($authdata['user_request_key'])){

			$user_id = $authdata['user_no'];
			$condition = array(
	                        array('id','=',$user_id),
	                    );

			$userDetails = $this->common_model->fetchData($this->tableObj->tableNameUser,$condition);
			
			$data['userDetails'] = $userDetails;
			//$this->remove_all_cookies(); // for manualy cookie remove testing
			return view('website.business-logo-social-network', $data);
		}
        return view('website.user.login.login');
	}



	public function calendar()
	{
		return view('website.calendar');
	}


	public function gift_certificates()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.gift-certificates');
		}

		return view('website.gift-certificates');
	}

	public function marketing_discount_coupons()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.marketing-discount-coupons');
		}

		return view('website.marketing-discount-coupons');
	}

	public function offers()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.offers');
		}

		return view('website.offers');
	}

	public function reports()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.reports');
		}

		return view('website.reports');
	}

	public function client_database()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.client-database');
		}

		return view('website.client-database');
	}

	public function staff_details()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.staff-details');
		}

		return view('website.staff-details');
	}

	public function booking_options()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.booking-options');
		}

		return view('website.booking-options');
	}


	public function booking_rules()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.booking-rules');
		}

		return view('website.booking-rules');
	}

	public function booking_policies()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.booking-policies');
		}

		return view('website.booking-policies');
	}

	public function notification_settings()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.notification-settings');
		}

		return view('website.notification-settings');
	}

	public function email_confirmation()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.email-confirmation');
		}

		return view('website.email-confirmation');
	}

	public function settings_membership()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.settings-membership');
		}

		return view('website.settings-membership');
	}

	public function integration()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.integration');
		}

		return view('website.integration');
	}

	public function settings_business_hours()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.settings-business-hours');
		}

		return view('website.settings-business-hours');
	}

	public function invitees()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.invitees');
		}

		return view('website.invitees');
	}

	public function services()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.services');
		}

		return view('website.services');
	}

	public function payment_options()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.payment-options');
		}

		return view('website.payment-options');
	}

	public function create_invoice()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.create-invoice');
		}

		return view('website.create-invoice');
	}

	public function invoice()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.invoice');
		}

		return view('website.invoice');
	}

	public function invoice_details()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.invoice-details');
		}

		return view('website.invoice-details');
	}

	public function invite_contacts()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.invite-contacts');
		}

		return view('website.invite-contacts');
	}


	

	

	

	

	

	

	

	

	

	


	

	

	

	

	

	

	




	
}