<?php require '/wamp64/www/tp_gestion_cours/views/layouts/main.php'; ?>
     <h3>Your Grades</h3>
     <ul>
     <?php foreach ($notes as $note): ?>
         <li><?php echo $note['nom'] . ": " . $note['valeur']; ?></li>
     <?php endforeach; ?>
     </ul>
     <a href="/tp_gestion_cours/logout.php">Logout</a>