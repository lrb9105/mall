<?php
session_start();
$login_id = $_SESSION['LOGIN_ID'];

// 로그인 되어있지 않다면 메인화면으로 이동
if($login_id == null || $login_id == ''){
    echo "<script> document.location.href='index.php'</script>";
}

// 자유게시판 작성페이지
// 만약 답글작성이라면 원글의 데이터 가져오기
$seq = $_GET['seq'];
$row = null;
$title = null;

if($seq != ''){
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
            FB.GROUP_NO,
            FB.GROUP_ORDER
            FROM FREE_BOARD FB
            WHERE FB.SEQ = '$seq'
            ";
    // 쿼리를 통해 가져온 결과
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    //그룹번호, 그룹내순서, DEPTH, 제목생성
    $group_no = $row['GROUP_NO'];
    $group_order = $row['GROUP_ORDER'] + 1;
    $depth = $row['DEPTH'] + 1;
    $title = 'RE: '.$row['TITLE'];
}
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
                                <li aria-current="page" class="breadcrumb-item active">자유게시판 작성</li>
                            </ol>
                        </nav>
                    </div>
                    <div id="board" class="col-lg-12">
                        <div class="box">
                            <!-- Contact Section Begin -->
                            <section class="contact spad">
                                <form method="POST" action="/mall/php/writeFreeBoardCompl.php">
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
                                                                <input class="form-control py-4" type="text" name="writer" id="writer" value="<?echo $_SESSION['NAME']?>" readonly>
                                                                <input class="form-control py-4" type="text" name="login_id" id="login_id" value="<?echo $_SESSION['LOGIN_ID']?>" readonly hidden>
                                                                <?if($seq != '') {?>
                                                                    <input type="text" name="seq" id="seq" value="<?echo $seq?>" hidden>
                                                                <?}?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>제목</td>
                                                            <td><input class="form-control py-4" type="text" name="title" id="title" maxlength="1000" value="<? if($seq != '') echo $title?>"></td>
                                                        </tr>

                                                        <tr>
                                                            <td>내용</td>
                                                            <td>
                                                                <textarea name="contents" id="contents" class="nse_content" style="width: 100%; height: 400px;"></textarea>
                                                                <script type="text/javascript">
                                                                    var oEditors = [];
                                                                    nhn.husky.EZCreator.createInIFrame({
                                                                        oAppRef: oEditors,
                                                                        elPlaceHolder: "contents",
                                                                        sSkinURI: "smart_editor2/SmartEditor2Skin.html",
                                                                        fCreator: "createSEditor2"
                                                                    });
                                                                    function submitContents(elClickedObj) {
                                                                        // 에디터의 내용이 textarea에 적용됩니다.
                                                                        oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);
                                                                        // 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("contents").value를 이용해서 처리하면 됩니다.
                                                                        try {
                                                                            elClickedObj.form.submit();
                                                                        } catch(e) {}
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
                                            <button id="" type="submit" onclick="submitContents(this)" class="btn btn-primary navbar-btn">작성하기</button>
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