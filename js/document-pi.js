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
		var notes = $("#notes").val();
		var issuer = $("#issuer").text();
		var issuer_date = $("#issDate").val();
		var receiver = $("#receiver").text();
		var receiver_date = $("#recDate").val();
		$.post("parser/Production_issue.php",{
			type: "add",
			doc_number: doc_number,
			doc_date: doc_date,
			doc_type: doc_type,
			notes: notes,
			issuer: issuer,
			issuer_date: issuer_date,
			receiver: receiver,
			receiver_date: receiver_date
			},function(data){
				
		});
	});
});

function addingRow()
{
	var addRow = "<td></td><td><input class=\"itemQuan\" size=\"5\" value=\"0\"/></td><td></td><td><input size=\"20\" class=\"remarks\"/></td>";
	var counterCell = $("<td></td>").text(++counter);
	var itemCode = $("<td></td>").html($("<input size=\"7\" class=\"itemCode\"></input>"));
	var wholeRow = $("<tr id=\"rowNo"+ counter +"\"></tr>").append(counterCell).append(itemCode).append(addRow);
	$("#formContent tbody").append(wholeRow);
}