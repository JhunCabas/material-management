var counter = 0;
$(function (){
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$(".datepicker").datepicker();
		addingRow();
	$("#addRowBTN").click(function (){
		addingRow();
	});
	$(".signHere").click(function (){
		$(this).parent().html($("#whoami").val());
	});
	$("#submitBTN").click(function (){
		var doc_number = $("#doc_num").val();
		var doc_date = $("#doc_date").val();
		var doc_type = $("#doc_type").val();
		var branch_id = $("#branch_id").val();
		var notes = $("#notes").val();
		var issuer = $("#issuer").text();
		var issuer_date = $("#issDate").val();
		var receiver = $("#receiver").text();
		var receiver_date = $("#recDate").val();
		if(confirm("Continue?"))
		$.post("parser/Production_issue.php",{
			type: "add",
			doc_number: doc_number,
			doc_date: doc_date,
			doc_type: doc_type,
			branch_id: branch_id,
			jsonForm: jsonForm(),
			notes: notes,
			issuer: issuer,
			issuer_date: issuer_date,
			receiver: receiver,
			receiver_date: receiver_date
			},function(data){
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
						window.location = tellDir() + "list-pi.php";
					 });
				 }
		});
	});
});

function addingRow()
{
	var addRow = "<td></td><td><input class=\"itemQuan\" size=\"5\" value=\"0\"/></td><td></td><td><input size=\"20\" class=\"remarks\"/></td>";
	var counterCell = $("<td></td>").text(++counter);
	var itemCode = $("<td></td>").html($("<input size=\"7\" class=\"itemCode\"></input>"));
	var wholeRow = $("<tr class=\"jsonRow\" id=\"rowNo"+ counter +"\"></tr>").append(counterCell).append(itemCode).append(addRow);
	$("#formContent tbody").append(wholeRow);
}

function jsonForm()
{
	var jsonString = "{";
		$(".jsonRow").each(function (){
		jsonString = jsonString + "\""+$(this).attr("id")+"\":{\"itemCode\":\""
					+$(this).find(".itemCode").val()+"\","
					+"\"itemQuan\":\""+$(this).find(".itemQuan").val()+"\","
					+"\"remarks\":\""+$(this).find(".remarks").val()+"\"},";
					});
		jsonString = jsonString.substring(0, jsonString.length-1) + "}";
	return jsonString;
}