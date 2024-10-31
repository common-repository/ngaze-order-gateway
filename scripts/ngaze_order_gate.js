jQuery(function($) {
    $(".poupclose").on("click",function(){
		$(".poupbgcontentwrap .poupbgcontent").html("");
		$(".poupbg").hide();
		$(".poupbgcontentwrap").hide();
	});
	$(".poupbg").on("click",function(){		
		$(".poupbgcontentwrap .poupbgcontent").html("");
		$(".poupbg").hide();
		$(".poupbgcontentwrap").hide();
	});
	$.fn.myfunction = function($nogclassname,$base_url) {		
		$popId=$nogclassname.split("_");
		$(".poupbgcontentwrap .poupbgcontent").html('<img src="'+$base_url+'assets/images/loading.gif" >');
		$(".poupbg").show();
		$(".poupbgcontentwrap").show();
       
          $.ajax({
            url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
            type: 'POST',
            timeout: 360000,
            data: {action: 'ngaze_show_order_form',
            dataid:$popId[1]},
            success: function (data, textStatus, xHr){               
              $(".poupbgcontentwrap .poupbgcontent").html(data);
            }
          });

		return this;
   }; 
   
   
   $(document).on("click", '.selectthisoption', function(e) {

	   
		e.preventDefault();	
		$thiform=$(this).data('formid');
		$orderurl=$(this).data('orderurl');
		$base_url=$(this).data('baseurl');
		
		$name =$('#'+$thiform).find('input[name="name"]').val();
		$email =$('#'+$thiform).find('input[name="email"]').val();
		$phone=$('#'+$thiform).find('input[name="phone"]').val();			
		$listval=$('#'+$thiform).find('input[name="listval"]').val();			
		$('#'+$thiform).find('input[name="orderurl"]').val($orderurl);
		
		if($name!='' & $email!='' & $phone!=''){		 
		$.ajax({
			url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
			type: 'POST',
			timeout: 360000,
			data: {action: 'ngaze_proceed_order_form',
			"name":$name,
			"email":$email,
			"phone":$phone,
			"listval":$listval,
			"orderurl":$orderurl
			},
			success: function (data, textStatus, xHr){               
			  $(".poupbgcontentwrap .poupbgcontent").html(data);
			}
		 });

		}
			
		else
			$('#'+$thiform).find('#errormessage').html('Fill all fields');	
	}) 
   
   
   
});