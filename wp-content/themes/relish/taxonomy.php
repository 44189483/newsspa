<?php
	get_header ();

	$sb = relish_get_sidebars();
	$sb_class = $sb && !empty($sb['sb_layout_class']) ? sanitize_html_class($sb['sb_layout_class']) . '_sidebar' : '';
	$sb1_class = $sb && $sb['sb_layout'] == 'right' ? 'sb_right' : 'sb_left';

	$taxonomy = get_query_var( 'taxonomy' );
	$term_slug = get_query_var( $taxonomy );
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
	if ( $sb && $sb['sb_exist'] ) {
		echo "<div class='container'>";
		if ( $sb['sb1_exists'] ) {
			if(is_active_sidebar($sb['sidebar1'])){
				echo "<aside class='$sb1_class'>";
				dynamic_sidebar( $sb['sidebar1'] );
				echo "</aside>";
			}
		}
		if ( $sb['sb2_exists'] ) {
			if(is_active_sidebar($sb['sidebar2'])){
				echo "<aside class='sb_right'>";
				dynamic_sidebar( $sb['sidebar2'] );
				echo "</aside>";
			}
		}
	}

	?>
	<main>
		<div class="grid_row">
			<?php
				switch( $taxonomy ) {
					case "cws_portfolio_cat":
						echo relish_portfolio( array( 'columns' => '4', 'portfolio_categories' => $term_slug, 'display' => 'grid' ) );
						break;
					case "cws_staff_member_department":
						echo relish_team( array( 'display' => 'grid', 'categories' => $term_slug ) );
						break;
					case "cws_staff_member_position":
						echo relish_team( array( 'display' => 'grid', 'tags' => $term_slug ) );
						break;
				}
			?>
		</div>
	</main>
	<?php if($sb && $sb['sb_exist']){
		echo "</div>";
	}?>
</div>

<?php

get_footer ();
?>