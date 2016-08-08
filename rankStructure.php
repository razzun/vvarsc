<?php include_once('functions/function_auth_user.php'); ?>
<?
	$enlistedRanksQuery = "
		select distinct
			r.rank_level
			,(select rank_abbr from projectx_vvarsc2.ranks r2 where r2.rank_level = r.rank_level and r2.rank_type = 'Navy') as 'rank_abbr_navy'
			,(select rank_name from projectx_vvarsc2.ranks r2 where r2.rank_level = r.rank_level and r2.rank_type = 'Navy') as 'rank_name_navy'
			,(select rank_tinyImage from projectx_vvarsc2.ranks r2 where r2.rank_level = r.rank_level and r2.rank_type = 'Navy') as 'rank_tinyImage_navy'
			,(select rank_abbr from projectx_vvarsc2.ranks r2 where r2.rank_level = r.rank_level and r2.rank_type = 'USMC') as 'rank_abbr_usmc'
			,(select rank_name from projectx_vvarsc2.ranks r2 where r2.rank_level = r.rank_level and r2.rank_type = 'USMC') as 'rank_name_usmc'
			,(select rank_tinyImage from projectx_vvarsc2.ranks r2 where r2.rank_level = r.rank_level and r2.rank_type = 'USMC') as 'rank_tinyImage_usmc'
			,(select distinct rank_groupName from projectx_vvarsc2.ranks r2 where r2.rank_level = r.rank_level) as 'rank_groupName'
		from projectx_vvarsc2.ranks r
		where r.rank_level like 'E-%'
		order by
			r.rank_orderby desc	
	";
	
	$enlistedTable = "
		<table class=\"adminTable\" style=\"width: auto; display: table-row\">
			<tr class=\"adminTableHeaderRow\">
				<th class=\"adminTableHeaderRowTD\">
					PayGrade
				</th>
				<th class=\"adminTableHeaderRowTD\">
					Navy Rank
				</th>
				<th class=\"adminTableHeaderRowTD\">
					Marine Rank
				</th>
				<th class=\"adminTableHeaderRowTD\">
					RSI Organization Rank
				</th>
			</tr>	
	";	

	$enlistedResult = $connection->query($enlistedRanksQuery);
	while(($row1 = $enlistedResult->fetch_assoc()) != false) {
		$rank_level = $row1['rank_level'];
		$rank_abbr_navy = $row1['rank_abbr_navy'];
		$rank_name_navy = $row1['rank_name_navy'];
		$rank_tinyImage_navy = $row1['rank_tinyImage_navy'];
		$rank_abbr_usmc = $row1['rank_abbr_usmc'];
		$rank_name_usmc = $row1['rank_name_usmc'];
		$rank_tinyImage_usmc = $row1['rank_tinyImage_usmc'];
		$rank_groupName = $row1['rank_groupName'];
		
		$rankNameNavy = "- not used -";
		if ($rank_abbr_navy != null && $rank_name_navy != null)
		{
			$rankNameNavy = "$rank_abbr_navy - $rank_name_navy";
		}
		$rankNameUSMC = "- not used -";
		if ($rank_abbr_usmc != null && $rank_name_usmc != null)
		{
			$rankNameUSMC = "$rank_abbr_usmc - $rank_name_usmc";
		}		
		
		$enlistedTable .= "
			<tr class=\"adminTableRow\">
				<td class=\"adminTableRowTD\">
					$rank_level
				</td>
				<td class=\"adminTableRowTD\">
					<div class=\"clickableRow_memRank_inner\">
						<img class=\"clickableRow_memRank_Image\" src=\"http://sc.vvarmachine.com/images/ranks/TS3/$rank_tinyImage_navy.png\" />
						<div class=\"rank_image_text\">
							$rankNameNavy
						</div>
					</div>
				</td>
				<td class=\"adminTableRowTD\" style=\"text-align: left\">
					<div class=\"clickableRow_memRank_inner\">
						<img class=\"clickableRow_memRank_Image\" src=\"http://sc.vvarmachine.com/images/ranks/TS3/$rank_tinyImage_usmc.png\" />
						<div class=\"rank_image_text\">
							$rankNameUSMC
						</div>
					</div>
				</td>
				<td class=\"adminTableRowTD\" style=\"text-align: left\">
					$rank_groupName
				</td>						
			</tr>			
		";
	}
	
	$enlistedTable .= "
		</table>	
	";
	
	$officerRanksQuery = "
		select distinct
			r.rank_level
			,(select rank_abbr from projectx_vvarsc2.ranks r2 where r2.rank_level = r.rank_level and r2.rank_type = 'Navy') as 'rank_abbr_navy'
			,(select rank_name from projectx_vvarsc2.ranks r2 where r2.rank_level = r.rank_level and r2.rank_type = 'Navy') as 'rank_name_navy'
			,(select rank_tinyImage from projectx_vvarsc2.ranks r2 where r2.rank_level = r.rank_level and r2.rank_type = 'Navy') as 'rank_tinyImage_navy'
			,(select rank_abbr from projectx_vvarsc2.ranks r2 where r2.rank_level = r.rank_level and r2.rank_type = 'Common') as 'rank_abbr_common'
			,(select rank_name from projectx_vvarsc2.ranks r2 where r2.rank_level = r.rank_level and r2.rank_type = 'Common') as 'rank_name_common'
			,(select rank_tinyImage from projectx_vvarsc2.ranks r2 where r2.rank_level = r.rank_level and r2.rank_type = 'Common') as 'rank_tinyImage_common'
			,(select distinct rank_groupName from projectx_vvarsc2.ranks r2 where r2.rank_level = r.rank_level) as 'rank_groupName'
		from projectx_vvarsc2.ranks r
		where r.rank_level like 'O-%'
		order by
			r.rank_orderby desc	
	";
	
	$officerTable = "
		<table class=\"adminTable\" style=\"width: auto; display: table-row\">
			<tr class=\"adminTableHeaderRow\">
				<th class=\"adminTableHeaderRowTD\">
					PayGrade
				</th>
				<th class=\"adminTableHeaderRowTD\">
					Navy Rank
				</th>
				<th class=\"adminTableHeaderRowTD\">
					Marine Rank
				</th>
				<th class=\"adminTableHeaderRowTD\">
					RSI Organization Rank
				</th>
			</tr>	
	";	

	$officerResult = $connection->query($officerRanksQuery);
	while(($row1 = $officerResult->fetch_assoc()) != false) {
		$rank_level = $row1['rank_level'];
		$rank_abbr_navy = $row1['rank_abbr_navy'];
		$rank_name_navy = $row1['rank_name_navy'];
		$rank_tinyImage_navy = $row1['rank_tinyImage_navy'];
		$rank_abbr_common = $row1['rank_abbr_common'];
		$rank_name_common = $row1['rank_name_common'];
		$rank_tinyImage_common = $row1['rank_tinyImage_common'];
		$rank_groupName = $row1['rank_groupName'];
		
		$rankNameNavy = "- not used- ";
		if ($rank_abbr_navy != null && $rank_name_navy != null)
		{
			$rankNameNavy = "$rank_abbr_navy - $rank_name_navy";
		}
		$rankNameCommon = "- not used -";
		if ($rank_abbr_common != null && $rank_name_common!= null)
		{
			$rankNameCommon = "$rank_abbr_common - $rank_name_common";
		}			
		
		if ($rank_tinyImage_common == null)
			$rank_tinyImage_common = $rank_tinyImage_navy;
		
		$officerTable .= "
			<tr class=\"adminTableRow\">
				<td class=\"adminTableRowTD\">
					$rank_level
				</td>
				<td class=\"adminTableRowTD\">
					<div class=\"clickableRow_memRank_inner\">
						<img class=\"clickableRow_memRank_Image\" src=\"http://sc.vvarmachine.com/images/ranks/TS3/$rank_tinyImage_navy.png\" />
						<div class=\"rank_image_text\">
							$rankNameNavy
						</div>
					</div>
				</td>
				<td class=\"adminTableRowTD\" style=\"text-align: left\">
					<div class=\"clickableRow_memRank_inner\">
						<img class=\"clickableRow_memRank_Image\" src=\"http://sc.vvarmachine.com/images/ranks/TS3/$rank_tinyImage_common.png\" />
						<div class=\"rank_image_text\">
							$rankNameCommon
						</div>
					</div>
				</td>
				<td class=\"adminTableRowTD\" style=\"text-align: left\">
					$rank_groupName
				</td>						
			</tr>			
		";
	}
	
	$officerTable .= "
		</table>	
	";	
	
	$connection->close();

