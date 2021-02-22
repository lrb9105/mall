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
                            <li aria-current="page" class="breadcrumb-item active">Page not found</li>
                        </ol>
                    </nav>
                    <div id="error-page" class="row">
                        <div class="col-md-6 mx-auto">
                            <div class="box text-center py-5">
                                <p class="text-center"><img src="img/.modal-header h5" alt="Obaju template"></p>
                                <h3>We are sorry - this page is not here anymore</h3>
                                <h4 class="text-muted">Error 404 - Page not found</h4>
                                <p class="text-center">To continue please use the <strong>Search form</strong> or <strong>Menu</strong> above.</p>
                                <p class="buttons"><a href="index.php" class="btn btn-primary"><i class="fa fa-home"></i> Go to Homepage</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        </div>
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