<?php 
	$manu_query = "
		select
			m.manu_id
			,m.manu_name
			,m.manu_shortName
			,m.manu_smallImage
		from projectx_vvarsc2.manufacturers m
	";
	
	$manu_query_results = $connection->query($manu_query);
	
	while(($row = $manu_query_results->fetch_assoc()) != false)
	{
		$manuID = $row['manu_id'];
		$manuName = $row['manu_name'];
		$manuShortName = $row['manu_shortName'];
		$manuSmallImage = $row['manu_smallImage'];
	
		$displayManu .= "
			<tr class=\"adminTableRow\">
				<td class=\"adminTableRowTD\">
					$manuID
				</td>
				<td class=\"adminTableRowTD\">
					$manuName
				</td>
				<td class=\"adminTableRowTD\">
					$manuShortName
				</td>
				<td class=\"adminTableRowTD\">
					<img class=\"shipyard_mainTable_row_header_manuImage\" align=\"center\" src=\"$manuSmallImage\" />
				</td>
				<td class=\"adminTableRowTD\">
					<button id=\"adminEditManu\" class=\"adminButton adminButtonEdit\">
						Edit
					</button>
					<button id=\"adminDeleteManu\" class=\"adminButton adminButtonDelete\">
						Delete
					</button>
				</td>
			</tr>
		";
	}
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">
</script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script>
	$(document).ready(function() {
		var dialog = $('#dialog-form');
		dialog.hide();
	});
	
	$(function() {

		var dialog = $('#dialog-form');
		var overlay = $('#overlay');

		$('#adminCreateManu').click(function() {
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(4px)'
			});
		});
		
		/*
		$('#adminDialogSubmit').click(function() {
			dialog.hide();
			$('.adminTable').css({
				filter: 'none'
			});
		});
		*/
		$('#adminDialogCancel').click(function() {
			dialog.hide();
			overlay.hide();
			$('.adminTable').css({
				filter: 'none'
			});
		});
		
	});
</script>

<h2>Manufacturer Management</h2>
<div id="TEXT">
	<div id="adminManuTableContainer" class="adminTableContainer">
		<button id="adminCreateManu" class="adminButton adminButtonCreate">Add New Manufacturer</button>
		<table id="adminManuTable" class="adminTable">
			<tr class="adminTableHeaderRow">
				<td class="adminTableHeaderRowTD">
					ID
				</td>
				<td class="adminTableHeaderRowTD">
					Name
				</td>
				<td class="adminTableHeaderRowTD">
					ShortName
				</td>
				<td class="adminTableHeaderRowTD">
					LogoImage
				</td>
				<td class="adminTableHeaderRowTD">
					Actions
				</td>
			</tr>
			<? echo $displayManu ?>
		</table>
		
		<div id="dialog-form" class="adminDialogFormContainer">
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Enter new Manufacturer Information Below.</p>
			<p class="validateTips">All form fields are required.</p>
			<form class="adminDialogForm" action="function_manu_Create.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
				  <label for="Name">Name</label>
				  <input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus>
				  
				  <label for="ShortName">ShortName</label>
				  <input type="text" name="ShortName" id="ShortName" value="" class="adminDialogTextInput"required>
				  
				  <label for="ImageURL">ImageURL</label>
				  <input type="text" name="ImageURL" id="ImageURL" value="" class="adminDialogTextInput"required>
				</fieldset>
				<div class="adminDialogButtonPane">
					<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
						Submit
					</button>
				</div>
			</form>
		</div>
	</div>
</div>