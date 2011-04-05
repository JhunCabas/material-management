function format(entry) {
	return entry.name;
}
function formatInvItem(entry){
	return entry.name +"  ("+ entry.desc +")";
}
$(function (){
	$(document).ajaxStop($.unblockUI); 
	$("#dialogBox").dialog({
		autoOpen: false
	});
	$(".updateBTN").click(function (){
		var target = $(this).attr("id");
		var quantity = $(this).parent().parent().find("input");
		$.blockUI({ 
			message: $('#messageBox'),
			css: { 
				border: 'none', 
				padding: '15px', 
				backgroundColor: '#000', 
				'-webkit-border-radius': '10px', 
				'-moz-border-radius': '10px', 
				opacity: .5, 
				color: '#fff' 
			} 
		}); 
		$.post("parser/Inv_stock.php",{type: "reset", target: target, quantity: quantity.val()},
			function(data)
			{
				quantity.find("input").val(data);
			});
	});
	$("#autocompleteItem").autocomplete("parser/autocomplete/Inv_item.php",{
						width: 300,
						parse: function(data) {
							return $.map(eval(data), function(row) {
								return {
									data: row,
									value: row.name,
									result: row.name
								}
							});
						},
						formatItem: function(item) {
							return formatInvItem(item);
						}
					});
});