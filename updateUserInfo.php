<?php
session_start();
$login_id = $_SESSION['LOGIN_ID'];

// 로그인 되어있지 않다면 메인화면으로 이동
if($login_id == null || $login_id == ''){
    echo "<script> document.location.href='index.php'</script>";
}

// mysql커넥션 연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 내정보 조회
$sqlUserInfo = "SELECT LOGIN_ID
                             , NAME
                             , ZIP_CODE
                             , ADDRESS_BASIC
                             , ADDRESS_DETAIL
                             , EMAIL_FRONT
                             , EMAIL_BACK
                             , PHONE_NUM1
                             , PHONE_NUM2
                             , PHONE_NUM3
        FROM USER
        WHERE LOGIN_ID = '$login_id'
        ";

$resultUserInfo = mysqli_query($conn, $sqlUserInfo);
$rowUserInfo = mysqli_fetch_array($resultUserInfo);

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
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <!-- breadcrumb-->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li aria-current="page" class="breadcrumb-item active">회원가입</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <!-- 좌측 사이드바-->
                <div class="col-lg-2"></div>
                <div class="col-lg-2">
                    <div class="card sidebar-menu mb-4">
                        <div class="card-header">
                            <h3 class="h4 card-title">마이페이지</h3>
                        </div>
                        <div class="card-body" id="side-bar">
                            <ul class="nav nav-pills flex-column category-menu" >
                                <li><a href="mypage.php?mypage_no=1" class="nav-link" style="color: #555555;">주문/배송</a></li>
                                <li><a href="updateUserInfo.php" class="nav-link active" style="color: #555555;">내정보</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--사이드바 종료-->
                <div class="col-lg-6">
                    <div class="box">
                        <h1>내정보</h1>
