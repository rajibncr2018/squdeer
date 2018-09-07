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
               <li><a href="{{ url('business-details1') }}" > Business Contact Info.</a></li>
               <li><a href="{{ url('business-details2') }}" class="active"> Business Logo & Social Info.</a> </li>
            </ul>
         </div>
      </div>
      <div class="rightpan">
         <div class="col-lg-12">
            <form>
               <div class="headRow">
                  <div class=" clearfix">
                     <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                           <div class="form-details">
                              <div class="upload-logo">
                                 <img src="{{asset('public/assets/website/images/picture.png')}}"><br>
                                 <span>Upload Business Logo</span>
                              </div>
                              <div class="upload-logo">
                                 <img src="{{asset('public/assets/website/images/picture.png')}}"><br>
                                 <span>Upload Other Photos</span>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                           <div class="form-details">
                              <label for="Phone">Facebook Link</label>
                              <input class="form-control" type="text" />
                              <label for="Phone">Twitter Link</label>
                              <input class="form-control" type="text" />
                              <label for="Phone">Linked IN Link</label>
                              <input class="form-control" type="text" />
                              <label for="Phone">User Website Link</label>
                              <input class="form-control" type="text" />
                           </div>
                        </div>
                     </div>
                     <div class="clearfix"></div>
                     <a class="btn btn-primary butt-next" style="margin: 30px auto 0; width: 150px; display: block">Update</a>
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