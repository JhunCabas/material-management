</head>
<body>
<div class="container">
	<div id="header" class="span-24 ui-corner-top last">
		<div id="tab1" class="span-3 tab ui-corner-top">
        	<a href="admin-user.php">Admin</a>
        </div>
        <div id="tab2" class="span-3 tab ui-corner-top">
        	<a href="list-pr.php">Document</a>
        </div>
        <div id="tab3" class="span-3 tab ui-corner-top">
        	<a href="inventory.php">Inventory</a>
        </div>
		<div id="tab4" class="span-3 tab ui-corner-top">
        	<a href="report.php">Report</a>
        </div>
		<div id="logintab" class="span-7">
		<?php
			//echo "<p style=\"float: right;\">";
			if(fAuthorization::checkLoggedIn())
			{
				echo "Welcome ". fAuthorization::getUserToken();
				//echo "<a>Change Password </a>";
				echo " <a href=\"authentication.php?type=logout\">Logout </a>";
				//echo "</p>";
			}
		?>
		</div>
		<div id="logo" class="go-right span-3 last"></div>
		
	</div>