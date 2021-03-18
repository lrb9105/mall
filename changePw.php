<?php
// 현재비밀번호 가져오기
session_start();
$login_id = $_SESSION['LOGIN_ID'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');


$sql  = "
       SELECT PASSWORD
       FROM USER
       WHERE LOGIN_ID = '$login_id'
       ";

// 회원정보 db에 입력
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html>
<?php
include 'head.php'
?>

<body>
<!-- navbar-->
<div id="all">
    <div id="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- breadcrumb-->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li aria-current="page" class="breadcrumb-item active">비밀번호 변경</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-12">
                    <div class="box">
                        <div id="register">
                            <div class="form-group">
                                <label class="register_form" for="login_id">현재 비밀번호</label>
                                <input id="current_pw" name="current_pw" type="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="register_form" for="name">새 비밀번호</label>
                                <input id="new_pw" name="new_pw" type="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="register_form" for="name">새 비밀번호 확인</label>
                                <input id="new_pw_confirm" name="new_pw_confirm" type="password" class="form-control">
                            </div>
                            <div class="text-center">
                                <button type="submit" id="btn_change_pw" class="btn btn-primary"><i class="fa fa-user-md"></i> 비밀번호 변경</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript files-->
<?php
include 'jsfile.php'
?>
    <script>
        $('#btn_change_pw').on("click",function () {
            if($('#current_pw').val() == ''){
                alert("현재 비밀번호를 입력해주세요.");
                return;
            }

            if($('#new_pw').val() == ''){
                alert("새 비밀번호를 입력해주세요.");
                return;
            }

            if($('#new_pw_confirm').val() == ''){
                alert("새 비밀번호를 확인해주세요.");
                return;
            }

            if($('#current_pw').val() != '<?=$row[0]?>'){
                alert("현재 비밀번호가 일치하지 않습니다.");
                return;
            }

            if($('#new_pw').val() != $('#new_pw_confirm').val()){
                alert("새 비밀번호가 일치하지 않습니다.");
                return;
            }

            if(confirm("비밀번호를 변경하시겠습니까?")){
                // ajax로 registerComplete.php에 데이터 보내기
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/changePwComplete.php',
                    data: {
                        new_pw: $('#new_pw').val()
                    },

                    success: function (json) {
                        if (json.result == 'ok') {
                            alert("비밀번호를 변경했습니다.");
                            window.close();
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
    </script>
</body>
</html>