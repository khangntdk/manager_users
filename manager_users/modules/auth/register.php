<?php
    if (! defined('_CODE')) {
    die('Access dennied...');

    }

    if (isPost()) {
    $filterAll = filter();
    $errors    = [];

    //validate fullname: bat buoc phai nhap
    if (empty($filterAll['fullname'])) {
        $errors['fullname']['required'] = 'Ho ten bat buoc phai nhap.';
    } else {
        if (strlen($filterAll['fullname']) < 5) {
            $errors['fullname']['min'] = 'Ho ten phai co it nhat 5 ky tu.';
        }
    }

    //validate email: bat buoc phai nhap, kiem tra dung dinh dang hay chua, email ton tai trong csdl chua;
    if (empty($filterAll['email'])) {
        $errors['email']['required'] = 'Email bat buoc phai nhap.';
    } else {
        $email = $filterAll['email'];
        $sql   = "SELECT id FROM users WHERE email = '$email'";
        if (getRaws($sql) > 0) {
            $errors['email']['uniqued'] = 'Email da ton tai.';
        }
    }

    //validate so dien thoai: kiem tra so dien thoaii co dung dinh dang khoong
    if (empty($filterAll['phone'])) {
        $errors['phone']['required'] = 'so dien thoai bat buoc phai nhap.';
    } else {
        if (! isPhone($filterAll['phone'])) {
            $errors['phone']['isPhone'] = 'so dien thoai khong hop le.';
        }
    }

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

    if (empty($errors)) {
        //xu ly insert
        $activeToken = sha1(uniqid() . time());
        $dataInsert  = [
            'fullname'    => $filterAll['fullname'],
            'email'       => $filterAll['email'],
            'phone'       => $filterAll['phone'],
            'password'    => password_hash($filterAll['password'], PASSWORD_DEFAULT),
            'activeToken' => $activeToken,
            'create_at'   => date('Y-m-d H:i:s'),

        ];
        $insertStatus = insert('users', $dataInsert);
        if ($insertStatus) {

            //tạo link active
            $linkActive = _WEB_HOST . '?module=auth&action=active&token=' . $activeToken;

            //thiết lập hàm gửi mail
            $subject  = $filterAll['fullname'] . 'Vui lòng kích hoạt tài khoản!!';
            $content  = 'Chào ' . $filterAll['fullname'] . '.</br>';
            $content .= 'Vui lòng ấn vào link bên dưới để kích hoạt tài khoản: </br>';
            $content .= $linkActive . '</br>';
            $content .= 'Trân trọng cảm ơn!!';

            //tiến hành gửi mail
            $sendMail = sendMail($filterAll['email'], $subject, $content);

            if ($sendMail) {
                setFlashData('smg', 'Đăng ký thành công, vui lòng kiểm tra email để kích hoạt tài khoản!!');
                setFlashData('smg_type', 'success');
            } else {
                setFlashData('smg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau!!');
                setFlashData('smg_type', 'danger');
            }

        } else {
            setFlashData('smg', 'Đăng ký không thành công!!');
            setFlashData('smg_type', 'danger');
        }

        redirect('?module=auth&action=register');
    } else {

        setFlashData('smg', 'vui long kiem tra lai du lieu!!');
        setFlashData('smg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $filterAll);
        redirect('?module=auth&action=register');
    }
    }

    layouts('header-login', $data);

    $smg      = getFlashData('smg');
    $smg_type = getFlashData('smg_type');
    $errors   = getFlashData('errors');
    $old      = getFlashData('old');

?>

<div class="row">
    <div class="col-4" style=" margin: 50px auto; ">
        <h2 class="text-center text-uppercase">Đăng ký tài khoản users</h2>
        <?php
            if (! empty($smg)) {
                getSmg($smg, $smg_type);
            }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Họ tên</label>
                <input name="fullname" type="text" class="form-control" placeholder="Nhập họ tên" value="<?php
                                                                                                              echo old('fullname', $old);
                                                                                                              ?>">
                <?php
                    echo form_error('fullname', '<span class="error">', '</span>', $errors);
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email" value="<?php
                                                                                                              echo old('email', $old);
                                                                                                              ?>">
                <?php
                    echo form_error('email', '<span class="error">', '</span>', $errors);
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Số điện thoại</label>
                <input name="phone" type="text" class="form-control" placeholder="Nhập số điện thoại"
                    value="<?php
                           echo old('phone', $old);
                           ?>">
                <?php
                    echo form_error('phone', '<span class="error">', '</span>', $errors);
                ?>
            </div>
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

            <button type="submit" class="mg-btn mg-form btn btn-primary">Đăng ký</button>
            <hr>

            <p class="text-center"><a href="?module=auth&action=login">Đăng nhập tài khoản</a></p>
        </form>
    </div>
</div>

<?php
layouts('footer-login');
?>