<?
header('Content-Type: application/json');
// ================ Connect to server =================
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qrcode";
$conn = new mysqli($servername, $username, $password, $dbname);
// ====================================================
$data = array(
    "result" => 0
);
$type_array = array("teacher", "student");
$request_array = array("add", "edit", "remove");
//

if (isset($_POST['request']) && in_array($_POST['request'], $request_array)) {
    if ($_POST['request'] == "add") {
        if (isset($_POST['username']) && $_POST['username'] != "" && isset($_POST['password']) && $_POST['password'] != "" && isset($_POST['type']) && in_array($_POST['type'], $type_array)) {
            $post_username = $_POST['username'];
            $post_password = $_POST['password'];
            $post_type = $_POST['type'];
        
            if ($conn->connect_error) {
                $data = array(
                    "result" => 0,
                    "message" => "Not connect database"
                );
                echo json_encode($data);
            } 

            $sql = "SELECT username FROM users WHERE username = '$post_username'";
            $select = $conn->query($sql);

            if ($select->num_rows > 0) {
                $data = array(
                    "result" => 0,
                    "message" => "Username already exis"
                );
                echo json_encode($data);
            } else {
                $sql = "INSERT INTO users (username, password, type)
                VALUES ('$post_username', '$post_password', '$post_type')";

                if ($conn->query($sql) === TRUE) {
                    $last_id = $conn->insert_id;
                    $data = array(
                        "result" => 1,
                        "username" => $post_username,
                        "type" => $post_type,
                        "user_id" => $last_id
                    );
                    echo json_encode($data);
                } else {
                    $data = array(
                        "result" => 0,
                        "message" => "Insert data error"
                    );
                    echo json_encode($data);
                }
            }
        } else {
            $data = array(
                "result" => 0,
                "message" => "Parameter invalid"
            );
            echo json_encode($data);
        }
    } else if ($_POST['request'] == "edit") {

    } else if ($_POST['request'] == "remove") {

    } else {
        $data = array(
            "result" => 0,
            "message" => "Request parameter type error"
        );
        echo json_encode($data);
    }
} else {
    $data = array(
        "result" => 0,
        "message" => "No request parameter"
    );
    echo json_encode($data);
}

$conn->close();
?>