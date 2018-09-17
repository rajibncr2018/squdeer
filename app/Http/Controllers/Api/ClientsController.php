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

class ClientsController extends ApiController {
	function __construct(Request $input){
		parent::__construct($input);
	}

	
	//*client add*//
    public function add_client(Request $request)
    {
        // Check User Login. If not logged in redirect to login page //
		$authdata = $this->website_login_checked();
		if((empty($authdata['user_no']) || ($authdata['user_no']<=0)) || (empty($authdata['user_request_key']))){
           return redirect('/login');
		}
        //echo '<pre>'; print_r($request->all()); exit;
        $response_data=array();
        $this->validate_parameter(1);
        $user_id = $this->logged_user_no;

        $validate = Validator::make($request->all(),[
                                 'client_name'=>'required',
                                 'client_email'=>'required|email',
                                 'client_mobile'=>'required|numeric'
                                             ]);

        if ($validate->fails())
        {
            $this->response_message = $this->decode_validator_error($validate->errors());
            $this->json_output($response_data);
        }
        else
        {
            $client_name = $request->input('client_name');
            $client_email = $request->input('client_email');
            $client_mobile = $request->input('client_mobile');
            $client_home_phone = $request->input('client_home_phone');
            $client_work_phone = $request->input('client_work_phone');
            $client_category = $request->input('client_category');
            $client_address = $request->input('client_address')?$request->input('client_address'):"";
            $client_timezone = $request->input('client_timezone');
            $client_note = $request->input('client_note');

            $client_profile_picture = '';
            $client_send_email = $request->input('client_send_email');
            $send_email = false;
            if(isset($client_send_email) && $client_send_email == 1){
                $send_email = true;
            }

            $conditions = array(
				'or'=>array('client_email' => $client_email)
			);
                        
            $result = $this->common_model->fetchData($this->tableObj->tableNameClient,$conditions);
            //echo '<pre>'; print_r($result); exit;
            if(!empty($result))
            {
                $this->response_message = "This email is already exist.";
            }
            else
            {
                $token1 = md5($client_email);
                $token = $token1;

                $digits = 8;
                $password = rand(pow(10, $digits-1), pow(10, $digits)-1);


                $client_data['user_id'] = $user_id;
                $client_data['client_name'] = $client_name;
                $client_data['client_email'] = $client_email;
                $client_data['client_mobile'] = $client_mobile;
                $client_data['client_home_phone'] = $client_home_phone;
                $client_data['client_work_phone'] = $client_work_phone;
                $client_data['client_category'] = $client_category;
                $client_data['client_address'] = $client_address;
                $client_data['client_timezone'] = $client_timezone;
                $client_data['client_note'] = $client_note;
                //$client_data['password'] = md5($password);
                $client_data['email_verification_code'] = $token;


                $insertdata = $this->common_model->insert_data_get_id($this->tableObj->tableNameClient,$client_data);
                if($insertdata > 0){
                    /* Send Email */
                    //$other_params = "?device_type=0&device_token_key=".Session::getId();
					//$verify_link = $this->base_url('api/emailverification/'.$token.$other_params);// need to change with website url
                    //$emailData['username']=$username;
                    //$emailData['password']=$password;
					//$emailData['toName']=$full_name;

                    $emailData['username'] = '';
                    $emailData['password'] = '';
                    $emailData['toName'] = $client_name;

                    $this->sendmail(6,$client_email,$emailData);
                    
                    $this->response_status='1';
                    $this->response_message = "Client successfully added.";
                } else {
                    $this->response_message = "Something went wrong. Please try agian later.";
                }
                
            }

            $this->json_output($response_data);
        }
    }

    // User's client Listing //
    public function client_list(Request $request){
		$response_data=array();	
		// validate the requested param for access this service api
		$this->validate_parameter(1); // along with the user request key validation
		$other_user_no = $request->input('other_user_no');
		$pageNo = $request->input('page_no');
		$pageNo = ($pageNo>1)?$pageNo:1;
		$limit=$this->limit;
		$offset=($pageNo-1)*$limit;

		if(!empty($other_user_no) && $other_user_no!=0){
			$user_no = $other_user_no;
		}
		else{
			$user_no = $this->logged_user_no;
		}
        
        $serch_text = $request->input('client_search_text');
		$findCond=array(
            array('user_id','=',$user_no),
			array('is_deleted','=','0'),
			array('is_blocked','=','0'),
		);
		if(!empty($search_text)){
			$findCond[]=array('client_name','like','%'.$search_text.'%');
		}
		$client_list = $this->common_model->fetchDatas($this->tableObj->tableNameClient,$findCond,$selectFields=array());
		$response_data['client_list']=$client_list;
		$this->response_status='1';
		// generate the service / api response
		$this->json_output($response_data);
	}




}