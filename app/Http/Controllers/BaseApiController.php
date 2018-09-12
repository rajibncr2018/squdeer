<?php
/**
 * @Author : NCRTS
 * Base Controller for Website & API
 * 
 */

namespace App\Http\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Http\Request;
use App\model\common_model;
use App\Http\Controllers\TablesController;
use App\Http\Controllers\HelpersController;
use Lang;
use Mail;
use URL;
use Session;
use JD\Cloudder\Facades\Cloudder;
class BaseApiController extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public $postParam;
    public $common_model;
    public $response_status;
    public $response_message;
    public $limit;
    public $logged_user_no;
    public $logged_user;
    public $date_format;
    public $tableObj;
    // all table name params 


    function __construct(Request $params){
        $this->limit=30;
        //$this->tablePrefix="iimp_";
        $this->logged_user_no=0;
        $this->logged_user=array();
        $this->response_status=0;
        $this->response_message='';
        $this->device_type = '';
        $this->device_token_key = '';
        $this->user_request_key ='';
        $this->date_format=date("Y-m-d H:i:s");
        // table object 
        $this->tableObj=new TablesController();
        //Load Model
        $this->common_model = new common_model();
        // request object
        $this->postParam = $params;

        // restrict this section only for api  called
        $calling = $this->postParam->segment(1);
        if(strtolower($calling) == 'api'){
            $this->validate_parameter();
        }
        
    }
    // request validation section 

    public function validate_parameter($validate_type=0){
        $user_id = $this->postParam->input('user_no');
        $device_type = $this->postParam->input('device_type');
        $device_unique_code = $this->postParam->input('device_token_key');
        $user_request_key = $this->postParam->input('user_request_key');
       // parameter validation section 
       /* $authdata = $this->website_login_checked();
        print_r($authdata); die();*/
       switch($validate_type){
        case 1:
            // if user no is empty
            if(empty($user_id)){
                $this->response_message="user_no_required";
                $this->json_output();
            }
            // if user no is invalid
            if($user_id<0){
                $this->response_message="user_no_invalid";
                $this->json_output();
            }
            // if user request key is empty
            if(empty($user_request_key) ){
                $this->response_message="user_request_key_required";
                $this->json_output();
            }

            if(empty($device_unique_code) ){
                $this->response_message="user_device_key_required";
                $this->json_output();
            }
            
            // Get users details //

            $conditions=array(
                array('user_id','=',$user_id),
                array('device_type','=',$device_type),
                array('request_key','=',$user_request_key),
                array('device_token_key','=',$device_unique_code),
            );
           
            $userSelectFields = array('user_type','username','name','email','mobile');
            $joins=array(
                array(
                    'join_with'=>$this->tableObj->tableNameUserRequestKey,
                    'join_type'=>'inner',
                    'join_table'=>$this->tableObj->tableNameUser,
                    'join_table_alias'=>'UB',
                    'join_on'=>array('user_id','=','id'),
                    'join_on_more'=>array(array('is_deleted','=','0')),
                    'select_fields'=>$userSelectFields
                ),
            );
            $selectFileds=array('user_id','device_type','device_unique_code');
            $user = $this->common_model->fetchData($this->tableObj->tableNameUserRequestKey,$conditions,$selectFileds,$joins);

            
            // If user details not found //
            if(empty($user)){
                // authentication error 
                $this->response_status='-1';
                $this->response_message="user_request_key_expired";
                $this->json_output();
            }
            // Login the user //
            $this->logged_user_no=$user_id;
            $this->logged_user=$user; // its a object

        break;
        default:
            $param_checked=true;

            if($param_checked){
                // 0 : website, 1 : android modile, 2 : iOS mobile
                $device_type = $device_type ? $device_type : 0;
                if(!in_array($device_type,array('0','1','2'),false)){
                    $this->response_message="device_type_invalid";
                    $this->json_output();
                }

                // if device type = 0 then device token key should be the blowser session id capture
                $device_unique_code = $device_unique_code ? $device_unique_code : Session::getId();
                if(empty($device_unique_code)){
                    $this->response_message="device_unique_key_required";
                    $this->json_output();
                }
            }
        
        break;
       }
    }

    public function user_request_key($user_id=0,$is_new=0)
    {
        $has_key = md5(time().$user_id);
        $device_type = $this->postParam->input('device_type');
        $device_unique_code = $this->postParam->input('device_unique_code');
        $device_push_key = $this->postParam->input('device_push_key');

        if(!$is_new)
        {
            // old user update has key
            $find_data=array(
                array('device_type','=',$device_type),
                array('device_unique_code','=',$device_unique_code),
            );

            // remove old instance 
            $this->common_model->removeDatas($this->tableObj->tableNameUserHaxKey,$find_data);
        }

        // save the new data 
        $save_data=array(
            'user_id'=>$user_id,
            'haxkey'=>$has_key,
            'device_push_key'=>$device_push_key, // trying todo with 
            'device_type'=>$device_type,
            'device_unique_code'=>$device_unique_code,
        );

        $this->common_model->insert_data_get_id($this->tableObj->tableNameUserHaxKey,$save_data);
        return $has_key;
    }

    //***** JSON output *****//

    public function json_output($responcedata=array())
    {
        // message
        if(empty($this->response_message)){
            $this->response_message = "";
        } 
        $responseData = array(
            'response_status' => $this->response_status,
            'response_message' => $this->response_message,
            'result' => $this->response_status,
            'message' => $this->response_message, 
        );
        if(is_array($responcedata) && !empty($responcedata)){
            $responseData = array_merge($responseData,$responcedata);
        }
        // response end
        return die(json_encode($responseData));
        //return Response::make($responseData);
    }


    //***** Message Render *****//

    public function message_render($message=NULL){
        $response_message = $message;
        if($message!=NULL){
            if(Lang::has('message.api.'.$message)){
                $response_message = trans('message.api.'.$message);
            }
        }
        return $response_message;
    }



    //***** Validation Error Message *****//

    public function forErrorMessage($errors=array()){
        $errors_strg='';
        if(is_object($errors)){
            $errors =(array)$errors;
        }
        if(!empty($errors)){
            foreach($errors as $error){
                $msg=implode("\n",$error);
                if(empty($errors_strg)){
                    $errors_strg=$msg;
                }
                else{
                    $errors_strg.="\n".$msg;
                }
            }
        }
        return $errors_strg;
    }



    //***** Check Password Strength *****//

    public function checked_password_strength($password=''){
       // this function must called in value passed
        if(!empty($password)){

            // rule :: minumum 8 character length
            if(strlen($password)>7){
                $pattern = "/([a-z]+)$";
                if(preg_match($pattern,$password)){
                }
            }
        }
        return true; 
    }
    
    //***** Check User is exist or not by Email *****//

    public function checkUserby($type='',$value=''){
        if(!empty($type) && !empty($value)){
            //$type : 1 for email, 2 for username//
            $tableName = $this->tableObj->tableNameUser;
            if($type == 1){
                $where=array(array('email','=',$value));
            } else if($type == 2){
                $where=array(array('username','=',$value));
            }
            if($this->common_model->row_present_check($tableName, $where))
            {
                $res = true;
            } else {
                $res = false;
            }
        }
        return $res;
    }

    //***** Generate Logged in User Request Key *****//

    public function generate_request_key($is_new_key=0)
    {
        $user_ip = $this->get_client_ip();
        $user_no = $this->logged_user_no;
        $has_key = md5(time().$user_no);
        $device_type = $this->device_type;
        $device_token_key = Session::getId(); 
        $device_push_key = '';
        // get time zone from ip 
        $user_timezone="";
        $user_timezone_offset="";

        // table name of this section 
        $tableName = $this->tableObj->tableNameUserRequestKey;
        if(!$is_new_key){
            // old user update has key
            $delete_cond=array(
                array('device_type','=',$device_type),
                array('user_id','=',$user_no),
                array('user_ip','=',$user_ip),
                array('device_token_key','=',$device_token_key),
            );
            // for browser 
            if($device_type<>0){
                // this is a session id so it must all time 
                $delete_cond[]=array('device_token_key','=',$device_token_key);
            }
            // remove old instance
         $this->common_model->removeDatas($tableName,$delete_cond);
        }
        // save the new data
        $c_date = $this->date_format;
        $save_data=array(
            'user_id'=>$user_no,
            'request_key'=>$has_key,
            'device_push_key'=>$device_push_key, // trying todo with 
            'device_type'=>$device_type,
            'device_token_key'=>$device_token_key,
            'user_ip'=>$user_ip,
            'user_timezone'=>$user_timezone,
            'user_timezone_offset'=>$user_timezone_offset,
            'created_on'=>$c_date,
            'updated_on'=>$c_date,
        );
        // save the data
        $this->common_model->insert_data_get_id($tableName,$save_data);
        return $has_key;

    }

    //***** Check user login or not *****//

    public function website_login_checked()
    {
        // Check & Set Coockie //
        $user_no=(isset($_COOKIE['sqd_user_no']))?$_COOKIE['sqd_user_no']:0;
        $user_type=(isset($_COOKIE['sqd_user_type']))?$_COOKIE['sqd_user_type']:0;
        $user_request_key=(isset($_COOKIE['sqd_user_request_key']))?$_COOKIE['sqd_user_request_key']:'';
        $device_token_key=(isset($_COOKIE['sqd_device_token_key']) && !empty($_COOKIE['sqd_device_token_key']))?$_COOKIE['sqd_device_token_key']:Session::getId();
        $device_type="0";
        // Create Authentication Data//
        $authdata=array(
            'user_no'=>$user_no,
            'user_type'=>$user_type,
            'user_request_key'=>$user_request_key,
            'device_token_key'=>$device_token_key,
            'device_type'=>$device_type
        );
        return $authdata;
    }

    //***** Get Base URL *****//

    public function base_url($path=''){
        $url = '';
        if(empty($path)){
            $url = URL::to('/');
        }
        else{
            $url = URL::to($path);
        }
        return $url;
    }

     //***** Send Email *****//

    public function sendmail($mailType=0,$email='',$emailData=array()){
        //If email not empty
        if(!empty($email)){
            if(!is_array($email)){
                // must checked the email format validation 
                if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                    return false;
                }
            }
            // Send email //
            $mail_subject="Mail Subject";
            $mail_body="";
            $mailTemplateName="";
            $is_email_send=true;
            switch($mailType){
                case 1:// email verification link 
                    //$mail_subject = trans('message.mail.subject_email_verify');
                    $mail_subject = "Emmail verification link";
                    $mail_body="Your verification link is : ".$emailData['verify_link'];
                    $verify_link=$emailData['verify_link'];
                    $mailTemplateName="emails/general";
                    $emailData['mailBody']=$mail_body;
                    $emailData['verifyLink']=$verify_link;
                break;
                case 2: // forgot password
                    $mail_subject = trans('message.mail.reset_password');
                    $mail_body="Reset password link is : ".$emailData['verify_link'];
                    $mailTemplateName="emails/general";
                    $emailData['mailBody']=$mail_body;
                break;
                case 3: // password reset notify
                    $mail_subject = trans('message.mail.password_reset_successfully');
                    $mail_body="your password changed.Please login to your profile.";
                    $mailTemplateName="emails/general";
                    $emailData['mailBody']=$mail_body;
                break;
                case 4: // changed password
                    $mail_subject = trans('message.mail.password_change_successfully');
                    $mail_body="Thank you for your password changed.Its help your account more secure.";
                    $mailTemplateName="emails/general";
                    $emailData['mailBody']=$mail_body;
                break;
                
                default:

                    $is_email_send=false;

                break;

            }


            if($is_email_send){

                // Using Email Template //

                if($mailTemplateName)
                {
                    Mail::raw($mail_body,function($mail) use($email,$mail_subject)
                    {
                        $mail->from('info@squeedr.com','Squeedr email verification link');
                        $mail->subject($mail_subject);
                        //$mail->bcc('mrinmoydas.ncrts@gmail.com');
                        if(is_array($email)){
                            foreach($email as $eId){
                                $mail->to($eId);
                            }
                        }
                        else
                        {
                            $mail->to($email);
                        }
                    });
                }
            }
            return true;
        }
        else
        {
            return false;
        }

    }

    //***** Get Server IP Address *****//

    public function get_client_ip()
    {

        $ip='';
        if(isset($_SERVER['HTTP_REMOTE_ADDR'])){
            $ip = $_SERVER['HTTP_REMOTE_ADDR'];
        }
        if(empty($ip)){
            $ip = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'';
        }
        return $ip;
    }


    //***** Drop down list *****//
    public function master_data_list($table='')
    {   
        $data = array();
        if(!empty($table)){
            $conditions = array(
                array('is_blocked', '=', 0),
                array('is_deleted', '=', 0)
            );
            $data = $this->common_model->fetchDatas($table, $conditions);
        } 
        return $data;
    }


    public static function category_list(){
        $data = array();
        $obj = new Request();
        $ci = new BaseApiController($obj);
        $table = $ci->tableObj->tableNameCategory;
        $conditions = array(
            array('is_blocked', '=', 0),
            array('is_deleted', '=', 0)
        );
        $category_list = $ci->common_model->fetchDatas($table, $conditions);

        $data=array('category_list'=>$category_list);
        //echo '<pre>'; print_r($data); exit;
        return $data;
    }

    //***** Call CURL *****//

    public function curl_call($url_func_name='',$post_data=array())
    {
       /* echo $url_func_name; 
        echo "<pre>";
        print_r($post_data); die();*/
        $return_data=array();
        // If function name not empty //
        if(!empty($url_func_name)){
            $ip = $this->get_client_ip();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, url('api/'.$url_func_name));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip"));
            $return_curl = curl_exec($ch); 
            $error_no = curl_errno($ch);
            curl_close($ch);
            if($error_no == 0){
                $return_data = json_decode($return_curl);
                //print_r($return_data); die();
                if($return_data->response_status == -1){
                    $this->user_cookie_remove();
                    return redirect(url('login'));
                }
            }
            else{
                // need to clean all the cookie
                $this->user_cookie_remove();
                return redirect(url('login'));
            }
        }
        return $return_data;
    }


    //***** User Session / Cookie Removed (for Website) *****//

    public function user_cookie_remove(){

        $this->remove_all_cookies();

    }

    /** remove all the cookies */

    public function remove_all_cookies()
    {
       // $cookie_params=array('user_no','user_type','user_request_key','device_token_key');
        $cookie_params=array('sqd_user_no','sqd_user_type','sqd_user_request_key','sqd_device_token_key');
        foreach($cookie_params as $cookie_name)
        {
            if(isset($_COOKIE[$cookie_name])){
                unset($_COOKIE[$cookie_name]);
                setcookie($cookie_name,'',time()-3600,'/');
            }
        }
    }

}

