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

class BookingsController extends ApiController {

	function __construct(Request $input){

		parent::__construct($input);

	}

	//*client add*//
    public function email_customisation_update(Request $request)
    {
        // Check User Login. If not logged in redirect to login page //
		$authdata = $this->website_login_checked();
		if((empty($authdata['user_no']) || ($authdata['user_no']<=0)) || (empty($authdata['user_request_key']))){
           return redirect('/login');
		}
        //echo '<pre>'; print_r($request->all()); exit;
        $response_data=array();
        $this->validate_parameter(1);
        $user_id = $authdata['user_no'];
        $type = $request->input('type');
        $subject = $request->input('subject') ? $request->input('subject') : '';
        $message = $request->input('message') ? $request->input('message') : '';

         $conditions = array(
                array('type', '=', $type),
                array('user_id', '=', $user_id),
            );
                        
        $check = $this->common_model->fetchData($this->tableObj->tableNameUserEmailCustomisation,$conditions);

        if(empty($check))
        {
            $data['user_id'] = $user_id;
            $data['type'] = $type;
            $data['subject'] = $subject;
            $data['message'] = $message;

            $insert = $this->common_model->insert_data_get_id($this->tableObj->tableNameUserEmailCustomisation,$data);
            if($insert)
            {
                $this->response_status='1';
                $this->response_message="Successfully updated.";
            }
            else
            {
                $this->response_message="Somthing wrong. Try agaij later";
            }
            
        }
        else
        {
            $updateCond=array(
                array('user_id','=',$user_id),
                array('type', '=', $type),
            );

            $data['type'] = $type;
            $data['subject'] = $subject;
            $data['message'] = $message;

            $update = $this->common_model->update_data($this->tableObj->tableNameUserEmailCustomisation,$updateCond,$data);

            $this->response_status='1';
            $this->response_message="Successfully updated.";
        } 

        //return redirect('/email-customisation');
        $this->json_output($response_data);
    }

     public function email_customisation_data(Request $request){
        $response_data = array(); 
        // validate the requested param for access this service api
        $this->validate_parameter(1); // along with the user request key validation
        $other_user_no = $request->input('other_user_no');
        $pageNo = $request->input('page_no');
        $pageNo = ($pageNo>1)?$pageNo:1;
        $limit=$this->limit;
        $offset=($pageNo-1)*$limit;

        if(!empty($other_user_no) && $other_user_no!=0)
        {
            $user_no = $other_user_no;
        }
        else
        {
            $user_no = $this->logged_user_no;
        }
        
        $findCond=array(
            array('user_id','=',$user_no),
            array('is_deleted','=','0'),
            array('is_blocked','=','0'),
        );
        
        $selectFields=array();
        $staff_list = $this->common_model->fetchDatas($this->tableObj->tableNameUserEmailCustomisation,$findCond,$selectFields);
        $response_data['email_customisation_data']=$staff_list;
        $this->response_status='1';
        // generate the service / api response
        $this->json_output($response_data);
    }

    public function add_appoinment(Request $request)
    {
       

        $response_data = array(); 
        // validate the requested param for access this service api
        $this->validate_parameter(1); // along with the user request key validation
        if(!empty($other_user_no) && $other_user_no!=0){
            $user_id = $other_user_no;
        }
        else{
            $user_id = $this->logged_user_no;
        }

        $validate = Validator::make($request->all(),[
                                 'client'=>'required',
                                 'appoinment_service'=>'required',
                                 'staff'=>'required',
                                 'date'=>'required',
                                 'appointmenttime'=>'required',
                                 'appoinment_note'=>'required'
                                             ]);

        if ($validate->fails())
        {
            $this->response_message = $this->decode_validator_error($validate->errors());
            $this->json_output($response_data);
        }
        else
        {
            $client = $request->input('client');
            $appoinment_service = $request->input('appoinment_service');
            $staff = $request->input('staff');
            $date = $request->input('date');
            $appointmenttime = $request->input('appointmenttime');
            $appoinment_note = $request->input('appoinment_note');
            $colour_code = $request->input('colour_code');
            $apoinment_mail = $request->input('apoinment_mail');
            $user_id = $user_id;


            //Client data using id
            $client_condition = array(
                array('client_id', '=', $client)
            );

            $client_fields = array('client_id', 'client_email', 'client_name');
                        
            $client_details = $this->common_model->fetchData($this->tableObj->tableNameClient,$client_condition, $client_fields);

            //Stuff data using id
            $stuff_condition = array(
                array('staff_id', '=', $staff)
            );

            $stuff_fields = array('staff_id', 'email', 'full_name');
                        
            $stuff_details = $this->common_model->fetchData($this->tableObj->tableNameStaff,$stuff_condition, $stuff_fields);

            //Survice duration
            $service_condition = array(
                array('service_id', '=', $appoinment_service)
            );

            $sevice_fields = array('service_id', 'duration');
                        
            $service_details = $this->common_model->fetchData($this->tableObj->tableUserService,$service_condition, $sevice_fields);

            //calculate end time
            $duration = $service_details->duration;
            $endTime = strtotime("+".$duration." minutes", strtotime($appointmenttime));
            $endTime = date('h:i A', $endTime); 

            $param = array(
                'user_id' => $user_id,
                'service_id' => $appoinment_service,
                'staff_id' => $staff,
                'client_id' => $client,
                'date' => date('Y-m-d', strtotime($date)),
                'start_time' => $appointmenttime,
                'end_time' => $endTime,
                'colour_code' => $colour_code,
                'note' => $appoinment_note,
            );


            $insertdata = $this->common_model->insert_data_get_id($this->tableObj->tableNameAppointment,$param);
            if($insertdata > 0)
            {
                if($apoinment_mail)
                {
                    //send mail to client
                    $client_email = $client_details->client_email;
                    $client_name = $client_details->client_name;
                    $client_email_data['email_data'] = "A appoinment has created successfully.";
                    $this->sendmail(7,$client_email,$client_email_data);

                    //send mail to stuff
                    $stuff_email = $stuff_details->email;
                    $stuff_name = $stuff_details->full_name;
                    $stuff_email_data['email_data'] = "A appoinment has created successfully.";
                    $this->sendmail(8,$stuff_email,$stuff_email_data);

                }
                
                $this->response_status='1';
                $this->response_message = "Appoinment Successfully Creadetd.";
            }
            else
            {
                $this->response_message = "Something went wrong. Please try agian later.";
            }

            $this->json_output($response_data);
        }
    }


