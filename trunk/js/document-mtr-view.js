$(function (){
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$(".datepicker").datepicker();
	$("#submitBTN").click(function (){
		if(confirm("Continue?"))
		$.post("parser/Inv_stock.php",{type: "transfer",jsonForm: jsonForm(), branch: $("#branchId").text()}, function()
		{
			
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
			+"\"branch\":\""+$(this).find("#fromBranch").val()+"\"},"
		counter++;
	});
	jsonString = jsonString.substring(0, jsonString.length-1) + "}";
	return jsonString;
}