<?php include_once('../functions/function_auth_officer.php'); ?>
<?php include_once('../functions/function_getUnitsForUser.php'); ?>
<?php include_once('../inc/OptionQueries/lk_qualificationCategories_query.php'); ?>

<?php
	$UnitRoleID = strip_tags(isset($_GET[pid]) ? $_GET[pid] : '');
	
	
	$UnitRoleQuals_query = "
		select
			u.UnitName
			,u.UnitID
			,r.role_name
			,r.role_id
			,uq.RowID
			,q.IsActive
			,q.qualification_id
			,q.qualification_name
			,q.qualification_categoryID
			,lk.CategoryName as qualification_category
			,q.qualification_image
			,IFNULL(uq.QualificationLevel,0) as `qualification_level`
			,q.level1_reqs
			,q.level2_reqs
			,q.level3_reqs
		from projectx_vvarsc2.UnitRoles ur
		join projectx_vvarsc2.roles r
			on r.role_id = ur.RoleID
		join projectx_vvarsc2.Units u
			on u.UnitID = ur.UnitID
		left join projectx_vvarsc2.UnitQualifications uq
			on uq.RoleID = ur.RoleID
			and uq.UnitID = ur.UnitID
		left join projectx_vvarsc2.qualifications q
			on q.qualification_id = uq.QualificationID
		left join projectx_vvarsc2.LK_QualificationCategories lk
			on lk.CategoryID = q.qualification_categoryID
		where ur.RowID = $UnitRoleID
		order by
			q.qualification_name
	";
	
	$UnitRoleQuals_query_results = $connection->query($UnitRoleQuals_query);
	$display_unitRole_qualifications = "";
	
	$UnitName = "";
	$UnitID = "";
	$RoleName = "";
	$RoleID = "";
	while(($row2 = $UnitRoleQuals_query_results->fetch_assoc()) != false)
	{
		$UnitName = $row2['UnitName'];
		$UnitID = $row2['UnitID'];
		$RoleName = $row2['role_name'];
		$RoleID = $row2['role_id'];
		
		$rowID = $row2['RowID'];
		$isActive = $row2['IsActive'];
		$qual_id = $row2['qualification_id'];
		$qual_name = $row2['qualification_name'];
		$qual_categoryID = $row2['qualification_categoryID'];
		$qual_category = $row2['qualification_category'];
		$qual_image = $link_base."/images/qualifications/".$row2['qualification_image'];
		$qual_level_id = $row2['qualification_level'];
		$level1_reqs = $row2['level1_reqs'];
		$level2_reqs = $row2['level2_reqs'];
		$level3_reqs = $row2['level3_reqs'];
		
		if ($level1_reqs == null || $level1_reqs == "")
			$level1_reqs = "- No Requirements Found -";
			
		if ($level2_reqs == null || $level2_reqs == "")
			$level2_reqs = "- No Requirements Found -";
			
		if ($level3_reqs == null || $level3_reqs == "")
			$level3_reqs = "- No Requirements Found -";
		
		$imageClassName1 = "player_qual_row_image";
		$imageClassName2 = "player_qual_row_image";
		$imageClassName3 = "player_qual_row_image";
		
		if ($qual_level_id == 1) {
			$imageClassName1 = "player_qual_row_image_highlighted";
			$imageClassName2 = "player_qual_row_image";
			$imageClassName3 = "player_qual_row_image";
		}
		else if ($qual_level_id == 2) {
			$imageClassName1 = "player_qual_row_image_highlighted";
			$imageClassName2 = "player_qual_row_image_highlighted";
			$imageClassName3 = "player_qual_row_image";
		}
		else if ($qual_level_id == 3) {
			$imageClassName1 = "player_qual_row_image_highlighted";
			$imageClassName2 = "player_qual_row_image_highlighted";
			$imageClassName3 = "player_qual_row_image_highlighted";
		}
		
		if ($isActive == 0)
		{
			$imageClassName1 .= " image_inactive";
			$imageClassName2 .= " image_inactive";
			$imageClassName3 .= " image_inactive";
		}

		$display_unitRole_qualifications .= "
			<tr class=\"player_qual_row\"
				data-rowid=$rowID
				data-id=$qual_id
				data-level=$qual_level_id
				data-category=$qual_categoryID
				data-unitid=$UnitID
				data-roleid=$RoleID
				data-isactive=$isActive
			>
		";
		if ($isActive == 1)
		{
			$display_unitRole_qualifications .= "
				<td class=\"player_qual_row_name\">$qual_category<br /><strong>$qual_name</strong></td>
			";
		}
		else
		{
			$display_unitRole_qualifications .= "
				<td class=\"player_qual_row_name inactive\">$qual_category<br />$qual_name</td>
			";
		}
		$display_unitRole_qualifications .= "
				<td class=\"player_qual_row_image_container tooltip-wrap\">
					<img class=\"$imageClassName1\" src=\"$qual_image\" height=\"30px\" width=\"30px\">
					<div class=\"rsi-tooltip\">
						<div class=\"rsi-tooltip-content\">
							<strong>$qual_name - Level 1</strong>
							<br />
							$level1_reqs
						</div>
						<span class=\"rsi-tooltip-bottom\"></span>
					</div>
				</td>
				<td class=\"player_qual_row_image_container tooltip-wrap\">
					<img class=\"$imageClassName2\" src=\"$qual_image\" height=\"30px\" width=\"30px\">
					<div class=\"rsi-tooltip\">
						<div class=\"rsi-tooltip-content\">
							<strong>$qual_name - Level 2</strong>
							<br />
							$level2_reqs
						</div>
						<span class=\"rsi-tooltip-bottom\"></span>
					</div>
				</td>
				<td class=\"player_qual_row_image_container tooltip-wrap\">
					<img class=\"$imageClassName3\" src=\"$qual_image\" height=\"30px\" width=\"30px\">
					<div class=\"rsi-tooltip\">
						<div class=\"rsi-tooltip-content\">
							<strong>$qual_name - Level 3</strong>
							<br />
							$level3_reqs
						</div>
						<span class=\"rsi-tooltip-bottom\"></span>
					</div>
				</td>
				<td>
					<div class=\"player_qual_entry_buttons_buttonContainer\">
						<button class=\"adminButton adminButtonEdit adminEditQual\" style=\"		margin-right:0px\" title=\"Edit Qualification\">
							<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_edit.png\">
						</button>
						<button class=\"adminButton adminButtonDelete adminDeleteQual\" style=\"margin-left:0px\" title=\"Delete Qualification\">
							<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_delete.png\">
						</button>
					</div>
				</td>
			</tr>
		";
	}
	$display_qual_edit = "
		<button id=\"adminAddQualification\" class=\"adminButton adminButtonCreate\" title=\"Add Qualification\"style=\"
			float: right;
			margin-left: 0px;
			margin-right: 2%;
		\">
			<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_add.png\">
			Add Qualification
		</button>
		<br />
	";
	
	include_once('../inc/OptionQueries/getAvailableQualificationsForUnitRole.php');
