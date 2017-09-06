<?php
/*
 * CWS Image Resizer v1.0
 *
 * (c) 2016 CWS Team
 *
 * Uses WP's Image Editor Class
 *
 * @param $url string the local image URL to manipulate
 * @param $params array the options to perform on the image. Keys and values supported:
 *          'width' int pixels
 *          'height' int pixels
 *          'crop' bool | array()
 * @param $single boolean, if false then an array of data will be returned
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!function_exists('cws_get_id_from_url')) {
	function cws_get_id_from_url($attachment_url) {
		global $wpdb;
		$attachment_id = '';
		if($attachment_url !== '') {
			$query = $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid=%s", $attachment_url);
			$attachment_id = $wpdb->get_var($query);
		}
		return $attachment_id;
	}
}

if(!function_exists('cws_thumb')) {
    function cws_thumb($url, $params = array('width'=>'', 'height'=>'', 'crop' => false ), $single = false) {
    	global $wp_filesystem;

		if( empty( $wp_filesystem ) ) {
		    require_once( ABSPATH .'/wp-admin/includes/file.php' );
		    WP_Filesystem();
		}    	

        $return_array = array();
		extract($params);

        if($url !== '') {
            $attach_id = cws_get_id_from_url($url);
        }

        $img_dimensions = wp_get_attachment_image_src($attach_id, 'full');
        $orig_image_width = $img_dimensions[1];
        $orig_image_height = $img_dimensions[2];

        if(!empty($attach_id) && (isset($width) || isset($height))) {
			//Retina Dimensions
			if (isset($width)) {
				if (stripos($width, '%') !== false) {
					$width = (int)((float)str_replace('%', '', $width) / 100 * $orig_image_width);
					$retina_width = ( (int)((float)str_replace('%', '', $width) * 2 ) / 100 * $orig_image_width);
				}
				else{
					$retina_width = (int)$width * 2;
				}
			}
			if (isset($height)) {
				if (stripos($height, '%') !== false) {
					$height = (int)((float)str_replace('%', '', $height) / 100 * $orig_image_height);
					$retina_height = ( (int)((float)str_replace('%', '', $height) * 2 ) / 100 * $orig_image_height);
				}
				else{
					$retina_height = (int)$height * 2;
				}
			}

			$retina_thumb = true;


			//Make sure we can get Retina
			if ( ((isset($retina_width) && $retina_width > $orig_image_width) || (isset($retina_height) && $retina_height > $orig_image_height)) || (isset($retina_width) && $retina_width == 0 && isset($retina_height) && $retina_height == 0)  ) {
				$retina_thumb = false;
			}

			//Retina Dimensions
            $img_path = get_attached_file($attach_id);
            $img_url  = wp_get_attachment_url($attach_id);

            $file_info = stat($img_path);

            $img_path_array = pathinfo($img_path);
			$img_suffix = 
				$file_info['size'] . $file_info['mtime'] .
				(isset($width) ? str_pad((string)$width, 5, '0', STR_PAD_LEFT) : '00000') .
				(isset($height) ? str_pad((string)$height, 5, '0', STR_PAD_LEFT) : '00000') .
				(isset($crop) ? ($crop ? '1' : '0') : '0') .
				(isset($crop) && is_array($crop) ? 
					(isset($crop[0]) ? ($crop[0] == 'left' ? 'left' : '0') : '0') .
					(isset($crop[0]) ? ($crop[0] == 'center' ? 'center' : '0') : '0') .
					(isset($crop[0]) ? ($crop[0] == 'right' ? 'right' : '0') : '0') .

					(isset($crop[1]) ? ($crop[1] == 'top' ? 'top' : '0') : '0') .
					(isset($crop[1]) ? ($crop[1] == 'center' ? 'center' : '0') : '0') .
					(isset($crop[1]) ? ($crop[1] == 'bottom' ? 'bottom' : '0') : '0')
				: 'no_array');

			$img_suffix = md5($img_suffix);

			//Thumbnail url
            $new_img_path = $img_path_array['dirname'].'/'.$img_path_array['filename'].'_'.$img_suffix.'.'.$img_path_array['extension'];
            $retina_new_img_path = $img_path_array['dirname'].'/'.$img_path_array['filename'].'_'.$img_suffix.'@2x.'.$img_path_array['extension'];

            //Thumbnail path
            $new_img_url = str_replace($img_path_array['basename'], $img_path_array['filename'].'_'.$img_suffix.'.'.$img_path_array['extension'], $img_url);
            $retina_new_img_url = str_replace($img_path_array['basename'], $img_path_array['filename'].'_'.$img_suffix.'@2x.'.$img_path_array['extension'], $img_url);
            
            //check if thumbnail exists
			if(!$wp_filesystem->exists($new_img_path)) {
                //Get image object
                $image_object = wp_get_image_editor($img_path);

                if(!is_wp_error($image_object)) {
                    //Resize and save
                    $image_object->resize(isset( $width ) ? $width : null, isset( $height ) ? $height : null, isset( $crop ) ? $crop : false);
                    $image_object->save($new_img_path);

                    //Get sizes of new image object
                    $image_sizes = $image_object->get_size();
                    $image_width = $image_sizes['width'];
                    $image_height = $image_sizes['height'];
                }

				if ( $retina_thumb ) {

					//Get image object (Retina)
					$retina_object = wp_get_image_editor($img_path);

	              	if(!is_wp_error($retina_object)) {
	                    //Resize and save
	                    if ( ( isset( $retina_width ) && $retina_width ) || ( isset( $retina_height ) && $retina_height ) ) {
		                    $retina_object->resize(isset( $retina_width ) ? $retina_width : null, isset( $retina_height ) ? $retina_height : null, isset( $crop ) ? $crop : false);
		                    $retina_object->save($retina_new_img_path);

		                    //Get sizes of new image object
		                    $image_sizes = $retina_object->get_size();
		                    $retina_width = $image_sizes['width'];
		                    $retina_height = $image_sizes['height'];	                    	               
	               		}
	                }
				}
			} else {
            	$image_sizes = getimagesize($new_img_path);
                $image_width = $image_sizes[0];
                $image_height = $image_sizes[1];
			}

			if($single) {
				$return_array = array(
					0 => $new_img_url,
					1 => NULL,
					2 => NULL,
					3 => array(
						'retina_thumb_exists' => $retina_thumb,
						'retina_thumb_url' => ($retina_thumb ? $retina_new_img_url : '')
					),
				);
			} else {
				//Data to return
				$return_array = array (
					0 => $new_img_url,
					1 => isset($image_width) ? $image_width : $orig_image_width,
					2 => isset($image_height) ? $image_height : $orig_image_height,
					3 => array(
						'retina_thumb_exists' => $retina_thumb,
						'retina_thumb_url' => ($retina_thumb ? $retina_new_img_url : '')
					)
				);
			}

        } 

        //Attachment wasn't found, probably because it comes from external source
        elseif($url !== '' && (isset($width) && isset($height))) {
            //Generate data to be returned
            $return_array = array(
                0 => $url,
				1 => NULL,
				2 => NULL,
				3 => array(
					'retina_thumb_exists' => false ,
					'retina_thumb_url' => ''
				),
			);
        }

        return $return_array;
    }
}
?>