<?php
/* 장바구니 수량을 수정한다.
    1.
*/

// 글 정보
$cart_seq = $_POST['cart_seq'];
$quantity = $_POST['quantity'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 회원정보 입력 쿼리
$sql = "
    UPDATE CART SET
            `QUANTITY`  = '$quantity'
    WHERE SEQ = '$cart_seq'";

// 댓글
$result = mysqli_query($conn, $sql);

// update가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo json_encode(array('result'=>'fail'));
} else {
    echo json_encode(array('result'=>'ok'));
}
