<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['cid'])) {
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

    $cid = $_GET['cid'];

    // 사용자 uid 가져오기
    if(isset($_SESSION['uid'])) {
        $user_id = $_SESSION['uid'];

        // enrollments update
        $update_enrollments_sql = "INSERT INTO enrollments(user_id, course_id) values ('$user_id', '$cid')";
        if ($conn->query($update_enrollments_sql) === TRUE) {
            echo "success";
        } else {
            echo "fail";
        }
    } else {
        echo "user not logged in";
    }

    // MySQL 연결 닫기
    $conn->close();
}
?>


