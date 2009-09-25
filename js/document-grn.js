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
	$("#poNo").autocomplete("parser/autocomplete/Purchase.php",{
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
							return format(item);
						}
					});
	$("#supplierAuto").autocomplete("parser/autocomplete/Supplier.php",{
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
							return format(item);
						}
					})
					.result(function(e, item) {
						$("#supplier").val(item.to)});
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
		if($(this).parent().next().children("input").val() == "")
			alert("Input Date first");
		else
			$(this).parent().html($("#whoami").val());
	});
	$("#submitBTN").click(function (){
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
		}
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
			cloneNew: cloneNew,
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
	var counterCell = $("<td></td>").text(++counter);
	var itemCode = $("<td></td>").html(itemCodeInner);
	var assess = $("<td></td>").html(
					$("<select class=\"assess\"></select>")
						.html("<option value=\"OK\">OK</option><option value=\"NG\">NG</option><option value=\"Q\">Q</option><option value=\"X\">X</option>"));
	var wholeRow = $("<tr class=\"jsonRow\"id=\"rowNo"+ counter +"\"></tr>").append(counterCell).append(itemCode).append(addRow).append(assess);
	$("#formContent tbody").append(wholeRow);
}

function getRunningNumber()
{
	$.post("parser/Good_receipt_note.php",{type:"count"},function (data){
		$("#run_num").val(data);
		$("#doc_num").val("GRN"+"/"+$("#branch_id").val()+"/"+$("#run_num").val()+"/"+Date.parseExact($(".datepicker").val(), "M/d/yyyy").toString("MM/yyyy"));
	});
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