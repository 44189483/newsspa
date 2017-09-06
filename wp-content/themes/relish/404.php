<?php
	get_header ();
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
<div class="page_content" style='<?php echo esc_attr($page_title_container_styles);?>'>
	<main>
		<div class="grid_row clearfix">
			<div class="grid_col grid_col_12">
				<div class="ce">
					<div class="not_found">
						<div class="banner_404">
							<img src="<?php echo RELISH_URI . "/img/404.png"; ?>" alt />
						</div>
						<div class="desc_404">
							<div class="msg_404">
								<?php
									echo esc_html__( 'Sorry', 'relish' ) . "<br />" . esc_html__( "This page doesn't exist.", "relish" );
								?>
							</div>
							<div class="link">
								<?php
									echo esc_html__( 'Please, proceed to our ', 'relish' ) . "<a href='".esc_url(home_url( '/' ))."'>" . esc_html__( 'Home page', 'relish' ) . "</a>";
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
</div>
<?php
get_footer ();
?>