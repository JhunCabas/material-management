var tempText = "";

$(function (){
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$(".supplierRow").mouseenter(function () {
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
	 	 var key = $(this).parent().parent().parent().find("#sId").text();
	 	 var name = $(this).parent().parent().parent().find("input#sName").val();
	 	 var contact = $(this).parent().parent().parent().find("input#sContact").val();
	 	 var info = $(this).parent().parent().parent().find("input#sInfo").val();
		 $(this).parent().parent().parent().addClass("edit"+key);
		 $.post("parser/Supplier.php",{type: "edit", key: key, name: name, contact: contact, info:info},
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
				 $(".edit"+key).find("#sName").text(name);
				 $(".edit"+key).find("#sContact").text(contact);
				 $(".edit"+key).find("#sInfo").text(info);
				 $(".edit"+key).removeClass("edit"+key);
			 }
		 });
	 });
});