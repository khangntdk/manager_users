
<?php
if(!defined('_CODE')){
    die('Access dennied...');
}

layouts('header');

$token = filter()['token'];
if(!empty($token)){
    //truy vấn để kiểm tra token với database
    $tokenQuery = oneRaw("SELECT id FROM users WHERE activeToken = '$token'");
    if(!empty($tokenQuery)){
        $userId = $tokenQuery['id'];
        $dataUpdate = [
            'status' => 1,
            'activeToken' => null
        ];

        $updateStatus = update('users', $dataUpdate, "id = $userId");
        if($updateStatus){
            setFlashData('smg', 'Kích hoạt tài khoản thành công, bạn có thể đăng nhập ngay bây giờ!');
            setFlashData('smg_type', 'success');
        }else{
            setFlashData('smg', 'Kích hoạt tài khoản không thành công, vui lòng liên hệ quản trị viên!');
            setFlashData('smg_type', 'danger');

        }

        redirect('?module=auth&action=login');
    }else{
        getSmg('Liên kết không tồn tại hoặc đã hết hạn!', 'danger');
    }
}else{
    getSmg('Liên kết không tồn tại hoặc đã hết hạn!', 'danger');

}
?>

<h1>Active</h1>
<?php
 layouts('footer');
?>