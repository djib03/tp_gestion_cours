<?php
       require_once 'config/database.php';
       require_once 'src/models/User.php';

       class AuthController {
           private $userModel;
           public function __construct() {
               global $pdo;
               $this->userModel = new User($pdo);
           }
           public function login() {
               session_start();
               if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
                   $email = $_POST['email'];
                   $password = $_POST['mot_de_passe'];
                   $user = $this->userModel->authenticate($email, $password);
                   if ($user) {
                       $_SESSION['user_id'] = $user['id'];
                       $_SESSION['role'] = $this->userModel->getRole($user['id']);
                       header("Location: dashboard.php");
                       exit();
                   } else {
                       $error = "Invalid credentials";
                   }
               }
               require 'views/auth/login.php';
           }
       }
       ?>