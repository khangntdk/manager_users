<?php
if(!defined('_CODE')){
    die('Access dennied...');
    
}
    $data = [
        'pageTitle' => 'Đăng nhập tài khoản'
    ];

    layouts('header', $data);
if(isLogin()){
    redirect('?module=home&action=dashboard');
}

  if(isPost()){
    $filterAll = filter();
    if(!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))){
    //kiểm tra đăng nhập
    $email = $filterAll['email'];
    $password = $filterAll['password'];
    
    //truy vấn lấy thông tin user theo email
        $userQuerry = oneRaw("SELECT password, id FROM users WHERE email = '$email' ");
        
        if(!empty($userQuerry)){
            $passwordHash = $userQuerry['password'];
            $userId = $userQuerry['id'];
            if(password_verify($password, $passwordHash)){

                //tạo token login
                $tokenLogin = sha1(uniqid() . time());

                //insert vào bảng login_token
                $dataInsert = [
                    'user_id' => $userId,
                    'token' => $tokenLogin,
                    'create_at' => date('Y-m-d H:i:s')
                ];
                $insertStatus = insert('login_token', $dataInsert);
                if($insertStatus){
                    //insert thành công
                    //lưu login token vào session
                    setSession('login_token', $tokenLogin);
                    redirect('?module=home&action=dashboard');

                }else{
                    setFlashData('smg', 'Không thể đăng nhập, vui lòng thử lại sau.');
                    setFlashData('smg_type', 'danger');
                }

            }else{
                setFlashData('smg', 'Mật khẩu không chính xác.');
                setFlashData('smg_type', 'danger');
                
            }
        }else{
            setFlashData('smg', 'Vui lòng nhập email và mật khẩu.');
            setFlashData('smg_type', 'danger');
            
        }

    }else{
        setFlashData('smg', 'Email không tồn tại.');
        setFlashData('smg_type', 'danger');
    }
        redirect('?module=auth&action=login');

  }
    $smg = getFlashData('smg');
    $smg_type = getFlashData('smg_type');

?>

<div class="row">
    <div class="col-4" style=" margin: 50px auto; ">
        <h2 class="text-center text-uppercase" >Đăng nhập quản lý users</h2>
            <?php
            if(!empty($smg)){
                getSmg($smg, $smg_type);
            }
            ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email">

            </div>
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="text" class="form-control" placeholder="Nhập mật khẩu">
            </div>
            <button type="submit" class="mg-btn mg-form btn btn-primary" >Đăng nhập</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>

<?php
    layouts('footer');
?>