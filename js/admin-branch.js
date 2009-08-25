var tempText = "";

$(function (){
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$(".branchRow").mouseenter(function () {
			$(this).addClass("highlight");
		}).mouseleave(function (){
		 	$(this).removeClass("highlight");
		}).dblclick(function (){
		 	$(this).children("#iconCell").removeClass("hideFirst");
		$.each($(this).children(".varInput"), function (){
			var defText = $(this).text();
			var idAttr = $(this).attr("id");
			$(this).html("<input id=\""+idAttr+"\" value=\""+defText+"\"></input>").attr("default",defText);
		});
	});
	$("li#cancel").click(function (){
		$(this).parent().parent().addClass("hideFirst");
		$.each($(this).parent().parent().parent().find(".varInput"), function(){
			$(this).text($(this).attr("default"));
		});
	});
	$("li#save").click(function (){
		 $(this).parent().parent().addClass("hideFirst");
	 	 var key = $(this).parent().parent().parent().find("#bId").text();
	 	 var name = $(this).parent().parent().parent().find("input#bName").val();
	 	 var location = $(this).parent().parent().parent().find("input#bLocation").val();
	 	 var phone = $(this).parent().parent().parent().find("input#bPhone").val();
		 $(this).parent().parent().parent().addClass("tobeEdit");
		 $.post("parser/Branch.php",{type: "edit", key: key, name: name, location: location, phone_no:phone},
		 function (data){
		 	if(data != "")
			 {
				 $("#dialogBox").html(data);
				 $("#dialogBox").dialog('option', 'title', 'Error');
				 $("#dialogBox").dialog('open');
			 }
			 else{
			 	 $("#dialogBox").html("<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: 0.3em;\"/>Editted");
				 $("#dialogBox").dialog('option', 'title', 'Success');
				 $("#dialogBox").dialog('open');
				 $(".tobeEdit").find("#bName").text(name);
				 $(".tobeEdit").find("#bLocation").text(location);
				 $(".tobeEdit").find("#bPhone").text(phone);
				 $(".tobeEdit").removeClass("tobeEdit");
			 }
		 });
	 });
	 $("li#add").click(function (){
		 var key = $("#nuId").val();
		 var name = $("#nuName").val();
		 var location = $("#nuLocation").val();
		 var phone = $("#nuPhone").val();
		 if(confirm("Continue?"))
			$.post("parser/Branch.php",{type: "add", id: key, name:name, location: location, phone_no: phone},function (data){
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
					 	var prependedText = "<tr><td>"+key+"</td><td>"+name+"</td><td>"+location+"</td><td>"+phone+"</td></tr>";
						 $("#newItem").before(prependedText);
				 }
			});
	 });
});