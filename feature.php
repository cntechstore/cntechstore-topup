<?php
require "database.php";

function isFeatureEnabled($key){
    global $conn;

    $stmt = $conn->prepare("
        SELECT is_enabled
        FROM site_feature_control
        WHERE feature_key=?
        LIMIT 1
    ");

    $stmt->bind_param("s", $key);
    $stmt->execute();

    $row = $stmt->get_result()->fetch_assoc();

    return $row ? (int)$row['is_enabled'] === 1 : true;
}
?>