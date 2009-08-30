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
		$(this).parent().html($("#whoami").val());
	});
	$(".itemExtP").each(function (){
		$(this).bind("blur",function (){
			var total = 0;
			$(".itemExtP").each(function (){
				total = total + parseFloat($(this).val());
			});
			$("#purchaseTotal").text(formatAsMoney(total));
		});
	});
	$(".datepicker").datepicker();
	if($("#docStatus").val() == "unapproved")
	{
		counter = $("#lastCounter").val();
		editableBody();
	}
	$("#submitBTN").click(function (){
		var total = $("#purchaseTotal").text();
		var approver1 = $("#approver1").text();
		var approver1_date = $("#app1Date").val();
		if(confirm("Continue?"))
		$.post("parser/Purchase.php",{
			type: "edit",
			key: $("#docNum").text(),
			jsonForm: jsonForm(),
			total: total,
			approver_1: approver1,
			approver_1_date: approver1_date
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
						window.location = tellDir() + "list-pr.php";
					 });
				 }
			});
	});
});

function editableBody(){
	$("#addRowBTN").click(function (){
		addingRow();
	});
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
											$(this).parent().parent().removeClass("emptyRow");
											$(this).parent().parent().find("#descAuto").text(item.desc);
											$(this).parent().parent().find("#uomAuto").text(item.uom);
										});

	var addRow = "<td id=\"descAuto\"></td><td><input class=\"itemQuan\" size=\"5\" value=\"0\"/></td><td id=\"uomAuto\"></td><td><input class=\"itemUnitP\"/></td>";
	var extendedInner = $("<input></input>").addClass("itemExtP")
						.bind("blur",function (){
							var total = 0;
							$(".itemExtP").each(function (){
								total = total + parseFloat($(this).val());
							});
							$("#purchaseTotal").text(formatAsMoney(total));
						});
	var extendedCell = $("<td></td>").html(extendedInner);
	var counterCell = $("<td></td>").html(counter  + "<input type=\"hidden\" class=\"detailId\" value=\"0\"></input>");
	var itemCode = $("<td></td>").html(itemCodeInner);
	var wholeRow = $("<tr id=\"rowNo"+ counter++ +"\" class=\"jsonRow\"></tr>").addClass("emptyRow").append(counterCell).append(itemCode).append(addRow).append(extendedCell);
	$("#formContent tbody").append(wholeRow);
}

function jsonForm()
{
	var jsonString = "{";
		$(".jsonRow").each(function (){
					if(!$(this).hasClass("emptyRow"))
					jsonString = jsonString + "\""+$(this).attr("id")
								+"\":{\"detailId\":\""+$(this).find(".detailId").val()+"\","
								+"\"itemCode\":\""+$(this).find(".itemCode").val()+"\","
								+"\"itemQuan\":\""+$(this).find(".itemQuan").val()+"\","
								+"\"itemUnitP\":\""+$(this).find(".itemUnitP").val()+"\","
								+"\"itemExtP\":\""+$(this).find(".itemExtP").val()+"\"},";
					});
		jsonString = jsonString.substring(0, jsonString.length-1) + "}";
	return jsonString;
}


function formatAsMoney(mnt) {
    mnt -= 0;
    mnt = (Math.round(mnt*100))/100;
    return (mnt == Math.floor(mnt)) ? mnt + '.00' 
              : ( (mnt*10 == Math.floor(mnt*10)) ? 
                       mnt + '0' : mnt);
}