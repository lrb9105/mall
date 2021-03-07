<?php
/* 주문상태를 수정한다.
    1.
*/
error_reporting(E_ALL);
ini_set("display_errors", 1);

// 수정할 종류 0: 발주확인, 1:발송완료
$changed_state = $_POST['changed_state'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
$result = null;

if($changed_state == '0'){ //발주확인
    // 수정할 주문번호 리스트
    $order_no_list = $_POST['order_no_list'];

    // 발주완료
    $sql = "
    UPDATE ORDER_LIST SET ORDER_STATE = '상품준비중'
    WHERE ORDER_NO IN ($order_no_list) AND ORDER_STATE = '결제완료'";

    $result = mysqli_query($conn, $sql);
} elseif($changed_state == '1'){
    // 수정할 주문번호 리스트
    $order_no_list = $_POST['order_no_list'];

    // 송장번호 리스트
    $invoice_number_list = $_POST['invoice_number_list'];
    $order_no_list = explode(",", $order_no_list);
    $invoice_number_list = explode(",", $invoice_number_list);
    $cnt = count($invoice_number_list);

    // 발송완료
    for($i = 0; $i < $cnt; $i++){ //발송완료
        $sql = "
                UPDATE ORDER_LIST SET ORDER_STATE = '배송중'
                                    , INVOICE_NUMBER = $invoice_number_list[$i]
                WHERE ORDER_NO =$order_no_list[$i] AND ORDER_STATE = '상품준비중'";

        $result = mysqli_query($conn, $sql);
    }
} elseif($changed_state == '2'){ //구매확정
    $order_no = $_POST['order_no'];

    $sql = "
                UPDATE ORDER_LIST SET ORDER_STATE = '배송완료'
                WHERE ORDER_NO = '$order_no' AND ORDER_STATE = '배송중'";

    $result = mysqli_query($conn, $sql);
}


// update가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo json_encode(array('result'=>'fail'));
} else {
    echo json_encode(array('result'=>'ok'));
}