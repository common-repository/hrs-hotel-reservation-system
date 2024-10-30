jQuery(document).ready(function($){
	$.ajax({
		url : mhrs_test_js_obj.admin_ajax_url,
		type: 'post',
		data: {
			'item_id'	:	'it89',
			'action'	: 'mhrsaddtocart'
		}
	}).done(function(r){
		console.log(r);
	}).fail(function(jqXHR, textStatus){
		console.log(jqXHR);
		console.log(textStatus);
	});
});