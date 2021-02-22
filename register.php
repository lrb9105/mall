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
                <div class="col-lg-3"></div>
                <div class="col-lg-6">
                    <!-- breadcrumb-->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li aria-current="page" class="breadcrumb-item active">회원가입</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-6">
                    <div class="box">
                        <h1>회원가입</h1>
<!--                        <p class="lead">Not our registered customer yet?</p>
                        <p>With registration with us new world of fashion, fantastic discounts and much more opens to you! The whole process will not take you more than a minute!</p>
                        <p class="text-muted">If you have any questions, please feel free to <a href="contact.php">contact us</a>, our customer service center is working for you 24/7.</p>-->
                        <hr>
                        <div id="register">
                            <div class="form-group">
                                <label for="login_id">아이디</label>
                                <input id="login_id" name="login_id" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password">비밀번호</label>
                                <input id="password" name="password" type="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password_confirm">비밀번호 확인</label>
                                <input id="password_confirm" name="password_confirm" type="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="name">이름</label>
                                <input id="name" name="name" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="email">이메일</label>
                                <input id="email" name="email" type="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="zip_code">주소</label>
                                <div class="form-inline">
                                    <input id="zip_code" name="zip_code" type="number" placeholder="우편 번호" class="form-control" style="width: 100px; margin-right: 5px;">
                                    <button class="btn btn-primary">찾기</button>
                                </div>
                                <input id="address_basic" name="address_basic" type="text" placeholder="기본 주소" class="form-control" style="margin-top: 5px; margin-bottom: 5px;">
                                <input id="address_detail" name="address_detail" type="text" placeholder="상세 주소" class="form-control">
                            </div>
                            <div class="text-center">
                                <button type="submit" id="btn_register" class="btn btn-primary"><i class="fa fa-user-md"></i> 회원가입 완료</button>
                            </div>
                        </div>


                        <!-- test.php로 이동-->
                        <!--<form action="php/test.php" method="post">
                            <div class="form-group">
                                <label for="login_id">아이디</label>
                                <input id="login_id" name="login_id" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password">비밀번호</label>
                                <input id="password" name="password" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="name">이름</label>
                                <input id="name" name="name" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="email">이메일</label>
                                <input id="email" name="email" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="zip_code">주소</label>
                                <div class="form-inline">
                                    <input id="zip_code" name="zip_code" type="text" placeholder="우편 번호" class="form-control" style="width: 100px; margin-right: 5px;" readonly>
                                    <button class="btn btn-primary">찾기</button>
                                </div>
                                <input id="address_basic" name="address_basic" type="text" placeholder="기본 주소" class="form-control" style="margin-top: 5px; margin-bottom: 5px;">
                                <input id="address_detail" name="address_detail" type="text" placeholder="상세 주소" class="form-control">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-user-md"></i> 회원가입 완료</button>
                            </div>
                        </form>-->
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
    <script>
        $('#btn_register').on("click",function () {
            // ajax로 registerComplete.php에 데이터 보내기
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/registerComplete.php',
                data: {
                    login_id: $('#login_id').val()
                    , password: $('#password').val()
                    , name: $('#name').val()
                    , email: $('#email').val()
                    , zip_code: $('#zip_code').val()
                    , address_basic: $('#address_basic').val()
                    , address_detail: $('#address_detail').val()
                },

                success: function (json) {
                    if (json.result == 'ok') {
                        alert("회원가입에 성공했습니다!");
                        location.replace('/mall/index.php');
                    } else {
                        alert("회원가입에 실패했습니다!");
                    }
                },
                error: function () {
                    alert("에러가 발생했습니다.");
                }
            });
        });
    </script>
</body>
</html>