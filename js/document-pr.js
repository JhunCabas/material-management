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
	$("#loaderBar").hide();
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
						$("#sup1").val(item.to);
						$.post("parser/Supplier.php",{type: "generate", key: item.to}, function (data){
							$("#box1 .boxBody").hide().html(data).slideDown();
						})	
					});
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
						$("#sup2").val(item.to);
						$.post("parser/Supplier.php",{type: "generate", key: item.to}, function (data){
							$("#box2 .boxBody").hide().html(data).slideDown();
						})
					});
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
						$("#sup3").val(item.to);
						$.post("parser/Supplier.php",{type: "generate", key: item.to}, function (data){
							$("#box3 .boxBody").hide().html(data).slideDown();
						})
					});
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
				var month = Date.parseExact(dateText, "M/d/yyyy").toString("MM");
				var year = Date.parseExact(dateText, "M/d/yyyy").toString("yyyy");
				$.post("parser/Currency.php",{type: "option", month: month, year: year}, function (data){
					$("#currency_id").html(data);
				});
			}
	});
	$("#doc_date").datepicker('setDate', Date.today() );
	getCurrency();
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
		var supplier_2 = $("#sup2").val();
		var supplier_3 = $("#sup3").val();
		var special = $("#special").val();
		var requester = $("#requester").text();
		var requester_date = $("#reqDate").val();
		var payment = $("#payment").val();
		var delivery = $("#delivery").val();
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
			supplier_2: supplier_2,
			supplier_3: supplier_3,
			requester: requester,
			requester_date: requester_date,
			payment: payment,
			delivery: delivery,
			special_instruction: special
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
	$("#submitPOBTN").click(function (){
		var running_number = $("#run_num").val();
		var doc_number = $("#doc_num").val();
		var doc_date = $("#doc_date").val();
		var doc_type = $("#doc_type").val();
		var discount = $("#discountRate").val();
		var currency = $("#currency_id").val();
		var total = $("#purchaseTotal").text();
		var branch_id = $("#branch_id").val();
		var supplier_1 = $("#sup1").val();
		var supplier_2 = $("#sup2").val();
		var supplier_3 = $("#sup3").val();
		var special = $("#special").val();
		var requester = $("#requester").text();
		var requester_date = $("#reqDate").val();
		var payment = $("#payment").val();
		var delivery = $("#delivery").val();
		if(confirm("Continue?"))
		$.post("parser/Purchase.php",{
			type: "approve",
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
			supplier_2: supplier_2,
			supplier_3: supplier_3,
			requester: requester,
			requester_date: requester_date,
			approver_1: requester,
			approver_1_date: requester_date,
			payment: payment,
			delivery: delivery,
			special_instruction: special
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
											$(this).parent().parent().find("#descAuto input").val(item.desc);
											$(this).parent().parent().find("#uomAuto").text(item.uom);
										});

	var descCell = $("<td></td>").attr("id","descAuto").html(
						$("<input></input>").addClass("itemDesc").attr("size",40)
						);
	var uomCell = $("<td></td>").attr("id","uomAuto");
	var quantityCell = $("<td></td>").attr("id","itemQuan").html(
							$("<input></input>").addClass("itemQuan").attr("size",5).val(0)
								.bind("blur", function (){
									var unitValue = $(this).parent().parent().find(".itemUnitP").val();
									$(this).parent().parent().find(".itemExtP").val(formatAsMoney(parseFloat(unitValue)*$(this).val()));
									calculateTotal();
								})
						);
	
	var priceCell = $("<td></td>").html(
							$("<input></input>").addClass("itemUnitP").val("0.00")
								.bind("blur", function (){
									var quanValue = $(this).parent().parent().find(".itemQuan").val();
									$(this).parent().parent().find(".itemExtP").val(formatAsMoney(parseInt(quanValue)*$(this).val()));
									calculateTotal();
								})
						);
	var extendedCell = $("<td></td>").html(
							$("<input></input>").addClass("itemExtP").attr("readonly",true)
						);
	var counterCell = $("<td></td>").text(++counter)
						.bind("dblclick", function (){
							if(confirm("Confirm delete row?"))
							{
								counter--;
								$(this).parent().remove();
							}
						});
	var itemCode = $("<td></td>").html(itemCodeInner);
	var wholeRow = $("<tr id=\"rowNo"+ counter +"\" class=\"jsonRow\"></tr>").addClass("emptyRow")
					.append(counterCell)
					.append(itemCode)
					.append(descCell)
					.append(quantityCell)
					.append(uomCell)
					.append(priceCell)
					.append(extendedCell);
	$("#formContent tbody").append(wholeRow);
}

function calculateTotal()
{
	var total = 0;
	$(".itemExtP").each(function (){
		total = total + parseFloat($(this).val());
	});
	total = total - $("#discountRate").val();
	$("#purchaseTotal").text(formatAsMoney(total));
}

function jsonForm()
{
	var jsonString = "{";
		$(".jsonRow").each(function (){
					if(!$(this).hasClass("emptyRow"))
					var descEncode = $("<div/>").text($(this).find(".itemDesc").val()).html().replace(/"/g,'&quot;');
					jsonString = jsonString + "\""+$(this).attr("id")+"\":{\"itemCode\":\""
								+$(this).find(".itemCode").val()+"\","
								+"\"itemDesc\":\""+descEncode+"\","
								+"\"itemQuan\":\""+$(this).find(".itemQuan").val()+"\","
								+"\"itemUnitP\":\""+$(this).find(".itemUnitP").val()+"\","
								+"\"itemExtP\":\""+$(this).find(".itemExtP").val()+"\"},";
					});
		jsonString = jsonString.substring(0, jsonString.length-1) + "}";
	return jsonString;
}

function getRunningNumber()
{
	$("#loaderBar").show();
	$.post("parser/Purchase.php",{type:"countPR", branch:$("#hiddenBranch").val(), doctype:$("#doc_type").val()},function (data){
		$("#run_num").val(data);
		$("#doc_num").val("PR"+$("#doc_type").val()+"/"+$("#hiddenBranch").val()+"/"+$("#run_num").val()+"/"+Date.parseExact($("#doc_date").val(), "M/d/yyyy").toString("MM/yyyy"));
		$("#loaderBar").hide();
	});
}

function getCurrency()
{
	var month = Date.today().toString("MM");
	var year = Date.today().toString("yyyy");
	$.post("parser/Currency.php",{type: "option", month: month, year: year}, function (data){
		$("#currency_id").html(data);
	});
}

function formatAsMoney(mnt) {
    mnt -= 0;
    mnt = (Math.round(mnt*100))/100;
    return (mnt == Math.floor(mnt)) ? mnt + '.00' 
              : ( (mnt*10 == Math.floor(mnt*10)) ? 
                       mnt + '0' : mnt);
}