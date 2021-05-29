<?php
session_start();
$login_id = $_SESSION['LOGIN_ID'];

// mysql커넥션 연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

/* 데이터 가져오기 */
// 상품정보
$sqlCartInfo = "SELECT P.PRODUCT_SEQ,
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
                P.THICKNESS,
                P.REFLECTION,
                P.ELASTICITY,
                P.SEASON,
                P.FIT,
                C.SEQ CART_SEQ,
                C.QUANTITY,
                C.COLOR,
                C.SIZE,
                F.SAVE_PATH
        FROM PRODUCT P
        INNER JOIN CART C ON P.PRODUCT_SEQ = C.PRODUCT_SEQ
        INNER JOIN FILE F ON P.PRODUCT_SEQ = F.REF_SEQ
        WHERE C.REGISTER_ID = '$login_id'
        AND F.TYPE = 0
        AND P.SOLD_OUT_YN = 'N'
        ";
$resultCartInfo = mysqli_query($conn, $sqlCartInfo);
$count = mysqli_num_rows($resultCartInfo);

?>
<!DOCTYPE html>
<html>
<?php
include 'head.php'
?>
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
                <div class="col-lg-12">
                    <!-- breadcrumb-->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">홈</a></li>
                            <li aria-current="page" class="breadcrumb-item active">장바구니</li>
                        </ol>
                    </nav>
                </div>
                <div id="basket" class="col-lg-12">
                    <form method="post" action="checkout4.php" name="form_purchase">
                        <div class="box">
                            <h1>장바구니</h1>
                            <p class="text-muted">현재 장바구니에 <span style="font-weight: bold; color: red;"><?echo $count?></span>개의 아이템이 들어있습니다.</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr style="border-top: 2px solid black;">
                                        <th><input type="checkbox" id="chk_all"></th>
                                        <th colspan="2" style="text-align: center;">상품정보</th>
                                        <th>수량</th>
                                        <th>판매금액</th>
                                        <th>배송비</th>
                                        <th>주문금액</th>
                                        <th>주문</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <? $cnt = 0;
                                       $total_price = 0;
                                    while($rowCartInfo = mysqli_fetch_array($resultCartInfo)) {
                                        $product_name = '';
                                        $current_price = $rowCartInfo['PRODUCT_PRICE_SALE'] * $rowCartInfo['QUANTITY'];
                                        $total_price += $current_price;
                                        $current_product_name = $rowCartInfo['PRODUCT_NAME'];
                                        $product_name += $current_product_name + ' ';
                                        ?>
                                        <tr class="product_info">
                                            <td><input class="chk" type="checkbox" id="chk_<?echo $cnt?>"><input id="cart_seq_<?echo $cnt?>" value="<?echo $rowCartInfo['CART_SEQ']?>" hidden><input id="menu_no_<?echo $cnt?>" value="<?echo $rowCartInfo['FIRST_CATEGORY']?>" hidden></td>
                                            <td id="img_<?echo $cnt?>" style="text-align: center;"><a href="/mall/detail.php?menu_no=<?echo $rowCartInfo['SECOND_CATEGORY']?>&product_no=<?echo $rowCartInfo['PRODUCT_SEQ']?>"><img class="product_img" src="<?echo $rowCartInfo['SAVE_PATH']?>" alt="<?echo $current_product_name?>"></a></td>
                                            <td><span id="product_name_<?echo $cnt?>" class="product_name"><a href="#"><?echo $rowCartInfo['PRODUCT_NAME']?></a></span><br>색상: <span id="product_color_<?echo $cnt?>" class="product_color"><?echo $rowCartInfo['COLOR']?></span> /&nbsp;사이즈: <span id="product_size_<?echo $cnt?>" class="product_size"><?echo $rowCartInfo['SIZE']?></span><input id="product_no_<?echo $cnt?>" class="product_no" value="<?echo $rowCartInfo['PRODUCT_SEQ']?>" type="hidden"></td>
                                            <td class="product_number"><input id="product_number_<?echo $cnt?>" type="number" value="<?echo $rowCartInfo['QUANTITY']?>"><button type="button" onclick="modifyQuantity(<?echo $rowCartInfo['CART_SEQ']?>, <?echo $cnt?>);" style="margin-left: 3px;" id="btn_modify_quantity" class="btn btn-outline-dark">변경</button> </td>
                                            <td class="product_price" id="product_price_<?echo $cnt?>"><?echo number_format($rowCartInfo['PRODUCT_PRICE_SALE'])?>원</td>
                                            <td class="product_delivery_fee">0원</td>
                                            <td><span style="font-weight: bold; color: red;" class="product_order_price" id="product_order_price_<?echo $cnt?>"><?echo number_format($current_price)?>원</span></td>
                                            <td><button onclick="purchase(<?echo $cnt?>);" type="button" class="btn btn-info">구매하기</button><br><button type="button" onclick="deleteCart(<?echo $rowCartInfo['CART_SEQ']?>)" style="margin-top: 2px;"class="btn btn-success">삭제하기</button> </td>
                                        </tr>
                                        <?$cnt++;
                                    }?>
                                    </tbody>
                                </table>
                            </div>
                            <?if($count > 0) { ?>
                                <div>
                                    <button id="btn_delete_sel" type="button" class="btn btn-outline-secondary"><i class="fa fa-remove"></i>선택상품 삭제</button>
                                    <span style="font-size: 20px; font-weight: bold; float: right; margin-right: 60px;">전체가격:&nbsp;&nbsp;&nbsp;&nbsp;<?echo number_format($total_price)?>원</span>
                                </div>
                                <!-- /.table-responsive-->
                                <div class="box-footer d-flex justify-content-between flex-column flex-lg-row">
                                    <div class="left"></div>
                                    <div class="right">
                                        <button id="btn_purchase_sel" type="button" class="btn btn-warning">선택상품구매 <i class="fa fa-chevron-right"></i></button>
                                        <button id="btn_purchase_all" type="button" class="btn btn-primary">전체구매 <i class="fa fa-chevron-right"></i></button>
                                    </div>
                                </div>
                            <?}?>
                        </div>
                    </form>
                    <!-- /.box-->
                </div>
                <!-- /.col-md-3-->
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
        // 전체 체크, 해제
        $('#chk_all').on("click", function(){
            // 체크되어있다면
            if($(this).is(":checked")){
                $('.chk').prop("checked",true);
            } else{ //아니라면
                $('.chk').prop("checked",false);
            }
        });

        //수량 변경
        function modifyQuantity(seq, cnt) {
            if(confirm("수량을 변경하시겠습니까??")){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/cart/updateCartCompl.php',
                    data: {
                        cart_seq: seq,
                        quantity: $('#product_number_'+cnt).val()
                    },

                    success: function (json) {
                        if (json.result == 'ok') {
                            alert("수량을 변경했습니다.");
                            location.reload();
                        } else {
                            alert("수량변경에 실패했습니다!");
                        }
                    },
                    error: function () {
                        alert("에러가 발생했습니다.");
                    }
                });
            }
        }

        //장바구니 개별 삭제
        function deleteCart(seq){
            if(confirm("해당 상품을 장바구니에서 삭제하시겠습니까??")){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/cart/deleteCartCompl.php',
                    data: {
                        cart_seq: seq
                    },

                    success: function (json) {
                        if (json.result == 'ok') {
                            alert("삭제했습니다.");
                            location.reload();
                        } else {
                            alert("삭제에 실패했습니다!");
                        }
                    },
                    error: function () {
                        alert("에러가 발생했습니다.");
                    }
                });
            }
        }

        //장바구니 선택 삭제
        $('#btn_delete_sel').on("click", function(){
            let length = $('.chk').length;
            let i;

            // 선택하지 않으면 return;
            for(i = 0; i < length; i++){
                if($('#chk_'+i).prop("checked") == true){
                    console.log('11');
                    break;
                }else {
                    console.log('222');
                }
            }

            if(i == length){
                alert("항목을 선택해 주세요.");
                return;
            }

            if(confirm("삭제하시겠습니까?")){
                // 선택한 요소
                let cart_seq_list = 0;

                $('.chk').each(function(index, item){
                    if($(this).prop("checked") == true){
                        cart_seq_list = cart_seq_list + $('#cart_seq_'+index).val() + ', ';
                    }

                });

                cart_seq_list = cart_seq_list.substring(0, cart_seq_list.length -2);

                // 선택된 항목 삭제
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/cart/deleteCartCompl.php',
                    data: {
                        cart_seq_list: cart_seq_list,
                        type: 1
                    },

                    success: function (json) {
                        if (json.result == 'ok') {
                            alert("삭제 완료했습니다.");
                            location.reload();
                        } else {
                            alert("삭제에 실패했습니다.");
                        }
                    },
                    error: function (request, status, error) {
                        alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);

                        //alert("에러가 발생했습니다.");
                    }
                });
            }
        });

        // 개별구매
        function purchase(cnt){
            if(confirm("해당 상품을 구매하시겠습니까?")){
                //새로운 폼을 생성
                let newForm = $('<form></form>');
                // 폼의 속성값 추가
                newForm.attr("name","newForm");
                newForm.attr("method","post");
                newForm.attr("action","checkout4.php");

                newForm.append($('<input/>', {type: 'hidden', name: 'product_no', value:$('#product_no_'+cnt).val() }));
                newForm.append($('<input/>', {type: 'hidden', name: 'option1', value:$('#product_size_'+cnt).text() }));
                newForm.append($('<input/>', {type: 'hidden', name: 'option2', value: $('#product_color_'+cnt).text()}));
                newForm.append($('<input/>', {type: 'hidden', name: 'product_number', value: $('#product_number_'+cnt).val() }));
                newForm.append($('<input/>', {type: 'hidden', name: 'menu_no', value: $('#menu_no_'+cnt).val()}));
                newForm.append($('<input/>', {type: 'hidden', name: 'delivery_payment', value: 1 }));
                newForm.append($('<input/>', {type: 'hidden', name: 'cart_no', value: $('#cart_seq_'+cnt).val()}));

                newForm.appendTo('body');

                newForm.submit();
            }
        }

        // 선택한상품 구매
        $('#btn_purchase_sel').on("click", function (){
            let length = $('.chk').length;
            let i;

            // 선택하지 않으면 return;
            for(i = 0; i < length; i++){
                if($('#chk_'+i).prop("checked") == true){
                    console.log('11');
                    break;
                }else {
                    console.log('222');
                }
            }

            if(i == length){
                alert("항목을 선택해 주세요.");
                return;
            }

            if(confirm("해당 상품을 구매하시겠습니까?")){
                //새로운 폼을 생성
                let newForm = $('<form></form>');
                // 폼의 속성값 추가
                newForm.attr("name","newForm");
                newForm.attr("method","post");
                newForm.attr("action","checkout4.php");

                // 체크한 항목의 정보를 보낸다.
                $('.chk').each(function(index, item){
                    if($(this).prop("checked") == true){
                        newForm.append($('<input/>', {type: 'hidden', name: 'product_no[]', value:$('#product_no_'+index).val() }));
                        newForm.append($('<input/>', {type: 'hidden', name: 'option1[]', value:$('#product_size_'+index).text() }));
                        newForm.append($('<input/>', {type: 'hidden', name: 'option2[]', value: $('#product_color_'+index).text()}));
                        newForm.append($('<input/>', {type: 'hidden', name: 'product_number[]', value: $('#product_number_'+index).val() }));
                        newForm.append($('<input/>', {type: 'hidden', name: 'menu_no[]', value: $('#menu_no_'+index).val()}));
                        newForm.append($('<input/>', {type: 'hidden', name: 'delivery_payment[]', value: 1 }));
                        newForm.append($('<input/>', {type: 'hidden', name: 'cart_no[]', value: $('#cart_seq_'+index).val()}));
                    }
                });

                newForm.appendTo('body');

                newForm.submit();
            }
        });

        // 전체상품 구매
        $('#btn_purchase_all').on("click", function (){
            let length = $('.chk').length;
            let i;

            if(confirm("모든 상품을 구매하시겠습니까?")){
                //새로운 폼을 생성
                let newForm = $('<form></form>');
                // 폼의 속성값 추가
                newForm.attr("name","newForm");
                newForm.attr("method","post");
                newForm.attr("action","checkout4.php");

                let length = $('.chk').length;
                let i;
                
                // 모든항목의 정보를 보낸다.
                for(i = 0; i < length; i++){
                    newForm.append($('<input/>', {type: 'hidden', name: 'product_no[]', value:$('#product_no_'+ i).val() }));
                    newForm.append($('<input/>', {type: 'hidden', name: 'option1[]', value:$('#product_size_'+ i).text() }));
                    newForm.append($('<input/>', {type: 'hidden', name: 'option2[]', value: $('#product_color_'+ i).text()}));
                    newForm.append($('<input/>', {type: 'hidden', name: 'product_number[]', value: $('#product_number_'+ i).val() }));
                    newForm.append($('<input/>', {type: 'hidden', name: 'menu_no[]', value: $('#menu_no_'+ i).val()}));
                    newForm.append($('<input/>', {type: 'hidden', name: 'delivery_payment[]', value: 1 }));
                    newForm.append($('<input/>', {type: 'hidden', name: 'cart_no[]', value: $('#cart_seq_'+ i).val()}));
                }

                newForm.appendTo('body');

                newForm.submit();
            }
        });
    </script>
</body>
</html>