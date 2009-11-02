$(function (){
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$(".datepicker").datepicker();
	$(".signHere").click(function (){
		if($(this).parent().next().children("input").val() == "")
			alert("Input Date first");
		else
			$(this).parent().html($("#whoami").val());
	});
	$("li#save").click(function (){
		var rowNo = $(this).parent().parent().parent().attr("id");
		$(this).next().addClass("loadingOpen").show();
		var branch = $("#branchId").text();
		var itemCode = $("#"+rowNo).find("#itemCode").text();
		var itemQuan = $("#"+rowNo).find("#itemQuan").text();
		var detailId = $("#"+rowNo).find("#detailId").text();
		$.post("parser/Production_issue.php", {type: "removeStock", detailId: detailId, branch: branch, itemCode: itemCode, itemQuan: itemQuan}, function (data)
		{
			$("#"+rowNo).find(".loadingOpen").removeClass("loadingOpen").hide();
			if(data != "")
			 {
			 	 $("#dialogBox").html(data);
				 $("#dialogBox").dialog('option', 'title', 'Error');
				 $("#dialogBox").dialog('open');
			 }
			else
			{
				$("#dialogBox").html("<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: 0.3em;\"/>Stock out complete");
				 $("#dialogBox").dialog('option', 'title', 'Success');
				 $("#dialogBox").dialog('open');
				$("#"+rowNo).find("li#save").hide();
			}
		});
	});
	$("#submitBTN").click(function (){
		var issuer = $("#issuer").text();
		var issuer_date = $("#issDate").val();
		var receiver = $("#receiver").text();
		var receiver_date = $("#recDate").val();
		$.post("parser/Production_issue.php",{type: "save", key: $("#docNum").text(), issuer: issuer, issuer_date: issuer_date, receiver: receiver, receiver_date: receiver_date});
	});
});