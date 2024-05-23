<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

$apiKey = 'b28d28f38e7641cf2ca8f597785d5ec7'; // Reemplaza con tu API key real.
$city = 'London';
$units = isset($_GET['units']) ? $_GET['units'] : 'metric';
$unitSymbol = $units == 'metric' ? '°C' : '°F';
$url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&units={$units}&appid={$apiKey}";

$client = new Client();

try {
    $response = $client->request('GET', $url);
    $data = json_decode($response->getBody(), true);
    
    if ($response->getStatusCode() == 200) {
        $temperature = $data['main']['temp'];
        $description = $data['weather'][0]['description'];
        $cityName = $data['name'];
        $icon = $data['weather'][0]['icon'];
    } else {
        $error = "Error: " . $data['message'];
    }
} catch (Exception $e) {
    $error = "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Weather App</title>
</head>
<body>
    <div class="weather-container">
        <h1>Weather in <?php echo htmlspecialchars($cityName); ?></h1>
        <?php if (isset($temperature) && isset($description)): ?>
            <div class="weather-info">
                <img src="http://openweathermap.org/img/wn/<?php echo htmlspecialchars($icon); ?>@2x.png" alt="Weather Icon">
                <p>Temperature: <?php echo htmlspecialchars($temperature); ?><?php echo $unitSymbol; ?></p>
                <p>Description: <?php echo htmlspecialchars($description); ?></p>
            </div>
            <div class="unit-toggle">
                <a href="?units=metric" class="<?php echo $units == 'metric' ? 'active' : ''; ?>">°C</a> |
                <a href="?units=imperial" class="<?php echo $units == 'imperial' ? 'active' : ''; ?>">°F</a>
            </div>
        <?php else: ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

