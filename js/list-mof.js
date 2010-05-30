$(function (){
	$("tr.linkable.PR").click(function (){
		window.location = "document-pr-view.php?"+"id="+$(this).children(".docNumber").text();
	});
	$("tr.linkable.PO").click(function (){
		window.location = "document-po-view.php?"+"id="+$(this).children(".docNumber").text();
	});
});