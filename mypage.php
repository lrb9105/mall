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
                      ,  P.SECOND_CATEGORY 
                      ,  OL.INVOICE_NUMBER
                      ,  OPL.REVIEW_YN
                      ,  OL.ORDER_PERSON_ID
        FROM ORDER_PRODUCT_LIST OPL 
        INNER JOIN PRODUCT P ON OPL.PRODUCT_SEQ = P.PRODUCT_SEQ
        INNER JOIN ORDER_LIST OL ON OPL.ORDER_NO = OL.ORDER_NO
        WHERE OL.ORDER_PERSON_ID = '$login_id'
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
                      ,  P.SECOND_CATEGORY 
                      ,  OL.INVOICE_NUMBER
                      ,  OPL.REVIEW_YN
                      ,  OL.ORDER_PERSON_ID
        FROM ORDER_PRODUCT_LIST OPL 
        INNER JOIN PRODUCT P ON OPL.PRODUCT_SEQ = P.PRODUCT_SEQ
        INNER JOIN ORDER_LIST OL ON OPL.ORDER_NO = OL.ORDER_NO
        WHERE OL.ORDER_PERSON_ID = '$login_id'
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
                                    <li><a href="updateUserInfo.php" class="nav-link" style="color: #555555;">내정보</a></li>
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
                                        <!--<th>상품평작성</th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?while($rowProductInfo = mysqli_fetch_array($resultOrderProductInfo)) {
                                        ?>
                                        <tr class="product_info">
                                            <td class="product_number"><?echo substr($rowProductInfo['ORDER_DATETIME'],0,10)?></td>
                                            <td class="product_number"><a href="orderDetail.php?order_no=<?echo $rowProductInfo['ORDER_NO']?>"><?echo $rowProductInfo['ORDER_NO']?></a></td>
                                            <td style="text-align: center;"><a href="/mall/detail.php?menu_no=<?echo $rowProductInfo['SECOND_CATEGORY']?>&product_no=<?echo $rowProductInfo['PRODUCT_SEQ']?>"><img style="width: 100px;" class="product_img" src="<?echo $rowProductInfo['SRC']?>" alt="<?echo $rowProductInfo['PRODUCT_NAME']?>"></a></td>
                                            <td><span style="text-align: center;"class="product_name"><a href="/mall/detail.php?menu_no=<?echo $rowProductInfo['SECOND_CATEGORY']?>&product_no=<?echo $rowProductInfo['PRODUCT_SEQ']?>"><?echo $rowProductInfo['PRODUCT_NAME']?></a></span><br>색상: <span class="product_color"><?echo $rowProductInfo['PRODUCT_COLOR']?></span><br>사이즈: <span class="product_size"><?echo $rowProductInfo['PRODUCT_SIZE']?></span><br>수량: <span class="product_size"><?echo $rowProductInfo['PRODUCT_NUMBER']?></span></td>
                                            <td class="product_price"><?echo number_format($rowProductInfo['PRODUCT_PRICE'])?></td>
                                            <td class="invoice_number"><?echo $rowProductInfo['INVOICE_NUMBER']?></td>
                                            <td class="product_price"><?echo $rowProductInfo['ORDER_STATE']?><?if($rowProductInfo['ORDER_STATE'] == '배송중' && $login_id == $rowProductInfo['ORDER_PERSON_ID']){?><br><button onclick="purcharseCompl(<?echo $rowProductInfo['ORDER_NO']?>)" id="btn_purchase_compl" class="btn btn-info">구매확정</button> <?}?></td>
                                        </tr>
                                    <?}?>

                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="justify-content: center;">
                                        <?if($current_num_of_block != 1){ ?>
                                            <li class="page-item"><a href="mypage.php?mypage_no=1&page_no=<?= 1?>" aria-label="Previous" class="page-link"><span aria-hidden="true"><<</span><span class="sr-only">Previous</span></a></li>
                                        <?}?>
                                        <?if($current_num_of_block != 1){ ?>mypage
                                            <li class="page-item"><a href="mypage.phpmenu_no=&page_no=<?= $start_page_num_of_block - 1?>" aria-label="Previous" class="page-link"><span aria-hidden="true"><</span><span class="sr-only">Previous</span></a></li>
                                        <?}?>
                                        <?for($i = $start_page_num_of_block; $i <= $end_page_num_of_block; $i++) {
                                            if($page_no != $i){ ?>
                                                <li class="page-item"><a href="mypage.php?mypage_no=1&page_no=<?= $i?>" class="page-link"><?= $i?></a></li>
                                            <?} else{ ?>
                                                <li class="page-item active"><a href="mypage.php?mypage_no=1&page_no=<?= $i?>" class="page-link"><?= $i?></a></li><?}?>
                                        <?}?>
                                        <?if($current_num_of_block != $total_count_of_block){?>
                                            <li class="page-item"><a href="mypage.php?mypage_no=1&page_no=<?= $end_page_num_of_block + 1?>" aria-label="Next" class="page-link"><span aria-hidden="true">></span><span class="sr-only">Next</span></a></li>
                                        <?}?>
                                        <?if($current_num_of_block != $total_count_of_block){?>
                                            <li class="page-item"><a href="mypage.php?mypage_no=1&page_no=<?= $total_count_of_page?>" aria-label="Next" class="page-link"><span aria-hidden="true">>></span><span class="sr-only">Next</span></a></li>
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

        // 리뷰 작성완료
        function writeReview(product_no){
            /*console.log($('#photo_review_title').val());
            console.log($('#photo_review_contents').val());
            console.log($('#photo_review_evaluation_size').val());
            console.log($('#photo_review_evaluation_color').val());
            console.log($('#photo_review_evaluation_lightness').val());
            console.log($("#photo_review_raty").children('input').val());
            console.log($('#photo_review_file_photo_review')[0].files[0]);*/

            let formData = new FormData();
            if($('#photo_review_file_photo_review').val() != ''){
                formData.append("type", "0"); // 포토후기
            } else{
                formData.append("type", "1"); // 일반후기
            }
            formData.append("photo_review_selected_product", $('#photo_review_selected_product').val());
            formData.append("product_no", product_no);
            formData.append("photo_review_title", $("#photo_review_title").val());
            formData.append("photo_review_contents", $("#photo_review_contents").val());
            formData.append("photo_review_evaluation_size", $("#photo_review_evaluation_size").val());
            formData.append("photo_review_evaluation_color", $("#photo_review_evaluation_color").val());
            formData.append("photo_review_evaluation_lightness", $("#photo_review_evaluation_lightness").val());
            formData.append("photo_review_evaluation_thickness", $("#photo_review_evaluation_thickness").val());
            formData.append("photo_review_raty", $("#photo_review_raty").children('input').val());
            formData.append("photo_review_file", $('#photo_review_file_photo_review')[0].files[0]);


            // 리뷰 작성완료
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/product/review/writeReviewCompl.php',
                processData: false, // 필수
                contentType: false, // 필수
                data: formData,
                success: function (json) {
                    if(json.result == 'ok'){
                        // 모달 창 닫기
                        $('#photo-review-modal').modal("hide");
                        location.reload();
                    }
                },
                error: function () {
                }
            });
        }

        // 상품이미지
        $('#file_photo_review').on("change", function(e){
            alert('11');
            let files = e.target.files;
            let fileArr = Array.prototype.slice.call(files);

            fileArr.forEach(function(file){
                if(!file.type.match("image.*")) {
                    alert("확장자는 이미지 확장자만 가능합니다.");
                    return;
                }

                let reader = new FileReader();

                reader.onload = function(e) {
                    $('#img_photo_review').attr("src", e.target.result);
                }
                reader.readAsDataURL(file);

            });
        });

        $('#photo_review_raty').raty({half : true, readOnly: false});
    </script>
</body>
</html>