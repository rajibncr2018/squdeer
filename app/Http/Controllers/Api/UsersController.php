<?php
/**
* @Author : NCRTS
* Track :: 1
* Users Controller for Users Registration, login and basic section Related Apis
* oparetion with database
* 
*/

namespace App\Http\Controllers\Api;
use App\Http\Requests;
use App\Http\Controllers\BaseApiController as ApiController;
use Illuminate\Http\Request;
use Validator;
use Session;

class UsersController extends ApiController {
	function __construct(Request $input){
		parent::__construct($input);
	}


	//***** User Login *****//
	public function login(Request $request)
	{
		$response_data=array();
		// validated the parameters
		$validator = Validator::make($request->all(), [
			'email' => 'bail|required',
			'password' => 'bail|required',
		]);
		if(!$validator->fails())
		{
			//validate the user details

			$table_name = $this->tableObj->tableNameUser;
			$password = $request->input('password');
			$email = $request->input('email');
			$conditions = array(
				array('password','=',md5($password)),
				'or'=>array('email'=>$email,'username'=>$email)
			);
			$selectFields=array('id','user_type','is_email_verified','created_on','is_deleted','is_blocked');
			$user = $this->common_model->fetchData($table_name,$conditions,$selectFields);
			if(empty($user))
			{
				$this->response_message="Email/username & password not match";
			}
			else
			{
				//echo '<pre>'; print_r($user); exit;
				$is_blocked = $user->is_blocked;
				if($is_blocked == 0)
				{
					$created_on = strtotime($user->created_on);
					//checked is the email is verified or not 
					if($user->is_email_verified == 0 || $user->is_email_verified == 1)
					{
						//If user is registered within 3days
						if(($created_on + (72*3600)) >= time() )
						{
							// create the user request key for validating the farther request of the user
							$this->logged_user_no = $user->id;
							$user_request_key = $this->generate_request_key();
							$user_details['user_no']=$this->logged_user_no;
							$user_details['user_type']=$user->user_type;
							$user_details['user_request_key']=$user_request_key;
							//$user_details['is_basic_data_saved']=$user->is_basic_data_saved;
							$response_data['user']=$user_details;
							$this->response_status='1';
						}
						else
						{
							$this->response_message="email_need_to_verify_for_login";
						}
					}
					else
					{
						$this->logged_user_no = $user->user_no;
						$user_request_key = $this->generate_request_key();
						$user_details['user_no']=$this->logged_user_no;
						$user_details['user_type']=$user->user_type;
						$user_details['user_request_key']=$user_request_key;
						//$user_details['is_basic_data_saved']=$user->is_basic_data_saved;
						$response_data['user']=$user_details;
						$this->response_status='1';
					}
				}
				else
				{
					$this->response_message="account_blocked";
				}
			}
		}
		else
		{
			// if parameter validation checked faild
			$errors = $validator->errors()->messages();
			$this->response_message = $this->forErrorMessage($errors);
		}
		// generate the service / api response
		$this->json_output($response_data);
	}

	/***Login ***/
	public function logout(Request $request)
	{
		//echo "+++++++++"; die();
		$response_data=array();
		// validate the requested param for access this service api
		$this->validate_parameter(1);
		// now remove the request key 
		$deleteConds=array(
			array('request_key','=',$this->user_request_key),
			array('user_id','=',$this->logged_user_no),
		);
		
		$this->common_model->removeDatas($this->tableObj->tableNameUserRequestKey,$deleteConds);
		//remove all the cookies 
		$this->remove_all_cookies();
		$this->response_status=1;
		// generate the service / api response
		$this->json_output($response_data);
	}

	/**** Change Password******/

