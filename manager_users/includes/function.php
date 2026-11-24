
<?php
if(!defined('_CODE')){
    die('Access dennied...');
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
function layouts($layoutName = 'header', $data=[]){
    if(file_exists(_WEB_PATH_TEMPLATES . '/layout/' .$layoutName. '.php')){
        require_once _WEB_PATH_TEMPLATES . '/layout/' .$layoutName. '.php';
    }
}

//ham gui mail
function sendMail($to, $subject, $content){

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'duykhang24032007@gmail.com';                     //SMTP username
    $mail->Password   = 'ezpc owjh wlkg usnx';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('duykhang24032007@gmail.com', 'Duy Khang');
    $mail->addAddress($to);     //Add a recipient
   
    //Content
    $mail -> CharSet = "UTF-8";
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $content;

    //PHPmailer ssl certificate verify failed
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $sendMail = $mail->send();
    if($sendMail){
        return $sendMail;
    }
    // echo 'Gửi thành công';
} catch (Exception $e) {
    echo "Gửi mail thất bại. Mailer Error: {$mail->ErrorInfo}";
}
}

//ktra phuong thuc GET
function isGet(){
    if($_SERVER['REQUEST_METHOD']=='GET'){
        return true;
    }else{
        return false;
    }
}

//kiemtra phuong thuc post
function isPost(){
    if($_SERVER['REQUEST_METHOD']=='POST'){
        return true;
    }else{
        return false;
    }
}

//ham filter loc du lieu
function filter(){
    $filterArr = [];
        if(isGet()){
            //xu li du lieu truoc khi hien thi ra
           // return $_GET;

           if(!empty($_GET)){
                foreach($_GET as $key => $value)
                    {
                        $key = strip_tags($key);
                        if(is_array($value)){
                            $filterArr[$key]= filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                        }else{
                            $filterArr[$key]= filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                        }
                    }
           }
        }

         if(isPost()){
               if(!empty($_POST)){
                foreach($_POST as $key => $value)
                    {
                        $key = strip_tags($key);
                        if(is_array($value)){
                            $filterArr[$key]= filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                        }else{
                            $filterArr[$key]= filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                        }
                    }
           }
        }
        return $filterArr;
}

//ham kiem tra email
function isEmail($email){
    $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}

//ham kiem tra so nguyen
function isNumberInt($number){
    $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
    return $checkNumber;
}

//ham kiem tra so thuc
function isNumberFloat($number){
    $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT);
    return $checkNumber;
}

//ham kiem tra so dien thoai
function isPhone($phone){
    //Die kien 1: ky tu dau tien la so 0
    $checkZero = false;

    if($phone[0] == 0){
        $checkZero = true;
        $phone = substr($phone,1);
    }

    //dieu kien 2: phia sau co 9 so
    $checkNumber = false;
    if(isNumberInt($phone) && (strlen($phone) == 9)){
        $checkNumber = true;
    }

    if($checkZero && $checkNumber){
        return true;
    }
        return false;
    
}

//thong bao loi
function getSmg($smg, $type='success'){
    echo '<div class="alert alert-'.$type.'">';
    echo $smg;
    echo '</div>';
}

//ham chuyen huong
function redirect($path='index.php'){
    header("Location: $path");
    exit;
}

//hàm thông báo lỗi
function form_error($fileName, $beforeHTML='', $afterHTML='', $errors){
    return (!empty($errors[$fileName])) ? '<span class="error">'.reset($errors[$fileName]).'</span>' :  null; 
}

//hàm hiển thị dữ liệu cũ 
function old($fileName, $oldData, $default = null){
    return (!empty($oldData[$fileName])) ? $oldData[$fileName] : $default;
}

//hàm kiểm tra trạng thái đăng nhập 
function isLogin(){
        $checkLogin = false;
    if(getSession('login_token')){
        $tokenLogin = getSession('login_token');
        //kiểm tra token có giống trong database không
        $queryToken = oneRaw("SELECT user_id FROM login_token WHERE token = '$tokenLogin'");

        if(!empty($queryToken)){
            $checkLogin = true;
        }else{
            removeSession('login_token');
        }
    }
    return $checkLogin;
}