var counter = 0;
$(function (){
	$(".datepicker").datepicker();
		addingRow();
	$("#addRowBTN").click(function (){
		addingRow();
	});
	$("#submitBTN").click(function (){
		var doc_number = $("#doc_num").val();
		var doc_date = $("#doc_date").val();
		var doc_type = $("#doc_type").val();
		var supplier_1 = $("#sup1").val();
		var supplier_1_contact = $("#con1").val();
		var supplier_1_tel = $("#tel1").val();
		var supplier_2 = $("#sup2").val();
		var supplier_2_contact = $("#con2").val();
		var supplier_2_tel = $("#tel2").val();
		var supplier_3 = $("#sup3").val();
		var supplier_3_contact = $("#con3").val();
		var supplier_3_tel = $("#tel3").val();
		var requester = $("#requester").text();
		var requester_date = $("#reqDate").val();
		$.post("parser/Purchase.php",{
			type: "add",
			doc_number: doc_number,
			doc_type: doc_type,
			supplier_1: supplier_1,
			supplier_1_contact: supplier_1_contact,
			supplier_1_tel: supplier_1_tel,
			supplier_2: supplier_2,
			supplier_2_contact: supplier_2_contact,
			supplier_2_tel: supplier_2_tel,
			supplier_3: supplier_3,
			supplier_3_contact: supplier_3_contact,
			supplier_3_tel: supplier_3_tel,
			requester: requester,
			requester_date: requester_date
			}, function (data){
				
		});9
	});
});

function addingRow()
{
	var addRow = "<td></td><td><input class=\"itemQuan\" size=\"5\" value=\"0\"/></td><td></td><td><input class=\"itemUnitP\"/></td><td><input class=\"itemExtP\"/></td>";
	var counterCell = $("<td></td>").text(++counter);
	var itemCode = $("<td></td>").html($("<input size=\"7\" class=\"itemCode\"></input>"));
	var wholeRow = $("<tr id=\"rowNo"+ counter +"\"></tr>").append(counterCell).append(itemCode).append(addRow);
	$("#formContent tbody").append(wholeRow);
}

function jsonForm()
{
	var jsonString = "[";
		
	jsonString = jsonString + "]";
	return jsonString;
}