<?php
    session_start();

    $mypage_no = $_GET['mypage_no'];
    $mypage_name = null;

    $login_id = $_SESSION['LOGIN_ID'];

    if($mypage_no == '1') {
        $mypage_name = '주문/배송';
    }

    // 페이지 번호
    if(isset($_GET['page_no'])){
        $page_no = $_GET['page_no'];
    }else {
        $page_no = 0;
    }
    
    // mysql커넥션 연결
    $conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

    // 상품갯수 가져오기
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
                      ,  OL.ORDER_DATETIME
                      ,  OL.ORDER_STATE
                      ,  P.FIRST_CATEGORY
                      ,  OL.INVOICE_NUMBER
                      ,  OL.ORDER_NAME
        FROM ORDER_PRODUCT_LIST OPL 
        INNER JOIN PRODUCT P ON OPL.PRODUCT_SEQ = P.PRODUCT_SEQ
        INNER JOIN ORDER_LIST OL ON OPL.ORDER_NO = OL.ORDER_NO
        ORDER BY ORDER_DATETIME DESC
        ";
    // 쿼리를 통해 가져온 결과
    $result = mysqli_query($conn, $sqlOrderProductInfo);

    // 현재 페이지번호
    if(!isset($_GET['page_no'])){
        $page_no = 1;
    } else{
        $page_no = $_GET['page_no'];
    }

    // 총 게시물 개수
    $total_count_of_post = mysqli_num_rows($result);
    // 한 페이지당 보여줄 게시물 개수
    $count_of_post_per_page = 5;
    // 총 페이지 개수(나머지가 있다면 1추가)
    //$total_count_of_page = $total_count_of_post / $count_of_post_per_page + ($total_count_of_post % $count_of_post_per_page > 0 ? 1 : 0);
    $total_count_of_page = ceil($total_count_of_post / $count_of_post_per_page);
    // 한 페이지에서 보여줄 블록 개수
    $count_of_block_per_page = 10;
    // 총 블록그룹 개수(총 페이지 / 페이지 당 블록 수) + 1(나머지 있다면, 없다면 0)
    //$total_count_of_block = $total_count_of_page / $count_of_block_per_page + ($total_count_of_page % $count_of_block_per_page > 0 ? 1 : 0);
    $total_count_of_block = ceil($total_count_of_page / $count_of_block_per_page);
    // 현재 블록그룹 번호
    if($page_no != 1){
        $current_num_of_block = ceil($page_no/$count_of_block_per_page);
    } else{
        $current_num_of_block = 1;
    }
    // 블록의 시작페이지 번호
    $start_page_num_of_block = $current_num_of_block * $count_of_block_per_page - ($count_of_block_per_page - 1);
    // 블록의 종료페이지 번호
    $end_page_num_of_block = $current_num_of_block * $count_of_block_per_page;
    if($end_page_num_of_block > $total_count_of_page){
        $end_page_num_of_block = $total_count_of_page;
    }

    // 조회 해야할 데이터 시작번호
    $s_point = ($page_no-1) * $count_of_post_per_page;



    // 실제 데이터 조회
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
                      ,  OL.ORDER_DATETIME
                      ,  OL.ORDER_STATE
                      ,  P.FIRST_CATEGORY
                      ,  OL.INVOICE_NUMBER
                      ,  OL.ORDER_NAME
        FROM ORDER_PRODUCT_LIST OPL 
        INNER JOIN PRODUCT P ON OPL.PRODUCT_SEQ = P.PRODUCT_SEQ
        INNER JOIN ORDER_LIST OL ON OPL.ORDER_NO = OL.ORDER_NO
        ORDER BY ORDER_DATETIME DESC
        LIMIT $s_point,$count_of_post_per_page
        ";

    // 쿼리를 통해 가져온 결과
    $resultOrderProductInfo = mysqli_query($conn, $sqlOrderProductInfo);
    $count = mysqli_num_rows($result);
    
    $referer = $_SERVER['HTTP_REFERER']
?>
<script>
    if('<?echo $referer?>' == ''){
        alert('잘못된 접근입니다.');
        location.href = 'index.php';
    }
