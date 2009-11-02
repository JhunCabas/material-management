var tempText = "";

$(function (){
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$(".doctypeRow").mouseenter(function () {
			$(this).addClass("highlight");
		}).mouseleave(function (){
		 	$(this).removeClass("highlight");
	});
	$("li#add").click(function (){
		var key = $("#docID").val();
		var desc = $("#docDesc").val();
		$.post("parser/Document_type.php",{ type: "add", id: key, description: desc}, function (data){
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
				 	var prependedText = "<tr><td>"+key+"</td><td>"+desc+"</td></tr>";
					 $("#newItem").before(prependedText);
			 }
		});
	});
});