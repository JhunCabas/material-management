$(function (){
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$("td span.varInput").mouseenter(function () {
		$(this).addClass("highlight");
	}).mouseleave(function (){
		$(this).removeClass("highlight");
	});
	$("#editBTN").click(function (){
		$(this).fadeOut(function (){
			$("#saveBTN").fadeIn();
		});		
		$.each($("span.varInput"), function (){
			if($(this).attr("id") == "statusVal")
			{
				$.post("parser/Supplier.php", {type: "option"},
					function(data){
						$("#statusVal").html(data);
					});
			}
			else{
				var tempText = $(this).text();
				$(this).html("<input value=\""+tempText+"\"></input>");
			}
		});
	});
	$("#saveBTN").click(function (){
		$.post("parser/Supplier.php",{
			type: "edit", 
			key: $("#key").val(),
			name: $("#supName input").val(),
			address: $("#address input").val(), 
			line_1: $("#line1 input").val(),
			line_2: $("#line2 input").val(),
			line_3: $("#line3 input").val(),
			contact_person: $("#cPerson input").val(),
			contact: $("#contact input").val(),
			fax_no: $("#fax input").val(),
			info: $("#info input").val(),
			status: $("#statusVal select").val()
			},
			function (data){
				if(data != "")
				{
					$("#dialogBox").html(data);
					$("#dialogBox").dialog('option', 'title', 'Error');
					$("#dialogBox").dialog('open');
				}
				//location.reload();	
			});
		$(this).fadeOut(function (){
			$("#editBTN").fadeIn();
		});	
		$("span#titleName").text($("#supName input").val());		
		$.each($("span.varInput"), function (){
			var tempText = $(this).children("input").val();
			$(this).text(tempText);
		});
	});
});