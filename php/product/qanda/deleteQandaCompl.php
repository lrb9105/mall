<?php
/* 상품을 삭제한다.
    1.
*/

// 상품 번호
$seq = $_POST['seq'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 회원정보 입력 쿼리
$sql = "UPDATE QANDA SET USE_YN = 'N' WHERE SEQ = $seq";
$result = mysqli_query($conn, $sql);

// delete가 실패했다면 false, 성공이라면 ok
if ($result == false) {
    echo json_encode(array('result'=>'fail'));
} else {
    echo json_encode(array('result'=>'ok'));
}