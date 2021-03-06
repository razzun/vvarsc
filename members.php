<?php include_once('functions/function_auth_user.php'); ?>

<?
	$div_id = strip_tags(isset($_GET['pid']) ? $_GET['pid'] : '');
	
	if(is_numeric($div_id)) {
		$div_query = "
		select
			s1.div_name as division
			,s1.rank_groupName as rank
			,s1.rank_groupImage as rank_image
			,s1.rank_groupTinyImage as rank_tinyImage
			,s1.mem_id as mem_id_info
			,s1.mem_status
			,s1.mem_callsign as mem_name_info
			,s1.rank_tinyImage as mem_rank_info
			,s1.mem_avatar_link as mem_avatar_info
			,s1.role_name as mem_role_info
			,s1.div_name as mem_div_info
			,s1.rank_orderby
			,s1.FullTitle
		from
		(
		";
			$div_query .= "
			select distinct
				r.rank_groupName
				,r.rank_group_orderby
				,r.rank_groupImage
				,r.rank_groupTinyImage
				,r.rank_tinyImage
				,r.rank_orderby
				,m.mem_id
				,m.mem_callsign
				,m.mem_avatar_link
				,m.mem_status
				,(
					select
						case
							when r2.isPrivate = 0 and r2.role_shortName = '' then r2.role_name
							when r2.isPrivate = 0 and r2.role_shortName != '' then r2.role_shortName
							else ''
						end as role_name
					from projectx_vvarsc2.UnitMembers um
					left join projectx_vvarsc2.roles r2
						on r2.role_id = um.MemberRoleID
					where um.MemberID = m.mem_id
					order by
						r2.role_orderby
					limit 1
				) as role_name
				,(
					select
						d.div_name
					from projectx_vvarsc2.UnitMembers um
					left join projectx_vvarsc2.roles r2
						on r2.role_id = um.MemberRoleID
					left join projectx_vvarsc2.Units u
						on u.UnitID = um.UnitID
					left join projectx_vvarsc2.divisions d
						on d.div_id = u.DivisionID
					where um.MemberID = m.mem_id
					order by
						r2.role_orderby
					limit 1
				) as div_name
				,(
					select
						CASE
							when rc.RatingCode is not null 
								and ra.rank_suffix is not null then CONCAT(rc.RatingCode, ra.rank_suffix, ' ', m2.mem_callsign) ##For Navy Enlisted, with Unit Override of RatingCode for Role
							when r2.rating_designation is not null 
								and ra.rank_suffix is not null then CONCAT(r2.rating_designation, ra.rank_suffix, ' ', m2.mem_callsign) ##For Navy Enlisted, without Override of RatingCode
							else CONCAT(ra.rank_abbr, ' ', m2.mem_callsign) ##For Officers, un-assigned members, and Enlisted Marines
						end
					from projectx_vvarsc2.members m2
					join projectx_vvarsc2.ranks ra
						on ra.rank_id = m2.ranks_rank_id
					left join projectx_vvarsc2.UnitMembers um
						on m2.mem_id = um.MemberID
					left join projectx_vvarsc2.UnitRoles ur
						on ur.UnitID = um.UnitID
						and ur.RoleID = um.MemberRoleID
					left join projectx_vvarsc2.roles r2
						on r2.role_id = um.MemberRoleID
					left join projectx_vvarsc2.RatingCodes rc
						on rc.RatingCode = ur.RatingCodeOverride
					where m2.mem_id = m.mem_id
					order by
						r2.role_orderby
				limit 1
				) as FullTitle			
			from projectx_vvarsc2.members m
			left join projectx_vvarsc2.divisions d
				on d.div_id = m.divisions_div_id
			join projectx_vvarsc2.ranks r
				on r.rank_id = m.ranks_rank_id
			left join projectx_vvarsc2.UnitMembers um
				on um.MemberID = m.mem_id
			left join projectx_vvarsc2.roles r2
				on r2.role_id = um.MemberRoleID
			where m.mem_sc = 1
				and m.mem_name <> 'guest'
		";
		if ($div_id == 1)
		{
			$div_query .= "and um.RowID is not null";
		}
		
		$div_query .= "
		) s1
		order by
			s1.rank_group_orderby
			,s1.rank_orderby
			,s1.mem_callsign
		";
	}
		
	$div_query_results = $connection->query($div_query);
	
	$display_div = "";
	$display_selectors = "";
	
	$master_div_name = "Rank View - All Members";
	if ($div_id == 0)
	$display_selectors .= "
		<div class=\"div_filters_container\">
			<div class=\"div_filters_entry div_filters_selected\">
				<a href=\"/members/0\">All Members</a>
			</div>
			<div class=\"div_filters_entry div_filters\">
				<a href=\"/members/1\">Assigned Members</a>
			</div>
			<div class=\"div_filters_entry\">
				<a href=\"/membersList\">List View</a>
			</div>
		</div>
	";
	if ($div_id == 1)
	$display_selectors .= "
		<div class=\"div_filters_container\">
			<div class=\"div_filters_entry div_filters\">
				<a href=\"/members/0\">All Members</a>
			</div>
			<div class=\"div_filters_entry div_filters_selected\">
				<a href=\"/members/1\">Assigned Members</a>
			</div>
			<div class=\"div_filters_entry\">
				<a href=\"/membersList\">List View</a>
			</div>
		</div>
	";
	
	$separateInactive = false;
	if ($div_id == 1)
		$separateInactive = true;
		
	$previousGroup = "";
	$currentGroup = "";
	$previousRankOrder = "";
	$currentRankOrder = "";
	
	while (($row = $div_query_results->fetch_assoc()) != false)
	{
		$div_name = $row['division'];
		$rank_name = $row['rank'];
		$rank_image = $row['rank_image'];
		$rank_tinyImage = $row['rank_tinyImage'];
		$mem_id_info = $row['mem_id_info'];
		$mem_status = $row['mem_status'];
		$mem_name_info = $row['mem_name_info'];
		$mem_avatar_info = $row['mem_avatar_info'];
		$mem_role_info = $row['mem_role_info'];
		$mem_rank_info = $row['mem_rank_info'];
		$mem_div_info = $row['mem_div_info'];
		$rank_orderby = $row['rank_orderby'];
		$full_title = $row['FullTitle'];
		
		$currentGroup = $rank_name;
		$currentRankOrder = $rank_orderby;
		
		//If This is a New Group, Open a New Row and Title
		if ($currentGroup != $previousGroup)
		{
			//If This is not 1st Row, Close Previous Row
			if ($previousGroup != "")
			{
				$display_div .= "
							</div>
							<div class=\"divinfo_table_header_block\">
							</div>
						</td>
					</tr>
				";				
			}
			
			//Open New Row
			$display_div .= "
				<tr class=\"divinfo_tableRow\">
					<td class=\"divinfo_tableCell_rankDetailsContainer\">
						<div style=\"display: table-cell;\">
							<div class=\"divinfo_tableCell_rankDetailsImgContainer\">
								<div class=\"corner corner-top-left\">
								</div>
								<div class=\"corner corner-top-right\">
								</div>
								<div class=\"corner corner-bottom-left\">
								</div>
								<div class=\"corner corner-bottom-right\">
								</div>
								<img class=\"divinfo_rankImg_medium\" align=\"center\" alt=\"$rank_name\" src=\"$link_base/images/ranks/$rank_image.png\" />
							</div>
							<div class=\"divinfo_tableCell_rankDetailsName\">
								$rank_name
							</div>
						</div>
					</td>
					<td class=\"divinfo_tableCell_membersContainer\">
						
						<div class=\"divinfo_tableCell_membersTable\">
			";
		}
		else
		{
			//Separator Between Ranks within a Group
			if ($currentRankOrder != $previousRankOrder)
			{
				$display_div .= "
					<div class=\"spacer_2px\">
					</div>
					<div class=\"two-line-separator-grey\" style=\"opacity: 0.75;\">
					</div>
					<div class=\"spacer_2px\">
					</div>
				";
			}
		}
		
		//Command Division
		if ($mem_div_info == "Command")
		{
			$display_div .= "
				<div class=\"divinfo_tableCell_membersTable_tableCell divinfo_comm";
		}
		//Logistics
		else if ($mem_div_info == "Logistics")
		{
			$display_div .= "
				<div class=\"divinfo_tableCell_membersTable_tableCell divinfo_logistics";
		}
		//Air Forces
		else if ($mem_div_info == "Air Forces")
		{
			$display_div .= "
				<div class=\"divinfo_tableCell_membersTable_tableCell divinfo_airForces";
		}
		//Marines
		else if ($mem_div_info == "Marine Forces")
		{
			$display_div .= "
				<div class=\"divinfo_tableCell_membersTable_tableCell divinfo_marines";
		}
		//Special Warfare
		else if ($mem_div_info == "Special Warfare")
		{
			$display_div .= "
				<div class=\"divinfo_tableCell_membersTable_tableCell divinfo_specialWarfare";
		}	
		//No Assignment
		else
		{
			$display_div .= "
				<div class=\"divinfo_tableCell_membersTable_tableCell";
		}
		
		if ($separateInactive == true && $mem_status == "Inactive")
		{
			$display_div .= " image_inactive2\">";
		}
		else
		{
			$display_div .= "\">";
		}
		
		$display_div .= "
				<a href=\"$link_base/player/$mem_id_info\" target=\"_top\" style=\"
					text-decoration: none;
					padding: 2px 2px 2px 0;
					border-collapse: collapse;
					width: 101%;
				\">
					<div class=\"divinfo_memAvatarContainer\">
						<div style=\"display: table-row;\">
							<img class=\"divinfo_memAvatarImg\" align=\"center\" alt=\"$rank_name\" src=\"$link_base/images/player_avatars/$mem_avatar_info.png\"/ style=\"
									height:40px;
									width:40px;
									display: table-cell;
								\">
							<div style=\"
								display: table-cell;
								vertical-align: middle;
								padding-left: 4px;
								width: 100%;
							\">
								<div class=\"divinfo_memAvatar_textOverlay_rankTinyImage\">
									<img class=\"divinfo_memAvatarRankTinyImg\" align=\"center\" alt=\"$rank_name\" src=\"$link_base/images/ranks/TS3/$mem_rank_info.png\"/>
								</div>
								<div class=\"divinfo_memAvatar_textOverlay_memName\">
									$full_title
									<!--$mem_name_info-->
								</div>	
								<div class=\"divinfo_memAvatar_textOverlay_memRole\">
									$mem_role_info
								</div>				
							</div>
						</div>
					</div>
				</a>
				
			</div>
		";
		
		$previousGroup = $currentGroup;
		$previousRankOrder = $currentRankOrder;
	}
	
	//Close Last Row
	$display_div .= "
				</div>
				<div class=\"divinfo_table_header_block\">
				</div>
			</td>
		</tr>
	";	
	
	$master_div_name;	
	
	$connection->close();
?>

<h2>
	Star Citizen Fleet Roster
</h2>
<br />
<div id="TEXT">
	<div class="divinfo_container">
		<? echo $display_selectors; ?>
		<h3>
			<? echo $master_div_name; ?>
		</h3>
		<div class="table_header_block">
		</div>
		<table class="divinfo">
			<? echo $display_div; ?>
		</table>
	</div>
</div>
 
 
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>