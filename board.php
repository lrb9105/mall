<?php
    $board_no = $_GET['board_no'];
    $board_name = null;

    // mysql커넥션 연결
    $conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
    $sql = null;
    $result = null;
    $count = null;

    // 데이터 가져오기(자주묻는 질문, 공지사항)
    if($board_no != '3'){
        $sql = "SELECT TITLE, CONTENTS, SEQ, TYPE
            FROM NOTICE_AND_FAQ
            WHERE TYPE = '$board_no'
            ORDER BY SEQ
            ";

        if($board_no == '1'){
            $board_name = '공지사항';
        } elseif ($board_no == '2'){
            $board_name = '자주묻는질문';
        }
        // 쿼리를 통해 가져온 결과
        $result = mysqli_query($conn, $sql);

        //가져온 행의 갯수
        $count = mysqli_num_rows($result);
    } else{// 데이터 가져오기(자유게시판)
        /*$sql = "SELECT SEQ
                     , TITLE
                     , WRITER
                     , CRE_DATETIME
                     , CNT
                     , DEPTH
            FROM FREE_BOARD
            ORDER BY GROUP_NO DESC, GROUP_ORDER ASC
            ";*/
        $board_name = '자유게시판';
    }
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
                                <li class="breadcrumb-item"><a href="#">커뮤니티</a></li>
                                <li aria-current="page" class="breadcrumb-item active"><? echo $board_name?></li>
                            </ol>
                        </nav>
                    </div>
                    <!-- 우측 사이드바-->
                    <div class="col-lg-2">
                        <div class="card sidebar-menu mb-4">
                            <div class="card-header">
                                <h3 class="h4 card-title">커뮤니티</h3>
                            </div>
                            <div class="card-body" id="side-bar">
                                <ul class="nav nav-pills flex-column category-menu" >
                                    <li><a href="board.php?board_no=1" class="nav-link" style="color: #555555;">공지사항</a></li>
                                    <li><a href="board.php?board_no=2" class="nav-link" style="color: #555555;">자주묻는 질문</a></li>
                                    <li><a href="board.php?board_no=3" class="nav-link" style="color: #555555;">자유게시판</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--사이드바 종료-->
                    <div id="board" class="col-lg-10">
                        <div class="box">
                            <!-- 공지사항, 자주묻는 질문-->
                            <?if($board_no != '3'){?>
                            <div id="contact" class="box">
                                <?
                                    if($board_no == '1'){
                                        echo "<h1>공지사항</h1>";
                                        echo "<p class='lead'>사이트의 공지사항 입니다.</p>";
                                    } elseif ($board_no == '2'){
                                        echo "<h1>자주묻는 질문</h1>";
                                        echo "<p class='lead'>고객님들께서 자주 묻는 질문에 대한 답변입니다.</p>";
                                    }
                                ?>
                                <hr>
                                <hr>
                                <div id="accordion">
                                    <?for($i = 0; $i < $count; $i++){
                                        $row = mysqli_fetch_array($result);
                                        ?>

                                    <div class="card border-primary mb-3">
                                        <div id="heading<?echo $i?>" class="card-header p-0 border-0">
                                            <h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse<?echo $i?>" aria-expanded="false" aria-controls="collapse<?echo $i?>" class="btn btn-primary d-block text-left rounded-0"><?echo $i + 1 ?>. <? echo $row['TITLE'] ?></a></h4>
                                        </div>
                                        <div id="collapse<?echo $i?>" aria-labelledby="heading<?echo $i?>" data-parent="#accordion" class="collapse">
                                            <div class="card-body"><? echo str_replace("\n","</br>",$row['CONTENTS']) ?></div>
                                            <? if($_SESSION['USER_TYPE'] == "0") { ?>
                                                <div class="navbar-buttons" align="right" style="display: flex;">
                                                    <!-- /.nav-collapse-->
                                                    <div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="/mall/updateNoticeOrFaq.php?SEQ=<?echo $row['SEQ']?>" class="btn btn-primary navbar-btn">수정</a></div>
                                                    <div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a onclick="return confirm('정말로 삭제하시겠습니까?');" href="/mall/php/deleteNoticeOrFaqCompl.php?SEQ=<?echo $row['SEQ']?>&TYPE=<?echo $row['TYPE']?>" class="btn btn-primary navbar-btn">삭제</a></div>
                                                </div>
                                            <?}?>
                                        </div>
                                    </div>
                                    <?}?>
                                </div>
                                <!-- /.accordion-->
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
                            <?} else {?>
                            <!--자유게시판-->
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
                                            <th>번호</th>
                                            <th style="width: 50%;">제목</th>
                                            <th>작성자</th>
                                            <th>날짜</th>
                                            <th>조회수</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <hr/>
                                    <div id="btn_write" class="navbar-collapse collapse d-none d-lg-block" style="text-align: right"><a href="writeFreeBoard.php" class="btn btn-primary navbar-btn">작성하기</a></div>

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

                                </div>
                            </section>
                        </div>
                        <?}?>
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
            if(<?echo $board_no?> == '3'){
                let searchSelect = $('#search-select').val();
                let searchText = $('#search-text').val();
                search(searchSelect, searchText);
            }
        });

        // menu_no에 따라 menu_title, cat_second, cat_third 변경하기
        boardNo = '<?echo $board_no?>';

        // 선택된 커뮤니티 active
        // href$="val" : href의 속성값이 val로 끝나는 요소
        $('.category-menu li a[href$='+ boardNo +']').each(function (index, item){
            if(index == 0) {
                $(item).addClass("active");
            }
            console.log(item);
        });

        //게시물 검색
        function search(searchSelect, searchText){
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/selectFreeBoardCompl.php',
                data: {
                    searchSelect: searchSelect,
                    searchText: searchText
                },

                success: function (json) {
                    if (json.result == 'ok') {
                        //alert("조회를 완료헀습니다.");
                        // 가져온 데이터가 있다면 모든 행 삭제.
                        if(json.seq.length != 0){
                            //모든 행 삭제
                            $('#free_board_post_tb > tbody > tr').remove();
                        } else{
                            alert("검색결과가 없습니다.");
                            return;
                        }

                        for(let i = 0; i < json.seq.length; i++){
                            let space = '';

                            for(let j= 0; j < (json.depth[i] - 1) * 2; j++){
                                space += '&nbsp';
                            }
                            if(space != ''){
                                space += '┖';
                            }
                            $('#free_board_post_tb > tbody:last').append(
                                '<tr style="cursor: pointer;" onclick="location.href=\'detailFreeBoard.php?board_no=3&seq=' + json.seq[i] + '\'">'
                                +    '<td>'+json.seq[i]+'</td>'
                                +    '<td style="text-align: left;">'
                                +         '<a href="detailFreeBoard.php?board_no=3&seq='+json.seq[i]+'">'
                                +             space + '<u>' + json.title[i] + '</u>'
                                +         '</a>' + ' [' + json.commentCnt[i] + ']'
                                +     '</td>'
                                +     '<td>'+ json.name[i] +'</td>'
                                +     '<td>'+ json.creDatetime[i].substring(0,10) +'</td>'
                                +     '<td>'+ json.cnt[i] +'</td>'
                                + '</tr>');
                        }
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
    </script>
</body>
</html>