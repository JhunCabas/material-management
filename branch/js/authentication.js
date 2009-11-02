$(function (){
	$("#submitBTN").click(function (){
		$.post("parser/Authentication.php", {type: "login", 
			username: $("#username").val(), password: $("#password").val()}, 
			function(data){
				if(data == 'true')
				{
					window.location.replace("inventory.php");
				}else{
					$("#statusbar").text("Login Failed");
				}
			});
	});
});