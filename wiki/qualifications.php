<?php include_once('../functions/function_auth_user.php'); ?>

<?php 
	$qualifications_query = "
		select
			q.qualification_id
			,q.qualification_name
			,q.qualification_image
			,q.qualification_categoryID
			,lk.CategoryName as qualification_category
			,q.IsActive
			,q.level1_reqs
			,q.level2_reqs
			,q.level3_reqs
		from projectx_vvarsc2.qualifications q
		join projectx_vvarsc2.LK_QualificationCategories lk
			on lk.CategoryID = q.qualification_categoryID
		where q.IsActive = 1
		order by
			lk.CategoryName
			,q.qualification_name
	";
	
	$qualifications_query_results = $connection->query($qualifications_query);
	$displayQualifications = "";
	
	while(($row = $qualifications_query_results->fetch_assoc()) != false)
	{
		$qualID = $row['qualification_id'];
		$qualName = $row['qualification_name'];
		$qualCategoryID = $row['qualification_categoryID'];
		$qualCategory = $row['qualification_category'];
		$qualImage = $row['qualification_image'];
		$qualIsActive = $row['IsActive'];
		$qualLevel1Reqs = $row['level1_reqs'];
		$qualLevel2Reqs = $row['level2_reqs'];
		$qualLevel3Reqs = $row['level3_reqs'];
		
		$qualIsActiveDisplay = "";
		$qualLevel1ReqsDisplay = "";
		$qualLevel2ReqsDisplay = "";
		$qualLevel3ReqsDisplay = "";
		
		if ($qualIsActive == 1)
			$qualIsActiveDisplay = "Active";
		else
			$qualIsActiveDisplay = "Inactive";
		
		if ($qualLevel1Reqs == null || $qualLevel1Reqs == "")
			$qualLevel1ReqsDisplay = "-- no requirements found --";
		else
			$qualLevel1ReqsDisplay = $qualLevel1Reqs;
			
		if ($qualLevel2Reqs == null || $qualLevel2Reqs == "")
			$qualLevel2ReqsDisplay = "-- no requirements found --";
		else
			$qualLevel2ReqsDisplay = $qualLevel2Reqs;
			
		if ($qualLevel3Reqs == null || $qualLevel3Reqs == "")
			$qualLevel3ReqsDisplay = "-- no requirements found --";
		else
			$qualLevel3ReqsDisplay = $qualLevel3Reqs;
	
		$displayQualifications .= "
			<div class=\"table_header_block\">
			</div>
			<div class=\"yard_filters\" style=\"margin-bottom: 16px;\"
				data-id=\"$qualID\"
				data-name=\"$qualName\"
				data-categoryid=\"$qualCategoryID\"
				data-imageurl=\"$qualImage\"
				data-isactive=\"$qualIsActive\"
				data-levelonereqs=\"$qualLevel1Reqs\"
				data-leveltworeqs=\"$qualLevel2Reqs\"
				data-levelthreereqs=\"$qualLevel3Reqs\"
			>
		";
		
		$displayQualifications .= "
				<div class=\"PayGradeDetails_Entry_Header\" style=\"
					cursor: pointer;
					vertical-align: middle;
					margin-top: 6px;
					display: table;
					padding-bottom: 4px;
				\">
					<img class=\"shipyard_mainTable_row_header_arrow\" style=\"display: table-cell;\" src=\"../images/misc/SC_Button01.png\" align=\"middle\">
					<div class=\"player_qual_row_name\" style=\"
						margin-top:8px;
						margin-bottom:8px;
						padding-left:8px;
						display: table-cell;
						vertical-align: middle;
					\">
						$qualCategory
						<br />
						<strong>$qualName</strong>
					</div>
				</div>
				<div class=\"shipyard_mainTable_row_content\" style=\"
					padding-top:0px;
					width: 100%;
				\">
					<div class=\"shipDetails_ownerInfo_tableRow_inner\" style=\"
						display: inline-block;
						padding: 8px;
					\">
						<div class=\"shipDetails_ownerInfo_tableRow_ImgContainer\" style=\"
							height: 38px;
							width: 38px;
							padding-left: 0px;
							padding-right: 0px;
						\">
							<div class=\"corner corner-top-left\">
							</div>
							<div class=\"corner corner-top-right\">
							</div>
							<div class=\"corner corner-bottom-left\">
							</div>
							<div class=\"corner corner-bottom-right\">
							</div>
							<img class=\"divinfo_rankImg\" align=\"center\" style=\"height:30px;width:30px;\"src=\"../images/qualifications/$qualImage\" />					
						</div>
					</div>
					<div class=\"player_qual_row_name\" style=\"
						margin-bottom:8px;
						padding-left:8px;
					\">
						Status
						<br />
						<strong>$qualIsActiveDisplay</strong>
					</div>				
					<h4 style=\"
						padding: 0px 8px 0px 8px;
						margin-left: 0;
						font-size: 12pt;
					\">
						Requirements
					</h4>
					<div class=\"qual_reqs\" style=\"
						padding-left: 12px;
						font-size: 9pt;
						font-style: italic;
					\">
						<div id=\"qual_reqs_entry-$qualID-level1\" class=\"qual_reqs_entry\">
							<strong>Level 1</strong>
							<p style=\"
								font-size: 9pt;
								font-style: italic;
								color: #DDD;
								margin-bottom: 4px;
							\">
								$qualLevel1ReqsDisplay
							</p>
						</div>
						<div id=\"qual_reqs_entry-$qualID-level2\" class=\"qual_reqs_entry\">
							<strong>Level 2</strong>
							<p style=\"
								font-size: 9pt;
								font-style: italic;
								color: #DDD;
								margin-bottom: 4px;
							\">
								$qualLevel2ReqsDisplay
							</p>
						</div>
						<div id=\"qual_reqs_entry-$qualID-level3\" class=\"qual_reqs_entry\">
							<strong>Level 3</strong>
							<p style=\"
								font-size: 9pt;
								font-style: italic;
								color: #DDD;
								margin-bottom: 4px;
							\">
								$qualLevel3ReqsDisplay
							</p>
						</div>
					</div>
				</div>
			</div>
		";
	}
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">
</script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<!--Script to Show/Hide Rows when Arrows are clicked on each row-->
<script language="javascript">
    $(document).ready(function () {
        $(".shipyard_mainTable_row_content").hide();
		//$(".shipyard_mainTable_row_header_arrow").addClass('rotate90CW');
		
        $(".PayGradeDetails_Entry_Header").click(function () {
            $(this).parent().find(".shipyard_mainTable_row_content").slideToggle(500);
			$(this).find('.shipyard_mainTable_row_header_arrow').toggleClass('rotate90CW');
			$(this).find('.OperationText_Hideable').toggleClass('hidden');
        });		
    });
</script>

<br />
<div class="div_filters_container">
	<div class="div_filters_entry">
		<a href="?page=wiki">&#8672; Back to Wiki Home</a>
	</div>
</div>
<h2 id="MainPageHeaderText">Qualifications</h2>
<div id="TEXT">
	<div id="adminManuTableContainer" class="adminTableContainer">
		<div id="adminQualificationsTable" class="unit_description_container">
			<div class="top-line">
			</div>
			<div class="corner4 corner-diag-blue-topLeft">
			</div>
			<div class="corner4 corner-diag-blue-topRight">
			</div>
			<div class="corner4 corner-diag-blue-bottomLeft">
			</div>
			<div class="corner4 corner-diag-blue-bottomRight">
			</div>
			<div class="PayGradeDetails">
				<? echo $displayQualifications ?>
			</div>
		</div>
		
	</div>
</div>