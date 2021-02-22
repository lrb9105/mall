<?php
    $loginId = $_SESSION['LOGIN_ID'];
    $isSet = isset($_SESSION['LOGIN_ID']);
?>
<div id="top">
    <div class="container">
        <div class="row">
            <!--<div class="col-lg-6 offer mb-3 mb-lg-0"><a href="#" class="btn btn-success btn-sm">Offer of the day</a><a href="#" class="ml-1">Get flat 35% off on orders over $50!</a></div>-->
            <div class="col-lg-12 text-right text-lg-right">
                <ul class="menu list-inline mb-0">
                    <li class="list-inline-item" id="login"><a href="#" data-toggle="modal" data-target="#login-modal">로그인</a></li>
                    <li class="list-inline-item" id="login_id_li" style="display: none; color: #FFFFFF"><a href="#" ></a></li>
                    <li class="list-inline-item" id="logout" style="display: none;"><a href="#">로그아웃</a></li>
                    <li class="list-inline-item"><a href="register.php">회원가입</a></li>
                    <li class="list-inline-item"><a href="contact.php">고객센터</a></li>
                    <li class="list-inline-item"><a href="#">최근본상품</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="login-modal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">로그인</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div id="login_form">
                        <!-- 아이디 입력 폼-->
                        <div class="form-group">
                            <input id="email-modal" type="text" placeholder="아이디" class="form-control">
                        </div>
                        <!-- 비밀번호 입력 폼-->
                        <div class="form-group">
                            <input id="password-modal" type="password" placeholder="비밀번호" class="form-control">
                        </div>
                        <p class="text-center">
                            <button id="btn_login" class="btn btn-primary"><i class="fa fa-sign-in"></i> 로그인</button>
                        </p>
                    </div>
                    <p class="text-center text-muted">아직 회원이 아니신가요?</p>
                    <p class="text-center text-muted"><a href="register.php"><strong>회원가입 하러가기</strong></a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // 로그인 버튼 클릭 시 loginComplete.php로 이동
    // ajax통신을 해서 db에 사용자가 입력한 데이터가 있는지 확인 => 있다면 ok반환
    $('#btn_login').on("click",function(){
        // ajax로 registerComplete.php에 데이터 보내기
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '/mall/php/loginComplete.php',
            data: {
                  login_id: $('#email-modal').val()
                , password: $('#password-modal').val()
            },

            success: function (json) {
                if (json.result == 'ok') {
                    alert("로그인에 성공했습니다.");
                    // 로그인 -> 로그아웃으로 변경

                    $('#login').hide();
                    $('#logout').show();
                    $('#login_id_li').show();
                    $('#login_id_li').text(json.login_id + "님 ");
                    // 모달창 종료
                    $('#login-modal').modal("hide");
                } else {
                    alert("로그인에 실패했습니다. 아이디와 비밀번호를 확인해주세요.");
                }
            },
            error: function () {
                alert("에러가 발생했습니다.");
            }
        });
    });

    //로그아웃 => 세션종료
    $('#logout').on("click", function(){
        $('#login').show();
        $('#logout').hide();
        $('#login_id_li').hide();
    });

</script>