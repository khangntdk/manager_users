<?php
    if (! defined('_CODE')) {
    die('Access dennied...');

    }
    $data = [
    'pageTitle' => 'Quên mật khẩu',
    ];

    layouts('header-login', $data);
    if (isLogin()) {
    redirect('?module=home&action=dashboard');
    }

    if (isPost()) {
    $filterAll = filter();
    if (! empty($filterAll['email'])) {
        $email = $filterAll['email'];

        $queryUser = oneRaw("SELECT id FROM users WHERE email ='$email'");
        if (! empty($queryUser)) {
            $userId = $queryUser['id'];

            //tạo forgot token
            $forgotToken = sha1(uniqid() . time());

            $dataUpdate = [
                'forgotToken' => $forgotToken,

            ];

            $updateStatus = update('users', $dataUpdate, "id=$userId");
            if ($updateStatus) {
                //tạo link reset, khôi phục mật khẩu
                $linkReset  = _WEB_HOST . '?module=auth&action=reset&token=' . $forgotToken;
                //gửi mail cho người dùng
                $subject  = 'Yêu cầu khôi phục mật khẩu.';
                $content  = 'Chào bạn.</br>';
                $content .= 'Chúng tôi nhận được yêu cầu khôi phục mật khẩu từ bạn.
                Vui lòng click vào link sau để đổi lại mật khẩu: </br>';
                $content .= $linkReset . '</br>';
                $content .= 'Trân trọng!';

                $sendEmail = sendMail($email, $subject, $content);
                if ($sendEmail) {
                    setFlashData('smg', 'Vui lòng kiểm tra email để xem hướng dẫn đặt lại mật khẩu.');
                    setFlashData('smg_type', 'success');
                } else {
                    setFlashData('smg', 'Lỗi hệ thống, vui lòng thử lại sau!(email)');
                    setFlashData('smg_type', 'danger');
                }

            } else {
                setFlashData('smg', 'Lỗi hệ thống, vui lòng thử lại sau!');
                setFlashData('smg_type', 'danger');
            }

        } else {
            setFlashData('smg', 'Địa chỉ email không tồn tại trong hệ thống!');
            setFlashData('smg_type', 'danger');
        }

    } else {
        setFlashData('smg', 'Vui lòng nhập địa chỉ email!');
        setFlashData('smg_type', 'danger');
    }

    //redirect('?module=auth&action=forgot');

    }
    $smg      = getFlashData('smg');
    $smg_type = getFlashData('smg_type');

?>

<div class="row">
    <div class="col-4" style=" margin: 50px auto; ">
        <h2 class="text-center text-uppercase">Quên mật khẩu</h2>
        <?php
            if (! empty($smg)) {
                getSmg($smg, $smg_type);
            }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email">

            </div>

            <button type="submit" class="mg-btn mg-form btn btn-primary">Gửi</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Đăng nhập</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>

<?php
layouts('footer-login');
?>