	public function forgotpassword(Request $request){
		$response_data=array();
		// validated the parameters
		$validator = Validator::make($request->all(), [
            'email' => 'required|email'
            ]
		);

        if ($validator->fails()) {
			// if parameter validation checked faild
            $errors = $validator->errors()->messages();
			$this->response_message = $this->forErrorMessage($errors);
        } else {
			// all validations are passed
			$email = $request->input('email');
			// find the email in the user table
			$findCond=array(
				array('email','=',$email),
				array('is_email_verified','=','1'),
			);

			$findCond = $this->set_common_param_for_fetch($findCond);
			$selectFileds=array('user_no','email','username','first_name');
			$user = $this->common_model->fetchData($this->tableObj->tableNameUser,$findCond,$selectFileds);
			if(empty($user)){
				$this->response_message="email_not_registered";
			}
			else{
				// create the token 
				$email = $user->email;
				$username = $user->username;
				$user_no = $user->user_no;
				$token1 = md5($email);
				$token2 = md5($username.$user_no);
				$token = $token1.$token2;
				// insert the data 
				$saveData=array(
					'user_no'=>$user_no,
					'token'=>$token,
				);

				$saveData =  array_merge($saveData,$this->set_common_param_for_saving());
				$save_id = $this->common_model->insert_data_get_id($this->tableObj->tableNameForgotPassword,$saveData);
				if($save_id>0){
					// send mail 

					$verify_link = $this->base_url('retrieve-password/'.$token); //need to change the link with website
					$emailData['verify_link']=$verify_link;
					$emailData['toName']=$user->first_name;
					$this->sendmail(2,$email,$emailData);
					$this->response_message="reset_password_link_sent";
					$this->response_status='1';
				}
				else{
					$this->response_message="data_saving_error";
				}
			}
		}
		// generate the service / api response
		$this->json_output($response_data);
	}


	/**** Reset Password *****/

	public function resetpassword($verify_token=''){
		$response_data=array();
		if(!empty($verify_token)){
			// validate the user verify token
			$findCond=array(
				array('token','=',$verify_token),
			);
			$findCond = $this->set_common_param_for_fetch($findCond);
			$select_fields=array('forgot_password_no','user_no');
			$joins=array(
				array(
					'join_type'=>'inner',
					'join_with'=>$this->tableObj->tableNameForgotPassword,
					'join_table'=>$this->tableObj->tableNameUser,
					'join_on'=>array('user_no','=','user_no'),
					'join_conditions'=>array(
						array('is_email_verified','=','1'),
						array('is_deleted','=','0'),
					),
					'select_fields'=>array('email','first_name')
				)
			);
			$user = $this->common_model->fetchData($this->tableObj->tableNameForgotPassword,$findCond,$select_fields,$joins);
			if(empty($user)){
				$this->response_message="reset_password_link_invalid";
			}
			else{
				// validation for password format
				$validator = Validator::make($this->postParam->all(),
					[
						'password' => 'bail|required|min:8|regex:/[A-Z]+/|regex:/[0-1]+/|regex:/[*@&%!#$]+/',
				]);

				if(!$validator->fails()){
					//print_r($user);
					$password = $this->postParam->input('password');
					$user_no = $user->user_no;
					$email = $user->email;
					$forgot_password_no = $user->forgot_password_no;
					//now update the new password 
					$updateData=array(
						'password'=>md5($password),
						'updated_on'=>$this->date_format
					);
					$updateCond=array(
						array('user_no','=',$user_no)
					);
					$this->common_model->update_data($this->tableObj->tableNameUser,$updateCond,$updateData);
					// remove the password token 
					$findCond[]=array('forgot_password_no','=',$forgot_password_no);
					$this->common_model->removeDatas($this->tableObj->tableNameForgotPassword,$findCond);
					// now send email for password changed 
					$this->sendmail(3,$email,array('toName'=>$user->first_name));
					$this->response_message="password_reset_successfully";
					$this->response_status='1';
				}
				else{
					$errors = $validator->errors()->messages();
					$this->response_message = $this->forErrorMessage($errors);
				}
			}
		}
		else{
			$this->response_message="reset_password_link_invalid";
		}
		// generate the service / api response
		$this->json_output($response_data);
	}

	

	/**** Change Password *****/

