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

// 과목의 이름을 가져오기 위한 쿼리
$course_id = $_GET['cid'];
$course_info_sql = "SELECT cname FROM courses WHERE cid='$course_id'";
$course_info_result = $conn->query($course_info_sql);
$course_info_row = $course_info_result->fetch_assoc();
$course_title = $course_info_row['cname'];

// 해당 과목의 주차별 강의 목록을 가져오는 쿼리
$course_contents_sql = "SELECT * FROM course_contents WHERE cid='$course_id'";
$course_contents_result = $conn->query($course_contents_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $course_title; ?></title>
</head>
<body>
    <h2><?php echo $course_title; ?></h2>
    <h3>강의 목차</h3>
    <ul>
        <?php
        // 주차별 강의 목록을 출력
        while($row = $course_contents_result->fetch_assoc()) {
	    echo "Test";
            echo "<li><a href='#'>" . $row["week"] . "주차: " . $row["subtitle"] . "</a></li>";
        }
        ?>
    </ul>

    <?php
    // 주차별 강의 목록을 다시 가져와서 출력
    $course_contents_result->data_seek(0); // 결과셋의 포인터를 처음으로 되돌림
    while($row = $course_contents_result->fetch_assoc()) {
        echo "<div id='week" . $row["week"] . "'>";
        echo "<h3>" . $row["week"] . "주차: " . $row["subtitle"] . "</h3>";
        // 각 주차별 강의 내용 출력
        // 여기에 해당 주차의 강의 내용을 출력하는 코드를 추가
        echo "</div>";
    }
    ?>

</body>
</html>

<?php
// MySQL 연결 닫기
$conn->close();
?>

