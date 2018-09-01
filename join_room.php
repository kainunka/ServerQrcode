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
$request_array = array("add", "remove", "get", "get_id", "course_add", "get_user");
//

if (isset($_POST['request']) && in_array($_POST['request'], $request_array)) {
    if ($_POST['request'] == "add") {
        if (isset($_POST['course_id']) && $_POST['course_id'] != "" && isset($_POST['user_id']) && $_POST['user_id'] != "" && isset($_POST['time']) && $_POST['time'] != "") {
            $post_course_id = $_POST['course_id'];
            $post_user_id = $_POST['user_id'];
            $post_time = $_POST['time'];
        
            if ($conn->connect_error) {
                $data = array(
                    "result" => 0,
                    "message" => "Not connect database"
                );
                echo json_encode($data);
            }          
            $sql = "INSERT INTO join_room (course_id, user_id, time)
            VALUES ('$post_course_id', '$post_user_id', '$post_time')";

            if ($conn->query($sql) === TRUE) {
                $last_id = $conn->insert_id;
                $data = array(
                    "result" => 1,
                    "join_id" => $last_id,
                    "course_id" => $post_course_id,
                    "user_id" => $post_user_id,
                    "time" => $post_time
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
    } else if ($_POST['request'] == "remove") {
        if (isset($_POST['join_id']) && $_POST['join_id'] != "") {
            $post_join_id = $_POST['join_id'];
            $sql = "SELECT join_id FROM join_room WHERE join_id = '$post_join_id'";
            $select = $conn->query($sql);

            if ($select->num_rows > 0) {
                $sql = "DELETE FROM join_room WHERE join_id='$post_join_id'";
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
                    "message" => "Id join room invalid"
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

        $sql = "SELECT * FROM join_room";
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
        if (isset($_POST['join_id']) && $_POST['join_id'] != "") {
            $post_join_id = $_POST['join_id'];

            $sql = "SELECT * FROM join_room WHERE join_id = '$post_join_id'";
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
                    "message" => "Id join room invalid"
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
    } else if ($_POST['request'] == "get_user") {
        if (isset($_POST['user_id']) && $_POST['user_id'] != "") {
            $post_user_id = $_POST['user_id'];
            $sql = "SELECT * FROM join_room WHERE user_id = '$post_user_id'";
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
                "message" => "Parameter invalid"
            );
            echo json_encode($data);
        }
    } else if ($_POST['request'] == "course_add") {
        if (isset($_POST['course_id']) && $_POST['course_id'] != "" && isset($_POST['user_id']) && $_POST['user_id'] != "" && isset($_POST['time']) && $_POST['time'] != "") {
            $post_course_id = $_POST['course_id'];
            $post_user_id = $_POST['user_id'];
            $post_time = $_POST['time'];

            $sql = "SELECT * FROM course WHERE course_id = '$post_course_id'";
            $select = $conn->query($sql);
            if ($select->num_rows > 0) {
                $row = $select->fetch_assoc();

                if ($row['end_time'] < $post_time) {
                    $data = array(
                        "result" => 0,
                        "status" => "ไม่สามารถเข้าร่วมได้เนื่องจาก เลยเวลาแล้ว",
                        "message" => "Can not join room"
                    );
                    echo json_encode($data);
                } else {

                    if ($row['start_time'] > $post_time) {
                        $data = array(
                            "result" => 0,
                            "status" => "ไม่สามารถเข้าร่วมได้เนื่องจาก ยังไม่ถึงเวลา",
                            "message" => "Can not join room"
                        );
                        echo json_encode($data);
                    } else {
                        $sql_insert = "INSERT INTO join_room (course_id, user_id, time, name, start_time, end_time)
                        VALUES ('$post_course_id', '$post_user_id', '$post_time', '".$row['name']."', '".$row['start_time']."', '".$row['end_time']."')";

                        if ($conn->query($sql_insert) === TRUE) {
                            $last_id = $conn->insert_id;
                        
                            $data = array(
                                "result" => 1,
                                "join_id" => $last_id,
                                "course_id" => $post_course_id,
                                "user_id" => $post_user_id,
                                "time" => $post_time,
                                "name" => $row['name'],
                                "start_time" => $row['start_time'],
                                "end_time" => $row['end_time']
                            );
                            echo json_encode($data);
                        } else {
                            $data = array(
                                "result" => 0,
                                "message" => "Insert error"
                            );
                            echo json_encode($data);
                        }
                    }
                }

                
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