<?php
require_once 'config/database.php';
     require_once 'src/controllers/AuthController.php';
     $controller = new AuthController();
     $controller->login();
     ?>