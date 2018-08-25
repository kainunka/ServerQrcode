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
$request_array = array("add", "edit", "remove", "get", "get_id");
//

if (isset($_POST['request']) && in_array($_POST['request'], $request_array)) {
    if ($_POST['request'] == "add") {
        if (isset($_POST['name']) && $_POST['name'] != "") {
            $post_name = $_POST['name'];
        
            if ($conn->connect_error) {
                $data = array(
                    "result" => 0,
                    "message" => "Not connect database"
                );
                echo json_encode($data);
            } 

            $sql = "INSERT INTO room (name)
            VALUES ('$post_name')";

            if ($conn->query($sql) === TRUE) {
                $last_id = $conn->insert_id;
                $data = array(
                    "result" => 1,
                    "room_id" => $last_id,
                    "name" => $post_name,
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
        if (isset($_POST['room_id']) && $_POST['room_id'] != "" && isset($_POST['name']) && $_POST['name'] != "") {
            $post_room_id = $_POST['room_id'];
            $post_name = $_POST['name'];

            $sql = "SELECT room_id FROM room WHERE room_id = '$post_room_id'";
            $select = $conn->query($sql);
            if ($select->num_rows > 0) {
                $sql = "UPDATE room SET name='$post_name' WHERE room_id='$post_room_id'";

                if ($conn->query($sql) === TRUE) {
                    $data = array(
                        "result" => 1,
                        "room_id" => $post_room_id,
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
        if (isset($_POST['room_id']) && $_POST['room_id'] != "") {
            $post_room_id = $_POST['room_id'];
            $sql = "SELECT room_id FROM room WHERE room_id = '$post_room_id'";
            $select = $conn->query($sql);

            if ($select->num_rows > 0) {
                $sql = "DELETE FROM room WHERE room_id='$post_room_id'";
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

        $sql = "SELECT * FROM room";
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
        if (isset($_POST['room_id']) && $_POST['room_id'] != "") {
            $post_room_id = $_POST['room_id'];

            $sql = "SELECT * FROM room WHERE room_id = '$post_room_id'";
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
                    "message" => "Id room invalid"
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