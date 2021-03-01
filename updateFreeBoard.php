<?php
//자유게시판 상세페이지
session_start();
if($_SESSION['USER_TYPE'] != "0"){
    echo "<script> document.location.href='/mall/index.php'; </script>";
}

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
            U.NAME
            FROM FREE_BOARD FB
            INNER JOIN USER U ON FB.WRITER = U.LOGIN_ID
            WHERE FB.SEQ = '$seq'
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
                                <li class="breadcrumb-item"><a href="#">커뮤니티</a></li>
                                <li aria-current="page" class="breadcrumb-item active">자유게시판 상세보기</li>
                            </ol>
                        </nav>
                    </div>
                    <div id="board" class="col-lg-12">
                        <div class="box">
                            <!-- Contact Section Begin -->
                            <section class="contact spad">
                                <form method="POST" action="/mall/php/updateFreeBoardCompl.php">
                                    <div class="container">
                                        <table class="table">
                                            <tr>
                                                <td height=20 align=center bgcolor=#ccc style="size: 20px;">게시글 작성</td>
                                            </tr>
                                            <tr>
                                                <td bgcolor=white>
                                                    <table class="table">
                                                        <tr>
                                                            <td>작성자</td>
                                                            <td>
                                                                <input class="form-control py-4" type="text" name="writer" id="writer" value="<?echo $row['NAME']?>" readonly>
                                                                <input class="form-control py-4" type="text" name="seq" id="seq" value="<?echo $seq?>" hidden>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>제목</td>
                                                            <td><input class="form-control py-4" type="text" name="title" id="title" maxlength="1000" value="<?echo $row['TITLE']?>"></td>
                                                        </tr>

                                                        <tr>
                                                            <td>내용</td>
                                                            <td>
                                                                <textarea name="contents" id="contents" class="nse_content" style="width: 100%; height: 400px;"><?echo $row['CONTENTS']?>.</textarea>
                                                                <script type="text/javascript">
                                                                    var oEditors = [];
                                                                    nhn.husky.EZCreator.createInIFrame({
                                                                        oAppRef: oEditors,
                                                                        elPlaceHolder: "contents",
                                                                        sSkinURI: "smart_editor2/SmartEditor2Skin.html",
                                                                        fCreator: "createSEditor2"
                                                                    });
                                                                    function submitContents(elClickedObj) {
                                                                        if(confirm("수정완료하시겠습니까?")){
                                                                            // 에디터의 내용이 textarea에 적용됩니다.
                                                                            oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);
                                                                            // 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("contents").value를 이용해서 처리하면 됩니다.
                                                                            try {
                                                                                elClickedObj.form.submit();
                                                                            } catch(e) {}
                                                                        }
                                                                    }
                                                                </script>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <div align="right">
                                            <!--<button id="btn_write" class="btn btn-primary navbar-btn">작성하기</button>-->
                                            <button id="" type="submit" onclick="submitContents(this)" class="btn btn-primary navbar-btn">수정하기</button>
                                        </div>
                                </form>
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
</body>
</html>