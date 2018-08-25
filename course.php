<?php
header('Access-Control-Allow-Origin: *'); 
header('Content-Type: application/json; charset=utf-8');
// ================ Connect to server =================
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "qrcode";

// user server
$servername = "localhost";
$username = "id6911739_qrcodeapp";
$password = "123456789";
$dbname = "id6911739_qrcode";

$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");
// ====================================================
$data = array(
    "result" => 0
);
$request_array = array("add", "edit", "remove", "get", "get_id", "get_id_room");
//

if (isset($_POST['request']) && in_array($_POST['request'], $request_array)) {
    if ($_POST['request'] == "add") {
        if (isset($_POST['room_id']) && $_POST['room_id'] != "" && isset($_POST['name']) && $_POST['name'] != "" && isset($_POST['start_time']) && $_POST['start_time'] != "" && isset($_POST['end_time']) && $_POST['end_time'] != "") {
            $post_room_id = $_POST['room_id'];
            $post_name = $_POST['name'];
            $post_start_time = $_POST['start_time'];
            $post_end_time = $_POST['end_time'];
        
            if ($conn->connect_error) {
                $data = array(
                    "result" => 0,
                    "message" => "Not connect database"
                );
                echo json_encode($data);
            }          
            $sql = "INSERT INTO course (room_id, name, start_time, end_time)
            VALUES ('$post_room_id', '$post_name', '$post_start_time', '$post_end_time')";

            if ($conn->query($sql) === TRUE) {
                $last_id = $conn->insert_id;
                $data = array(
                    "result" => 1,
                    "course_id" => $last_id,
                    "room_id" => $post_room_id,
                    "name" => $post_name,
                    "start_time" => $post_start_time,
                    "end_time" => $post_end_time
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
                "message" => "Parameter invalid"
            );
            echo json_encode($data);
        }
    } else if ($_POST['request'] == "edit") {
        if (isset($_POST['course_id']) && $_POST['course_id'] != "" && isset($_POST['name']) && $_POST['name'] != "") {
            $post_course_id = $_POST['course_id'];
            $post_name = $_POST['name'];

            $sql = "SELECT course_id FROM course WHERE course_id = '$post_course_id'";
            $select = $conn->query($sql);
            if ($select->num_rows > 0) {
                $sql = "UPDATE course SET name='$post_name' WHERE course_id='$post_course_id'";

                if ($conn->query($sql) === TRUE) {
                    $data = array(
                        "result" => 1,
                        "course_id" => $post_course_id,
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
        if (isset($_POST['course_id']) && $_POST['course_id'] != "") {
            $post_course_id = $_POST['course_id'];
            $sql = "SELECT course_id FROM course WHERE course_id = '$post_course_id'";
            $select = $conn->query($sql);

            if ($select->num_rows > 0) {
                $sql = "DELETE FROM course WHERE course_id='$post_course_id'";
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

        $sql = "SELECT * FROM course";
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
    } else if ($_POST['request'] == "get_id") {
        if (isset($_POST['course_id']) && $_POST['course_id'] != "") {
            $post_course_id = $_POST['course_id'];

            $sql = "SELECT * FROM course WHERE course_id = '$post_course_id'";
            $select = $conn->query($sql);

            if ($select->num_rows > 0) {
                $data = array();
                $row = $select->fetch_assoc();
                $data = $row;
                
                echo json_encode(array(
                    "result" => 1,
                    "data" => $data
                ));
            } else {
                $data = array(
                    "result" => 0,
                    "message" => "Id course invalid"
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
    } else if ($_POST['request'] == "get_id_room") {
        if (isset($_POST['room_id']) && $_POST['room_id'] != "") {
            $post_room_id = $_POST['room_id'];
            $sql = "SELECT * FROM course WHERE room_id = '$post_room_id'";
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
                    "message" => "Id room course invalid"
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