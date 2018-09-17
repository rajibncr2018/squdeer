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
        		\Session::flash('error_message', "Email already exists."); 
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
			$country = $this->master_data_list($table=$this->tableObj->tableNameCountry);
			$professions = $this->master_data_list($table=$this->tableObj->tableNameProfession);


			$user_id = $_COOKIE['sqd_user_no'];

			$condition = array(
	                        array('id','=',$user_id),
	                    );

			$userDetails = $this->common_model->fetchData($this->tableObj->tableNameUser,$condition);
			

			$prof_conditions = array(
                array('profession_id', '=', $userDetails->profession),
            );
            $prof_data = $this->common_model->fetchData($this->tableObj->tableNameProfession, $prof_conditions);

			$country_key = array_search($userDetails->country, array_column($country, 'country_no'));
			$data['country_name'] = $country[$country_key]->country_name;
			$data['profession_name'] = $prof_data->profession;
			$data['country'] = $country;
			$data['professions'] = $professions;
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
		$authdata = $this->website_login_checked();
		if((empty($authdata['user_no']) || ($authdata['user_no']<=0)) || (empty($authdata['user_request_key']))){
           return redirect('/login');
		}


		// Call API //
		$post_data = $authdata;
		$post_data['page_no']=1;
		$data=array(
			'client_list'=>array(),
			'authdata'=>$authdata
		);
		$url_func_name="client_list";
		$return = $this->curl_call($url_func_name,$post_data);
		
		// Check response status. If success return data //		
		if(isset($return->response_status)){
			if($return->response_status == 1){
				$data['client_list'] = $return->client_list;
			}
			//echo '<pre>'; print_r($data); exit;
			return view('website.client.client-database')->with($data);
		}
		else{
			return $return;
		}
		//return view('website.client.client-database');

	}

	public function staff_details()
	{
		// Check User Login. If not logged in redirect to login page //
		$authdata = $this->website_login_checked();
		if((empty($authdata['user_no']) || ($authdata['user_no']<=0)) || (empty($authdata['user_request_key']))){
           return redirect('/login');
		}
		// Call API //
		$post_data = $authdata;
		$post_data['page_no']=1;
		$data=array(
			'staff_list'=>array(),
			'authdata'=>$authdata
		);
		$url_func_name="staff_list";
		$return = $this->curl_call($url_func_name,$post_data);
		
		// Check response status. If success return data //		
		if(isset($return->response_status)){
			if($return->response_status == 1){
				$data['staff_list'] = $return->staff_list;
			}
			//echo '<pre>'; print_r($data); exit;
			return view('website.staff.staff-details')->with($data);
		}
		else{
			return $return;
		}
		//return view('website.staff.staff-details');
	}

	public function services()
	{
		// Check User Login. If not logged in redirect to login page //
		$authdata = $this->website_login_checked();
		if((empty($authdata['user_no']) || ($authdata['user_no']<=0)) || (empty($authdata['user_request_key']))){
           return redirect('/login');
		}

		// Call API //
		$post_data = $authdata;
		$post_data['page_no']=1;
		$data=array(
			'service_list'=>array(),
			'authdata'=>$authdata
		);
		$url_func_name="service_list";
		$return = $this->curl_call($url_func_name,$post_data);
		
		// Check response status. If success return data //		
		if(isset($return->response_status))
		{
			if($return->response_status == 1)
			{
				$data['service_list'] = $return->service_list;
			}
			//echo '<pre>'; print_r($data); exit;
			return view('website.service.services')->with($data);
		}
		else{
			return $return;
		}

		//return view('website.services');
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

	public function add_location()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.add-location');
		}

		return view('website.add-location');
	}

	public function privacy_settings()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.privacy-settings');
		}

		return view('website.privacy-settings');

	}

	public function profile_settings()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.profile-settings');
		}

		return view('website.profile-settings');
	}

	public function profile_picture()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.profile-picture');
		}

		return view('website.profile-picture');
	}

	public function profile_link()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.profile-link');
		}

		return view('website.profile-link');
	}

	public function profile_payment()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.profile-payment');
		}

		return view('website.profile-payment');
	}

	public function profile_login()
	{
		if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'])
		{
			return view('website.profile-login');
		}

		return view('website.profile-login');
	}

	


	

	

	

	

	

	

	

	

	

	


	

	

	

	

	

	

	




	
}