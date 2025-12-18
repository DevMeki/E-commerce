<?php
require 'config.php';
echo "ORDER TABLE:\n";
$result = $conn->query("DESCRIBE `order`");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
echo "\nORDERITEM TABLE:\n";
$result = $conn->query("DESCRIBE orderitem");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>
