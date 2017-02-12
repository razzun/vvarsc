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
			,s1.mem_callsign as mem_name_info
			,s1.rank_tinyImage as mem_rank_info
			,s1.mem_avatar_link as mem_avatar_info
			,s1.role_name as mem_role_info
			,s1.div_name as mem_div_info
		from
		(
		";
		
		if ($div_id == 0)
		{
			$div_query .= "
			select distinct
				r.rank_groupName
				,r.rank_group_orderby
				,r.rank_groupImage
				,r.rank_groupTinyImage
				,r.rank_tinyImage
				,r.rank_orderby
				,d.div_name
				,m.mem_id
				,m.mem_callsign
				,m.mem_avatar_link
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
			from projectx_vvarsc2.members m
			join projectx_vvarsc2.divisions d
				on d.div_id = m.divisions_div_id
				and d.div_name = 'Command'
			join projectx_vvarsc2.ranks r
				on r.rank_id = m.ranks_rank_id
			left join projectx_vvarsc2.UnitMembers um
				on um.MemberID = m.mem_id
			where m.mem_sc = 1
				and m.mem_name <> 'guest'
			union
			";
		}
			$div_query .= "
			select distinct
				r.rank_groupName
				,r.rank_group_orderby
				,r.rank_groupImage
				,r.rank_groupTinyImage
				,r.rank_tinyImage
				,r.rank_orderby
				,d.div_name
				,m.mem_id
				,m.mem_callsign
				,m.mem_avatar_link
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
			from projectx_vvarsc2.members m
			join projectx_vvarsc2.divisions d
				on d.div_id = m.divisions_div_id
				and d.div_name != 'Command'
			join projectx_vvarsc2.ranks r
				on r.rank_id = m.ranks_rank_id
			left join projectx_vvarsc2.UnitMembers um
				on um.MemberID = m.mem_id
			left join projectx_vvarsc2.roles r2
				on r2.role_id = um.MemberRoleID
			where m.mem_sc = 1
				and($div_id = 0 or d.div_id = $div_id)
				and m.mem_name <> 'guest'
		) s1
		order by
			s1.rank_group_orderby
			,s1.rank_orderby
			,s1.mem_callsign";
	}
		
	$div_query_results = $connection->query($div_query);
	
	$display_div = "";
	$display_selectors = "";
	
	if ($div_id == 0) {
		$master_div_name = "Division View - All Members";
		$display_selectors .= "
			<div class=\"div_filters_container\">
				<div class=\"div_filters_entry\">
					<a href=\"/divisions/3\">Military</a>
				</div>
				<div class=\"div_filters_entry\">
					<a href=\"/divisions/2\">Economy</a>
				</div>
				<div class=\"div_filters_entry div_filters_selected\">
					<a href=\"/divisions/0\">All Members</a>
				</div>
				<div class=\"div_filters_entry\">
					<a href=\"/members\">List View</a>
				</div>
			</div>
		";
	}
	
	if ($div_id == 2) {
		$master_div_name = "Division View - Economy";
		$display_selectors .= "
			<div class=\"div_filters_container\">
				<div class=\"div_filters_entry\">
					<a href=\"/divisions/3\">Military</a>
				</div>
				<div class=\"div_filters_entry div_filters_selected\">
					<a href=\"/divisions/2\">Economy</a>
				</div>
				<div class=\"div_filters_entry\">
					<a href=\"/divisions/0\">All Members</a>
				</div>
				<div class=\"div_filters_entry\">
					<a href=\"/members\">List View</a>
				</div>
			</div>
		";
	}

	if ($div_id == 3) {
		$master_div_name = "Division View - Military";
		$display_selectors .= "
			<div class=\"div_filters_container\">
				<div class=\"div_filters_entry div_filters_selected\">
					<a href=\"/divisions/3\">Military</a>
				</div>
				<div class=\"div_filters_entry\">
					<a href=\"/divisions/2\">Economy</a>
				</div>
				<div class=\"div_filters_entry\">
					<a href=\"/divisions/0\">All Members</a>
				</div>
				<div class=\"div_filters_entry\">
					<a href=\"/members\">List View</a>
				</div>
			</div>
		";
	}
		
	$previousGroup = "";
	$currentGroup = "";
	
	while (($row = $div_query_results->fetch_assoc()) != false)
	{
		$div_name = $row['division'];
		$rank_name = $row['rank'];
		$rank_image = $row['rank_image'];
		$rank_tinyImage = $row['rank_tinyImage'];
		$mem_id_info = $row['mem_id_info'];
		$mem_name_info = $row['mem_name_info'];
		$mem_avatar_info = $row['mem_avatar_info'];
		$mem_role_info = $row['mem_role_info'];
		$mem_rank_info = $row['mem_rank_info'];
		$mem_div_info = $row['mem_div_info'];
		
		$currentGroup = $rank_name;
		
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
						<div class=\"divinfo_tableCell_rankDetailsImgContainer\">
							<div class=\"corner corner-top-left\">
							</div>
							<div class=\"corner corner-top-right\">
							</div>
							<div class=\"corner corner-bottom-left\">
							</div>
							<div class=\"corner corner-bottom-right\">
							</div>
							<img class=\"divinfo_rankImg\" align=\"center\" alt=\"$rank_name\" src=\"$link_base/images/ranks/$rank_image.png\" />
						</div>
						<div class=\"divinfo_tableCell_rankDetailsName\">
							$rank_name
						</div>
					</td>
					<td class=\"divinfo_tableCell_membersContainer\">
						
						<div class=\"divinfo_tableCell_membersTable\">
			";
		}		
		
		//Command Division
		if ($mem_div_info == "Command")
		{
			if ($mem_role_info == "Fleet CO")
			{
				$display_div .= "<div class=\"divinfo_tableCell_membersTable_tableCell divinfo_comm divinfo_FCO\">";
			}
			else
			{
				$display_div .= "
					<div class=\"divinfo_tableCell_membersTable_tableCell divinfo_comm\">";
			}
		}
		//Econ Division
		else if ($mem_div_info == "Economy")
		{
			if ($mem_role_info == "Division CO")
			{
				$display_div .= "
					<div class=\"divinfo_tableCell_membersTable_tableCell divinfo_econ divinfo_DCO\">";	
			}
			else if ($mem_role_info == "Division XO")
			{
				$display_div .= "
					<div class=\"divinfo_tableCell_membersTable_tableCell divinfo_econ divinfo_DXO\">";
			}
			else
			{
				$display_div .= "
					<div class=\"divinfo_tableCell_membersTable_tableCell divinfo_econ\">";
			}
		}
		//Military Division
		else if ($mem_div_info == "Military")
		{
			if ($mem_role_info == "Division CO")
			{
				$display_div .= "
					<div class=\"divinfo_tableCell_membersTable_tableCell divinfo_mil divinfo_DCO\">";	
			}
			else if ($mem_role_info == "Division XO")
			{
				$display_div .= "
					<div class=\"divinfo_tableCell_membersTable_tableCell divinfo_mil divinfo_DXO\">";
			}
			else
			{
				$display_div .= "
					<div class=\"divinfo_tableCell_membersTable_tableCell divinfo_mil\">";
			}
		}	
		else
		{
			$display_div .= "
				<div class=\"divinfo_tableCell_membersTable_tableCell\">";
		}
		
		$display_div .= "
				<a href=\"$link_base/player/$mem_id_info\" target=\"_top\">
					<div class=\"divinfo_memAvatarContainer\">
						<img class=\"divinfo_memAvatarImg\" align=\"center\" alt=\"$rank_name\" src=\"$link_base/images/player_avatars/$mem_avatar_info.png\" />
						<div class=\"divinfo_memAvatar_textOverlay_rankTinyImage\">
							<img class=\"divinfo_memAvatarRankTinyImg\" align=\"center\" alt=\"$rank_name\" src=\"$link_base/images/ranks/TS3/$mem_rank_info.png\" />
						</div>
						<div class=\"divinfo_memAvatar_textOverlay_memName\">
							$mem_name_info
						</div>
						<div class=\"divinfo_memAvatar_textOverlay_memRole\">
							$mem_role_info
						</div>
					</div>
				</a>
				
			</div>
		";
		
		$previousGroup = $currentGroup;
		
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
<? echo $display_selectors; ?>
<div id="TEXT">
	<div class="divinfo_container">
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
<script type="text/javascript" src="/js/jquery.jScale.js"></script>

 <!--Script to Resize Avatar Images to 50%-->
<script>

	jQuery(document).ready(function($) {
		$('.divinfo_memAvatarImg').jScale({w:'50%'})
	});

</script>