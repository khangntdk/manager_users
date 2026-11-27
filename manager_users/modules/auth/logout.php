<?php
if (! defined('_CODE')) {
    die('Access dennied...');
}

if (isLogin()) {
    $token = getSession('login_token');
    delete('login_token', "token = '$token'");
    removeSession('login_token');
    redirect('?module=auth&action=login');
}
