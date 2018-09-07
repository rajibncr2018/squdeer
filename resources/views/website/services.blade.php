@extends('../layouts/website/master_template_web')
@section('title')
Squeedr
@endsection

@section('content')
<div class="body-part">
<div class="container-custm">
   <div class="upper-cmnsection">
      <div class="heading-uprlft">Dashboard</div>
      <div class="upr-rgtsec">
         <div class="col-md-6">
            <ul class="tab-menu">
               <li><a href="#" class="active">My Squeedr</a></li>
               <li><a href="#">Group</a></li>
               <li><a href="#">Users</a></li>
               <li><a href="#">Template</a></li>
            </ul>
         </div>
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
         <div class="col-sm-1">
            <div class="filter-option"><a href="#">  <i class="fa fa-filter" aria-hidden="true"></i></a></div>
         </div>
      </div>
   </div>
   <div class="clearfix"></div>
   <div class="leftpan">
      <div class="left-menu">
         <ul>
            <li><a href="#" class="active"> Services</a></li>
            <li><a href="#"> Packs</a> </li>
            <li><a href="#">Resource</a></li>
            <li><a href="#">Meetings</a></li>
         </ul>
      </div>
   </div>
   <div class="rightpan">
      <!-- <div class="headRow showDekstop relativePostion clearfix" id="row1">
         <div class="col-lg-9 col-md-9 col-sm-10 nopadding staticPosition">
            <div class="owl-carousel owl-theme owl-mobile staticPosition">
              <div class="item"><a href="#" class="active">All <span>(6)</span></a></div>
              <div class="item"><a href="#">Services <span>(2)</span></a></div>
              <div class="item"><a href="#">Packs <span>(1)</span></a></div>
              <div class="item"><a href="#">Resource <span>(6)</span></a></div>
              <div class="item"><a href="#">Meetings <span>(1)</span></a></div>
            </div>
          </div>
          <div class="filter"> <a onclick="showFilter(this)"><i class="fa fa-sliders"></i> </a>
            <ul>
              <li><a><i class="fa fa-eye"></i> Active </a> </li>
              <li><a><i class="fa fa-eye-slash"></i> Inactive </a> </li>
              <li><a><i class="fa fa-times"></i> Cancelled </a> </li>
            </ul>
          </div>
          <div class="cal-serv"> <a href=""><i class="fa fa-calendar"></i> </a></div>
         </div>-->
      <div class="mobSevices lnk">
         <ul >
            <li onclick="showUl(this);">
               <a href="#"> Mysqueedr.link.com <i class="fa fa-caret-down"></i></a>
               <ul>
                  <li><a><i class="fa fa-copy"></i> Copy Link </a> </li>
                  <li><a><i class="fa fa-code"></i> Embed </a> </li>
               </ul>
            </li>
         </ul>
      </div>
      <div class="new-event">
         <form class="form-horizontal" action="/action_page.php">
            <div class="form-group nomarging custom-select color-b" >
               <select >
                  <option>  New Event Type</option>
               </select>
               <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
         </form>
         <div class="new-event">    
         </div>
         <div class="clearfix"></div>
      </div>
      <div class="headRow mobileappointed clearfix break40px "  id="row2">
         <div class="appointment mobSevices  col-sm-4">
            <div class="pull-left">
               <p>Dental Consultation</p>
               <span>30min-1h
               <label>$50</label>
               </span> 
            </div>
            <ul class="pull-right">
               <li onclick="showUl(this);">
                  <a> <img src="{{asset('public/assets/website/images/threeDots.png')}}"/> </a>
                  <ul>
                     <li><a><i class="fa fa-edit"></i> Edit </a> </li>
                     <li><a><i class="fa fa-copy"></i> Copy Link </a> </li>
                     <li><a><i class="fa fa-clone"></i> Clone </a> </li>
                     <li><a><i class="fa fa-floppy-o"></i> Save Template </a> </li>
                     <li><a><i class="fa fa-code"></i> Embed </a> </li>
                     <li><a><i class="fa fa-trash"></i> Delete </a> </li>
                  </ul>
               </li>
               <li><a onclick="togglebtn(this);" class="active"> <i class="fa fa-toggle-on"></i> </a> </li>
            </ul>
         </div>
         <div class="appointment mobSevices col-sm-4">
            <div class="pull-left">
               <p>Smile corrections</p>
               <span>30min-1h
               <label>$50</label>
               </span> 
            </div>
            <ul class="pull-right">
               <li onclick="showUl(this);">
                  <a> <img src="{{asset('public/assets/website/images/threeDots.png')}}"/> </a>
                  <ul>
                     <li><a href="settings-service.html"><i class="fa fa-edit"></i> Edit </a> </li>
                     <li><a><i class="fa fa-copy"></i> Copy Link </a> </li>
                     <li><a><i class="fa fa-clone"></i> Clone </a> </li>
                     <li><a><i class="fa fa-floppy-o"></i> Save Template </a> </li>
                     <li><a><i class="fa fa-code"></i> Embed </a> </li>
                     <li><a><i class="fa fa-trash"></i> Delete </a> </li>
                  </ul>
               </li>
               <li><a onclick="togglebtn(this);"> <i class="fa fa-toggle-off"></i> </a> </li>
            </ul>
         </div>
         <div class="appointment mobSevices col-sm-4">
            <div class="pull-left">
               <p>Smile corrections</p>
               <span>30min-1h
               <label>$50</label>
               </span> 
            </div>
            <ul class="pull-right">
               <li onclick="showUl(this);">
                  <a> <img src="{{asset('public/assets/website/images/threeDots.png')}}"/> </a>
                  <ul>
                     <li><a href="settings-service.html"><i class="fa fa-edit"></i> Edit </a> </li>
                     <li><a><i class="fa fa-copy"></i> Copy Link </a> </li>
                     <li><a><i class="fa fa-clone"></i> Clone </a> </li>
                     <li><a><i class="fa fa-floppy-o"></i> Save Template </a> </li>
                     <li><a><i class="fa fa-code"></i> Embed </a> </li>
                     <li><a><i class="fa fa-trash"></i> Delete </a> </li>
                  </ul>
               </li>
               <li><a onclick="togglebtn(this);"> <i class="fa fa-toggle-off"></i> </a> </li>
            </ul>
         </div>
         <div class="appointment mobSevices col-sm-4">
            <div class="pull-left">
               <p>Smile corrections</p>
               <span>30min-1h
               <label>$50</label>
               </span> 
            </div>
            <ul class="pull-right">
               <li onclick="showUl(this);">
                  <a> <img src="{{asset('public/assets/website/images/threeDots.png')}}"/> </a>
                  <ul>
                     <li><a href="settings-service.html"><i class="fa fa-edit"></i> Edit </a> </li>
                     <li><a><i class="fa fa-copy"></i> Copy Link </a> </li>
                     <li><a><i class="fa fa-clone"></i> Clone </a> </li>
                     <li><a><i class="fa fa-floppy-o"></i> Save Template </a> </li>
                     <li><a><i class="fa fa-code"></i> Embed </a> </li>
                     <li><a><i class="fa fa-trash"></i> Delete </a> </li>
                  </ul>
               </li>
               <li><a onclick="togglebtn(this);"> <i class="fa fa-toggle-off"></i> </a> </li>
            </ul>
         </div>
      </div>
      <div class="clearfix"></div>
   </div>
</div>
@endsection