<?php include_once('functions/function_auth_user.php'); ?>

<?
	function generate_list($array,$parent,$level)
	{
		foreach ($array as $value)
		{
			$has_children=false;
			if ($value['ParentUnitID']==$parent)
			{
				if ($has_children==false)
				{
					$has_children=true;
					echo '<div class="unitHierarchy level'.$level.'" style="margin-left: '.($level * 8).'px;">';
					$level++;
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
				generate_list($array,$value['UnitID'],$level);
				echo '</div>';
			}

			if ($has_children==true)
			{
				echo '</div>';
				$level--;
			}
		}
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
?>

<h2>Fleet Unit Structure</h2>
<div id="TEXT">

  <!-- Comment -->
	<div class="units_main_container">
		<div class="table_header_block">
		</div>
		<div class="units_tree_container">
			<? generate_list($units,0,0); ?>
		</div>
	</div>
</div> 
  
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">
</script>
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