	public function changepssword(Request $request){

		$response_data=array();
		// validate the requested param for access this service api
		$this->validate_parameter(1);
		//validation section 
		$validator = Validator::make($this->postParam->all(),
			[
				'old_password' => 'bail|required',
				'password' => 'bail|required|min:8|different:old_password|regex:/[A-Z]+/|regex:/[0-9]+/|regex:/[*@&%!#$]+/',
		]);



		if(!$validator->fails()){
			// all validations are passed
			$user_no = $this->logged_user_no;
			$old_password = $request->input('old_password');
			$password = $request->input('password');
			// find the user password 
			$findCond=array(
				array('password','=',md5($old_password)),
				array('user_no','=',$user_no)
			);
			$select_fields=array('user_no','email','first_name');
			$user = $this->common_model->fetchData($this->tableObj->tableNameUser,$findCond,$select_fields);
			if(empty($user)){
				$this->response_message="old_password_not_matched";
			}
			else{
				// now update the password with new one
				$updateData=array(
					'password'=>md5($password),
					'updated_on'=>$this->date_format
				);
				$updateCond=array(
					array('user_no','=',$user_no)
				);
				$this->common_model->update_data($this->tableObj->tableNameUser,$updateCond,$updateData);
				// send mail
				$email = $user->email;
				$this->sendmail(4,$email,array('toName'=>$user->first_name));
				// update your password 
				$this->response_message="password_change_successfully";
				$this->response_status='1';
			}
		}
		else{
			// if parameter validation checked faild
			$errors = $validator->errors()->messages();
			$this->response_message = $this->forErrorMessage($errors);
		}
		// generate the service / api response
		$this->json_output($response_data);
	}


	/**** Registration Process Step One****/
	public function registration_step1(Request $request)
	{
		$response_data=array();
		//validation section 
		$validator = Validator::make($this->postParam->all(),
			[
				'user_name' => 'bail|required',
				'password' => 'bail|required|min:8',
				'phone' => 'bail|required',
				'profession' => 'bail|required',
				'country' => 'bail|required',
				
		]);

		if(!$validator->fails())
		{
			$user_type = $request->input('user_type');
			$username = $request->input('user_name');
			$password = $request->input('password');
			$mobile = $request->input('phone');
			$profession = $request->input('profession');
			$country = $request->input('country');  
			$email = $_COOKIE['new_email'];


			$param = array(
					'user_type' => $user_type,
					'username' => $username,
					'password' => md5($password),
					'mobile' => $mobile,
					'profession' => $profession,
					'country' => $country,
					'email' => $email	
			);

			$condition = array(
	                        array('username','=',$username),
	                    );
	        $checkUsername = $this->common_model->fetchData($this->tableObj->tableNameUser,$condition);

	        $emailCodtion = array(
	                        array('email','=',$email),
	                    );
	        $checkEmail = $this->common_model->fetchData($this->tableObj->tableNameUser,$emailCodtion);

	        if(empty($checkUsername) && empty($checkEmail))
	        {
	        	$user_no = $this->common_model->insert_data_get_id($this->tableObj->tableNameUser, $param);
	            if($user_no)
	            {
					// create email validation token :: format : md5(email).md5(username.user_no)
					$email = $email;
					$token1 = md5($email);
					$token2 = md5($username.$user_no);
					$token = $token1.$token2;
					//update the user with the token
					$update_condition=array(
						array('id','=',$user_no),
						array('email','=',$email)
					);
					$update_data=array('email_verification_code'=>$token);
					$this->common_model->update_data($this->tableObj->tableNameUser,$update_condition,$update_data);
					// send mail 
					$other_params = "?device_type=0&device_token_key=".Session::getId();
					$verify_link = $this->base_url('api/emailverification/'.$token.$other_params);// need to change with website url
					$emailData['verify_link']=$verify_link;
					$emailData['toName']=$username;

					$this->sendmail(1,$email,$emailData);

					// return section
					$response_data['token'] = $token;
					$response_data['user_type'] = $user_type;
					$this->response_status='1';
					$this->response_message="Verification link send to your email.";

	            }
	            else
	            {
	            	$this->response_message="Somthing wrong.Try again later.";
					$this->response_status='0';
	            }
	        }
	        else
	        {
	        	$this->response_message="User already exist.";
				$this->response_status='0';
	        }
		}
		else
		{
			$errors = $validator->errors()->messages();
			$this->response_message = $this->forErrorMessage($errors);
		}

		// generate the service / api response
		$this->json_output($response_data);

	}


