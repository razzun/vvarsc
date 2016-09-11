<?php include_once('functions/function_auth_user.php'); ?>

<?
	$display_members;
	$mem_count;
	
   	$mem_query = "
	SELECT
		m.mem_id
		,m.mem_name
		,m.mem_callsign
		,r.rank_groupTinyImage as rank_image
		,r.rank_groupName as rank_name
		,REPLACE(d.div_name,'Command ','') AS div_info
		,case
			when ro.isPrivate = 0 then ro.role_name
			when ro.role_id is null then 'n/a'
			else '[Redacted]'
		end as mem_role
		,sp.spec_name AS mem_spec
		,COUNT(shm.ships_ship_id) as ship_info
	FROM projectx_vvarsc2.members m 
	LEFT JOIN projectx_vvarsc2.ships_has_members shm
		ON m.mem_id = shm.members_mem_id
	LEFT JOIN projectx_vvarsc2.ships s
		ON shm.ships_ship_id = s.ship_id
	LEFT JOIN projectx_vvarsc2.manufacturers man
		ON s.manufacturers_manu_id = man.manu_id
	JOIN projectx_vvarsc2.ranks r
		ON m.ranks_rank_id = r.rank_id
	JOIN projectx_vvarsc2.divisions d
		ON m.divisions_div_id = d.div_id
	left join projectx_vvarsc2.UnitMembers um
		on um.MemberID = m.mem_id
	left join projectx_vvarsc2.roles ro
		ON um.MemberRoleID = ro.role_id
	LEFT JOIN projectx_vvarsc2.specialties sp
		ON m.specialties_spec_id = sp.spec_id
	WHERE
		m.mem_sc = 1
			and m.mem_name <> 'guest'
	GROUP BY
		m.mem_name
	ORDER BY
		m.mem_name
	";	
    
    $mem_query_results = $connection->query($mem_query);
	
	while(($row = $mem_query_results->fetch_assoc()) != false) {
	    $mem_id = $row['mem_id'];
	    $mem_name = $row['mem_name'];
		$mem_callsign = $row['mem_callsign'];
		$rank_name = $row['rank_name'];	
		$rank_image = $row['rank_image'];
		$div_info = $row['div_info'];
		$mem_role = $row['mem_role'];
		$mem_spec = $row['mem_spec'];
		$ship_info = $row['ship_info'];
		
		$display_members .= "
			<tr class=\"clickableRow\" data-url=\"player/$mem_id\">
				<td class=\"clickableRow_memName\">
					<a href=\"player/$mem_id\" target=\"_top\">
						$mem_name
					</a>
				</td>
				<td class=\"clickableRow_memCallsign\">
					$mem_callsign
				</td>
				<td class=\"clickableRow_memRank\">
					<div class=\"clickableRow_memRank_inner\">
						<img class=\"clickableRow_memRank_Image\" src=\"http://sc.vvarmachine.com/images/ranks/TS3/$rank_image.png\" />
						<div class=\"rank_image_text\">
							$rank_name
						</div>
					</div>
				</td>
				<td class=\"clickableRow_divInfo\">
					$div_info
				</td>
				<td class=\"clickableRow_memRole\">
					$mem_role
				</td>
				<td class=\"clickableRow_memSpec\">
					$mem_spec
				</td>
				<td class=\"clickableRow_memShips\">
					$ship_info
				</td>
			</tr>
		";
		
		$mem_count++;
	}
	
	$ship_count_query = "SELECT COUNT(*) AS ship_count FROM `projectx_vvarsc2`.`ships_has_members`";
	$ship_count_results = $connection->query($ship_count_query);
	$row = $ship_count_results->fetch_assoc();
	$ship_count = $row['ship_count'];
	
	$connection->close();
?>

  <h2>Star Citizen Fleet Roster</h2>
  <div id="TEXT">    
    <!--<a href="$_">All</a> | Command | Economy | Military-->
	<table class="tbmem" id="sourceHeader">
	   <thead>
    		<tr class="clickableRow_headerRow">
    			<th class="clickableRow_header_memName">Player Name</th>
				<th class="clickableRow_header_memCallsign">CallSign</th>
    			<th class="clickableRow_header_memRank">Rank</th>
				<th class="clickableRow_header_divInfo">Division</th>
    			<th class="clickableRow_header_memRole">Role</th>
				<th class="clickableRow_header_memSpec">Specialty</th>
    			<th class="clickableRow_header_memShips">Ships Owned</th>
    		</tr>
    	</thead>
    	<tbody>
		  <? echo "$display_members" ?>
		</tbody>
		<tfoot>
    		<tr>
    			<th valign="top" align="left" nowrap style="padding-left: 8px;">Total Members: <? echo "$mem_count" ?></th>
    		</tr>
			<tr>
    			<th valign="top" align="left" nowrap style="padding-left: 8px;">Total Ships: <? echo "$ship_count" ?></th>
			</tr>
		</tfoot>
	</table>		
  </div>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script>
  jQuery(document).ready(function($) {
      $(".clickableRow").click(function() {
            window.document.location = $(this).data("url");
      });
  });
  </script>  
  
  