<?php
// 상품등록페이지

session_start();
$login_id = $_SESSION['LOGIN_ID'];

// 로그인 되어있지 않다면 메인화면으로 이동
if($login_id == null || $login_id == ''){
    echo "<script> document.location.href='index.php'</script>";
}

// 카테고리 정보를 가지고 있는 클래스
class Second_Category {
    // 프로퍼티(멤버 변수)
    private String $menuId;
    private String $menuName;
    private String $parentId;
    // 메소드
    public function getMenuId(): string
    {
        return $this-> menuId;
    }

    public function getMenuName(): string
    {
        return $this-> menuName;
    }

    public function getParentId(): string
    {
        return $this-> parentId;
    }

    public function setMenuId($menuId) {
        $this->menuId = $menuId;
    }

    public function setMenuName($menuName) {
        $this->menuName = $menuName;
    }

    public function setMenuParentId($parentId) {
        $this->parentId = $parentId;
    }
}

// DB연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
// 첫번째 카테고리 가져오기
$sqlFirstCat = "SELECT MENU_ID
                     , MENU_NAME
                FROM MENU
                WHERE DEPTH = 2
            ";
$resultFirstCat = mysqli_query($conn, $sqlFirstCat);
$countFirstCat = mysqli_num_rows($resultFirstCat);

// 두번째 카테고리 가져오기(선택한 상위 메뉴에 해당하는 하위메뉴만 출력되도록!)
$sqlSecondCat = "SELECT MENU_ID
                     , MENU_NAME
                     , MENU_PARENT_ID
                FROM MENU
                WHERE DEPTH = 3
                AND USE_YN = 'Y'
            ";
$resultSecondCat = mysqli_query($conn, $sqlSecondCat);
$countSecondCat = mysqli_num_rows($resultSecondCat);
$secondCatArr = array();
$menuIdArr = array();
$menuNameArr = array();
$menuParentIdArr = array();

while($rowSecondCat= mysqli_fetch_array($resultSecondCat)){
    /*$temp = new Second_Category();
    $temp->setMenuId($rowSecondCat['MENU_ID']);
    $temp->setMenuName($rowSecondCat['MENU_NAME']);
    $temp->setMenuParentId($rowSecondCat['MENU_PARENT_ID']);*/
    array_push($menuIdArr, $rowSecondCat['MENU_ID']);
    array_push($menuNameArr, $rowSecondCat['MENU_NAME']);
    array_push($menuParentIdArr, $rowSecondCat['MENU_PARENT_ID']);
}
array_push($secondCatArr, $menuIdArr, $menuNameArr, $menuParentIdArr);

// 제조자가져오기
$sqlManufacture = "SELECT DISTINCT MANUFACTURER
                   FROM PRODUCT 
                   WHERE MANUFACTURER != ''
            ";
$resultManufacture = mysqli_query($conn, $sqlManufacture);
$countManufacture = mysqli_num_rows($resultManufacture);

// 제조국 가져오기
$sqlCountryOfManufacture = "SELECT DISTINCT COUNTRY_OF_MANUFACTURER
                            FROM PRODUCT 
                            WHERE COUNTRY_OF_MANUFACTURER != ''
            ";
$resultCountryOfManufacture = mysqli_query($conn, $sqlCountryOfManufacture);
$countCountryOfManufacture = mysqli_num_rows($resultCountryOfManufacture);

// 색상 가져오기
$sqlColor = "SELECT DISTINCT COLOR 
                            FROM PRODUCT_OPTION 
                            WHERE COLOR != ''
            ";
$resultColor = mysqli_query($conn, $sqlColor);
$countColor = mysqli_num_rows($resultColor);

// 사이즈 가져오기
$sqlSize = "SELECT DISTINCT SIZE  
                            FROM PRODUCT_OPTION 
                            WHERE SIZE  != ''
            ";
$resultSize = mysqli_query($conn, $sqlSize);
$countSize = mysqli_num_rows($resultSize);
?>

