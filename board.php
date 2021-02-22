<?php
    $board_no = $_GET['board_no'];
    $board_name = null;

    if($board_no == '1'){
        $board_name = '공지사항';
    } elseif ($board_no == '2'){
        $board_name = '자주묻는질문';
    } elseif ($board_no == '3'){
        $board_name = '자유게시판';
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
                                <li aria-current="page" class="breadcrumb-item active"><? echo $board_name?></li>
                            </ol>
                        </nav>
                    </div>
                    <div id="board" class="col-lg-12">
                        <div class="box">

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
</body>
</html>