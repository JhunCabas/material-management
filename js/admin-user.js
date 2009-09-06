var tempText = "";

$(function (){
	 $("#dialogBox").dialog({
	 	autoOpen: false
	 });
	 $(".userRow").mouseenter(function () {
		 	$(this).addClass("highlight");
		 }).mouseleave(function (){
		 	$(this).removeClass("highlight");
		 }).dblclick(function (){
		 	$(this).children("#iconCell").removeClass("hideFirst");
		 $.each($(this).children(".varInput"), function (){
		 var defText = $(this).text();
		 var idAttr = $(this).attr("id");
		 if(idAttr == "uBranch")
		 {
		 	$(this).addClass("tobeSelect");
			 $.post("parser/Branch.php",{type :"option"}, function (data){
			 	$(".tobeSelect").html("<select id="+idAttr+">"+ data +"</select>").attr("default",defText).removeClass("tobeSelect");
			 });
		 }else
		 	$(this).html("<input id="+idAttr+" value="+defText+"></input>").attr("default",defText);
		 });
	 });
	 $("li#cancel").click(function (){
	 	$(this).parent().parent().addClass("hideFirst");
		$.each($(this).parent().parent().parent().find(".varInput"), function(){
			$(this).text($(this).attr("default"));
		});
	 });
	 $("li#delete").click(function (){
	     $(this).parent().parent().parent().addClass("tobeDelete");
		 var key = $(".tobeDelete").find("#uUser").text();
		 if(confirm("Continue?"))
		 	 $.post("parser/User.php",{type: "delete", key: key},function (data){
			 	 if(data != "")
				 {
				 	 $("#dialogBox").html(data);
					 $("#dialogBox").dialog('option', 'title', 'Error');
					 $("#dialogBox").dialog('open');
				 }
				 else{
				 	 $("#dialogBox").html("<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: 0.3em;\"/>Deleted");
					 $("#dialogBox").dialog('option', 'title', 'Success');
					 $("#dialogBox").dialog('open');
					 $(".tobeDelete").fadeOut();
				 }
			 });
	 });
	 $("li#save").click(function (){
		 $(this).parent().parent().addClass("hideFirst");
	 	 var username = $(this).parent().parent().parent().find("#uUser").text();
	 	 var name = $(this).parent().parent().parent().find("input#uName").val();
	 	 var branch = $(this).parent().parent().parent().find("select#uBranch").val();
	 	 var email = $(this).parent().parent().parent().find("input#uEmail").val();
	 	 var level = $(this).parent().parent().parent().find("input#uLevel").val();
		 $(this).parent().parent().parent().addClass("tobeEdit");
		 $.post("parser/User.php",{type: "edit", key: username, name: name, branch_id: branch, email: email, level:level},
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
					$.post("parser/Branch.php",{type: "name", key: branch},function (data){
						$(".tobeEdit").find("#uName").text(name);
					 	$(".tobeEdit").find("#uBranch").text(data);
						$(".tobeEdit").find("#uEmail").text(email);
						$(".tobeEdit").find("#uLevel").text(level);
						$(".tobeEdit").removeClass("tobeEdit");
					 });
			 }
		 });
	 });
	 $(".uPassword").click(function (){
		$.post("parser/User.php",{type: "reset", key: $(this).attr("user")},function (data){
			if(data != "")
			 {
				 $("#dialogBox").html(data);
				 $("#dialogBox").dialog('option', 'title', 'Error');
				 $("#dialogBox").dialog('open');
			 }
			 else{
			 	 $("#dialogBox").html("<span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: 0.3em;\"/>Password Changed");
				 $("#dialogBox").dialog('option', 'title', 'Success');
				 $("#dialogBox").dialog('open');
			};
		});
	 });
	 $("li#add").click(function (){
		 var username = $("#nuUser").val();
		 var name = $("#nuName").val();
		 var password = $("#nuPassword").val();
		 var branch = $("#nuBranch").val();
		 var email = $("#nuEmail").val();
		 var level = $("#nuLevel").val();
		 if(confirm("Continue?"))
			$.post("parser/User.php",{type: "add", username: username, name: name, password: password, branch_id: branch, email: email, level: level},function (data){
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
					 $.post("parser/Branch.php",{type: "name", key: branch},function (data){
					 	var prependedText = "<tr><td>"+name+"</td><td>"+username+"</td><td>"+data+"</td><td>"+email+"</td><td>"+level+"</td><td><input type=\"button\"></input></td></tr>";
						 $("#newItem").before(prependedText);
					 });
				 }
			});
	 });
});