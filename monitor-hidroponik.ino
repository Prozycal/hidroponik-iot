#include <ESP8266WiFi.h>
#include <DHT.h>
#include <ESP8266HTTPClient.h>
#include <WiFiManager.h>

#define DHTPIN D5
#define DHTTYPE DHT22
DHT dht(DHTPIN, DHTTYPE);

#define sensorPin A0
int sensorValue = 0;

WiFiClient client;
HTTPClient http;

void setup() {
  Serial.begin(115200);

  WiFiManager wifiManager;
  
  if (!wifiManager.autoConnect("AutoConnectAP")) {
    Serial.println("Failed to connect to WiFi, entering WiFi config mode.");
    delay(3000);
    ESP.reset(); 
  }

  Serial.println("Connected to WiFi!");
  dht.begin();
}

void loop() {
  float h = dht.readHumidity();
  float t = dht.readTemperature();

  if (isnan(h) || isnan(t)) {
    Serial.println("Failed to read from DHT sensor!");
    return;
  }

  sensorValue = analogRead(sensorPin);
  float waterLevel = map(sensorValue, 0, 1023, 0, 100);

  // Kirim data ke server PHP melalui HTTP POST
  if (WiFi.status() == WL_CONNECTED) {
    http.begin(client, "http://192.168.1.0/php-iot/data.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    String postData = "temperature=" + String(t) + "&humidity=" + String(h) + "&waterLevel=" + String(waterLevel);
    int httpResponseCode = http.POST(postData);

    if (httpResponseCode > 0) {
      Serial.println("Data sent successfully");
    } else {
      Serial.println("Error sending data");
    }

    http.end();
  }

  delay(5000);
}
