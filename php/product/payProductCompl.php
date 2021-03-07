<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

/* 결제 완료 시 db에 저장
    1. 주문테이블에 데이터 저장
    2. 결제테이블에 데이터 저장
    3. 상품 수량 --
*/
// 쿼리의  결과를 저장하는 변수
$result = true;

// 결제상품정보
$total_price = $_POST['total_price'];
$paymentInfoArr = $_POST['paymentInfoArr'];
$paymentInfoArr = json_decode($paymentInfoArr);

/*$paymentInfoArr[0]->product_no;
$paymentInfoArr[0]->product_name;
$paymentInfoArr[0]->product_color;
$paymentInfoArr[0]->product_size;
$paymentInfoArr[0]->product_number;
$paymentInfoArr[0]->product_price;
$paymentInfoArr[0]->product_delivery_fee;
$paymentInfoArr[0]->product_order_price;
$paymentInfoArr[0]->src;*/

// 주문자 정보
$order_person_id  = $_SESSION['LOGIN_ID']  ;
$order_person  = $_POST['order_person'];
$order_phone_num  = $_POST['order_phone_num'];
$email  = $_POST['email'];

//배송정보
$recipient  = $_POST['recipient'];
$phone_num  = $_POST['phone_num'];
$zip_code  = $_POST['zip_code'];
$address  = $_POST['address'];
$deliver_msg  = $_POST['deliver_msg'];

//결제정보
/*'고유ID : ' + rsp.imp_uid;
'상점 거래ID : ' + rsp.merchant_uid;
'결제 금액 : ' + rsp.paid_amount;
'카드 승인번호 : ' + rsp.apply_num;
결제방법: pay_method*/

$payment_id  = $_POST['payment_id'];
$merchant_uid  = $_POST['merchant_uid'];
$paid_amount  = $_POST['paid_amount'];
$apply_num  = $_POST['apply_num'];
$pay_method = $_POST['pay_method'];

/* 입력정보 출력 */
/*echo json_encode(array('$order_person'=>$order_person ));
echo json_encode(array('$paymentInfoArr'=>$paymentInfoArr[0]->src ));
echo json_encode(array('$paymentInfoArr'=>count($paymentInfoArr)));
echo json_encode(array('$order_phone_num'=>$order_phone_num ));
echo json_encode(array('$email'=>$email ));
echo json_encode(array('$recipient'=>$recipient ));
echo json_encode(array('$phone_num'=>$phone_num ));
echo json_encode(array('$zip_code'=>$zip_code ));
echo json_encode(array('$address'=>$address ));
echo json_encode(array('$deliver_msg'=>$deliver_msg ));*/


//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

/* 주문테이블에 저장 */
// 주문번호
$order_no = date("YmdHis");

$sqlInsertOrderList = "INSERT INTO ORDER_LIST 
        VALUES (    '$order_no'
                  , '$order_person_id'              
                  , '$order_person'
                  , '$order_phone_num'
                  , '$email'
                  , '$recipient'
                  , '$phone_num'
                  , '$zip_code'
                  , '$address'
                  , '$deliver_msg'
                  , $total_price
                  , '결제완료'
                  , NOW()
                  , NULL 
        )";
$resultInsertOrderList = mysqli_query($conn, $sqlInsertOrderList);

// 실패 시 sql출력
if($resultInsertOrderList === false){
    $result = false;
    //echo $sqlInsertOrderList;
    echo '<script>alert("실패$sqlInsertOrderList")</script>';
    return;
}