</script>

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
                                <li class="breadcrumb-item"><a href="#">주문관리</a></li>
                                <li aria-current="page" class="breadcrumb-item active"><? echo $mypage_name?></li>
                            </ol>
                        </nav>
                    </div>
                    <!-- 좌측 사이드바-->
                    <div class="col-lg-2">
                        <div class="card sidebar-menu mb-4">
                            <div class="card-header">
                                <h3 class="h4 card-title">커뮤니티</h3>
                            </div>
                            <div class="card-body" id="side-bar">
                                <ul class="nav nav-pills flex-column category-menu" >
                                    <li><a href="orderManage.php" class="nav-link active" style="color: #555555;">주문 관리</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--사이드바 종료-->
                    <div id="board" class="col-lg-10">
                        <div class="box">
                            <!-- 공지사항, 자주묻는 질문-->
                            <div id="contact" class="box">
                                <h1>주문 관리</h1>
                                <p class='lead'>주문 내역을 관리합니다.</p>
                                <hr>
                            </div>
                            <div style="text-align: right; margin-bottom: 3px;">
                                <button id="btn_order_confirm" class="btn btn-warning center">발주확인</button>
                                <button id="btn_send_complete" class="btn btn-info center">발송완료</button>
                            </div>

                            <span class="table-responsive">
                                <table class="table">
                                    <colgroup>
                                        <col width = "1%">
                                        <col width = "10%">
                                        <col width = "10%">
                                        <col width = "10%">
                                        <col width = "15%">
                                        <col width = "10%">
                                        <col width = "10%">
                                        <col width = "15%">
                                    </colgroup>
                                    <thead>
                                    <tr style="border-top: 2px solid black;">
                                        <th><input type="checkbox" id="chk_all"></th>
                                        <th>주문일</th>
                                        <th>주문번호</th>
                                        <th>주문자</th>
                                        <th colspan="2" style="text-align: center;">상품정보</th>
                                        <th>주문금액</th>
                                        <th>주문상태</th>
                                        <th>송장번호</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <? $cnt = 0;
                                    while($rowProductInfo = mysqli_fetch_array($resultOrderProductInfo)) {
                                        ?>
                                        <tr class="product_info">
                                            <?if($rowProductInfo['ORDER_STATE'] != '배송중' && $rowProductInfo['ORDER_STATE'] != '배송완료'){?>
                                            <td><input class="chk" type="checkbox" id="chk_<?echo $cnt?>"></td>
                                            <?} else{?>
                                            <td><input class="chk" type="checkbox" id="chk_<?echo $cnt?>" hidden></td>
                                            <?}?>
                                            <td class="order_date"><?echo substr($rowProductInfo['ORDER_DATETIME'],0,10)?></td>
                                            <td class="order_no" id="order_no_<?echo $cnt?>"><a href="orderDetail.php?order_no=<?echo $rowProductInfo['ORDER_NO']?>"><?echo $rowProductInfo['ORDER_NO']?></a></td>
                                            <td class="order_person"><?echo $rowProductInfo['ORDER_NAME']?></td>
                                            <td style="text-align: center;"><a href="/mall/detail.php?menu_no=<?echo $rowProductInfo['FIRST_CATEGORY']?>&product_no=<?echo $rowProductInfo['PRODUCT_SEQ']?>"><img style="width: 100px;" class="product_img" src="<?echo $rowProductInfo['SRC']?>" alt="<?echo $rowProductInfo['PRODUCT_NAME']?>"></a></td>
                                            <td><span style="text-align: center;"class="product_name"><a href="/mall/detail.php?menu_no=<?echo $rowProductInfo['FIRST_CATEGORY']?>&product_no=<?echo $rowProductInfo['PRODUCT_SEQ']?>"><?echo $rowProductInfo['PRODUCT_NAME']?></a></span><br>색상: <span class="product_color"><?echo $rowProductInfo['PRODUCT_COLOR']?></span><br>사이즈: <span class="product_size"><?echo $rowProductInfo['PRODUCT_SIZE']?></span><br>수량: <span class="product_size"><?echo $rowProductInfo['PRODUCT_NUMBER']?></span></td>
                                            <td class="order_price"><?echo number_format($rowProductInfo['PRODUCT_PRICE'])?></td>
                                            <td class="order_state" id="order_state_<?echo $cnt?>"><?echo $rowProductInfo['ORDER_STATE']?></td>
                                            <?if($rowProductInfo['ORDER_STATE'] == '상품준비중'){?>
                                                <td><input type="text"  class="invoice_number" id="invoice_number_<?echo $cnt?>"></td>
                                            <?} elseif($rowProductInfo['ORDER_STATE'] == '배송중' || $rowProductInfo['ORDER_STATE'] == '배송완료') {?>
                                                <td><?echo $rowProductInfo['INVOICE_NUMBER']?></td>
                                            <?}?>
                                        </tr>

                                    <?$cnt++;
                                    }?>

                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="justify-content: center;">
                                        <?if($current_num_of_block != 1){ ?>
                                            <li class="page-item"><a href="orderManage.php?page_no=<?= 1?>" aria-label="Previous" class="page-link"><span aria-hidden="true"><<</span><span class="sr-only">Previous</span></a></li>
                                        <?}?>
                                        <?if($current_num_of_block != 1){ ?>mypage
                                            <li class="page-item"><a href="orderManage.phpmenu_no=&page_no=<?= $start_page_num_of_block - 1?>" aria-label="Previous" class="page-link"><span aria-hidden="true"><</span><span class="sr-only">Previous</span></a></li>
                                        <?}?>
                                        <?for($i = $start_page_num_of_block; $i <= $end_page_num_of_block; $i++) {
                                            if($page_no != $i){ ?>
                                                <li class="page-item"><a href="orderManage.php?page_no=<?= $i?>" class="page-link"><?= $i?></a></li>
                                            <?} else{ ?>
                                                <li class="page-item active"><a href="orderManage.php?page_no=<?= $i?>" class="page-link"><?= $i?></a></li><?}?>
                                        <?}?>
                                        <?if($current_num_of_block != $total_count_of_block){?>
                                            <li class="page-item"><a href="orderManage.php?page_no=<?= $end_page_num_of_block + 1?>" aria-label="Next" class="page-link"><span aria-hidden="true">></span><span class="sr-only">Next</span></a></li>
                                        <?}?>
                                        <?if($current_num_of_block != $total_count_of_block){?>
                                            <li class="page-item"><a href="orderManage.php?page_no=<?= $total_count_of_page?>" aria-label="Next" class="page-link"><span aria-hidden="true">>></span><span class="sr-only">Next</span></a></li>
                                        <?}?>
                                    </ul>
                                </nav>
                            </div>
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
        // 전체 체크, 해제
        $('#chk_all').on("click", function(){
           // 체크되어있다면
            if($(this).is(":checked")){
               $('.chk').prop("checked",true);
           } else{ //아니라면
                $('.chk').prop("checked",false);
           }
        });

        // 발주확인
        $('#btn_order_confirm').on("click", function(){
            let i;
            let length = $('.chk').length;

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

            // 결제완료 상태인 항목만 선택됐는지 확인
            for(i = 0; i < length; i++){
                if($('#chk_'+i).prop("checked") == true){
                    if($('#order_state_'+i).text() != '결제완료'){
                        alert('결제완료 상태의 항목만 발주확인할 수 있습니다.');
                        return;
                    }
                }
            }

            // 선택한 요소
            let order_no_list = '';

            $('.chk').each(function(index, item){
                if($(this).prop("checked") == true){
                    order_no_list = order_no_list + "'"+ $('#order_no_'+index).text() + "'" + ', ';
                }

            });

            order_no_list = order_no_list.substring(0, order_no_list.length -2);

            // 선택된 항목 발주처리
            //0: 발주처리, 1: 발송완료
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/product/updateOrderState.php',
                data: {
                    order_no_list: order_no_list,
                    changed_state: '0'
                },

                success: function (json) {
                    if (json.result == 'ok') {
                        alert("발주처리 완료했습니다.");
                        location.reload();
                    } else {
                        alert("발주처리에 실패했습니다.");
                    }
                },
                error: function (request, status, error) {
                    alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);

                    //alert("에러가 발생했습니다.");
                }
            });
        });

        //발송완료
        $('#btn_send_complete').on("click", function(){
            let i;
            let length = $('.chk').length;

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

            // 상품준비중 상태인 항목만 선택됐는지 확인
            for(i = 0; i < length; i++){
                if($('#chk_'+i).prop("checked") == true){
                    if($('#order_state_'+i).text() != '상품준비중'){
                        alert('상품준비중 상태의 항목만 발송완료할 수 있습니다.');
                        return;
                    }
                }
            }

            // 선택한 요소
            let order_no_list = '';
            let invoice_number_list = '';

            // 주문번호, 송장번호리스트 생성, 송장번호 입력했는지 확인
            let noAjax = false;

            $('.chk').each(function(index, item){
                if($(this).prop("checked") == true){
                    if($('#invoice_number_'+index).val() == '' || $('#invoice_number_'+index).val() == null) {
                        alert('송장번호를 입력해주세요');
                        noAjax = true;
                    }
                    order_no_list = order_no_list + "'"+ $('#order_no_'+index).text() + "'" + ',';
                    invoice_number_list = invoice_number_list + "'"+ $('#invoice_number_'+index).val() + "'" + ',';
                }

            });

            if(noAjax){
                return;
            }

            order_no_list = order_no_list.substring(0, order_no_list.length -1);
            invoice_number_list = invoice_number_list.substring(0, invoice_number_list.length -1);

            // 선택된 항목 발주처리
            //0: 발주처리, 1: 발송완료
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/product/updateOrderState.php',
                data: {
                    order_no_list: order_no_list,
                    changed_state: '1',
                    invoice_number_list: invoice_number_list
                },

                success: function (json) {
                    if (json.result == 'ok') {
                        alert("발송완료처리 되었습니다.");
                        location.reload();
                    } else {
                        alert("발송완료 처리가 완료되지 않았습니다.");
                    }
                },
                error: function (request, status, error) {
                    alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);

                    //alert("에러가 발생했습니다.");
                }
            });
        });
    </script>
</body>
</html>