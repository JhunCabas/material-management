$(function (){
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$(".datepicker").datepicker();
	$("#cancelBTN").click(function (){
		if(confirm("Continue?"))
		$.post("parser/Material_transfer.php",{
			type: "cancel",
			doc_num: $("#doc_num").val()
		},function (data)
		{
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
					history.back();
				 });
			}
		});
	});
	$("#submitBTN").click(function (){
		if(confirm("Continue?"))
		$.post("parser/Inv_stock.php",{
			type: "transfer",
			doc_num: $("#doc_num").val(),
			jsonForm: jsonForm(), 
			branch: $("#branchId").text()}, function(data)
		{
			if(data != "")
			{
			 	 $("#dialogBox").html(data);
				 $("#dialogBox").dialog('option', 'title', 'Error');
				 $("#dialogBox").dialog('open');
			}
			else{
			 	$("#dialogBox").html("<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: 0.3em;\"/>Added");
				 $("#dialogBox").dialog('option', 'title', 'Success');
				 $("#dialogBox").dialog('open');
				 $("#dialogBox").bind('dialogclose', function(event, ui) {
					history.back();
				 });
			}
		});
	});
	$(".transitBTN").click(function (){
		if(confirm("Continue?"))
		{
			$(this).parent().find(".loader").show();
			$(this).hide();
		$.post("parser/Material_transfer.php",{
			type: "transit",
			key: $(this).attr("key")}, function (data){
				if(data != "")
				{
				 	 $("#dialogBox").html(data);
					 $("#dialogBox").dialog('option', 'title', 'Error');
					 $("#dialogBox").dialog('open');
				}
				else{
				 	$("#dialogBox").html("<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: 0.3em;\"/>Quantity Moved");
					 $("#dialogBox").dialog('option', 'title', 'Success');
					 $("#dialogBox").dialog('open');
					$("#dialogBox").bind('dialogclose', function(event, ui) {
						history.go(0);
					 });
				}
		});
		}
	});
	$(".rejectBTN").click(function (){
		if(confirm("Continue?"))
		{
			$(this).parent().find(".loader").show();
			$(this).parent().find(".acceptBTN").hide();
			$(this).hide();
		$.post("parser/Material_transfer.php",{
			type: "reject",
			key: $(this).attr("key")}, function (data){
				if(data != "")
				{
				 	 $("#dialogBox").html(data);
					 $("#dialogBox").dialog('option', 'title', 'Error');
					 $("#dialogBox").dialog('open');
				}
				else{
				 	$("#dialogBox").html("<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: 0.3em;\"/>Quantity Rejected");
					 $("#dialogBox").dialog('option', 'title', 'Success');
					 $("#dialogBox").dialog('open');
					$("#dialogBox").bind('dialogclose', function(event, ui) {
						history.go(0);
					 });
				}
		});
		}
	});
	$(".acceptBTN").click(function (){
		if(confirm("Continue?"))
		{
			$(this).parent().find(".loader").show();
			$(this).parent().find(".rejectBTN").hide();
			$(this).hide();
		$.post("parser/Material_transfer.php",{
			type: "accept",
			key: $(this).attr("key")}, function (data){
				if(data != "")
				{
				 	 $("#dialogBox").html(data);
					 $("#dialogBox").dialog('option', 'title', 'Error');
					 $("#dialogBox").dialog('open');
				}
				else{
				 	$("#dialogBox").html("<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: 0.3em;\"/>Quantity Accepted");
					 $("#dialogBox").dialog('option', 'title', 'Success');
					 $("#dialogBox").dialog('open');
					$("#dialogBox").bind('dialogclose', function(event, ui) {
						history.go(0);
					 });
				}
		});
		}
	});
});

function jsonForm()
{
	var jsonString = "{";
	var counter = 1;
	$(".jsonRow").each(function (){
		jsonString = jsonString + "\""+counter+"\":{"
			+"\"itemCode\":\""+$(this).find(".itemCode").text()+"\","
			+"\"quantity\":"+$(this).find(".itemQuan").text()+","
			+"\"id\":"+$(this).find(".itemId").val()+","
			+"\"branch\":\""+$(this).find("#fromBranch").val()+"\"},"
		counter++;
	});
	jsonString = jsonString.substring(0, jsonString.length-1) + "}";
	return jsonString;
}