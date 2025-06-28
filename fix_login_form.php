<?php

// Read the login form file
$content = file_get_contents('resources/views/auth/login.blade.php');

// Fix the duplicate checked attribute
$content = str_replace(
    'value="registrar" required checked>',
    'value="registrar" required>',
    $content
);

// Write the fixed content back
file_put_contents('resources/views/auth/login.blade.php', $content);

echo "✅ Fixed the duplicate 'checked' attribute in the login form!\n";
echo "The registrar radio button no longer has the 'checked' attribute.\n";
echo "Now only the admin radio button is checked by default.\n"; 