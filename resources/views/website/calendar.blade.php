@extends('../layouts/website/master_template_web')
@section('title')
Squeedr
@endsection

@section('custom_css')
<!-- fullCalendar 2.2.5-->
<link rel="stylesheet" href="{{asset('public/assets/website/plugins/fullcalendar/fullcalendar.min.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/website/plugins/fullcalendar/fullcalendar.print.css')}}" media="print">
@endsection

@section('content')
<div class="body-part">
   <div class="container-custm">
      <div class="upper-cmnsection">
         <div class="heading-uprlft">Calendar</div>
         <div class="upr-rgtsec">
            <div class="col-sm-5">
               <div id="custom-search-input">
                  <div class="input-group col-md-12">
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
                  <div class="filter-option"><a href="/">Show Filter <i class="fa fa-filter" aria-hidden="true"></i></a></div>
               </div>
            </div>
         </div>
      </div>
      <div class="clearfix"></div>
      <div class="rightpan full">
         <!-- THE CALENDAR -->
         <div id="calendar"></div>


        <div id='datepicker'></div>


        <div class="modal fade in" id="myModalContent" role="dialog">
            <div class="modal-dialog add-pop">
                <!-- Modal content-->
                <div class="modal-content new-modalcustm">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title">Appointment Details</h4>
                    </div>
                    
                    <div class="modal-body clr-modalbdy">
                        <!--<div class="row">
                            <div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group"> <span class="input-group-addon"><i class="fa fa-ban"></i></span>
                                    <input id="title" type="text" class="form-control" name="block" placeholder="">
                                </div>
                            </div>
                            </div>
                        </div>-->
                        Staff: <span id="">Jeet Roy</span><br>
                        Client: <span id="">Pradipta Barik</span><br>
                        Date: <span id="">09-05-12018</span><br>
                        Start: <span id="startTime"></span><br>
                        End: <span id="endTime"></span><br><br>
                        
                        <p><strong><a id="eventLink" href="" target="_blank">Read More</a></strong></p>
                        
                    </div>
                    
                </div>
            </div>
        </div>

      </div>
   </div>
</div>
@endsection

@section('custom_js')
<!-- fullCalendar 2.2.5 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="{{asset('public/assets/website/plugins/fullcalendar/fullcalendar.min.js')}}"></script>
<script>
      $(function () {
        /* initialize the calendar
         -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date();
        var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
        $('#calendar').fullCalendar({
            minTime: "07:00:00",
            maxTime: "21:00:00",
            /*dayClick: function(date, jsEvent, view) {
                $('#calendar').fullCalendar('gotoDate',date);
                $('#calendar').fullCalendar('changeView','agendaDay');
            },*/

          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
          },
          navLinks: true, // can click day/week names to navigate views
          buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day'
          },
          //Random default events
          events: [
            {
              title: 'Lunch',
              start: new Date(y, m, d, 12, 0),
              end: new Date(y, m, d, 14, 0),
              allDay: false,
              backgroundColor: "#00c0ef", //Info (aqua)
              borderColor: "#00c0ef" //Info (aqua)
            }
          ],
          editable: true,
          droppable: false, // this allows things to be dropped onto the calendar !!!

          eventRender: function(event, element) {
            //$(element).tooltip({title: event.title});
            element.attr('href', 'javascript:void(0);');
            element.click(function() {
                $("#startTime").html(moment(event.start).format('MMM Do h:mm A'));
                $("#endTime").html(moment(event.end).format('MMM Do h:mm A'));
                $("#eventInfo").html(event.description);
                $("#eventLink").attr('href', event.url);
                $("#myModalContent").modal('show');
            });             
          },

            selectable: true,
            selectHelper: true,
            select: function(date, start, end, jsEvent, view) {
                // Display the modal.
                // You could fill in the start and end fields based on the parameters
                if(start.isBefore(moment())) {
                    $('#calendar').fullCalendar('unselect');
                    return false;
                }
    
                var strtdt = moment(start).format();
                var enddt = moment(end).format();
                $('#appointmenttime').val(moment(start).format('LT'));
                $('#appointmentdate').val(moment(date).format('l'));
                $('#myModaladdappoinment').modal('show');
                //$('.modal').modal('show');

            },
            eventClick: function(event, element) {
                // Display the modal and set the values to the event values.
                /*$('.modal').modal('show');
                $('.modal').find('#title').val(event.title);
                $('.modal').find('#starts-at').val(event.start);
                $('.modal').find('#ends-at').val(event.end);*/

            },
            editable: true,
            eventLimit: true // allow "more" link when too many events


        });

        //$("#starttime, #endtime").datetimepicker();

        $('#save-event').on('click', function() {
            var title = $('#title').val();
            var date = $('#date').val();
            var start = $('#starttime').val();
            var end = $('#endtime').val();

            if (title) {
                var eventData = {
                    title: title,
                    start: new Date(y, m, d, start),
                    end: new Date(y, m, d, end),
                    allDay: false,
                    backgroundColor: "#00c0ef", //Info (aqua)
                    borderColor: "#00c0ef" //Info (aqua)
                };
                $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
            }
            $('#calendar').fullCalendar('unselect');

            // Clear modal inputs
            $('#myModalAppointment').find('input').val('');

            // hide modal
            $('#myModalAppointment').modal('hide');
        });

      });

      
</script>
@endsection