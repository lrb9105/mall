<?php
session_start();
// 상세페이지에서 받아온 하나의 product_no, option1(색상), option2(사이즈), 수량
$product_no = $_POST['product_no'];
$option1 = $_POST['option1'];
$option2 = $_POST['option2'];
$product_number = $_POST['product_number'];
$menu_no = $_POST['menu_no'];
$delivery_payment = $_POST['delivery_payment']; //배송비 결제방식 0: 선결제, 1:착불

// 총 결제금액
$total_price = 0;
// 장바구니에서 받아온 여러개의 product_no

$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
$login_id = $_SESSION['LOGIN_ID'];

// 사용자정보 조회
$sqlUserInfo = "SELECT LOGIN_ID
                     , NAME
                     , ZIP_CODE
                     , ADDRESS_BASIC
                     , ADDRESS_DETAIL
                     , EMAIL_FRONT
                     , EMAIL_BACK
                     , PHONE_NUM1
                     , PHONE_NUM2
                     , PHONE_NUM3
        FROM USER
        WHERE LOGIN_ID = '$login_id'
        ";

$resultUserInfo = mysqli_query($conn, $sqlUserInfo);
$rowUserInfo = mysqli_fetch_array($resultUserInfo);

/* 상품정보 */

// 장바구니 - 상품 여러개인 경우 모든 상품번호를 붙인 문자열을 넘겨 줌($product_no = "1 AND 2 AND 3")
if($product_no != null || $product_no != '' ){
}

$sqlProductInfo = "SELECT P.PRODUCT_SEQ,
                P.FIRST_CATEGORY, 
                P.SECOND_CATEGORY,
                P.PRODUCT_NAME,
                P.PRODUCT_PRICE,
                P.PRODUCT_PRICE_SALE,
                P.MATERIAL,
                P.MANUFACTURER,
                P.COUNTRY_OF_MANUFACTURER,
                P.CLEANING_METHOD,
                P.DETAIL_INFO,
                P.CRE_DATETIME,
                F.SAVE_PATH
        FROM PRODUCT P
        INNER JOIN FILE F ON P.PRODUCT_SEQ = REF_SEQ
        WHERE P.PRODUCT_SEQ = '$product_no'
        AND F.TYPE = 0
        ";
$resultProductInfo = mysqli_query($conn, $sqlProductInfo);
$countProductInfo = mysqli_num_rows($resultProductInfo);

