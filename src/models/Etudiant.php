<?php require '../layouts/main.php'; ?>
     <h3>Your Grades</h3>
     <ul>
     <?php foreach ($notes as $note): ?>
         <li><?php echo $note['nom'] . ": " . $note['valeur']; ?></li>
     <?php endforeach; ?>
     </ul>
     <a href="/logout.php">Logout</a>