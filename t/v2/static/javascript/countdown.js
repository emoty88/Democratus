/*! 
* jQuery Countdown Timer 1.2 Plugin
* Copyright 2011 Tom Ellis http://www.webmuse.co.uk
* Licensed under MIT License
* See http://www.webmuse.co.uk/license/
*/
(function($) {
   
	$.fn.countdown = function( options ) {  
	
		var defaults = {
				date: new Date(),
				updateTime: 1000,
				htmlTemplate: "%{d} <span class=\"cd-time\">gün</span> %{h} <span class=\"cd-time\">saat</span> %{m} <span class=\"cd-time\">dakika</span> %{s} <span class=\"cd-time\">saniye</span> kaldı",
				minus: false,
				onChange: null,
				onComplete: null,
				leadingZero: false
			},
			opts = {},
			rDate = /(%\{d\}|%\{h\}|%\{m\}|%\{s\})/g,
			rDays = /%\{d\}/,
			rHours = /%\{h\}/,
			rMins = /%\{m\}/,
			rSecs = /%\{s\}/,
			complete = false,
			template,
			floor = Math.floor,
			onChange = null,
			onComplete = null;
		   
		$.extend( opts, defaults, options );
		
		template = opts.htmlTemplate;
		
		return this.each(function() {
		
			var $this = $(this),
				timer,
				TodaysDate = new Date(),
				CountdownDate = new Date( opts.date ),
				msPerDay = 864E5, //24 * 60 * 60 * 1000
				timeLeft = CountdownDate.getTime() - TodaysDate.getTime(),
				e_daysLeft = timeLeft / msPerDay,
				daysLeft = floor(e_daysLeft),
				e_hrsLeft = (e_daysLeft - daysLeft)*24, //Gets remainder and * 24
				hrsLeft = floor(e_hrsLeft),
				minsLeft = floor((e_hrsLeft - hrsLeft)*60),					
				e_minsleft = (e_hrsLeft - hrsLeft)*60, //Gets remainder and * 60
				secLeft = floor((e_minsleft - minsLeft)*60),
				time = "";

			if( opts.onChange){
				$this.bind("change", opts.onChange);
			}
			
			if( opts.onComplete ){
				$this.bind("complete", opts.onComplete);
			}
			
			if ( opts.leadingZero ) {
			
				if ( daysLeft < 10) {
					daysLeft = "0" + daysLeft;
				}
				
				if ( hrsLeft < 10) {
					hrsLeft = "0" + hrsLeft;
				}
				
				if ( minsLeft < 10) {
					minsLeft = "0" + minsLeft;
				}
				
				if ( secLeft < 10) {
					secLeft = "0" + secLeft;
				}
			}

			//Set initial time
			if ( TodaysDate <= CountdownDate || opts.minus ) {
				time = template.replace( rDays, daysLeft ).replace( rHours, hrsLeft ).replace( rMins, minsLeft ).replace( rSecs, secLeft );
			} else {
				time = template.replace( rDate, "00");
				complete = true;
			}
							
			timer = window.setInterval(function(){
				
				TodaysDate = new Date(),
				CountdownDate = new Date( opts.date ),
				msPerDay = 864E5, //24 * 60 * 60 * 1000
				timeLeft = CountdownDate.getTime() - TodaysDate.getTime(),
				e_daysLeft = timeLeft / msPerDay,
				daysLeft = floor(e_daysLeft),
				e_hrsLeft = (e_daysLeft - daysLeft)*24, //Gets remainder and * 24
				hrsLeft = floor(e_hrsLeft),
				minsLeft = floor((e_hrsLeft - hrsLeft)*60),					
				e_minsleft = (e_hrsLeft - hrsLeft)*60, //Gets remainder and * 60
				secLeft = floor((e_minsleft - minsLeft)*60),
				time = "";
				
				if ( opts.leadingZero ) {
				
					if ( daysLeft < 10) {
						daysLeft = "0" + daysLeft;
					}
					
					if ( hrsLeft < 10) {
						hrsLeft = "0" + hrsLeft;
					}
					
					if ( minsLeft < 10) {
						minsLeft = "0" + minsLeft;
					}
					
					if ( secLeft < 10) {
						secLeft = "0" + secLeft;
					}
				}

				if ( TodaysDate <= CountdownDate || opts.minus ) {
					time = template.replace( rDays, daysLeft ).replace( rHours, hrsLeft ).replace( rMins, minsLeft ).replace( rSecs, secLeft );
				} else {
					time = template.replace( rDate, "00");
					complete = true;
				}
								
				$this.html( time );
				
				$this.trigger('change', [timer] );
			    
				if ( complete ){

					$this.trigger('complete');
					clearInterval( timer );
				}       		
			
			}, opts.updateTime);


		    $this.html( time );
			
			if ( complete ){
				$this.trigger('complete');
				clearInterval( timer );
			}
			
		});
	};
       
})(jQuery);