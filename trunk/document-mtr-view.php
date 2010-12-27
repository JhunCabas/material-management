<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/document-mtr-view.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php 
		$tmpl->place('menuDocument');
		if(!isSet($_GET['id']))
		{
			echo "<div class=\"span-24 ui-state-error ui-corner-all\">
					<span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: 30px;\"></span>
					You arrived to this page in error</div>";
		}else{
			$me = fAuthorization::getUserToken(); 
			$user = new User($me);
			$myBranch = $user->getBranchId();
			try{
				$mattrans = new Material_transfer($_GET['id']);
				$mattrans_details = Material_transfer_detail::findDetail($_GET['id']);
	?>
	<h2>Material Transfer</h2>
	  <div align=left> <a href=report/mtf-pdf.php?MTFnum=<?=$_GET['id']?>><b>Download PDF</b></a></div><br>
  <div class="form-frame span-23 last">
		<h3>Material Transfer Form</h3><br />
		<label for="doc_num">Document Number </label>
			<?php echo $mattrans->prepareDocNumber(); ?><input id="doc_num" type="hidden" value="<?php echo $mattrans->getDocNumber(); ?>"></input><br />
		<label for="doc_date">Document Date </label>
			<?php echo $mattrans->prepareDocDate("j F Y"); ?><br />
		<label for="doc_type">Document Type </label>
			<?php echo $mattrans->prepareDocType(); ?><br />
		<label for="branch_id">From Branch </label>
				<?php $fromBranch = new Branch($mattrans->getBranchFrom()); echo $fromBranch->prepareName();?>
		<label for="branch_id"> To Branch </label>
			<?php $toBranch = new Branch($mattrans->getBranchTo()); echo $toBranch->prepareName();?><br />
		<table id="formContent">
			<thead>
				<tr><th>No</th>
					<th>Item Code</th><th width="300px">Description</th><th>Quantity</th><th>UOM</th><th>Remarks</th><th>In <?php echo $fromBranch->prepareId(); ?></th><th width="75px">Icons</th></tr>
			</thead>
			<tbody>
				<?php
					$counter = 1;
					foreach($mattrans_details as $mattrans_detail)
					{
						echo "<tr class=\"jsonRow\"><td>".$counter."</td><td class=\"itemCode\">".$mattrans_detail->prepareItemId().
							"<input class=\"itemId\" type=\"hidden\" value=\"".$mattrans_detail->prepareId()."\"></input></td>";
						$item = new Inv_item($mattrans_detail->getItemId());
						echo "<td>".$item->prepareDescription()."</td><td class=\"itemQuan\">".$mattrans_detail->prepareQuantity()."</td>
							 	<td>".$item->prepareUnitOfMeasure()."</td><td>".$mattrans_detail->prepareRemark()."</td>";
						$tempRecords = Inv_stock::findStockByBranch($mattrans_detail->getItemId(),$mattrans->getBranchFrom());
						$quanRow = 0;
						
						foreach($tempRecords as $tempRecord)
						{
							$quanRow = $tempRecord->getQuantity();
						}
						echo "<td>".$quanRow."</td>";
						if(fAuthorization::checkAuthLevel('admin')){
							echo "<td><span class=\"loader hideFirst\"><img src=\"./img/layout/ajax-loader2.gif\" /></span>";
							if($mattrans_detail->getStatus() == "pending" && $quanRow >= $mattrans_detail->getQuantity())
								echo "<input type=\"button\" value=\"Transit\" 
										key=\"".$mattrans_detail->prepareId()."\" class=\"transitBTN\"></input></td>";
							else if($mattrans_detail->getStatus() == "transit")
							{
								echo "<input type=\"button\" value=\"Reject\" 
										key=\"".$mattrans_detail->prepareId()."\" class=\"rejectBTN\"></input>";
								echo "<input type=\"button\" value=\"Accept\" 
										key=\"".$mattrans_detail->prepareId()."\" class=\"acceptBTN\"></input>";
								echo "</td>";
							}
							else if($mattrans_detail->getStatus() == "completed")
								echo "<span class=\"ui-icon ui-icon-check\"></span></td>";
							else
								echo "<span class=\"error-not-enough ui-state-error ui-corner-all\">Not Enough</span></td>";
						}else if($mattrans->getBranchFrom() == $myBranch)
						{
							echo "<td><span class=\"loader hideFirst\"><img src=\"./img/layout/ajax-loader2.gif\" /></span>";
							if($mattrans_detail->getStatus() == "pending" && $quanRow >= $mattrans_detail->getQuantity())
								echo "<input type=\"button\" value=\"Transit\" 
										key=\"".$mattrans_detail->prepareId()."\" class=\"transitBTN\"></input></td>";
							else if($mattrans_detail->getStatus() == "pending" && $quanRow < $mattrans_detail->getQuantity())
								echo "<span class=\"error-not-enough ui-state-error ui-corner-all\">Not Enough</span></td>";
							else if($mattrans_detail->getStatus() == "completed")
								echo "<span class=\"ui-icon ui-icon-check\"></span></td>";
							else
								echo "<span class=\"ui-icon ui-icon-clock\"></span></td>";
						}else if($mattrans->getBranchTo() == $myBranch)
						{
							echo "<td><span class=\"loader hideFirst\"><img src=\"./img/layout/ajax-loader2.gif\" /></span>";
							if($mattrans_detail->getStatus() == "transit")
							{
								echo "<input type=\"button\" value=\"Reject\" 
										key=\"".$mattrans_detail->prepareId()."\" class=\"rejectBTN\"></input>";
								echo "<input type=\"button\" value=\"Accept\" 
										key=\"".$mattrans_detail->prepareId()."\" class=\"acceptBTN\"></input>";
								echo "</td>";
							}
							else if($mattrans_detail->getStatus() == "pending")
								echo "<span class=\"ui-icon ui-icon-clock\"></span></td>";
							else if($mattrans_detail->getStatus() == "completed")
								echo "<span class=\"ui-icon ui-icon-check\"></span></td>";
							else
								echo "<span class=\"error-not-enough ui-state-error ui-corner-all\">Not Enough</span></td>";
						}else{
							echo "Unauthorized";
						}
						
						echo "</tr>";
						
						$counter++;
					}
				?>
			</tbody>
		</table>
		<table id="approveContent">
			<tbody>
				<tr>
					<td><label>Requester </label></td><td id="requester"><?php echo $mattrans->prepareRequester(); ?></td><td><label>Date </label><?php echo $mattrans->prepareRequesterDate("j F Y"); ?></td>
				</tr>
			</tbody>
		</table>
		<?php 
					if($mattrans->getStatus() != "completed")
					echo "<input type=\"button\" id=\"cancelBTN\" value=\"Cancel\" style=\"float: right;\"/>";
					echo "<input type=\"hidden\" id=\"whoami\" value=\"".$me."\"/>";
				} catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			}
		?>
	</div>
</div>
<?php $tmpl->place('footer'); ?>