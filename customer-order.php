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
                            <li aria-current="page" class="breadcrumb-item"><a href="#">My orders</a></li>
                            <li aria-current="page" class="breadcrumb-item active">Order # 1735</li>
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
                <div id="customer-order" class="col-lg-9">
                    <div class="box">
                        <h1>Order #1735</h1>
                        <p class="lead">Order #1735 was placed on <strong>22/06/2013</strong> and is currently <strong>Being prepared</strong>.</p>
                        <p class="text-muted">If you have any questions, please feel free to <a href="contact.php">contact us</a>, our customer service center is working for you 24/7.</p>
                        <hr>
                        <div class="table-responsive mb-4">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th colspan="2">Product</th>
                                    <th>Quantity</th>
                                    <th>Unit price</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><a href="#"><img src="img/detailsquare.jpg" alt="White Blouse Armani"></a></td>
                                    <td><a href="#">White Blouse Armani</a></td>
                                    <td>2</td>
                                    <td>$123.00</td>
                                    <td>$0.00</td>
                                    <td>$246.00</td>
                                </tr>
                                <tr>
                                    <td><a href="#"><img src="img/basketsquare.jpg" alt="Black Blouse Armani"></a></td>
                                    <td><a href="#">Black Blouse Armani</a></td>
                                    <td>1</td>
                                    <td>$200.00</td>
                                    <td>$0.00</td>
                                    <td>$200.00</td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Order subtotal</th>
                                    <th>$446.00</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">Shipping and handling</th>
                                    <th>$10.00</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">Tax</th>
                                    <th>$0.00</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">Total</th>
                                    <th>$456.00</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.table-responsive-->
                        <div class="row addresses">
                            <div class="col-lg-6">
                                <h2>Invoice address</h2>
                                <p>John Brown<br>13/25 New Avenue<br>New Heaven<br>45Y 73J<br>England<br>Great Britain</p>
                            </div>
                            <div class="col-lg-6">
                                <h2>Shipping address</h2>
                                <p>John Brown<br>13/25 New Avenue<br>New Heaven<br>45Y 73J<br>England<br>Great Britain</p>
                            </div>
                        </div>
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