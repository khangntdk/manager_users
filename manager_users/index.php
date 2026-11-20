<?php
session_start();
require_once('config.php');
require_once('./includes/connect.php');

//thu vien php mailer
require_once('./includes/phpmailer/Exception.php');
require_once('./includes/phpmailer/PHPMailer.php');
require_once('./includes/phpmailer/SMTP.php');


require_once('./includes/function.php');
require_once('./includes/database.php');
require_once('./includes/session.php');

// $session_test = setSession('khang','values cua session khang');
// var_dump($session_test);
// removeSession('khang');
// echo getSession('khang');
// $content ='';
// $subject = '';
// sendMail('duykhang24032007@gmail.com', $subject, $content);

$module = _MODULE;
$action = _ACTION;

if(!empty($_GET['module'])){
    if(is_string($_GET['module'])){
        $module = trim($_GET['module']);
       

    }
}
if(!empty($_GET['action'])){
    if(is_string($_GET['action'])){
        $action = trim($_GET['action']);

    }
}


$path = 'modules/' . $module . '/' . $action . '.php';

if(file_exists($path)){
    require_once ($path);

}else{
    require_once('modules/error/404.php');
}