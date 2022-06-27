<?php 
$book_id = !empty($_GET['book_id']) ? $_GET['book_id'] : '';

$lawyer_name = '';
	$lawyer_url='';
if($book_id){
	$lawyer_name = get_the_title($book_id);
	$lawyer_url = get_permalink($book_id);
}


$duration = 60; // how much the is the duration of a time slot
$start    = '09:00'; // start time
$end      = '23:00'; // end time



 $timeSlots =  prepare_time_slots($start, $end,$duration,$currenTime=null) ;
 
 $getOffDays =  get_field('week_days',$book_id);
 $use_default_time = get_field('use_default_time',$book_id);
 
 $weekDaysOffAr= array('sunday'=>false,'monday'=>false,'tuesday'=>false,'wednesday'=>false,'thursday'=>false,'friday'=>false,'saturday'=>false);
  $weekDaysOff =array();
 foreach($weekDaysOffAr as $key=>$day){
	  if($key=="sunday"){
		   $weekDaysOff[ucfirst($key)]= false;
	  }else{
		   $weekDaysOff[ucfirst($key)]= $getOffDays[$key.'_off'];
	  }
 }
$weekDaysOff = json_encode($weekDaysOff);


//var_dump($use_default_time);
?>
<div class="calendar-bx">
    <div class="row">
      <div  class="col-sm-6 calendar-bx-left">
			<div class="lawyer_icon">
			<img aria-hidden="true" alt="" class="private-image img-circle private-image--circle header-avatar avatar-image user-provided-avatar img-responsive private-image--responsive" src="https://cdn2.hubspot.net/hubfs/20975772/LinkedIn%20-Profile%20Picture.png">
			</div>
			<div class="meeting_with"><a href="<?php echo $lawyer_url; ?>" class="meet_name"> Meet with <?php echo $lawyer_name; ?> </a></div>
			<div class="datepick"></div>
	 </div>
	  <div class="col-sm-6 calendar-bx-right">

	    <div class="time_slot">
		    <div class="meeting_duration">Meeting at</div>
			  <span class="meeting_date_message"></span>  <span class="meeting_time_message"></span>
		    <div class="meeting_duration">Meeting duration</div>
		    <div class="meeting_duration_time">1 hour</div>
			<div class="time_best">What time works best?</div>
			<ul class="time_slot_list">
			   <?php foreach($timeSlots as $slot){?>
			    <li class="time_slot_<?php echo $slot; ?>"><a href="javascript:void(0);" class="time_book_now"  data-slot="<?php echo $slot; ?>"><?php echo $slot; ?></a></li>
			   <?php } ?>
			</ul>
		</div>
	  </div>
	  </div>
	    </div>

<div id="booking_form_lawyer" class="modal" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
		     <h1>Your information</h2>
		     <span class="meeting_date_message"></span>  <span class="meeting_time_message"></span>
           <?php  echo do_shortcode('[contact-form-7 id="1302" title="Lawyer Contact Booking Form"]');?> 
       </div>
        </div>
      
     </div>
    </div>
</div>
	  
	  
   
 <script>
 var use_default_time = '<?php echo $use_default_time; ?>';
 var weekDaysOff = JSON.parse('<?php echo $weekDaysOff; ?>');

console.log('use_default_time ' + use_default_time)
var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

var monthNames = ["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"
];


function getCurrentTime(){
	var today = new Date();
	var month = ((today.getMonth()+1) < 10? '0':'')+(today.getMonth()+1);
	var day= (today.getDate() < 10? '0' : '') + today.getDate();
	
	var monthName = 	monthNames[today.getMonth()];

	var dayName = days[today.getDay()];	
		

	var minutes = (today.getMinutes() < 10? '0' : '') + today.getMinutes();
	var hours = (today.getHours() < 10? '0' : '') + today.getHours();
	var seconds = (today.getSeconds() < 10? '0' : '') + today.getSeconds();
	//var time =  hours + ":" + minutes + ":" + seconds;
	var time =  hours + ":" + minutes;

	var date =  dayName+', '+ monthName+' '+day+', '+today.getFullYear();

	var dateTime = date+' '+time;
	return time;
}

 
function getCurrentDateTime(){
	var today = new Date();
	var month = ((today.getMonth()+1) < 10? '0':'')+(today.getMonth()+1);
	var day= (today.getDate() < 10? '0' : '') + today.getDate();
	
	var monthName = 	monthNames[today.getMonth()];

	var dayName = days[today.getDay()];	
		

	var minutes = (today.getMinutes() < 10? '0' : '') + today.getMinutes();
	var hours = (today.getHours() < 10? '0' : '') + today.getHours();
	var seconds = (today.getSeconds() < 10? '0' : '') + today.getSeconds();
	var time =  hours + ":" + minutes + ":" + seconds;

	var date =  dayName+', '+ monthName+' '+day+', '+today.getFullYear();

	var dateTime = date+' '+time;
	return date;
}

