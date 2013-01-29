/*
	@author	Caner Türkmen <caner.turkmen@democratus.com>
	@date	24.10.2012
*/
var profileID 		= 0;
var json_voices		= "";
var firstVoice		= 0;
var lastVoiceID		= 0;
var wallmoreAction	= 0;
var onlyProfile		= 0;
var notOpen			= 0;
var linkCount		= 0;
var paths			= 0;
var plugin			= 0;
var currentTab		= 0;
var voiceDControl 	= 0;
var globalRandID	= 0;

jQuery(document).ready(function ($) {
    
    var last = 0;
	//$(".fineUploader").each(function (){
	//	init_fineUploader(this);
	//});	

	$(".pImageUpload").each(function (){
		init_userfUploader(this);
	});
        $(".myImageUpload").each(function (){
                init_profileImageUploader(this);
        });
	$(".fineUploader").live("click", function (){
		globalRandID=$(this).attr("data-randID");
	});

	$(".unfollow").live("mouseover",function () {
		$(this).addClass("btn-danger");
		$(this).removeClass("btn-info");
		$(this).html($(this).attr("data-unfText"));
	});
	$(".unfollow").live("mouseout", function () {
		$(this).addClass("btn-info");
		$(this).removeClass("btn-danger");
		$(this).html($(this).attr("data-fText"));
	});
        
	var loc = location.href;
	var tabName=loc.split("#");
	if(tabName[1]!=undefined && tabName[1].length>0){
		$('a[href="#tab-'+tabName[1]+'"]').tab("show");
		currentTab=tabName[1];
	} 

	$("#message_send").live("click", function(){
		send_message(friendPerma, $("#new_message").val(), "messageSendBtn")
	});
	
	$('#tab-container a').click(function (e) {
		e.preventDefault();
    	$(this).tab('show');
    	location.href="#"+$(this).attr("rel");
   	});

   	switch(plugin)
   	{
   		case "parliament": parliament_page(); break;
   		case "message" : message_page(); break;
   		case "message-dialog" : message_dialog_page(); break;
   		case "voice" : voice_page(); break;
   		case "hashTag" : hashtag_page();break;
   		case "search" : search_page();break;
   	}  	
	
	if(plugin=="profile" || plugin=="home" || plugin=="hashTag")
	{
		if(plugin == "hashTag")
		{
			hashTag = profilePerma;
		}
		else 
		{
			hashTag = 0;
		}
		$(window).scroll(function(){
		if(wallmoreAction==0 && $(window).scrollTop() == $(document).height() - $(window).height()){
		    	get_wall(profileID,lastVoiceID,onlyProfile, hashTag);
	        }
		});
		get_wall(profileID,lastVoiceID,onlyProfile, hashTag);
	}
	
	get_noticeCount();
	// mobile_menu
	if( $('[data-benim-olayim="hedef_goster_gizle"]').length )
	{
		$('[data-benim-olayim="hedef_goster_gizle"]').each(function(){
		
		});
	}

	if( $('select.mobil_menu').length ) {
		$('select.mobil_menu').change(function(){
			if ($(this).val()!='') {
				window.location.href = $(this).val();
			}
		});
	}
	
	var detaylar=	{	html	:true, 
						title	:"Bildirimler",
						content	:'<div class="popoverContent-noticeIcon">Yükleniyor</div>',
						placement:"bottom"
					};
	$("#noticeIcon").popover(detaylar);
	$("#noticeIcon").live("click", function(){
		$(".popoverContent-noticeIcon").load("/notice/mini");
	}); 
	
	// CarouFredSel
    if ($().carouFredSel) {
   		
        $("#kirmizi-bilesen").carouFredSel({
        	items: 1,
            circular: true,
            infinite: true,
            auto    : false,
            height	: 245,
            pagination  : "#kirmizi-bilesen-pag"
        });
        $("#turkiye-meclisi").carouFredSel({
        	items: 1,
            circular: true,
            infinite: true,
            auto    : false,
            pagination  : "#turkiye-meclisi-pag"
        });
        /*
        $(".img-slider-content").carouFredSel({
        	items: 6,
            circular: false,
			infinite: false,
			auto 	: false,
            prev	: {	
				button	: "#slider-prev"
			},
			next	: { 
				button	: "#slider-next"
			},
        });
		*/
    }

    // Tab
    $('#ses-tabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	})
	$('#ses-tabs a:first').tab('show');

    // Add New Icon
    if ($('.yeni').length) {
    	$('.yeni').append('<div class="yeni-ikon"></div>');
    }

	// scroll top
	if( $('.scroll_top').length ) {
		$('.scroll_top').click(function(){
			scroll_to( 0 );
			return false;
		});
	}

	// scroll bottom
	function scroll_bottom(){
		var target = $('body').height() - $(window).height();
		scroll_to(target);
		return false;
	}
	// scroll to target
	function scroll_to( target ){
		$('html, body').animate({scrollTop:target }, 'slow');
		return false;
	}
	
	// validateEmail
	function validateEmail(email) {
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		if( !emailReg.test( email ) ) {
			return false;
		} else {
			return true;
		}
	}

	// e-posta adresi
	if( $('.eposta_adresi').length ) {
		$('.eposta_adresi').change(function(){
			var gecerli = validateEmail( $(this).val() );
			if( ! gecerli ) {
				alert( $(this).val() + ' geçersiz bir e-posta adresidir.' );
				$(this).val( '' );
				$(this).change();
			}
		});
	}

	// karakter sayacı
	if( $( '.karakter_sayaci_tutucu .karakter_sayaci' ).length ) {
		$( '.karakter_sayaci_tutucu .karakter_sayaci' ).each(function(){
			var $ks = $(this);
			
			var limit = 200;
			if( is_valid_data_attr( $ks.attr('data-limit') ) ) { limit = parseInt( $ks.attr('data-limit') ); }
				
			var $kst = $ks.closest('.karakter_sayaci_tutucu');
			var $ksa = $kst.find('.karakteri_sayilacak_alan');
			if( $ksa.is('textarea') || $ksa.is('input[type="text"]') || $ksa.is('input[type="password"]') ) {
				var karakter_sayaci_guncelle = function( ksa, ks, limit ){
					var kalan = limit - $(ksa).val().length;
					$(ks).text(kalan);
					if( kalan < 0 )
						$(ks).addClass('limit_asimi');
					else
						$(ks).removeClass('limit_asimi');
				};
				$ksa.change(function(){ karakter_sayaci_guncelle($ksa, $ks, limit); });
				$ksa.keyup(function(){ karakter_sayaci_guncelle($ksa, $ks, limit); });
				karakter_sayaci_guncelle($ksa, $ks, limit);
			}
		});
	}
	/* yenisi Ajax a uygun olarak hazırlandı 
	// ac kapa
	if( $( '[data-tetikleyici="ac-kapa"]' ).length ) {
		$( '[data-tetikleyici="ac-kapa"]' ).each(function(){
			var $tetikleyici = $(this);
			if( is_valid_data_attr($tetikleyici.attr('data-hedef')) ) {
				var $hedef = $( $tetikleyici.attr('data-hedef') );
				if( $hedef.length ) {
					if( is_valid_data_attr($tetikleyici.attr('data-vazgec-metni')) ) {
						var tetikleyici_metni = $(this).html();
						var vazgec_metni = $tetikleyici.attr('data-vazgec-metni');
						var $ikon = $(this).find('i');
						if( $ikon.length ) $ikon_klon = $ikon.clone(false);
					}
					$tetikleyici.click( function(olay){
						olay.preventDefault();
						$hedef.stop();
						if( $hedef.hasClass('hedef_gorunur') ){
							$hedef.removeClass('hedef_gorunur');
							$hedef.slideUp(400);
							if( is_valid_data_attr($tetikleyici.attr('data-vazgec-metni')) ) { $tetikleyici.html( tetikleyici_metni ); }
						} else {
							$hedef.addClass('hedef_gorunur');
							$hedef.slideDown(400);
							if( is_valid_data_attr($tetikleyici.attr('data-vazgec-metni')) ) {
								if( $ikon_klon.length ) {
									$tetikleyici.html( ' '+vazgec_metni );
									$ikon_klon.clone(false).prependTo($tetikleyici);
								} else $tetikleyici.html( vazgec_metni );
							}
						}
					} );
				}
			}
		});
	}
	*/
	// panel tetikleyici
	if( $( '[data-tetikleyici="panel"]' ).length ) {
		$( '[data-tetikleyici="panel"]' ).each(function(){
			var $tetikleyici = $(this);
			if( is_valid_data_attr($tetikleyici.attr('data-panel')) && is_valid_data_attr($tetikleyici.attr('data-panel-tutucu')) ) {
				var $panel_tutucu = $tetikleyici.closest( $tetikleyici.attr('data-panel-tutucu') );
				var $panel = $panel_tutucu.find( $tetikleyici.attr('data-panel') );
				if( $panel.length && $panel_tutucu.length ) {
					if( is_valid_data_attr($tetikleyici.attr('data-vazgec-metni')) ) {
						var tetikleyici_metni = $(this).text();
						var vazgec_metni = $tetikleyici.attr('data-vazgec-metni');
					}

					$tetikleyici.click( function(olay){
						olay.preventDefault();
						$panel.stop();
						if( $panel.hasClass('panel_gorunur') ){
							$panel.removeClass('panel_gorunur');
							$panel.fadeOut(300);
							if( is_valid_data_attr($tetikleyici.attr('data-vazgec-metni')) ) { $tetikleyici.text( tetikleyici_metni ); }
						} else {
							$panel.addClass('panel_gorunur');
							$panel.fadeIn(300);
							if( is_valid_data_attr($tetikleyici.attr('data-vazgec-metni')) ) { $tetikleyici.text( vazgec_metni ); }
						}
					} );
				}
			}
		});
	}

	// süslü dosya yükle düğmesi
	if( $( '.suslu_dosya_yukle_dugmesi input[type="file"]' ).length ) {
		$( '.suslu_dosya_yukle_dugmesi input[type="file"]' ).each(function(){
			var $dosya = $(this);
			var $tutucu = $dosya.closest( '.suslu_dosya_yukle_dugmesi' );
			var $goronor_metin = $tutucu.find('input[type="text"]');
			if( $goronor_metin.length ) {
				$dosya.change( function(){
					$goronor_metin.val( $dosya.val() );
				} );
			}
		});
	}

	if( $('.akilli_select ul a').length ) {
		$('.akilli_select ul a').click(function(olay){
			olay.preventDefault();
			var $secenek = $(this);
			var $tutucu = $secenek.closest('.akilli_select');
			var $input = $tutucu.find('input[type="text"]');
			if( $input.length ) {
				$input.val( $secenek.text() );
			}
		});
	}

	// Auto Complete
	var list = [
		{
			"img":"2",
			"label":"Ali Emre Çakmakoğlu",
			"username":"aliemreeee"
		},
		{
			"img":"3",
			"label":"Oğuz Güç",
			"username":"oguzguc"
		}
	];
	
    $( "#arama_kutusu" ).autocomplete({
        minLength: 2,
        source: "/ajax/get_followersAutoComplite",
        focus: function( event, ui ) {
            $( "#arama_kutusu" ).val( ui.item.label );
            return false;
        },
        select: function( event, ui ) {
            $( "#arama_kutusu" ).val( ui.item.label );
            //$( "#project-id" ).val( ui.item.value );
            //$( "#project-description" ).html( ui.item.desc );
            //$( "#project-icon" ).attr( "src", "images/" + ui.item.icon );

            return false;
        }
    })
    .data( "autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .data( "item.autocomplete", item )
            .append( '<img class="search-r-img" src="'+item.pImage+'" /><a href="/'+item.pPerma+'" class="search-r-name">' + item.pName + '</a><a href="/'+item.pPerma+'" class="search-r-username">@' + item.pPerma + '</a>'+'<div class="clearfix"></div>' )
            .appendTo( ul );
    };

   
	/* onclick eventinde fonksiyon çağırıldı
	$("#share_voice").live("click", function (){
		share_voice(this);
	});
	*/
	$(".meclis_oy").live("click", function(){
		var agendaID=$(this).attr("data-agendaID");
		var choice=$(this).attr("data-choice");
		set_meclis_oy(agendaID, choice);
	});
        
        $('#findMyFriend').keypress(function (){
            var input = $(this);
            if(input.val().length >=0){
                get_myFollowing(input.val());
            }
        });
});
	// is_valid_data_attr
	function set_meclis_oy(agendaID, choice)
	{
		post_data={agendaID:agendaID, vote:choice};
		$.ajax({
			type: "POST",
			url: "/ajax/agendavote",
			data: post_data,
			dataType:"json",
			success: function(response)
			{
				if(response.status == "success")
				{
					get_meclis_istatistik(agendaID);
					$(".btn-agenda-"+agendaID).removeClass("btn-danger");
					$(".btn-agenda-"+agendaID+"-"+choice).addClass("btn-danger");
				}
				else
				{
					
				}
				
			}
		});	// ajax son 
	}
	function get_meclis_istatistik(agendaID)
	{
		post_data={agendaID:agendaID};
		$.ajax({
			type: "POST",
			url: "/ajax/get_agenda_statistic",
			data: post_data,
			dataType:"json",
			success: function(response)
			{
				if(response.status == "success")
				{
					$("#meclis-bottom-box-"+agendaID).html("");
					var oranlar={olumlu:response.statistic.olumlu, olumsuz:response.statistic.olumsuz,fikiryok:response.statistic.fikiryok};
					//console.log(response.statistic[2]);
					$("#meclis-istatistik-tmpl").tmpl(oranlar).appendTo("#meclis-bottom-box-"+agendaID);
				}
				else
				{
					
				}
				
			}
		});	// ajax son 
	}
	function is_valid_data_attr( data ) {
		return ( typeof data !== 'undefined' && data !== false );
	}

	// bos_degilse
	function bos_degilse( data ) {
		return ( typeof data !== 'undefined' && data !== false && data != '' );
	}
	
	function ac_kapa_manual(dom)
	{
		var $tetikleyici 	= $(dom);
		var voiceID			= $tetikleyici.attr("data-voiceID");
		var $hedef			= $($tetikleyici.attr("data-hedef"));
		var metin			= $tetikleyici.attr("data-metni");
		var vazgecMetin		= $tetikleyici.attr("data-vazgec-metni");
		
		//console.log($hedef.hasClass("open"));
		if($hedef.hasClass("open"))
		{
			$hedef.removeClass("open");
			$hedef.slideUp(400);
			$tetikleyici.children("span").text(metin);
		}
		else
		{
			
			if(!$hedef.hasClass("loadData"))
			{
				$("#loadingbar-tmpl").tmpl().appendTo($hedef);
				get_voiceImage(voiceID, $hedef);
			}
			$hedef.slideDown(400);
			$hedef.addClass("open");
			$tetikleyici.children("span").text(vazgecMetin);
		}
		
	}
	function get_voiceImage(ID, $hedef)
	{
		var post_data = {voiceID:ID};
		$.ajax({
			type: "POST",
			url: "/ajax/get_voiceImage",
			data: post_data,
			dataType:"json",
			success: function(response)
			{
				if(response.status == "success")
				{
					$hedef.addClass("loadData");
					$hedef.children("img").attr("src",response.reeImagePath);
					$hedef.children(".loading_bar").remove();
				}
				else
				{
					////$("#duvaryazisi-tmpl").tmpl(response.voices).appendTo("#orta_alan_container");
					$hedef.addClass("loadData");
					$hedef.children(".loading_bar").html(response.errorMessage);
					
				}
				
			}
		});		
	}
	//share
	function share_voice(shareBtn)
	{
		var $shareBtn	= $(shareBtn);
		var randID 		= $shareBtn.attr("data-randID");
		var voice_text	= $("#replyTextArea_"+randID).val();
		var replying	= $("#replying_"+randID).val();
		var initem		= $("#initem_"+randID).val();  // initemler  düzenlenmeli
		var initemName	= $("#initem-name_"+randID).val(); //intiremler düzenlenmeli
		
		var post_data	= {voice_text:voice_text, initem:initem, initemName:initemName, replying:replying};
		//console.log(post_data);
                
                $('#voice-share-progress').show();
                $(shareBtn).attr('disabled',true);
                
		$.ajax({
			type: "POST",
			url: "/ajax/set_share_voice",
			data: post_data,
			dataType:"json",
			success: function(response)
			{
				if(response.status == "success")
				{
					
						if(response.voice.replyID>0)
						{
							if(randID == "qe")
							{
								
								$("#voice-reply-tmpl").tmpl(response.voice).prependTo(".replyAreaFix");
								
							}
							else
							{
								$("#voice-reply-tmpl").tmpl(response.voice).prependTo("#voiceReplyArea_"+randID); // bu alanda  diğer yazılan larda  yüklenicek.
							}
							$("#replyTextArea_"+randID).val("+voice ");
							$("#voiceReplyArea_"+randID).slideDown();
						}
						else
						{
							if(plugin != "parliament")
							{
								$("#duvaryazisi-tmpl").tmpl(response.voice).prependTo("#orta_alan_container");	
							}
							$("#replyTextArea_"+randID).val("");
						}	
					
					$("#initem_"+randID).val(0);
					$("#initem-name_"+randID).val(0);
				}
				else
				{
					alert(response.message);
					
				}
                                
                                $('#voice-share-progress').hide();
                                $(shareBtn).removeAttr('disabled');
			}
			
		});	
	}
	function get_noticeCount()
	{
		var post_data	= {ID:0};
		$.ajax({
			type: "POST",
			url: "/ajax/get_noticeCount",
			data: post_data,
			dataType:"json",
			success: function(response)
			{
				if(response==null)
				{
					$("#noticeCount").hide();
				}
				else
				{
					$("#noticeCount").show().text(response);
				}
				//console.log(response);				
			}
		});	
	}
	function get_archiveSearch(keyword,start)
	{
		if(get_archiveSearch.arguments.length<2)
		{
			start=0;	
		}
		if(get_archiveSearch.arguments.length<1)
		{
			return false;
		}
		var post_data = {keyword:keyword, start:start};
		$(".daha_fazla_duvar_yazisi").remove();
		$("#loadingbar-tmpl").tmpl().appendTo("#kisiler-container");
		
		$.ajax({
			type: "POST",
			url: "/ajax/get_oldAgenda",
			data: post_data,
			dataType:"json",
			success: function(response)
			{
				if(response.status == "success")
				{
					$(".loading_bar").remove();
	        		$("#parliament-oldAgenda-tmpl").tmpl(response.olAgendas).appendTo("#arsivler-container");
	        		init_oldAgenda();
					
					
				}
				else
				{
					//$("#duvaryazisi-tmpl").tmpl(response.voices).appendTo("#orta_alan_container");
				}
				
			}
		});		
	}
	function get_userSearch(keyword,start)
	{
		if(get_userSearch.arguments.length<2)
		{
			start=0;	
		}
		if(get_userSearch.arguments.length<1)
		{
			return false;
		}
		var post_data = {keyword:keyword, start:start};
		$(".daha_fazla_duvar_yazisi").remove();
		$("#loadingbar-tmpl").tmpl().appendTo("#kisiler-container");
		$.ajax({
			type: "POST",
			url: "/ajax/get_userSearch",
			data: post_data,
			dataType:"json",
			success: function(response)
			{
				if(response.status == "success")
				{
					$("#social-friendList-tmpl").tmpl(response.users).appendTo("#kisiler-container");
					//console.log(response.users);
					$(".loading_bar").remove();
					//$("#dahafazlases-tmpl").tmpl().appendTo("#kisiler-container");
				}
				else
				{
					//$("#duvaryazisi-tmpl").tmpl(response.voices).appendTo("#orta_alan_container");
				}
				
			}
		});		
	}
	function get_voiceSearch(keyword,start)
	{
		if(get_voiceSearch.arguments.length<2)
		{
			start=0;	
		}
		if(get_voiceSearch.arguments.length<1)
		{
			return false;
		}

		var post_data = {keyword:keyword, start:start};
		$(".daha_fazla_duvar_yazisi").remove();
		$("#loadingbar-tmpl").tmpl().appendTo("#sesler-container");
		$.ajax({
			type: "POST",
			url: "/ajax/get_voiceSearch",
			data: post_data,
			dataType:"json",
			success: function(response)
			{
				if(response.status == "success")
				{
					if(response.voices.length>0)
					{
						firstVoice	= response.voices[0].ID;
						lastVoiceID	= response.voices[response.voices.length-1].ID;;
						$("#duvaryazisi-tmpl").tmpl(response.voices).appendTo("#sesler-container");
						$(".loading_bar").remove();
					}
					else
					{
						$("#voiceBulunamadı-tmpl").tmpl().appendTo("#sesler-container");
					}
				}
				else
				{
					//$("#duvaryazisi-tmpl").tmpl(response.voices).appendTo("#orta_alan_container");
				}
				
			}
		});		
	}
	function get_wall(profileID, start, onlyProfile, hashTag, keyword){
		wallmoreAction=1;
		
		if(get_wall.arguments.length<5)
		{
			keyword="";
		}
		if(get_wall.arguments.length<4)
		{
			hashTag=0;
		}
		if(get_wall.arguments.length<3)
		{
			onlyProfile=0;
		}
		if(get_wall.arguments.length<2)
		{
			start=0;	
		}
		if(get_wall.arguments.length<1)
		{
			profileID=0;
		}
		var post_data = {profileID:profileID, start:start, onlyProfile:onlyProfile, hashTag:hashTag, keyword:keyword};
		$(".daha_fazla_duvar_yazisi").remove();
		$("#loadingbar-tmpl").tmpl().appendTo("#orta_alan_container");
		$.ajax({
			type: "POST",
			url: "/ajax/get_wall",
			data: post_data,
			dataType:"json",
			success: function(response)
			{
				if(response.status == "success")
				{
					firstVoice	= response.voices[0].ID;
					lastVoiceID	= response.voices[response.voices.length-1].ID;;
					$("#duvaryazisi-tmpl").tmpl(response.voices).appendTo("#orta_alan_container");
					$(".loading_bar").remove();
					$("#dahafazlases-tmpl").tmpl().appendTo("#orta_alan_container");
					wallmoreAction=0;
					get_iconText(response.voices);
					init_voice_details();
				}
				else
				{
					//$("#duvaryazisi-tmpl").tmpl(response.voices).appendTo("#orta_alan_container");
				}
				
			}
		});		
	} // get_wall
	function new_messageToggle()
	{
		$("#direkt_mesaj_formu_tutucu").slideToggle("400",function (){
			if($(this).css("display")=="block")
			{
				$("input#alici").focus();
			}
		});
	}
	function redi(ID){
	    $.post("/ajax/redi", {ID: ID}, function(data){ 
	        if(data.status == 'success'){
	            $("#paylas_btn_"+ID+" span").html(" Paylaştın");
	        }
	    },'json');    
	}
	
	function send_newMesage()
	{
		var aliciPerma = $("#aliciPerma").val();
		var msgText = $("#yeni_yazi").val();
		send_message(aliciPerma, msgText, "newMessage")
	}
	
	function send_message(friendPerma, msgText, callF)
	{
		post_data={friendPerma:friendPerma, msgText:msgText};
		$.ajax({
			type: "POST",
			url: "/ajax/send_message",
			data: post_data,
			dataType:"json",
			success: function(response)
			{
				if(response.status == "success")
				{
					if(callF=="newMessage")
					{
						location.href="/message/dialog/"+friendPerma;
					}
					else if(callF=="messageSendBtn"){
						get_dialog_details('');
						$("#new_message").val("");
					}
					
				}
				else
				{
					alert("Sorun oldu tekrar deneyeniniz");// hata uyarılarını düzelt
				}
				
			}
		});	// ajax son 
	} 
	
	function voice_like(voiceID, type)
	{
		$.post("/ajax/voice_like", {voiceID: voiceID, likeType:type}, function(data){ 
	        if(data.status == 'success'){
	        	if(type==1)
	        	{
	        		$("#taktir_btn_"+voiceID+" span").html(" Taktir Ettin");
	        		$("#saygi_btn_"+voiceID+" span").html(" Saygı Duy");
	        	}
	        	else
	        	{
	        		$("#taktir_btn_"+voiceID+" span").html(" Taktir Et");
	        		$("#saygi_btn_"+voiceID+" span").html(" Saygı Duydun");
	        	}
	            
	        }
	    },'json');  
	}
	function voice_delete(voiceID)
	{
		$.post("/ajax/voice_delete", {voiceID: voiceID}, function(data){ 
	        if(data.status == 'success'){
	        	$("#duvar_yazisi-content-"+voiceID).slideUp("400", function (){
	        		$("#duvar_yazisi-content-"+voiceID).remove();
	        	});
	        	
	        }
	    },'json');  
	}
	function get_iconText(voices)
	{
		var IDs	= new Array();
		$.each(voices, function(index, value) { 
				IDs[index] = value.ID; 
		});
		$.post("/ajax/get_voiceIconText", {voiceIDs: IDs}, function(response){ 
	        set_iconText(response);
	    },'json');  
	}
	function set_iconText(dataS)
	{
		$.each(dataS, function(index, value) { 
			if(value.redi==true)
			{
				$("#paylas_btn_"+value.ID+" span").html(" Paylaştın");
			}
			if(value.likeType)
			{
				if(value.likeType=="like1"){
					$("#taktir_btn_"+value.ID+" span").html(" Taktir Ettin");
				}
				else
				{
	        		$("#saygi_btn_"+value.ID+" span").html(" Saygı Duydun");
				}
			}
			
		});
	}
	function voiceDetail(voice)
	{
                var $tetik = $('a[data-voiceid='+$(voice).attr("data-voiceID")+']');
                ac_kapa_manual($tetik);
		if(plugin=="voice")
		{	
			if(voiceDControl==1)
				return 0;
			else
				voiceDControl=1;
		}
		if(notOpen==1)
		{
			notOpen=0;
		}
		else
		{
			var vID = $(voice).attr("data-voiceID");
			var randNum = $(voice).attr("data-randNum");
			var randID	= vID+"-"+randNum;
			if($("#voice_detailArea_"+randID).attr("data-isOpen")=="0")
			{
				$("#voice_detailArea_"+randID).attr("data-isOpen","1");
				$("#voiceSliceTop_"+randID).slideDown();
				$("#voiceSliceBottom_"+randID).slideDown();
				$("#voice_detailArea_"+randID).slideDown();
				get_voiceReply(vID, randNum);
				get_parentVoice(vID, randNum);
				
			}
			else
			{
				$("#voice_detailArea_"+randID).attr("data-isOpen","0");
				$("#voiceSliceTop_"+randID).slideUp();
				$("#voiceSliceBottom_"+randID).slideUp();
				$("#voice_detailArea_"+randID).slideUp();
				$("#voiceReplyArea_"+vID+"-"+randNum).slideUp();
				$("#voiceTopArea_"+vID+"-"+randNum).slideUp();
			}
			notOpen=0;
		}

		
	}
	
	function get_voiceReply(vID, randNum)
	{
		//$("#loadingbar-tmpl").appendTo("#voiceReplyArea_"+vID+"-"+randNum);
		//$("#voiceReplyArea_"+vID+"-"+randNum).show();
		if($("#voiceReplyArea_"+vID+"-"+randNum).attr("data-isload")==1)
		{
			$("#voiceReplyArea_"+vID+"-"+randNum).slideDown();
			return ;
		}
		$.post("/ajax/get_voiceReply", {voiceID: vID}, function(response){ 
	        if(response.status=="success")
	        {
	        	if(response.voice_count>0)
	        	{
	        		$("#voice-reply-tmpl").tmpl(response.voices).appendTo("#voiceReplyArea_"+vID+"-"+randNum);
	        		$("#voiceReplyArea_"+vID+"-"+randNum).show();
	        		$("#voiceReplyArea_"+vID+"-"+randNum).attr("data-isload", "1");
	        	}
	        }
	    },'json');  
	}
	
	function get_parentVoice(vID, randNum)
	{
		//$("#loadingbar-tmpl").appendTo("#voiceReplyArea_"+vID+"-"+randNum);
		//$("#voiceReplyArea_"+vID+"-"+randNum).show();
		if($("#voiceTopArea_"+vID+"-"+randNum).attr("data-replyID")==0)
		{
			return ;
		}
		if($("#voiceTopArea_"+vID+"-"+randNum).attr("data-isload")==1)
		{
			$("#voiceTopArea_"+vID+"-"+randNum).slideDown();
			return ;
		}
		
		replyID = $("#voiceTopArea_"+vID+"-"+randNum).attr("data-replyID");
		$.post("/ajax/get_parentVoice", {replyID: replyID}, function(response){ 
	        if(response.status=="success")
	        {
	        	
	        	$("#voice-reply-tmpl").tmpl(response.voice).appendTo("#voiceTopArea_"+vID+"-"+randNum);
	        	$("#voiceTopArea_"+vID+"-"+randNum).show();
	        	$("#voiceTopArea_"+vID+"-"+randNum).attr("data-isload", "1");
	        	
	        }
	    },'json');  
	}
	
	function replyTextFocus(voiceID, randNum)
	{
		var randID= voiceID+"-"+randNum;
		if(!$("#replyArea_"+randID).hasClass("replyAreaActive"))
		{
			$("#replyArea_"+randID).addClass("replyAreaActive");
		}	
                
                replyKarakterSay(voiceID,randNum);
	}
        
        function replyKarakterSay(voiceID,randNum){
            var textarea = $('#replyTextArea_'+voiceID+'-'+randNum);
            
            var sayici = $('#replyArea_'+voiceID+'-'+randNum+'Number');
            
            var say = function(textarea,sayici){
                var kalan = 200-textarea.val().length;
                sayici.html(kalan);
                if( kalan < 0 )
                        $(sayici).addClass('limit_asimi');
                else
                        $(sayici).removeClass('limit_asimi');
            };
            //textarea.keypress(function(){say(textarea,sayici)});
            textarea.keyup(function(){say(textarea,sayici)});
            textarea.change(function(){say(textarea,sayici)});
            
        }
	function replyTextBlur(voiceID, randNum)
	{
		var randID= voiceID+"-"+randNum;
		
		if($("#replyTextArea_"+randID).val()=="+voice ")
		{
			$("#replyArea_"+randID).removeClass("replyAreaActive");
		}	
	}
	function get_agendas()
	{
		$("#loadingbar-tmpl").appendTo("#referandum-container");
		$.post("/ajax/get_agendasObj", {voiceIDs: "1"}, function(response){ 
	        if(response.status=="success")
	        {
	        	$(".loading_bar").remove();
	        	agendeSortList = agendaSortNew(response.agendas);
	        	$("#parliament-agenda-tmpl").tmpl(agendeSortList).appendTo("#referandum-container");
	        }
	    },'json');  
	}
	function agendaSortNew(list)
	{
		var ilk = new Array();
		var son = new Array();
		$.each(list, function(key, value){
			if(value.myVote == null)
			{
				ilk.push(value);
			}
			else
			{
				son.push(value);
			}
		});

		returnArr = ilk.concat(son);

		return returnArr;
	}
	function get_deputyList()
	{
		$("#loadingbar-tmpl").appendTo("#deputy-container");
		$.post("/ajax/get_deputyList", {voiceIDs: "1"}, function(response){ 
	        if(response.status=="success")
	        {
	        	$(".loading_bar").remove();
	        	$("#parliament-deputys-tmpl").tmpl(response.deputys).appendTo("#deputy-container");
	        }
	    },'json');  
	}
	function get_oldAgenda()
	{
		$("#loadingbar-tmpl").appendTo("#eskiReferandum-container");
		$.post("/ajax/get_oldAgenda", {voiceIDs: "1"}, function(response){ 
	        if(response.status=="success")
	        {
	        	$(".loading_bar").remove();
	        	$("#parliament-oldAgenda-tmpl").tmpl(response.olAgendas).appendTo("#eskiReferandum-container");
	        	init_oldAgenda();
	        }
	    },'json');  
	}
	function get_myDeputy()
	{
		$("#vekil_listesi_ul").html("");
		$.post("/ajax/get_myDeputy", {voiceIDs: "1"}, function(response){ 
	        if(response.status=="success")
	        {
	        	$("#parliament-deputyItem-tmpl").tmpl(response.myDeputy).appendTo("#vekil_listesi_ul");
	        	get_kalanOyCount();
	        }
	    },'json');  
	}
	function get_myFollowing(search)
	{
		$.post("/ajax/get_myFollowing", {limit: 15,keyword:search}, function(response){ 
	        if(response.status=="success")
	        {
	        	//$(".loading_bar").remove();
                        
                        $("#arkadas_listesi_ul").html("");
	        	$("#parliament-friendItem-tmpl").tmpl(response.myFollowing).prependTo("#arkadas_listesi_ul");
                        //$("#duvaryazisi-tmpl").tmpl(response.voice).prependTo("#orta_alan_container");
	        }
	    },'json');  
	}
	function get_proposal()
	{
		$("#proposalArea").html("");
		$.post("/ajax/get_proposal", {limit: 15}, function(response){ 
	        if(response.status=="success")
	        {
	        	$("#parliament-proposal-tmpl").tmpl(response.proposals).appendTo("#proposalArea");
	        }
	    },'json');  
	}
	function vekilOyu(deputyID)
	{
		location.hash = "#vekil_adaylarim"
		location.hash = "vekilsecimleri";
		$.post("/ajax/set_vekilOyu", {deputyID: deputyID}, function(response){ 
	        if(response.status=="success")
	        {
	        	//$(".loading_bar").remove();
	        	get_myDeputy();
	        	//$("#parliament-friendItem-tmpl").tmpl(response.myFollowing).appendTo("#arkadas_listesi_ul");
	        }
	        else
	        {
	        	alert(response.errorMsg);// uyarının düzelmesi lazım html popup
	        }
	    },'json');  
	}
	function set_proposal()
	{
		var proposalTxt = $("#tasari_textarea").val();
		if(proposalTxt.length>200)
		{
			return false;
		}
		$.post("/ajax/set_proposal", {proposalTxt: proposalTxt}, function(response){ 
			if(response.status == "success")
			{
				get_proposal();
				$("#tasari_textarea").val("");
                                count++;
                                check_proposal_count();
			}else{
                                $('#message').html(response.message);
                                $('#message').show();
                        }
	    },'json'); 
	}
        function check_proposal_count(){
            if(count>2){
                $('#yeni_yazi_yaz').html('Bir günde en fazla 3 tasarı gönderebilirsiniz.');
            }
        }
	function get_kalanOyCount()
	{
		$.post("/ajax/get_kalanOyCount", {deputyID: 0}, function(response){ 
			$("#kalanOySayisi").html(response);
	    },'json');
	}
	function get_messageDialog(){
		$.post("/ajax/get_messageDialog", {deputyID: 0}, function(response){
			if(response.status=="success")
	        {
	        	$("#message-dialog-tmpl").tmpl(response.dialogs).appendTo("#dialog_list_ul");
	        }
	        else
	        {
	        	
	        }
	    },'json');
	}
	function get_dialog_details(before,x)
	{
                        $('#onceki_mesajlar').niceScroll();
                        
                        if(before.length<1)
                                $("#onceki_mesajlar").html("");
                                
                        $("#loadingbar-tmpl").tmpl().appendTo("#onceki_mesajlar");
                        $.post("/ajax/get_messageDialogDetail", {fID: fID,before:before}, function(response){
                            if(response.status=="success")
                            {
                                if(response.last=='x')
                                       $('#before-messages').hide();
                                last = response.last;
                                //alert(last);
                                $('#before-messages').remove();
                                $("#message-dialog-detail-tmpl").tmpl(response.dialogs).prependTo("#onceki_mesajlar");
                                if(response.dialogs.length>=10)
                                    $('#onceki_mesajlar').prepend('<a href="javascript:;" style="text-align: center; display: block" id="before-messages" onclick="javascript:before();">Önceki Mesajlar</a>');
                                
                                
                                if(x==1)
                                    $('#onceki_mesajlar').scrollTo('#before-messages');
                                else{
                                    $('#onceki_mesajlar').append('<div id="focus"></div>');
                                    $('#onceki_mesajlar').scrollTo('#focus');
                                }
                                $(".loading_bar").hide();
                                //$.prependTo(content)
                            }
                            else
                            {

                            }
                        },'json');
	}
        
        function before(){
            if(last.length>0)
                get_dialog_details(last,1);
            else
                $('#before-messages').hide();
        }
	//sm functions 
	function twitter_friendFind()
	{
		$.post("/ajax/twitter_get_friends", {deputyID: 0}, function(response){ 
			if(response.status == "success")
			{
	        	$("#social-friendList-tmpl").tmpl(response.friendList).appendTo("#socialListArea");
	        	$(".loading_bar").remove();
				
			}
	    },'json');
	}
	
	function twitter_open_LoginWindow(url)
	{
		//console.log(url);
		var signinWin;
        signinWinTW = window.open(url, "SignIn", "width=800,height=500,toolbar=0,scrollbars=0,status=0,resizable=0,location=0,menuBar=0");
        signinWinTW.focus();
        signinWinTW.onbeforeunload = function(){ twitter_friendFind(); };
        return false;
	}
	function twitter_friendFind()
	{
		$("#socialListArea").html("");
		$("#loadingbar-tmpl").tmpl().appendTo("#socialListArea");
		$.post("/ajax/twitter_get_friendSuggestion", {deputyID: 0}, function(response){ 
			if(response.status == "success")
			{
	        	$("#social-friendList-tmpl").tmpl(response.friendList).appendTo("#socialListArea");
	        	$(".loading_bar").remove();
				
			}
	    },'json');
	}
	
	function facebook_friendFind(fbID)
	{
		$("#socialListArea").html("");
		$("#loadingbar-tmpl").tmpl().appendTo("#socialListArea");
		$.post("/ajax/facebook_get_friendSuggestion", {deputyID: 0}, function(response){ 
			if(response.status == "success")
			{
	        	$("#social-friendList-tmpl").tmpl(response.friendList).appendTo("#socialListArea");
	        	$(".loading_bar").remove();
				
			}
	    },'json');
	}
	function facebook_get_loginUrl(perm)
	{
		
	}
	function facebook_open_LoginWindow(url)
	{
		//console.log(url);
		var signinWin;
        signinWin = window.open(url, "SignIn", "width=500,height=300,toolbar=0,scrollbars=0,status=0,resizable=0,location=0,menuBar=0");
        signinWin.focus();
        signinWin.onbeforeunload = function(){ facebook_friendFind(); };
        return false;
	}
	//page functions
	function message_page()
	{
		$( "#alici" ).autocomplete({
	    	source: "/ajax/get_followersAutoComplite",
	        minLength: 2,
	        focus: function( event, ui ) {
	            $( this ).val( ui.item.pName + "  @"+ui.item.pPerma  );
	            return false;
	        },
	        select: function( event, ui ) {
	            $( this ).val( ui.item.pName + "  @"+ui.item.pPerma  );
	            $("#aliciPerma").val(ui.item.pPerma);
	            return false;
	        }
	    }).data( "autocomplete" )._renderItem = function( ul, item ) {
	        return $( "<li>" )
	            .data( "item.autocomplete", item )
	            .append( '<img class="search-r-img" src="'+item.pImage+'" /><a class="search-r-name">' + item.pName + '</a><a class="search-r-username">@' + item.pPerma + '</a>'+'<div class="clearfix"></div>' )
	            .appendTo( ul );
	    };
		get_messageDialog();
	}
	
	function message_dialog_page(){
		get_dialog_details('');
		// ajax ile yeni mesajları kontrol et gelince  göster;
		var myScroll;
		function loaded() {
			myScroll = new iScroll('wrapper');
		}
	}
	
	function parliament_page(){
                check_proposal_count();
		get_agendas();
		get_deputyList();
		get_oldAgenda();
		get_myDeputy();
		get_myFollowing('');
		get_proposal();
	}
	function voice_page()
	{
		$("#duvaryazisi-tmpl").tmpl(voiceObj).appendTo("#orta_alan_container");
	}
	function hashtag_page()
	{
		//get_wall(profileID,lastVoiceID,onlyProfile,profilePerma);//sadece hashtagin yazzdıkları gelio arama yapıcak gale getir 
		get_imageGalery(profileID);
	}
	function search_page()
	{
		get_voiceSearch(keyword);
		get_userSearch(keyword);
		get_archiveSearch(keyword);
	}
	function get_imageGalery(profileID)
	{
		$.post("/ajax/get_imageGalery", {profileID: profileID}, function(response){ 
			if(response.status == "success")
			{
				//console.log(response);
				$("#hashtag-image-tmpl").tmpl(response.images).appendTo("#imageGaleryArea");
				//$(".unfollow-" + profileID).toggle();
	        	//$(".follow-" + profileID).toggle();	
			}
	    },'json');
	}
	function follow(profileID)
	{
		$.post("/ajax/follow", {profileID: profileID}, function(response){ 
			if(response.status == "success")
			{
				$(".unfollow-" + profileID).toggle();
	        	$(".follow-" + profileID).toggle();	
			}
	    },'json');
	}

	function init_quick_editor()
	{
		if(plugin=="profile")
		{
			 $("#replyTextArea_qe").val("@"+profilePerma+" ");
		}
		if(plugin=="voice")
		{
			$("#replyTextArea_qe").val("+voice ");
			$("#replying_qe").val(voiceID);
		}
		if(plugin=="hashTag")
		{
			$("#replyTextArea_qe").val("#"+profilePerma+" ");
		}
	}
	function init_voice_details()
	{
		$(".fineUploader").each(function (){
			init_fineUploader(this);
		});	
		
	}
	
	function init_userfUploader(dom)
	{
		$fub = $(dom);
		uploadData = $fub.attr("data-upload");
		var uploader = new qq.FineUploaderBasic({
			button: $fub[0],
			request: {
				params : {uploadType:uploadData},
				endpoint: '/ajax/upload_image'
			},
			validation: {
				allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
				sizeLimit: 4194304 // 200 kB = 200 * 1024 bytes
			},
			callbacks: {
				onComplete: function(id, fileName, responseJSON) {
					if(responseJSON.success==true)
					{
						set_coverImage(responseJSON);
					}
				}
			},
			debug:false
		});
	}
        
        
        function init_profileImageUploader(dom)
	{
		$fub = $(dom);
		uploadData = $fub.attr("data-upload");
		var uploader = new qq.FineUploaderBasic({
			button: $fub[0],
			request: {
				params : {uploadType:uploadData},
				endpoint: '/ajax/upload_image'
			},
			validation: {
				allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
				sizeLimit: 4194304 // 200 kB = 200 * 1024 bytes
			},
			callbacks: {
				onComplete: function(id, fileName, responseJSON) {
					if(responseJSON.success==true)
					{
						set_profileImage(responseJSON);
					}
				}
			},
			debug:false
		});
	}
        
        function set_profileImage(imageData)
	{
           // console.log(imageData);
            
		$.post("/ajax/set_profileImage", {imageData: imageData}, function(response){ 
                    console.log(response);
			if(response.status == "success")
			{
				$("#profileImage").attr("src",response.imageUrl);
                                $(".uye_profili img").attr('src',response.imageUrl);
			}
	    },'json');
	}
	
	function set_coverImage(imageData)
	{
		$.post("/ajax/set_coverImage", {imageData: imageData}, function(response){ 
			if(response.status == "success")
			{
				$("#profileCoverImage").attr("src",response.imageUrl);
			}
	    },'json');
	}
	/**
	 * 
 	 * @param {Object} dom fine uploader a dönüşecek dom elementi
 	 * 
	 */
	function init_fineUploader(dom)
	{
		$fub = $(dom);
		var uploader = new qq.FineUploaderBasic({
			button: $fub[0],
			request: {
				endpoint: '/ajax/upload_image'
			},
			validation: {
				allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
				sizeLimit: 4194304 // 200 kB = 200 * 1024 bytes
			},
			callbacks: {
				onComplete: function(id, fileName, responseJSON) {
					if(responseJSON.success==true)
					{
						$("#initem_"+globalRandID).val("1");
						$("#initem-name_"+globalRandID).val(responseJSON.fileName);
					}
				}
			},
			debug:false
		});
	}
	
	function init_oldAgenda()
	{
		 // Hover on percentage
	    $(".yuzde").css({'cursor':'pointer'}).popover({
	    	html: true,
	    	placement: 'left',
	    	trigger: 'hover',
	    	content: function () {
	    		return $(this).find('div').html();
	    	}
	    });
	}

        function set_proposal_vote(id,value){
            
            
            $.post("/ajax/set_proposal_vote", {id: id, value: value}, function(response){ 
                if(response.status=='success'){
                    $('#v-'+id+'-'+value).addClass('btn-danger');
                    $('#v-'+id+'-'+(value^1)).removeClass('btn-danger');
                    
                }else{
                    alert('hata');
                }
            },'json');
        }
        
        function populardiToPopular(ID,button){
            data="populardiID="+ID;
            $.post('/ajax/set_popularToProposal', data,  
                function(response){ 
                    if(response.status=="success"){
                        $(button).html("Tasarı olarak kaydedildi.");
                        $(button).removeAttr('onclick');
                    }else{
                        $(button).html("En fazla 3 tasarı gönderbilirsiniz.");
                    }
                },'json');
        }

	
	function gotoSearch()
	{
		var word=$('#arama_kutusu').val();
		if(word.search("#")>=0)
		{
			word=word.replace("#","");location.href='/search/'+word+"#sesler";
		}
		else
		{
			location.href='/search/'+word+"#kisiler";
		}
	}
	function get_who2follow()
	{
		$.post("/ajax/get_who2follow", {data: 0}, function(response){ 
			if(response.status == "success")
			{
				$("#gaget-w2f-tmpl").tmpl(response.persons).appendTo("#gaget_who2follow");
				//$("#profileCoverImage").attr("src",response.imageUrl);
			}
	    },'json');
	}
    function set_proposal_vote(id,value){
      
        
        $.post("/ajax/set_proposal_vote", {id: id, value: value}, function(response){ 
            if(response.status=='success'){
                $('#v-'+id+'-'+value).addClass('btn-danger');
                $('#v-'+id+'-'+(value^1)).removeClass('btn-danger');
                
            }else{
                alert('hata');
            }
        },'json');
    }
    function proposal_delete(pID){
        $.post("/ajax/proposal_delete", {pID: pID}, function(data){ 
            if(data.status == 'success'){
                    $("#duvar_yazisi-content-"+pID).slideUp("400", function (){
                            $("#duvar_yazisi-content-"+pID).remove();
                    });

            }
        },'json');  
    }
    function send_activateMail(){
        $.post('/ajax/send_activationMail', null, function(response){ 
			if(response.status=='success')
                            hide_alertBox("alert");
	},'json');
    }
        
