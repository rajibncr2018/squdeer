@extends('../layouts/website/master_template_web')
@section('title')
Squeedr
@endsection

@section('content')
<div class="body-part">
   <div class="container-custm">
      <div class="upper-cmnsection">
         <div class="heading-uprlft">Client Database</div>
         <div class="upr-rgtsec">
            <div class="col-md-5">
            </div>
            <div class="col-md-7">
               <div class="full-rgt">
               </div>
            </div>
         </div>
      </div>
      <div class="leftpan">
         <div class="left-menu">
            <div id="custom-search-input">
               <a href="#" class="imp-st" data-toggle="tooltip" title="Import Staff"><i class="fa fa-download"></i> </a> <a href="#" class="exp-st"  data-toggle="tooltip" title="Export Staff"><i class="fa fa-external-link "></i> </a>
               <div class="input-group col-md-12">
                  <input type="text" class="search-staff form-control" placeholder="Search Staff" />
                  <span class="input-group-btn">
                  <button class="btn btn-danger" type="button"> <span class=" glyphicon glyphicon-search"></span> </button>
                  </span>
               </div>
            </div>
            <div class="stf-list heightfull">
               <a href="#" class="active">
                  <img src="{{asset('public/assets/website/images/business-hours/blue-user.png')}}" /> 
                  <div>
                     <h3>Steph Pouyet</h3>
                     <small>steph.pouyet@hotmail.com</small>
                  </div>
               </a>
               <a href="#" >
                  <img src="{{asset('public/assets/website/images/business-hours/blue-user.png')}}" /> 
                  <div>
                     <h3>Steph Pouyet</h3>
                     <small>steph.pouyet@hotmail.com</small>
                  </div>
               </a>
               <a href="#" >
                  <img src="{{asset('public/assets/website/images/business-hours/blue-user.png')}}" /> 
                  <div>
                     <h3>Steph Pouyet</h3>
                     <small>steph.pouyet@hotmail.com</small>
                  </div>
               </a>
               <a href="#" >
                  <img src="{{asset('public/assets/website/images/business-hours/blue-user.png')}}" /> 
                  <div>
                     <h3>Steph Pouyet</h3>
                     <small>steph.pouyet@hotmail.com</small>
                  </div>
               </a>
               <a href="#" >
                  <img src="{{asset('public/assets/website/images/business-hours/blue-user.png')}}" /> 
                  <div>
                     <h3>Steph Pouyet</h3>
                     <small>steph.pouyet@hotmail.com</small>
                  </div>
               </a>
               <a href="#" >
                  <img src="{{asset('public/assets/website/images/business-hours/blue-user.png')}}" /> 
                  <div>
                     <h3>Steph Pouyet</h3>
                     <small>steph.pouyet@hotmail.com</small>
                  </div>
               </a>
            </div>
         </div>
      </div>
      <div class="rightpan">
         <div class="row">
            <div class="col-lg-12">
               <form>
                  <div class="headRow  custm-linkedt custm-l " id="clientdatabase">
                     <ul>
                        <li><a><i class="fa fa-edit"></i> Edit </a> </li>
                        <li><a><i class="fa fa-paper-plane"></i> Invite </a> </li>
                        <li><a><i class="fa fa-lock"></i> Verify </a> </li>
                     </ul>
                  </div>
                  <div class="headRow cdHeadbar">
                     <div class="namebar">
                        <label>ST</label>
                     </div>
                     <h3> Steph Pouyet<br/>
                        <span>steph.pouyet@hotmail.com</span> 
                     </h3>
                  </div>
                  <div class="headRow">
                     <ul class="schedulebh nomarginbottom showDekstop clearfix">
                        <li><a href="#" class="active">Info </a></li>
                        <li><a href="#">Appointments </a></li>
                        <li><a href="#">Give as a gift </a></li>
                        <li><a href="#">Payments</a></li>
                        <li><a href="#">Feedback</a></li>
                        <li><a href="#">Journey</a></li>
                        <li><a href="#">Notes</a></li>
                     </ul>
                     <div class="padding15px clearfix">
                        <div class="infoBar">
                           <div>
                              <h2>Customer Notes</h2>
                              <label>Notes help you keep track of your customers</label>
                           </div>
                        </div>
                        <div class="infoBar">
                           <div>
                              <h2>Mobile</h2>
                              <label>0788852211</label>
                           </div>
                        </div>
                        <div class="infoBar">
                           <div>
                              <h2>Registration</h2>
                              <label>22 May 2018, 05:06 PM</label>
                              <span>Created by Admin</span> 
                           </div>
                           <div>
                              <h2>Last Appointment</h2>
                              <label>22 May 2018, 05:06 PM</label>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="popup">
   <div id="selectstaff">
      <div class="container-fluid">
         <div class="popupInside">
            <h3>Select Staff</h3>
            <ul>
               <li><a onclick="staffcheck(this)"><img src="{{asset('public/assets/website/images/business-hours/blue-user.png')}}"/>
                  <label>Douglas N</label>
                  </a> 
               </li>
               <li><a onclick="staffcheck(this)"><img src="{{asset('public/assets/website/images/business-hours/grey-user.png')}}"/>
                  <label>Janice D</label>
                  </a> 
               </li>
            </ul>
         </div>
      </div>
   </div>
</div>
@endsection