?>

<div class="div_filters_container">
	<div class="div_filters_entry">
		<a href="../admin/?page=admin_unit&pid=<? echo $UnitID ?>">&#8672; Back to Unit Page</a>
	</div>
</div>
		<!--QUALIFICATIONS-->
		<h3 style="padding-left: 0px; margin-left: 0px">
			<?echo $UnitName;?> // <? echo $RoleName;?>
		</h3>
		<h4 style="padding-left: 0px; margin-left: 0px">
			Required Qualifications
		</h4>
		<div class="table_header_block">
		</div>	
		<div id="p_qual_container" class="play" valign="top" align="left" style="width:auto;">
			<div class="p_qual" valign="top" style="width:auto;">
				<div class="p_section_header" style="float:left">
					<a href="<? echo $link_base; ?>/wiki/?page=qual" target="_blank" style="
						text-decoration: none;
						font-size: 1.1em;
						font-weight: normal;
						color: #FFF;
					">
						Qualifications
					</a>
				</div>
				<? echo $display_qual_edit ?>
				<div style="
					width:100%;
				">
					<table class="player_qualifications">
						<? echo $display_unitRole_qualifications; ?>
					</table>
				</div>
			</div>
		</div>
		
<!--FORMS-->
	<!--Add Qualification Form-->
	<div id="dialog-form-add-qual" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Add Qualification</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/function_unitRoleQualification_Create.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="UnitRoleID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="UnitRoleID" id="UnitRoleID" value="" class="adminDialogTextInput" style="display: none" readonly>	
				
				<label for="UnitID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="UnitID" id="UnitID" value="" class="adminDialogTextInput" style="display: none" readonly>	
				
				<label for="RoleID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="RoleID" id="RoleID" value="" class="adminDialogTextInput" style="display: none" readonly>
				
				<label for="Category" class="adminDialogInputLabel">
					Category
				</label>
				<select name="Category" id="Category" class="adminDialogDropDown">
					<option selected="true" disabled="true" value="default" id="CategoryID-default">
						--Select a Category--
					</option>
					<? echo $displayQualificationCategorySelectors ?>
				</select>
				
				<label for="QualificationID" class="adminDialogInputLabel">
					Qualification Name
				</label>
				<select name="QualificationID" id="QualificationID" class="adminDialogDropDown" required>
					<option selected="true" disabled="true" value="default" id="QualID-default">
						--Select a Qualification--
					</option>
					<? echo $displayAvailableQualificationsSelectors ?>
				</select>
					
				
				<label for="Level" class="adminDialogInputLabel">
					Qualification Level
				</label>
				<select name="Level" id="Level" class="adminDialogDropDown" required>
					<option selected="true" disabled="true" value="default" id="Level-default">
						--Select a Level--
					</option>
					<option value="1" id="Level-1">
						1 (Basic)
					</option>
					<option value="2" id="Level-2">
						2 (Advanced)
					</option>
					<option value="3" id="Level-3">
						3 (Mastery)
					</option>
				</select>
			</fieldset>
			<div class="adminDialogButtonPane">
				<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
					Submit
				</button>
			</div>
		</form>
	</div>
	
	<!--Edit Qualification Form-->
	<div id="dialog-form-edit-qual" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Update Qualification</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/function_unitRoleQualification_Edit.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="RowID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" style="display: none" readonly>

				<label for="UnitRoleID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="UnitRoleID" id="UnitRoleID" value="" class="adminDialogTextInput" style="display: none" readonly>	
				
				<label for="UnitID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="UnitID" id="UnitID" value="" class="adminDialogTextInput" style="display: none" readonly>	
				
				<label for="RoleID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="RoleID" id="RoleID" value="" class="adminDialogTextInput" style="display: none" readonly>
				
				<label for="Category" class="adminDialogInputLabel">
					Category
				</label>
				<select name="Category" id="Category" class="adminDialogDropDown" disabled>
					<option selected="true" disabled="true" value="default" id="CategoryID-default">
						--Select a Category--
					</option>
					<? echo $displayQualificationCategorySelectors ?>
				</select>
				
				<label for="QualificationID" class="adminDialogInputLabel">
					Qualification Name
				</label>
				<select name="QualificationID" id="QualificationID" class="adminDialogDropDown" disabled>
					<option selected="true" disabled="true" value="default" id="QualID-default">
						--Select a Qualification--
					</option>
					<? echo $displayQualificationsSelectors ?>
				</select>
					
				
				<label for="Level" class="adminDialogInputLabel">
					Qualification Level
				</label>
				<select name="Level" id="Level" class="adminDialogDropDown">
					<option selected="true" disabled="true" value="default" id="Level-default">
						--Select a Level--
					</option>
					<option value="1" id="Level-1">
						1 (Basic)
					</option>
					<option value="2" id="Level-2">
						2 (Advanced)
					</option>
					<option value="3" id="Level-3">
						3 (Mastery)
					</option>
				</select>
			<div class="adminDialogButtonPane">
				<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
					Submit
				</button>
			</div>
		</form>
	</div>
	
	<!--Delete Qualification Form-->
	<div id="dialog-form-delete-qual" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Delete Qualification</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/function_unitRoleQualification_Delete.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="RowID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" style="display: none" readonly>

				<label for="UnitRoleID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="UnitRoleID" id="UnitRoleID" value="" class="adminDialogTextInput" style="display: none" readonly>	
				
				<label for="UnitID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="UnitID" id="UnitID" value="" class="adminDialogTextInput" style="display: none" readonly>	
				
				<label for="RoleID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="RoleID" id="RoleID" value="" class="adminDialogTextInput" style="display: none" readonly>
				
				<label for="Category" class="adminDialogInputLabel">
					Category
				</label>
				<select name="Category" id="Category" class="adminDialogDropDown" disabled>
					<option selected="true" disabled="true" value="default" id="CategoryID-default">
						--Select a Category--
					</option>
					<? echo $displayQualificationCategorySelectors ?>
				</select>
				
				<label for="QualificationID" class="adminDialogInputLabel">
					Qualification Name
				</label>
				<select name="QualificationID" id="QualificationID" class="adminDialogDropDown" disabled>
					<option selected="true" disabled="true" value="default" id="QualID-default">
						--Select a Qualification--
					</option>
					<? echo $displayQualificationsSelectors ?>
				</select>
					
				
				<label for="Level" class="adminDialogInputLabel">
					Qualification Level
				</label>
				<select name="Level" id="Level" class="adminDialogDropDown"  disabled>
					<option selected="true" disabled="true" value="default" id="Level-default">
						--Select a Level--
					</option>
					<option value="1" id="Level-1">
						1 (Basic)
					</option>
					<option value="2" id="Level-2">
						2 (Advanced)
					</option>
					<option value="3" id="Level-3">
						3 (Mastery)
					</option>
				</select>
			</fieldset>
			<div class="adminDialogButtonPane">
				<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
					Submit
				</button>
			</div>
		</form>
	</div>		
	
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<!--Form Controls-->
<script>
	function resizeInput() {
		$(this).attr('size', $(this).val().length);
	}
	
	$(document).ready(function() {
		var dialog = $('#dialog-form');
		dialog.hide();
		
		//Set TextArea Input Height to Correct Values
		//$("textarea").height( $("textarea")[0].scrollHeight );
		
		$('input[type="text"]')
		// event handler
		.keyup(resizeInput)
		// resize on page load
		.each(resizeInput);
	});
	
	$(function() {

		var overlay = $('#overlay');

		//Add Qualification
		$('#adminAddQualification').click(function() {
			var dialog = $('#dialog-form-add-qual');
			
			var $self = jQuery(this);
			
			var unitID = "<? echo $UnitID ?>";
			var roleID = "<? echo $RoleID ?>";
			var unitRoleID = "<? echo $UnitRoleID ?>";
			
			dialog.find('#UnitID').val(unitID).text();
			dialog.find('#RoleID').val(roleID).text();
			dialog.find('#UnitRoleID').val(unitRoleID).text();

			dialog.find('select').find('option').prop('selected',false);
			
			dialog.find('#Category').find('#CategoryID-default').prop('selected',true);
			dialog.find('#QualificationID').find('#QualID-default').prop('selected',true);
			dialog.find('#Level').find('#Level-default').prop('selected',true);
			//dialog.find('#IsActive').find('#IsActive-1').prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.player_topTable_Container').css({
				filter: 'blur(2px)'
			});
			$('.player_shipsTable_Container').css({
				filter: 'blur(2px)'
			});
			
			dialog.find('#Category').change(function() {
				if(typeof $(this).data('options') === "undefined"){
					$(this).data('options',dialog.find(('#QualificationID option')));
				}
				var select2 = dialog.find('#QualificationID');
				var id = $(this).val();
				var filter = id + '_';
				var defaultOption = select2.find('#QualID-default');
				var options = $(this).data('options').filter('[value^=' + filter + ']');
				var optionsList = defaultOption.add(options);
				
				select2.html(optionsList);
				select2.find('#QualID-default').prop('selected',true);				
			});
			dialog.find('#Category').trigger('change');

		});
		
		//Edit Qualification
		$('.adminButton.adminButtonEdit.adminEditQual').click(function() {
			var dialog = $('#dialog-form-edit-qual');
			
			var $self = jQuery(this);
			
			var unitID = "<? echo $UnitID ?>";
			var roleID = "<? echo $RoleID ?>";
			var unitRoleID = "<? echo $UnitRoleID ?>";
			
			dialog.find('#UnitID').val(unitID).text();
			dialog.find('#RoleID').val(roleID).text();
			dialog.find('#UnitRoleID').val(unitRoleID).text();
			
			var rowID = $self.parent().parent().parent().data("rowid");
			var qualID = $self.parent().parent().parent().data("id");
			var qualLevel = $self.parent().parent().parent().data("level");
			var qualName = $self.parent().parent().parent().find('.player_qual_row_name strong').text().trim();
			var category = $self.parent().parent().parent().data("category");
			var isActive = $self.parent().parent().parent().data("isactive");
			
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#UnitID').val(unitID).text();
			dialog.find('#RoleID').val(roleID).text();
			dialog.find('#UnitRoleID').val(unitRoleID).text();
			
			dialog.find('#Category').find('#CategoryID-' + category).prop('selected',true);
			dialog.find('#QualificationID').find('#QualID-' + qualID).prop('selected',true);
			dialog.find('#Level').find('#Level-' + qualLevel).prop('selected',true);
			//dialog.find('#IsActive').find('#IsActive-1' + isActive).prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.player_topTable_Container').css({
				filter: 'blur(2px)'
			});
			$('.player_shipsTable_Container').css({
				filter: 'blur(2px)'
			});

		});

		//Delete Qualification
		$('.adminButton.adminButtonDelete.adminDeleteQual').click(function() {
			var dialog = $('#dialog-form-delete-qual');
			
			var $self = jQuery(this);
			
			var unitID = "<? echo $UnitID ?>";
			var roleID = "<? echo $RoleID ?>";
			var unitRoleID = "<? echo $UnitRoleID ?>";
			
			dialog.find('#UnitID').val(unitID).text();
			dialog.find('#RoleID').val(roleID).text();
			dialog.find('#UnitRoleID').val(unitRoleID).text();
			
			var rowID = $self.parent().parent().parent().data("rowid");
			var qualID = $self.parent().parent().parent().data("id");
			var qualLevel = $self.parent().parent().parent().data("level");
			var qualName = $self.parent().parent().parent().find('.player_qual_row_name strong').text().trim();
			var category = $self.parent().parent().parent().data("category");
			var isActive = $self.parent().parent().parent().data("isactive");
			
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#UnitID').val(unitID).text();
			dialog.find('#RoleID').val(roleID).text();
			dialog.find('#UnitRoleID').val(unitRoleID).text();
			
			dialog.find('#Category').find('#CategoryID-' + category).prop('selected',true);
			dialog.find('#QualificationID').find('#QualID-' + qualID).prop('selected',true);
			dialog.find('#Level').find('#Level-' + qualLevel).prop('selected',true);
			//dialog.find('#IsActive').find('#IsActive-1' + isActive).prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.player_topTable_Container').css({
				filter: 'blur(2px)'
			});
			$('.player_shipsTable_Container').css({
				filter: 'blur(2px)'
			});

		});
		
		//Cancel
		$('.adminDialogButton.dialogButtonCancel').click(function() {
			
			//Clear DropDown Selections
			$('.adminDiaglogFormFieldset').find('#UnitID').val("").text();
			$('.adminDiaglogFormFieldset').find('#RoleID').val("").text();
			$('.adminDiaglogFormFieldset').find('#RowID').val("").text();
			$('.adminDiaglogFormFieldset').find('select').find('option').prop('selected',false);
			
			//Hide All Dialog Containers
			$('#dialog-form-add-qual').hide();
			$('#dialog-form-edit-qual').hide();
			$('#dialog-form-delete-qual').hide();
			
			overlay.hide();
			$('.player_topTable_Container').css({
				filter: 'none'
			});
			$('.player_shipsTable_Container').css({
				filter: 'none'
			});
		});	
	});
</script>