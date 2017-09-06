<?php
	get_header ();

	$p_id = get_queried_object_id();

	$sb = relish_get_sidebars( $p_id ); 
	$sb_class = $sb && !empty( $sb['sb_layout_class'] ) ? sanitize_html_class($sb['sb_layout_class']) . '_sidebar' : ''; 
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
<div class="page_content <?php echo esc_attr($sb_class); ?>" style='<?php echo esc_attr($page_title_container_styles);?>'>
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

	$section_class = "cws_ourteam single";

	?>
	<main>
		<div class="grid_row">
			<section class="<?php echo esc_attr($section_class); ?>">
				<div class="cws_wrapper">
					<div class="cws_ourteam_items grid">
						<article class="item clearfix">
						<?php
							$dims = cws_get_ourteam_thumbnail_dims( $p_id );
							while ( have_posts() ):
								the_post();
								render_cws_ourteam_item( $p_id, $dims );
							endwhile;
							wp_reset_postdata();
						?>
						</article>
					</div>
				</div>
			</section>
		</div>
	</main>
	<?php if($sb && $sb['sb_exist']){
		echo "</div>";
	}?>
</div>

<?php

get_footer ();
?>