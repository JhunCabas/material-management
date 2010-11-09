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
			var tempText = $(this).text();
			if($(this).attr("id") == "statusVal")
			{
				$.post("parser/Inv_item.php", {type: "option", input: tempText},
					function(data){
						$("#statusVal").html(data);
					});
			}else{
				$(this).html("<input value=\""+encodeHTML(tempText)+"\"></input>");
			}
		});
	});
	$("#saveBTN").click(function (){
		$.post("parser/Inv_item.php",{
			type: "edit", 
			key: $("#idCaption").text(), 
			description: $("#desc input").val(),
			weight: $("#weight input").val(),
			dimension: $("#dim input").val(),
			part_number: $("#part input").val(),
			unit_of_measure: $("#uom input").val(),
			rate: $("#rate input").val(),
			currency: $("#curr input").val(),
			purchase_year: $("#pury input").val(),
			detailed_description: $("#detailed input").val(),
			status: $("#statusVal select").val()
			},
			function (data){
				if(data != "")
				{
					$("#dialogBox").html(data);
					$("#dialogBox").dialog('option', 'title', 'Error');
					$("#dialogBox").dialog('open');
				}
				location.reload();	
			});
		$(this).fadeOut(function (){
			$("#editBTN").fadeIn();
		});		
		$.each($("span.varInput"), function (){
			var tempText = $(this).children("input").val();
			$(this).text(tempText);
		});
	});
});

function encodeHTML(decodedString)
{
	return $("<div />").text(decodedString).html().replace(/"/g,'&quot;');
}