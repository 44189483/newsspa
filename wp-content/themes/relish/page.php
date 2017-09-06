<?php
get_header ();

global $post;

$post_meta = cwsfw_get_post_meta( $post->ID, RELISH_MB_PAGE_LAYOUT_KEY );

$is_blog = isset($post_meta[0]['is_blog']) ? $post_meta[0]['is_blog'] : false;
$sb = relish_get_sidebars($post->ID);

$sb_class = $sb && !empty($sb['sb_layout_class']) ? $sb['sb_layout_class'] . '_sidebar' : '';
$sb1_class = $sb && $sb['sb_layout'] == 'right' ? 'sb_right' : 'sb_left';
$spacings_blog = relish_get_option( "spacings_blog" );
$page_title_container_styles = '';

$post_content = $post->post_content;

$builder = get_body_class();
$builder_active = true;
if (in_array('cwspb-active',$builder)) {
    $builder_active = false;
}
$cws_grid = preg_match( "#\[cws-row#", $post_content );

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
	<?php
	if (!$cws_grid && !$is_blog && !empty($builder_active)) {
  		echo("<div class='grid_row'>");
  	}
  	if(!$cws_grid && $is_blog  && !empty($builder_active)){
  		echo("<div class='grid_row blog_row'>");
  	}
	while ( have_posts() ) : the_post();
		the_content();
		relish_page_links();
	endwhile;
	wp_reset_postdata();
	if(!$cws_grid && $is_blog  && !empty($builder_active)){
		echo("</div>");
	}
	if ( $is_blog ) get_template_part( 'content', 'blog' );
	comments_template();
	
	if (!$cws_grid && !$is_blog && !empty($builder_active)) {
   		echo("</div>");
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