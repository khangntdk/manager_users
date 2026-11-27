<!-- các hằng sô cố định -->
<?php
    // $modules = 'home';
    // $action = 'dashboard';
    const _MODULE = 'home';
    const _ACTION = 'dashboard';
    const _CODE   = true;

    //thiet lap host
    define('_WEB_HOST', 'http://' . $_SERVER['HTTP_HOST'] . '/kyan_php/manager_users');
    define('_WEB_HOST_TEMPLATES', _WEB_HOST . '/templates');

    //thiet lap path
    define('_WEB_PATH', __DIR__);
    define('_WEB_PATH_TEMPLATES', _WEB_PATH . '/templates');

    // Thông tin kết nối
    const _HOST = 'localhost';
    const _DB   = 'khang_123';
const _USER = 'root';
const _PASS = '';