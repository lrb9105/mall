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
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li aria-current="page" class="breadcrumb-item active">My wishlist</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <!--
                    *** CUSTOMER MENU ***
                    _________________________________________________________
                    -->
                    <div class="card sidebar-menu">
                        <div class="card-header">
                            <h3 class="h4 card-title">Customer section</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills flex-column"><a href="customer-orders.php" class="nav-link active"><i class="fa fa-list"></i> My orders</a><a href="customer-wishlist.php" class="nav-link"><i class="fa fa-heart"></i> My wishlist</a><a href="customer-account.php" class="nav-link"><i class="fa fa-user"></i> My account</a><a href="index.php" class="nav-link"><i class="fa fa-sign-out"></i> Logout</a></ul>
                        </div>
                    </div>
                    <!-- /.col-lg-3-->
                    <!-- *** CUSTOMER MENU END ***-->
                </div>
                <div id="wishlist" class="col-lg-9">
                    <ul class="breadcrumb">
                        <li><a href="#">Home</a></li>
                        <li>Ladies</li>
                    </ul>
                    <div class="box">
                        <h1>My wishlist</h1>
                        <p class="lead">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
                    </div>
                    <div class="row products">
                        <div class="col-lg-3 col-md-4">
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
                                    <p class="buttons"><a href="detail.php" class="btn btn-outline-secondary">View detail</a><a href="basket.php" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Add to cart</a></p>
                                </div>
                                <!-- /.text-->
                            </div>
                            <!-- /.product            -->
                        </div>
                        <div class="col-lg-3 col-md-4">
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
                                    <p class="buttons"><a href="detail.php" class="btn btn-outline-secondary">View detail</a><a href="basket.php" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Add to cart</a></p>
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
                            <!-- /.product            -->
                        </div>
                        <div class="col-lg-3 col-md-4">
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
                                    <p class="buttons"><a href="detail.php" class="btn btn-outline-secondary">View detail</a><a href="basket.php" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Add to cart</a></p>
                                </div>
                                <!-- /.text-->
                            </div>
                            <!-- /.product            -->
                        </div>
                        <div class="col-lg-3 col-md-4">
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
                                    <p class="buttons"><a href="detail.php" class="btn btn-outline-secondary">View detail</a><a href="basket.php" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Add to cart</a></p>
                                </div>
                                <!-- /.text-->
                            </div>
                            <!-- /.product            -->
                        </div>
                        <div class="col-lg-3 col-md-4">
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
                                    <p class="buttons"><a href="detail.php" class="btn btn-outline-secondary">View detail</a><a href="basket.php" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Add to cart</a></p>
                                </div>
                                <!-- /.text-->
                                <div class="ribbon new">
                                    <div class="theribbon">NEW</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                            </div>
                            <!-- /.product            -->
                        </div>
                        <div class="col-lg-3 col-md-4">
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
                                    <p class="buttons"><a href="detail.php" class="btn btn-outline-secondary">View detail</a><a href="basket.php" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Add to cart</a></p>
                                </div>
                                <!-- /.text-->
                                <div class="ribbon gift">
                                    <div class="theribbon">GIFT</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                            </div>
                            <!-- /.product            -->
                        </div>
                        <div class="col-lg-3 col-md-4">
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
                                    <p class="buttons"><a href="detail.php" class="btn btn-outline-secondary">View detail</a><a href="basket.php" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Add to cart</a></p>
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
                            <!-- /.product            -->
                        </div>
                        <div class="col-lg-3 col-md-4">
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
                                    <p class="buttons"><a href="detail.php" class="btn btn-outline-secondary">View detail</a><a href="basket.php" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Add to cart</a></p>
                                </div>
                                <!-- /.text-->
                            </div>
                            <!-- /.product            -->
                        </div>
                        <!-- /.products-->
                    </div>
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