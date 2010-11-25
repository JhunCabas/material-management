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

	$("#subcc select").hide();
	$("#classific select").hide();
	$("#subcc").hide();
	$("#classific").hide();
	$("#addsubcc").hide();
	$("#addclassific").hide();
	$("#addsubcc select").hide();
	$("#addclassific select").hide();
	
	$("#idInput").attr("disabled","true");
	
	$("#maincc select").mousedown(function (){
		if($("#subcc").is(":visible"))
		{
			$("#subcc select").fadeOut(function (){
				$("#subcc img").show();
				$("#classific").hide();
				$("#classific select").hide();
			});	
		}
	});
	$("#maincc select").change(function (){
		$("#subcc").fadeIn();
		if($(this).val() != "all")
		{
			$.post("parser/Inv_subcategory.php", { type: "option", key: $(this).val() },
			function (data){
				if(data != "")
					$("#subcc img").fadeOut("slow", function (){
						$("#subcc select").html("<option></option>"+data).fadeIn();
					});
			});
		}
		else
		{
			$("#subcc").fadeOut();
			$("#subcc img").fadeOut();
			$("#subcc select").fadeOut();
			$("#subcc select").val("");
			$("#classific").fadeOut();
			$("#classific select").fadeOut();
			$("#classific select").val("");
		}
	});
	
	$("#subcc select").mousedown(function (){
		if($("#classific").is(":visible"))
		{
			$("#classific select").fadeOut(function (){
				$("#classific img").show();
			});	
		}
	});
	$("#subcc select").change(function (){
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
	$("#addmaincc select").change(function (){
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
	$("#addsubcc select").change(function (){
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
	$("#addclassific select").change(function (){
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