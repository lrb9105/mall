<?php
// 페이지 번호
if(isset($_GET['page_no'])){
    $page_no = $_GET['page_no'];
}else {
    $page_no = 0;
}

// mysql커넥션 연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
$sql = null;
$result = null;
$count = null;

$board_name = 'Q&A 답변';

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
                                <li class="breadcrumb-item"><a href="#">관리자</a></li>
                                <li aria-current="page" class="breadcrumb-item active"><? echo $board_name?></li>
                            </ol>
                        </nav>
                    </div>
                    <!-- 우측 사이드바-->
                    <div class="col-lg-2">
                        <div class="card sidebar-menu mb-4">
                            <div class="card-header">
                                <h3 class="h4 card-title">관리자</h3>
                            </div>
                            <div class="card-body" id="side-bar">
                                <ul class="nav nav-pills flex-column category-menu" >
                                    <li><a href="writeNoticeOrFaq.php" class="nav-link" style="color: #555555;">공지사항 | 자주묻는질문 작성</a></li>
                                    <li><a href="qandaAnswer.php" class="nav-link active" style="color: #555555;">Q&A 답변하기</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--사이드바 종료-->
                    <div id="board" class="col-lg-10">
                        <div class="box">
                            <!--Q&A 답변-->
                            <section class="contact spad">
                                <div class="container">
                                    <div class="search-box" style="margin-bottom: 5px;">
                                        <select id="search-select" class=”form-control" style="float: left;">
                                            <option value="">전체</option>
                                            <option value="1">작성자</option>
                                            <option value="2">제목</option>
                                        </select>
                                        <div class="input-group col-lg-10 col-md-12" >
                                            <input id="search-text" type="text" class="form-control">
                                            <div class="input-group-append" style="margin-left: 3px;">
                                                <div id="search-button" class="navbar-collapse collapse d-none d-lg-block"><a href="#" class="btn btn-primary navbar-btn"><i class="fa fa-search"></i><span>검색</span></a></div>
                                            </div>
                                        </div>
                                    </div>

                                    <table id="free_board_post_tb" class="table table-hover" style="text-align: center;">
                                        <thead>
                                        <tr>
                                            <th>답변상태</th>
                                            <th>질문유형</th>
                                            <th>상품명</th>
                                            <th style="width: 50%;">제목</th>
                                            <th>작성자</th>
                                            <th>날짜</th>
                                        </tr>
                                        </thead>
                                        <tbody id="accordion_qanda">
                                        </tbody>
                                    </table>
                                    <hr/>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" style="justify-content: center;"></ul>
                                    </nav>

                                </div>
                            </section>
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
        $(document).ready(function(){
            let searchSelect = $('#search-select').val();
            let searchText = $('#search-text').val();
            search(searchSelect, searchText);
        });

        //게시물 검색
        function search(searchSelect, searchText){
            let page_no = ((<?echo $page_no?> == 0)? 1: <?echo $page_no?>);

            if(searchText != ''){
                page_no = 1;
            }

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/selectQandaAnswerCompl.php',
                data: {
                    searchSelect: searchSelect,
                    searchText: searchText,
                    page_no: page_no
                },

                success: function (json) {
                    if (json.result == 'ok') {
                        //alert("조회를 완료헀습니다.");
                        // 가져온 데이터가 있다면 모든 행 삭제.
                        if(json.seq.length != 0){
                            //모든 행 삭제
                            $('#free_board_post_tb > tbody > tr').remove();
                            // 페이징 삭제
                            $('.pagination > li').remove();
                        } else{
                            alert("검색결과가 없습니다.");
                            return;
                        }

                        // 페이징 적용
                        for(let i = 0; i < json.seq.length; i++){
                            let tr = '<tr id="heading'+i+'" class="card-header" style="cursor: pointer;" data-toggle="collapse" data-target="#collapse'+i+'" aria-expanded="false" aria-controls="collapse'+i+'">'
                                +    '<td id="answer_state'+i+'">'+json.answerState[i]+'</td>'
                                +    '<td>'+json.type[i]+'</td>'
                                +    '<td style="text-align: left;">'
                                +         '<a href="javascript:getProductInfo('+json.productSeq[i]+');">'
                                +             '<u>' + json.productName[i] + '</u>'
                                +         '</a>'
                                +     '</td>'
                                +    '<td style="text-align: left;">'
                                +       '<u>' + json.title[i] + '</u>'
                                +     '</td>'
                                +     '<td>'+ json.name[i] +'</td>'
                                +     '<td>'+ json.creDatetime[i].substring(0,10) +'</td>'
                                + '</tr>'
                                + '<tr id="collapse'+i+'" aria-labelledby="heading'+i+'" data-parent="#accordion_qanda" class="collapse" >';
                            // 답변중, 답변완료 상태에 따라 textarea readOnly 결정
                            if(json.answerState[i] == '답변중'){
                                    tr += '<td colspan="6">'
                                            + '<div id="answer_div'+i+'">'
                                                + '<textarea name="answer'+i+'" id="answer'+i+'" rows="5" style="width: 100%;"></textarea>'
                                                + '<button onclick="updateAnswer('+ json.seq[i] +','+ i + ');" name="btn_answer'+i+'" id="btn_answer_write'+i+'" class="btn btn-info" style="float: right; margin-top: 5px;">답변완료</button>'
                                            + '</div>'
                                        + '</td>'
                                    + '</tr>';
                            } else{
                                tr += '<td colspan="6">'
                                    + '<div id="answer_div'+i+'">'
                                    + '<textarea name="answer'+i+'" id="answer'+i+'" rows="5" style="width: 100%;" readonly>'+json.answer[i]+'</textarea>'
                                    //+ '<button name="btn_answer'+i+'" id="btn_answer_delete'+i+'" class="btn btn-warning" style="float: right; margin-top: 5px; margin-left: 5px;">삭제</button>'
                                    //+ '<button name="btn_answer'+i+'" id="btn_answer_modify'+i+'" class="btn btn-info" style="float: right; margin-top: 5px;">수정</button>'
                                    + '</div>'
                                    + '</td>'
                                    + '</tr>';
                            }


                            $('#free_board_post_tb > tbody:last').append(tr);

                        }

                        /* 페이징 시작 */
                        if(parseInt(json.current_num_of_block) != 1){
                            $('.pagination').append('<li class="page-item"><a href="qandaAnswer.php?page_no=1 "class="page-link">' + '<<' + '</a></li>');
                        }

                        if(parseInt(json.current_num_of_block) != 1){
                            $('.pagination').append('<li class="page-item"><a href="qandaAnswer.php?&page_no='+ (parseInt(json.start_page_num_of_block) - 1) + '"class="page-link">' + '<' + '</a></li>');
                        }

                        for(let i = parseInt(json.start_page_num_of_block); i <= parseInt(json.end_page_num_of_block); i++){
                            if(page_no != i){
                                $('.pagination').append('<li class="page-item"><a href="qandaAnswer.php?page_no='+i + '"class="page-link">' + i + '</a></li>');
                            } else{
                                $('.pagination').append('<li class="page-item active"><a href="qandaAnswer.php?page_no='+i + '"class="page-link">' + i + '</a></li>');
                            }
                        }

                        if(parseInt(json.current_num_of_block) != parseInt(json.total_count_of_block)){
                            $('.pagination').append('<li class="page-item"><a href="qandaAnswer.php?page_no='+ (parseInt(json.end_page_num_of_block) + 1) + '"class="page-link">' + '>' + '</a></li>');
                        }

                        if(parseInt(json.current_num_of_block) != parseInt(json.total_count_of_block)){
                            $('.pagination').append('<li class="page-item"><a href="qandaAnswer.php?page_no='+ parseInt(json.total_count_of_page) + '"class="page-link">' + '>>' + '</a></li>');
                        }
                        /* 페이징 종료 */
                    } else {
                        alert("조회에 실패했습니다!");
                    }
                },
                error: function () {
                    alert("에러가 발생했습니다.");
                }
            });
        }

        $('#search-button').on("click", function(){
            let searchSelect = $('#search-select').val();
            let searchText = $('#search-text').val();

            // 전체조회면 검색어 입력해도 적용안되도록
            if(searchSelect == ''){
                searchText = '';
            }
                // 검색어 입력안했으면 alert
            if(searchSelect != '' && searchText == ''){
                alert("검색어를 입력하세요.");
                return;
            }


            search(searchSelect, searchText);
        });

        // 검색창에서 엔터 입력 시 조회되도록
        $("#search-text").keydown(function(key) {
            if(key.keyCode == 13){
                let searchSelect = $('#search-select').val();
                let searchText = $('#search-text').val();
                search(searchSelect, searchText);
            }
        });

        //상품정보
        function getProductInfo(productSeq){
            window.open("/mall/productInfo.php?product_no="+productSeq,"상품정보","width=1200px;,height=1200px;");
        }

        // 답변 작성완료
        function updateAnswer(seq, index){
            if(confirm("답변을 완료하시겠습니까?")){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/product/qanda/updateAnswer.php',
                    data: {
                          seq: seq
                        , answer: $('#answer'+index).val()
                    },

                    success: function (json) {
                        if (json.result == 'ok') {
                            // textarea readOnly로 변경
                            $('#answer'+index).attr("readonly", true);

                            // 답변완료 버튼 비활성화
                            $('#btn_answer_write'+index).hide();
                            $('#btn_answer_write'+index).attr("disabled", true);

                            // 수정, 삭제 버튼 생성
                            let buttons =
                                    '<button name="btn_answer'+index+'" id="btn_answer_delete'+index+'" class="btn btn-warning" style="float: right; margin-top: 5px; margin-left: 5px;">삭제</button>'
                                +  '<button name="btn_answer'+index+'" id="btn_answer_modify'+index+'" class="btn btn-info" style="float: right; margin-top: 5px;">수정</button>';

                            //$('#answer_div'+index).append(buttons);

                            // 답변중=>답변완료로 변경
                            $('#answer_state'+index).text('답변완료');
                        } else {
                            alert("작성에 실패했습니다!");
                        }
                    },
                    error: function () {
                        alert("에러가 발생했습니다.");
                    }
                });
            }
        }
    </script>
</body>
</html>