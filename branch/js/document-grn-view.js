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
	counter = 0;
	$(".datepicker").datepicker();
	//Get form body from JSON
	$.ajax({type:"post",url:"parser/Good_receipt_note.php",data:{type:"json",key:$("#grnNo").val()},dataType:"json", success: function(data){
		$.each(data, function(i,item){
			$.get("parser/Inv_item.php",{type:"uom",key:item.item_id},function (data){
				fillingRow(item.item_id,item.description,0,data);
			});
		});
	}})
	$("#addRowBTN").click(function (){
		addingRow();
	});
	$("#submitBTN").click(function (){
		var doc_number = $("#doc_num").text();
		var inspector = $("#inspectorID").val();
		var inspector_date = $("#insDate").val();
			$(".assess").each(function (){
				if($(this).val()!= "OK")
				{
					if(confirm("System will create a new GRN as there are items that do not meet the PO requirement."))
					{
						cloneNew = "yes";
					}
				}
			});
		if(confirm("Continue?"))
		$.post("parser/Good_receipt_note.php",{
			type: "save",
			cloneNew: cloneNew,
			doc_number: doc_number,
			jsonForm: jsonForm(),
			inspector: inspector,
			inspector_date: inspector_date
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
					+"\"itemDesc\":\""+$(this).find(".itemDesc").val()+"\","
					+"\"remarks\":\""+$(this).find(".remarks").val()+"\"},";
					});
		jsonString = jsonString.substring(0, jsonString.length-1) + "}";
		if(jsonString == "}")
			jsonString = "";
	return jsonString;
}

function fillingRow(itemCode,itemDesc,itemQuantity,itemUOM)
{
	var itemCodeInner = $("<input></input>").attr("readonly","true").attr("size",7).addClass("itemCode").val(itemCode);
	var addRow = "<td id=\"descAuto\">"+itemDesc+"</td><td><input class=\"itemQuan\" size=\"5\" value=\""+itemQuantity+"\"/></td><td id=\"uomAuto\">"+itemUOM+"</td><td><input size=\"20\" class=\"remarks\"/></td>";
	var descCell = $("<td></td>").html($("<input></input>").addClass("itemDesc").attr("size",40).val(itemDesc));
	var quanCell = $("<td></td>").html($("<input></input>").addClass("itemQuan").attr("size",5).val(itemQuantity));
	var uomCell = $("<td></td>").attr("id","uomAuto").text(itemUOM);
	var remarkCell = $("<td></td>").html($("<input></input>").addClass("remarks").attr("size",20));
	var counterCell = $("<td></td>").text(++counter);
	var itemCode = $("<td></td>").html(itemCodeInner);
	var assess = $("<td></td>").html(
					$("<select class=\"assess\"></select>")
						.html("<option value=\"OK\">OK</option><option value=\"NG\">NG</option><option value=\"Q\">Q</option><option value=\"X\">X</option>"));
	var wholeRow = $("<tr class=\"jsonRow\"id=\"rowNo"+ counter +"\"></tr>")
					.append(counterCell)
					.append(itemCode)
					//.append(addRow)
					.append(descCell)
					.append(quanCell)
					.append(uomCell)
					.append(remarkCell)
					.append(assess);
	$("#formContent tbody").append(wholeRow);
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