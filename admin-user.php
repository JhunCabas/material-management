<?php
include './resources/init.php';
fAuthorization::requireAuthLevel('admin');
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/admin-user.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuAdmin'); ?>
	<h2>Administration</h2><h3>User</h3>
	<table>
		<thead>
			<tr><th>Name</th><th>Username</th><th>Branch</th><th>Email</th><th>Level</th><th>Password</th><th>Icon</th></tr>
		</thead>
		<tbody>
			<?php
				try{
					$users = User::findAll();
					foreach($users as $user)
					{
						$branch = new Branch($user->getBranchId());
						printf("<tr class=\"userRow\"><td id=\"uName\" class=\"varInput\">%s</td><td id=\"uUser\">%s</td><td id=\"uBranch\"class=\"varInput\">%s</td><td id=\"uEmail\" class=\"varInput\">%s</td><td id=\"uLevel\" class=\"varInput\">%s</td>
								<td><input type=\"button\" class=\"uPassword\" user=\"%s\" value=\"Reset\"></input></td>",
								$user->prepareName(),
								$user->prepareUsername(),
								$branch->prepareName(),
								$user->prepareEmail(),
								$user->prepareLevel(),
								$user->prepareUsername());
						printf("<td id=\"iconCell\" class=\"hideFirst\"><ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\">
								<li id=\"save\" title=\"Save\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-check\"></span></li>
								<li id=\"cancel\" title=\"Cancel\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-close\"></span></li>
								<li id=\"delete\" title=\"Delete\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-trash\"></span></li>
								</ul></td></tr>");
					}
				} catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			?>
			<tr id="newItem">
				<td><input id="nuName" value="Input Name" size="10"></input></td>
				<td><input id="nuUser" value="Input Username"></input></td>
				<td><select id="nuBranch"><?php Branch::findAllOption(); ?></select></td>
				<td><input id="nuEmail" value="Input Email"></input></td>
				<td><select id="nuLevel">
						<option value="admin">admin</option>
						<option value="user">user</option>
					</select>
				</td>
				<td><input id="nuPassword" size="7"></input></td>
				<td id="iconCell">
					<ul id="icons" class="ui-widget ui-helper-clearfix">
						<li id="add" title="Add" class="ui-state-default ui-corner-all">
							<span class="ui-icon ui-icon-circle-plus"></span>
						</li>
					</ul>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?php $tmpl->place('footer'); ?>