<?php
/*
File Name: send
Description: post mail and mobile message.
Version: 1.0.0
*/

// POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$name = trim($_POST["field_name"]);
	$email = trim($_POST["field_mail"]);
	$mobile = trim($_POST["field_mobile"]);
	$date = $_POST['field_date'];
	$time = $_POST['field_time'];
	$datetime = $date.' '.$time;

	$pax = $_POST['field_num'];
	$message = $_POST['field_message'];

    require('../../../wp-load.php');

    $wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );

    //获取技师
    $res = $wpdb->get_row("SELECT post_title FROM wp_posts WHERE ID={$_POST['field_id']}");

    $masseur = $res->post_title;

    $table = 'wp_appointment';

    $sql = "INSERT INTO {$table} (name,email,mobile,isRobot,appointmentPerson,appointmentTime,appointmentMessage,post_id,masseur,createTime)VALUES('{$name}','{$email}','{$mobile}','{$_POST['field_robot']}','{$pax}','{$datetime}','{$message}','{$_POST['field_id']}','{$masseur}','".date('Y-m-d H:i:s')."')";

    $wpdb->query($sql);

	$insert_id = $wpdb->insert_id;

	/**** send sms start *****/
	include 'plugins/APIClient2.php';

	$api = new transmitsmsAPI("6b5cc9ba59b9133f6ef5b453734d1cd9",'microuniver');

	$to = '+6582624536';

	$sms_message = "News Spa\nName: {$name}\nPhone: {$mobile}\nDate: {$date}\nTime: {$time}\nPax: {$pax}\n";

	if(!empty($masseur)){
		$sms_message .= "Masseur: {$masseur}\n";
	}

	if(!empty($message)){
		$sms_message .= "Special Request:{$message}";
	}

	// sending to a set of numbers
	$result = $api->sendSms($sms_message,$to);

	// $fp = fopen('log.txt', 'w+');
	// fwrite($fp, $result->error->code.'---'.date('Y-m-d H:i')."\r\n");
	// fclose($fp);
	 
	if($result->error->code == 'SUCCESS'){

	    //echo"Message to {$result->recipients} recipients sent with ID {$result->message_id}, cost {$result->cost}";

	    //发短信成功
		$wpdb->query("UPDATE {$table} SET sendMessage=1 WHERE id={$insert_id}");

		//短信发送记录
	    $wpdb->query("UPDATE wp_options SET option_value=option_value+1 WHERE option_name='used-message'");

	}else{
	    echo"Error: {$result->error->code}";
	    exit;
	}

	if ($name == "" || $email == "") {
		http_response_code(400);
		echo "name,mailbox required";
		exit;
	}

	/**** send sms end *****/

	/**** send email start *****/

	$body = "
		<h2>Reservation</h2>
		<p>Name: {$name}</p>
		<p>Phone: {$mobile}</p>
		<p>Date: {$date}</p>
		<p>Time: {$time}</p>
	";
	if(!empty($masseur)){
		$body .= "<p>Masseur: {$masseur}</p>";
	}

	if(!empty($message)){
		$body .= "<p>Special Request: {$message}</p>";
	}

	include ABSPATH.WPINC.'/class-phpmailer.php';
	include ABSPATH.WPINC.'/class-smtp.php';
	$mail             = new PHPMailer(); //new一个PHPMailer对象出来	
    $body             = eregi_replace("[\]",'',$body); //对邮件内容进行必要的过滤
    $mail->IsSMTP(); // 设定使用SMTP服务
    $mail->SMTPDebug  = 0;                       // 启用SMTP调试功能
    $mail->SMTPAuth   = true;                    // 启用 SMTP 验证功能
    $mail->SMTPSecure = "ssl";                   // 安全协议，可以注释掉
    $mail->Host       = 'mail.microuniver.com';  // SMTP 服务器
    $mail->Port       = 465;                     // SMTP服务器的端口号
    $mail->Username   = 'client@microuniver.com';// SMTP服务器用户名，PS：我乱打的
    $mail->Password   = 'Microuniver668';        // SMTP服务器密码
    $mail->From       = "client@microuniver.com";//发件人地址
    $mail->FromName   = "MICROUNIVER";           //发件人姓名
    $mail->Subject    = 'Reservation form '.$name;
    $mail->MsgHTML($body);
    $address = 'client@microuniver.com';
    $mail->AddAddress($address, '');
    if(!$mail->Send()) {
        echo 'Mailer Error:'.$mail->ErrorInfo;
        exit;
    } else {
		//发邮件成功
		$wpdb->query("UPDATE {$table} SET sendEmail=1 WHERE id={$insert_id}");
    }

	/**** send sms end *****/

	echo '200';

} else {
	// 不是一个POST请求，设置一个403（禁止）响应代码
	http_response_code(403);
	echo "403";
}
 
?>