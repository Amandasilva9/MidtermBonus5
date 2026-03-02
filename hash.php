<?php
echo "Hash for 12345: " . password_hash("12345", PASSWORD_DEFAULT) . "<br>";
echo "Hash for pass123: " . password_hash("pass123", PASSWORD_DEFAULT) . "<br>";
?>
