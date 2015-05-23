<?php

// Application E-mail Notification
// This code sends an email to the relevant employer and applicant via the Job Post page contact form

send_mail_employer();
send_mail_applicant();

function set_html_content_type() {
	return 'text/html';
}

function send_mail_employer() {
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$headers[] = 'From:'.$_POST['app-firstname'].' '.$_POST['app-lastname'].' <'.$_POST['app-email'].'>';
	
	$to = get_the_author_meta('user_email', get_the_author_meta('ID'));
	$subject = 'Job Application: ' . get_the_title();

	$body = '';
	foreach($_POST as $post) $body .= $post.'<br/>';

	$attachments = array();
	if(isset($_POST['file-attachment']) && $_POST['file-attachment'] != '') {
		$upload_dir = wp_upload_dir();
		$filename = explode('/', $_POST['file-attachment']);
		$attachments = array($upload_dir["path"].'/'.end($filename));
	}

	wp_mail($to, $subject, $body, $headers, $attachments);
}

function send_mail_applicant() {
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$headers[] = 'From:'.$_POST['app-firstname'].' '.$_POST['app-lastname'].' <'.$_POST['app-email'].'>';

	$to = array($_POST['app-email']);
	$subject = 'You have applied for job ' . get_the_title();
	$body = $_POST['app-content'];

	wp_mail($to, $subject, $body, $headers);
}