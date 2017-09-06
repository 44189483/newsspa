<?php
	get_header ();

	$p_id = get_queried_object_id();

	$sb = relish_get_sidebars( $p_id ); 
	$sb_class = $sb && !empty( $sb['sb_layout_class'] ) ? $sb['sb_layout_class'] . '_sidebar' : ''; 
	$sb1_class = $sb && $sb['sb_layout'] == 'right' ? 'sb_right' : 'sb_left';

	$spacings_blog = relish_get_option( "spacings_blog" );
	$page_title_container_styles = '';

	if(isset($spacings_blog) && !empty($spacings_blog)){
		foreach ($spacings_blog as $key => $value) {
			$page_title_container_styles .= "padding-".$key . ": " . (int) $value . "px;";
		}
	}
	else{
		$page_title_container_styles .= "padding-top:90px;padding-bottom:40px;";
	}
?>
<div class="page_content <?php echo sanitize_html_class($sb_class); ?>" style='<?php echo esc_attr($page_title_container_styles);?>'>
	<?php
	if ( $sb && $sb['sb_exist'] ){
		echo "<div class='container'>";
		if ( $sb['sb1_exists'] ){
			if(is_active_sidebar($sb['sidebar1'])){
				echo "<aside class='$sb1_class'>";
				dynamic_sidebar( $sb['sidebar1'] );
				echo "</aside>";
			}
		}
		if ( $sb['sb2_exists'] ){
			if(is_active_sidebar($sb['sidebar2'])){
				echo "<aside class='sb_right'>";
				dynamic_sidebar( $sb['sidebar2'] );
				echo "</aside>";
			}
		}
	}

	$meta = get_post_meta( $p_id, 'cws_mb_post' );
	$meta = isset( $meta[0] ) ? $meta[0] : array();


	extract( shortcode_atts( array(
			'carousel' => '0',
			'show_related' => '0',
			'related_projects_options' => array(),
			'enable_hover' => '0',
			'link_options_fancybox' => '0',
		), $meta) );


	$related_projects_title = isset( $meta['rpo_title'] ) ? $meta['rpo_title'] : '';
	$related_projects_cols = isset( $meta['rpo_cols'] ) ? (int) $meta['rpo_cols'] : '4';
	$related_projects_count = isset( $meta['rpo_items_count'] ) ? (int) $meta['rpo_items_count'] : '4';
	$categories = isset( $meta['rpo_categories'] ) ? $meta['rpo_categories'] : '';
	if(is_array($categories)){
		if(empty($categories[0])){
			unset($categories[0]);
		}
	}  
	$link_options = isset( $link_options['@'] ) ? $link_options['@'] : array();

	$cats = get_the_terms( $p_id, 'cws_portfolio_cat' );
	$cats = $cats ? $cats : array(); /* if get_the_terms returns false */
	$cat_slugs = array();
	foreach( $cats as $cat ){
		$cat_slugs[] = $cat->slug;
	}
	$has_cats = !empty( $cat_slugs );

	$related_posts = array();
	$has_related = false;

	if ( $has_cats ){
		$query_args = array(
			'post_type' => 'cws_portfolio',
		);
		$query_args['tax_query'] = array( array(
			'taxonomy' => 'cws_portfolio_cat',
			'field' => 'slug',
			'terms' => !empty($categories) ? $categories : $cat_slugs
		));
		$query_args["post__not_in"] = array( $p_id );
		if ( $related_projects_count ){
			$query_args['posts_per_page'] = $related_projects_count;
		}
		$q = new WP_Query( $query_args );
		$related_posts = $q->posts;
		if ( count( $related_posts ) > 0 ){
			$has_related = true;
		}
	}

	$use_related_carousel = $carousel == 1 && $has_related;
	$show_related_items  = $show_related == 1 && $has_related;

	$section_class = "cws_portfolio single";
	$section_class .= $use_related_carousel ? " related" : ""

	?>
	<main>
		<div class="grid_row">
			<section class="<?php echo esc_attr($section_class); ?>">
				<div class="cws_wrapper">
					<div class="cws_portfolio_items grid">
						<article class="item">
						<?php
							$dims = cws_get_portfolio_thumbnail_dims();
							$chars_count = cws_portfolio_get_chars_count();
							while ( have_posts() ):
								the_post();
								render_cws_portfolio_item( $p_id, $dims, $chars_count );
							endwhile;
							wp_reset_postdata();
						?>
						</article>
					</div>
				</div>
				<?php
					if ( $use_related_carousel ){
						$related_ids = array();
						foreach ( $related_posts as $related_post ) {
							$related_ids[] = $related_post->ID;
						}
						array_unshift( $related_ids, $p_id );
						$ajax_data = array(
							'current' => $p_id,
							'initial' => $p_id,
							'related_ids' => $related_ids
						);
						echo "<input type='hidden' id='cws_portfolio_single_ajax_data' value='" . esc_attr(json_encode( $ajax_data ) ) . "' />";
						?>
						<div class='carousel_nav_panel clearfix'>
							<div class='prev_section'>
								<span class='prev'></span>
								<span><?php esc_html_e( 'Prev', 'relish' ); ?></span>
							</div>
							<div class='next_section'>
								<span><?php esc_html_e( 'Next', 'relish' ); ?></span>
								<span class='next'></span>
							</div>
						</div>
						<?php
					}
				?>
			</section>
		</div>
		<?php
			if ( $show_related_items ){

				$mode = $q->post_count > $related_projects_cols ? 'carousel' : 'grid';
				$sc_atts = array(
					'title' => array(
						'text' => ''
					),
					'portfolio_columns' => $related_projects_cols,
					'portfolio_categories' => !empty($categories) ? implode( ',', $categories ) : implode( ',', $cat_slugs ), 
					'portfolio_display' => $mode,
					'items_per_page' => $related_projects_count,
					'dis_pagination' => false,
					'exclude' => array( $p_id ),
					'portfolio_mode' => 'circle',
				);
				$related_projects_section = relish_portfolio( $sc_atts );
				echo !empty( $related_projects_section ) ? "<div class='grid_row single-cws-related'>
					<div class='ce_title'><div>$related_projects_title</div></div>
				$related_projects_section</div>" : "";
			}
		?>
	</main>
	<?php if($sb && $sb['sb_exist']){
		echo "</div>";
	}?>
</div>

<?php

get_footer ();
?>