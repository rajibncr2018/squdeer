@extends('../layouts/website/master_template_web')
@section('title')
Squeedr
@endsection

@section('content')
 <div class="body-part">
   <div class="container-custm">
      <div class="upper-cmnsection">
         <div class="heading-uprlft">Business Details</div>
         <div class="upr-rgtsec">
            <div class="col-sm-5">
               <div id="custom-search-input">
                  <div class="input-group ">
                     <input type="text" class="  search-query form-control" placeholder="Search" />
                     <span class="input-group-btn">
                     <button class="btn btn-danger" type="button"> <span class=" glyphicon glyphicon-search"></span> </button>
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
                  <div class="filter-option"><a href="#">Show Filter <i class="fa fa-filter" aria-hidden="true"></i></a></div>
               </div>
            </div>
         </div>
      </div>
      <div class="leftpan">
         <div class="left-menu">
            <ul>
               <li><a href="{{ url('business-details1') }}"  class="active"> Business Contact Info.</a></li>
               <li><a href="{{ url('business-details2') }}"> Business Logo & Social Info.</a> </li>
            </ul>
         </div>
      </div>
      <div class="rightpan">
         <div class="col-lg-12">
            <form>
               <div class="headRow nopadding" id="businessdetails">
                  <ul class="footnote">
                     <li>Here you can manage the profession, contact, and physical address for each location of your business, as well as the information that will appear on the "About Us" section on you booking portal.</li>
                  </ul>
               </div>
               <div class="headRow">
                  <div class=" clearfix">
                     <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                           <div class="form-details">
                              <label for="Profession">Profession</label>
                              <select class="form-control">
                                 <option>IT Services</option>
                              </select>
                              <label for="Business Name">Business Name</label>
                              <input class="form-control" type="text" />
                              <label for="Business Location">Business Location</label>
                              <input class="form-control" type="text" />
                              <div class="row">
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <label for="Country">Country</label>
                                    <select class="form-control">
                                       <option>France</option>
                                    </select>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <label for="Region">Region</label>
                                    <select class="form-control">
                                       <option>Ile-De-France</option>
                                    </select>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <label for="City">City</label>
                                    <select class="form-control">
                                       <option>Paris</option>
                                    </select>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <label for="Region">Zipcode</label>
                                    <input class="form-control" type="text" />
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <label for="City">Mobile Phone</label>
                                    <input class="form-control" type="text" />
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-6">
                                    <label for="Region">Office Phone</label>
                                    <input class="form-control" type="text" />
                                 </div>
                              </div>
                              <label for="Phone">Skype ID</label>
                              <input class="form-control" type="text" />
                              <label for="Business Description">Business Description</label>
                              <textarea class="form-control" rows="4"></textarea>
                              <span class="specialnote">HTML Tags not allowed, 1000 characters remaining</span>
                           </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                           <iframe class="img-thumbnail" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14743.31409025346!2d88.39881!3d22.510616!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xd9bfd73a5d056f32!2sNCR+Technosolutions+%7C+Mobile+App+Development+Company!5e0!3m2!1sen!2sin!4v1531414309030" width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
                        </div>
                        <div class="clearfix"></div>
                        <a class="btn btn-primary butt-next" style="margin: 30px auto 0; width: 150px; display: block">Update</a>
                     </div>
                  </div>
               </div>
            </form>
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
               <li><a onclick="staffcheck(this)"><img src="images/business-hours/blue-user.png"/>
                  <label>Douglas N</label>
                  </a> 
               </li>
               <li><a onclick="staffcheck(this)"><img src="images/business-hours/grey-user.png"/>
                  <label>Janice D</label>
                  </a> 
               </li>
            </ul>
         </div>
      </div>
   </div>
</div>
@endsection