$(function (){
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$("#cancelBTN").click(function (){
		$.post("parser/Purchase.php",{type:"cancelPO", key: $("#PrNumber").val()},function (data){
			if(data != "")
			 {
				 $("#dialogBox").html(data);
				 $("#dialogBox").dialog('option', 'title', 'Error');
				 $("#dialogBox").dialog('open');
			 }
			 else{
				$("#dialogBox").html("<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: 0.3em;\"/>Cancelled");
				 $("#dialogBox").dialog('option', 'title', 'Success');
				 $("#dialogBox").dialog('open');
				 $("#dialogBox").bind('dialogclose', function(event, ui) {
					history.go(-1);
				 });
			 }
		});
	});
});