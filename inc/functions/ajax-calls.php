<?php
if(!defined('ABSPATH')) {exit;}
/* Login on checkout page.*/
add_action( 'wp_ajax_mhrsajax_login_user_on_checkout', 'mhrsajax_login_user_on_checkout_func' );
add_action( 'wp_ajax_nopriv_mhrsajax_login_user_on_checkout', 'mhrsajax_login_user_on_checkout_func' );
function mhrsajax_login_user_on_checkout_func()
{
	if(isset($_POST) && $_POST['action'] == "mhrsajax_login_user_on_checkout")
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		if(!empty($username) && !empty($password))
		{
			$user_obj = "";
			if(is_email($username))
			{
				$user_obj = get_user_by("email", $username);
			}
			else
			{
				$user_obj = get_user_by("login", $username);
			}
			if(!empty($user_obj))
			{
				if ( wp_check_password( $password, $user_obj->data->user_pass, $user_obj->ID) ) 
				{
					wp_set_current_user( $user_obj->ID, $user_obj->user_login );
					wp_set_auth_cookie( $user_obj->ID );
					do_action( 'wp_login', $user_obj->user_login );
					wp_send_json_success(array("rcode" => 1, "msg" => "login successful, loading..."));
				}
			}
		}
		wp_send_json_success(array("rcode" => 2, "msg" => "<p>Invalid credentials.</p>"));
	}
	wp_send_json_error();
}
/* Registration on checkout page.*/
add_action( 'wp_ajax_mhrsajax_register_user_on_checkout', 'mhrsajax_register_user_on_checkout_func' );
add_action( 'wp_ajax_nopriv_mhrsajax_register_user_on_checkout', 'mhrsajax_register_user_on_checkout_func' );
function mhrsajax_register_user_on_checkout_func()
{
	if(isset($_POST) && $_POST['action'] == "mhrsajax_register_user_on_checkout")
	{
		$mhrs_checkout_r_title = $_POST['mhrs_checkout_r_title'];
		$mhrs_checkout_r_f_name = $_POST['mhrs_checkout_r_f_name'];
		$mhrs_checkout_r_l_name = $_POST['mhrs_checkout_r_l_name'];
		$mhrs_checkout_r_address = $_POST['mhrs_checkout_r_address'];
		$mhrs_checkout_r_city = $_POST['mhrs_checkout_r_city'];
		$mhrs_checkout_r_state = $_POST['mhrs_checkout_r_state'];
		$mhrs_checkout_r_pcode = $_POST['mhrs_checkout_r_pcode'];
		$mhrs_checkout_r_country = $_POST['mhrs_checkout_r_country'];
		$mhrs_checkout_r_phone = $_POST['mhrs_checkout_r_phone'];
		$mhrs_checkout_r_id_type = $_POST['mhrs_checkout_r_id_type'];
		$mhrs_checkout_r_id_number = $_POST['mhrs_checkout_r_id_number'];
		$mhrs_checkout_r_email = $_POST['mhrs_checkout_r_email'];
		$mhrs_checkout_r_password = $_POST['mhrs_checkout_r_password'];
		
		if($mhrs_checkout_r_email != "" && $mhrs_checkout_r_password != "" && $mhrs_checkout_r_f_name != "")
		{
			if(is_email($mhrs_checkout_r_email))
			{
				if(!email_exists($mhrs_checkout_r_email))
				{
					$userdata = array(
						'user_login'  	=>  $mhrs_checkout_r_email,
						'user_pass'   	=>  $mhrs_checkout_r_password,
						'user_email'	=>	$mhrs_checkout_r_email,
						'first_name'	=>	$mhrs_checkout_r_f_name,
						'last_name'		=>	$mhrs_checkout_r_l_name,
					);
					$user_id = wp_insert_user( $userdata ) ;
					if(!is_wp_error($user_id)) 
					{
						update_user_meta($user_id, "mhrs_checkout_r_title", $mhrs_checkout_r_title);
						update_user_meta($user_id, "mhrs_checkout_r_address", $mhrs_checkout_r_address);
						update_user_meta($user_id, "mhrs_checkout_r_city", $mhrs_checkout_r_city);
						update_user_meta($user_id, "mhrs_checkout_r_state", $mhrs_checkout_r_state);
						update_user_meta($user_id, "mhrs_checkout_r_pcode", $mhrs_checkout_r_pcode);
						update_user_meta($user_id, "mhrs_checkout_r_country", $mhrs_checkout_r_country);
						update_user_meta($user_id, "mhrs_checkout_r_phone", $mhrs_checkout_r_phone);
						update_user_meta($user_id, "mhrs_checkout_r_id_type", $mhrs_checkout_r_id_type);
						update_user_meta($user_id, "mhrs_checkout_r_id_number", $mhrs_checkout_r_id_number);
						
						/* registered successfully, send mail */
						$subject = "Registered successfully.";
						$message = "You are registered successfully on ".site_url()."<br>";
						$message .= "Username: ".$mhrs_checkout_r_email."<br>";
						$message .= "Password: ".$mhrs_checkout_r_password."<br>";						
						send_mail_via_wp_mail($mhrs_checkout_r_email, $subject, $message, $from_mail = "");
						
						$user_obj = get_user_by("id", $user_id);
						if(!empty($user_obj))
						{
							if ( wp_check_password( $mhrs_checkout_r_password, $user_obj->data->user_pass, $user_obj->ID) ) 
							{
								wp_set_current_user( $user_obj->ID, $user_obj->user_login );
								wp_set_auth_cookie( $user_obj->ID );
								do_action( 'wp_login', $user_obj->user_login );
								wp_send_json_success(array("rcode" => 1, "msg" => "Registered successfully."));
							}
						}
						wp_send_json_success(array("rcode" => 2, "msg" => "Something went wrong! Please try again."));
					}
					else
					{
						wp_send_json_success(array("rcode" => 2, "msg" => "Something went wrong! Please try again."));
					}
				}
				else
				{
					wp_send_json_success(array("rcode" => 2, "msg" => "Email already registered."));
				}
			}
			else
			{
				wp_send_json_success(array("rcode" => 2, "msg" => "Please enter valid email."));
			}
		}
		else
		{
			wp_send_json_success(array("rcode" => 2, "msg" => "Please fill all fields."));
		}
	}
	wp_send_json_error();
}

