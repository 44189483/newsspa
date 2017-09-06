<?php
	$args = array("post_type"=>"cws_portfolio",
					'post_status' => 'publish',
					'paged' => $paged);	

	$i = 0; 
    foreach (get_categories('taxonomy=cws_portfolio_cat') as $key => $value) { 
    	$name_cats[$i]['name'] = $value->name;
    	$name_cats[$i]['slug'] = $value->slug;
    	$i++;
   	}

	$vale = '';
	foreach ($name_cats as $key => $value) {
		$vale .= $value['slug'].',';

	}
	$vale = substr($vale, 0, -1);
	$cats = explode(",", $vale);

	$args['tax_query'] = $tax_query[] = array(
				'taxonomy' => 'cws_portfolio_cat',
				'field' => 'slug',
				'terms' => $cats
			);	
	$query = new WP_Query( $args );
	
	render_cws_portfolio( $query ); // output posts

?>

