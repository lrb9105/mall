<?php
/*id체크 모듈
    1. 아이디 존재하는지 확인
    2. 없다면 'ok'반환
    3. 있으면 'duplication'반환
*/

$login_id = $_POST['login_id'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
$sql = "SELECT LOGIN_ID
        FROM USER 
        WHERE LOGIN_ID='$login_id' 
        ";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

// 해당 아이디를 사용하고 있지 않다면
if($row == null){
    echo json_encode(array('result'=>'ok'));
} else{ // 해당 아이디를 사용중이라면
    echo json_encode(array('result'=>'duplication'));
}