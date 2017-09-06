<?php
	$paged = !empty($_POST['paged']) ? (int)$_POST['paged'] : (!empty($_GET['paged']) ? (int)$_GET['paged'] : ( get_query_var("paged") ? get_query_var("paged") : 1 ) );
	$posts_per_page = (int)get_option('posts_per_page');
	$search_terms = get_query_var( 'search_terms' );

	get_header();

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
<div class="page_content search_results <?php echo esc_attr($sb_class); ?>" style='<?php echo esc_attr($page_title_container_styles);?>'>
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

	$blogtype = relish_get_option( 'def_blogtype' );
	$news_class = !empty( $blogtype ) ? ( preg_match( '#^\d+$#', $blogtype ) ? "news-pinterest" : "news-$blogtype" ) : "news-medium";
	$grid_class = $news_class == "news-pinterest" ? "grid-$blogtype isotope" : "";

	?>
	<main>
		<div class="grid_row clearfix">
			<?php
			global $wp_query;
			$total_post_count = $wp_query->found_posts;
			$max_paged = ceil( $total_post_count / $posts_per_page );
			if ( 0 === strlen($wp_query->query_vars['s'] ) ){
				ob_start();
				echo do_shortcode( "[cws_sc_msg_box type='info' title='" . esc_html__( "Empty search string", 'relish' ) . "' text='" . esc_html__( "Please, enter some characters to search field", 'relish' ) . "'][/cws_sc_msg_box]" );
				get_search_form( $search_terms );
				$sc_content = ob_get_clean();
				echo do_shortcode( "[cws-widget type='text']" . $sc_content . "[/cws-widget]" );
			}
			if(have_posts()){
				?>
				<section class="news <?php echo esc_attr($news_class); ?>">
					<div class='cws_wrapper'>
						<div class="grid <?php echo esc_attr($grid_class); ?>">
						<?php
							wp_enqueue_script ('isotope');
							$use_pagination = $max_paged > 1;
								while( have_posts() ) : the_post();
									$content = get_the_content();
									$content = preg_replace( '/\[.*?(\"title\":\"(.*?)\").*?\]/', '$2', $content );
									$content = preg_replace( '/\[.*?(|title=\"(.*?)\".*?)\]/', '$2', $content );
									$content = strip_tags( $content );
									$content = preg_replace( '|\s+|', ' ', $content );
									$title = get_the_title();

									$cont = '';
									$bFound = false;
									$contlen = strlen( $content );
									if(isset($search_terms) && !empty($search_terms)){
										foreach ($search_terms as $term) {
											$pos = 0;
											$term_len = strlen($term);
											do {
												if ( $contlen <= $pos ) {
													break;
												}
												$pos = stripos( $content, $term, $pos );
												if ( $pos ) {
													$start = ($pos > 50) ? $pos - 50 : 0;
													$temp = substr( $content, $start, $term_len + 100 );
													$cont .= ! empty( $temp ) ? $temp . ' ... ' : '';
													$pos += $term_len + 50;
												}
											} while ($pos);
										}										
									}


									if (strlen($cont) > 0) {
										$bFound = true;
									}
									else {
										$cont = mb_substr( $content, 0, $contlen < 100 ? $contlen : 100 );
										if ( $contlen > 100 ) {
											$cont .= '...';
										}
										$bFound = true;
									}
									$pattern = "#\[[^\]]+\]#";
									$replace = "";
									$cont = preg_replace($pattern, $replace, $cont);
									if(!empty($search_terms)){
										$cont = preg_replace('/('.implode('|', $search_terms) .')/iu', '<mark>\0</mark>', $cont);
									}
									
									$permalink = esc_url( get_the_permalink() );
									$title = sanitize_title( get_the_title() );
									if(!empty($search_terms)){
										$title = preg_replace( '/('.implode( '|', $search_terms ) .')/iu', '<mark>\0</mark>', $title );	
									}
									
									echo "<article class='item small'>";
									
										if ( has_post_thumbnail() ) {
											$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_id() ), 'full', true );

											$thumb_obj = cws_thumb( $image[0],array( 'width' => 350, 'crop' => true ),false );
											$thumb_path_hdpi = $thumb_obj[3]['retina_thumb_exists'] ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3]['retina_thumb_url'] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
											$logo_src = $thumb_path_hdpi;

											if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
												$default_attr = array(
														'class' => 'cws_img_frame alignleft noborder',
														'style' => 'margin-top:0',
													);
													the_post_thumbnail( 'thumbnail' ,$default_attr );
											}
										}
										echo !empty( $title ) ? RELISH_BEFORE_CE_TITLE . "<span><a href='$permalink'>$title</a></span>" . RELISH_AFTER_CE_TITLE : "";
										echo "<div class='post_content'>" . apply_filters( 'the_content', $cont ) . "</div>";
										if ( has_tag() ){
											echo "<div class='post_tags'>";
											the_tags ( "<i class='fa fa-tag'></i>", RELISH_V_SEP, "" );
											echo "</div>";
										}
										if ( has_category() ){
											echo "<div class='post_categories'><i class='fa fa-bookmark'></i>";
											the_category ( RELISH_V_SEP);
											echo "</div>";
										}
										$button_word = esc_html__( 'Read More', 'relish' );
										echo "<div class='right_alight'><a href='$permalink' class='cws_button small'>".$button_word.'</a></div>';
									echo "</article>";
								endwhile;
								wp_reset_postdata();
							?>
						</div>
					</div>
				</section>
				<?php
				//global $wp_query;
				if ( $use_pagination ) {
					relish_pagination($paged,$max_paged);
				}
			}
			else {
				ob_start();
				echo do_shortcode( "[cws_sc_msg_box type='info' title='" . esc_html__( "No search Results", 'relish' ) . "' text='" . esc_html__( "There are no posts matching your query", 'relish' ) . "'][/cws_sc_msg_box]" );
				get_search_form( $search_terms );
				$sc_content = ob_get_clean();
				echo do_shortcode( "[cws-widget type='text']" . $sc_content . "[/cws-widget]" );
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