    public function appoinment_list(Request $request)
    {
        // Check User Login. If not logged in redirect to login page /
        $response_data = array(); 
        // validate the requested param for access this service api
        $this->validate_parameter(1); // along with the user request key validation
        if(!empty($other_user_no) && $other_user_no!=0){
            $user_no = $other_user_no;
        }
        else{
            $user_no = $this->logged_user_no;
        }

        //appoinment data using id
        $appoinment_condition = array(
            array('user_id', '=', $user_no)
        );

        $appoinment_fields = array('appointment_id', 'staff_id', 'start_time', 'end_time', 'date','colour_code');

        $stuff_fields = array('full_name');

        $joins = array(
                    array(
                    'join_table'=>$this->tableObj->tableNameStaff,
                    //'join_table_alias'=>'invItemTb',
                    'join_with'=>$this->tableObj->tableNameAppointment,
                    'join_type'=>'left',
                    'join_on'=>array('staff_id','=','staff_id'),
                    'join_on_more'=>array(),
                    //'join_conditions' => array(array('transaction_no','=','invoice_no')),
                    'select_fields' => $stuff_fields,
                ),
        );
        
        $appoinment_list = $this->common_model->fetchDatas($this->tableObj->tableNameAppointment,$appoinment_condition,$appoinment_fields,$joins);
        $appoinment_array = array();
        foreach ($appoinment_list as $key => $value)
        {
            $appoinment_array[] = array(
                    'service_name' => $value->full_name,
                    'appointment_id' => $value->appointment_id,
                    'start_date' => date('Y-m-d', strtotime($value->date)).' '.$value->start_time,
                    'end_time' => date('Y-m-d', strtotime($value->date)).' '.$value->end_time,
                    'colour_code' => $value->colour_code,
            );
        }
        $response_data['appoinment_list'] = $appoinment_array;
        $this->response_status='1';
        // generate the service / api response
        $this->json_output($response_data);
    }

    public function appoinment_details(Request $request)
    {
        // Check User Login. If not logged in redirect to login page /
        $response_data = array(); 
        // validate the requested param for access this service api
        $this->validate_parameter(1); // along with the user request key validation
        if(!empty($other_user_no) && $other_user_no!=0){
            $user_no = $other_user_no;
        }
        else{
            $user_no = $this->logged_user_no;
        }

        //appoinment data using id
        $appoinment_condition = array(
            array('user_id', '=', $user_no)
        );

        $appoinment_fields = array('appointment_id', 'staff_id', 'start_time', 'end_time', 'date','colour_code');

        $stuff_fields = array('full_name');

        $joins = array(
                    array(
                    'join_table'=>$this->tableObj->tableNameStaff,
                    //'join_table_alias'=>'invItemTb',
                    'join_with'=>$this->tableObj->tableNameAppointment,
                    'join_type'=>'left',
                    'join_on'=>array('staff_id','=','staff_id'),
                    'join_on_more'=>array(),
                    //'join_conditions' => array(array('transaction_no','=','invoice_no')),
                    'select_fields' => $stuff_fields,
                ),
        );
        
        $appoinment_list = $this->common_model->fetchDatas($this->tableObj->tableNameAppointment,$appoinment_condition,$appoinment_fields,$joins);
        $appoinment_array = array();
        foreach ($appoinment_list as $key => $value)
        {
            $appoinment_array[] = array(
                    'service_name' => $value->full_name,
                    'appointment_id' => $value->appointment_id,
                    'start_date' => date('Y-m-d', strtotime($value->date)).' '.$value->start_time,
                    'end_time' => date('Y-m-d', strtotime($value->date)).' '.$value->end_time,
                    'colour_code' => $value->colour_code,
            );
        }
        $response_data['appoinment_list'] = $appoinment_array;
        $this->response_status='1';
        // generate the service / api response
        $this->json_output($response_data);
    }

}