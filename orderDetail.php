<?php
session_start();

/*error_reporting(E_ALL);
ini_set("display_errors", 1);*/
// 주문번호
$order_no = $_GET['order_no'];
$login_id = $_SESSION['LOGIN_ID'];

$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');


// 상품정보 조회
$sqlOrderProductInfo = "SELECT   OPL.ORDER_NO                       
                      ,  OPL.PRODUCT_SEQ                       
                      ,  OPL.PRODUCT_NAME                       
                      ,  OPL.PRODUCT_COLOR                       
                      ,  OPL.PRODUCT_SIZE                       
                      ,  OPL.PRODUCT_NUMBER                       
                      ,  OPL.PRODUCT_PRICE                       
                      ,  OPL.PRODUCT_DELIVERY_FEE                       
                      ,  OPL.PRODUCT_ORDER_PRICE                       
                      ,  OPL.SRC  
                      ,  P.FIRST_CATEGORY  
        FROM ORDER_PRODUCT_LIST OPL
        INNER JOIN PRODUCT P ON OPL.PRODUCT_SEQ = P.PRODUCT_SEQ
        WHERE ORDER_NO = '$order_no'
        ";
$resultOrderProductInfo = mysqli_query($conn, $sqlOrderProductInfo);

//결제정보 조회
$sqlPaymentInfo = "SELECT   PAYMENT_PERSON_ID                       
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
        FROM PAYMENT_LIST
        WHERE ORDER_NO = '$order_no'
        ";

$resultPaymentInfo = mysqli_query($conn, $sqlPaymentInfo);
$rowPaymentInfo = mysqli_fetch_array($resultPaymentInfo);

//주문정보 조회
$sqlOrderList = "SELECT   ORDER_NO
                        , ORDER_PERSON_ID
                        , ORDER_NAME
                        , ORDER_PERSON_PHONE_NUM
                        , EMAIL
                        , RECIPIENT
                        , RECIPIENT_PHONE_NUM
                        , ZIP_CODE
                        , ORDER_ADDRESS
                        , DELIVER_MSG
                        , ORDER_PRICE
                        , ORDER_STATE 
                        , ORDER_DATETIME
                        , INVOICE_NUMBER
        FROM ORDER_LIST
        WHERE ORDER_NO = '$order_no'
        ";

$resultOrderList = mysqli_query($conn, $sqlOrderList);
$rowOrderInfo = mysqli_fetch_array($resultOrderList);

?>
<!DOCTYPE html>
<html>
<?php
include 'head.php'
?>
<style>
    .text-left{
        text-align: left;
    }
