/*!
 * custom.js v1.0.0
 * Copyright 2017 
 */

(function ($){

	$( document ).ready(function (){
	});

	$('#mdInputTime').click(function(){
		//$('.clockpicker-hours div:first,.clockpicker-minutes div:first').addClass('white');
	});

	$('.clockpicker-tick').click(function(){
		//$('.clockpicker-hours div:first,.clockpicker-minutes div:first').removeClass('white');
	});

	var form = $('#ajax-contact');
	// 为表单创建事件监听
	$(form).submit(function(e) {

		// 阻止浏览器直接提交表单
		e.preventDefault();

		//手机验证
		var mobile = document.getElementById("mdInputMobile");;
	    mobile.onblur = function(){
	        if(mobile.validity.patternMismatch){ 
	            mobile.setCustomValidity("cell phone number error.");
	        }
	    };

	    var robot = $('#mdCheckReset');
	    if(robot.val() == '1'){
	    	$('.isrobot i').attr('style','color:red');
            return false;
	    }

		// 序列化表单数据
		var formData = $(form).serialize();

		// 使用AJAX提交表单
		$.ajax({
			type: 'POST',
			url: $(form).attr('action'),
			data: formData,
			beforeSend: function ( xhr ) {
				$('.btn-wrap').append('<img src="../wp-content/themes/relish/img/fw_img/ajax.gif" alt="" />');
				$('.btn--darker').hide();
			}
		})
		.done(function(response) {

			$('#modalTip').modal();

			if(response == 200){

				$('#myModal').modal('hide');

				// 清除表单
				$('#myCarousel div.item').removeClass('active');
				$('.br-widget a').removeClass('br-selected br-current');
				$('.br-widget a').eq(0).addClass('br-selected br-current');
				$('.isrobot').find('i').attr("class","fa fa-check-square-o");
				$('#mdInputId').val('');
				$('#mdInputName').val('');
				$('#mdInputMail').val('');
				$('#mdInputMobile').val('');
				$('#mdInputDate').val('');
				$('#mdInputTime').val('');
				$('#mdInputNum').val('1');
				$('#mdInputMessage').val('');

				$('#myModalLabel').html('<img src="../wp-content/themes/relish/img/icon_person.png" alt="" style="float:left;"/>Thank You For <br/>Using Our Online Booking System.');

				$(formMessages).html('<img src="../wp-content/themes/relish/img/icon_tel.png" alt="" style="float:left;margin:-15px 10px 0 0;"/>Our staff will contact you for confirmation shortly.');

				//setTimeout(function(){$("#modalTip").modal("hide")},3000);

			}else if(response == 500){
				$('#myModalLabel').text('Tip');
				$(formMessages).text('Internal Server Error.');
			}

			$('.btn-wrap img').remove();

			$('.btn--darker').show();

		})
		.fail(function(data) {

			$('#modalTip').modal();

			$('#myModalLabel').text('Tip');

			// 设置消息文本
			if (data.responseText !== '') {
				$(formMessages).text(data.responseText);
			} else {
				$(formMessages).text('An error occurred and could not send.');
			}

			$('.btn--darker').show();

			$('.btn-wrap img').remove();

		});
 
	});

	//services style
	$('.cws_wrapper ul li').each(function(){
		$(this).find('.btn-container a').eq(0).hover(
			function(){
				$(this).attr('style','background:#795548;color:#ffffff');
			},
			function(){
				$(this).attr('style','background:#ffffff;color:#3e2723');
			}
		);
		$(this).find('.btn-container a').eq(1).hover(
			function(){
				$(this).attr('style','background:#795548;color:#ffffff');
			},
			function(){
				$(this).attr('style','background:#3e2723;color:#ffffff');
			}
		);
	});

	//Don't choose person
	jQuery('.br-widget a').unbind("click");

	clickPerson();

	//add Index Special Offers appointment
	$('.banners-wrapper a').attr('href','javascript:showAppointment();');

	//remove Services a link 
	$('.grid_row_content .widget_wrapper .btn-container a').attr('href','');

}(jQuery));

//calendar
jQuery('.datepicker').datepicker({
    orientation: "bottom left",
    autoclose:true,
    startDate:new Date(),
    endDate:'+2D'
});

//clock
jQuery('.clockpicker').clockpicker();

//click person
function clickPerson(){

	jQuery('.br-widget a').click(function(){

		var n = jQuery(this).attr('data-rating-value');

		jQuery('.br-current-rating').text(n);

		jQuery('#mdInputNum').val(n);

		jQuery('.br-widget a').removeClass('br-selected br-current');

		var i = 0;
		jQuery('.br-widget a').each(function(){
		   if(i < n){
		   	jQuery('.br-widget a').eq(i).addClass('br-selected br-current');
		   }
		   i++;
		});

	});

}

//click bind
jQuery('.isrobot').toggle(
  function () {
  	jQuery(this).find('i').attr("class","fa fa-check-square-o");
  	jQuery(this).find('i').removeAttr('style');
    // jQuery('.br-widget').removeClass('disable');
    // jQuery('.br-widget a').bind('click',function(){
    // 	clickPerson();
    // });
    jQuery('#mdCheckReset').val(0);
  },
  function () {
    jQuery(this).find('i').attr("class","fa fa-square-o");
    // jQuery('.br-widget').addClass('disable');
    // jQuery('.br-widget a').unbind("click");
    jQuery('#mdCheckReset').val(1);
  }
);

//dialog
function showAppointment(id){
	if(id > 0 || id != null){
		jQuery('#myCarousel div.item').removeClass('active');
		jQuery('#'+id).addClass('active');
		jQuery('#mdInputId').val(id);
		jQuery('#myCarousel').show();
	}
	jQuery('#myModal').modal();
}

//person slide
jQuery('#myCarousel').carousel({
	interval:false
});

jQuery('#myCarousel').on('slid.bs.carousel', function () {
	var id = jQuery(this).find('.active').attr('id');
	jQuery('#mdInputId').val(id);
});