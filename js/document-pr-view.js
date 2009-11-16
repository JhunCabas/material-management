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
	$("#discountRate").blur(function (){
		var total = 0;
		$(".itemExtP").each(function (){
			total = total + parseFloat($(this).val());
		});
		total = total - $("#discountRate").val();
		$("#purchaseTotal").text(formatAsMoney(total));
	});
	$(".itemExtP").each(function (){
		$(this).bind("blur",function (){
			var total = 0;
			$(".itemExtP").each(function (){
				total = total + parseFloat($(this).val());
			});
			total = total - $("#discountRate").val();
			$("#purchaseTotal").text(formatAsMoney(total));
		});
	});
	$(".datepicker").datepicker();
	if($("#docStatus").val() == "unapproved")
	{
		editableBody();
	}
	//Get form body from JSON
	$.ajax({type:"post",url:"parser/Purchase.php",data:{type:"json",key:$("#docNum").text()},dataType:"json", success: function(data){
		$.each(data, function(i,item){
			$.get("parser/Inv_item.php",{type:"uom",key:item.item_id},function (data){
				fillingRow(item.item_id,item.description,item.quantity,data,item.unit_price,item.extended_price,item.id);
			});
		});
	}})
	$("#submitBTN").click(function (){
		var total = $("#purchaseTotal").text();
		var discount = $("#discountRate").val();
		var approver1 = $("#approver1").text();
		var approver1_date = $("#app1Date").val();
		if(confirm("Continue?"))
		$.post("parser/Purchase.php",{
			type: "edit",
			key: $("#docNum").text(),
			jsonForm: jsonForm(),
			discount: discount,
			total: total,
			approver_1: approver1,
			approver_1_date: approver1_date
			}, function (data){
		 		if(data != "")
				 {
					console.log(jsonForm());
				 	 $("#dialogBox").html(data);
					 $("#dialogBox").dialog('option', 'title', 'Error');
					 $("#dialogBox").dialog('open');
				 }
				 else{
				 	$("#dialogBox").html("<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: 0.3em;\"/>Edited");
					 $("#dialogBox").dialog('option', 'title', 'Success');
					 $("#dialogBox").dialog('open');
					 $("#dialogBox").bind('dialogclose', function(event, ui) {
						history.go(-1);
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

function fillingRow(itemCode,itemDesc,itemQuantity,itemUOM,itemPrice,itemExtend,itemDetail)
{
	var itemCodeInner = $("<input size=\"7\" class=\"itemCode\"></input>").val(itemCode)
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
						$("<input></input>").addClass("itemDesc").attr("size",40).val($('<div/>').html(itemDesc).text())
					);
	var uomCell = $("<td></td>").attr("id","uomAuto").text(itemUOM);
	var quantityCell = $("<td></td>").attr("id","itemQuan").html(
							$("<input></input>").addClass("itemQuan").attr("size",5).val(itemQuantity)
								.bind("blur", function (){
									var unitValue = $(this).parent().parent().find(".itemUnitP").val();
									$(this).parent().parent().find(".itemExtP").val(formatAsMoney(parseFloat(unitValue)*$(this).val()));
									calculateTotal();
								})
						);
	
	var priceCell = $("<td></td>").html(
							$("<input></input>").addClass("itemUnitP").val(formatAsMoney(itemPrice))
								.bind("blur", function (){
									var quanValue = $(this).parent().parent().find(".itemQuan").val();
									$(this).parent().parent().find(".itemExtP").val(formatAsMoney(parseInt(quanValue)*$(this).val()));
									calculateTotal();
								})
						);
	var extendedCell = $("<td></td>").html(
							$("<input></input>").addClass("itemExtP").attr("readonly","true").val(formatAsMoney(itemExtend))
						);
	var idInput = $("<input></input>").addClass("detailId").val(itemDetail).hide();
	var counterCell = $("<td></td>").text(++counter).append(idInput)
						.bind("dblclick", function (){
							if(confirm("Confirm delete row?"))
							{
								counter--;
								$(this).parent().remove();
							}
						});
	var itemCode = $("<td></td>").html(itemCodeInner);
	var wholeRow = $("<tr id=\"rowNo"+ counter +"\" class=\"jsonRow\"></tr>")
					.append(counterCell)
					.append(itemCode)
					.append(descCell)
					.append(quantityCell)
					.append(uomCell)
					.append(priceCell)
					.append(extendedCell);
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
							$("<input></input>").addClass("itemExtP").attr("readonly","true")
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
					jsonString = jsonString + "\""+$(this).attr("id")
								+"\":{\"detailId\":\""+$(this).find(".detailId").val()+"\","
								+"\"itemCode\":\""+$(this).find(".itemCode").val()+"\","
								+"\"itemDesc\":\""+descEncode+"\","
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