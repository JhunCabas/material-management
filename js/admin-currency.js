$(function (){
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$(".currencyRow").mouseenter(function () {
			$(this).addClass("highlight");
		}).mouseleave(function (){
		 	$(this).removeClass("highlight");
		}).dblclick(function (){
		 	$(this).children("#iconCell").removeClass("hideFirst");
		$.each($(this).children(".varInput"), function (){
			var defText = $(this).text();
			var idAttr = $(this).attr("id");
			if(idAttr != "cMonth")
				$(this).html("<input id=\""+idAttr+"\" value=\""+defText+"\"></input>").attr("default",defText);
			else
				$(this).html("<select id=\""+idAttr+"\"><option value=\"1\">January</option><option value=\"2\">February</option><option value=\"3\">March</option><option value=\"4\">April</option><option value=\"5\">May</option><option value=\"6\">June</option><option value=\"7\">July</option><option value=\"8\">August</option><option value=\"9\">September</option><option value=\"10\">October</option><option value=\"11\">November</option><option value=\"12\">December</option></select>").attr("default",defText);
		});
	});
	$("li#save").click(function (){
		 $(this).parent().parent().addClass("hideFirst");
	 	 var key = $(this).parent().parent().parent().find("#cId").text();
	 	 var country = $(this).parent().parent().parent().find("input#cCountry").val();
	 	 var month = $(this).parent().parent().parent().find("select#cMonth").val();
	 	 var year = $(this).parent().parent().parent().find("input#cYear").val();
		 var exchange = $(this).parent().parent().parent().find("input#cExchange").val();
		 var saveDate = Date.parse(month + " 1st " + year); 
		 $(this).parent().parent().parent().addClass("tobeEdit");
		 $.post("parser/Currency.php",{type: "edit", key: key, country: country, month: saveDate, exchange: exchange},
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
				 $(".tobeEdit").find("#cCountry").text(country);
				 $(".tobeEdit").find("#cExchange").text(exchange);
				 $(".tobeEdit").find("#cMonth").text(saveDate.toString("MMMM"));
				$(".tobeEdit").find("#cYear").text(year);
				 $(".tobeEdit").removeClass("tobeEdit");
			 }
		 });
	 });
	$("li#add").click(function (){
		 var country = $("#nuCountry").val();
		 var exchange = $("#nuExchange").val();
		 var month = $("#nuMonth").val();
		 var year = $("#nuYear").val();
		 var saveDate = Date.parse(month + " 1st " + year); 
		 if(confirm("Continue?") && saveDate != null)
			$.post("parser/Currency.php",{type: "add", month:saveDate, exchange: exchange, country: country},function (data){
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
					 	var prependedText = "<tr><td>"+country+"</td><td>"+exchange+"</td><td>"+saveDate.toString("MMMM")+"</td><td>"+year+"</td></tr>";
						 $("#newItem").before(prependedText);
				 }
			});
	 });
	$("li#cancel").click(function (){
		$(this).parent().parent().addClass("hideFirst");
		$.each($(this).parent().parent().parent().find(".varInput"), function(){
			$(this).text($(this).attr("default"));
		});
	});
});