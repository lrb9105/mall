<?php
//자유게시판 상세페이지
$seq = $_GET['seq'];

// mysql커넥션 연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 데이터 가져오기 - 자유게시판
$sql = "SELECT FB.TITLE ,
            FB.CONTENTS ,
            FB.WRITER ,
            FB.CNT,
            FB.DEPTH ,
            FB.CRE_DATETIME,
            FB.SEQ,
            U.NAME
            FROM FREE_BOARD FB
            INNER JOIN USER U ON FB.WRITER = U.LOGIN_ID
            WHERE FB.SEQ = '$seq'
            ";
// 쿼리를 통해 가져온 결과
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
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
                                <li class="breadcrumb-item"> href="#">커뮤니티</a></li>
                                <li aria-current="page" class="breadcrumb-item active">자유게시판 상세보기</li>
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
                                            <td bgcolor=white>
                                                <div id="title" class="box">
                                                    <div>
                                                        <h1 style="text-align: center;"><?echo $row['TITLE']?></h1>
                                                        <p style="text-align: left;" class="lead"><?echo $row['NAME']?> | <?echo $row['CRE_DATETIME']?> <span style="float: right;">조회수: <?echo $row['CNT']?></span></p>
                                                    </div>
                                                    <hr>
                                                    <div name="contents" id="contents" class="nse_content" style="width: 100%;" readonly><?echo $row['CONTENTS']?></div>
                                                    <hr>
                                                </div>
                                                <!--<table class="table">
                                                    <tr>
                                                        <td>작성자</td>
                                                        <td>
                                                            <input class="form-control py-2" type="text" name="writer" id="writer" value="<?/*echo $row['NAME']*/?>" readonly>
                                                            <input class="form-control py-2" type="text" name="writer" id="writer" value="" readonly>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>제목</td>
                                                        <td><input class="form-control py-4" type="text" name="title" id="title" value="<?/*echo $row['TITLE']*/?>" readonly></td>
                                                    </tr>

                                                    <tr>
                                                        <td>내용</td>
                                                        <td>
                                                            <div name="contents" id="contents" class="nse_content" style="width: 100%;" readonly><?/*echo $row['CONTENTS']*/?></div>
                                                        </td>
                                                    </tr>
                                                </table>-->
                                            </td>
                                        </tr>
                                    </table>
                                    <div align="right">
                                        <!--<button id="btn_write" class="btn btn-primary navbar-btn">작성하기</button>-->
                                        <?if($_SESSION['LOGIN_ID'] != '') {?>
                                            <a href="writeFreeBoard.php?seq=<?echo $row['SEQ']?>" style="color: white;"><button id="btn_reply" class="btn btn-primary navbar-btn">답글</button></a>
                                        <?}?>
                                        <?if($row['WRITER'] == $_SESSION['LOGIN_ID']){?>
                                            <a href="updateFreeBoard.php?seq=<?echo $seq?>" style="color: white;"><button id="btn_modify" class="btn btn-primary navbar-btn">수정</button></a>
                                            <button id="btn_delete" class="btn btn-info navbar-btn">삭제</button>
                                        <?}?>
                                        <a href="board.php?board_no=3" style="color: white;"><button id="btn_list"  class="btn btn-dark navbar-btn">목록</button></a>
                                        <!--<button id="" type="submit" onclick="submitContents(this)" class="btn btn-primary navbar-btn">작성하기</button>-->
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
            $('#btn_delete').on("click",function(){
                if(confirm("정말로 삭제하시겠습니까?")){
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: '/mall/php/deleteFreeBoardCompl.php',
                        data: {
                            seq: <?echo $seq?>
                        },

                        success: function (json) {
                            if (json.result == 'ok') {
                                alert("삭제를 완료헀습니다.");
                            } else {
                                alert("삭제에 실패했습니다!");
                            }
                            location.replace('board.php?board_no=3');
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