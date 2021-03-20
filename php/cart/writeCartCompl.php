<?php
session_start();

/* 장바구니에 데이터를 저장한다.
    1.
*/
$loginId = $_SESSION['LOGIN_ID'];

// 상품 정보
$product_no = $_POST['product_no'];
$color = $_POST['color'];
$size = $_POST['size'];
$quantity = $_POST['quantity'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

$sql = "SELECT 1 FROM CART 
        WHERE PRODUCT_SEQ = '$product_no' 
        AND COLOR = '$color' 
        AND SIZE = '$size' 
        AND REGISTER_ID = '$loginId'";
$result = mysqli_query($conn, $sql);

if(mysqli_fetch_array($result)[0] != null){
    $sql = "UPDATE CART
            SET  QUANTITY = QUANTITY + $quantity
            WHERE PRODUCT_SEQ = '$product_no' 
            AND COLOR = '$color' 
            AND SIZE = '$size' 
            AND REGISTER_ID = '$loginId'";
    $result = mysqli_query($conn, $sql);

    if ($result === false) {
        echo json_encode(array('result'=>'fail'));
    } else {
        echo json_encode(array('result'=>'ok'));
    }

    return;
}

// 장바구니 추가
$sql = "
    INSERT INTO CART (
            REGISTER_ID  ,
            PRODUCT_SEQ  ,
            QUANTITY ,
            COLOR ,
            SIZE,
            CRE_DATETIME
    ) VALUES (
            '$loginId',
            $product_no ,
            $quantity,
            '$color' ,
            '$size',
            NOW()
    )";
$result = mysqli_query($conn, $sql);

// insert가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo json_encode(array('result'=>'fail'));
} else {
    echo json_encode(array('result'=>'ok'));
}