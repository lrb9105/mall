<?php
/*로그인 모듈
    1. 아이디/비밀번호 db에서 확인
    2. 존재한다면 ok 반환
    3. 없다면 fail 반환
*/
$login_id = $_POST['login_id'];
$password = $_POST['password'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
$sql = "SELECT LOGIN_ID
        FROM USER 
        WHERE LOGIN_ID='$login_id' 
        AND PASSWORD='$password'
        ";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

if($row == null){
    echo json_encode(array('result'=>'fail'));
} else if($row[0] != null){
    session_start();
    $_SESSION['LOGIN_ID'] = $row[0];
    echo json_encode(array('result'=>'ok', 'login_id'=>$_SESSION['LOGIN_ID']));
} else{
    echo json_encode(array('result'=>'fail'));
}