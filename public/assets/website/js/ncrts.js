var user_no;
var device_type=0;
var device_token_key;
var user_request_key;

function addCommonParams(data){

    if(typeof(user_no)!=undefined){
        //get this param value from cookie
		if(typeof($.cookie("user_no"))!='undefined'){
			if($.cookie("user_no") != ''){
				user_no = $.cookie("user_no");
			}
		}
        data.push({name:'user_no',value:user_no});
    }
    if(typeof(user_request_key)!=undefined){
        //get this param value from cookie
		if(typeof($.cookie("user_request_key"))!='undefined'){
			if($.cookie("user_request_key") != ''){
				user_request_key = $.cookie("user_request_key");
			}
		}
        data.push({name:'user_request_key',value:user_request_key});
    }
    
    if(typeof(device_type)!=undefined){
        data.push({name:'device_type',value:device_type});
    }
    if(typeof(device_token_key)!='undefined'){
        //
		if(typeof($.cookie("device_token_key"))!= 'undefined'){
			if($.cookie("device_token_key") != ''){
				device_token_key = $.cookie("device_token_key");
			}
		}
		
        data.push({name:'device_token_key',value:device_token_key});
    }
    return data;
}