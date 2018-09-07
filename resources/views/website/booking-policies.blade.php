@extends('../layouts/website/master_template_web')
@section('title')
Squeedr
@endsection

@section('content')
 <div class="body-part">
   <div class="container-custm">
      <div class="upper-cmnsection">
         <div class="heading-uprlft">Booking Options</div>
         <div class="upr-rgtsec">
            <div class="col-sm-5">
               <div id="custom-search-input">
                  <div class="input-group">
                     <input type="text" class="  search-query form-control" placeholder="Search" />
                     <span class="input-group-btn">
                     <button class="btn btn-danger" type="button">
                     <span class=" glyphicon glyphicon-search"></span>
                     </button>
                     </span>
                  </div>
               </div>
            </div>
            <div class="col-md-7">
               <div class="full-rgt">
                  <div class="dropdown custm-uperdrop">
                     <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">Upcoming Dates <img src="{{asset('public/assets/website/images/arrow.png')}}" alt=""/></button>
                     <ul class="dropdown-menu">
                        <li><a href="#">JAN</a></li>
                        <li><a href="#">FEB</a></li>
                        <li><a href="#">MARCH</a></li>
                     </ul>
                  </div>
                  <div class="filter-option"><a href="/">Show Filter <i class="fa fa-filter" aria-hidden="true"></i></a></div>
               </div>
            </div>
         </div>
      </div>
      <div class="leftpan">
         <div class="left-menu">
            <ul>
               <li><a href="{{ url('booking-options') }}"> Clients Booking Flow</a></li>
               <li><a href="{{ url('booking-rules') }}"> Booking Rules</a> </li>
               <li><a href="{{ url('booking-policies') }}" class="active"> Booking Policies</a></li>
               <li><a href="{{ url('notification-settings') }}"> Notification Settings</a></li>
               <li><a href="{{ url('email-confirmation') }}"> Email Customisation</a> </li>
            </ul>
         </div>
      </div>
      <div class="rightpan">
         <div class="container-fluid body-sec  ">
            <div class="row ">
               <div class="col-md-12 booking-opt">
                  <div onclick="slideDiv(this);" data-toggle="collapse" data-parent="#accordion" href="#collapse1" style="cursor: pointer;">
                     <i class="fa fa-custom fa-angle-down" style="font-size: 30px; float: right;"></i>    
                     <h2>Cancellation Policy</h2>
                     <p>
                        Use the area to specify your Cancellation policy. Your cutomers will be able to view this policy before they book an appointment. 
                        This policy can alos be sent to customers as a part of appointment related emails.
                     </p>
                  </div>
                  <div id="collapse1" class="panel-collapse collapse">
                     <div class="padleft30">
                        <textarea style="width: 100%;" id="area5" > Write your policy content here...</textarea>
                        <div class="col-md-9">
                           Tips: Please use the following tags to insert your business information.<br>
                           {yourphone} &RightArrow; insert your business phone number <br>
                           {mail}  &RightArrow;  insherst your business email ID<br>
                           {Cancellation Hour}  &RightArrow;  insert th minimum notice required for cancelling an appointment 
                        </div>
                        <div class="col-md-3 text-right">2000 characters remaining </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="container-fluid body-sec  ">
            <div class="row ">
               <div class="col-md-12 booking-opt">
                  <div onclick="slideDiv(this);" data-toggle="collapse" data-parent="#accordion" href="#collapse2" style="cursor: pointer;">
                     <i class="fa fa-custom fa-angle-down" style="font-size: 30px; float: right;"></i>    
                     <h2>Additional Information</h2>
                     <p>
                        Use the area to specify any additional information or policy. This can be sent to the customers as a part of Appointment related emails.
                     </p>
                  </div>
                  <div id="collapse2" class="panel-collapse collapse">
                     <div class="padleft30">
                        <textarea style="width: 100%;" id="area5" > Write your policy content here...</textarea>
                        <div class="col-md-9">
                           Tips: Please use the following tags to insert your business information.<br>
                           {yourphone} &RightArrow; insert your business phone number <br>
                           {mail}  &RightArrow;  insherst your business email ID<br>
                           {Cancellation Hour}  &RightArrow;  insert th minimum notice required for cancelling an appointment 
                        </div>
                        <div class="col-md-3 text-right">2000 characters remaining </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="container-fluid body-sec  ">
            <div class="row ">
               <div class="col-md-12 booking-opt">
                  <div onclick="slideDiv(this);" data-toggle="collapse" data-parent="#accordion" href="#collapse3" style="cursor: pointer;">
                     <i class="fa fa-custom fa-angle-down" style="font-size: 30px; float: right;"></i>    
                     <h2>Terms & Conditions</h2>
                     <p>
                        Use the area in order to specify term & conditions specific to your business. Your user will need to agree to these terms before book their first appointment.
                        Everytime you update / change these terms, your customers will need to give their consent again before booking any further appointments.
                     </p>
                  </div>
                  <div id="collapse3" class="panel-collapse collapse">
                     <div class="padleft30">
                        <textarea style="width: 100%;" id="area5" > Write your policy content here...</textarea>
                        <div class="col-md-9">
                           Tips: Please use the following tags to insert your business information.<br>
                           {yourphone} &RightArrow; insert your business phone number <br>
                           {mail}  &RightArrow;  insherst your business email ID<br>
                           {Cancellation Hour}  &RightArrow;  insert th minimum notice required for cancelling an appointment 
                        </div>
                        <div class="col-md-3 text-right">2000 characters remaining </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!--  <button class="btn btn-primary search-btn" type="submit">Save</button>-->
      </div>
   </div>
</div>
@endsection