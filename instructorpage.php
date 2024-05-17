<?php
// 여기에 로그인 세션 확인 및 사용자 정보 가져오는 코드 추가
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

// 강사가 담당하는 강의 목록을 가져오는 쿼리
$instructor_name = $_SESSION['name']; // 강사의 이름을 세션에서 가져옴
$courses_sql = "SELECT * FROM courses WHERE instructor='$instructor_name'";
$courses_result = $conn->query($courses_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Page</title>
</head>
<body>
    <h2>Welcome, <?php echo $instructor_name; ?>!</h2>
    <h3>Your Courses:</h3>
    <ul>
        <?php
        // 강사가 담당하는 강의 목록 출력
        #while($row = $courses_result->fetch_assoc()) {
            #echo "<li><a href='lecturemanage.php?cid=" . $row["cid"] . "'>" . $row["cname"] . "</a></li>";
            #echo "<li>";
            #echo "<a href='lecturemanage.php?cid=" . $row["cid"] . "'>" . $row["cname"] . "</a>";
            #echo "<a href='inst_course.php?cid=" . $row["cid"] . "'>" . $row["cname"] . "</a>";
            #echo " | <a href='remove_course.php?cid=" . $row["cid"] . "'>Remove</a>";
            #echo "</li>";
            #echo "<button onclick='showAlert()'>remove</button>";
        #}
        while($row = $courses_result->fetch_assoc()) {
            echo "<li><a href='lecturemanage.php?cid=" . $row["cid"] . "'>" . $row["cname"] . "</a> | <button onclick='removeCourse(" . $row["cid"] . ")'>Remove</button></li>";
        }
        ?>
    </ul>

    <h3>Add New Course</h3>
    <form id="add_course_form">
        <label for="course_name">Course Name:</label>
        <input type="text" id="course_name" name="course_name" required><br>
        <button type="button" onclick="addCourse()">Add Course</button>
    </form>

<script>
    function addCourse() {
        var courseName = document.getElementById('course_name').value;
	var url = '/add_course.php?course_name=' + encodeURIComponent(courseName);

        fetch(url)
        .then(response => {
            if (response.ok) {
                alert('코스가 성공적으로 추가되었습니다.');
                window.location.reload(); // 페이지 새로고침
            } else {
                throw new Error('코스 추가 중 오류가 발생했습니다.');
            }
        })
        .catch(error => {
            alert(error.message);
        });
    }


    function removeCourse(cid) {
        var url = '/remove_course.php?cid=' + encodeURIComponent(cid);

        fetch(url)
        .then(response => {
            if (response.ok) {
                alert('코스가 성공적으로 삭제되었습니다.');
                window.location.reload(); // 페이지 새로고침
            } else {
                throw new Error('코스 삭제 중 오류가 발생했습니다.');
            }
        })
        .catch(error => {
            alert(error.message);
        });
    }
</script>

</body>
</html>

<?php
// MySQL 연결 닫기
$conn->close();
?>
