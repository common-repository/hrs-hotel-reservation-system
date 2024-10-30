jQuery(document).ready(function($){
	/* mhrs_hotel_search_form_obj */
	/* console.log(mhrs_hotel_search_form_obj); */
	
	var min_date = mhrs_hotel_search_form_obj.min_date;
	var max_date = mhrs_hotel_search_form_obj.max_date;
	var checkindate = mhrs_hotel_search_form_obj.checkindate;
	var checkoutdate = mhrs_hotel_search_form_obj.checkoutdate;
	
	
	$(".mhrs_hotel_search_wrap .checkindate").datepicker({
		dateFormat: 'MM dd, yy',
		changeMonth: true,
		changeYear: true,
		minDate: checkindate,
		maxDate: max_date,
		onSelect: function(dateStr) {
			var new_checkout_min = $(this).datepicker('getDate');
			var new_checkout_min_show = $(this).datepicker('getDate');
			
			new_checkout_min.setDate(new_checkout_min.getDate() + 1);
			new_checkout_min_show.setDate(new_checkout_min_show.getDate() + 2);
			
			new_checkout_min = format_date_ymd( new_checkout_min );
			new_checkout_min_show = format_date_ymd( new_checkout_min_show );
			
			$(".mhrs_hotel_search_wrap .checkoutdate").datepicker('option', 'minDate', new_checkout_min );
			$(".mhrs_hotel_search_wrap .checkoutdate").val(new_checkout_min_show);
		}
	});
	$(".mhrs_hotel_search_wrap .checkoutdate").datepicker({
		dateFormat: 'MM dd, yy',
		changeMonth: true,
		changeYear: true,
		minDate: checkoutdate,
		maxDate: max_date
	});
	
	$("#mhrs_hotel_search_location").change(function(){
		curr_location = $(this).val();
		curr_location = $.trim( curr_location );
		
		load_hotels( curr_location );
	});
	/* 
	$("#mhrs_hotel_search_hotel").change(function(){
		curr_hotel_id = $(this).val();
		curr_hotel_id = $.trim( curr_hotel_id );
		
		load_rooms( curr_hotel_id );
	});
	 */
	$("#mhrs_hotel_search_options_button").click(function(){
		$("#mhrs_hotel_search_options").slideToggle({
			done: function(){
				if( $("#mhrs_hotel_search_options").is(':visible') ){
					$("#mhrs_hotel_search_options_button").html('- Hide Options');
				} else {
					$("#mhrs_hotel_search_options_button").html('+ Show Options');
				}
			}
		});
		
	});
	
	/* Checkout code started*/
	/* Login on checkout page.*/
	$("#mhrs_checkout_login_frm").submit(function(e) {
        e.preventDefault();
		$("#mhrs_checkout_l_loader").show();
		var mhrs_username = $("#mhrs_checkout_login_username").val();
		var mhrs_password = $("#mhrs_checkout_login_password").val();
		$.ajax({
			url: mhrs_hotel_search_form_obj.admin_ajax_url,
			method: 'POST',
			dataType: 'json',
			data: {
				'action': 'mhrsajax_login_user_on_checkout',
				'username': mhrs_username,
				'password': mhrs_password
			}
		}).done(function(r){
			if( r.success )
			{
				if(r.data.rcode == 1)
				{
					//location.href = location.href;
					$("#mhrs_make_user_login").submit();
				}
				else
				{
					$("#Login_error").html(r.data.msg);
				}
			}
			else
			{
				$("#Login_error").html("Something went wrong! Please try again.");
			}
			$("#mhrs_checkout_l_loader").hide();
		}).fail(function( jqXHR, textStatus ){
			$("#mhrs_checkout_l_loader").hide();
			$("#Login_error").html("Something went wrong! Please try again.");
		});
    });
	
	/* Registration on checkout page.*/
	$("#mhrs_checkout_registration_frm").submit(function(e) {
		e.preventDefault();
		$("#mhrs_checkout_r_loader").show();
		var mhrs_checkout_r_title = $("#mhrs_checkout_r_title").val();
		var mhrs_checkout_r_f_name = $("#mhrs_checkout_r_f_name").val();
		var mhrs_checkout_r_l_name = $("#mhrs_checkout_r_l_name").val();
		var mhrs_checkout_r_address = $("#mhrs_checkout_r_address").val();
		var mhrs_checkout_r_city = $("#mhrs_checkout_r_city").val();
		var mhrs_checkout_r_state = $("#mhrs_checkout_r_state").val();
		var mhrs_checkout_r_pcode = $("#mhrs_checkout_r_pcode").val();
		var mhrs_checkout_r_country = $("#mhrs_checkout_r_country").val();
		var mhrs_checkout_r_phone = $("#mhrs_checkout_r_phone").val();
		var mhrs_checkout_r_id_type = $("#mhrs_checkout_r_id_type").val();
		var mhrs_checkout_r_id_number = $("#mhrs_checkout_r_id_number").val();
		var mhrs_checkout_r_email = $("#mhrs_checkout_r_email").val();
		var mhrs_checkout_r_password = $("#mhrs_checkout_r_password").val();
		
		$.ajax({
			url: mhrs_hotel_search_form_obj.admin_ajax_url,
			method: 'POST',
			dataType: 'json',
			data: {
				'action': 'mhrsajax_register_user_on_checkout',
				'mhrs_checkout_r_title': mhrs_checkout_r_title,
				'mhrs_checkout_r_f_name': mhrs_checkout_r_f_name,
				'mhrs_checkout_r_l_name': mhrs_checkout_r_l_name,
				'mhrs_checkout_r_address': mhrs_checkout_r_address,
				'mhrs_checkout_r_city': mhrs_checkout_r_city,
				'mhrs_checkout_r_state': mhrs_checkout_r_state,
				'mhrs_checkout_r_pcode': mhrs_checkout_r_pcode,
				'mhrs_checkout_r_country': mhrs_checkout_r_country,
				'mhrs_checkout_r_phone': mhrs_checkout_r_phone,
				'mhrs_checkout_r_id_type': mhrs_checkout_r_id_type,
				'mhrs_checkout_r_id_number': mhrs_checkout_r_id_number,
				'mhrs_checkout_r_email': mhrs_checkout_r_email,
				'mhrs_checkout_r_password': mhrs_checkout_r_password
			}
		}).done(function(r){
			if( r.success )
			{
				if(r.data.rcode == 1)
				{
					//location.href = location.href;
					$("#mhrs_make_user_login").submit();
				}
				else
				{
					$("#register_error").html(r.data.msg);
				}
			}
			else
			{
				$("#register_error").html("Something went wrong! Please try again.");
			}
			$("#mhrs_checkout_r_loader").hide();
		}).fail(function( jqXHR, textStatus ){
			$("#mhrs_checkout_r_loader").hide();
			$("#register_error").html("Something went wrong! Please try again.");
		});
	});
	
	/* getting available rooms */
	$(".mhrs_adult_stay").change(function(){
		var roomid = $(this).attr("data-roomid");
		var adult_stay = $(this).val();
		var children_stay = $("#mhrs_children_stay_"+roomid).val();
		var checkindate = $("#mhrs_checkindate").val();
		var checkoutdate = $("#mhrs_checkoutdate").val();
		
		if( adult_stay > 0 || children_stay > 0 ){
			get_available_rooms(roomid, adult_stay, children_stay, checkindate, checkoutdate);
		} else {
			$("#mhrs_rooms_required_"+roomid).html( '0' );
			$("#select_booking_"+roomid).prop("checked",false);
			$("#select_booking_"+roomid).prop("disabled",true);
		}
	});
	
	$(".mhrs_children_stay").change(function(){
		var roomid = $(this).attr("data-roomid");
		var adult_stay = $("#mhrs_adult_stay_"+roomid).val();
		var children_stay = $(this).val();
		var checkindate = $("#mhrs_checkindate").val();
		var checkoutdate = $("#mhrs_checkoutdate").val();
		
		if( adult_stay > 0 || children_stay > 0 ){
			get_available_rooms(roomid, adult_stay, children_stay, checkindate, checkoutdate);
		} else {
			$("#mhrs_rooms_required_"+roomid).html( '0' );
			$("#select_booking_"+roomid).prop("checked",false);
			$("#select_booking_"+roomid).prop("disabled",true);
		}
	});
	/* getting available rooms, start */
	
	/* Checkout code ends here.*/
	
	/* functions, start */
	function format_date_ymd(date) {
		var d = new Date(date),
			month = '' + (d.getMonth() + 1),
			day = '' + d.getDate(),
			year = d.getFullYear();

		if (month.length < 2) month = '0' + month;
		if (day.length < 2) day = '0' + day;
		
		month = get_month_name( ( parseInt(month) - 1 ) );
		return ( month + ' ' + day + ', ' + year );
	}
	
	function get_month_name(monthnumber){
		var m = new Array();
		m[0] = "January";
		m[1] = "February";
		m[2] = "March";
		m[3] = "April";
		m[4] = "May";
		m[5] = "June";
		m[6] = "July";
		m[7] = "August";
		m[8] = "September";
		m[9] = "October";
		m[10] = "November";
		m[11] = "December";
		return m[monthnumber];
	}
	
	function add_query_url(uri, key, value) {
		var i = uri.indexOf('#');
		var hash = i === -1 ? ''  : uri.substr(i);
		uri = i === -1 ? uri : uri.substr(0, i);
		
		var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
		var separator = uri.indexOf('?') !== -1 ? "&" : "?";
		
		if (uri.match(re)) {
			uri = uri.replace(re, '$1' + key + "=" + value + '$2');
		} else {
			uri = uri + separator + key + "=" + value;
		}
		
		return uri + hash;  // finally append the hash as well
	}
	
	function get_available_rooms(roomid, adult_stay, children_stay, checkindate, checkoutdate){
		var rooms_required = $("#mhrs_rooms_required_"+roomid);
		var ajaxloader = $("#hs_hotel_ajax_loader_"+roomid);
		var rooms_required_input = $("#rooms_required_input_"+roomid);
		
		rooms_required.html('');
		ajaxloader.css("display","inline-block");
		
		$.ajax({
			url: mhrs_hotel_search_form_obj.admin_ajax_url,
			type: "POST",
			data: {
				action : 'mhrsajax_get_available_rooms',
				roomid : roomid,
				adult_stay : adult_stay,
				children_stay : children_stay,
				checkindate : checkindate,
				checkoutdate : checkoutdate
			},
			dataType: "json"
		}).done(function( r ) {
			if( r.success ){
				var required_rooms = r.data.required_rooms;
				console.log( required_rooms );
				rooms_required.html( required_rooms );
				rooms_required_input.val(required_rooms);
				
				ajaxloader.css("display","none");
				$("#select_booking_"+roomid).prop("disabled",false);
				$("#select_booking_"+roomid).prop("checked",true);
			} else {
				$("#select_booking_"+roomid).prop("checked",false);
				$("#select_booking_"+roomid).prop("disabled",true);
				rooms_required.html( r.data.content );
				rooms_required_input.val('');
				
				ajaxloader.css("display","none");
			}
		}).fail(function( jqXHR, textStatus ) {
			console.log('-- error start --');
			console.log(jqXHR);
			console.log(textStatus);
			console.log('-- error stop --');
			rooms_required.html( textStatus );
			ajaxloader.css("display","none");
		});
	}
	
	function load_hotels( loc ){
		if( curr_location != '' ){
			ajax_loader = $(".hs_hotel_ajax_loader");
			
			ajax_loader.css('display', 'inline-block');
			
			$( "#mhrs_hotel_search_hotel" ).prop( "disabled", false );
			
			$.ajax({
				url: mhrs_hotel_search_form_obj.admin_ajax_url,
				method: 'POST',
				dataType: 'json',
				data: {
					'action': 'mhrsajax_load_hotels_by_location_id',
					'location_id': loc
				}
			}).done(function(r){
				if( r.success ){
					html	=	'<option value="">-Choose Hotel-</option>';
					$.each(r.data, function(key, value){
						id = value.id;
						title = value.title;
						html	+=	'<option value="' + id + '">' + title + '</option>';
					});
					
					$( "#mhrs_hotel_search_hotel" ).html(html);
				}
				
				ajax_loader.css('display', 'none');
				
			}).fail(function( jqXHR, textStatus ){
				//console.log(jqXHR);
				//console.log(textStatus);
				ajax_loader.css('display', 'none');
			});
			
		} else {
			$( "#mhrs_hotel_search_hotel" ).prop( "disabled", true );
		}
	}
	
	function load_rooms( hotel_id ){
		if( hotel_id != '' ){
			ajax_loader = $(".hs_room_ajax_loader");
			
			ajax_loader.css('display', 'inline-block');
			
			$( "#mhrs_hotel_search_room" ).prop( "disabled", false );
			
			$.ajax({
				url: mhrs_hotel_search_form_obj.admin_ajax_url,
				method: 'POST',
				dataType: 'json',
				data: {
					'action': 'mhrsajax_load_rooms_by_hotel_id',
					'hotel_id': hotel_id
				}
			}).done(function(r){
				if( r.success ){
					html	=	'<option value="">-Choose Room-</option>';
					$.each(r.data, function(key, value){
						id = value.id;
						title = value.title;
						html	+=	'<option value="' + id + '">' + title + '</option>';
					});
					
					$( "#mhrs_hotel_search_room" ).html(html);
				}
				
				ajax_loader.css('display', 'none');
				
			}).fail(function( jqXHR, textStatus ){
				//console.log(jqXHR);
				//console.log(textStatus);
				ajax_loader.css('display', 'none');
			});
			
		} else {
			$( "#mhrs_hotel_search_room" ).prop( "disabled", true );
		}
	}
	
	/* functions, stop */
	
});