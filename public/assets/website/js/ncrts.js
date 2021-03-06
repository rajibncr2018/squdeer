   //================Custom validation for 10 digit phone====================
   $.validator.addMethod("phoneUS", function (phone_number, element) {
     phone_number = phone_number.replace(/\s+/g, "");
     return this.optional(element) || phone_number.length > 11 && phone_number.length < 14;
   }, "Please specify a valid phone number with country prefix.");
   //================Custom validation for 10 digit phone====================

 //================Submit AJAX request ==================
 $('#update-contact-info').validate({

       ignore: ":hidden:not(.selectpicker)",

       rules: {
           profession: {
               required: true
           },
           business_name: {
               required: true
           },
           business_location: {
               required: true
           },
           street_number: {
               required: true
           },
           route: {
               required: true
           },
           city: {
               required: true
           },
           state: {
               required: true
           },
           zip_code: {
               required: true
           },
           country: {
               required: true
           },
           mobile: {
               required: true,
               phoneUS: true
           },
           office_phone: {
               required: true
           },
           skype_id: {
               required: true,
           },
           business_description: {
               required: true,
           },
           
       },
       
       messages: {
           profession: {
               required: 'Please select profession'
           },
           business_name: {
               required: 'Please enter business name'
           },
           business_location: {
               required: 'Please enter business location'
           },
           street_number: {
               required: 'Please enter street number'
           },
           route: {
               required: 'Please enter route'
           },
           city: {
               required: 'Please enter city'
           },
           state: {
               required: 'Please enter state'
           },
           zip_code: {
               required: 'Please enter zip code'
           },
           country: {
               required: 'Please enter country'
           },
           mobile: {
               required: 'Please enter mobile'
           },
           office_phone: {
               required: 'Please enter office phone'
           },
           skype_id: {
               required: 'Please enter skype id'
           },
           business_description: {
               required: 'Please enter business description'
           },
       },

       submitHandler: function(form) {
         var data = $(form).serializeArray();
         data = addCommonParams(data);
         $.ajax({
             url: form.action,
             type: form.method,
             data:data ,
             dataType: "json",
             success: function(response) {
               console.log(response);
               if(response.result=='1')
               {
                  swal("Success!", response.message, "success")
               }
               else
               {
                   swal("Error", response.message , "error");
               }
             },
             beforeSend: function(){
                 $('.animationload').show();
             },
             complete: function(){
                 $('.animationload').hide();
             }
         });
       }
   });
 //================Submit AJAX request ==================

$("#business-info-update").bind('click',function(e){
   e.preventDefault();
   $('#update-contact-info').submit();
});


$("#profile-image-upload").on('click',function(e){
   e.preventDefault();
   $( "#profile-image" ).trigger( "click" );
});


