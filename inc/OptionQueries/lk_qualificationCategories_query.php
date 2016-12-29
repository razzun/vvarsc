<?
	/*Get Qualification Categories*/
	$getQualificationCategories_query = "
		select
			lk.CategoryID
			,lk.CategoryName
		from projectx_vvarsc2.LK_QualificationCategories lk
		order by
			lk.CategoryName
	";
	
	$getQualificationCategories_query_results = $connection->query($getQualificationCategories_query);
	$displayQualificationCategorySelectors = "";
	
	while(($row = $getQualificationCategories_query_results->fetch_assoc()) != false)
	{
		$CategoryID = $row['CategoryID'];
		$CategoryName = $row['CategoryName'];
	
		$displayQualificationCategorySelectors .= "
			<option value=\"$CategoryID\" id=\"CategoryID-$CategoryID\">
				$CategoryName
			</option>
		";
	}
?>