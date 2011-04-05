function format(entry) {
	return entry.name;
}
function formatInvItem(entry){
	return entry.name +"  ("+ entry.desc +")";
}
$(function (){
	$("#dialogBox").dialog({
		autoOpen: false
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