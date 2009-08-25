$(function (){
	//Autoupdate scroll
	$('document').ready(function(){		
		updatestatus();		
		scrollalert();
	});
	function updatestatus(){
		//Show number of loaded items
		var totalItems=$('#content p').length;
		$('#status').text('Loaded '+totalItems+' Items');
	}
	function scrollalert(){
		var scrolltop=$('#scrollbox').attr('scrollTop');
		var scrollheight=$('#scrollbox').attr('scrollHeight');
		var windowheight=$('#scrollbox').attr('clientHeight');
		var scrolloffset=20;
		if(scrolltop>=(scrollheight-(windowheight+scrolloffset)))
		{
			//fetch new items
			$('#status').text('Loading more items...');
			$.get('new-items.html', '', function(newitems){
				$('#content').append(newitems);
				updatestatus();
			});
		}
		setTimeout('scrollalert();', 1500);
	}
	$(".linkable").mouseenter(function () {
			$(this).addClass("highlight");
		}).mouseleave(function (){
		 	$(this).removeClass("highlight");
		});
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$("#uploadBox").dialog({
		autoOpen: false
	});
	$("#subcc").children("select").attr("disabled","true");
	$("#classific").children("select").attr("disabled","true");
	$("#addsubcc").attr("disabled","true");
	$("#addclassific").attr("disabled","true");
	$("#maincc").click(function (){
		$.post("parser/Inv_subcategory.php", { type: "option", key: $(this).children("select").val() },
		function (data){
			$("#subcc").children("select").html("<option></option>"+data).removeAttr("disabled");
		});
	});
	$("#subcc").click(function (){
		$("#subcc").children("select").removeAttr("disabled");
		$.post("parser/Inv_classification.php", { type: "option", key: $(this).children("select").val() },
		function (data){
			$("#classific").children("select").html("<option></option>"+data).removeAttr("disabled");
		});
	});
	$("tr.linkable").click(function (){
		window.open("inventory-view.php?"+"id="+$(this).children("#itemID").children().text());
	});
	$("#newItem").click(function (){
		$(".addItem").fadeIn();
	});
	$("#addmaincc").click(function (){
		$.post("parser/Inv_subcategory.php", { type: "option", key: $(this).val() },
		function (data){
			$("#addsubcc").html(data).removeAttr("disabled");
		});
	});
	$("#addsubcc").click(function (){
		$(this).removeAttr("disabled");
		$.post("parser/Inv_classification.php", { type: "option", key: $(this).val() },
		function (data){
			$("#addclassific").html(data).removeAttr("disabled");
			$("table#tableDetail").fadeIn("slow");
		});
	});
	$("#idInput").blur(function (){
		$("#idSpan").text($("#addclassific").val() + $("#idInput").val());
	});
	$("#addBTN").click(function (){
		$.post("parser/Inv_item.php", { 
			type: "add", 
			id: $("#idSpan").text(), 
			main_category_code: $("#addmaincc").val(),
			sub_category_code: $("#addsubcc").val(),
			classification_code: $("#addclassific").val(),
			item_code: $("#idInput").val(),
			description: $("#desc").val(),
			weight: $("#weight").val(),
			dimension: $("#dim").val(),
			part_number: $("#part").val(),
			unit_of_measure: $("#uom").val(),
			rate: $("#rate").val(),
			currency: $("#curr").val(),
			purchase_year: $("#pury").val(),
			detailed_description: $("#detailed").val(),
			status: $("#statusVal").val()
			},
		function (data){
			if(data != "")
			{
				$("#dialogBox").html(data);
				$("#dialogBox").dialog('option', 'title', 'Error');
				$("#dialogBox").dialog('open');
			}else{
				$("#uploadBox").dialog('option', 'title', 'Success');
				$("#uploadify").uploadify({
					'uploader'	: './resources/library/jquery.uploadify/uploadify.swf',
					'script'    : './resources/library/jquery.uploadify/uploadify.php',
					'cancelImg'	: './resources/library/jquery.uploadify/cancel.png',
					'auto'		: true,					
					'folder'	: 'storage/image/'+$("#idSpan").text(),
					'onComplete': function (evt, queueID, fileObj, response) {
									$.post("parser/Inv_item.php", {
										type: "url",
										url: fileObj.filePath,
										key: $("#idSpan").text()
									});
								}
				});
				$("#uploadBox").dialog('open');
			}
		});
	});
});