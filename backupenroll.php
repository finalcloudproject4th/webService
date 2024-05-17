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

// POST 요청에서 사용자가 선택한 과목의 고유 id 가져오기
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cid = $_POST['cid'];

    // 사용자가 선택한 과목의 남은 수강정원을 감소시키기
    $sql = "UPDATE courses SET capacity = capacity - 1 WHERE cid='$cid' AND capacity > 0";
    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "fail";
    }
}

// MySQL 연결 닫기
$conn->close();
?>

