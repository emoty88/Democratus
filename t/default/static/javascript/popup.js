(function($) {
		
	$.fn.democratus_popup = function(options) {

		var settings = 	{ }
		
		var options = $.extend(settings, options);
		
		$('.popup').fadeOut('slow', function(){
			$('.popup').remove();
		});
		
		$('#container').before('  <div class="popup">'
								+		'<div class="wrapper">'
								+			'<div class="close"></div>'
								+			'<div class="top"></div>'
								+			'<div class="container">'
								+				'<h3 class="title">'+ options.title +'</h3>'
								+				'<div class="title_line"></div>'
								+				 $(this).html()
								+			'<div class="clear"></div>'
								+			'</div>'
								+			'<div class="bottom"></div>'
								+		'</div>'
								+ '</div>');
						
		$('.popup').fadeIn('slow');
		
		$(document).keyup(function(e) {
			if (e.keyCode == 27)
			{
				$('.popup').fadeOut('slow', function(){
					$('.popup').remove();
				});
			}
		});
		
		
		$('.popup .close, .forgot_password_close').click(function(){
			$('.popup').fadeOut('slow', function(){
				$('.popup').remove();
			});
		});
		
	}
	
})(jQuery);


