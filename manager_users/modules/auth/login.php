
<?php
if(!defined('_CODE')){
    die('Access dennied...');
    
}

    $data = [
        'pageTitle' => 'Đăng nhập tài khoản'
    ];

    layouts('header', $data);
 
  if(isPost()){
    $filterAll = filter();
  
  }

?>

<div class="row">
    <div class="col-4" style=" margin: 50px auto; ">
        <h2 class="text-center text-uppercase" >Đăng nhập quản lý users</h2>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email">
            </div>
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="password" class="form-control" placeholder="Nhập mật khẩu">
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