var cloneNew = "no";
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
	$(".signHere").click(function (){
		if($(this).parent().next().children("input").val() == "")
			alert("Input Date first");
		else
			$(this).parent().html($("#whoami").val());
	});
	counter = $("#lastCount").val();
	$(".datepicker").datepicker();
	$("#addRowBTN").click(function (){
		addingRow();
	});
	$("#submitBTN").click(function (){
		var doc_number = $("#doc_num").text();
		var inspector = $("#inspector").text();
		var inspector_date = $("#insDate").val();
		var receiver = $("#receiver").text();
		var receiver_date = $("#recDate").val();
		if(inspector!="" && receiver!="")
		{
			$(".assess").each(function (){
				if($(this).val()!= "OK")
				{
					if(confirm("System will create a new GRN as there are items that do not meet the PO requirement."))
					{
						cloneNew = "yes";
					}
				}
			});
			$(".assessText").each(function (){
				if($(this).text()!= "OK" && cloneNew != "yes")
				{
					if(confirm("System will create a new GRN as there are items that do not meet the PO requirement."))
					{
						cloneNew = "yes";
					}
				}
			});
		}
		if(confirm("Continue?"))
		$.post("parser/Good_receipt_note.php",{
			type: "save",
			cloneNew: cloneNew,
			doc_number: doc_number,
			jsonForm: jsonForm(),
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
		if(jsonString == "}")
			jsonString = "";
	return jsonString;
}

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
	var addRow = "<td id=\"descAuto\"></td><td><input class=\"itemQuan\" size=\"5\" value=\"0\"/></td><td id=\"uomAuto\"></td><td><input size=\"20\" class=\"remarks\"/></td>";
	var counterCell = $("<td></td>").text(counter++);
	var itemCode = $("<td></td>").html(itemCodeInner);
	var assess = $("<td></td>").html(
					$("<select class=\"assess\"></select>")
						.html("<option value=\"OK\">OK</option><option value=\"NG\">NG</option><option value=\"Q\">Q</option><option value=\"X\">X</option>"));
	var wholeRow = $("<tr class=\"jsonRow\"id=\"rowNo"+ counter +"\"></tr>").append(counterCell).append(itemCode).append(addRow).append(assess);
	$("#formContent tbody").append(wholeRow);
}