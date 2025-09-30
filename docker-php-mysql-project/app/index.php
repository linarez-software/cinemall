<!DOCTYPE html>
<html>
<head>
    <title>Docker PHP MySQL Project</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .info { background: #e8f4f8; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .success { color: green; }
        .error { color: red; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Docker PHP MySQL Project</h1>

        <div class="info">
            <h2>PHP Information</h2>
            <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
            <p><strong>Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
        </div>

        <div class="info">
            <h2>MySQL Connection Test</h2>
            <?php
            $host = getenv('MYSQL_HOST') ?: 'mysql';
            $port = getenv('MYSQL_PORT') ?: '3306';
            $database = getenv('MYSQL_DATABASE') ?: 'mydb';
            $username = getenv('MYSQL_USER') ?: 'dbuser';
            $password = getenv('MYSQL_PASSWORD') ?: 'dbpassword';

            try {
                $connection = new mysqli($host, $username, $password, $database, $port);

                if ($connection->connect_error) {
                    throw new Exception("Connection failed: " . $connection->connect_error);
                }

                echo '<p class="success">✓ Successfully connected to MySQL!</p>';
                echo '<p><strong>Server Version:</strong> ' . $connection->server_info . '</p>';
                echo '<p><strong>Host Info:</strong> ' . $connection->host_info . '</p>';

                $connection->close();

            } catch (Exception $e) {
                echo '<p class="error">✗ ' . $e->getMessage() . '</p>';
            }
            ?>
        </div>

        <div class="info">
            <h2>Available PHP Extensions</h2>
            <?php
            $extensions = get_loaded_extensions();
            $important = ['mysqli', 'mysql', 'pdo', 'pdo_mysql', 'gd', 'mcrypt'];
            echo '<table>';
            echo '<tr><th>Extension</th><th>Status</th></tr>';
            foreach ($important as $ext) {
                $loaded = in_array($ext, $extensions);
                $status = $loaded ? '<span class="success">✓ Loaded</span>' : '<span class="error">✗ Not Loaded</span>';
                echo "<tr><td>{$ext}</td><td>{$status}</td></tr>";
            }
            echo '</table>';
            ?>
        </div>

        <div class="info">
            <h2>Quick Links</h2>
            <p><a href="phpinfo.php">PHP Info</a> | <a href="test-db.php">Database Test</a> | <a href="http://localhost:8081" target="_blank">phpMyAdmin</a></p>
        </div>
    </div>
</body>
</html>