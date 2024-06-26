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
                            <li aria-current="page" class="breadcrumb-item active">faq</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <!--
                    *** PAGES MENU ***
                    _________________________________________________________
                    -->
                    <div class="card sidebar-menu mb-4">
                        <div class="card-header">
                            <h3 class="h4 card-title">Pages</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills flex-column">
                                <li><a href="text.php" class="nav-link">Text page</a></li>
                                <li><a href="contact.php" class="nav-link">Contact page</a></li>
                                <li><a href="faq.php" class="nav-link">FAQ</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- *** PAGES MENU END ***-->
                    <div class="banner"><a href="#"><img src="img/banner.jpg" alt="sales 2014" class="img-fluid"></a></div>
                </div>
                <div class="col-lg-9">
                    <div id="contact" class="box">
                        <h1>Frequently asked questions</h1>
                        <p class="lead">Are you curious about something? Do you have some kind of problem with our products?</p>
                        <p>Please feel free to contact us, our customer service center is working for you 24/7.</p>
                        <hr>
                        <div id="accordion">
                            <div class="card border-primary mb-3">
                                <div id="headingOne" class="card-header p-0 border-0">
                                    <h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="btn btn-primary d-block text-left rounded-0">1. What to do if I have still not received the order?</a></h4>
                                </div>
                                <div id="collapseOne" aria-labelledby="headingOne" data-parent="#accordion" class="collapse show">
                                    <div class="card-body">
                                        <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
                                        <ul>
                                            <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
                                            <li>Aliquam tincidunt mauris eu risus.</li>
                                            <li>Vestibulum auctor dapibus neque.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card border-primary mb-3">
                                <div id="headingTwo" class="card-header p-0 border-0">
                                    <h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" class="btn btn-primary d-block text-left rounded-0">2. What are the postal rates?</a></h4>
                                </div>
                                <div id="collapseTwo" aria-labelledby="headingTwo" data-parent="#accordion" class="collapse">
                                    <div class="card-body">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</div>
                                </div>
                            </div>
                            <div class="card border-primary">
                                <div id="headingThree" class="card-header p-0 border-0">
                                    <h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="btn btn-primary d-block text-left rounded-0">3. Do you send overseas?</a></h4>
                                </div>
                                <div id="collapseThree" aria-labelledby="headingThree" data-parent="#accordion" class="collapse">
                                    <div class="card-body">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</div>
                                </div>
                            </div>
                        </div>
                        <!-- /.accordion-->
                    </div>
                </div>
                <!-- /.col-lg-9-->
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