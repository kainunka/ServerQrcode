<?
header('Content-Type: application/json; charset=utf-8');
// ================ Connect to server =================
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qrcode";
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");
// ====================================================
$data = array(
    "result" => 0
);
$type_array = array("teacher", "student");
$request_array = array("add", "edit", "remove", "get");
//

if (isset($_POST['request']) && in_array($_POST['request'], $request_array)) {
    if ($_POST['request'] == "add") {
        if (isset($_POST['username']) && $_POST['username'] != "" && isset($_POST['password']) && $_POST['password'] != "" && isset($_POST['name']) && $_POST['name'] != "" && isset($_POST['type']) && in_array($_POST['type'], $type_array)) {
            $post_username = $_POST['username'];
            $post_password = $_POST['password'];
            $post_type = $_POST['type'];
            $post_name = $_POST['name'];
        
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
                $sql = "INSERT INTO users (username, password, name, type)
                VALUES ('$post_username', '$post_password', '$post_name', '$post_type')";

                if ($conn->query($sql) === TRUE) {
                    $last_id = $conn->insert_id;
                    $data = array(
                        "result" => 1,
                        "username" => $post_username,
                        "name" => $post_name,
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
        if (isset($_POST['user_id']) && $_POST['user_id'] != "" && isset($_POST['name']) && $_POST['name'] != "") {
            $post_user_id = $_POST['user_id'];
            $post_name = $_POST['name'];

            $sql = "SELECT user_id FROM users WHERE user_id = '$post_user_id'";
            $select = $conn->query($sql);
            if ($select->num_rows > 0) {
                $sql = "UPDATE users SET name='$post_name' WHERE user_id='$post_user_id'";

                if ($conn->query($sql) === TRUE) {
                    $data = array(
                        "result" => 1,
                        "user_id" => $post_user_id,
                        "name" => $post_name
                    );
                    echo json_encode($data);
                } else {
                    $data = array(
                        "result" => 0,
                        "message" => "Insert data error"
                    );
                    echo json_encode($data);
                }
            } else {
                $data = array(
                    "result" => 0,
                    "message" => "Id user invalid"
                );
                echo json_encode($data);
            }

        } else {
            $data = array(
                "result" => 0,
                "message" => "Parameter invalid"
            );
            echo json_encode($data);
        }
    } else if ($_POST['request'] == "remove") {
        if (isset($_POST['user_id']) && $_POST['user_id'] != "") {
            $post_user_id = $_POST['user_id'];
            $sql = "SELECT user_id FROM users WHERE user_id = '$post_user_id'";
            $select = $conn->query($sql);

            if ($select->num_rows > 0) {
                $sql = "DELETE FROM users WHERE user_id='$post_user_id'";
                if ($conn->query($sql) === TRUE) {
                    $data = array(
                        "result" => 1,
                        "status" => "success",
                    );
                    echo json_encode($data);
                } else {
                    $data = array(
                        "result" => 0,
                        "message" => "delete error",
                    );
                    echo json_encode($data);
                }
            } else {
                $data = array(
                    "result" => 0,
                    "message" => "Id user invalid"
                );
                echo json_encode($data);
            }
        } else {
            $data = array(
                "result" => 0,
                "message" => "Parameter invalid"
            );
            echo json_encode($data);
        }
    } else if ($_POST['request'] == "get") {

        $sql = "SELECT * FROM users";
        $select = $conn->query($sql);
        
        if ($select->num_rows > 0) {
            $data = array();
            while($row = $select->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode(array(
                "result" => 1,
                "data" => $data
            ));
        } else {
            $data = array(
                "result" => 0,
                "message" => "select error",
            );
            echo json_encode($data);
        }
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