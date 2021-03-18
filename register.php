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
                                <label class="register_form" for="login_id"><span>* </span>아이디</label>
                                <div class="form-inline">
                                    <input id="login_id" name="login_id" type="text" class="form-control" style="width: 80%; margin-right: 5px;">
                                    <button class="btn btn-primary" id="btn_check_dupl">중복체크</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="register_form" for="password"><span>* </span>비밀번호</label>
                                <input id="password" name="password" type="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="register_form" for="password_confirm"><span>* </span>비밀번호 확인</label>
                                <input id="password_confirm" name="password_confirm" type="password" class="form-control">

                            </div>
                            <div class="form-group">
                                <label class="register_form" for="name"><span>* </span>이름</label>
                                <input id="name" name="name" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="register_form" for="email"><span>* </span>이메일</label>
                                <input id="email" name="email" type="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="register_form" for="phone_num"><span>* </span>휴대폰번호</label>
                                <div class="form-inline">
                                    <input id="phone_num1" name="phone_num1" class="form-control" >-
                                    <input id="phone_num2" name="phone_num2" class="form-control" >-
                                    <input id="phone_num3" name="phone_num3" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="register_form" for="zip_code"><span>* </span>주소</label>
                                <div class="form-inline">
                                    <input readonly id="zip_code" name="zip_code" type="number" placeholder="우편 번호" class="form-control" style="width: 100px; margin-right: 5px;">
                                    <button onclick="getPostcode();" class="btn btn-primary">주소찾기</button>
                                </div>
                                <input readonly id="address_basic" name="address_basic" type="text" placeholder="기본 주소" class="form-control" style="margin-top: 5px; margin-bottom: 5px;">
                                <input id="address_detail" name="address_detail" type="text" placeholder="상세 주소" class="form-control">
                            </div><br>
                            <div id="agreementDivArea" class="agreement" style="width: 70%;">
                                <div>
                                    <input type="checkbox" class="n-check" id="checkAll">
                                    <label for="checkAll" class="all">약관 전체동의</label>
                                </div>
                                <div>
                                    <input type="checkbox" class="n-check agree-item required-agree-item" id="agreeCheckbox" name="agreeCheckbox">
                                    <label for="agreeCheckbox">개인정보 수집 이용동의(필수)</label>
                                    <a href="javascript:privacyAgreeUsagePop();" style="text-align: right;"><u>약관보기</u></a>
                                </div>
                                <div>
                                    <input type="checkbox" class="n-check agree-item required-agree-item" id="useTermsCheckbox" name="useTermsCheckbox">
                                    <label for="useTermsCheckbox">MallForMan 이용약관(필수)</label>
                                    <a href="javascript:serviceAgreementPop();" style="text-align: right;"><u>약관보기</u></a>
                                </div>

                                <div>
                                    <input type="checkbox" class="n-check agree-item optional-agree-item" id="marketingReceiveAgreeYn" name="marketingReceiveAgreeYn">
                                    <label for="marketingReceiveAgreeYn">마케팅 활용 및 광고성 정보 수신 동의(선택)</label>
                                    <a href="javascript:marketingAgreementPop();" style="text-align: right;"><u>약관보기</u></a>
                                </div>
                            </div><br><br>
                            <div class="text-center">
                                <button type="submit" id="btn_register" class="btn btn-primary"><i class="fa fa-user-md"></i> 회원가입 완료</button>
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
        let idCheckComplete = false;

        // 중복체크
        $('#btn_check_dupl').on("click", function() {
            let id = $('#login_id').val();
            // 아이디는 영어 숫자만 허용(8-20자리)
            let idReg = /^[A-Za-z0-9+]{8,20}$/;

            // 영문자 혼합
            let chk_num = id.search(/[0-9]/g);
            let chk_eng = id.search(/[a-z]/ig);


            if (id == '') {
                alert("아이디를 입력하세요.");
                return;
            }

            if (!idReg.test(id) || chk_num < 0 || chk_eng < 0) {
                alert('아이디는 영어, 숫자를 혼합해야 하며 8-20자리 이내로 만들어야 합니다.');
                return;
            }

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/idCheck.php',
                data: {
                    login_id: id
                },

                success: function (json) {
                    if (json.result == 'ok') {
                        idCheckComplete = true;
                        alert("사용할 수 있는 아이디 입니다.");
                    } else {
                        alert("이미 사용중인 아이디 입니다.");
                        $('#login_id').val('');
                        $('#login_id').focus();
                    }
                },
                error: function () {
                    alert("에러가 발생했습니다.");
                }
            });
        });

        $('#btn_register').on("click",function () {
            // 이메일 체크
            let regExp = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i;
            let regExp2 = /^[0-9]*$/

            if($('#login_id').val() == ''){
                alert("아이디를 입력해주세요.");
                return;
            }

            if(!idCheckComplete){
                alert("아이디 중복체크를 해주세요.");
                return;
            }

            if($('#password').val() == ''){
                alert("비밀번호를 입력해주세요.");
                return;
            }

            if($('#password_confirm').val() == ''){
                alert("비밀번호를 확인해주세요.");
                return;
            }

            if($('#password').val() != $('#password_confirm').val()){
                alert("비밀번호가 일치하지 않습니다.");
                return;
            }

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

            if($('#zip_code').val() == ''){
                alert("주소를 입력해주세요.");

                return;
            }


            if(!$('#agreeCheckbox').is(":checked")){
                alert("개인정보 수집 이용을 동의해주세요.");
                return;
            }

            if(!$('#useTermsCheckbox').is(":checked")){
                alert("MallForMan 이용약관을 동의해주세요.");
                return;
            }

            let email = ($('#email').val()).split("@");

            if(confirm("회원가입을 완료하시겠습니까?")){
                // ajax로 registerComplete.php에 데이터 보내기
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/registerComplete.php',
                    data: {
                        login_id: $('#login_id').val()
                        , password: $('#password').val()
                        , name: $('#name').val()
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

        // 전체약관 동의 체크박스 클릭 시 모든 체크박스 체크
        $('#checkAll').on("click", function(){
            // 체크되어있다면
            if($(this).is(":checked")){
                $('.agree-item').prop("checked",true);
            } else{ //아니라면
                $('.agree-item').prop("checked",false);
            }
        });

        //개인정보 수집 이용동의(필수)
        function privacyAgreeUsagePop(){
            window.open("/mall/php/terms/privacyAgreeUsagePop.html","개인정보 수집 이용동의약관","width=500px;,height=700px;");
        }

        //MallForMal이용약관(필수)
        function serviceAgreementPop(){
            window.open("/mall/php/terms/serviceAgreementPop.html","MallForMal이용약관","width=500px;,height=700px;");
        }

        //마케팅 활용 및 광고성 정보 수신 동의(선택)
        function marketingAgreementPop(){
            window.open("/mall/php/terms/marketingAgreementPop.html","마케팅 활용 및 광고성 정보 수신 동의(","width=500px;,height=700px;");
        }

    </script>
</body>
</html>