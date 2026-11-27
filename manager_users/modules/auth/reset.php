<!-- reset password -->
<?php
    if (! defined('_CODE')) {
    die('Access dennied...');
    }

    layouts('header-login');

    $token = filter()['token'];
    if (! empty($token)) {
    //truy vấn kiểm tra token
    $tokenQuery = oneRaw("SELECT id, fullname, email FROM users WHERE forgotToken = '$token'");
    if (! empty($tokenQuery)) {

        $userId = $tokenQuery['id'];
        if (isPost()) {
            $filterAll = filter();
            $errors    = []; //mảng chứa các lỗi
                             //validate password: bat buoc nhap, so ky tu 8<=;
            if (empty($filterAll['password'])) {
                $errors['password']['required'] = 'mat khau bat buoc phai nhap.';
            } else {
                if (strlen($filterAll['password']) < 8) {
                    $errors['password']['min'] = 'mat khau phai lon hon hoac bang 8 ky tu';
                }
            }

            //validate confirm_password: bat buoc  nhap, giong voi passoword da nhap
            if (empty($filterAll['password_confirm'])) {
                $errors['password_confirm']['required'] = 'mat khau bat buoc phai nhap.';
            } else {
                if ($filterAll['password'] != $filterAll['password_confirm']) {
                    $errors['password_confirm']['match'] = 'mat khau nhap lai khong dung.';
                }
            }
            //errors
            if (empty($errors)) {
                //xu ly việc update mật khẩu
                $passwordHash = password_hash($filterAll['password'], PASSWORD_DEFAULT);
                $dataUpdate   = [
                    'password'    => $passwordHash,
                    'forgotToken' => null,
                    'update_at'   => date('Y-m-d H:i:s'),
                ];

                $updateStatus = update('users', $dataUpdate, "id='$userId'");
                if ($updateStatus) {
                    setFlashData('smg', 'Đổi mật khẩu thành công!');
                    setFlashData('smg_type', 'success');

                    redirect('?module=auth&action=login');
                } else {
                    setFlashData('smg', 'Lỗi hệ thống, vui lòng thử lại sau!');
                    setFlashData('smg_type', 'danger');
                }

            } else {

                setFlashData('smg', 'vui long kiem tra lai du lieu!!');
                setFlashData('smg_type', 'danger');
                setFlashData('errors', $errors);
                redirect('?module=auth&action=reset&token=' . $token);
            }
        }

        $smg      = getFlashData('smg');
        $smg_type = getFlashData('smg_type');
        $errors   = getFlashData('errors');

        ?>
<!-- Form đặt lại mật khẩu -->
<div class="row">
    <div class="col-4" style=" margin: 50px auto; ">
        <h2 class="text-center text-uppercase">Đặt lại mật khẩu</h2>
        <?php
            if (! empty($smg)) {
                        getSmg($smg, $smg_type);
                    }
                ?>
        <form action="" method="post">

            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="text" class="form-control" placeholder="Nhập mật khẩu">
                <?php
                    echo form_error('password', '<span class="error">', '</span>', $errors);
                        ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Nhập lại password</label>
                <input name="password_confirm" type="text" class="form-control" placeholder="Nhập lại mật khẩu">
                <?php
                    echo form_error('password_confirm', '<span class="error">', '</span>', $errors);
                        ?>
            </div>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <button type="submit" class="mg-btn mg-form btn btn-primary">Gửi</button>
            <hr>

            <p class="text-center"><a href="?module=auth&action=login">Đăng nhập tài khoản</a></p>
        </form>
    </div>
</div>
<?php
    } else {
        getSmg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
    }

    } else {
    getSmg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
    }

?>

<?php
layouts('footer-login');
?>