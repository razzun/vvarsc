<?
	/*Get Qualification Categories*/
	$getQualifications_query = "
		select
			q.qualification_id
			,q.qualification_name
			,q.qualification_categoryID
		from projectx_vvarsc2.qualifications q
		join projectx_vvarsc2.LK_QualificationCategories lk
			on lk.CategoryID = q.qualification_categoryID
		left join (
			select
				mq.RowID
				,mq.qualification_id
			from projectx_vvarsc2.member_qualifications mq
			where mq.member_id = $player_id
		) mq
			on mq.qualification_id = q.qualification_id
		where mq.RowID is null
			and q.IsActive = 1
		order by
			lk.CategoryName
			,q.qualification_Name
	";
	
	$getQualifications_query_results = $connection->query($getQualifications_query);
	$displayQualificationsSelectors = "";
	
	while(($row = $getQualifications_query_results->fetch_assoc()) != false)
	{
		$qualID = $row['qualification_id'];
		$qualName = $row['qualification_name'];
		$qualCategoryID = $row['qualification_categoryID'];
		
		$Value = $qualCategoryID.'_'.$qualID;
	
		$displayQualificationsSelectors .= "
			<option value=\"$Value\" id=\"QualID-$qualID\">
				$qualName
			</option>
		";
	}
?>