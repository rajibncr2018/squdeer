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

class StaffsController extends ApiController {
	function __construct(Request $input){
		parent::__construct($input);
	}

	
	//*user staff add*//
    public function add_staff(Request $request)
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
                                 'staff_fullname'=>'required',
                                 'staff_email'=>'required|email',
                                 'staff_mobile'=>'required|numeric',
                                 'staff_description'=>'required'
                                             ]);

        if ($validate->fails())
        {
            $this->response_message = $this->decode_validator_error($validate->errors());
            $this->json_output($response_data);
        }
        else
        {
            $full_name = $request->input('staff_fullname');
            $email = $request->input('staff_email');
            $username = $request->input('staff_username');
            $mobile = $request->input('staff_mobile');
            $home_phone = $request->input('staff_home_phone');
            $work_phone = $request->input('staff_work_phone');
            $category_id = $request->input('staff_category');
            $expertise = $request->input('staff_expertise');
            $description = $request->input('staff_description');

            $staff_profile_picture = '';

            $conditions = array(
				'or'=>array('email'=>$email,'username'=>$username)
			);
                        
            $result = $this->common_model->fetchData($this->tableObj->tableNameStaff,$conditions);
            //echo '<pre>'; print_r($result); exit;
            if(!empty($result))
            {
                $this->response_message = "This username or email is already exist.";
            }
            else
            {
                $token1 = md5($email);
                $token2 = md5($username);
                $token = $token1.$token2;

                $digits = 8;
                $password = rand(pow(10, $digits-1), pow(10, $digits)-1);

                $destinationPath = './uploads/profile_image/';
                if (!empty($_FILES)) {
                    if ($_FILES['staff_profile_picture'] && $_FILES['staff_profile_picture']['name'] != "") {
                        $staff_profile_picture_name = str_replace(" ", "_", time() . $_FILES['staff_profile_picture']['name']);
                        if (move_uploaded_file($_FILES['staff_profile_picture']['tmp_name'], $destinationPath . $staff_profile_picture_name)) {
                            //$user_data['staff_profile_picture'] = $staff_profile_picture_name;
                            $staff_data['staff_profile_picture'] = url('uploads/profile_image/'.$staff_profile_picture_name);
    
                            /*if ($data->input('old_staff_profile_picture') != "") {
                                if (file_exists($destinationPath . $data->input('old_staff_profile_picture'))) {
                                    unlink($destinationPath . $data->input('old_staff_profile_picture'));
                                }
                            }*/
                        }
                    }
                }

                $staff_data['user_id'] = $user_id;
                $staff_data['username'] = $username;
                $staff_data['full_name'] = $full_name;
                $staff_data['email'] = $email;
                $staff_data['mobile'] = $mobile;
                $staff_data['home_phone'] = $home_phone;
                $staff_data['work_phone'] = $work_phone;
                $staff_data['expertise'] = $expertise;
                $staff_data['description'] = $description;
                $staff_data['category_id'] = $category_id;
                $staff_data['password'] = md5($password);
                $staff_data['email_verification_code'] = $token;

                /*$data=array(
                    'user_id' => $user_id,
                    'username' => $username,
                    'full_name' => $full_name,
                    'email' => $email,
                    'mobile' => $mobile,
                    'home_phone' => $home_phone,
                    'work_phone' => $work_phone,
                    'expertise' => $expertise,
                    'description' => $description,
                    'category_id' => $category_id,
                    'password' => md5($password),
                    'email_verification_code' => $token
                );*/

                $insertdata = $this->common_model->insert_data_get_id($this->tableObj->tableNameStaff,$staff_data);
                if($insertdata > 0){
                    $this->response_status='1';
                    $this->response_message = "Staff successfully added.";
                } else {
                    $this->response_message = "Something went wrong. Please try agian later.";
                }
                
            }

            $this->json_output($response_data);
        }
    }

    // User's Staff Listing //
    public function staff_list(Request $request){
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
        
        $serch_text = $request->input('staff_search_text');
		$findCond=array(
            array('user_id','=',$user_no),
			array('is_deleted','=','0'),
			array('is_blocked','=','0'),
		);
		if(!empty($search_text)){
			$findCond[]=array('full_name','like','%'.$search_text.'%');
		}
		$selectFields=array('staff_id','user_id','full_name','username','email','mobile','description','home_phone','work_phone','expertise','category_id','staff_profile_picture','is_internal_staff','booking_url','is_login_allowed','is_email_verified','is_blocked','create_at');
		$staff_list = $this->common_model->fetchDatas($this->tableObj->tableNameStaff,$findCond,$selectFields);
		$response_data['staff_list']=$staff_list;
		$this->response_status='1';
		// generate the service / api response
		$this->json_output($response_data);
	}




}