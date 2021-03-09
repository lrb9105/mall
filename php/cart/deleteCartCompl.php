<?php
/* 장바구니에서 상품을 삭제한다.
    1.
*/
//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 삭제타입 1: 선택삭제
$type = $_POST['type'];

//선택삭제
if($type == 1){
    $cart_seq_list = $_POST['cart_seq_list'];
    $sql = "DELETE FROM CART WHERE SEQ IN ($cart_seq_list)";

    // 장바구니
    $result = mysqli_query($conn, $sql);
} else{
    // 장바구니 seq
    $cart_seq = $_POST['cart_seq'];
    $sql = "DELETE FROM CART WHERE SEQ = '$cart_seq'";

    // 장바구니
    $result = mysqli_query($conn, $sql);

}
// 회원정보 입력 쿼리

// delete가 실패했다면 false, 성공이라면 ok
if ($result == false) {
    echo json_encode(array('result'=>'fail'));
} else {
    echo json_encode(array('result'=>'ok'));
}