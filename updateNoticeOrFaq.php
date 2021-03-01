<?php
session_start();
if($_SESSION['USER_TYPE'] != "0"){
    echo "<script> document.location.href='/mall/index.php'; </script>";
}

$seq = $_GET['SEQ'];

// mysql커넥션 연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
$sql = null;

// 데이터 가져오기(자주묻는 질문, 공지사항)
$sql = "SELECT SEQ, TITLE, CONTENTS,TYPE
            FROM NOTICE_AND_FAQ
            WHERE SEQ = '$seq'
            ";
// 쿼리를 통해 가져온 결과
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
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
                                <li aria-current="page" class="breadcrumb-item active">공지사항 | 자주묻는 질문 작성</li>
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
                                                            <select name="board-type" id="board-type" readonly>
                                                                <option value="">종류</option>
                                                                <?if($row['TYPE'] == "1") { ?>
                                                                <option value="1" selected>공지사항</option>
                                                                <?} else {?>
                                                                <option value="2" selected>자주묻는 질문</option>
                                                                <?}?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>제목</td>
                                                        <td><input class="form-control py-4" type="text" name="title" id="title" maxlength="1000" value="<?echo $row['TITLE']?>"></td>
                                                    </tr>

                                                    <tr>
                                                        <td>내용</td>
                                                        <td><textarea class="form-control py-4" id="contents" name="contents" name="id" cols=85 rows=15 maxlength="4000"><?echo $row['CONTENTS']?></textarea></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <div align="right">
                                        <button id="btn_modify" class="btn btn-primary navbar-btn">수정하기</button>
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
            $('#btn_modify').on("click",function(){
                if(confirm("수정완료 하시겠습니까?")){
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: '/mall/php/updateNoticeOrFaqCompl.php',
                        data: {
                            SEQ: <?echo $seq?>
                            , TITLE: $('#title').val()
                            , CONTENTS: $('#contents').val()
                        },

                        success: function (json) {
                            if (json.result == 'ok') {
                                alert("수정 완료했습니다.");
                                location.replace('/mall/board.php?board_no=<?echo $row['TYPE']?>');
                            } else {
                                alert("수정에 실패했습니다!");
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