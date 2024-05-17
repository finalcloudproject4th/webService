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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이페이지</title>
</head>
<body>
    <h2>마이페이지</h2>
    <p>사용자 ID: <?php echo $user_info_row['id']; ?></p>
    <p>수강중인 과목 수: <?php echo $num_courses; ?></p>
    <p>수강중인 과목:</p>
    <ul>
        <?php
        // 사용자가 수강중인 각각의 과목에 대한 하이퍼링크 생성
        while($row = $enrollments_result->fetch_assoc()) {
                echo "<li><a href='course.php?cid=" . $row["cid"] . "'>" . $row["cname"] . "</a></li>";
        }

        ?>
    </ul>

    <!-- enroll.php로 이동하는 링크 추가 -->
    <p><a href="enroll.php">강의 신청하러 가기</a></p>

</body>
</html>

<?php
// MySQL 연결 닫기
$conn->close();
?>
