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
                                <li class="breadcrumb-item"><a href="#">관리자</a></li>
                                <li aria-current="page" class="breadcrumb-item active">공지사항&자주묻는질문 작성</li>
                            </ol>
                        </nav>
                    </div>
                    <div id="board" class="col-lg-12">
                        <div class="box">
                            <!-- Contact Section Begin -->
                            <section class="contact spad">
                                <div class="container">
                                    <table class="table">
                                        <tr>
                                            <td height=20 align=center bgcolor=#ccc style="size: 20px;">작성하기</td>
                                        </tr>
                                        <tr>
                                            <td bgcolor=white>
                                                <table class="table">
                                                    <tr>
                                                        <td>종류</td>
                                                        <td>
                                                            <select name="board-type" id="board-type">
                                                                <option value="">종류</option>
                                                                <option value="1">공지사항</option>
                                                                <option value="2">자주묻는 질문</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>제목</td>
                                                        <td><input class="form-control py-4" type="text" name="title" id="title" maxlength="1000"></td>
                                                    </tr>

                                                    <tr>
                                                        <td>내용</td>
                                                        <td><textarea class="form-control py-4" id="contents" name="contents" name="id" cols=85 rows=15 maxlength="4000"></textarea></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <div align="right">
                                        <button id="btn_write" class="btn btn-primary navbar-btn">작성하기</button>
                                    </div>
                            </section>
                            <!-- Contact Section End -->
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
            $('#btn_write').on("click",function(){
                if(confirm("작성하시겠습니까?")){
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: '/mall/php/writeNoticeOrFaqCompl.php',
                        data: {
                            board_type: $('#board-type').val()
                            , title: $('#title').val()
                            , contents: $('#contents').val()
                        },

                        success: function (json) {
                            if (json.result == 'ok') {
                                alert("작성완료했습니다.");
                                location.replace('/mall/board.php?board_no=' + $('#board-type').val());
                            } else {
                                alert("작성에 실패했습니다!");
                            }
                        },
                        error: function () {
                            alert("에러가 발생했습니다.");
                        }
                    });
                }
            });
        </script>
</body>
</html>