<!DOCTYPE html>
<html>
<?php
include 'head.php'
?>
<style>
    .item_title {
        padding: 0px;
        margin: 0;
        width: 10%;
        text-align: center;
        font-weight: bold;
        background-color: #4FBFA8;
        color: #FFFFFF;
    }

    .item_title_inner{
        padding: 0px;
        margin: 0;
        width: 10%;
        text-align: center;
        font-weight: bold;
        background-color: #61b977;
        color: #FFFFFF;
        border: 1px solid white;
    }
    .upper{
        border-top: 1px solid black;
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
                    <div class="col-lg-12">
                        <!-- breadcrumb-->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">관리자</a></li>
                                <li class="breadcrumb-item"><a href="#">상품</a></li>
                                <li aria-current="page" class="breadcrumb-item active">상품 등록</li>
                            </ol>
                        </nav>
                    </div>
                    <div id="board" class="col-lg-12">
                        <div class="box">
                            <!-- Contact Section Begin -->
                            <section class="contact spad">
                                <form method="POST" action="/mall/php/product/writeProductCompl.php" enctype="multipart/form-data">
                                <div class="container">
                                    <table class="table">
                                        <tr>
                                            <td height=20 align=center bgcolor=#ccc style="size: 20px;">상품 등록</td>
                                        </tr>
                                        <tr>
                                            <td bgcolor=white>
                                                <table class="table">
                                                    <tr >
                                                        <td class="item_title">상품명</td>
                                                        <td colspan="5">
                                                            <input class="form-control " type="text" name="product_name" id="product_name">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="item_title">상품 1차분류</td>
                                                        <td>
                                                            <select class="form-control"  name="first_category" id="first_category">
                                                                <option value="">[1차분류]</option>
                                                                <?for($j = 0; $j < $countFirstCat; $j++){
                                                                    $rowFirstCat = mysqli_fetch_array($resultFirstCat);
                                                                    ?>
                                                                    <option value="<?echo $rowFirstCat['MENU_ID']?>"><?echo $rowFirstCat['MENU_NAME']?></option>
                                                                <?}?>
                                                            </select>
                                                        </td>
                                                        <td class="item_title">상품 2차분류</td>
                                                        <td>
                                                            <select class="form-control"  name="second_category" id="second_category">
                                                                <option value="">[2차분류]</option>
                                                            </select>
                                                        </td>
                                                        <td class="item_title">제조자</td>
                                                        <td>
                                                            <select class="form-control"  name="product_manufacture" id="product_manufacture">
                                                                <option value="">[선택]</option>
                                                                <?for($j = 0; $j < $countManufacture; $j++){
                                                                    $rowManufacture = mysqli_fetch_array($resultManufacture);
                                                                    ?>
                                                                    <option value="<?echo $rowManufacture['MANUFACTURER']?>"><?echo $rowManufacture['MANUFACTURER']?></option>
                                                                <?}?>
                                                                <option id="product_manufacture_etc" value="etc">직접입력</option>
                                                            </select>
                                                            <input class="form-control" id="product_manufacture_input" name="product_manufacture_input" placeholder="제조자를 입력하세요.">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="item_title">기존가격</td>
                                                        <td>
                                                            <input  class="form-control" type="text" name="product_price" id="product_price">
                                                        </td>
                                                        <td class="item_title">할인가격</td>
                                                        <td>
                                                            <input style="float: left" class="form-control" type="text" name="product_price_sale" id="product_price_sale">
                                                        </td>
                                                        <td class="item_title">제조국</td>
                                                        <td>
                                                            <select class="form-control"  name="country_of_manufacture" id="country_of_manufacture">
                                                                <option value="">[선택]</option>
                                                                <?for($j = 0; $j < $countCountryOfManufacture; $j++){
                                                                    $rowCountryOfManufacture = mysqli_fetch_array($resultCountryOfManufacture);
                                                                    ?>
                                                                    <option value="<?echo $rowCountryOfManufacture['COUNTRY_OF_MANUFACTURER']?>"><?echo $rowCountryOfManufacture['COUNTRY_OF_MANUFACTURER']?></option>
                                                                <?}?>
                                                                <option id="country_of_manufacture_etc" value="etc">직접입력</option>
                                                            </select>
                                                            <input class="form-control" id="country_of_manufacture_input" name="country_of_manufacture_input" placeholder="제조국를 입력하세요.">
                                                        </td>
                                                    </tr>
                                                    <tr >
                                                        <td class="item_title">상품소재</td>
                                                        <td colspan="5">
                                                            <input class="form-control " type="text" name="product_material" id="product_material">
                                                        </td>
                                                    </tr>
                                                    <tr id="tr_add">
                                                        <td class="item_title">세탁방법 및 취급 시 주의사항</td>
                                                        <td colspan="5">
                                                            <textarea class="form-control " name="cleaning_method" id="cleaning_method" rows="5"></textarea>
                                                        </td>
                                                    </tr>
                                                    <!-- 카테고리에 따라 넣어야 할 값 달라지고 그 부분은 여기에 입력하기-->
                                                    <tr>
                                                        <td class="item_title" colspan="6">상품 수량정보(색상, 사이즈, 수량)<span>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="product_info_add" class="btn btn-warning">상품정보 추가</button>&nbsp;<button type="button" id="product_info_delete" class="btn btn-dark">상품정보 삭제</button></span></td>
                                                    </tr>
                                                    <tr id="tr_product_info_0">
                                                        <td class="item_title">색상</td>
                                                        <td>
                                                            <select class="form-control"  name="product_color[]" id="product_color">
                                                                <option value="">[선택]</option>
                                                                <option value="화이트">화이트</option>
                                                                <option value="레드">레드</option>
                                                                <option value="블랙">블랙</option>
                                                                <option value="오렌지">오렌지</option>
                                                                <option value="블루">블루</option>
                                                                <option value="옐로우">옐로우</option>
                                                                <option value="그린">그린</option>
                                                                <option value="네이비">네이비</option>
                                                                <option value="그레이">그레이</option>
                                                                <option value="베이지">베이지</option>
                                                                <option value="카키">카키</option>
                                                                <option value="브라운">브라운</option>
                                                            </select>
                                                        </td>
                                                        <td class="item_title">사이즈</td>
                                                        <td>
                                                            <select class="form-control"  name="product_size[]" id="product_size">'
                                                                <option value="">[선택]</option>
                                                                <option value="S">S</option>
                                                                <option value="M">M</option>
                                                                <option value="L">L</option>
                                                                <option value="XL">XL</option>
                                                                <option value="2XL">2XL</option>
                                                                <option value="3XL">3XL</option>
                                                                <option value="4XL">4XL</option>
                                                                <option value="FREE">FREE</option>
                                                            </select>
                                                        </td>
                                                        <td class="item_title">수량</td>
                                                        <td>
                                                            <input class="form-control " type="number" name="product_number[]" id="product_number">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="item_title" colspan="6">제품이미지<!--<span>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="img_add" class="btn btn-dark">상세이미지 추가</button></span>--></td>
                                                    </tr>
                                                    <tr id="tr_img">
                                                        <td class="item_title">대표 이미지</td>
                                                        <td colspan="5">
                                                            <input type="file" class="form-control" name="file_represent" id="file_represent">
                                                            <div style="margin-top: 10px;">
                                                                <img id="img_represent" width="200px;">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="item_title">상세 이미지</td>
                                                        <td colspan="5">
                                                            <input type="file" class="form-control" name="file_detail[]" id="file_detail" multiple>
                                                            <div id="img_detail" style="margin-top: 10px;"></div>
                                                        </td>
                                                    </tr>
                                                    <tr id="model_info">
                                                        <td class="tr_model_info item_title" colspan="6">모델 정보<span>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="model_info_add" class="btn btn-warning">모델정보 추가</button>&nbsp;<button type="button" id="model_info_delete" class="btn btn-dark">모델정보 삭제</button></span></td>
                                                    </tr>
                                                    <tr class="tr_model_info" id="tr_model_info_0">
                                                        <td class="item_title">키(cm)</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="model_height[]">
                                                        </td>
                                                        <td class="item_title">몸무게(kg)</td>
                                                        <td>
                                                            <input type="text" class="form-control" name="model_weight[]">
                                                        </td>
                                                        <td class="item_title">착용사이즈</td>
                                                        <td>
                                                            <select class="form-control"  name="model_size[]" id="model_size">'
                                                                <option value="">[선택]</option>
                                                                <option value="S">S</option>
                                                                <option value="M">M</option>
                                                                <option value="L">L</option>
                                                                <option value="XL">XL</option>
                                                                <option value="2XL">2XL</option>
                                                                <option value="3XL">3XL</option>
                                                                <option value="4XL">4XL</option>
                                                                <option value="FREE">FREE</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr id="detail_info">
                                                        <td class="item_title">상세정보</td>
                                                        <td colspan="5">
                                                            <textarea name="contents" id="contents" class="nse_content" style="width: 100%; height: 400px;"></textarea>
                                                            <script type="text/javascript">
                                                                var oEditors = [];
                                                                nhn.husky.EZCreator.createInIFrame({
                                                                    oAppRef: oEditors,
                                                                    elPlaceHolder: "contents",
                                                                    sSkinURI: "smart_editor2/SmartEditor2Skin.html",
                                                                    fCreator: "createSEditor2"
                                                                });
                                                                function submitContents(elClickedObj) {
                                                                    // 에디터의 내용이 textarea에 적용됩니다.
                                                                    oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);
                                                                    // 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("contents").value를 이용해서 처리하면 됩니다.
                                                                    try {
                                                                        elClickedObj.form.submit();
                                                                    } catch(e) {}
                                                                }
                                                            </script>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <div align="right">
                                        <!--<button id="btn_write" class="btn btn-primary navbar-btn">작성하기</button>-->
                                        <button id="" type="submit" onclick="submitContents(this)" class="btn btn-primary navbar-btn">상품등록</button>
                                    </div>
                                </form>
                            </section>
                            <!-- Contact Section End -->
                        <!-- /.box-->
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
            $(function (){
                $('#product_manufacture_input').hide();
                $('#country_of_manufacture_input').hide();
            });

            // 첫번째 카테고리 선택 시 두번째 카테고리 내용 변경
            $('#first_category').change(function(){
                // 두번째 카테고리 배열의 크기를 받아온다.
                let secondCatArray = <?echo json_encode($secondCatArr)?>;

                let type = 0;
                if($(this).val() == '2'){ // 상의
                    type = 2;
                } else if($(this).val() == '3'){ //아우터
                    type = 3;
                } else if($(this).val() == '4'){ //하의
                    type = 4;
                } else if($(this).val() == '26'){ //신발
                    type = 26;
                } else if($(this).val() == '27'){ //모자
                    type = 27;
                }

                // 두번째 카테고리 박스를 비우기
                $('#second_category').empty();
                $('#second_category').append('<option value="">[2차분류]</option>');


                for(let i = 0; i < secondCatArray[0].length; i++){
                    if(type == secondCatArray[2][i]){
                        let option = "<option value=" + secondCatArray[0][i] + ">"+ secondCatArray[1][i] +"</option>";
                        //console.log(option);
                        $('#second_category').append(option);
                    }
                }
            });

            //제조자 기타 선택 시 직접입력
            $('#product_manufacture').change(function (){
                if($('#product_manufacture').val() == "etc"){
                    $('#product_manufacture_input').show();
                } else{
                    $('#product_manufacture_input').hide();
                    $('#product_manufacture_input').val('');
                }
            });

            //제조국 기타 선택 시 직접입력
            $('#country_of_manufacture').change(function (){
                if($('#country_of_manufacture').val() == "etc"){
                    $('#country_of_manufacture_input').show();
                } else{
                    $('#country_of_manufacture_input').hide();
                    $('#country_of_manufacture_input').val('');
                }
            });

            let productInfoCnt = 1;

            // 상품정보(색상, 사이즈, 수량 추가) 추가 버튼 클릭
            $('#product_info_add').on("click", function(){
                // 동적생성 - 색상, 사이즈 셀렉터 사용
                let trTop = '<tr id="tr_product_info_'+productInfoCnt+'">'
                        + '<td class="item_title">색상</td>'
                        + '<td>'
                            + '<select class="form-control"  name="product_color[]" id="product_color">'
                                + '<option value="">[선택]</option>'
                                + '<option value="화이트">화이트</option>'
                                + '<option value="레드">레드</option>'
                                + '<option value="블랙">블랙</option>'
                                + '<option value="오렌지">오렌지</option>'
                                + '<option value="블루">블루</option>'
                                + '<option value="옐로우">옐로우</option>'
                                + '<option value="그린">그린</option>'
                                + '<option value="네이비">네이비</option>'
                                + '<option value="그레이">그레이</option>'
                                + '<option value="베이지">베이지</option>'
                                + '<option value="베이지">카키</option>'
                                + '<option value="브라운">브라운</option>'
                            + '</select>'
                        + '</td>';
                let trBody= null;

                if($('#first_category').val() != '26'){
                    trBody = '<td class="item_title">사이즈</td>'
                    + '<td>'
                    + '<select class="form-control"  name="product_size[]" id="product_size">'
                    + '<option value="">[선택]</option>'
                    + '<option value="S">S</option>'
                    + '<option value="M">M</option>'
                    + '<option value="L">L</option>'
                    + '<option value="XL">XL</option>'
                    + '<option value="2XL">2XL</option>'
                    + '<option value="3XL">3XL</option>'
                    + '<option value="4XL">4XL</option>'
                    + '<option value="FREE">FREE</option>'
                    + '</select>'
                    + '</td>';
                } else{
                    trBody = '<td class="item_title">사이즈</td>'
                    + '<td>'
                    + '<select class="form-control"  name="product_size[]" id="product_size">'
                    +"<option value=''>[선택]</option>"
                    + "<option value='220'>220</option>"
                    + "<option value='225'>225</option>"
                    + "<option value='230'>230</option>"
                    + "<option value='235'>235</option>"
                    + "<option value='240'>240</option>"
                    + "<option value='245'>245</option>"
                    + "<option value='250'>250</option>"
                    + "<option value='255'>255</option>"
                    + "<option value='260'>260</option>"
                    + "<option value='265'>265</option>"
                    + "<option value='270'>270</option>"
                    + "<option value='275'>275</option>"
                    + "<option value='280'>280</option>"
                    + "<option value='285'>285</option>"
                    + "<option value='290'>290</option>"
                    + "<option value='295'>295</option>"
                    + "<option value='300'>300</option>"
                    + '</select>'
                    + '</td>';
                }

                let trBottom =
                  '<td class="item_title">수량</td>'
                + '<td>'
                    + '<input class="form-control " type="number" name="product_number[]" id="product_number">'
                + '</td>'
                + '</tr>';

                let tr = trTop + trBody + trBottom;


               $('#tr_product_info_'+(productInfoCnt-1)).after(tr);
                productInfoCnt++;
            });

            //상세이미지 추가
            /*$('#img_add').on("click", function(){
                let tr =  '<tr>'
                            + '<td class="item_title">상세 이미지</td>'
                            + '<td colspan="5">'
                            + '<input type="file" class="form-control" name="file_detail[]">'
                            + '</td>'
                        + '</tr>'
                $('#tr_img').after(tr);
            });*/

            // 2차 카테고리 선택 시 1차카테고리에 따라 치수정보 추가하기
            $('#second_category').change(function(){
                if($(this).val() != ''){
                   let tr = null;
                   $('.added_info').remove();
                   // 상의
                    if($('#first_category').val() == '26'){
                        $('#product_size option').remove();
                        option = "<option value=''>[선택]</option>"
                            + "<option value='220'>220</option>"
                            + "<option value='225'>225</option>"
                            + "<option value='230'>230</option>"
                            + "<option value='235'>235</option>"
                            + "<option value='240'>240</option>"
                            + "<option value='245'>245</option>"
                            + "<option value='250'>250</option>"
                            + "<option value='255'>255</option>"
                            + "<option value='260'>260</option>"
                            + "<option value='265'>265</option>"
                            + "<option value='270'>270</option>"
                            + "<option value='275'>275</option>"
                            + "<option value='280'>280</option>"
                            + "<option value='285'>285</option>"
                            + "<option value='290'>290</option>"
                            + "<option value='295'>295</option>"
                            + "<option value='300'>300</option>";
                        $("#product_size").append(option);

                        $('#model_size option').remove();
                        option = "<option value=''>[선택]</option>"
                            + "<option value='220'>220</option>"
                            + "<option value='225'>225</option>"
                            + "<option value='230'>230</option>"
                            + "<option value='235'>235</option>"
                            + "<option value='240'>240</option>"
                            + "<option value='245'>245</option>"
                            + "<option value='250'>250</option>"
                            + "<option value='255'>255</option>"
                            + "<option value='260'>260</option>"
                            + "<option value='265'>265</option>"
                            + "<option value='270'>270</option>"
                            + "<option value='275'>275</option>"
                            + "<option value='280'>280</option>"
                            + "<option value='285'>285</option>"
                            + "<option value='290'>290</option>"
                            + "<option value='295'>295</option>"
                            + "<option value='300'>300</option>";
                        $("#model_size").append(option);

                    } else{
                        $('#product_size option').remove();
                        option = "<option value=''>[선택]</option>"
                                + '<option value="S">S</option>'
                                + '<option value="M">M</option>'
                                + '<option value="L">L</option>'
                                + '<option value="XL">XL</option>'
                                + '<option value="2XL">2XL</option>'
                                + '<option value="3XL">3XL</option>'
                                + '<option value="4XL">4XL</option>';
                                + '<option value="FREE">FREE</option>';
                        $("#product_size").append(option);

                        $('#model_size option').remove();
                        option = "<option value=''>[선택]</option>"
                            + '<option value="S">S</option>'
                            + '<option value="M">M</option>'
                            + '<option value="L">L</option>'
                            + '<option value="XL">XL</option>'
                            + '<option value="2XL">2XL</option>'
                            + '<option value="3XL">3XL</option>'
                            + '<option value="4XL">4XL</option>';
                            + '<option value="FREE">FREE</option>';
                        $("#model_size").append(option);

                        if($('#first_category').val() == '2'){ //상의
                            tr =  '<tr>'
                                + '<td class="item_title added_info">치수정보</td>'
                                + '<td colspan="5" class="added_info">'
                                + '<table class="table" style="border: 1px solid black">'
                                + '<tr >'
                                + '<td colspan="6" class="item_title_inner upper" style="border-left: 1px solid black;border-right: 1px solid black; border-top: 1px solid black;">치수정보<span>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="size_info_add" class="btn btn-warning">치수정보 추가</button>&nbsp;<button type="button" id="size_info_delete" class="btn btn-dark">치수정보 삭제</button></span></td>'
                                + '</tr>'
                                + '<tr >'
                                + '<td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">사이즈</td>'
                                + '<td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">어깨길이(cm)</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">가슴둘레(cm)</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">암홀(cm)</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">팔길이(cm)</td>'
                                + '<td class="item_title_inner upper" style="border-right: 1px solid black; border-top: 1px solid black;">총길이(cm)</td>'
                                + '</tr>'
                                + '<tr id="tr_size_info_0">'
                                + '<td>'
                                + '<select class="form-control"  name="size[]" id="size">'
                                + '<option value="">[선택]</option>'
                                + '<option value="S">S</option>'
                                + '<option value="M">M</option>'
                                + '<option value="L">L</option>'
                                + '<option value="XL">XL</option>'
                                + '<option value="2XL">2XL</option>'
                                + '<option value="3XL">3XL</option>'
                                + '<option value="4XL">4XL</option>'
                                + '<option value="FREE">FREE</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="TOP_SHOULDER_SIZE[]" id="TOP_SHOULDER_SIZE">'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="TOP_CHEST_SIZE[]" id="TOP_CHEST_SIZE">'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="TOP_ARMHOLE_SIZE[]" id="TOP_ARMHOLE_SIZE">'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="TOP_ARM_SIZE[]" id="TOP_ARM_SIZE">'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="TOP_TOTAL_LENGTH[]" id="TOP_TOTAL_LENGTH">'
                                + '</td>'
                                + '</tr>'
                                + '<tr >'
                                + '<td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">핏</td>'
                                + '<td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">두깨감</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">신축성</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">비침</td>'
                                + '<td colspan="2" class="item_title_inner upper" style="border-right: 1px solid black; border-top: 1px solid black;">계절</td>'
                                + '</tr>'
                                + '<tr>'
                                + '<td>'
                                + '<select class="form-control"  name="fit" id="fit">'
                                + '<option value="">[선택]</option>'
                                + '<option value="스탠다드">스탠다드</option>'
                                + '<option value="세미오버">세미오버</option>'
                                + '<option value="오버">오버</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<select class="form-control"  name="thickness" id="thickness">'
                                + '<option value="">[선택]</option>'
                                + '<option value="두꺼움">두꺼움</option>'
                                + '<option value="보통">보통</option>'
                                + '<option value="얇음">얇음</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<select class="form-control"  name="elasticity" id="elasticity">'
                                + '<option value="">[선택]</option>'
                                + '<option value="좋음">좋음</option>'
                                + '<option value="약간">약간</option>'
                                + '<option value="없음">없음</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<select class="form-control"  name="reflection" id="reflection">'
                                + '<option value="">[선택]</option>'
                                + '<option value="비침">비침</option>'
                                + '<option value="약간">약간</option>'
                                + '<option value="없음">없음</option>'
                                + '</select>'
                                + '</td>'
                                + '<td colspan="2">'
                                + '<select class="form-control"  name="season" id="season">'
                                + '<option value="">[선택]</option>'
                                + '<option value="봄/가을">봄/가을</option>'
                                + '<option value="여름">여름</option>'
                                + '<option value="겨울">겨울</option>'
                                + '<option value="사계절">사계절</option>'
                                + '</select>'
                                + '</td>'
                                + '</tr>'
                                + '</table>'
                                + '</td>'
                                + '</tr>';
                        } else if($('#first_category').val() == '3'){ //아우터
                            tr =  '<tr>'
                                + '<td class="item_title added_info">치수정보</td>'
                                + '<td colspan="5" class="added_info">'
                                + '<table class="table" style="border: 1px solid black">'
                                + '<tr >'
                                + '<td colspan="6" class="item_title_inner upper" style="border-left: 1px solid black;border-right: 1px solid black; border-top: 1px solid black;">치수정보<span>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="size_info_add" class="btn btn-warning">치수정보 추가</button>&nbsp;<button type="button" id="size_info_delete" class="btn btn-dark">치수정보 삭제</button></span></td>'
                                + '</tr>'
                                + '<tr >'
                                + '<td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">사이즈</td>'
                                + '<td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">총장(cm)</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">어깨너비(cm)</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">가슴단면(cm)</td>'
                                + '<td colspan="2" class="item_title_inner upper" style="border-top: 1px solid black;">소매길이(cm)</td>'
                                + '</tr>'
                                + '<tr id="tr_size_info_0">'
                                + '<td>'
                                + '<select class="form-control"  name="size[]" id="size">'
                                + '<option value="">[선택]</option>'
                                + '<option value="S">S</option>'
                                + '<option value="M">M</option>'
                                + '<option value="L">L</option>'
                                + '<option value="XL">XL</option>'
                                + '<option value="2XL">2XL</option>'
                                + '<option value="3XL">3XL</option>'
                                + '<option value="4XL">4XL</option>'
                                + '<option value="FREE">FREE</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="OUTER_TOTAL_LENGTH[]" id="OUTER_TOTAL_LENGTH ">'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="OUTER_SHOULDER_SIZE[]" id="OUTER_SHOULDER_SIZE ">'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="OUTER__CHEST_SIZE[]" id="OUTER__CHEST_SIZE ">'
                                + '</td>'
                                + '<td colspan="2">'
                                + '<input class="form-control " type="text" name="OUTER_SLEEVE_LENGTH[]" id="OUTER_SLEEVE_LENGTH ">'
                                + '</td>'
                                + '</tr>'
                                + '<tr >'
                                + '<td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">핏</td>'
                                + '<td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">두깨감</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">신축성</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">비침</td>'
                                + '<td colspan="2" class="item_title_inner upper" style="border-right: 1px solid black; border-top: 1px solid black;">계절</td>'
                                + '</tr>'
                                + '<tr>'
                                + '<td>'
                                + '<select class="form-control"  name="fit" id="fit">'
                                + '<option value="">[선택]</option>'
                                + '<option value="스탠다드">스탠다드</option>'
                                + '<option value="세미오버">세미오버</option>'
                                + '<option value="오버">오버</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<select class="form-control"  name="thickness" id="thickness">'
                                + '<option value="">[선택]</option>'
                                + '<option value="두꺼움">두꺼움</option>'
                                + '<option value="보통">보통</option>'
                                + '<option value="얇음">얇음</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<select class="form-control"  name="elasticity" id="elasticity">'
                                + '<option value="">[선택]</option>'
                                + '<option value="좋음">좋음</option>'
                                + '<option value="약간">약간</option>'
                                + '<option value="없음">없음</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<select class="form-control"  name="reflection" id="reflection">'
                                + '<option value="">[선택]</option>'
                                + '<option value="비침">비침</option>'
                                + '<option value="약간">약간</option>'
                                + '<option value="없음">없음</option>'
                                + '</select>'
                                + '</td>'
                                + '<td colspan="2">'
                                + '<select class="form-control"  name="season" id="season">'
                                + '<option value="">[선택]</option>'
                                + '<option value="봄/가을">봄/가을</option>'
                                + '<option value="여름">여름</option>'
                                + '<option value="겨울">겨울</option>'
                                + '<option value="사계절">사계절</option>'
                                + '</select>'
                                + '</td>'
                                + '</tr>'
                                + '</table>'
                                + '</td>'
                                + '</tr>';
                        } else if($('#first_category').val() == '4'){ //하의
                            tr =  '<tr>'
                                + '<td class="item_title added_info">치수정보</td>'
                                + '<td colspan="5" class="added_info">'
                                + '<table class="table" style="border: 1px solid black">'
                                + '<tr >'
                                + '<td colspan="6" class="item_title_inner upper" style="border-left: 1px solid black;border-right: 1px solid black; border-top: 1px solid black;">치수정보<span>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="size_info_add" class="btn btn-warning">치수정보 추가</button>&nbsp;<button type="button" id="size_info_delete" class="btn btn-dark">치수정보 삭제</button></span></td>'
                                + '</tr>'
                                + '<tr >'
                                + '<td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">사이즈</td>'
                                + '<td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">허리단면(cm)</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">총기장(cm)</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">허벅지단면(cm)</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">밑단단면(cm)</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">밑위(cm)</td>'
                                + '</tr>'
                                + '<tr id="tr_size_info_0">'
                                + '<td>'
                                + '<select class="form-control"  name="size[]" id="size">'
                                + '<option value="">[선택]</option>'
                                + '<option value="S">S</option>'
                                + '<option value="M">M</option>'
                                + '<option value="L">L</option>'
                                + '<option value="XL">XL</option>'
                                + '<option value="2XL">2XL</option>'
                                + '<option value="3XL">3XL</option>'
                                + '<option value="4XL">4XL</option>'
                                + '<option value="FREE">FREE</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="BOTTOM_WAIST_SIZE[]" id="BOTTOM_WAIST_SIZE  ">'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="BOTTOM_TOTAL_LENGTH[]" id="BOTTOM_TOTAL_LENGTH  ">'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="BOTTOM_THIGH_SIZE[]" id="BOTTOM_THIGH_SIZE  ">'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="BOTTOM_HEM_SIZE[]" id="BOTTOM_HEM_SIZE  ">'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="BOTTOM_RISE[]" id="BOTTOM_RISE  ">'
                                + '</td>'
                                + '</tr>'
                                + '<tr >'
                                + '<td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">핏</td>'
                                + '<td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">두깨감</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">신축성</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">비침</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">촉감</td>'
                                + '<td class="item_title_inner upper" style="border-right: 1px solid black; border-top: 1px solid black;">계절</td>'
                                + '</tr>'
                                + '<tr>'
                                + '<td>'
                                + '<select class="form-control"  name="fit" id="fit">'
                                + '<option value="">[선택]</option>'
                                + '<option value="스탠다드">스탠다드</option>'
                                + '<option value="세미오버">세미오버</option>'
                                + '<option value="오버">오버</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<select class="form-control"  name="thickness" id="thickness">'
                                + '<option value="">[선택]</option>'
                                + '<option value="두꺼움">두꺼움</option>'
                                + '<option value="보통">보통</option>'
                                + '<option value="얇음">얇음</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<select class="form-control"  name="elasticity" id="elasticity">'
                                + '<option value="">[선택]</option>'
                                + '<option value="좋음">좋음</option>'
                                + '<option value="약간">약간</option>'
                                + '<option value="없음">없음</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<select class="form-control"  name="reflection" id="reflection">'
                                + '<option value="">[선택]</option>'
                                + '<option value="비침">비침</option>'
                                + '<option value="약간">약간</option>'
                                + '<option value="없음">없음</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<select class="form-control"  name="touch" id="touch">'
                                + '<option value="">[선택]</option>'
                                + '<option value="부드러움">부드러움</option>'
                                + '<option value="보통">보통</option>'
                                + '<option value="뻣뻣함">뻣뻣함</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<select class="form-control"  name="season" id="season">'
                                + '<option value="">[선택]</option>'
                                + '<option value="봄/가을">봄/가을</option>'
                                + '<option value="여름">여름</option>'
                                + '<option value="겨울">겨울</option>'
                                + '<option value="사계절">사계절</option>'
                                + '</select>'
                                + '</td>'
                                + '</tr>'
                                + '</table>'
                                + '</td>'
                                + '</tr>';

                        } else if($('#first_category').val() == '27'){ //모자
                            tr =  '<tr>'
                                + '<td class="item_title added_info">치수정보</td>'
                                + '<td colspan="5" class="added_info">'
                                + '<table class="table" style="border: 1px solid black">'
                                + '<tr >'
                                + '<td colspan="6" class="item_title_inner upper" style="border-left: 1px solid black;border-right: 1px solid black; border-top: 1px solid black;">치수정보<span>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="size_info_add" class="btn btn-warning">치수정보 추가</button>&nbsp;<button type="button" id="size_info_delete" class="btn btn-dark">치수정보 삭제</button></span></td>'
                                + '</tr>'
                                + '<tr >'
                                + '<td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">사이즈</td>'
                                + '<td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">둘레(cm)</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">챙길이(cm)</td>'
                                + '<td class="item_title_inner upper" style="border-top: 1px solid black;">높이(cm)</td>'
                                + '</tr>'
                                + '<tr id="tr_size_info_0">'
                                + '<td>'
                                + '<select class="form-control"  name="size[]" id="size">'
                                + '<option value="">[선택]</option>'
                                + '<option value="S">S</option>'
                                + '<option value="M">M</option>'
                                + '<option value="L">L</option>'
                                + '<option value="XL">XL</option>'
                                + '<option value="2XL">2XL</option>'
                                + '<option value="3XL">3XL</option>'
                                + '<option value="4XL">4XL</option>'
                                + '<option value="FREE">FREE</option>'
                                + '</select>'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="HAT_ROUND[]" id="HAT_ROUND  ">'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="HAT_LENGTH[]" id="HAT_LENGTH  ">'
                                + '</td>'
                                + '<td>'
                                + '<input class="form-control " type="text" name="HAT_HEIGHT[]" id="HAT_HEIGHT  ">'
                                + '</td>'
                                + '</tr>';
                                + '</table>';

                            $('.tr_model_info').remove();
                            $('#model_info').remove();
                        }
                        $('#detail_info').before(tr);

                    }
               }
            });

            // 모델정보 추가
            let modelCnt = 1;
            $('#model_info_add').on("click", function(){
                let trTop = '<tr class="tr_model_info" id="tr_model_info_'+modelCnt+'">'
                    + '<td class="item_title">키(cm)</td>'
                    + '<td>'
                        + '<input type="text" class="form-control" name="model_height[]">'
                    + '</td>'
                    + '<td class="item_title">몸무게(kg)</td>'
                    + '<td>'
                        + '<input type="text" class="form-control" name="model_weight[]">'
                    + '</td>'
                let trBody= null;
                if($('#first_category').val() != '26'){
                    trBody = '<td class="item_title">사이즈</td>'
                        + '<td>'
                        + '<select class="form-control"  name="model_size[]" id="model_size">'
                        + '<option value="">[선택]</option>'
                        + '<option value="S">S</option>'
                        + '<option value="M">M</option>'
                        + '<option value="L">L</option>'
                        + '<option value="XL">XL</option>'
                        + '<option value="2XL">2XL</option>'
                        + '<option value="3XL">3XL</option>'
                        + '<option value="4XL">4XL</option>'
                        + '<option value="FREE">FREE</option>'
                        + '</select>'
                        + '</td>';
                } else{
                    trBody = '<td class="item_title">사이즈</td>'
                        + '<td>'
                        + '<select class="form-control"  name="model_size[]" id="model_size">'
                        +"<option value=''>[선택]</option>"
                        + "<option value='220'>220</option>"
                        + "<option value='225'>225</option>"
                        + "<option value='230'>230</option>"
                        + "<option value='235'>235</option>"
                        + "<option value='240'>240</option>"
                        + "<option value='245'>245</option>"
                        + "<option value='250'>250</option>"
                        + "<option value='255'>255</option>"
                        + "<option value='260'>260</option>"
                        + "<option value='265'>265</option>"
                        + "<option value='270'>270</option>"
                        + "<option value='275'>275</option>"
                        + "<option value='280'>280</option>"
                        + "<option value='285'>285</option>"
                        + "<option value='290'>290</option>"
                        + "<option value='295'>295</option>"
                        + "<option value='300'>300</option>"
                        + '</select>'
                        + '</td>';
                }

                let tr = trTop + trBody;
                $('#tr_model_info_'+(modelCnt-1)).after(tr);
                modelCnt++;
            });

            //치수정보 추가
            let sizeCnt = 1;
            $(document).on("click", "#size_info_add", function(){
                let tr = null;
                if($('#first_category').val() == '2'){
                    //상의
                    tr = '<tr id="tr_size_info_'+sizeCnt+'">'
                        + '<td>'
                        + '<select class="form-control"  name="size[]" id="size">'
                        + '<option value="">[선택]</option>'
                        + '<option value="S">S</option>'
                        + '<option value="M">M</option>'
                        + '<option value="L">L</option>'
                        + '<option value="XL">XL</option>'
                        + '<option value="2XL">2XL</option>'
                        + '<option value="3XL">3XL</option>'
                        + '<option value="4XL">4XL</option>'
                        + '<option value="FREE">FREE</option>'
                        + '</select>'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="TOP_SHOULDER_SIZE[]" id="TOP_SHOULDER_SIZE">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="TOP_CHEST_SIZE[]" id="TOP_CHEST_SIZE">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="TOP_ARMHOLE_SIZE[]" id="TOP_ARMHOLE_SIZE">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="TOP_ARM_SIZE[]" id="TOP_ARM_SIZE">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="TOP_TOTAL_LENGTH[]" id="TOP_TOTAL_LENGTH">'
                        + '</td>'
                        + '</tr>';
                } else if($('#first_category').val() == '3'){
                    // 아우터
                    tr = '<tr id="tr_size_info_'+sizeCnt+'">'
                        + '<td>'
                        + '<select class="form-control"  name="size[]" id="size">'
                        + '<option value="">[선택]</option>'
                        + '<option value="S">S</option>'
                        + '<option value="M">M</option>'
                        + '<option value="L">L</option>'
                        + '<option value="XL">XL</option>'
                        + '<option value="2XL">2XL</option>'
                        + '<option value="3XL">3XL</option>'
                        + '<option value="4XL">4XL</option>'
                        + '<option value="FREE">FREE</option>'
                        + '</select>'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="OUTER_TOTAL_LENGTH[]" id="OUTER_TOTAL_LENGTH ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="OUTER_SHOULDER_SIZE[]" id="OUTER_SHOULDER_SIZE ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="OUTER__CHEST_SIZE[]" id="OUTER__CHEST_SIZE ">'
                        + '</td>'
                        + '<td colspan="2">'
                        + '<input class="form-control " type="text" name="OUTER_SLEEVE_LENGTH[]" id="OUTER_SLEEVE_LENGTH ">'
                        + '</td>'
                        + '</tr>';
                } else if($('#first_category').val() == '4'){
// 하의
                    tr = '<tr id="tr_size_info_'+sizeCnt+'">'
                        + '<td>'
                        + '<select class="form-control"  name="size[]" id="size">'
                        + '<option value="">[선택]</option>'
                        + '<option value="S">S</option>'
                        + '<option value="M">M</option>'
                        + '<option value="L">L</option>'
                        + '<option value="XL">XL</option>'
                        + '<option value="2XL">2XL</option>'
                        + '<option value="3XL">3XL</option>'
                        + '<option value="4XL">4XL</option>'
                        + '<option value="FREE">FREE</option>'
                        + '</select>'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="BOTTOM_WAIST_SIZE[]" id="BOTTOM_WAIST_SIZE  ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="BOTTOM_TOTAL_LENGTH[]" id="BOTTOM_TOTAL_LENGTH  ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="BOTTOM_THIGH_SIZE[]" id="BOTTOM_THIGH_SIZE  ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="BOTTOM_HEM_SIZE[]" id="BOTTOM_HEM_SIZE  ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="BOTTOM_RISE[]" id="BOTTOM_RISE  ">'
                        + '</td>'
                        + '</tr>';
                } else if($('#first_category').val() == '27'){
                    // 모자
                    tr = '<tr id="tr_size_info_'+sizeCnt+'">'
                        + '<td>'
                        + '<select class="form-control"  name="size[]" id="size">'
                        + '<option value="">[선택]</option>'
                        + '<option value="S">S</option>'
                        + '<option value="M">M</option>'
                        + '<option value="L">L</option>'
                        + '<option value="XL">XL</option>'
                        + '<option value="2XL">2XL</option>'
                        + '<option value="3XL">3XL</option>'
                        + '<option value="4XL">4XL</option>'
                        + '<option value="FREE">FREE</option>'
                        + '</select>'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="HAT_ROUND[]" id="HAT_ROUND  ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="HAT_LENGTH[]" id="HAT_LENGTH  ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="HAT_HEIGHT[]" id="HAT_HEIGHT  ">'
                        + '</td>'
                        + '</tr>'
                }

                $('#tr_size_info_'+(sizeCnt-1)).after(tr);
                sizeCnt++;
            });

            // 상품정보 삭제(마지막 행 삭제)
            $(document).on("click", "#product_info_delete", function(){
                let currentCnt = productInfoCnt-1;

                if(currentCnt != 0){
                    $('#tr_product_info_'+(productInfoCnt-1)).remove();
                    productInfoCnt--;
                }
            });

            // 모델정보 삭제(마지막 행 삭제)
            $(document).on("click", "#model_info_delete", function(){
                let currentCnt = modelCnt-1;
                if(currentCnt != 0){
                    $('#tr_model_info_'+(modelCnt-1)).remove();
                    modelCnt--;
                }
            });

            // 치수정보 삭제(마지막 행 삭제)
            $(document).on("click", "#size_info_delete", function(){
                let currentCnt = sizeCnt-1;
                if(currentCnt != 0){
                    $('#tr_size_info_'+(sizeCnt-1)).remove();
                    sizeCnt--;
                }
            });

            // 대표이미지 미리보기
            $('#file_represent').on("change", function(e){
                let files = e.target.files;
                let fileArr = Array.prototype.slice.call(files);

                fileArr.forEach(function(file){
                   if(!file.type.match("image.*")) {
                       alert("확장자는 이미지 확장자만 가능합니다.");
                       return;
                   }

                   let reader = new FileReader();

                   reader.onload = function(e) {
                       $('#img_represent').attr("src", e.target.result);
                   }
                   reader.readAsDataURL(file);

                });
            });

            // 싱세이미지 미리보기
            $('#file_detail').on("change", function(e){
                let files = e.target.files;
                let fileArr = Array.prototype.slice.call(files);
                let sel_files = [];

                $('.img_detail').remove();

                fileArr.forEach(function(file){
                    if(!file.type.match("image.*")) {
                        alert("확장자는 이미지 확장자만 가능합니다.");
                        return;
                    }

                    let reader = new FileReader();

                    reader.onload = function(e) {
                        $('#img_detail').append('<img class="img_detail" src=\"' + e.target.result + '\" width="200px;" style="float: left;"/>');
                    }

                    reader.readAsDataURL(file);

                });
            });
        </script>
</body>
</html>