<?php
/**
 * Archive template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage The_8
 * @since The 8 1.0
 */

	$sb = relish_get_sidebars();
	$sb_class = $sb && !empty($sb['sb_layout_class']) ? $sb['sb_layout_class'] . '_sidebar' : '';
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
	get_header();
	?>
	<div class="page_content <?php echo sanitize_html_class($sb_class); ?>" style='<?php echo esc_attr($page_title_container_styles);?>'>
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
			<?php get_template_part( 'content', 'blog' ); ?>
		</main>
		<?php if($sb && $sb['sb_exist']){
			echo "</div>";
		}?>
	</div>

<?php
get_footer();