<?php
session_start();

// 만약 로그인 세션이 없다면 로그인 페이지로 리다이렉트
if (!isset($_SESSION['id'])) {
    header("Location: instructorlogin.html"); // 로그인 페이지 URL로 리다이렉트
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

// 선택된 강의의 ID 가져오기
$course_id = $_GET['cid'];

// 해당 강의의 이름을 가져오는 쿼리
$course_name_sql = "SELECT cname FROM courses WHERE cid='$course_id'";
$course_name_result = $conn->query($course_name_sql);
$course_name_row = $course_name_result->fetch_assoc();
$course_name = $course_name_row['cname'];

// 선택된 강의의 주차와 소제목 가져오기
$course_contents_sql = "SELECT * FROM course_contents WHERE cid='$course_id'";
$course_contents_result = $conn->query($course_contents_sql);

// 데이터베이스 쿼리 실행결과 확인 후 오류 발생 시 적절한 예외처리 수행
if (!$course_contents_result) {
    die("Error retrieving course contents: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture Management</title>
</head>
<body>
    <h2>Lecture Management</h2>
    <h3>Course: <?php echo $course_name; ?></h3>
    <h3>Course Contents</h3>
    <ul>
        <?php
        // 선택된 강의의 주차와 소제목 표시
        while($row = $course_contents_result->fetch_assoc()) {
            echo "<li>Week " . $row["week"] . ": " . $row["subtitle"] . "</li>";
        }
        ?>
    </ul>

    <h3><a href="instructorpage.php">Back to My Courses</a></h3>

    <h3>Add New Content</h3>
    <form id="add_content_form" action="add_content.php" method="get">
    	<label for="week">Week:</label>
    	<input type="text" id="week" name="week" required><br>
    	<label for="subtitle">Subtitle:</label>
    	<input type="text" id="subtitle" name="subtitle" required><br>
    	<input type="hidden" id="course_id" name="course_id" value="<?php echo $course_id; ?>">
    	<input type="submit" value="Add Content">
    </form>

    <h3>Edit Content</h3>
    <form id="edit_content_form" action="edit_content.php" method="get">
        <label for="week_edit">Week to Edit:</label>
        <input type="text" id="week_edit" name="week_edit" required><br>
        <label for="new_subtitle">New Subtitle:</label>
        <input type="text" id="new_subtitle" name="new_subtitle" required><br>
        <input type="hidden" id="course_id" name="course_id" value="<?php echo $course_id; ?>">
        <input type="submit" value="Edit Content">
    </form>

    <h3>Delete Content</h3>
    <form id="delete_content_form" action="delete_content.php" method="get">
        <label for="week_delete">Week to Delete:</label>
        <input type="text" id="week_delete" name="week_delete" required><br>
	<label for="delete_subtitle">Delete Subtitle:</label>
        <input type="hidden" id="course_id" name="course_id" value="<?php echo $course_id; ?>">
        <input type="submit" value="Delete Content">
    </form>

<script>
// 폼 제출 이벤트를 addContent 함수로 연결
document.getElementById('add_content_form').addEventListener('submit', addContent);

function addContent(event) {
    event.preventDefault();
    
    const form = document.getElementById('add_content_form');
    const formData = new FormData(form);

    const queryString = new URLSearchParams(formData).toString();
    const url = `${form.action}?${queryString}`;
    
    fetch(url)
	.then(response => {
            if (response.ok) {
                alert( '콘텐츠가 성공적으로 추가되었습니다.');
                window.location.reload(); // 페이지 새로고침
            } else {
                throw new Error('콘텐츠 추가 중 오류가 발생했습니다.');
            }
        })
        .catch(error => {
            alert(error.message);
        });
}

// 폼 제출 이벤트를 editContent 함수로 연결
document.getElementById('edit_content_form').addEventListener('submit', editContent);

function editContent(event) {
    event.preventDefault();

    const form = document.getElementById('edit_content_form');
    const formData = new FormData(form);

    const queryString = new URLSearchParams(formData).toString();
    const url = `${form.action}?${queryString}`;

    fetch(url)
        .then(response => {
            if (response.ok) {
                alert( '콘텐츠가 성공적으로 수정되었습니다.');
                window.location.reload(); // 페이지 새로고침
            } else {
                throw new Error('콘텐츠 수정 중 오류가 발생했습니다.');
            }
        })
        .catch(error => {
            alert(error.message);
        });
}

// 폼 제출 이벤트를 deleteContent 함수로 연결
document.getElementById('delete_content_form').addEventListener('submit', deleteContent);

function deleteContent(event) {
    event.preventDefault();

    const form = document.getElementById('delete_content_form');
    const formData = new FormData(form);

    const queryString = new URLSearchParams(formData).toString();
    const url = `${form.action}?${queryString}`;

    fetch(url)
        .then(response => {
            if (response.ok) {
                alert( '콘텐츠가 성공적으로 삭제되었습니다.');
                window.location.reload(); // 페이지 새로고침
            } else {
                throw new Error('콘텐츠 삭제 중 오류가 발생했습니다.');
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

