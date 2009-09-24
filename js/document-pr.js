var counter = 0;
var jsonSupplier = 0;
var jsonItem = 0;
function format(entry) {
	return entry.name;
}
function formatInvItem(entry){
	return entry.name +"  ("+ entry.desc +")";
}
$(function (){
	addingRow();
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$("#sup1auto").autocomplete("parser/autocomplete/Supplier.php",{
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
						$("#sup1").val(item.to)});
	$("#sup2auto").autocomplete("parser/autocomplete/Supplier.php",{
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
						$("#sup2").val(item.to)});
	$("#sup3auto").autocomplete("parser/autocomplete/Supplier.php",{
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
						$("#sup3").val(item.to)});
	$("#discountRate").blur(function (){
		var total = 0;
		$(".itemExtP").each(function (){
			total = total + parseFloat($(this).val());
		});
		total = total - $("#discountRate").val();
		$("#purchaseTotal").text(formatAsMoney(total));
	});
	$(".datepicker").datepicker();
	$("#doc_date").datepicker({
			onSelect: function(dateText, inst){
				getRunningNumber();
				var month = Date.parseExact(dateText, "M/d/yyyy").toString("MM")
				var year = Date.parseExact(dateText, "M/d/yyyy").toString("yyyy")
				$.post("parser/Currency.php",{type: "option", month: month, year: year}, function (data){
					$("#currency_id").html(data);
				});
			}
	});
	$("#branch_id").change(function (){ getRunningNumber(); });
	$("#doc_type").change(function (){ getRunningNumber(); });
	$("#doc_num").attr("readonly","true");
	getRunningNumber();
	$("#addRowBTN").click(function (){
		addingRow();
	});
	$("#submitBTN").click(function (){
		var running_number = $("#run_num").val();
		var doc_number = $("#doc_num").val();
		var doc_date = $("#doc_date").val();
		var doc_type = $("#doc_type").val();
		var discount = $("#discountRate").val();
		var currency = $("#currency_id").val();
		var total = $("#purchaseTotal").text();
		var branch_id = $("#branch_id").val();
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
		if(confirm("Continue?"))
		$.post("parser/Purchase.php",{
			type: "add",
			doc_number: doc_number,
			doc_date: doc_date,
			doc_type: doc_type,
			currency: currency,
			discount: discount,
			running_number: running_number,
			total: total,
			branch_id: branch_id,
			jsonForm: jsonForm(),
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
							total = total - $("#discountRate").val();
							$("#purchaseTotal").text(formatAsMoney(total));
						});
	var extendedCell = $("<td></td>").html(extendedInner);
	var counterCell = $("<td></td>").text(++counter);
	var itemCode = $("<td></td>").html(itemCodeInner);
	var wholeRow = $("<tr id=\"rowNo"+ counter +"\" class=\"jsonRow\"></tr>").addClass("emptyRow").append(counterCell).append(itemCode).append(addRow).append(extendedCell);
	$("#formContent tbody").append(wholeRow);
}

function jsonForm()
{
	var jsonString = "{";
		$(".jsonRow").each(function (){
					if(!$(this).hasClass("emptyRow"))
					jsonString = jsonString + "\""+$(this).attr("id")+"\":{\"itemCode\":\""
								+$(this).find(".itemCode").val()+"\","
								+"\"itemQuan\":\""+$(this).find(".itemQuan").val()+"\","
								+"\"itemUnitP\":\""+$(this).find(".itemUnitP").val()+"\","
								+"\"itemExtP\":\""+$(this).find(".itemExtP").val()+"\"},";
					});
		jsonString = jsonString.substring(0, jsonString.length-1) + "}";
	return jsonString;
}

function getRunningNumber()
{
	$.post("parser/Purchase.php",{type:"countPR"},function (data){
		$("#run_num").val(data);
		$("#doc_num").val($("#doc_type").val()+"/"+$("#branch_id").val()+"/"+$("#run_num").val()+"/"+Date.parseExact($("#doc_date").val(), "M/d/yyyy").toString("MM/yyyy"));
	});
}

function formatAsMoney(mnt) {
    mnt -= 0;
    mnt = (Math.round(mnt*100))/100;
    return (mnt == Math.floor(mnt)) ? mnt + '.00' 
              : ( (mnt*10 == Math.floor(mnt*10)) ? 
                       mnt + '0' : mnt);
}