//$rowProductInfo = mysqli_fetch_array($resultProductInfo);
$product_name = '';

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
                            <li aria-current="page" class="breadcrumb-item active">주문하기</li>
                        </ol>
                    </nav>
                </div>
                <div id="checkout" class="col-lg-10">
                    <div class="box">
                        <h1>주문결제</h1><br><br>
                        <h3>결제상품</h3>
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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?while($rowProductInfo = mysqli_fetch_array($resultProductInfo)) {
                                        $current_price = $rowProductInfo['PRODUCT_PRICE_SALE'] * $product_number;
                                        $total_price += $current_price;
                                        $current_product_name = $rowProductInfo['PRODUCT_NAME'];
                                        $product_name += $current_product_name + ' ';
                                        ?>
                                        <tr class="product_info">
                                            <td style="text-align: center;"><a href="/mall/detail.php?menu_no=<?echo $menu_no?>&product_no=<?echo $product_no?>"><img class="product_img" src="<?echo $rowProductInfo['SAVE_PATH']?>" alt="<?echo $current_product_name?>"></a></td>
                                            <td><span class="product_name"><a href="#"><?echo $rowProductInfo['PRODUCT_NAME']?></a></span><br>색상: <span class="product_color"><?echo $option1?></span> 사이즈: <span class="product_size"><?echo $option2?></span><input class="product_no" value="<?echo $rowProductInfo['PRODUCT_SEQ']?>" type="hidden"></td>
                                            <td class="product_number"><?echo $product_number?></td>
                                            <td class="product_price"><?echo $rowProductInfo['PRODUCT_PRICE_SALE']?>원</td>
                                            <?if($delivery_payment =='0'){?>
                                                <td class="product_delivery_fee">2,500원</td>
                                            <?} else {?>
                                                <td class="product_delivery_fee">0원</td>
                                            <?}?>
                                            <td><span style="font-weight: bold; color: red;" class="product_order_price"><?echo $current_price?>원</span></td>
                                        </tr>
                                    <?}?>

                                    </tbody>
                                </table>
                            </div>
                            <br><br><br>
                            <h3>결제정보</h3>
                            <div class="table-responsive">
                                <table class="table" style="text-align: center;">
                                    <thead>
                                    <tr style="border-top: 2px solid black;">
                                        <th>상품가격</th>
                                        <th></th>
                                        <th>총 배송비</th>
                                        <th><span style="font-weight: bold; font-size: 22px; text-align: right;">총 구매금액</span></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><span style="font-weight: bold;"><?echo $total_price?>원</span></td>
                                        <td> + </td>
                                        <?if($delivery_payment =='0'){?>
                                            <td><span style="font-weight: bold;">2,500원</span></td>
                                            <td><span id="total_price" style="font-weight: bold; font-size: 22px;"><?echo $total_price + 2500?></span>원</td>
                                        <?} else {?>
                                            <td><span style="font-weight: bold;">0원</span></td>
                                            <td><span id="total_price" style="font-weight: bold; font-size: 22px;"><?echo $total_price?></span>원</td>
                                        <?}?>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br><br><br>
                            <h3>주문자 정보</h3>
                            <div>
                                <table class="table">
                                    <tr style="border-top: 2px solid black;">
                                        <th style="text-align: center;">주문자</th>
                                        <td><input name="order_person" class="text-left"  id="order_person" style="width: 50%;"type="text" value="<?echo $rowUserInfo['NAME']?>"></td>
                                    </tr>
                                    <tr >
                                        <th style="text-align: center;">휴대폰번호</th>
                                        <td><input name="order_phone_num1" class="text-left" id="order_phone_num1" style="width: 16%;" type="text" value="<?echo $rowUserInfo['PHONE_NUM1']?>"> <span>-</span> <input name="order_phone_num2" class="text-left" id="order_phone_num2" style="width: 16%;"type="text" value="<?echo $rowUserInfo['PHONE_NUM2']?>"> <span>-</span> <input name="order_phone_num3" class="text-left" id="order_phone_num3" style="width: 16%;"type="text" value="<?echo $rowUserInfo['PHONE_NUM3']?>"></td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center;">이메일</th>
                                        <td><input name="email_front" class="text-left" id="email_front" style="width: 24%;" type="text" value="<?echo $rowUserInfo['EMAIL_FRONT']?>"> <span>@</span> <input name="email_back" class="text-left" id="email_back" style="width: 24%;" type="text" value="<?echo $rowUserInfo['EMAIL_BACK']?>"></td>
                                    </tr>
                                </table>
                            </div>

                            <br><br><br>
                            <h3>배송 정보</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr style="border-top: 2px solid black;">
                                        <th style="text-align: center;">배송지 선택</th>
                                        <td>
                                            <label><input class="address" type="radio" name="address" value="origin_address" checked>기존 배송지</label>
                                            <label><input class="address" type="radio" name="address" value="new_address">새로운 배송지</label>
                                        </td>
                                    </tr>
                                    <tr >
                                        <th style="text-align: center;">수령인</th>
                                        <td><input name="recipient" class="text-left delivery" id="recipient" style="width: 50%;"type="text" value="<?echo $rowUserInfo['NAME']?>"></td>
                                    </tr>
                                    <tr >
                                        <th style="text-align: center;">휴대폰번호</th>
                                        <td><input class="text-left delivery" id="phone_num1" style="width: 16%;" type="text" value="<?echo $rowUserInfo['PHONE_NUM1']?>"> <span>-</span> <input class="text-left delivery" id="phone_num2" style="width: 16%;"type="text" value="<?echo $rowUserInfo['PHONE_NUM2']?>"> <span>-</span> <input class="text-left delivery" id="phone_num3" style="width: 16%;"type="text" value="<?echo $rowUserInfo['PHONE_NUM3']?>"></td>
                                    </tr>
                                    <tr >
                                        <th style="text-align: center;">주소</th>
                                        <td>
                                            <input name="zip_code" class="text-left delivery" id="zip_code" style="width: 25%;" type="text"  placeholder="우편번호"value="<?echo $rowUserInfo['ZIP_CODE']?>" readonly>&nbsp;&nbsp;<span><button class="btn btn-info">주소찾기</button></span><br>
                                            <input name="address_basic" class="text-left delivery" id="address_basic" style="width: 50%;"type="text" placeholder="주소" value="<?echo $rowUserInfo['ADDRESS_BASIC']?>" readonly><br>
                                            <input name="address_detail" class="text-left delivery" id="address_detail"style="width: 50%;"type="text" placeholder="상세주소" value="<?echo $rowUserInfo['ADDRESS_DETAIL']?>">
                                        </td>
                                    </tr>
                                    <tr >
                                        <th style="text-align: center;">배송메시지</th>
                                        <td><textarea name="deliver_msg" id="deliver_msg" rows="5" style="width: 50%;"></textarea></td>
                                    </tr>
                                </table>
                            </div>

                            <br><br><br>
                            <h3>결제 하기</h3>
                            <div>
                                <table class="table">
                                    <tr style="border-top: 2px solid black;">
                                        <th><button id="btn_payment" style=" width: 30%; height: 50px;" class="btn btn-warning center">결제하기</button></th>
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
    <script type="text/javascript" src="https://cdn.iamport.kr/js/iamport.payment-1.1.5.js"></script>
    <script>
        $('.address').on("click", function(){
            // 새주소지
            if($(this).val() == 'new_address'){
                $('.delivery').val('');
            } else{
                $('#recipient').val('<?echo $rowUserInfo['NAME']?>');
                $('#phone_num1').val('<?echo $rowUserInfo['PHONE_NUM1']?>');
                $('#phone_num2').val('<?echo $rowUserInfo['PHONE_NUM2']?>');
                $('#phone_num3').val('<?echo $rowUserInfo['PHONE_NUM3']?>');
                $('#zip_code').val('<?echo $rowUserInfo['ZIP_CODE']?>');
                $('#address_basic').val('<?echo $rowUserInfo['ADDRESS_BASIC']?>');
                $('#address_detail').val('<?echo $rowUserInfo['ADDRESS_DETAIL']?>');
            }
        });

        $('#btn_payment').on("click", function(){
            let paymentInfoArr = new Array();
            let paymentInfoJson = null;

           if(confirm("결제를 진행하시겠습니까?")){
               let IMP = window.IMP; // 생략가능
               IMP.init('imp47249292'); // 'iamport' 대신 부여받은 "가맹점 식별코드"를 사용

               IMP.request_pay({
                   pg : 'inicis', // version 1.1.0부터 지원.
                   pay_method : 'card',
                   merchant_uid : 'merchant_' + new Date().getTime(),
                   name : '상품',
                   amount : $('#total_price').text(),
                   buyer_email : $('#email_front').val() + '@' + $('#email_back').val(),
                   buyer_name : $('#order_person').val(),
                   buyer_tel : $('#phone_num1').val() + '-' + $('#phone_num2').val() + '-' + $('#phone_num3').val(),
                   buyer_addr : $('#address_basic').val() + ' ' + $('#address_detail').val(),
                   buyer_postcode : $('#zip_code').val(),
                   m_redirect_url : 'https://www.yourdomain.com/payments/complete'
               }, function(rsp) {
                   if ( rsp.success ) {
                       /* 결제완료 시 테이블에 저장
                       *  1. 주문테이블에 저장
                       *  2. 결제테이블에 저장
                       *  3. 주문한 상품 수량 -
                       * */

                       // 결제상품정보 생성
                       let paymentInfoArr = new Array();
                       let paymentInfoJson = null;

                       $('.product_info td').each(function(index, item){
                           if(index == 0){
                               paymentInfoJson = new Object();
                               paymentInfoJson.src = $(item).find('a img').attr("src");

                               //console.log($(item).find('a img').attr("src"));
                           } else if(index == 1){
                               paymentInfoJson.product_name = $(item).children('.product_name').text();
                               paymentInfoJson.product_color = $(item).find('.product_color').text();
                               paymentInfoJson.product_size = $(item).find('.product_size').text();
                               paymentInfoJson.product_no = $(item).find('.product_no').val();

                               //console.log($(item).children('.product_name').text());
                               //console.log($(item).find('.product_color').text());
                               //console.log($(item).find('.product_size').text());
                           } else if(index == 2){
                               paymentInfoJson.product_number = $(item).text();

                               //console.log($(item).text());
                           } else if(index == 3){
                               paymentInfoJson.product_price = $(item).text();

                               //console.log($(item).text());
                           } else if(index == 4){
                               paymentInfoJson.product_delivery_fee = $(item).text();

                               //console.log($(item).text());
                           } else if(index == 5){
                               paymentInfoJson.product_order_price = $(item).text();
                               //console.log($(item).text());
                               paymentInfoJson = JSON.stringify(paymentInfoJson);
                               paymentInfoArr.push(JSON.parse(paymentInfoJson));
                           }
                       });

                       console.log(paymentInfoArr);

                       $.ajax({
                           type: 'post',
                           dataType: 'json',
                           data: {
                               // 결제상품정보
                               total_price : $('#total_price').text(),
                               paymentInfoArr : JSON.stringify(paymentInfoArr),

                               // 주문자 정보
                               order_person : $('#order_person').val(),
                               order_phone_num : $('#order_phone_num1').val() + $('#order_phone_num2').val() +$('#order_phone_num3').val(),
                               email : $('#email_front').val() + '@'+$('#email_back').val(),

                               //배송정보
                               recipient : $('#recipient').val(),
                               phone_num : $('#phone_num1').val() + '-' + $('#phone_num2').val() + '-' + $('#phone_num3').val(),
                               zip_code : $('#zip_code').val(),
                               address : $('#address_basic').val() + ' ' + $('#address_detail').val(),
                               deliver_msg : $('#deliver_msg').val(),

                               //결제정보
                               payment_id : rsp.imp_uid,
                               merchant_uid : rsp.merchant_uid,
                               paid_amount : rsp.paid_amount,
                               apply_num : rsp.apply_num,
                               pay_method: rsp.pay_method


                               /*'고유ID : ' + rsp.imp_uid;
                               '상점 거래ID : ' + rsp.merchant_uid;
                               '결제 금액 : ' + rsp.paid_amount;
                               '카드 승인번호 : ' + rsp.apply_num;*/

                           },
                           url: '/mall/php/product/payProductCompl.php',

                           success: function (json) {
                               if (json.result == 'ok') { // 주문내역 및 결제내역을 DB에 저장 후 돌아오면 주문내역 페이지로 이동시켜준다.
                                   var msg = '결제가 완료되었습니다.';
                                   alert(msg);
                                   document.location.href = 'paymentCompl.php?order_no='+json.order_no
                               } else {
                                   var msg = '결제에 실패하였습니다.';
                                   msg += '에러내용 : ' + 1111
                                   alert(msg);
                               }
                           },
                           error: function () {
                               var msg = '결제에 실패하였습니다.';
                               msg += '에러내용 : ' + 2222;
                               alert(msg);
                           }
                       });
                   } else {
                       var msg = '결제에 실패하였습니다.';
                       msg += '에러내용 : ' + rsp.error_msg;
                       alert(msg);
                   }
               });
           }
        });
    </script>
</body>
</html>