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
					echo '<div class="unitHierarchy level'.$level.' UnitLevel'.$value['UnitLevel'].'" style="margin-left: '.($level * 8).'px;">';
					$level++;
				}

				#Unit Header
				echo '<div class="unitHierarchyHeader">';
					
					if ($value['DivisionName'] == "Economy")
					{
						if ($value['UnitLevel'] == "Division")
						{
							echo '<div class="unitHierarchy_arrowContainer" style="display:table-cell; vertical-align:middle;">';
								echo '<img class="unitHierarchy_row_header_arrow" align="center" src="http://vvarmachine.com/uploads/galleries/SC_Button01.png" />';
							echo '</div>';
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
						if ($value['UnitLevel'] != "Squadron" && $value['UnitLevel'] != "Platoon" && $value['UnitLevel'] != "QRF" && $value['UnitLevel'] != "Department")
						{
							echo '<div class="unitHierarchy_arrowContainer" style="display:table-cell; vertical-align:middle;">';
								echo '<img class="unitHierarchy_row_header_arrow" align="center" src="http://vvarmachine.com/uploads/galleries/SC_Button01.png" />';
							echo '</div>';
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
					
					//Unit Image//
					if ($value['UnitLevel'] != "Department")
					{
						$temp_unitEmblemImage = "";
						if ($value['UnitEmblemImage'] == null || $value['UnitEmblemImage'] == "")
							$temp_unitEmblemImage = "http://vvarmachine.com/uploads/galleries/03KgFv0_med.png";
						else
							$temp_unitEmblemImage = $value['UnitEmblemImage'];
							
						echo '<div class="shipDetails_ownerInfo_tableRow_ImgContainer" style="height: 38px;	width: 38px; padding-left:8px; padding-right:0px; padding-top:2px; padding-bottom:2px; border:none; background:none;">';
						
						if ($value['IsActive'] == "Active")
							echo '<img class="divinfo_rankImg" align="center" style="height:30px;width:30px;vertical-align: middle;"src="'.$temp_unitEmblemImage.'" />';
						else
							echo '<img class="divinfo_rankImg image_inactive" align="center" style="height:30px;width:30px;vertical-align: middle;"src="'.$temp_unitEmblemImage.'" />';
							
						echo '</div>';
					}
					//End Unit Image//
					
					echo '<div class="unitHierarchyHeader_mainContainer">';
					
								/*
								echo '<div>';
									echo '<img src="'.$value['UnitEmblemImage'].'" height="40px" />';
								echo '</div>';
								*/
						echo '<div class="unitHierarchyHeader_textContainer">';
							if ($value['IsActive'] == "Active")
							{
								echo '<div class="unitHierarchyHeader_unitName">';
									echo '<a href="'.$link_base.'/unit/'.$value['UnitID'].'" target="_top">'.$value['UnitName'].'</a>';
								echo '</div>';				
							}
							else
							{
								echo '<div class="unitHierarchyHeader_unitName inactive">';
									echo '<a href="'.$link_base.'/unit/'.$value['UnitID'].'" target="_top">'.$value['UnitName'].'</a>';
								echo '</div>';
							}
							
							#CreatedOn Div for Active Units (not Team,Flight,Division,Fleet)
							/*
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
							*/
							
							#Unit Commander (not applied to Economy "Group" within the Division
							echo '<div class="unitHierarchyHeader_unitCO">';
								echo '<div class="unitHierarchyHeader_key">';
									if ($value['UnitLevel'] == "Group" && $value['DivisionName'] == "Economy")
									{
										echo '';
									}
									else if ($value['IsActive'] == "Active"
										&& ($value['UnitLeaderID'] != null && $value['UnitLeaderID'] != '')
										&& $value['UnitLevel'] == "Squadron"
									)
									{
										echo 'Squadron Leader';
									}
									else if ($value['IsActive'] == "Active"
										&& ($value['UnitLeaderID'] != null && $value['UnitLeaderID'] != '')
										&& $value['UnitLevel'] == "Platoon"
									)
									{
										echo 'Platoon Leader';
									}
									else if ($value['IsActive'] == "Active" && ($value['UnitLeaderID'] != null && $value['UnitLeaderID'] != ''))
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
										if ($value['UnitLeaderID'] != null && $value['UnitLeaderID'] != '')
										{
											echo '<img class="unitHierarchyContent_rankTinyImage" align="center" src="'.$link_base.'/images/ranks/TS3/'.$value['LeadeRankImage'].'.png" />';
											echo '<a href="'.$link_base.'/player/'.$value['UnitLeaderID'].'" target="_top">'.$value['LeaderRankAbbr'].' '.$value['UnitLeaderName'].'</a>';
										}
										else
											echo '';
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
						,u.UnitEmblemImage
						,DATE_FORMAT(DATE(u.CreatedOn),'%d %b %Y') as UnitCreatedOn
						,m.mem_id as UnitLeaderID
						,m.mem_callsign as UnitLeaderName
						,m.rank_tinyImage as LeadeRankImage
						,m.rank_abbr as LeaderRankAbbr
						,case
							when u.UnitLevel = 'Fleet' then 1
							when u.UnitLevel = 'Department' then 2
							when u.UnitLevel = 'Division' then 3
							when u.UnitLevel = 'Group' then 4
							when u.UnitLevel = 'Wing' then 5
							when u.UnitLevel = 'Company' then 5
							when u.UnitLevel = 'Squadron' then 6
							when u.UnitLevel = 'Platoon' then 6
							when u.UnitLevel = 'QRF' then 6
						end as SortOrder
					from projectx_vvarsc2.Units u
					left join (
						select
							m.mem_callsign
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
						17
						,u.UnitName";	
    
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
			,'UnitEmblemImage' => $row['UnitEmblemImage']
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

	/*
		var mainClass = $('#MainWrapper');
		var footerClass = $('#FOOTER');
	
		$('.parallax__layer').css('height', mainClass.height() );
		
		var img = new Image;
		img.src = $('#parallax__layer--back3').css('background-image').replace(/url\(|\)$/ig, "");
		var bgImgWidth = img.width;
		var bgImgHeight = img.height;		
		
		var scaleFactor = mainClass.height() / bgImgHeight;
		
		$('#parallax__layer--back3').css({
			transform: 'translateZ(-4px) scale(5)'
		});
*/
		
		<!--Hide All Other Nodes-->
        $(".unitHierarchyChildren").hide();
		
		<!--Open Division Nodes by Default -->
		$(".unitHierarchy.level0").children(".unitHierarchyChildren").show();
		$(".unitHierarchy.level0").children(".unitHierarchyHeader").children(".unitHierarchy_row_header_arrow").toggleClass('rotate90CW');
		
		<!--Open Inactive Nodes by Default->>
		$(".unitHierarchyHeader_unitName.inactive").parent().parent().parent().parent().children(".unitHierarchyChildren").show();
		$(".unitHierarchyHeader_unitName.inactive").parent().parent().parent().children(".unitHierarchy_arrowContainer").children(".unitHierarchy_row_header_arrow").toggleClass('rotate90CW');
		
        $(".unitHierarchy_row_header_arrow").click(function () {
		
			var websiteClass = $('#TEXT');
			var currentContentHeight = websiteClass.height();
			
            $(this).parent().parent().parent().children(".unitHierarchyChildren").slideToggle(500);
			$(this).toggleClass('rotate90CW');

        });	
    });
</script>