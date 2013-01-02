/**
 * @author caner türkmen
 */

$().ready(function() {
	$fub = $('#fine-uploader-btn');
	$messages = $('#fine-uploader-msg');
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
					$("#initem").val("1");
					$("#initem-name").val(responseJSON.fileName);
					console.log($("#initem").val());
					console.log($("#initem-name").val());
				}
			}
		},
		debug:false
	});

});
 	function createUploader() {
		var uploader = new qq.FineUploader({
			element: document.getElementById('bootstrapped-fine-uploader'),
			request: {
				endpoint: 'server/handleUploads'
			},
			text: {
				uploadButton: '<i id="bootstrapped-fine-uploader" class="atolye15-ikon-gorsel atolye15-ikon-24"></i>'
			},
			template: 	'{uploadButtonText}' ,
			classes: {
				success: 'alert alert-success',
				fail: 'alert alert-error'
			}
		});
	}
	//window.onload = createUploader;

