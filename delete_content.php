<?php
// 세션 시작
session_start();

// 만약 로그인 세션이 없다면 로그인 페이지로 리다이렉트
if (!isset($_SESSION['id'])) {
    header("Location: instructorlogin.php"); // 로그인 페이지 URL로 리다이렉트
    exit; // 코드 실행 중지
}

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

// 사용자가 선택한 과목의 ID 가져오기
$course_id = $_GET['course_id'];
$week = $_GET['week_delete'];

// 현재 로그인한 강사의 이름 가져오기
#$instructor_name = $_SESSION['name'];

// 선택한 과목을 강사의 담당 과목에서 제거하는 쿼리
#$remove_course_contents_sql = "DELETE FROM course_contents WHERE cid='$course_id'";
#$sql = "DELETE FROM course_contents (cid, week, subtitle) VALUES ('$course_id', '$week', '$subtitle')";
// 삭제할 콘텐츠를 데이터베이스에서 삭제하는 쿼리
$sql = "DELETE FROM course_contents WHERE cid='$course_id' AND week='$week'";

#if ($conn->query($remove_course_contents_sql) === TRUE) {
#    // 강의를 삭제한 후에 해당 강의의 내용도 삭제
#    $remove_course_sql = "DELETE FROM courses WHERE cid='$course_id' AND instructor='$instructor_name'";
#    if ($conn->query($remove_course_sql) === TRUE) {
#        echo "Course removed successfully";
#    } else {
#        echo "Error removing course contents: " . $conn->error;
#    }
#} else {
#    echo "Error removing course: " . $conn->error;
#}
if ($conn->query($sql) === TRUE) {
        echo "Content deleted successfully";
}else{
        echo "Error deleting content: " . $conn->error;
}



// MySQL 연결 닫기
$conn->close();
?>

