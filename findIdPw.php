<?php
$no = $_GET['no'];

?>
<!DOCTYPE html>
<html>
<?php
include 'head.php'
?>
<style>
    .register_form > span{
        color: red;
    }
</style>
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
                    <div class="box">
                        <nav id="myTab" role="tablist" class="nav nav-tabs">
                            <?if($no == 1){?>
                                <a id="tab4-1-tab" data-toggle="tab" href="#tab4-1" role="tab" aria-controls="tab4-1" aria-selected="true" class="nav-item nav-link active"> <i class="icon-star"></i>아이디 찾기</a>
                                <a id="tab4-2-tab" data-toggle="tab" href="#tab4-2" role="tab" aria-controls="tab4-2" aria-selected="false" class="nav-item nav-link">비밀번호 찾기</a>
                            <?} else {?>
                                <a id="tab4-1-tab" data-toggle="tab" href="#tab4-1" role="tab" aria-controls="tab4-1" aria-selected="false" class="nav-item nav-link"> <i class="icon-star"></i>아이디 찾기</a>
                                <a id="tab4-2-tab" data-toggle="tab" href="#tab4-2" role="tab" aria-controls="tab4-2" aria-selected="true" class="nav-item nav-link active">비밀번호 찾기</a>
                            <?}?>

                        <div id="nav-tabContent" class="tab-content">
                            <?if($no == 1){?>
                            <div id="tab4-1" role="tabpanel" aria-labelledby="tab4-1-tab" class="tab-pane fade show active">
                            <?} else {?>
                                <div id="tab4-1" role="tabpanel" aria-labelledby="tab4-1-tab" class="tab-pane fade">
                            <?}?>
                                <br>
                                <h2>아이디 찾기</h2>
                                <h4 id="user_id"></h4>
                                <hr>
                                <div id="find_id">
                                    <div class="form-group">
                                        <label class="register_form" for="name">이름</label>
                                        <input id="name" name="name" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="register_form" for="phone_num">휴대폰번호</label>
                                        <div class="form-inline">
                                            <input id="phone_num1" name="phone_num1" class="form-control" >-
                                            <input id="phone_num2" name="phone_num2" class="form-control" >-
                                            <input id="phone_num3" name="phone_num3" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" id="btn_find_id" class="btn btn-primary"><i class="fa fa-user-md"></i> 아이디 찾기</button>
                                    </div>
                                    <br>
                                </div>
                            </div>
                                <?if($no == 1){?>
                                <div id="tab4-2" role="tabpanel" aria-labelledby="tab4-2-tab" class="tab-pane fade">
                                    <?} else {?>
                                    <div id="tab4-2" role="tabpanel" aria-labelledby="tab4-2-tab" class="tab-pane fade show active">
                                        <?}?>
                                <br>
                                <h2>비밀번호 찾기</h2>
                                <p id="temp_pw"></p>
                                <hr>
                                <div id="find_pw">
                                    <div class="form-group">
                                        <label class="register_form" for="login_id">아이디</label>
                                        <input id="pw_login_id" name="pw_login_id" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="register_form" for="name">이름</label>
                                        <input id="pw_name" name="pw_name" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="register_form" for="phone_num">휴대폰번호</label>
                                        <div class="form-inline">
                                            <input id="pw_phone_num1" name="pw_phone_num1" class="form-control" >-
                                            <input id="pw_phone_num2" name="pw_phone_num2" class="form-control" >-
                                            <input id="pw_phone_num3" name="pw_phone_num3" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" id="btn_find_pw" class="btn btn-primary"><i class="fa fa-user-md"></i> 비밀번호 찾기</button>
                                    </div>
                                    <br>
                                </div>
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
    <script>
        // 아이디 찾기
        $('#btn_find_id').on("click", function() {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/findIdCompl.php',
                data: {
                    name: $('#name').val(),
                    phoneNum1: $('#phone_num1').val(),
                    phoneNum2: $('#phone_num2').val(),
                    phoneNum3: $('#phone_num3').val()
                },

                success: function (json) {
                    if (json.result == 'ok') {
                        $('#user_id').text("당신의 아이디는 " + json.id + "입니다.");
                    } else {
                        alert("입력하신 정보에 해당하는 정보가 없습니다.");
                    }
                },
                error: function () {
                    alert("에러가 발생했습니다.");
                }
            });
        });

        // 비밀번호 찾기
        $('#btn_find_pw').on("click", function() {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/findPwCompl.php',
                data: {
                    name: $('#pw_name').val(),
                    phoneNum1: $('#pw_phone_num1').val(),
                    phoneNum2: $('#pw_phone_num2').val(),
                    phoneNum3: $('#pw_phone_num3').val(),
                    loginId: $('#pw_login_id').val()
                },

                success: function (json) {
                    if (json.result == 'ok') {
                        $('#temp_pw').text("임시비밀번호가 발급되었습니다.\n" + "임시비밀번호: " + json.temp_pw + " 입니다.\n" +
                            "임시 비밀번호로 로그인 후 비밀번호를 변경하시기 바랍니다.");
                    } else {
                        alert("입력하신 정보에 해당하는 정보가 없습니다.");
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