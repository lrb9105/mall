<?php
session_start();

$login_id_cookie = $_COOKIE['LOGIN_ID'];
$login_pw_cookie = $_COOKIE['PW'];
$login_id = $_SESSION['LOGIN_ID'];

if($login_id_cookie != '' && $login_pw_cookie != '' && $login_id ==''){
    //mysql연결
    $conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

    $sql = "SELECT LOGIN_ID, NAME, USER_TYPE
        FROM USER 
        WHERE LOGIN_ID='$login_id_cookie' 
        AND PASSWORD='$login_pw_cookie'
        ";

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    if($row == null){
        // 해당하는 값이 없다면 세션 삭제
        session_unset();
    } else if($row[0] != null){
        $_SESSION['LOGIN_ID'] = $row[0];
        $_SESSION['NAME'] = $row[1];
        $_SESSION['USER_TYPE'] = $row[2];
    } else{
        // 해당하는 값이 없다면 세션 삭제
        session_unset();
    }
}
?>
<!DOCTYPE html>
<html>
<?php
include 'head.php'
?>
<link id="new-stylesheet" rel="stylesheet">
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
                    <div class="col-md-12">
                        <div id="main-slider" class="owl-carousel owl-theme">
                            <div class="item"><img src="img/main-slider1.jpg" alt="" class="img-fluid"></div>
                            <div class="item"><img src="img/main-slider2.jpg" alt="" class="img-fluid"></div>
                            <div class="item"><img src="img/main-slider3.jpg" alt="" class="img-fluid"></div>
                            <div class="item"><img src="img/main-slider4.jpg" alt="" class="img-fluid"></div>
                        </div>
                        <!-- /#main-slider-->
                    </div>
                </div>
            </div>
            <!--
            *** ADVANTAGES HOMEPAGE ***
            _________________________________________________________
            -->
            <div id="advantages">
                <div class="container">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="box clickable d-flex flex-column justify-content-center mb-0 h-100">
                                <div class="icon"><i class="fa fa-heart"></i></div>
                                <h3><a href="#">We love our customers</a></h3>
                                <p class="mb-0">We are known to provide best possible service ever</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box clickable d-flex flex-column justify-content-center mb-0 h-100">
                                <div class="icon"><i class="fa fa-tags"></i></div>
                                <h3><a href="#">Best prices</a></h3>
                                <p class="mb-0">You can check that the height of the boxes adjust when longer text like this one is used in one of them.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box clickable d-flex flex-column justify-content-center mb-0 h-100">
                                <div class="icon"><i class="fa fa-thumbs-up"></i></div>
                                <h3><a href="#">100% satisfaction guaranteed</a></h3>
                                <p class="mb-0">Free returns on everything for 3 months.</p>
                            </div>
                        </div>
                    </div>
                    <!-- /.row-->
                </div>
                <!-- /.container-->
            </div>
            <!-- /#advantages-->
            <!-- *** ADVANTAGES END ***-->
            <!--
            *** HOT PRODUCT SLIDESHOW ***
            _________________________________________________________
            -->
            <div id="hot">
                <!--<div class="box py-4">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="mb-0">Hot this week</h2>
                            </div>
                        </div>
                    </div>
                </div>-->
                <div class="container">
                    <div class="product-slider owl-carousel owl-theme">
                        <div class="item">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php"><img src="img/product1.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php"><img src="img/product1_2.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php" class="invisible"><img src="img/product1.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3><a href="detail.php">Fur coat with very but very very long name</a></h3>
                                    <p class="price">
                                        <del></del>$143.00
                                    </p>
                                </div>
                                <!-- /.text-->
                                <div class="ribbon sale">
                                    <div class="theribbon">SALE</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                                <div class="ribbon new">
                                    <div class="theribbon">NEW</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                                <div class="ribbon gift">
                                    <div class="theribbon">GIFT</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                            </div>
                            <!-- /.product-->
                        </div>
                        <div class="item">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php"><img src="img/product2.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php"><img src="img/product2_2.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php" class="invisible"><img src="img/product2.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3><a href="detail.php">White Blouse Armani</a></h3>
                                    <p class="price">
                                        <del>$280</del>$143.00
                                    </p>
                                </div>
                                <!-- /.text-->
                                <div class="ribbon sale">
                                    <div class="theribbon">SALE</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                                <div class="ribbon new">
                                    <div class="theribbon">NEW</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                                <div class="ribbon gift">
                                    <div class="theribbon">GIFT</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                            </div>
                            <!-- /.product-->
                        </div>
                        <div class="item">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php"><img src="img/product3.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php"><img src="img/product3_2.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php" class="invisible"><img src="img/product3.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3><a href="detail.php">Black Blouse Versace</a></h3>
                                    <p class="price">
                                        <del></del>$143.00
                                    </p>
                                </div>
                                <!-- /.text-->
                            </div>
                            <!-- /.product-->
                        </div>
                        <div class="item">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php"><img src="img/product3.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php"><img src="img/product3_2.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php" class="invisible"><img src="img/product3.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3><a href="detail.php">Black Blouse Versace</a></h3>
                                    <p class="price">
                                        <del></del>$143.00
                                    </p>
                                </div>
                                <!-- /.text-->
                            </div>
                            <!-- /.product-->
                        </div>
                        <div class="item">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php"><img src="img/product2.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php"><img src="img/product2_2.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php" class="invisible"><img src="img/product2.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3><a href="detail.php">White Blouse Versace</a></h3>
                                    <p class="price">
                                        <del></del>$143.00
                                    </p>
                                </div>
                                <!-- /.text-->
                                <div class="ribbon new">
                                    <div class="theribbon">NEW</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                            </div>
                            <!-- /.product-->
                        </div>
                        <div class="item">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php"><img src="img/product1.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php"><img src="img/product1_2.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php" class="invisible"><img src="img/product1.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3><a href="detail.php">Fur coat</a></h3>
                                    <p class="price">
                                        <del></del>$143.00
                                    </p>
                                </div>
                                <!-- /.text-->
                                <div class="ribbon gift">
                                    <div class="theribbon">GIFT</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                            </div>
                            <!-- /.product-->
                        </div>
                        <div class="item">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php"><img src="img/product2.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php"><img src="img/product2_2.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php" class="invisible"><img src="img/product2.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3><a href="detail.php">White Blouse Armani</a></h3>
                                    <p class="price">
                                        <del>$280</del>$143.00
                                    </p>
                                </div>
                                <!-- /.text-->
                                <div class="ribbon sale">
                                    <div class="theribbon">SALE</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                                <div class="ribbon new">
                                    <div class="theribbon">NEW</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                                <div class="ribbon gift">
                                    <div class="theribbon">GIFT</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                            </div>
                            <!-- /.product-->
                        </div>
                        <div class="item">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php"><img src="img/product3.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php"><img src="img/product3_2.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php" class="invisible"><img src="img/product3.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3><a href="detail.php">Black Blouse Versace</a></h3>
                                    <p class="price">
                                        <del></del>$143.00
                                    </p>
                                </div>
                                <!-- /.text-->
                            </div>
                            <!-- /.product-->
                        </div>
                        <!-- /.product-slider-->
                    </div>
                    <!-- /.container-->
                </div>
                <!-- /#hot-->
                <!-- *** HOT END ***-->
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
    </script>
</body>
</html>