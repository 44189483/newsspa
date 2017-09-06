<?php
	$blogtype = relish_get_option( "def_blogtype" );
	$taxonomy = "category";
	$terms  = array();

if ( is_page() ) {
	$blogtype = relish_get_page_meta_var ( array( 'blog', 'blogtype' ) );
	$cats = relish_get_page_meta_var ( array( 'blog', 'cats' ) );
	$terms = !empty($cats) ? explode(',', relish_get_page_meta_var ( array( 'blog', 'cats' ) )) : '';
}
else if ( is_category() ) {
	$term_id = get_query_var( 'cat' );
	$term = get_term_by( 'id', $term_id, 'category' );
	$term_slug = $term->slug;
	$terms = array( $term_slug );
}
else if ( is_tag() ) {
	$taxonomy = 'post_tag';
	$term_slug = get_query_var( 'tag' );
	$terms = array( $term_slug );
}


	if ($blogtype == 'default') {
		$blogtype = relish_get_option( 'def_blogtype' );
	}

	$post_type_array = array("post");
	$posts_per_page = (int)get_option('posts_per_page');
	$ajax = isset( $_POST['ajax'] ) ? (bool)$_POST['ajax'] : false;
	$paged_var = get_query_var( 'paged' );
	$paged = $ajax && isset( $_POST['paged'] ) ? $_POST['paged'] : ( $paged_var ? $paged_var : 1 );
	$args = array("post_type"=>$post_type_array,
					'post_status' => 'publish',
					'posts_per_page' => $posts_per_page,
					'paged' => $paged);	

	if ( !empty( $terms ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => $terms
			)
		);
	}
	if ( is_date() ) {
		$year = relish_get_date_part( 'y' );
		$month = relish_get_date_part( 'm' );
		$day = relish_get_date_part( 'd' );
		if ( !empty( $year ) ) {
			$args['year'] = $year;
		}
		if ( !empty( $month ) ) {
			$args['monthnum'] = $month;
		}
		if ( !empty( $day ) ) {
			$args['day'] = $day;
		}
	}
	$query = new WP_Query( $args );
	$max_paged = ceil( $query->found_posts / $posts_per_page );

	$blogtype = sanitize_html_class( $blogtype );

	$news_class = !empty( $blogtype ) ? ( preg_match( '#^\d+$#', $blogtype ) ? "news-pinterest" : "news-$blogtype" ) : "news-medium";
	$grid_class = $news_class == "news-pinterest" ? "grid-$blogtype isotope" : "";

	if ($news_class == "news-pinterest") {
		wp_enqueue_script ('isotope');
	}

	if ( !$ajax ): // not ajax request

		?>
		<div class="grid_row">
			<section class="news <?php echo esc_attr($news_class); ?>">
				<div class="cws_wrapper">
					<div class="grid <?php echo esc_attr($grid_class); ?>">
					<?php

						endif;
						relish_blog_output( $query ); // output posts
						if ( !$ajax ): // not ajax request

					?>
					</div>
					<?php
						if ( $news_class == "news-pinterest" && $paged < $max_paged && $blogtype != '1' ) {
							$template = 'content-blog';
							relish_load_more( $paged + 1, $template, $max_paged );
						}
						else if ( in_array( $news_class, array( "news-small", "news-medium", "news-large" ) ) && $max_paged > 1 ) {
							relish_pagination( $paged, $max_paged );
						}
						else if( $blogtype == '1' && $max_paged > 1){
							relish_pagination( $paged, $max_paged );
						}
					?>
				</div>
			</section>
		</div>
		<?php

	endif;
?>