</style>
<body>
<!-- navbar-->
<header class="header mb-5">
    <!--
    *** TOPBAR ***
    _________________________________________________________
    -->
    <?php
    include 'topbar.php'
    ?>
    <!-- *** TOP BAR END ***-->

    <!--
    *** HEADER ***
    _________________________________________________________
    -->
    <?php
    include 'header.php'
    ?>
    <!-- *** HEADER END ***-->

    <div id="all">
    <div id="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-10">
                    <!-- breadcrumb-->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">홈</a></li>
                            <li aria-current="page" class="breadcrumb-item active">주문상세</li>
                        </ol>
                    </nav>
                </div>
                <div id="checkout" class="col-lg-10">
                    <div class="box">
                        <h1>주문 상세</h1><br>
                        <h3>상품정보</h3>
                        <!--<div class="nav flex-column flex-sm-row nav-pills"><a href="checkout1.php" class="nav-link flex-sm-fill text-sm-center"> <i class="fa fa-map-marker">                  </i>Address</a><a href="checkout2.php" class="nav-link flex-sm-fill text-sm-center"> <i class="fa fa-truck">                       </i>Delivery Method</a><a href="checkout3.php" class="nav-link flex-sm-fill text-sm-center"> <i class="fa fa-money">                      </i>Payment Method</a><a href="#" class="nav-link flex-sm-fill text-sm-center active"> <i class="fa fa-eye">                     </i>Order Review</a></div>-->
                        <div class="content">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr style="border-top: 2px solid black;">
                                        <th colspan="2" style="text-align: center;">상품정보</th>
                                        <th>수량</th>
                                        <th>판매금액</th>
                                        <th>배송비</th>
                                        <th>주문금액</th>
                                        <?if($rowOrderInfo['ORDER_STATE'] == '배송중' || $rowOrderInfo['ORDER_STATE'] == '배송완료') {?>
                                        <th>송장번호</th>
                                        <?}?>
                                        <th>주문상태</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?while($rowProductInfo = mysqli_fetch_array($resultOrderProductInfo)) {
                                        ?>
                                        <tr class="product_info">
                                            <td style="text-align: center;"><a href="/mall/detail.php?menu_no=<?echo $rowProductInfo['FIRST_CATEGORY']?>&product_no=<?echo $rowProductInfo['PRODUCT_SEQ']?>"><img class="product_img" src="<?echo $rowProductInfo['SRC']?>" alt="<?echo $rowProductInfo['PRODUCT_NAME']?>"></a></td>
                                            <td><span class="product_name"><a href="#"><?echo $rowProductInfo['PRODUCT_NAME']?></a></span><br>색상: <span class="product_color"><?echo $rowProductInfo['COLOR']?></span> 사이즈: <span class="product_size"><?echo $rowProductInfo['SIZE']?></span></td>
                                            <td class="product_number"><?echo $rowProductInfo['PRODUCT_NUMBER']?></td>
                                            <td class="product_price"><?echo $rowProductInfo['PRODUCT_PRICE']?></td>
                                            <td class="product_delivery_fee">0원</td>
                                            <td><span style="font-weight: bold; color: red;" class="product_order_price"><?echo $rowProductInfo['PRODUCT_PRICE']?></span></td>
                                            <?if($rowOrderInfo['ORDER_STATE'] == '배송중' || $rowOrderInfo['ORDER_STATE'] == '배송완료') {?>
                                            <td class="invoice_number"><?echo $rowOrderInfo['INVOICE_NUMBER']?></td>
                                            <?}?>
                                            <td class="product_price"><?echo $rowOrderInfo['ORDER_STATE']?><?if($rowOrderInfo['ORDER_STATE'] == '배송중'){?><br><button onclick="purcharseCompl(<?echo $rowOrderInfo['ORDER_NO']?>)" id="btn_purchase_compl" class="btn btn-info">구매확정</button> <?}?></td>
                                        </tr>
                                    <?}?>

                                    </tbody>
                                </table>
                            </div>
                            <br><br><br>
                            <h3>결제정보</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr style="border-top: 2px solid black;">
                                        <th style="width: 20%; " >결제금액</th>
                                        <td style="text-align: left;"><? echo $rowPaymentInfo['PAYMENT_PRICE']?>원</td>
                                    </tr>
                                </table>
                            </div>
                            <br><br><br>

                            <h3>구매자 정보</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr style="border-top: 2px solid black;">
                                        <th style="width: 20%;">주문하신분</th>
                                        <td><?echo $rowOrderInfo['ORDER_NAME']?></td>
                                    </tr>
                                    <tr >
                                        <th>이메일주소</th>
                                        <td><?echo $rowOrderInfo['EMAIL']?></td>
                                    </tr>
                                    <tr >
                                        <th>휴대폰번호</th>
                                        <td><?echo $rowOrderInfo['ORDER_PERSON_PHONE_NUM']?></td>
                                    </tr>
                                </table>
                            </div>
                            <br><br><br>

                            <h3>배송 정보</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr style="border-top: 2px solid black;">
                                        <th style="width: 20%;">수령인</th>
                                        <td><?echo $rowOrderInfo['RECIPIENT']?></td>
                                    </tr>
                                    <tr >
                                        <th>휴대폰번호</th>
                                        <td><?echo $rowOrderInfo['RECIPIENT_PHONE_NUM']?></td>
                                    </tr>
                                    <tr >
                                        <th>주소</th>
                                        <td>(<?echo $rowOrderInfo['ZIP_CODE']?>)<br><?echo $rowOrderInfo['ORDER_ADDRESS']?></td>
                                    </tr>
                                    <tr >
                                        <th>배송메시지</th>
                                        <td><?echo $rowOrderInfo['DELIVER_MSG']?></td>
                                    </tr>
                                </table>
                            </div>
                            <!-- /.table-responsive-->
                        </div>
                    </div>
                    <!-- <form> -->
                    <!-- /.box-->
                </div>
            </div>
        </div>
    </div>
</div>
<!--
*** FOOTER ***
_________________________________________________________
-->
<?php
include 'footer.php'
?>
<!-- *** FOOTER END ***-->


<!--
*** COPYRIGHT ***
_________________________________________________________
-->
<?php
include 'copyright.php'
?>
<!-- *** COPYRIGHT END ***-->

<!-- JavaScript files-->
<?php
include 'jsfile.php'
?>
    <script>
        // 쇼핑계속하기
        $('#btn_shopping_proceed').on("click", function(){
           location.href =  'category.php?menu_no=5';
        });

        // 주문내역조회
        $('#btn_select_order_list').on("click", function(){
            location.href ='mypage.php?mypage_no=1'
        });

        // 구매확정
        function purcharseCompl(order_no){
            if(confirm("구매확정 하시겠습니까?")){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/product/updateOrderState.php',
                    data: {
                        order_no: order_no,
                        changed_state: '2'
                    },

                    success: function (json) {
                        if (json.result == 'ok') {
                            alert("구매확정 되었습니다.");
                            location.reload();
                        } else {
                            alert("구매확정 처리가 완료되지 않았습니다.");
                        }
                    },
                    error: function (request, status, error) {
                        alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);

                        //alert("에러가 발생했습니다.");
                    }
                });
            }
        }
    </script>
</body>
</html>