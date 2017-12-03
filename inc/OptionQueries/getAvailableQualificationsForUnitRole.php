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
				,mq.QualificationID
			from projectx_vvarsc2.UnitQualifications mq
			where mq.UnitID = $UnitID
				and mq.RoleID = $RoleID
		) mq
			on mq.QualificationID = q.qualification_id
		where mq.RowID is null
		order by
			lk.CategoryName
			,q.qualification_Name
	";
	
	$getQualifications_query_results = $connection->query($getQualifications_query);
	$displayAvailableQualificationsSelectors = "";
	
	while(($row = $getQualifications_query_results->fetch_assoc()) != false)
	{
		$qualID = $row['qualification_id'];
		$qualName = $row['qualification_name'];
		$qualCategoryID = $row['qualification_categoryID'];
		
		$Value = $qualCategoryID.'_'.$qualID;
	
		$displayAvailableQualificationsSelectors .= "
			<option value=\"$Value\" id=\"QualID-$qualID\">
				$qualName
			</option>
		";
	}
?>
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