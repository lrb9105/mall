<?php
/*id체크 모듈
    1. 아이디 존재하는지 확인
    2. 없다면 'ok'반환
    3. 있으면 'duplication'반환
*/

$name = $_POST['name'];
$phone_num1 = $_POST['phoneNum1'];
$phone_num2 = $_POST['phoneNum2'];
$phone_num3 = $_POST['phoneNum3'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
$sql = "SELECT LOGIN_ID
        FROM USER 
        WHERE NAME ='$name' 
        AND PHONE_NUM1 = '$phone_num1'
        AND PHONE_NUM2 = '$phone_num2'
        AND PHONE_NUM3 = '$phone_num3'
        ";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

// 해당 아이디가 존재한다면
if($row[0] != null){
    echo json_encode(array('result'=>'ok','id'=>$row[0]));
} else{ // 해당 아이디를 사용중이라면
    echo json_encode(array('result'=>'null'));
}