<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "5308";
$dbname = "iot_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed"]);
    exit;
}

// Jika request datang dari AJAX
if (isset($_GET['latest'])) {
    $sql = "SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode(["success" => true, "temperature" => $data['temperature'], "humidity" => $data['humidity'], "water_level" => $data['water_level'], "timestamp" => $data['timestamp']]);
    } else {
        echo json_encode(["success" => false, "message" => "No data found"]);
    }
    exit;
}

// Menerima data POST dari ESP8266
if (isset($_POST['temperature']) && isset($_POST['humidity']) && isset($_POST['waterLevel'])) {
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    $waterLevel = $_POST['waterLevel'];

    $sql = "INSERT INTO sensor_data (temperature, humidity, water_level) VALUES ('$temperature', '$humidity', '$waterLevel')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Data inserted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $sql . " - " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Missing data"]);
}

$conn->close();
?>
