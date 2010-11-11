<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/admin-branch.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuReport'); ?>
	<h2>User Report</h2><h3>Aging</h3>
	<?php
		$branch = "HQKL";
		$items = Inv_stock::findByBranch($branch);
	?>
	<table>
		<thead>
			<tr>
				<th></th>
				<?php
					for($i=1;$i<=12;$i++)
					{
						$stringmonth = date("F", mktime(0,0,0,$i));
						echo "<th>".$stringmonth."</th>";
					}
				?>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach($items as $item)
				{
			?>
			<tr>
				<td>
					<?php echo $item->prepareItemId(); ?>
				</td>
				<?php
					$total = 0;
				 	for($i=1;$i<=12;$i++)
					{
						$value = Inv_movement::findByMonth($branch,$i,$item->getItemId());
						$arr[$item->getItemId()][$i] = $value;
						echo "<td>".($value > 0 ? "+".$value : $value);
						$total = $total + $arr[$item->getItemId()][$i];
						echo "(".$total.")</td>";
					}
				?>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?php $tmpl->place('footer'); ?>