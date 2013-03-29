	function send_feedback()
	{
		
		var name = $("#name").val();
		var mail = $("#mail").val();
		var mesaj = $("#mesaj").val();
		post_data={name:name, mail:mail, mesaj:mesaj};
		$.ajax({
			type: "POST",
			url: "/ajax/send_feedback",
			data: post_data,
			dataType:"json",
			success: function(response)
			{
				alert(response);				
			}
		});	// ajax son 
	}
