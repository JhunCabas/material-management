var counter = 0;
$(function (){
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
		var supplier = $("#supplier").val();
		var do_no = $("#doNo").val();
		var po_no = $("#poNo").val();
		var inspector = $("#inspector").text();
		var inspector_date = $("#insDate").val();
		var receiver = $("#receiver").text();
		var receiver_date = $("#recDate").val();
		$.post("parser/Good_receipt_note.php",{
			type: "add",
			doc_number: doc_number,
			doc_date: doc_date,
			doc_type: doc_type,
			supplier: supplier,
			do_no: do_no,
			po_no: po_no,
			inspector: inspector,
			inspector_date: inspector_date,
			receiver: receiver,
			receiver_date: receiver_date
			},function (data){
			
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
	var wholeRow = $("<tr id=\"rowNo"+ counter +"\"></tr>").append(counterCell).append(itemCode).append(addRow).append(assess);
	$("#formContent tbody").append(wholeRow);
}