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

        // MySQL 연결 닫기
        $conn->close();
        ?>
    </ul>

    <script>
        // 수강신청 함수
        function enroll(cid) {
            // 수강신청을 위한 AJAX 요청
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "enroll.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    if (xhr.responseText === "success") {
                        alert("수강신청이 완료되었습니다.");
                        window.location.reload(); // 페이지 새로고침
                    } else {
                        alert("수강신청에 실패하였습니다.");
                    }
                }
            };
            xhr.send("cid=" + cid);
        }
    </script>
</body>
</html>

