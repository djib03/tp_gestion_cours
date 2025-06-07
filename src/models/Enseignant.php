<?php require '../layouts/main.php'; ?>
     <h3>Your Courses</h3>
     <ul>
     <?php foreach ($matieres as $matiere): ?>
         <li><?php echo $matiere['nom'] . " - " . $matiere['description']; ?></li>
     <?php endforeach; ?>
     </ul>
     <a href="/logout.php">Logout</a>