<?php
// session_start();

// DB 연결 정보
$servername = "database-1.cvu4uqwmyddr.ap-northeast-2.rds.amazonaws.com";
$username = "admin";
$password = "Amazon1!";
$dbname = "user";

// MySQL 연결
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// POST로부터 입력된 ID와 Password 가져오기
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$id = $_POST['id'];
	$password = $_POST['password'];

	// ID와 Password 확인 쿼리
	$sql = "SELECT * FROM users WHERE id='$id' AND password='$password'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // 로그인 성공
	    session_start();
	    $row = $result->fetch_assoc();
	    $_SESSION['uid'] = $row['uid'];
	    $_SESSION['id'] = $row['id'];
	    $_SESSION['password'] = $row['password'];
	    header("Location: testenroll.php"); // enroll.html 페이지로 이동
	    exit();
	} else {
	    // 로그인 실패
	    echo "로그인에 실패했습니다. 아이디와 비밀번호를 확인해주세요.";
	}
}
$conn->close();
?>

