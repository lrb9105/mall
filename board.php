<?php
    $board_no = $_GET['board_no'];
    $board_name = null;

    // mysql커넥션 연결
    $conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
    $sql = null;

    // 데이터 가져오기(자주묻는 질문, 공지사항)
    if($board_no != '3'){
        $sql = "SELECT TITLE, CONTENTS
            FROM NOTICE_AND_FAQ
            WHERE TYPE = '$board_no'
            ORDER BY SEQ
            ";

        if($board_no == '1'){
            $board_name = '공지사항';
        } elseif ($board_no == '2'){
            $board_name = '자주묻는질문';
        }
    } else{// 데이터 가져오기(자유게시판)
        $sql = "SELECT TITLE, CONTENTS
            FROM NOTICE_AND_FAQ
            WHERE TYPE = '$board_no'
            ORDER BY SEQ
            ";
        $board_name = '자유게시판';
    }

    // 쿼리를 통해 가져온 결과
    $result = mysqli_query($conn, $sql);

    //가져온 행의 갯수
    $count = mysqli_num_rows($result);
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
                                <li class="breadcrumb-item"><a href="#">커뮤니티</a></li>
                                <li aria-current="page" class="breadcrumb-item active"><? echo $board_name?></li>
                            </ol>
                        </nav>
                    </div>
                    <!-- 우측 사이드바-->
                    <div class="col-lg-3">
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
                    <div id="board" class="col-lg-9">
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
                                            <div class="card-body"><? echo $row['CONTENTS'] ?></div>
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
                                    <table class="table table-hover" style="text-align: center;">
                                        <thead>
                                        <tr >
                                            <th>번호</th>
                                            <th>제목</th>
                                            <th>작성자</th>
                                            <th>날짜</th>
                                            <th>조회수</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>제목</td>
                                            <td>작성자</td>
                                            <td>2021.02.02</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>제목</td>
                                            <td>작성자</td>
                                            <td>2021.02.02</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>제목</td>
                                            <td>작성자</td>
                                            <td>2021.02.02</td>
                                            <td>1</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <hr/>
                                    <div id="btn_write" class="navbar-collapse collapse d-none d-lg-block" style="text-align: right"><a href="basket.php" class="btn btn-primary navbar-btn">작성하기</a></div>

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
    </script>
</body>
</html>