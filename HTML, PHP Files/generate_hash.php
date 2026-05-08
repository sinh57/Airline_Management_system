<?php
// Quick script to generate bcrypt hash for admin123
$password = "admin123";
$hash = password_hash($password, PASSWORD_BCRYPT);
echo "Hash for 'admin123':\n";
echo $hash . "\n";
echo "\nCopy this hash and paste it into dbms.sql line 153\n";
echo "Replace: \$2y\$10\$abc123def456ghi789jkl012mno345pqr678stu901vwx234yzA\n";
echo "With: " . $hash . "\n";
?>
