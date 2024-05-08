<?php
// MySQL 데이터베이스 연결 정보
$servername = "database-1.cvu4uqwmyddr.ap-northeast-2.rds.amazonaws.com";
$username = "admin";
$password = "Amazon1!";
$dbname = "user";

// MySQL 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// POST 요청에서 사용자가 제출한 id와 password 가져오기
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $password = $_POST['password'];

    // 입력된 id와 password를 사용하여 데이터베이스에서 사용자 정보를 가져오기
    $sql = "SELECT * FROM users WHERE id='$id' AND password='$password'";
    $result = $conn->query($sql);

    // 사용자가 존재하는지 확인
    if ($result->num_rows > 0) {
        // 로그인에 성공하면 PHP 세션 설정
        session_start();
        $_SESSION['id'] = $id;

        // enroll.html 페이지로 리디렉션
        header("Location: enroll.php");
        exit();
    } else {
        echo "아이디 또는 비밀번호가 올바르지 않습니다.";
    }
}

// MySQL 연결 닫기
$conn->close();
?>

