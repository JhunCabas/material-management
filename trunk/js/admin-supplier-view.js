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
				$.post("parser/Inv_item.php", {type: "option"},
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
});