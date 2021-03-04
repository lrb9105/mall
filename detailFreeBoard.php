<?php
//자유게시판 상세페이지
$seq = $_GET['seq'];

$server = $_SERVER['DOCUMENT_ROOT'];

// mysql커넥션 연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

//조회수 없데이트
$sql2 = "UPDATE FREE_BOARD SET CNT =  IFNULL(CNT,0) + 1 WHERE SEQ = '$seq'";
$result_comment = mysqli_query($conn, $sql2);

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

//댓글가져오기
$sql = "SELECT  FBC.SEQ,
                FBC.WRITER,
                FBC.CONTENTS,
                FBC.CRE_DATETIME,
                FBC.UPD_DATETIME,
                US.NAME
            FROM FREE_BOARD_COMMENT FBC
            INNER JOIN USER US ON FBC.WRITER = US.LOGIN_ID
            WHERE FBC.PARENT_SEQ = '$seq'
            ";
// 쿼리를 통해 가져온 결과
$result_comment = mysqli_query($conn, $sql);
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
                                <div class="container">
                                    <table class="table">
                                        <tr>
                                            <td bgcolor=white>
                                                <div id="title" class="box">
                                                    <div>
                                                        <h1 style="text-align: center;"><?echo $row['TITLE'] ?></h1><br>
                                                        <p style="text-align: left;" class="lead">작성자: <?echo $row['NAME']?> <br> 작성일: <?echo $row['CRE_DATETIME']?> <span style="float: right;">조회수: <?echo $row['CNT']?></span></p>
                                                    </div>
                                                    <hr>
                                                    <div name="contents" id="contents" class="nse_content" style="width: 100%;" readonly><?echo $row['CONTENTS']?></div>
                                                </div>
                                                <table class="table">
                                                    <? while ($row_comment = mysqli_fetch_array($result_comment)){?>
                                                        <tr>
                                                            <td width="20%"><b><?echo $row_comment['NAME']?></b><br><?echo $row_comment['CRE_DATETIME']?></td>
                                                            <td width="1%">|</td>
                                                            <td width="79%">
                                                                <div style="word-break: break-all;"><?echo $row_comment['CONTENTS']?></div>
                                                                <?if($row_comment['WRITER'] == $_SESSION['LOGIN_ID']){?>
                                                                    <div style="text-align: right;">
                                                                        <a href="#" onclick="commentUpdateModalOpen(<?echo $row_comment['SEQ']?>, '<?echo $row_comment['CONTENTS']?>');" id="btn_comment_modify" data-toggle="modal" data-target="#comment-update-modal"><i class="fa fa-edit"></i><span>수정</span></a>
                                                                        <a href="#" onclick="deleteComment(<?echo $row_comment['SEQ']?>);" id="btn_comment_delete"><i class="fa fa-remove"></i><span>삭제</span></a>
                                                                    </div>
                                                                <?}?>
                                                            </td>
                                                        </tr>
                                                    <?}?>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <?if($_SESSION['LOGIN_ID'] != '') {?>
                                        <div class="container">
                                            <h2 style="display: inline;">comment</h2>
                                            <textarea id="comment_contents" class="form-control col-lg-12" rows="3"></textarea>
                                            <div style="text-align: right; margin-top: 5px;">
                                                <a id="btn_comment_write" data-toggle="collapse" href="#" class="btn navbar-btn btn-success"><i class="fa fa-comment-o"></i><span>댓글등록</span></a>
                                            </div>
                                        </div>
                                    <?}?>
                                    <br><br><br>
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
                            <div id="comment-update-modal" tabindex="-1" role="dialog" aria-labelledby="comment-update-modal" aria-hidden="true" class="modal fade">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">댓글수정</h5>
                                            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <div id="comment_form">
                                                <!-- 댓글-->
                                                <input id="comment_seq" hidden>
                                                <div class="form-group">
                                                    <textarea id="comment_contents_update" class="form-control col-lg-12" rows="3"></textarea>
                                                </div>
                                                <p class="text-center">
                                                    <button onclick="comment_update_complete()" id="btn_comment_update_complete" class="btn btn-primary"><i class="fa fa-sign-in"></i> 수정하기</button>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
            // 댓글등록
            $('#btn_comment_write').on("click",function(){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/comment/writeFbCommentCompl.php',
                    data: {
                        writer: '<?echo $_SESSION['LOGIN_ID']?>',
                        parent_seq: <?echo $seq?>,
                        contents: $('#comment_contents').val()
                    },

                    success: function (json) {
                        if (json.result == 'ok') {
                            alert("댓글을 등록헀습니다.");
                            location.reload();
                        } else {
                            alert("댓글등록에 실패했습니다!");
                        }
                    },
                    error: function () {
                        alert("에러가 발생했습니다.");
                    }
                });
            });

            // 댓글 수정
            function updateComment(){
                if(confirm("댓글을 수정 하시겠습니까?")){
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: '/mall/php/comment/updateFbCommentCompl.php',
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
            }


            // 댓글 삭제
            function deleteComment(seq){
                if(confirm("댓글을 삭제하시겠습니까?")){
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: '/mall/php/comment/deleteFbCommentCompl.php',
                        data: {
                            seq: seq
                        },

                        success: function (json) {
                            if (json.result == 'ok') {
                                alert("삭제를 완료헀습니다.");
                            } else {
                                alert("삭제에 실패했습니다!");
                            }
                            location.reload();
                        },
                        error: function () {
                            alert("에러가 발생했습니다.");
                        }
                    });
                }
            }

            // 원글 삭제
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

            // 댓글 모달 클릭 -> 댓글값 전달
            function commentUpdateModalOpen(seq, commentContents){
                $('#comment_seq').val(seq);
                $('#comment_contents_update').text(commentContents);
            }

            //댓글 수정
            function comment_update_complete(){
                if(confirm("댓글을 수정하시겠습니까?")){
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: '/mall/php/comment/updateFbCommentCompl.php',
                        data: {
                            seq: $('#comment_seq').val(),
                            contents: $('#comment_contents_update').val()
                        },

                        success: function (json) {
                            if (json.result == 'ok') {
                                alert("댓글수정을 완료헀습니다.");
                            } else {
                                alert("댓글수정에 실패했습니다!");
                            }
                            location.reload();
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