<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage The_8
 * @since The 8 1.0
 */
?>

	</div><!-- #main -->
	
	<?php 
		echo relish_page_footer();
	?>
</div>
<!-- end body cont -->
<?php 
wp_footer();

$results = $wpdb->get_results("SELECT ID,post_title FROM {$wpdb->posts} WHERE post_type='cws_staff' AND post_status='publish'");
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
      		<div class="modal-header">
      			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
      		</div>
		    <div class="modal-body">

		    	<form id="ajax-contact" action="<?php bloginfo('template_directory'); ?>/send.php" method="POST">
			    	<div class="hr-heading">
	          			<div class="h1">Appointment</div>
	        		</div>
			    	<div class="form-wrap form-res">
			            <div class="row">
			            	<div class="col-md-12">
				                <div id="myCarousel" class="carousel slide" style="display:none">
				                	<div class="carousel-inner">
										<?php
										foreach ($results as $val):
										    $row = wp_get_attachment_image_src(get_post_thumbnail_id($val->ID),'thumbnail');	
										?>
										<div class="item" id="<?php echo $val->ID;?>">
											<img src="<?php echo $row[0];?>" alt="<?php echo $val->post_title;?>">
											<p><?php echo $val->post_title;?></p>
										</div>
										<?php endforeach;?>
									</div>								
									<a class="carousel-control left" href="#myCarousel" 
									   data-slide="prev">&lsaquo;</a>
									<a class="carousel-control right" href="#myCarousel" 
									   data-slide="next">&rsaquo;</a>
									<input id="mdInputId" type="hidden" name="field_id" />
								</div> 
				            </div>
				            <div class="col-md-12">
				                <div class="row input-row">
				                  <div class="col-xs-12 input-wrap">
				                    <label for="mdInputName" class="label-control">Your name <span class="label-warn">*</span></label>
				                    <input id="mdInputName" type="text" name="field_name" placeholder="" required="" class="form-control">
				                  </div>
				                  <div class="col-xs-12 input-wrap">
				                    <label for="mdInputMail" class="label-control">Your e-mail <span class="label-warn">*</span></label>
				                    <input id="mdInputMail" type="email" name="field_mail" placeholder="" required="" class="form-control">
				                  </div>
				                  <div class="col-xs-12 input-wrap">
				                    <label for="mdInputMobile" class="label-control">Your mobile <span class="label-warn">*</span></label>
				                    <input id="mdInputMobile" type="text" name="field_mobile" required pattern="^(8|9)\d{7}$" placeholder="" class="form-control"/>
				                  </div> 
				                </div>
				            </div>
				            <div class="col-md-12">
				                <div class="row input-row">
				                  <div class="col-rs col-xs-6 input-wrap">
				                    <label for="mdInputDate" class="label-control">date</label>
				                    <input id="mdInputDate" name="field_date" data-date-format="mm/dd/yyyy" required="" class="form-control datepicker" readOnly="readOnly"/>
				                  </div>
				                  <div class="col-rs col-xs-6 input-wrap clockpicker" data-autoclose="true">
				                    <label for="mdInputTime" class="label-control">time</label>
				                    <input id="mdInputTime" type="text" name="field_time" required="" class="form-control" readOnly="readOnly"/>
				                  </div>
				                </div>
				            </div>
				            <div class="col-md-12">
				                <div class="label-control noCursor"># people</div>
				                <div class="row input-row number-of-reversed">
				                  <div class="col-rs col-xs-6 input-wrap fzReset">
				                    <div class="br-wrapper">
					                    <div class="br-widget">
						                    <a data-rating-value="1" data-rating-text="1" class="br-selected br-current"></a>
						                    <a data-rating-value="2" data-rating-text="2"></a>
						                    <a data-rating-value="3" data-rating-text="3"></a>
						                    <a data-rating-value="4" data-rating-text="4"></a>
						                    <a data-rating-value="5" data-rating-text="5"></a>
						                    <a data-rating-value="6" data-rating-text="6"></a>
						                    <div class="br-current-rating">1</div>
						                    <input id="mdInputNum" name="field_num" type="hidden" value="1"/>
					                    </div>
				                    </div>
				                  </div>
				                  <div class="col-rs col-xs-6 input-wrap isrobot">
				                    <input id="mdCheckReset" name="field_robot" type="hidden" value="1"/>
				                    <label for="mdCheckReset"><i class="fa fa-square-o"></i>
				                    	<span class="span">I not a robot</span>
				                    </label>
				                  </div>
				                </div>
				            </div>
				            <div class="col-md-12">
				                <div class="form-group">
				                  <div class="label-control noCursor">Special requests</div>
				                  <textarea id="mdInputMessage" name="field_message" placeholder="" class="form-control" rows="10"></textarea>
				                </div>
				            </div>
			            </div>
			            <div class="btn-wrap" style="text-align:center">
			              <button type="submit" name="submit" class="btn btn--darker">make an appointment</button>
			            </div>
			        </div>
		        </form>

		    </div>
		    <div class="modal-footer">
		    </div>
		</div>
	</div>
</div>


<div class="modal fade" id="modalTip" tabindex="-1" role="dialog" aria-labelledby="modalTipLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
				</button>
				<div class="modal-title" id="myModalLabel">
				</div>
			</div>
			<div class="modal-body" id="formMessages">
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/custom.js"></script>

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '453608241690909'); // Insert your pixel ID here.
fbq('track', 'PageView');
</script>
<noscript>< img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=453608241690909&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->
</body>
</html>