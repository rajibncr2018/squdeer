<?php
/**
* @Author : NCRTS
* Table Controller for all database table name preference
* 
*/
namespace App\Http\Controllers;

class TablesController {
    //public $tableNameUser;
    function __construct(){
        

        $this->tableNameCategory = config('constants.tables.categories');
        $this->tableNameCountry = config('constants.tables.countries');
        $this->tableNameProfession = config('constants.tables.profession');
        $this->tableNameCurrency = config('constants.tables.currency');
        $this->tableNameStaff = config('constants.tables.staff');
        //admin section
        $this->tableNameMasterAdmin = config('constants.tables.masterAdmin');
        $this->tableNameUser = config('constants.tables.user');
        $this->tableUserService = config('constants.tables.user_service');
        $this->tableNameUserRequestKey = config('constants.tables.user_token');
        $this->tableNameClient = config('constants.tables.client');
        $this->tableNameUserEmailCustomisation = config('constants.tables.user_email_customisation');
        $this->tableNameAppointment = config('constants.tables.appointment');


        
    }
}
?>