?>

<h2>Fleet Rank and Unit Structure</h2>
<div id="TEXT">

	<div id="rankStructure_main">
		<div class="table_header_block">
		</div>
		<div class="unit_details_container">
			<p style="text-align: center; font-style: italic">
				The VVarMachine Combined Fleet uses a Hierarchical Military Unit Structure and Ranking System, with design elements incorporated from the organization of the US Navy, AirForce, and Marines.
			</p>
			<div class="shipDetails_info1_table_ship_desc" style="font-style: normal">
				<div class="corner corner-top-left">
				</div>
				<div class="corner corner-top-right">
				</div>
				<div class="corner corner-bottom-left">
				</div>
				<div class="corner corner-bottom-right">
				</div>
				<h3 style="padding-left: 4px; margin-left: 0">Fleet Ranks and PayGrades</h3>
				In armed-forces around the world, a member's rank is defined by a <strong>PayGrade</strong>, and a <strong>Role</strong> that the member performs. As the member becomes more proficient in the skills needed to perform that Role, he or she can receive promotion to a higher PayGrade. When the member is sufficiently qualified in their speciality, and has reached the appropriate PayGrade, they can be chosen to lead and develop the skills of lower-grade members in their field. PayGrades are divided into three groups:
				<br />
				<ul>
					<li style="color: #FFF; font-style: italic">
						<a href="#rankDetails_enlisted">Enlisted Ranks</a>
					</li>
					<li style="color: #FFF; font-style: italic">Warrant-Officer Ranks (not used in the VVarMachine Fleet)</li>
					<li style="color: #FFF; font-style: italic">
						<a href="#rankDetails_officer">Officer Ranks</a>
					</li>
				</ul>
				Because there are far more PayGrades than there are supported-ranks in the RSI Organization Structure, each group of PayGrades can be divided further into sub-categories. These sub-categories relate directly to the six rank-levels of the RSI Organization Structure.
				<h4 style="padding-left: 8px; margin-left: 0; font-size: 12pt">Enlisted Ranks</h4>
				<? echo $enlistedTable ?>
				<h4 style="padding-left: 8px; margin-left: 0; font-size: 12pt">Officer Ranks</h4>
				<? echo $officerTable ?>
				<br />
				Military Flight Units and Division/Fleet Command utilize the Navy Rank Names and Images. Marine Names and Images are used by dedicated groud-combat units, along with Quick Reaction Force (QRF) Units.
				<br />
				<br />
				Details about each PayGrade can be found below, including the requirements for advancement from one to the next.
			</div>
		</div>
		<br />
		<br />
		<?include_once("ranks/rankDetails_enlisted.php");?>
		<br />
		<br />
		<?include_once("ranks/rankDetails_officer.php");?>
	</div>
	
</div>