add_action( 'wp_ajax_mhrsajax_get_available_rooms', 'mhrsajax_get_available_rooms_func' );
add_action( 'wp_ajax_nopriv_mhrsajax_get_available_rooms', 'mhrsajax_get_available_rooms_func' );

function mhrsajax_get_available_rooms_func(){
	$data = array( 'content' => 'unexpected error occurred, please try again' );
	$roomid = null;
	$adult_stay = null;
	$children_stay = null;
	$checkindate = null;
	$checkoutdate = null;
	
	if( isset($_REQUEST['roomid']) ){
		$roomid = trim( $_REQUEST['roomid'] );
	}
	if( isset($_REQUEST['adult_stay']) ){
		$adult_stay = trim( $_REQUEST['adult_stay'] );
	}
	if( isset($_REQUEST['children_stay']) ){
		$children_stay = trim( $_REQUEST['children_stay'] );
	}
	if( isset($_REQUEST['checkindate']) ){
		$checkindate = trim( $_REQUEST['checkindate'] );
	}
	if( isset($_REQUEST['checkoutdate']) ){
		$checkoutdate = trim( $_REQUEST['checkoutdate'] );
	}
	
	if(
		!is_null( $roomid ) &&
		!is_null( $adult_stay ) &&
		!is_null( $children_stay ) &&
		!is_null( $checkindate ) && 
		!is_null( $checkoutdate ) && 
		is_numeric( $roomid ) &&
		is_numeric( $adult_stay ) &&
		is_numeric( $children_stay )
	){
		
		$data = array(
			'roomid'			=>	$roomid,
			'adult_stay'		=>	$adult_stay,
			'children_stay'		=>	$children_stay,
			'checkindate'		=>	$checkindate,
			'checkoutdate'		=>	$checkoutdate,
			'required_rooms'	=>	0,
		);
		
		$hso = new MHRS_Hotel_Search();
		
		$return_arr = $hso->get_required_rooms( $roomid, $adult_stay, $children_stay, $checkindate, $checkoutdate );
		/*
		$return = array(
				'adult'						=>	false,
				'children'					=>	false,
				'adult_room_required'		=>	0,
				'children_room_required'	=>	0,
				'total_room_required'		=>	0,
			);*/
		
		if( $return_arr['adult'] && $return_arr['children'] ){
			$data['required_rooms'] = $return_arr['total_room_required'];
			wp_send_json_success( $data );
		} else {
			if( !$return_arr['adult'] ){
				$data = array( 'content' => 'unexpected error occurred, please try again' );
				$data['content'] = __( 'Sorry!! Described adult occupancy not available', 'mhrs' );
			} else if( !$return_arr['children'] ){
				$data = array( 'content' => 'unexpected error occurred, please try again' );
				$data['content'] = __( 'Sorry!! Described children occupancy not available', 'mhrs' );
			}
		}
		
		
	}
	
	wp_send_json_error( $data );
}