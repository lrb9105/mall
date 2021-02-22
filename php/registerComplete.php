<?php
/* 회원가입 모듈
    1.
*/

// 클라이언트로부터 받은 회원정보
$login_id = $_POST['login_id'];
$password = $_POST['password'];
$name = $_POST['name'];
$email = $_POST['email'];
$zip_code = $_POST['zip_code'];
$address_basic = $_POST['address_basic'];
$address_detail = $_POST['address_detail'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 회원정보 입력 쿼리
$sql  = "
    INSERT INTO USER 
    VALUES (
            '$login_id',
            '$password',
            '$name',
            '$zip_code',
            '$address_basic',
            '$address_detail',
            '$email',
            NOW(),
            NULL
    )";

// 회원정보 db에 입력
$result = mysqli_query($conn, $sql);

// insert가 실패했다면 false, 성공이라면 ok
if($result === false){
    echo json_encode(array('result'=>'fail'));
} else{
    echo json_encode(array('result'=>'ok'));
}