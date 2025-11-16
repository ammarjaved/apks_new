<?php
echo "Loaded php.ini: " . php_ini_loaded_file() . "<br>";
echo "Memory Limit: " . ini_get('memory_limit') . "<br>";
echo "Architecture: " . (PHP_INT_SIZE * 8) . "-bit<br>";
phpinfo();
?>