<?php
/* 회원가입 수정
    1.
*/
session_start();
$login_id = $_SESSION['LOGIN_ID'];
// 클라이언트로부터 받은 회원정보

$new_pw = $_POST['new_pw'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 회원정보 입력 쿼리
$sql  = "
       UPDATE USER 
       SET    PASSWORD =  '$new_pw'
        WHERE LOGIN_ID = '$login_id'
       ";

// 회원정보 db에 입력
$result = mysqli_query($conn, $sql);

// insert가 실패했다면 false, 성공이라면 ok
if($result === false){
    echo json_encode(array('result'=>'fail'));
} else{
    echo json_encode(array('result'=>'ok'));
}