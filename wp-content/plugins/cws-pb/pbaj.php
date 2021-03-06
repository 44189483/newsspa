<?php
if(!empty($_POST['cont'])) {
	require_once('../../../wp-config.php');

	$good_sc = array(
		'alert' => 'Alert',
		//'quote' => 'Quote',
		//'cws_cta' => 'Call To Action',
		'cws_button' => 'CWS Button',
		'shortcode_carousel' => 'Carousel',
		'price-table' => 'Pricing Table',
		'ourteam' => 'Our Team',
		'twitter' => 'Twitter',
		'services' => 'Services',
		'portfolio' => 'Portfolio',
		'shortcode_blog' => 'Blog',
		'progress' => '',
		'milestone' => '',
		'fa' => '',
		'featured_fa' => '',
		'mark' => '',
		'dropcap' => '',
		'current-year' => '',
		'site-title' => '',
		'site-tagline' => '',
		'site-url' => '',
		'wp-url' => '',
		'gallery' => '',
		'caption' => '',
		'contact-form-7' => '',
		'quote' => '',
		'embed' => '',
		'cws_cta' => '',
		'pb_ourteam' => '',
		'pb_portfolio' => '',
		'pb_portfolio_fw' => '',
		'pb_blog' => '',
		'pb_tweet' => '',
	);

	$replacement = "<div class=\"sc_render clearfix\"><img src=\"" . CWS_PB_PLUGIN_URL . "/scodes/%s.png\"></div><div class=\"sc_descr\">%s</div>%s";

	$str = stripslashes($_POST['cont']);

	$parts0 = preg_split('/(\[[a-zA-Z])/i', $str, -1, PREG_SPLIT_DELIM_CAPTURE);
	$parts = array($parts0[0]);
	$i = 1;
	$j = 1;
	foreach ($parts0 as $part) {
		if (strlen($part) == 2 && substr($part,0,1) == '[') {
			$parts[$i] = $part . $parts0[$j];
			$i++;
		}
		$j++;
	}
	$i = 0;
	$ret = '';
	foreach ($parts as $part) {
		if ($i) {
			$shortcode_name = substr(strtok(strtok($part, ' '), ']'), 1);
			$index = isset($good_sc[$shortcode_name]);
			if ($index) {
				//var_dump($good_sc[$shortcode_name], $shortcode_name);
				if (!empty($good_sc[$shortcode_name])) {
					$d = explode(']', $part);
					$text_after = $d[count($d) - 1];
					$ret .= sprintf($replacement, $shortcode_name, $good_sc[$shortcode_name], $text_after);
				} else {
					// these we don't touch
					$ret .= $part;
				}
			} else {
				// default picture should be used as this is unknown sc
				$d = explode(']', $part);
				$text_after = $d[count($d) - 1];
				if ('<p' == substr($ret, 0, 2)) {
					$ret .= '</p>';
				}
				if ('</p>' == $text_after) {
					$text_after = '<p></p>';
				}
				$ret .= sprintf($replacement, 'default', $shortcode_name, $text_after);
			}
		} else {
			// first part is always text
			$ret .= $part;
		}
		$i++;
	}
	global $post;
	$post = (object) array('ID' => '1'); // assign a dummy ID, otherwise embeds won't be working properly

	$out = apply_filters('the_content', $ret);
	$out = preg_replace('/<input.*?hidden.*?>\n/', '', $out);
	$out = preg_replace('/<input.*?>/', '<div class="cws_pb_input"></div>', $out);
	$out = preg_replace('/<textarea.*?>/', '<div class="cws_pb_textarea"></div>', $out);
	echo $out;
	die();
}
if(!empty($_POST['templ'])) {
	require_once('../../../wp-config.php');
	$templates = get_option('cws-' . THEME_SLUG . '-pbtempl');
	$templates[$_POST['tname']] = $_POST['templ'];
	update_option('cws-' . THEME_SLUG . '-pbtempl', $templates);
	die();
}
?>