// 주문상품 내역 테이블에 저장
for($i = 0; $i < count($paymentInfoArr); $i++){
    $product_no = $paymentInfoArr[$i]->product_no;
    $product_name = $paymentInfoArr[$i]->product_name;
    $product_color = $paymentInfoArr[$i]->product_color;
    $product_size = $paymentInfoArr[$i]->product_size;
    $product_number = $paymentInfoArr[$i]->product_number;
    $product_price = $paymentInfoArr[$i]->product_price;
    $product_delivery_fee = $paymentInfoArr[$i]->product_delivery_fee;
    $product_order_price = $paymentInfoArr[$i]->product_order_price;
    $src = $paymentInfoArr[$i]->src;

    $sqlInsertOrderProductList = "
        INSERT INTO ORDER_PRODUCT_LIST(
                     ORDER_NO                       
                  ,  PRODUCT_SEQ                       
                  ,  PRODUCT_NAME                       
                  ,  PRODUCT_COLOR                       
                  ,  PRODUCT_SIZE                       
                  ,  PRODUCT_NUMBER                       
                  ,  PRODUCT_PRICE                       
                  ,  PRODUCT_DELIVERY_FEE                       
                  ,  PRODUCT_ORDER_PRICE                       
                  ,  SRC                       
        ) 
        VALUES (     '$order_no'
                   ,  $product_no
                   , '$product_name'
                   , '$product_color'
                   , '$product_size'
                   ,  $product_number
                   , '$product_price'
                   , '$product_delivery_fee'
                   , '$product_order_price'
                   , '$src'
        )";
    $resultInsertOrderProductList = mysqli_query($conn, $sqlInsertOrderProductList);

    // 실패 시 sql출력
    if($resultInsertOrderProductList === false){
        $result = false;
        //echo $i.'번째: <br>'.$sqlInsertOrderProductList;
        echo '<script>alert("실패$sqlInsertOrderProductList")</script>';
        return;
    }
}
// 결제테이블에 저장
$sqlInsertPaymentList = "
        INSERT INTO PAYMENT_LIST(
                     PAYMENT_PERSON_ID                       
                  ,  ORDER_NO                       
                  ,  PAYMENT_PRICE                       
                  ,  PAYMENT_PERSON                       
                  ,  PAYMENT_STATE                       
                  ,  PAYMENT_DATETIME                       
                  ,  PAYMENT_ID                       
                  ,  MERCHANT_UID                       
                  ,  PAYMENT_AMMOUNT                       
                  ,  CARD_PERMIT_NO                       
                  ,  PAY_METHOD                       
        ) 
        VALUES (     '$order_person_id'
                   , '$order_no'
                   , $total_price
                   , '$order_person'
                   , '결제완료'
                   , NOW()
                   , '$payment_id'
                   , '$merchant_uid'
                   , '$paid_amount'
                   , '$apply_num'
                   , '$pay_method'
        )";
$resultInsertPaymentList = mysqli_query($conn, $sqlInsertPaymentList);

// 실패 시 sql출력
if($resultInsertPaymentList === false){
    $result = false;
    echo $sqlInsertPaymentList;
    echo '<script>alert("실패$sqlInsertPaymentList")</script>';
    return;
}

// 상품수량 --
for($i = 0; $i < count($paymentInfoArr); $i++){
    $product_number = $paymentInfoArr[$i]->product_number;
    $product_no = $paymentInfoArr[$i]->product_no;
    $product_color = $paymentInfoArr[$i]->product_color;
    $product_size = $paymentInfoArr[$i]->product_size;

    $sqlUpdateProductOption = "
        UPDATE PRODUCT_OPTION SET QUANTITY = QUANTITY - $product_number WHERE PRODUCT_SEQ = $product_no AND COLOR = '$product_color' AND SIZE = '$product_size'";
    $resultUpdateProductOption = mysqli_query($conn, $sqlUpdateProductOption);

    // 실패 시 sql출력
    if($resultUpdateProductOption === false){
        $result = false;
        echo $sqlUpdateProductOption;
        echo '<script>alert("실패$sqlUpdateProductOption")</script>';
        return;
    }
}


// insert가 실패했다면 false, 성공이라면 ok

if ($result === false) {
    echo json_encode(array('result'=>'fail' ));
} else {
    echo json_encode(array('result'=>'ok', 'order_no' => $order_no));
}