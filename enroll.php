<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll</title>
</head>
<body>
    <h2>Enroll</h2>
    <form action="enroll.php" method="post">
        <label for="search">Search:</label>
        <input type="text" id="search" name="search">
        <input type="submit" value="Search">
    </form>
    <br>
    <h3>Available Courses:</h3>
    <ul>
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

        // POST 요청에서 사용자가 입력한 검색어 가져오기
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search = $_POST['search'];

            // 사용자가 입력한 검색어를 사용하여 과목을 검색
            $sql = "SELECT * FROM courses WHERE cname LIKE '%$search%'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<li>" . $row["cname"] . " - " . $row["description"] . " - " . $row["instructor"] . " - Remaining capacity: " . $row["capacity"] . "</li>";
                    echo "<button onclick=\"enroll('" . $row["cid"] . "')\">Enroll</button><br>";
                }
            } else {
                echo "No courses found.";
            }
        }
// POST 요청에서 사용자가 선택한 과목의 고유 id 가져오기
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['cid'])){
        $cid = $_POST['cid'];

        // 해당 강좌의 수강정원 체크
        $check_capacity_sql = "SELECT capacity FROM courses WHERE cid='$cid'";
        $result = $conn->query($check_capacity_sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $capacity = $row["capacity"];
            if($capacity > 0){
                // 수강신청 가능한 경우 enrollments 테이블 업데이트
                session_start();
                $user_id = $_SESSION['uid']; // 로그인한 사용자의 id
                $update_enrollments_sql = "INSERT INTO enrollments (user_id, course_id) VALUES ('$user_id', '$cid')";
                if ($conn->query($update_enrollments_sql) === TRUE) {
                    // 해당 강좌의 수강정원 감소
                    $update_capacity_sql = "UPDATE courses SET capacity = capacity - 1 WHERE cid='$cid'";
                    if ($conn->query($update_capacity_sql) === TRUE) {
                        echo "success";
                    } else {
                        echo "fail";
                    }
                } else {
                    echo "fail";
                }
            } else {
                echo "capacity_full";
            }
        } else {
            echo "not_found";
        }
    } else {
        echo "cid_not_provided";
    }
}

        // MySQL 연결 닫기
        $conn->close();
        ?>
    </ul>

</body>
</html>

