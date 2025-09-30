<?php
$host = getenv('MYSQL_HOST') ?: 'mysql';
$port = getenv('MYSQL_PORT') ?: '3306';
$database = getenv('MYSQL_DATABASE') ?: 'mydb';
$username = getenv('MYSQL_USER') ?: 'dbuser';
$password = getenv('MYSQL_PASSWORD') ?: 'dbpassword';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { color: green; }
        .error { color: red; }
        pre { background: #f0f0f0; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Connection Test</h1>

        <?php
        try {
            echo "<h2>Testing MySQL Connection...</h2>";
            echo "<pre>";
            echo "Host: $host\n";
            echo "Port: $port\n";
            echo "Database: $database\n";
            echo "Username: $username\n";
            echo "</pre>";

            $connection = new mysqli($host, $username, $password, $database, $port);

            if ($connection->connect_error) {
                throw new Exception("Connection failed: " . $connection->connect_error);
            }

            echo '<p class="success">✓ Connected successfully!</p>';

            echo "<h2>Creating Test Table...</h2>";
            $sql = "CREATE TABLE IF NOT EXISTS test_table (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

            if ($connection->query($sql) === TRUE) {
                echo '<p class="success">✓ Table created successfully</p>';
            } else {
                throw new Exception("Error creating table: " . $connection->error);
            }

            echo "<h2>Inserting Test Data...</h2>";
            $sql = "INSERT INTO test_table (name) VALUES ('Test Entry " . date('Y-m-d H:i:s') . "')";
            if ($connection->query($sql) === TRUE) {
                echo '<p class="success">✓ Data inserted successfully</p>';
            }

            echo "<h2>Retrieving Data...</h2>";
            $sql = "SELECT * FROM test_table ORDER BY id DESC LIMIT 5";
            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                echo '<table border="1" cellpadding="10">';
                echo '<tr><th>ID</th><th>Name</th><th>Created At</th></tr>';
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td>' . $row['name'] . '</td>';
                    echo '<td>' . $row['created_at'] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<p>No data found</p>';
            }

            $connection->close();

        } catch (Exception $e) {
            echo '<p class="error">✗ Error: ' . $e->getMessage() . '</p>';
        }
        ?>

        <p><a href="index.php">← Back to Home</a></p>
    </div>
</body>
</html>