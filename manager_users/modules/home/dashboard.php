
<?php
if(!defined('_CODE')){
    die('Access dennied...');
}

$data = [
    'pageTitle' => 'Trang Dashboard'
];
layouts('header', $data);

//kiểm tra trạng thái đăng nhập
if(!isLogin()){
    redirect('?module=auth&action=login');
}
 
?>
<h1>Dashboard</h1>

<?php
layouts('footer');

?>