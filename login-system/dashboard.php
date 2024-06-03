<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    include "db_weather_conn.php";

    // Verificar conexión
    if (!$conn_weather) {
        die("Conexión fallida: " . mysqli_connect_error());
    } else {
        echo "Conexión exitosa.<br>";
    }

    $sql = "SELECT fecha, hora, temperatura, temp_aparente, precipitacion, humedad, velocidad_viento, radiacion_solar, nubosidad FROM weather_hmo";
    $result = mysqli_query($conn_weather, $sql);

    // Verificar consulta
    if (!$result) {
        die("Error en la consulta SQL: " . mysqli_error($conn_weather));
    } else {
        echo "Consulta SQL exitosa.<br>";
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script> <!-- Correccion. Adaptador de fechas. -->
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <?php echo $_SESSION['name']; ?></h1>
        <a href="logout.php" class="logout-link">Logout</a>
        <h2>Serie de tiempo de temperatura</h2>
        <canvas id="temperatureChart"></canvas>
        <h2>Datos del Clima</h2>
        <table class="weather-table">
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Temperatura</th>
                <th>Temperatura aparente</th>
                <th>Precipitación</th>
                <th>Humedad</th>
                <th>Velocidad del viento</th>
                <th>Radiación solar</th>
                <th>Nubosidad</th>
            </tr>
            <?php
            $row_count = 0;
            if (count($data) > 0) {
                foreach ($data as $row) {
                    $row_count++;
                    echo "<tr>
                            <td>" . htmlspecialchars($row['fecha']) . "</td>
                            <td>" . htmlspecialchars($row['hora']) . "</td>
                            <td>" . htmlspecialchars($row['temperatura']) . "</td>
                            <td>" . (isset($row['temp_aparente']) ? htmlspecialchars($row['temp_aparente']) : 'N/A') . "</td>
                            <td>" . (isset($row['precipitacion']) ? htmlspecialchars($row['precipitacion']) : 'N/A') . "</td>
                            <td>" . (isset($row['humedad']) ? htmlspecialchars($row['humedad']) : 'N/A') . "</td>
                            <td>" . (isset($row['velocidad_viento']) ? htmlspecialchars($row['velocidad_viento']) : 'N/A') . "</td>
                            <td>" . (isset($row['radiacion_solar']) ? htmlspecialchars($row['radiacion_solar']) : 'N/A') . "</td>
                            <td>" . (isset($row['nubosidad']) ? htmlspecialchars($row['nubosidad']) : 'N/A') . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No hay datos disponibles</td></tr>";
            }
            ?>
        </table>
        <?php echo "Número total de filas recuperadas: " . $row_count; ?>
    </div>
    <script>
        // PHP a JavaScript
        const data = <?php echo json_encode($data); ?>;
        console.log(data); 
        const labels = data.map(d => d.fecha + ' ' + d.hora);
        const temperatures = data.map(d => parseFloat(d.temperatura));

        // Verificar si los datos son correctos
        console.log("Labels:", labels);
        console.log("Temperatures:", temperatures);

        // Generar tabla
        const ctx = document.getElementById('temperatureChart').getContext('2d');
        const temperatureChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Temperatura',
                    data: temperatures,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: false
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'hour',
                            tooltipFormat: 'YYYY-MM-DD HH:mm'
                        },
                        title: {
                            display: true,
                            text: 'Fecha y Hora'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Temperatura (°C)'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
<?php
} else {
    header("Location: index.php");
    exit();
}
?>
