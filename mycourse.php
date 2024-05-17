<?php
// MySQL 데이터베이스 연결 정보
$servername = "database-1.cvu4uqwmyddr.ap-northeast-2.rds.amazonaws.com";
$username = "admin";
$password = "Amazon1!";
$dbname = "user";

// MySQL 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 세션 시작
session_start();

// 비회원이 마이페이지 접속 시(추가)
if(!isset($_SESSION['uid']) || !isset($_SESSION['uid'])){
    echo "<script>alert('비회원입니다!');";
    echo "window.location.href=\"../index.html\";</script>";
}

// 사용자의 uid 가져오기
if(isset($_SESSION['uid'])){
    $user_id = $_SESSION['uid']; // 로그인한 사용자의 id

    // 사용자의 정보 가져오기
    $user_info_sql = "SELECT * FROM users WHERE uid='$user_id'";
    $user_info_result = $conn->query($user_info_sql);
    $user_info_row = $user_info_result->fetch_assoc();

    // 사용자가 수강중인 과목 정보 가져오기
    $enrollments_sql = "SELECT courses.cid, courses.cname FROM enrollments JOIN courses ON enrollments.course_id = courses.cid WHERE enrollments.user_id='$user_id'";
    $enrollments_result = $conn->query($enrollments_sql);

    // 수강중인 과목 수 계산
    $num_courses = $enrollments_result->num_rows;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./images/fav/192.png">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/mycourse.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" 
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>나의 수강 강의</title>
</head>
<script src="http://code.jquery.com/jquery-3.5.1.min.js"></script>
<body>
    <div id="wrap">
        <div id="wrap-center">
            <!--header-->    
            <div class="header">
                <div id="top">
                    <a id="h-logo" href="index.html"><img src="images/logo.png" alt="logo"></a>
                    <ul>
                        <li><a href="login.html">회원로그인  |</a></li>
                        <li><a href="instructorlogin.html">강사로그인</a></li>
                        <li><a href="register.html">회원가입</a></li>
                        <li><a href="mypage.php">마이페이지</a></li>
                    </ul>
                </div>
            </div>
                      
            <!--search--> 
            <div id="search">
                <form action="">
                    <a id="s-logo" href="index.html"><img src="images/fav/512.png" alt="search-logo" ></a>
                    <label for="search-input"></label>
                    <input id="search-input" type="text" placeholder="강의 검색">
                    <div id="search-rt">
                        <button id="search-btn">
                            <div></div>
                        </button>
                    </div>
                </form>
            </div>
            
            <main id="main">
                <h2>마이페이지</h2>
                <!--mypage_content-->
                <section class="side_menu">
                    <ul class="menu">
                        <li><a href="#">내 정보 변경</a></li>
                        <li><a href="mycourse.php">나의 수강 강의</a></li>
                    </ul>
                </section>

                <div class="content">
                <p>수강중인 과목</p>
                <ul>
                    <?php
                    // 사용자가 수강중인 각각의 과목에 대한 하이퍼링크 생성
                    while($row = $enrollments_result->fetch_assoc()) {
                            echo "<li><a href='course.php?cid=" . $row["cid"] . "'>" . $row["cname"] . "</a></li>";
                    }

                    ?>
                </ul>
                </div>
                <!-- enroll.php로 이동하는 링크 추가 -->
                <div id="enroll">
                    <p><a href="enroll.php">강의 신청하러 가기 👈</a></p>
                </div>

            </main>
            
            <!--footer-->
            <footer class="footer">
                <div id="footer-top">
                    <div id="menu_list">
                        <div id="list_title">클래스랩</div>
                        <a href="#" class="list_link">클래스랩 소개</a>
                        <a href="#" class="list_link">수강평 모아보기</a>
                        <a href="#" class="list_link">블로그</a>
                    </div>
                    <div id="menu_list">
                        <div id="list_title">패밀리 사이트</div>
                        <a href="#" class="list_link">추가 중</a>
                        <a href="#" class="list_link">추가 중</a>
                        <a href="#" class="list_link">추가 중</a>
                        <a href="#" class="list_link">추가 중</a>
                        <a href="#" class="list_link">추가 중</a>
                    </div>
                    <div id="menu_list">
                        <div id="list_title">서비스</div>
                        <a href="#" class="list_link">기업 교육</a>
                        <a href="#" class="list_link">멘토링</a>
                        <a href="#" class="list_link">제휴</a>
                    </div>
                    <div id="menu_list">
                        <div id="list_title">고객센터</div>
                        <a href="#" class="list_link">공지사항</a>
                        <a href="#" class="list_link">자주묻는 질문</a>
                        <a href="#" class="list_link">신고센터</a>
                        <a href="#" class="list_link">수료증 확인</a>
                        <a href="#" class="list_link">강의 요청</a>
                    </div>
                </div>

                <address id="footer-bottom">
                    <a id="f-logo" href="index.html"><img src="images/footer_logo.png" alt="f-logo"></a>
                    <div id="ad_info">
                        (주)클래스랩 | 대표자: ㅇㅇㅇ | 사업자번호: 000-00-00000 <a href="#">[사업자정보확인]</a><br>
                        통신판매업: 2024-대한민국B-0000 | 개인정보보호책임자: ㅇㅇㅇ | 이메일: info@classlab<br>
                        전화번호: 070-0000-0000 | 주소: 경기도 ㅇㅇ ㅇㅇ구 ㅇㅇ로123번길 10 2동 3층<br>
                        ©CLASSLAB. ALL RIGHTS RESERVED
                    </div>
                </address>
                <div class="icons">
                    <a href="" target="_blank">
                    <i class="fab fa-instagram"></i></a>
                    <a href="" target="_blank">
                    <i class="fab fa-facebook-square"></i></a>
                    <a href="" target="_blank">
                    <i class="fab fa-brands fa-square-x-twitter"></i>
                    <a href="" target="_blank">
                    <i class="fab fa-youtube-square"></i></a>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>

<?php
// MySQL 연결 닫기
$conn->close();
?>
