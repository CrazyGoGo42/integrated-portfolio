<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .header { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .api-test { background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .button { display: inline-block; padding: 10px 20px; background: #007cba; color: white; text-decoration: none; border-radius: 4px; }
        .api-response { background: #f8f8f8; padding: 15px; border-radius: 4px; margin-top: 10px; font-family: monospace; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Portfolio Backend Admin</h1>
            <p>Administration panel for the recreated Joline's portfolio backend</p>
        </div>
        
        <div class="api-test">
            <h2>API Testing</h2>
            <p>Test the API endpoints:</p>
            <a href="index.php" class="button" target="_blank">Test Full Portfolio API</a>
            <a href="index.php?category=1" class="button" target="_blank">Test Category 1</a>
            <a href="index.php?gallery=1" class="button" target="_blank">Test Gallery 1</a>
        </div>
        
        <div class="section">
            <h2>Database Status</h2>
            <?php
            require_once 'config.php';

            try {
                $pdo = new PDO(
                    "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                    DB_USER,
                    DB_PASS
                );
                echo "<p style='color: green;'>Database connection successful</p>";

                // Check tables
                $stmt = $pdo->query("SHOW TABLES");
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                echo "<p>Tables found: " . implode(', ', $tables) . "</p>";

            } catch (PDOException $e) {
                echo "<p style='color: red;'>Database connection failed: " . $e->getMessage() . "</p>";
                echo "<p>Using static data fallback</p>";
            }
            ?>
        </div>
        
        <div class="section">
            <h2>System Information</h2>
            <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
            <p><strong>Upload Directory:</strong> <?php echo is_writable('uploads/') ? 'Writable' : 'Not writable'; ?></p>
            <p><strong>Max Upload Size:</strong> <?php echo ini_get('upload_max_filesize'); ?></p>
        </div>
    </div>
</body>
</html>