	public function emailverification($verify_token='')
	{
		$response_data=array();
		$redirectURl = $this->base_url('thank-you');
		if(!empty($verify_token))
		{
			$find_cond=array(
				array('email_verification_code','=',$verify_token),
				array('is_deleted','=','0'),
				array('is_email_verified','=','0')
			);

			$user = $this->common_model->fetchData($this->tableObj->tableNameUser,$find_cond);
			if(empty($user))
			{

				//$message = $this->message_render('email_verification_token_expired');
				//return redirect($redirectURl)->with('message',['type'=>'error','text'=>$message]);
				\Session::flash('error_message', "Email verification token expired."); 
                return redirect('/login');
			}
			else
			{
				// create email validation token :: format : md5(email).md5(username.user_no)
				$email = $user->email;
				$username = $user->username;
				$user_no = $user->id;
				$token1 = md5($email);
				$token2 = md5($username.$user_no);
				$token = $token1.$token2;
				$created_on = strtotime($user->created_on);
				// validate the token 
				if($token!=$verify_token){
					//$this->response_message="email_verification_token_invalid";
					//$this->json_output();
					//$message = $this->message_render('email_verification_token_expired');
					//return redirect($redirectURl)->with('message',['type'=>'error','text'=>$message]);
					\Session::flash('error_message', "Email verification token expired."); 
                	return redirect('/login');
				}
				else if($created_on + (72*3600) >= time() )
				{
					// do the neccessary work
					$find_cond[]=array('id','=',$user_no);
					// updatedata 
					$updateData=array(
						'is_email_verified'=>'1',
						'updated_on'=>$this->date_format
					);
					$this->common_model->update_data($this->tableObj->tableNameUser,$find_cond,$updateData);
					//$message = $this->message_render('email_verification_success');
					//return redirect($redirectURl)->with('message',['type'=>'success','text'=>$message]);
					\Session::flash('success_message', "Successfully email verified."); 
	     			return redirect('/login');
				} 
				else
				{
					//$message = $this->message_render('email_verification_token_expired');
					//return redirect($redirectURl)->with('message',['type'=>'error','text'=>$message]);
					\Session::flash('error_message', "Email verification token expired."); 
                	return redirect('/login');
				}
			}
		}
		else
		{
			\Session::flash('error_message', "Email verification token missing."); 
            return redirect('/login');
			//return redirect($redirectURl)->with('message',['type'=>'error','text'=>"email_verification_token_missing"]);
		}
	}

	/**** Registration Process Step One****/
	public function registration_step2(Request $request)
	{
		$response_data=array();

		$category = $request->input('category');
		$new_category_name = $request->input('new_category_name');
		$service = $request->input('service');
		$cost = $request->input('cost');
		$currency = $request->input('currency');
		$duration = $request->input('duration');
		$custom_duration = $request->input('custom_duration');  
		$capacity = $request->input('capacity'); 
		$email = $_COOKIE['new_email'];

		$emailCodtion = array(
	                        array('email','=',$email),
	                    );
	    $checkEmail = $this->common_model->fetchData($this->tableObj->tableNameUser,$emailCodtion);

	    $count = 0;
	    foreach ($category as $key => $value)
	    {
	    	if($value=='new')
	    	{
	    		$param = array('category' => $new_category_name[$key], 'is_blocked' => 1);
	    		$category_id = $this->common_model->insert_data_get_id($this->tableObj->tableNameCategory, $param);
	    	}
	    	else
	    	{
	    		$category_id = $value;
	    	}

	    	if($duration[$key] == "Custom")
	    	{
	    		$duration = $custom_duration[$key];
	    	}
	    	else
	    	{
	    		$duration = $duration[$key];
	    	}

	    	$param = array(
	    			'user_id' => $checkEmail->id,
					'category_id' => $category_id,
					'service_name' => $service[$key],
					'cost' => $cost[$key],
					'currency_id' => $currency[$key],
					'duration' => $duration,
					'capacity' => $capacity[$key],
			);

	    	//inset into service table
	    	$this->common_model->insert_data_get_id($this->tableObj->tableUserService, $param);
	    	$count++;
	    }
			
		$this->response_status='1';
		$this->response_message="Verification link send to your email.";

		// generate the service / api response
		$this->json_output($response_data);
	}

}