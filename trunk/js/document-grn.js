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
		var supplier = $("#supplier").val();
		var do_no = $("#doNo").val();
		var po_no = $("#poNo").val();
		var inspector = $("#inspector").text();
		var inspector_date = $("#insDate").val();
		var receiver = $("#receiver").text();
		var receiver_date = $("#recDate").val();
		if(confirm("Continue?"))
		$.post("parser/Good_receipt_note.php",{
			type: "add",
			doc_number: doc_number,
			doc_date: doc_date,
			doc_type: doc_type,
			branch_id: branch_id,
			jsonForm: jsonForm(),
			supplier: supplier,
			do_no: do_no,
			po_no: po_no,
			inspector: inspector,
			inspector_date: inspector_date,
			receiver: receiver,
			receiver_date: receiver_date
			},function (data){
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
						window.location = tellDir() + "list-grn.php";
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
	var assess = $("<td></td>").html(
					$("<select class=\"assess\"></select>")
						.html("<option value=\"OK\">OK</option><option value=\"NG\">NG</option><option value=\"Q\">Q</option><option value=\"X\">X</option>"));
	var wholeRow = $("<tr class=\"jsonRow\"id=\"rowNo"+ counter +"\"></tr>").append(counterCell).append(itemCode).append(addRow).append(assess);
	$("#formContent tbody").append(wholeRow);
}

function jsonForm()
{
	var jsonString = "{";
		$(".jsonRow").each(function (){
		jsonString = jsonString + "\""+$(this).attr("id")+"\":{\"itemCode\":\""
					+$(this).find(".itemCode").val()+"\","
					+"\"itemQuan\":\""+$(this).find(".itemQuan").val()+"\","
					+"\"assess\":\""+$(this).find(".assess").val()+"\","
					+"\"remarks\":\""+$(this).find(".remarks").val()+"\"},";
					});
		jsonString = jsonString.substring(0, jsonString.length-1) + "}";
	return jsonString;
}