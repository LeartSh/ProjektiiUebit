<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "studentiks");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed"]);
    exit();
}

$response = ["status" => "error", "message" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $email;
            
            $response["status"] = "success";
        } else {
            $response["message"] = "Incorrect password.";
        }
    } else {
        $response["message"] = "No account found with that email.";
    }
    $stmt->close();
}

$conn->close();
echo json_encode($response);
exit();