function convertTime(selectdata){


	var today = new Date(selectdata);
	var month = ((today.getMonth()+1) < 10? '0':'')+(today.getMonth()+1);
	var day= (today.getDate() < 10? '0' : '') + today.getDate();
	
	var monthName = 	monthNames[today.getMonth()];

	var dayName = days[today.getDay()];	
		

	var minutes = (today.getMinutes() < 10? '0' : '') + today.getMinutes();
	var hours = (today.getHours() < 10? '0' : '') + today.getHours();
	var seconds = (today.getSeconds() < 10? '0' : '') + today.getSeconds();
	var time =  hours + ":" + minutes + ":" + seconds;

	var date =  dayName+', '+ monthName+' '+day+', '+today.getFullYear();

	var dateTime = date+' '+time;
	return date;
}

function convertTimeServer(selectdata){


	var today = new Date(selectdata);
	var month = ((today.getMonth()+1) < 10? '0':'')+(today.getMonth()+1);
	var day= (today.getDate() < 10? '0' : '') + today.getDate();
	
	var monthName = 	monthNames[today.getMonth()];

	var dayName = days[today.getDay()];	
		

	var minutes = (today.getMinutes() < 10? '0' : '') + today.getMinutes();
	var hours = (today.getHours() < 10? '0' : '') + today.getHours();
	var seconds = (today.getSeconds() < 10? '0' : '') + today.getSeconds();
	var time =  hours + ":" + minutes + ":" + seconds;

	var date =  today.getFullYear()+'-'+ month+'-'+day;

	var dateTime = date+' '+time;
	return date;
}


 var select_date = getCurrentDateTime();
 var slot_time  ='';
 var lawyer_name ='<?php echo $lawyer_name; ?>';
 var lawyer_url='<?php echo $lawyer_url; ?>';
  var book_id='<?php echo $book_id; ?>';


jQuery(function($) {
	
	function fetch_time_slot_by_date_user(post_id,date,time){
		$('.time_slot_list').html('<li class="wait"> Please wait... </li>');
		$.ajax({
			type: "POST",
			url : php_vars.ajax_url,
			data :{
				action : 'fetch_time_slot_by_date_user',
				pid : post_id,
				date : date,
				currentTime:time
			},
			success:function(res){
				console.log(res);
				$('.time_slot_list').html(res);
			}
			
		})
	}

	$('.datepick').calendar({
 disable: function (date) { 
     var dayName= days[date.getDay()];
 //console.log(dayName +' '+ use_default_time);
    if(date < new Date()){
		return true;
	}
	if(dayName=="Sunday"){
		return true;
	}
	
	if(use_default_time){
		if(dayName=="Saturday"){
			return true;
		}
	}
		
	if(!use_default_time){
		if(weekDaysOff[dayName]){
			return true;
		}
	}

	
	
    //return date < new Date(); // This will disable all dates before today
  },
	  onChangeMonth: function (date) {},
	  onClickToday: function (date) {},
	  onClickMonthNext: function (date) {},
	  onClickMonthPrev: function (date) {},
	  onClickYearNext: function (date) {},
	  onClickYearPrev: function (date) {},
	  onShowYearView: function (date) {},
	  onSelectYear: function (date) {},
	   onClickDate: function (date) {
		   
		   select_date = convertTime(date);
		   var currentTime = getCurrentTime();
		   fetch_time_slot_by_date_user(book_id, convertTimeServer(date),currentTime);
		 $(".meeting_date_message").text(select_date);		
	   // $(".meeting_time_message").text(slot_time);	
         
  }
	  
	});	 
 
 $('body').on('click','.time_book_now',function(){
	
	    slot_time = jQuery(this).text();
	   	$(".modal-title").text(lawyer_name);
	    $(".meeting_date_message").text(select_date);		
	    $(".meeting_time_message").text(slot_time);		
		$("[name='contact_date']").val(select_date);
		$("[name='contact_time_slot']").val(slot_time);
		$("[name='lawyer_name']").val(lawyer_name);
		$("[name='lawyer_url']").val(lawyer_url);
		
	  $('#booking_form_lawyer').modal('show'); 
	  
	  return false;
 });
 
 $('.calendar-box .day').click(function(){

 $('.calendar-box .day').removeClass('active');
 $(this).addClass('active');	
	 
 });

 $(".close").click(function(){
            $("#booking_form_lawyer").modal('hide');
        });


/*Onload */


          //var select_date = convertTime(new Date());
		   var currentTime = getCurrentTime();
		   fetch_time_slot_by_date_user(book_id, convertTimeServer(new Date()),currentTime);
});
  </script> 
  		 