<?php
$servername = "localhost";
$username = "root";
$password = "5308";
$dbname = "iot_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil data terbaru dari tabel sensor_data
$sql = "SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IoT Hydroponics Monitoring</title>
  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
    rel="stylesheet"
  />
  <!-- Font Awesome Icons -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-pIVpY70+6F35Z28CtbF8Z2rY1lKkIh5zQ3tx1Kf4RR1ZPw0Siv5N1yxK+5WfF1QZ1aKqjZx4SAl6x51H3+M2Vg=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />
  <style>
    /* Reset dasar */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: "Roboto", sans-serif;
      background: linear-gradient(135deg, #74abe2, #5563de);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .container {
      background: #ffffff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 800px;
      animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    h1 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 2rem;
      color: #5563de;
    }
    .data {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }
    .data-item {
      flex: 1 1 calc(50% - 20px);
      background: #f0f4ff;
      margin: 10px;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      transition: transform 0.3s, box-shadow 0.3s;
      position: relative;
      overflow: hidden;
    }

    .data-item i {
      font-size: 2.5rem;
      margin-bottom: 10px;
      color: #5563de;
    }
    .data-item h2 {
      font-size: 1.2rem;
      margin-bottom: 10px;
      color: #333;
    }
    .data-item p {
      font-size: 1.5rem;
      font-weight: 500;
      color: #5563de;
    }
    /* Styling Progress Bar */
    .progress-container {
      background-color: #e0e0e0;
      border-radius: 10px;
      height: 15px;
      margin-top: 10px;
      overflow: hidden;
    }
    .progress-bar {
      height: 100%;
      border-radius: 10px;
      background: #5563de;
      width: 0;
      transition: width 0.5s ease-in-out;
    }
    .footer {
      text-align: center;
      margin-top: 30px;
      font-size: 0.9rem;
      color: #777;
    }
    .footer a {
      color: #5563de;
      text-decoration: none;
      transition: color 0.3s;
    }
    .footer a:hover {
      color: #333;
    }
    @media (max-width: 600px) {
      .data-item {
        flex: 1 1 100%;
        margin: 10px 0;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Monitoring IoT Sistem Hidroponik</h1>
    <div class="data">
      <!-- Suhu (Temperature) -->
      <div class="data-item">
        <i class="fas fa-thermometer-half"></i>
        <h2>Suhu</h2>
        <p>
          <span id="temperature">
            <?php echo isset($data['temperature']) ? $data['temperature'] : 'N/A'; ?>
          </span>
          °C
        </p>
        <div class="progress-container">
          <div class="progress-bar" id="temp-progress" style="width: <?php echo isset($data['temperature']) ? ($data['temperature'] / 50 * 100) : 0; ?>%;"></div>
        </div>
      </div>
      <!-- Kelembapan (Humidity) -->
      <div class="data-item">
        <i class="fas fa-tint"></i>
        <h2>Kelembapan</h2>
        <p>
          <span id="humidity">
            <?php echo isset($data['humidity']) ? $data['humidity'] : 'N/A'; ?>
          </span>
          %
        </p>
        <div class="progress-container">
          <div class="progress-bar" id="humidity-progress" style="width: <?php echo isset($data['humidity']) ? $data['humidity'] : 0; ?>%;"></div>
        </div>
      </div>
      <!-- Level Air (Water Level) -->
      <div class="data-item">
        <i class="fas fa-water"></i>
        <h2>Level Air</h2>
        <p>
          <span id="waterLevel">
            <?php echo isset($data['water_level']) ? $data['water_level'] : 'N/A'; ?>
          </span>
          %
        </p>
        <div class="progress-container">
          <div class="progress-bar" id="water-progress" style="width: <?php echo isset($data['water_level']) ? $data['water_level'] : 0; ?>%;"></div>
        </div>
      </div>
      <!-- Waktu Pengambilan Data (Timestamp) -->
      <div class="data-item">
        <i class="fas fa-clock"></i>
        <h2>Waktu Pengambilan Data</h2>
        <p>
          <span id="timestamp">
            <?php echo isset($data['timestamp']) ? $data['timestamp'] : 'N/A'; ?>
          </span>
        </p>
      </div>
    </div>
    <div class="footer">
      <p>
        Proyek IoT Hidroponik - 
        <a href="https://github.com/username/repository" target="_blank">GitHub</a>
      </p>
    </div>
  </div>

  <script>
    function fetchData() {
      fetch('data.php?latest=1')
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            document.getElementById("temperature").textContent =
              data.temperature ?? "N/A";
            document.getElementById("humidity").textContent =
              data.humidity ?? "N/A";
            document.getElementById("waterLevel").textContent =
              data.water_level ?? "N/A";
            document.getElementById("timestamp").textContent =
              data.timestamp ?? "N/A";

            // Update progress bars
            const tempValue = parseFloat(data.temperature);
            if (!isNaN(tempValue)) {
              // Misalkan maksimum suhu adalah 50°C
              document.getElementById("temp-progress").style.width =
                (tempValue / 50 * 100) + "%";
            }
            const humidityValue = parseFloat(data.humidity);
            if (!isNaN(humidityValue)) {
              document.getElementById("humidity-progress").style.width =
                humidityValue + "%";
            }
            const waterValue = parseFloat(data.water_level);
            if (!isNaN(waterValue)) {
              document.getElementById("water-progress").style.width =
                waterValue + "%";
            }
          }
        })
        .catch((error) => console.error("Error fetching data:", error));
    }

    setInterval(fetchData, 5000); // Refresh setiap 5 detik
  </script>
</body>
</html>
