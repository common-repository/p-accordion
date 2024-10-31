(function($) {
  $(document).ready(function() {
    $('.p_accordion_color-field').wpColorPicker();  });
})(jQuery);


function submit_form(element){
	 var xaction = element.name;
				 jQuery.ajax({
					type:"post",
					url:ajaxurl,
					data:"action=" + xaction,
					success:function(data){
						  window.location.assign(window.location.href);	
						  //jQuery("p-accordion-wrap").html(data);
                          p_accordion_write(data);					  
						  }
				 });
			  }
						  

	function p_accordion_write(data) {

    jQuery(".p-accordion-wrap").html(data); }