function readURL(input)
{
    if (input.files && input.files[0])
    {
        var reader = new FileReader();
        reader.onload = function (e) 
        {
            $('#image_upload_preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}



$("#profile-image").change(function () {
    readURL(this);
    $('#profile-image-remove').show();
});

$("#profile-image-remove").click(function (e) {
    e.preventDefault();
    $('.animationload').show();
    $('#profile-image-remove').hide();
    $('#old_profile_image').val('');
    $('#profile-image').val('');
    $('#image_upload_preview').attr('src', baseUrl+'/public/assets/website/images/picture.png');
    $('.animationload').hide();

});

//==================social & logo update=====================

$("#update-social-logo").on('submit', (function(e) {
    e.preventDefault();
    //data = addCommonParams(data);

    var data = $('#update-social-logo').serializeArray();
    data = addCommonParams(data);
    var files = $("#update-social-logo input[type='file']")[0].files;
    var form_data = new FormData();
    if(files.length>0){
        for(var i=0;i<files.length;i++){
            form_data.append('profile_image',files[i]);
        }
    }
    // append all data in form data 
    $.each(data, function( ia, l ){
        form_data.append(l.name, l.value);
    });

    $.ajax({
        url: baseUrl+"/api/update-logo-social", // Url to which the request is send
        type: "POST", // Type of request to be send, called as method
        data: form_data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false, // The content type used when sending data to the server.
        cache: false, // To unable request pages to be cached
        processData: false, // To send DOMDocument or non processed data file it is set to false
        dataType: "json",
        success: function(response) // A function to be called if request succeeds
        {
            console.log(response);
            $('.animationload').hide();
            if(response.result=='1')
            {
                swal("Success!", response.message, "success")
            }
            else
            {
                swal("Error", response.message , "error");
            }
        },
        beforeSend: function()
        {
            $('.animationload').show();
        },
        complete: function()
        {
            //$('.animationload').hide();
        }
    });
}));

//==================social & logo update=====================

$(".service-list").click(function (e) {
    e.preventDefault();
    $('.animationload').show();
    $('.service-list').removeClass("active");
    $(this).addClass("active");
    $(".break40px").hide();
    let id = $(this).attr('id');
    $("#row"+id).show();
    $('.animationload').hide();

});


$(".chnage-service-status").click(function (e) {
    e.preventDefault();
    
    let data = addCommonParams([]);
    let id = $(this).attr('id');
    data.push({name:'service_id',value:id});
    $.ajax({
        url: baseUrl+"/api/chnage-service-status", 
        type: "POST", 
        data: data, 
        dataType: "json",
        success: function(response) 
        {
            console.log(response);
            $('.animationload').hide();
            if(response.result=='1')
            {
                swal("Success!", response.message, "success");
            }
            else
            {
                swal("Error", response.message , "error");
            }
        },
        beforeSend: function()
        {
            $('.animationload').show();
        },
        complete: function()
        {
            //$('.animationload').hide();
        }
    });
    
});

    $('#add_client_form').validate({
            rules: {
                client_name: {
                    required: true
                },
                client_email: {
                    required: true,
                    email: true
                },
                client_mobile: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 10
                },
            },
            messages: {
                client_name: {
                    required: 'Please enter fullname'
                },
                client_email: {
                    required: 'Please enter email',
                    email: 'Please enter proper email'
                },
                client_mobile: {
                    required: 'Please enter mobile no',
                    number: 'Please enter proper mobile no',
                    minlength: 'Please enter minimum 10 digit mobile no',
                    maxlength: 'Please enter maximum 10 digit mobile no'
                },
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "client_name") {
                    error.insertAfter($('#clientname_error'));
                } else if (element.attr("name") == "client_email") {
                    error.insertAfter($('#clientemail_error'));
                } else if (element.attr("name") == "client_mobile") {
                    error.insertAfter($('#clientmobile_error'));
                } 
            },
            submitHandler: function(form) {
                    var data = $(form).serializeArray();
                    data = addCommonParams(data);
                    var files = $("#add_team_member_form input[type='file']")[0].files;
                    var form_data = new FormData();
                    if (files.length > 0) {
                        for (var i = 0; i < files.length; i++) {
                            form_data.append('staff_profile_picture', files[i]);
                        }
                    } 
          // append all data in form data 
      $.each(data, function(ia, l) {
          form_data.append(l.name, l.value);
        });
        $.ajax({
        url: form.action,
        type: form.method,
        data: form_data,
        dataType: "json",
        processData: false, // tell jQuery not to process the data 
        contentType: false, // tell jQuery not to set contentType 
        success: function(response) {
          console.log(response); //Success//
          if (response.response_status == 1) {
            $(form)[0].reset();
            $('#myModaladdclient').modal('hide');
            swal('Success!', response.response_message, 'success');
            location.reload();
          } else {
            swal('Sorry!', response.response_message, 'error');
          }
        },
        beforeSend: function() {
          $('.animationload').show();
        },
        complete: function() {
          $('.animationload').hide();
        }
        });
        }
        });


$(".email_cutomisation_form").submit(function(e) {
    // Getting the form ID
    e.preventDefault();
    var  formID = $(this).attr('id');
    var formDetails = $('#'+formID);
    data = formDetails.serializeArray();
    data = addCommonParams(data);

    var action = formDetails.attr('action');

    $.ajax({
      type: "POST",
      url: action,
      data: data,
      dataType: "json",
      beforeSend: function() {
          $('.animationload').show();
      },
      success: function(response) {
          console.log(response); //Success//
          if (response.response_status == 1) {
            $('.animationload').hide();
            swal('Success!', response.response_message, 'success');
          } else {
            swal('Sorry!', response.response_message, 'error');
          }
        }
    });
});

//================Submit AJAX request for add new appoinment ==================
 $('#add_appointmentm_form').validate({

       ignore: ":hidden:not(.selectpicker)",

       rules: {
           client: {
               required: true
           },
           appoinment_service: {
               required: true
           },
           staff: {
               required: true
           },
           date: {
               required: true
           },
           appointmenttime: {
               required: true
           },
           appoinment_note: {
               required: true
           },
       },
       
       messages: {
           client: {
               required: 'Client is required'
           },
           appoinment_service: {
               required: 'Service is required'
           },
           staff: {
               required: 'Staff is required'
           },
           date: {
               required: 'Date is required'
           },
           appointmenttime: {
               required: 'Time is required'
           },
           appoinment_note: {
               required: 'Note is required'
           },
       },

       submitHandler: function(form) {
         var data = $(form).serializeArray();
         data = addCommonParams(data);
         console.log(data);
         $.ajax({
             url: form.action,
             type: form.method,
             data:data ,
             dataType: "json",
             success: function(response) {
               console.log(response);
               if(response.result=='1')
               {
                    $('.animationload').hide();
                    $("#myModaladdappoinment").hide();
                    $("#add_appointmentm_form").trigger("reset");
                    swal({title: "Good job", text: response.message, type: "success"},
                       function(){ 
                           location.reload();
                       }
                    );
                    //swal("Success!", response.message, "success")
                    /*setTimeout(function()
                    {
                        window.location.reload();
                   
                    }, 1000);*/
               }
               else
               {
                   swal("Error", response.message , "error");
               }
             },
             beforeSend: function(){
                 $('.animationload').show();
             },
             complete: function(){
                 $('.animationload').hide();
             }
         });
       }
   });
 //================Submit AJAX request add new appoinment ==================




        



