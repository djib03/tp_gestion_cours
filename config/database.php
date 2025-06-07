<?php
     $dsn = 'mysql:host=localhost;dbname=escep_gestion_cours';
     $username = 'root';
     $password = '';

     try {
         $pdo = new PDO($dsn, $username, $password);
         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     } catch (PDOException $e) {
         die("Connection failed: " . $e->getMessage());
     }
     ?>