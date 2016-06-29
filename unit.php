<?
	$UnitID = strip_tags(isset($_GET[pid]) ? $_GET[pid] : '');
	
	function generate_list($array,$parent,$level,$realLevel)
	{
		foreach ($array as $value)
		{
			$has_children=false;
			if ($value['ParentUnitID']==$parent)
			{
				if ($has_children==false)
				{
					$has_children=true;
					echo '<div class="unitHierarchy level'.$level.'" style="margin-left: '.($realLevel * 8).'px;">';
					$level++;
					$realLevel++;
				}

				#Unit Header
				echo '<div class="unitHierarchyHeader">';
					
					if ($value['DivisionName'] == "Economy")
					{
						if ($value['UnitLevel'] == "Division")
						{
							echo '<img class="unitHierarchy_row_header_arrow" align="center" src="http://vvarmachine.com/uploads/galleries/SC_Button01.png" />';
						}
						else
						{
							echo '<div class="unitHierarchy_row_header_arrow_empty">';
							echo '</div>';
						}
					}
					else if ($value['DivisionName'] == "Military")
					{
						#If This Is Lowest-Level Unit, Don't Display Expand-Arrow
						if ($value['UnitLevel'] != "Squadron" && $value['UnitLevel'] != "Platoon")
						{
							echo '<img class="unitHierarchy_row_header_arrow" align="center" src="http://vvarmachine.com/uploads/galleries/SC_Button01.png" />';
						}
						else
						{
							echo '<div class="unitHierarchy_row_header_arrow_empty">';
							echo '</div>';
						}
					}
					else
					{
						echo '<div class="unitHierarchy_row_header_arrow_empty">';
						echo '</div>';					
					}
					
					echo '<div class="unitHierarchyHeader_mainContainer">';
					
						echo '<div class="unitHierarchyHeader_textContainer">';
							if ($value['IsActive'] == "Active")
							{
								echo '<div class="unitHierarchyHeader_unitName">';
									echo '<a href="http://sc.vvarmachine.com/unit/'.$value['UnitID'].'" target="_top">'.$value['UnitName'].'</a>';
								echo '</div>';				
							}
							else
							{
								echo '<div class="unitHierarchyHeader_unitName inactive">';
									echo '<a href="http://sc.vvarmachine.com/unit/'.$value['UnitID'].'" target="_top">'.$value['UnitName'].'</a>';
								echo '</div>';
							}
							
							#CreatedOn Div for Active Units (not Team,Flight,Division,Fleet)
							if($value['UnitLevel'] != "Division" && 
								$value['UnitLevel'] != "Fleet")
							{
								if ($value['IsActive'] == "Active")
								{
									echo '<div class="unitHierarchyHeader_unitDate">';
										echo 'Established '.$value['UnitCreatedOn'];
									echo '</div>';
								}
							}
							

							#Unit Commander (not applied to Inactive units, or Economy "Group" within the Division
							echo '<div class="unitHierarchyHeader_unitCO">';
								echo '<div class="unitHierarchyHeader_key">';
									if ($value['UnitLevel'] == "Group" && $value['DivisionName'] == "Economy")
									{
										echo '';
									}
									else if ($value['IsActive'] == "Active")
									{
										echo 'Commanding Officer';
									}
								echo '</div>';
								
								echo '<div class="unitHierarchyHeader_value">';
									echo '<div class="unitHierarchyHeader_value_container">';
									if ($value['UnitLevel'] == "Group" && $value['DivisionName'] == "Economy")
									{
										echo '';
									}
									else if ($value['IsActive'] == "Active")
									{										
										if ($value['UnitLeaderID'] != null)
										{
											echo '<img class="unitHierarchyContent_rankTinyImage" align="center" src="http://sc.vvarmachine.com/images/ranks/TS3/navy/'.$value['LeadeRankImage'].'.png" />';
											echo '<a href="http://sc.vvarmachine.com/player/'.$value['UnitLeaderID'].'" target="_top">'.$value['LeaderRankAbbr'].' '.$value['UnitLeaderName'].'</a>';
										}
										else
											echo '- No Leader Assigned -';
									}
									echo '</div>';
								echo '</div>';
							echo '</div>';	
						echo '</div>';
					echo '</div>';
				echo '</div>';
				
				#Child-Units
				echo '<div class="unitHierarchyChildren">';
				generate_list($array,$value['UnitID'],$level,$realLevel);
				echo '</div>';
			}

			if ($has_children==true)
			{
				echo '</div>';
				$level--;
				$realLevel--;
			}
		}
	}
	
	if(is_numeric($UnitID)) {
		$unit_query = "select
							IFNULL(u.UnitFullName,u.UnitName) as UnitName
							,u.UnitDepth
							,u.UnitDescription
							,u.ParentUnitID
							,u.UnitSlogan
							,u.UnitBackgroundImage
							,d.div_name
							,m.mem_id
							,m.mem_name
							,r.rank_name
							,r.rank_abbr
							,r.rank_image
							,r.rank_tinyImage
							,r.rank_level
							,r.rank_groupName
							,IFNULL(r2.role_shortName,r2.role_name) as RoleName
							,DATE_FORMAT(DATE(um.CreatedOn),'%d %b %Y') as MemberAssigned
						from projectx_vvarsc2.Units u
						left join projectx_vvarsc2.UnitMembers um
							on um.UnitID = u.UnitID
						left join projectx_vvarsc2.members m
							on m.mem_id = um.MemberID
						left join projectx_vvarsc2.ranks r
							on r.rank_id = m.ranks_rank_id
						left join projectx_vvarsc2.roles r2
							on r2.role_id = um.MemberRoleID
						left join projectx_vvarsc2.divisions d
							on d.div_id = u.DivisionID
						where u.UnitID = $UnitID
						order by
							r.rank_orderby
							,m.mem_name";	
		
		$unit_query_result = $connection->query($unit_query);
		
		while(($row1 = $unit_query_result->fetch_assoc()) != false) {
		
			$depth = $row1['UnitDepth'];
			$divName = $row1['div_name'];
			$unitName = $row1['UnitName'];
			$unitSlogan = $row1['UnitSlogan'];
			$unitDescription = $row1['UnitDescription'];
			$unitBackgroundImage = $row1['UnitBackgroundImage'];
			$parentUnitID = $row1['ParentUnitID'];
			$rank_abbr = $row1['rank_abbr'];
			$rank_image = $row1['rank_image'];
			$rank_tinyImage = $row1['rank_tinyImage'];
			$rank_level = $row1['rank_level'];
			$rank_groupName = $row1['rank_groupName'];
			$mem_name = $row1['mem_name'];
			$mem_role = $row1['RoleName'];
			$mem_assigned_date = $row1['MemberAssigned'];
			$mem_id = $row1['mem_id'];
			
			if ($unitBackgroundImage == null)
			{
				$unitBackgroundImage = 'http://vvarmachine.com/uploads/galleries/Gladius_01.jpg';
			}
			
			if ($mem_id != null)
			{
				$display_members .="
					<tr class=\"shipDetails_ownerInfo_tableRow\">
						<td class=\"shipDetails_ownerInfo_tableRow_inner\">
							<div class=\"shipDetails_ownerInfo_tableRow_ImgContainer\">
								<div class=\"corner corner-top-left\">
								</div>
								<div class=\"corner corner-top-right\">
								</div>
								<div class=\"corner corner-bottom-left\">
								</div>
								<div class=\"corner corner-bottom-right\">
								</div>
								<img class=\"divinfo_rankImg\" align=\"center\" alt=\"$rank_abbr\" src=\"http://sc.vvarmachine.com/images/ranks/$rank_image.png\" />
							</div>
							<div class=\"shipDetails_ownerInfo_tableRow_memInfoContainer\">
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo1\">
									<a href=\"http://sc.vvarmachine.com/player/$mem_id\" target=\"_top\">$mem_name</a>
								</div>
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo2\">
									- $rank_groupName
								</div>
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo3\">
									- $mem_role
								</div>
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo4\">
									Assigned $mem_assigned_date
								</div>
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo5\">
									$rank_abbr // $rank_level
								</div>
							</div>
						</td>
					</tr>
				";
			}
			else
			{
				$display_members .="
					<tr class=\"shipDetails_ownerInfo_tableRow\">
						<td class=\"shipDetails_ownerInfo_tableRow_inner\">
							- No Assigned Personnel -
						</td>
					</tr>
				";				
			}
		}
		
		if ($depth < 5)
		{
			if ($unitDescription == null)
			{
				$displayUnitDescription1 .= "
					<h3>
						Description
					</h3>
					<div class=\"table_header_block\">
					</div>
					<div class=\"unit_description_container\">
				";
						
				$displayUnitDescription2 .= "
					</div>
				";
				
				$displayUnitDescriptionContent = "- No Description Found -";
			}
			else
			{				
				$displayUnitDescription1 .= "
					<h3>
						Description
					</h3>
					<div class=\"table_header_block\">
					</div>
					<div class=\"unit_description_container\">
				";
						
				$displayUnitDescription2 .= "
					</div>
				";
				
				$displayUnitDescriptionContent = $unitDescription;
			}
		}
		else
		{
			$displayUnitDescription1 = "";
			$displayUnitDescription2 = "";
		}		
		
		$units_query = "select
							u.UnitID
							,u.UnitName
							,u.UnitShortName
							,u.UnitCallsign
							,u.DivisionID
							,d.div_name
							,u.IsActive
							,CASE
								when u.IsActive = 1 then 'Active'
								else 'Inactive'
							end as IsActive
							,u.UnitLevel
							,u.ParentUnitID
							,DATE_FORMAT(DATE(u.CreatedOn),'%d %b %Y') as UnitCreatedOn
							,m.mem_id as UnitLeaderID
							,m.mem_name as UnitLeaderName
							,m.rank_tinyImage as LeadeRankImage
							,m.rank_abbr as LeaderRankAbbr
						from projectx_vvarsc2.Units u
						left join (
							select
								m.mem_name
								,m.mem_id
								,r.rank_tinyImage
								,r.rank_abbr
							from projectx_vvarsc2.members m
							join projectx_vvarsc2.ranks r
								on r.rank_id = m.ranks_rank_id
						) m
							on m.mem_id = u.UnitLeaderID
						left join projectx_vvarsc2.divisions d
							on d.div_id = u.DivisionID
						order by
							u.UnitID";	
		
		$units_query_results = $connection->query($units_query);
		
		while(($row = $units_query_results->fetch_assoc()) != false) {
		
			$units[$row['UnitID']] = array(
				'UnitID' => $row['UnitID']
				,'UnitName' => $row['UnitName']
				,'UnitShortName' => $row['UnitShortName']
				,'UnitCallsign' => $row['UnitCallsign']
				,'DivisionID' => $row['DivisionID']
				,'DivisionName' => $row['div_name']
				,'IsActive' => $row['IsActive']
				,'UnitLevel' => $row['UnitLevel']
				,'ParentUnitID' => $row['ParentUnitID']
				,'UnitCreatedOn' => $row['UnitCreatedOn']
				,'UnitLeaderName' => $row['UnitLeaderName']
				,'UnitLeaderID' => $row['UnitLeaderID']
				,'LeadeRankImage' => $row['LeadeRankImage']
				,'LeaderRankAbbr' => $row['LeaderRankAbbr']
			);
		}
		
		$connection->close();
		
		if (($divName == "Military" && $depth < 4)
			|| ($divName == "Economy" && $depth < 2)
			|| $divName == "Command")
		{
				$displayChildren1 .= "
				<br />
				<h3>
					Subordinate Units
				</h3>
				<div class=\"table_header_block\">
				</div>
				<div class=\"units_tree_container\">";
				
				$displayChildren2 .= "
				</div>";
		}
		else
		{
			$displayChildren1 = "";
			$displayChildren2 = "";
		}
		
		if ($parentUnitID == 0)
		{
			$display_selectors .= "
				<div class=\"div_filters_container\">
					<div class=\"div_filters_entry\">
						<a href=\"http://sc.vvarmachine.com/units\">&#8672; Back to Parent</a>
					</div>
				</div>
			";		
		}
		else
		{
			$display_selectors .= "
				<div class=\"div_filters_container\">
					<div class=\"div_filters_entry\">
						<a href=\"http://sc.vvarmachine.com/unit/$parentUnitID\">&#8672; Back to Parent</a>
					</div>
				</div>
			";
		}
		
		
	}
	else
	{
        header("Location: http://sc.vvarmachine.com/units");
    }

