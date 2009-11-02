var tempText = "";

$(function (){
	$("#subcc").children("select").attr("disabled","true");
	$("#submitBTN").attr("disabled","disabled");
	$("#maincc").click(function (){
		$.post("parser/Inv_subcategory.php", { type: "option", key: $(this).children("select").val() },
		function (data){
			$("#subcc").children("select").html(data).removeAttr("disabled");
			$("#submitBTN").removeAttr("disabled");
		});
	});
	$("#dialogBox").dialog({
		autoOpen: false
	});
	
	$("td span.varInput").mouseenter(function () {
		$(this).addClass("highlight");
	}).mouseleave(function (){
		$(this).removeClass("highlight");
	}).dblclick(function (){
		if($(this).children().is("input"))
			tempText = $(this).children().attr("default");
		else
			tempText = $(this).text();
		if($(this).attr("id") == "Status")
			$(this).removeClass("varInput").html("<select default=\""+tempText+"\"><option value=\"1\">Active</option><option value=\"0\">Inactive</option>")
			.parent().parent().children("#iconCell").removeClass("hideFirst");
		else if($(this).attr("id") == "SubCategorycode" || $(this).attr("id") == "Classificationcode")
		{
			// For future improvement to change ID:
			//$(this).removeClass("varInput").html($("#maincc").html())
			//.parent().parent().children("#iconCell").removeClass("hideFirst");
			//$(this).children("select").attr("default",tempText);
			$(this).removeClass("highlight");
		}
		else
			$(this).removeClass("varInput").html("<input type=\"text\" default=\""+tempText+"\" value=\""+tempText+"\" />")
			.parent().parent().children("#iconCell").removeClass("hideFirst");
	});

	$("li#cancel").click(function (){
		$(this).parent().parent().addClass("hideFirst");
		$.each($(this).parent().parent().parent().find("td span"), function(){
			$(this).addClass("varInput").text($(this).children().attr("default"));
		});
	});
	
	$("li#add").click(function (){
		var Classificationcode = $("#newItem").find("span#Classificationcode input").val();
		var Description = $("#newItem").find("span#Description input").val();
		var SubCategorycode = $("#newItem").find("span#SubCategorycode select").val();
		var Status = $("#newItem").find("span#Status select").val();
		var Id = SubCategorycode + Classificationcode;
		if(confirm("Continue?"))
			$.post("parser/Inv_classification.php", { type: "add", id: Id, classification_code: Classificationcode, sub_category_code: SubCategorycode, description: Description, status: Status},
				function (data){
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
						
						var prependedText = "<tr><td class=\"hideFirst\"><span>"
											+Id+"</span></td><td><span class=\"varInput\" id=\"Classification\">"
											+Classificationcode+"</span></td><td><span class=\"varInput\" id=\"Description\">"
											+Description+"</span></td><td><span class=\"varInput\" id=\"SubCategorycode\">"
											+SubCategorycode+"<td><span class=\"varInput\" id=\"Status\">"
											+((Status == 1)? "Active":"Inactive")+"</span></td><td id=\"iconCell\" class=\"hideFirst\"><ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\"><li id=\"save\" title=\"Save\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-check\"></span></li><li id=\"cancel\" title=\"Cancel\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-close\"></span></li><li id=\"delete\" title=\"Delete\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-trash\"></span></li></ul></td></tr>";
						$("#newItem").before(prependedText);
						
					}
				});
	});
	
	$("li#save").click(function (){
		$(this).parent().parent().addClass("hideFirst");
		$(this).parent().parent().parent().addClass("tobeEdit");
		var key = "";
		var Classificationcode = "";
		var SubCategorycode = "";
		var Description = "";
		var Status = "";
		var Id;
		key = $(this).parent().parent().parent().find("td span#Id").text();
		if($(this).parent().parent().parent().find("#Categorycode").children().is("input"))
			Classificationcode = $(this).parent().parent().parent().find("#Classificationcode").children("input").val();
		else
			Classificationcode = $(this).parent().parent().parent().find("#Classificationcode").text();	
		if($(this).parent().parent().parent().find("#Description").children().is("input"))
			Description = $(this).parent().parent().parent().find("#Description").children("input").val();
		else
			Description = $(this).parent().parent().parent().find("#Description").text();
		if($(this).parent().parent().parent().find("#SubCategorycode").children().is("select"))
			SubCategorycode = $(this).parent().parent().parent().find("#SubCategorycode").children("select").val();
		else
			SubCategorycode = $(this).parent().parent().parent().find("#SubCategorycode").text();
		if($(this).parent().parent().parent().find("#Status").children().is("select"))
			Status = $(this).parent().parent().parent().find("#Status").children("select").val();
		else
			($(this).parent().parent().parent().find("#Status").text() == "Active") ? Status = 1 : Status = 0;
		Id = SubCategorycode + Classificationcode;
		if(confirm("Continue?"))
			$.post("parser/Inv_classification.php", { type: "edit", key: key, classification_code: Classificationcode, sub_category_code: SubCategorycode, description: Description, status: Status },
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
					$(this).parent().parent().text("tete");
					$(".tobeEdit").find("#Classificationcode").text(Classificationcode);
					$(".tobeEdit").find("#Description").text(Description);
					$(".tobeEdit").find("#SubCategorycode").text(SubCategorycode);
					$(".tobeEdit").find("#Status").text((Status == 1 ? "Active" : "Inactive"));
					$(".tobeEdit").removeClass("tobeEdit");
				}
			});
	});

	$("li#delete").click(function (){
		$(this).parent().parent().parent().addClass("tobeDelete");
		var key = $(this).parent().parent().parent().find("td span#Id").text();
		if(confirm("Continue?"))
			$.post("parser/Inv_classification.php", { type: "delete", key: key },
			function (data){
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
});