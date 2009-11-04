$(function (){
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$(".datepicker").datepicker();
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