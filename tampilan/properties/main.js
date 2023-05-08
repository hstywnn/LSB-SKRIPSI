function original() {
	// $('#progress').show();
	if ($('#form-gambar').prop('files')[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			base64 = e.target.result;
			// console.log(base64);
			$('#ori-image').attr('src', base64);
		};
		reader.readAsDataURL($('#form-gambar').prop('files')[0]);
	}
}

function sendEncode(fd){
	
	$.ajax({
		url: '../encode.php',
		type: 'POST',
		data: fd,
		contentType: false,
		processData: false,
		success: function(response){
			if(response != 0){
				console.log("success broh");
				// console.log(response);
				var resp = JSON.parse(response);
				// var cek = str.includes("data:image/png;base64,");
				// console.log(document.getElementById('image_download'));
				console.log(resp);
				console.log("hehehe");
                document.getElementById('cover_image').src="../"+resp.stego_file;
                document.getElementById('image_download').href="../"+resp.stego_file;
                document.getElementById('image_comparation').innerText = "PSNR: "+resp.psnr+", MSE: "+resp.mse;	
				
				
				
			}else{
				alert('file not uploaded');
			}
		},
	});
}

function sendDecode(fd){
	
	$.ajax({
		url: '../decode.php',
		type: 'POST',
		data: fd,
		contentType: false,
		processData: false,
		success: function(response){
			if(response != 0){
				console.log("success broh");
				// console.log(response);
				var str = String(response);
				// var cek = str.includes("data:image/png;base64,");
				console.log(str);
                document.getElementById('hidden_object').innerText = response;	
				
				
				
			}else{
				alert('file not uploaded');
			}
		},
	});
}

$(document).ready(function(){
    $("#submit_encode").click(function (){
        // console.log("hahahaha");
		var fd = new FormData();
		var files = $('#form-gambar')[0].files[0];
        var pin = $('#form-PIN')[0].value;
        var pesan = $('#form-pesan')[0].value;
		// console.log(files);
        // console.log(pin);
		fd.append('form_gambar',files);
        fd.append('form_PIN',pin);
        fd.append('form_pesan',pesan);
        console.log(fd);
		
		sendEncode(fd);
	});

    $("#submit_decode").click(function (){
        // console.log("hahahaha");
		var fd = new FormData();
		var files = $('#form-gambar')[0].files[0];
        var pin = $('#form-PIN')[0].value;
		// console.log(files);
        // console.log(pin);
		fd.append('form_gambar',files);
        fd.append('form_PIN',pin);
        console.log(fd);
		
		sendDecode(fd);
	});

});