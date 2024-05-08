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

// POST 요청에서 사용자가 제출한 id와 password 가져오기
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $password = $_POST['password'];

    // 입력된 id가 이미 데이터베이스에 존재하는지 확인
    $sql = "SELECT * FROM users WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        //alert( "이미 존재하는 ID입니다.");
        header("Location: register.html");
    } else {
        // 데이터베이스에 사용자 정보 삽입
        $sql = "INSERT INTO users (id, password) VALUES ('$id', '$password')";
        if ($conn->query($sql) === TRUE) {
                //alert( "회원가입 성공!");
            // 회원가입이 완료되면 index.html 페이지로 이동
            header("Location: index.html");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// MySQL 연결 닫기
$conn->close();
?>


