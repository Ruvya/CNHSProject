<?php
// Direct registrar fix - bypasses Laravel routing issues

echo "Content-Type: text/html\n\n";
echo "<!DOCTYPE html><html><head><title>Direct Registrar Fix</title>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .box{border:1px solid #ccc;padding:15px;margin:10px 0;}</style></head><body>";
echo "<h1>🔧 Direct Registrar Fix</h1>";

try {
    // Bootstrap Laravel
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "<div class='box'><h2>Step 1: Database Connection Test</h2>";
    
    // Test database connection
    try {
        $pdo = new PDO(
            'mysql:host=127.0.0.1;port=3306;dbname=newstudentportal',
            'root',
            'ruvyannlacaba1@1'
        );
        echo "<p class='success'>✅ Database connection successful</p>";
    } catch (Exception $e) {
        echo "<p class='error'>❌ Database connection failed: " . $e->getMessage() . "</p>";
        echo "</div></body></html>";
        exit;
    }
    echo "</div>";

    echo "<div class='box'><h2>Step 2: Check Registrars Table</h2>";
    
    // Check if registrars table exists
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'registrars'")->fetch();
    if ($tableCheck) {
        echo "<p class='success'>✅ Registrars table exists</p>";
        
        // Count existing registrars
        $count = $pdo->query("SELECT COUNT(*) FROM registrars")->fetchColumn();
        echo "<p>Current registrar count: {$count}</p>";
        
        // Show existing registrars
        $existing = $pdo->query("SELECT id, email, first_name, last_name FROM registrars")->fetchAll();
        if ($existing) {
            echo "<p>Existing registrars:</p><ul>";
            foreach ($existing as $reg) {
                echo "<li>ID: {$reg['id']}, Email: {$reg['email']}, Name: {$reg['first_name']} {$reg['last_name']}</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "<p class='error'>❌ Registrars table does not exist</p>";
        
        // Create the table
        echo "<p>Creating registrars table...</p>";
        $createTable = "
            CREATE TABLE registrars (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
                name VARCHAR(255) NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                phone VARCHAR(255) NULL,
                address TEXT NULL,
                profile_picture VARCHAR(255) NULL,
                registrar_secret VARCHAR(255) NULL,
                remember_token VARCHAR(100) NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ";
        
        if ($pdo->exec($createTable) !== false) {
            echo "<p class='success'>✅ Registrars table created successfully</p>";
        } else {
            echo "<p class='error'>❌ Failed to create registrars table</p>";
            echo "</div></body></html>";
            exit;
        }
    }
    echo "</div>";

    echo "<div class='box'><h2>Step 3: Create/Update Registrar Account</h2>";
    
    // Delete existing registrar
    $deleteStmt = $pdo->prepare("DELETE FROM registrars WHERE email = ?");
    $deleted = $deleteStmt->execute(['registrar@cnhs.edu.ph']);
    echo "<p>Deleted existing registrar account</p>";
    
    // Create new registrar with hashed password
    $hashedPassword = password_hash('123456', PASSWORD_DEFAULT);
    
    $insertStmt = $pdo->prepare("
        INSERT INTO registrars (first_name, last_name, name, email, password, phone, address, registrar_secret, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    $inserted = $insertStmt->execute([
        'CNHS',
        'Registrar', 
        'CNHS Registrar',
        'registrar@cnhs.edu.ph',
        $hashedPassword,
        '09123456789',
        'Camarines Norte High School',
        'letmein'
    ]);
    
    if ($inserted) {
        $registrarId = $pdo->lastInsertId();
        echo "<p class='success'>✅ Created new registrar account with ID: {$registrarId}</p>";
    } else {
        echo "<p class='error'>❌ Failed to create registrar account</p>";
        echo "</div></body></html>";
        exit;
    }
    echo "</div>";

    echo "<div class='box'><h2>Step 4: Test Password Verification</h2>";
    
    // Verify the password
    $verifyStmt = $pdo->prepare("SELECT id, email, password FROM registrars WHERE email = ?");
    $verifyStmt->execute(['registrar@cnhs.edu.ph']);
    $registrar = $verifyStmt->fetch();
    
    if ($registrar) {
        $passwordCorrect = password_verify('123456', $registrar['password']);
        echo "<p>Password verification: " . ($passwordCorrect ? "<span class='success'>✅ CORRECT</span>" : "<span class='error'>❌ INCORRECT</span>") . "</p>";
        
        if ($passwordCorrect) {
            echo "<p class='success'>✅ Registrar account is ready for login!</p>";
        }
    } else {
        echo "<p class='error'>❌ Could not find created registrar account</p>";
    }
    echo "</div>";

    echo "<div class='box' style='background:#e8f5e8;'><h2>✅ FINAL CREDENTIALS</h2>";
    echo "<p><strong>Email:</strong> registrar@cnhs.edu.ph</p>";
    echo "<p><strong>Password:</strong> 123456</p>";
    echo "<p><strong>Role:</strong> Select 'Registrar' from dropdown</p>";
    echo "<p><a href='/login' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Try Login Now</a></p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='box'><p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre></div>";
}

echo "</body></html>";
?>
