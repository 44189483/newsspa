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
<div class="page_content <?php echo esc_attr($sb_class); ?>" <?php if(is_single()):?> style='<?php echo esc_attr($page_title_container_styles);?>' <?php endif;?>>
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

	$section_class = "news single";

	?>
	<main>
		<div class="grid_row clearfix">
			<section class="<?php echo esc_attr($section_class); ?>">
				<div class="cws_wrapper">
					<div class="grid">
						<article class="item clearfix">
						<?php
							while ( have_posts() ):
								the_post();
								relish_post_output ();
							endwhile;
							wp_reset_postdata();
						?>
						</article>
					</div>
				</div>
			</section>
		</div>
		<?php comments_template(); ?>
	</main>
	<?php if($sb && $sb['sb_exist']){
		echo "</div>";
	}?>
</div>

<?php

get_footer ();
?>