?>

<h2>
	<? echo $unitName ?>
</h2>
<h4>
	<? echo $unitSlogan ?>
</h4>
<? echo $display_selectors ?>
<div id="TEXT">

  <!-- Comment -->
	<div class="units_main_container">
		
		<? echo $displayUnitDescription1 ?>
		<? echo nl2br($displayUnitDescriptionContent) ?>
		<? echo $displayUnitDescription2 ?>
		
		<br />
		<h3>
			Unit Personnel
		</h3>
		<div class="table_header_block">
		</div>
		<div class="unit_details_container">
			<table class="shipDetails_ownerInfo_table">
				<?echo $display_members; ?>
			</table>
		</div>
		
		<? echo $displayChildren1 ?>
		<? generate_list($units,$UnitID,($depth + 1),0); ?>
		<? echo $displayChildren2 ?>

	</div>
</div> 
  
<!--Script to Show/Hide Rows when Arrows are clicked on each row-->
<script language="javascript">
    $(document).ready(function () {
		
		<!--Hide All Other Nodes-->
        $(".unitHierarchyChildren").hide();
		
		<!--Open Division Nodes by Default -->
		$(".unitHierarchy.level0").children(".unitHierarchyChildren").show();
		$(".unitHierarchy.level0").children(".unitHierarchyHeader").children(".unitHierarchy_row_header_arrow").toggleClass('rotate90CW');
		
		<!--Open Inactive Nodes by Default->>
		$(".unitHierarchyHeader_unitName.inactive").parent().parent().parent().parent().children(".unitHierarchyChildren").show();
		$(".unitHierarchyHeader_unitName.inactive").parent().parent().parent().children(".unitHierarchy_row_header_arrow").toggleClass('rotate90CW');
		
        $(".unitHierarchy_row_header_arrow").click(function () {
		
			var websiteClass = $('#TEXT');
			var currentContentHeight = websiteClass.height();
			
            $(this).parent().parent().children(".unitHierarchyChildren").slideToggle(500);
			$(this).toggleClass('rotate90CW');
			
			setTimeout(function(){	
				resizeSpacer();
			}.bind(this), 500); 
        });
		
		function resizeSpacer()
		{
			//Script to adjust dynamic spacer height
			var height = $(window).height();
			//var newContentHeight = websiteClass.height();
			
			var footer = $('#FOOTER');
			var fHeight = footer.height();
			
			var spacerClass = $('#dynamicSpacer');
			//var currentSpacerHeight= spacerClass.height();
			
			var minHeight = (height - $('#CONTENT').offset().top - 8);
			var newSpacerHeight = (minHeight - spacerClass.offset().top - fHeight + 20);
			
			spacerClass.css({
				"height": newSpacerHeight +'px'
			});			
		}
    });
</script>

<!-- Script for changing background image-->
<script>
	$(document).ready(function() {
		$('body').css('background','url(<?php echo $unitBackgroundImage ?>) no-repeat fixed center center transparent');
	});
</script>