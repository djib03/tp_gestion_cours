<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/database.php';
require_once 'src/controllers/DashboardController.php';

$controller = new DashboardController($pdo);
$controller->index();
?>