<!--                        <p class="lead">Not our registered customer yet?</p>
                        <p>With registration with us new world of fashion, fantastic discounts and much more opens to you! The whole process will not take you more than a minute!</p>
                        <p class="text-muted">If you have any questions, please feel free to <a href="contact.php">contact us</a>, our customer service center is working for you 24/7.</p>-->
                        <hr>
                        <div id="register">
                            <div class="form-group">
                                <label class="register_form" for="login_id">아이디</label>
                                <input id="login_id" name="login_id" type="text" class="form-control" value="<?=$rowUserInfo['LOGIN_ID']?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="register_form" for="password">비밀번호</label>
                                <div class="form-inline">
                                    <input id="password" name="password" type="password" class="form-control" style="width: 80%; margin-right: 5px;" readonly>
                                    <button class="btn btn-primary" id="btn_change_pw">비밀번호 변경</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="register_form" for="name">이름</label>
                                <input id="name" name="name" type="text" class="form-control" value="<?=$rowUserInfo['NAME']?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="register_form" for="email">이메일</label>
                                <input id="email" name="email" type="email" class="form-control" value="<?=$rowUserInfo['EMAIL_FRONT']?>@<?=$rowUserInfo['EMAIL_BACK']?>">
                            </div>
                            <div class="form-group">
                                <label class="register_form" for="phone_num">휴대폰번호</label>
                                <div class="form-inline">
                                    <input id="phone_num1" name="phone_num1" class="form-control" value="<?=$rowUserInfo['PHONE_NUM1']?>">-
                                    <input id="phone_num2" name="phone_num2" class="form-control" value="<?=$rowUserInfo['PHONE_NUM2']?>" >-
                                    <input id="phone_num3" name="phone_num3" class="form-control" value="<?=$rowUserInfo['PHONE_NUM3']?>" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="register_form" for="zip_code">주소</label>
                                <div class="form-inline">
                                    <input readonly id="zip_code" name="zip_code" type="number" placeholder="우편 번호" class="form-control" style="width: 100px; margin-right: 5px;" value="<?=$rowUserInfo['ZIP_CODE']?>">
                                    <button onclick="getPostcode();" class="btn btn-primary">주소찾기</button>
                                </div>
                                <input readonly id="address_basic" name="address_basic" type="text" placeholder="기본 주소" class="form-control" style="margin-top: 5px; margin-bottom: 5px;" value="<?=$rowUserInfo['ADDRESS_BASIC']?>">
                                <input id="address_detail" name="address_detail" type="text" placeholder="상세 주소" class="form-control" value="<?=$rowUserInfo['ADDRESS_DETAIL']?>">
                            </div><br>
                            <div class="text-center">
                                <button type="submit" id="btn_register" class="btn btn-primary"><i class="fa fa-user-md"></i> 정보수정</button>
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
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <script>
        $('#btn_register').on("click",function () {
            // 이메일 체크
            let regExp = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;

            if($('#name').val() == ''){
                alert("이름을 입력해주세요.");
                return;
            }

            if($('#email').val() == ''){
                alert("이메일을 입력해주세요.");
                return;
            }

            if(!regExp.test($('#email').val())){
                alert("이메일 형식에 맞게 작성해주세요.");
                return;
            }

            if($('#zip_code').val() == ''){
                alert("주소를 입력해주세요.");
                return;
            }

            if($('#phone_num1').val() == '' || $('#phone_num2').val() == '' || $('#phone_num3').val() == ''){
                alert("휴대폰번호를 입력해주세요.");
                return;
            }

            if( !regExp2.test($('#phone_num1').val()) || !regExp2.test($('#phone_num2').val()) || !regExp2.test($('#phone_num3').val())) {

                alert("전화번호는 숫자만 입력하세요");

                $('#phone_num1').val('');
                $('#phone_num2').val('');
                $('#phone_num3').val('');
                return;
            }

            let email = ($('#email').val()).split("@");

            if(confirm("정보수정을 완료하시겠습니까?")){
                // ajax로 registerComplete.php에 데이터 보내기
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/updateUserInfoComplete.php',
                    data: {
                          name: $('#name').val()
                        , emailFront: email[0]
                        , emailBack: email[1]
                        , zip_code: $('#zip_code').val()
                        , address_basic: $('#address_basic').val()
                        , address_detail: $('#address_detail').val()
                        , phone_num1: $('#phone_num1').val()
                        , phone_num2: $('#phone_num2').val()
                        , phone_num3: $('#phone_num3').val()
                    },

                    success: function (json) {
                        if (json.result == 'ok') {
                            location.reload();
                        } else {
                            alert("정보수정에 실패했습니다!");
                        }
                    },
                    error: function () {
                        alert("에러가 발생했습니다.");
                    }
                });
            }
        });

        // 우편주소 받기
        function getPostcode() {
            new daum.Postcode({
                oncomplete: function(data) {
                    // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                    // 도로명 주소의 노출 규칙에 따라 주소를 표시한다.
                    // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                    var roadAddr = data.roadAddress; // 도로명 주소 변수
                    var extraRoadAddr = ''; // 참고 항목 변수

                    // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                    // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                    if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                        extraRoadAddr += data.bname;
                    }
                    // 건물명이 있고, 공동주택일 경우 추가한다.
                    if(data.buildingName !== '' && data.apartment === 'Y'){
                        extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                    if(extraRoadAddr !== ''){
                        extraRoadAddr = ' (' + extraRoadAddr + ')';
                    }

                    // 우편번호와 주소 정보를 해당 필드에 넣는다.
                    $('#zip_code').val(data.zonecode);
                    if(roadAddr != ''){
                        $('#address_basic').val(roadAddr);
                    } else{
                        $('#address_basic').val(data.jibunAddress);
                    }

                    // 상세주소 삭제
                    $('#address_detail').val('');
                }
            }).open();
        }

        //비밀번호 변경
        $('#btn_change_pw').on("click", function(){
           window.open("changePw.php", "비밀번호 변경","width=200px;height=200px;");
        });
    </script>
</body>
</html>