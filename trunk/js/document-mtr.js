var counter = 0;
function format(entry) {
	return entry.name;
}
function formatInvItem(entry){
	return entry.name +"  ("+ entry.desc +")";
}
$(function (){
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$(".datepicker").datepicker();
	$(".datepicker").datepicker('setDate', Date.today() );
	$(".datepicker").blur(function (){ getRunningNumber(); });
	$("#branch_id").change(function (){ getRunningNumber(); });
	$("#doc_type").change(function (){ getRunningNumber(); });
	$("#doc_num").attr("readonly","true");
	getRunningNumber();
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
		var branch_to = $("#branch_to").val();
		var branch_from = $("#branch_from").val();
		var requester = $("#requester").text();
		var requester_date = $("#reqDate").val();
		var approver = $("#approver").text();
		var approver_date = $("#appDate").val();
		if(confirm("Continue?"))
		$.post("parser/Material_transfer.php",{
			type: "add",
			doc_number: doc_number,
			doc_date: doc_date,
			doc_type: doc_type,
			status: "pending",
			branch_from: branch_from,
			branch_to: branch_to,
			jsonForm: jsonForm(),
			requester: requester,
			requester_date: requester_date,
			approver: approver,
			approver_date: approver_date
			}, function (data){
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

function addingRow()
{
	var itemCodeInner = $("<input size=\"7\" class=\"itemCode\"></input>")
						.autocomplete("parser/autocomplete/Inv_item.php",{
											width: 300,
											parse: function(data) {
												return $.map(eval(data), function(row) {
													return {
														data: row,
														value: row.name,
														result: row.name
													}
												});
											},
											formatItem: function(item) {
												return formatInvItem(item);
											}
										})
										.result(function(e, item) {
											$(this).parent().parent().find("#descAuto").text(item.desc);
											$(this).parent().parent().find("#uomAuto").text(item.uom);
										});
	var addRow = "<td id=\"descAuto\"></td><td><input class=\"itemQuan\" size=\"5\" value=\"0\"/></td><td id=\"uomAuto\"></td><td><input class=\"remarks\"/></td>";
	var counterCell = $("<td></td>").text(++counter);
	var itemCode = $("<td></td>").html(itemCodeInner);
	var wholeRow = $("<tr id=\"rowNo"+ counter +"\" class=\"jsonRow\"></tr>").append(counterCell).append(itemCode).append(addRow);
	$("#formContent tbody").append(wholeRow);
}

function getRunningNumber()
{
	$.post("parser/Material_transfer.php",{type:"count"},function (data){
		$("#run_num").val(data);
		$("#doc_num").val("MTF"+"/"+$("#hiddenBranch").val()+"/"+$("#run_num").val()+"/"+Date.parseExact($(".datepicker").val(), "M/d/yyyy").toString("MM/yyyy"));
	});
}

function jsonForm()
{
	var jsonString = "{";
		$(".jsonRow").each(function (){
		jsonString = jsonString + "\""+$(this).attr("id")+"\":{\"itemCode\":\""
					+$(this).find(".itemCode").val()+"\","
					+"\"itemQuan\":\""+$(this).find(".itemQuan").val()+"\","
					+"\"remark\":\""+$(this).find(".remarks").val()+"\"},";
					});
		jsonString = jsonString.substring(0, jsonString.length-1) + "}";
	return jsonString;
}