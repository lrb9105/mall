<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

/*로그인 모듈
    1. 아이디/비밀번호 db에서 확인
    2. 존재한다면 ok 반환
    3. 없다면 fail 반환
*/
$login_id = $_POST['login_id'];
$password = $_POST['password'];
$isChecked = $_POST['checked'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
$sql = "SELECT LOGIN_ID, NAME, USER_TYPE
        FROM USER 
        WHERE LOGIN_ID='$login_id' 
        AND PASSWORD='$password'
        ";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

if($isChecked == true) {
    setcookie("LOGIN_ID", $login_id, time() + 3600 * 7 * 24, '/');
    setcookie("PW", $password, time() + 3600 * 7 * 24, '/');
}

if($row == null){
    echo json_encode(array('result'=>'fail'));
    // 해당하는 값이 없다면 세션 삭제
    session_unset();
} else if($row[0] != null){
    $_SESSION['LOGIN_ID'] = $row[0];
    $_SESSION['NAME'] = $row[1];
    $_SESSION['USER_TYPE'] = $row[2];

    echo json_encode(array('result'=>'ok', 'login_id'=>$_SESSION['LOGIN_ID']));
} else{
    echo json_encode(array('result'=>'fail'));
    // 해당하는 값이 없다면 세션 삭제
    session_unset();
}