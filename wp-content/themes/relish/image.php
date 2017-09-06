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
								$c_post = get_post( get_the_id() );
								$title = get_the_title();
								$permalink = get_permalink();
								echo RELISH_BEFORE_CE_TITLE . "<span>$title</span>" . RELISH_AFTER_CE_TITLE;
								?>
								<div class="post_info_part">
									<div class="post_info_box">
										<div class="post_info_header">
											<div class="date">
												<?php
												$date = get_the_time( get_option("date_format") );
												$first_word_boundary = strpos( $date, " " );
												if ( $first_word_boundary ){
													$first_word = mb_substr( $date, 0, $first_word_boundary );
													$date = "<span class='first_word'>$first_word</span>" . mb_substr( $date, $first_word_boundary + 1 );
												}
												echo sprintf("%s", $date);
												?>
											</div>
											<div class="post_info">
												<?php
													$author = esc_html(get_the_author());
													$special_pf = relish_is_special_post_format();
													if ( !empty($author) || $special_pf ){
														echo "<div class='info'>";
															echo !empty($author) ? "<i class='fa fa-user'></i> by $author" : "";
															if($special_pf){
																if(!empty($author)){
																	echo RELISH_V_SEP;
																}
																echo relish_post_format_mark();
															}
														echo "</div>";
													}
													$comments_n = get_comments_number();
													if ( (int)$comments_n > 0 ){
														$permalink .= "#comments";
														echo "<div class='comments_link'><a href='$permalink'><i class='fa fa-comment'></i> $comments_n</a></div>";
													}
												?>
											</div>
										</div>
										<?php
											$thumbnail_dims = relish_get_post_thumbnail_dims();
											$thumbnail = wp_get_attachment_image_src( get_the_id(), 'full' );
											$thumbnail = !empty( $thumbnail ) ? $thumbnail[0] : "";
											echo "<div class='media_part'>";
												echo "<div class='pic'>";
													$thumbnail_obj = cws_thumb( $thumbnail ,$thumbnail_dims, false );
													$thumbnail_url = esc_url( $thumbnail_obj[0] );
													$thumbnail_retina_thumb_exists = $thumbnail_obj[3]['retina_thumb_exists'];
													$thumbnail_retina_thumb_url = $thumbnail_obj[3]['retina_thumb_url'];
													if ( $thumbnail_retina_thumb_exists ){
														echo "<img src='$thumbnail_url' data-at2x='$thumbnail_retina_thumb_url' alt />";
													}
													else{
														echo "<img src='$thumbnail_url' data-no-retina alt />";
													}
												echo "</div>";
											echo "</div>";
										?>
									</div>
								</div>
							<?php
							$content = get_the_content();
							if ( !empty( $content ) ) echo "<div class='post_content'>" . apply_filters( 'the_content', $content ) . "</div>";
							relish_page_links();

							/* ATTACHMENTS NAVIGATION */

							?>
							<?php
								ob_start();
								previous_image_link( false, "<span class='prev'></span><span>" . esc_html__( 'Previous Image', 'relish' ) . "</span>" );
								$prev_img_link = ob_get_clean();
								ob_start();
								next_image_link( false, "<span>" . esc_html__( 'Next Image', 'relish' ) . "</span><span class='next'></span>" );
								$next_img_link = ob_get_clean();
								if ( !empty( $prev_img_link ) || !empty( $next_img_link ) ){
									echo "<nav class='cws_img_navigation carousel_nav_panel clearfix'>";
										echo !empty( $prev_img_link ) ? "<div class='prev_section'>$prev_img_link</div>" : "";
										echo !empty( $next_img_link ) ? "<div class='next_section'>$next_img_link</div>" : "";
									echo "</nav>";
								}

							/* \ATTACHMENTS NAVIGATION */

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