function tellDir() {
	var montage=window.location.href.split("/");
	var simple=montage.length-2;
	var final="";
	for(var i=0;i<=simple;i++)
	{
		final=final+montage[i]+"/";
	}
	return final;
}