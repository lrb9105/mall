<?php
    session_start();

    $mypage_no = $_GET['mypage_no'];
    $mypage_name = null;

    $login_id = $_SESSION['LOGIN_ID'];

    if($mypage_no == '1') {
        $mypage_name = '주문/배송';
    }
    // mysql커넥션 연결
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
                      ,  OL.ORDER_DATETIME
                      ,  OL.ORDER_STATE
                      ,  P.FIRST_CATEGORY 
                      ,  OL.INVOICE_NUMBER
        FROM ORDER_PRODUCT_LIST OPL 
        INNER JOIN PRODUCT P ON OPL.PRODUCT_SEQ = P.PRODUCT_SEQ
        INNER JOIN ORDER_LIST OL ON OPL.ORDER_NO = OL.ORDER_NO
        WHERE OL.ORDER_PERSON_ID = '$login_id'
        ORDER BY ORDER_DATETIME DESC
        ";

    $resultOrderProductInfo = mysqli_query($conn, $sqlOrderProductInfo);

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
                                <li class="breadcrumb-item"><a href="#">마이페이지</a></li>
                                <li aria-current="page" class="breadcrumb-item active"><? echo $mypage_name?></li>
                            </ol>
                        </nav>
                    </div>
                    <!-- 좌측 사이드바-->
                    <div class="col-lg-2">
                        <div class="card sidebar-menu mb-4">
                            <div class="card-header">
                                <h3 class="h4 card-title">마이페이지</h3>
                            </div>
                            <div class="card-body" id="side-bar">
                                <ul class="nav nav-pills flex-column category-menu" >
                                    <li><a href="mypage.php?mypage_no=1" class="nav-link" style="color: #555555;">주문/배송</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--사이드바 종료-->
                    <div id="board" class="col-lg-10">
                        <div class="box">
                            <!-- 공지사항, 자주묻는 질문-->
                            <div id="contact" class="box">
                                <h1>주문/배송</h1>
                                <p class='lead'>주문 배송내역을 조회합니다..</p>
                                <hr>
                                <hr>
                            </div>
                            <span class="table-responsive">
                                <table class="table">
                                    <colgroup>
                                        <col width = "10%">
                                        <col width = "10%">
                                        <col width = "10%">
                                        <col width = "15%">
                                        <col width = "10%">
                                        <col width = "15%">
                                        <col width = "10%">
                                    </colgroup>
                                    <thead>
                                    <tr style="border-top: 2px solid black;">
                                        <th>주문일</th>
                                        <th>주문번호</th>
                                        <th colspan="2" style="text-align: center;">상품정보</th>
                                        <th>주문금액</th>
                                        <th>송장번호</th>
                                        <th>주문상태</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?while($rowProductInfo = mysqli_fetch_array($resultOrderProductInfo)) {
                                        ?>
                                        <tr class="product_info">
                                            <td class="product_number"><?echo substr($rowProductInfo['ORDER_DATETIME'],0,10)?></td>
                                            <td class="product_number"><a href="orderDetail.php?order_no=<?echo $rowProductInfo['ORDER_NO']?>"><?echo $rowProductInfo['ORDER_NO']?></a></td>
                                            <td style="text-align: center;"><a href="/mall/detail.php?menu_no=<?echo $rowProductInfo['FIRST_CATEGORY']?>&product_no=<?echo $rowProductInfo['PRODUCT_SEQ']?>"><img style="width: 100px;" class="product_img" src="<?echo $rowProductInfo['SRC']?>" alt="<?echo $rowProductInfo['PRODUCT_NAME']?>"></a></td>
                                            <td><span style="text-align: center;"class="product_name"><a href="#"><?echo $rowProductInfo['PRODUCT_NAME']?></a></span><br>색상: <span class="product_color"><?echo $rowProductInfo['PRODUCT_COLOR']?></span><br>사이즈: <span class="product_size"><?echo $rowProductInfo['PRODUCT_SIZE']?></span><br>수량: <span class="product_size"><?echo $rowProductInfo['PRODUCT_NUMBER']?></span></td>
                                            <td class="product_price"><?echo $rowProductInfo['PRODUCT_PRICE']?></td>
                                            <td class="invoice_number"><?echo $rowProductInfo['INVOICE_NUMBER']?></td>
                                            <td class="product_price"><?echo $rowProductInfo['ORDER_STATE']?><?if($rowProductInfo['ORDER_STATE'] == '배송중'){?><br><button onclick="purcharseCompl(<?echo $rowProductInfo['ORDER_NO']?>)" id="btn_purchase_compl" class="btn btn-info">구매확정</button> <?}?></td>
                                            
                                        </tr>
                                    <?}?>

                                    </tbody>
                                </table>

                            </div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="justify-content: center;">
                                        <li class="page-item"><a href="#" class="page-link">«</a></li>
                                        <li class="page-item active"><a href="#" class="page-link">1</a></li>
                                        <li class="page-item"><a href="#" class="page-link">2</a></li>
                                        <li class="page-item"><a href="#" class="page-link">3</a></li>
                                        <li class="page-item"><a href="#" class="page-link">4</a></li>
                                        <li class="page-item"><a href="#" class="page-link">5</a></li>
                                        <li class="page-item"><a href="#" class="page-link">»</a></li>
                                    </ul>
                                </nav>
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
        mypageNo = '<?echo $mypage_no?>';

        $('.category-menu li a[href$='+ mypageNo +']').each(function (index, item){
            if(index == 0) {
                $(item).addClass("active");
            }
            console.log(item);
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