$(function (){
	$(".linkable").mouseenter(function () {
			$(this).addClass("highlight");
		}).mouseleave(function (){
		 	$(this).removeClass("highlight");
		});
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$("#uploadBox").dialog({
		autoOpen: false
	});
	/*
	$("#subcc").children("select").attr("disabled","true");
	$("#classific").children("select").attr("disabled","true");
	*/
	$("#subcc select").hide();
	$("#classific select").hide();
	$("#subcc").hide();
	$("#classific").hide();
	$("#addsubcc").hide();
	$("#addclassific").hide();
	$("#addsubcc select").hide();
	$("#addclassific select").hide();
	
	$("#idInput").attr("disabled","true");
	
	$("#maincc select").click(function (){
		$("#subcc").fadeIn();
		$.post("parser/Inv_subcategory.php", { type: "option", key: $(this).val() },
		function (data){
			if(data != "")
				$("#subcc img").fadeOut("slow", function (){
					$("#subcc select").html("<option></option>"+data).fadeIn();
				});
		});
	});
	$("#subcc select").click(function (){
		$("#classific").fadeIn();
		$.post("parser/Inv_classification.php", { type: "option", key: $(this).val() },
		function (data){
			if(data != "")
				$("#classific img").fadeOut("slow", function (){
					$("#classific select").html("<option></option>"+data).fadeIn();
				});
		});
	});
	$("tr.linkable").click(function (){
		window.open("inventory-view.php?"+"id="+$(this).children("#itemID").children().text());
	});
	
	$("#newItem").click(function (){
		$(".addItem").fadeIn();
	});
	/*
	$("#addmaincc").click(function (){
		$.post("parser/Inv_subcategory.php", { type: "option", key: $(this).val() },
		function (data){
			$("#addsubcc").html(data).removeAttr("disabled");
		});
	});
	$("#addsubcc").click(function (){
		$(this).removeAttr("disabled");
		$.post("parser/Inv_classification.php", { type: "option", key: $(this).val() },
		function (data){
			$("#addclassific").html(data).removeAttr("disabled");
			$("table#tableDetail").show();
		});
	});
	$("#addclassific").blur(function (){
		if($(this).val() != "")
		{
			$.post("parser/Inv_item.php",{type: "lastCode", classific: $(this).val()}, function (data){
				$("#idInput").val(data);
				$("#idSpan").text($("#addclassific").val()+$("#idInput").val());
			})
		}
	});
	$("#idInput").blur(function (){
		$("#idSpan").text($("#addclassific").val() + $("#idInput").val());
	});
	*/
	$("#addmaincc select").mousedown(function (){
		if($("#addsubcc").is(":visible"))
		{
			$("#addsubcc select").fadeOut(function (){
				$("#addsubcc img").show();
				resetAddForm();
				$("#addclassific").hide();
				$("#addclassific select").hide();
			});	
		}
	});
	$("#addmaincc select").mouseup(function (){
		$("#addsubcc").fadeIn();
		$.post("parser/Inv_subcategory.php", { type: "option", key: $(this).val() },
		function (data){
			if(data != "")
				$("#addsubcc img").fadeOut("slow", function (){
					$("#addsubcc select").html(data).fadeIn();
				});
		});
	});
	$("#addsubcc select").mousedown(function (){
		if($("#addclassific").is(":visible"))
		{
			$("#addclassific select").fadeOut(function (){
				$("#addclassific img").show();
				resetAddForm();
			});
		}
	});
	$("#addsubcc select").mouseup(function (){
		$("#addclassific").fadeIn();
		$.post("parser/Inv_classification.php", { type: "option", key: $(this).val() },
		function (data){
			if(data != "")
				$("#addclassific img").fadeOut("slow", function (){
					$("table#tableDetail").fadeIn();
					$("#addclassific select").html(data).fadeIn();
					if($("#addclassific select").val() != "")
					{
						$.post("parser/Inv_item.php",{type: "lastCode", classific: $("#addclassific select").val()}, function (data){
							$("#idInput").val(data);
							$("#idSpan").text($("#addclassific select").val()+$("#idInput").val());
						})
					}
				});
		});
	});
	$("#addclassific select").mouseup(function (){
		if($(this).val() != "")
		{
			$.post("parser/Inv_item.php",{type: "lastCode", classific: $(this).val()}, function (data){
				$("#idInput").val(data);
				$("#idSpan").text($("#addclassific select").val()+$("#idInput").val());
			})
		}
	});
	$("#idInput").blur(function (){
		$("#idSpan").text($("#addclassific select").val() + $("#idInput").val());
	});
	
	$("#addBTN").click(function (){
		$.post("parser/Inv_item.php", { 
			type: "add", 
			id: $("#idSpan").text(), 
			main_category_code: $("#addmaincc select").val(),
			sub_category_code: $("#addsubcc select").val(),
			classification_code: $("#addclassific select").val(),
			item_code: $("#idInput").val(),
			description: $("#desc").val(),
			weight: $("#weight").val(),
			dimension: $("#dim").val(),
			part_number: $("#part").val(),
			unit_of_measure: $("#uom").val(),
			rate: $("#rate").val(),
			currency: $("#curr").val(),
			purchase_year: $("#pury").val(),
			detailed_description: $("#detailed").val(),
			status: $("#statusVal").val()
			},
		function (data){
			if(data != "")
			{
				$("#dialogBox").html(data);
				$("#dialogBox").dialog('option', 'title', 'Error');
				$("#dialogBox").dialog('open');
			}else{
				$("#dialogBox").html("<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: 0.3em;\"/>Success");
				$("#dialogBox").dialog('option', 'title', 'Success');
				$("#dialogBox").dialog('open');
				$(".tobeDelete").fadeOut();
			}
		});
	});
});

function resetAddForm()
{
	$("#idSpan").html("");
	$("#idInput").val("");
}