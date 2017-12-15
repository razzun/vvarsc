<?php include_once('../functions/function_auth_user.php'); ?>

<?php 
	$awards_query = "
		select
			a.AwardID
			,a.AwardName
			,a.AwardImage
			,a.IsActive
			,a.IsSingular
			,a.AwardRequirements
			,a.AwardOrderBy
            ,COUNT(distinct ma.MemberID) as AwardedCount
		from projectx_vvarsc2.Awards a
        left join projectx_vvarsc2.member_Awards ma
			on ma.AwardID = a.AwardID
		where a.IsActive = 1
		group by
			a.AwardID
			,a.AwardName
			,a.AwardImage
			,a.IsActive
			,a.IsSingular
			,a.AwardRequirements
			,a.AwardOrderBy
		order by
			a.AwardOrderBy
			,a.AwardName
	";
	
	$awards_query_results = $connection->query($awards_query);
	$displayAwards = "";
	
	while(($row = $awards_query_results->fetch_assoc()) != false)
	{
		$awardID = $row['AwardID'];
		$awardName = $row['AwardName'];
		$awardImage = $row['AwardImage'];
		$awardIsActive = $row['IsActive'];
		$awardIsSingular = $row['IsSingular'];
		$awardReqs = $row['AwardRequirements'];
		$awardOrderBy = $row['AwardOrderBy'];
		$awardedCount = $row['AwardedCount'];
		
		$awardIsActiveDisplay = "";
		$awardReqsDisplay = "";
		
		if ($awardIsActive == 1)
			$awardIsActiveDisplay = "Active";
		else
			$awardIsActiveDisplay = "Inactive";
			
		if ($awardIsSingular == 1)
			$awardIsSingularDisplay = "Yes";
		else
			$awardIsSingularDisplay = "No";
		
		if ($awardReqs == null || $awardReqs == "")
			$awardReqsDisplay = "-- no requirements found --";
		else
			$awardReqsDisplay = $awardReqs;
	
		$displayAwards .= "
			<div class=\"table_header_block\">
			</div>
			<div class=\"yard_filters\" style=\"margin-bottom: 16px;\"
				data-id=\"$awardID\"
				data-name=\"$awardName\"
				data-imageurl=\"$awardImage\"
				data-isactive=\"$awardIsActive\"
				data-reqs=\"$awardReqs\"
				data-orderby=\"$awardOrderBy\"
			>
		";
		
		$displayAwards .= "
				<div class=\"PayGradeDetails_Entry_Header\" style=\"
					vertical-align: middle;
					margin-top: 6px;
					display: table;
					padding-bottom: 4px;
				\">
					<div class=\"player_qual_row_name\" style=\"
						margin-top:8px;
						margin-bottom:8px;
						padding-left:8px;
						display: table-cell;
						vertical-align: middle;
					\">
						<strong>$awardName</strong>
						<br />
					</div>
				</div>
				<div style=\"
					padding-left: 8px;
					font-size: 8pt;
					font-style: italic;
				\">
					Recipients: $awardedCount</br>
					One-Time-Award: $awardIsSingularDisplay
				</div>
				<div class=\"shipyard_mainTable_row_content\" style=\"
					padding-top:0px;
					width: 100%;
					border-top: none;
				\">
					<div class=\"shipDetails_ownerInfo_tableRow_inner\" style=\"
						display: inline-block;
						padding: 8px;
					\">
						<div class=\"shipDetails_ownerInfo_tableRow_ImgContainer\" style=\"
							height: 30px;
							width: 88px;
							padding-left: 0px;
							padding-right: 0px;
							vertical-align: middle;
						\">
							<div class=\"corner corner-top-left\">
							</div>
							<div class=\"corner corner-top-right\">
							</div>
							<div class=\"corner corner-bottom-left\">
							</div>
							<div class=\"corner corner-bottom-right\">
							</div>
							<img class=\"divinfo_rankImg\" align=\"center\" style=\"height:22px;width:80px;vertical-align:middle;\"src=\"../images/awards/$awardImage\" />					
						</div>
					</div>
					<div class=\"qual_reqs\" style=\"
						padding-left: 12px;
						font-size: 9pt;
						font-style: italic;
					\">
						<div id=\"qual_reqs_entry-$qualID-level1\" class=\"qual_reqs_entry\">
							<p style=\"
								font-size: 9pt;
								font-style: italic;
								color: #DDD;
								margin-bottom: 4px;
							\">
								$awardReqsDisplay
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

<h2 id="MainPageHeaderText">Awards</h2>
<div id="TEXT">
	<div id="adminManuTableContainer" class="adminTableContainer">
		<div class="div_filters_container">
			<div class="div_filters_entry">
				<a href="?page=wiki">&#8672; Back to Wiki Home</a>
			</div>
		</div>
		<br />
		<div id="adminawardsTable" class="unit_description_container">
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
				<? echo $displayAwards ?>
			</div>
		</div>
	</div>
</div>