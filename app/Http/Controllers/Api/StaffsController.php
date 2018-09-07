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

	
	/*user staff add*/
    public function add_staff(Request $request)
    {
        $response_data=array();
        $this->validate_parameter(1);
        $user_id = $this->logged_user_id;

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
            $mobile = $request->input('staff_mobile');
            $home_phone = $request->input('staff_home_phone');
            $work_phone = $request->input('staff_work_phone');
            $category_id = $request->input('staff_category');
            $expertise = $request->input('staff_expertise');
            $description = $request->input('staff_description');

            $staff_profile_picture = '';

            $conditions=array(
                            array('mobile','=',$mobile),
                            array('email','=',$email),
                        );
                        
            $result = $this->common_model->fetchData($this->tableObj->tableNameStaff,$conditions);
            if(!empty($result))
            {
                $this->response_message = "This email or mobile no is already exist.";
            }
            else
            {
                $digits = 8;
                $password = rand(pow(10, $digits-1), pow(10, $digits)-1);

                $data=array(
                    'user_id' => $user_id,
                    'full_name' => $full_name,
                    'email' => $email,
                    'mobile' => $mobile,
                    'expertise' => $expertise,
                    'description' => $description,
                    'category_id' => $category_id,
                    'password' => md5($password),
                    'staff_profile_picture' => $staff_profile_picture
                );

                $insertdata = $this->common_model->insert_data_get_id($this->tableObj->tableNameStaff,$data);

                $this->response_status='1';
                $this->response_message = "Staff successfully added.";
            }

            $this->json_output($response_data);
        }
    }




}