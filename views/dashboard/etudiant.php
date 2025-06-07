<?php
// Debug : vérifier le contenu de $data
error_log("Vue étudiant - Data reçue: " . print_r($data, true));
?>

<style>
.dashboard-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.search-form {
    background: #f5f5f5;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.search-form label {
    display: block;
    margin-bottom: 10px;
}

.search-form input[type="text"] {
    width: 300px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 3px;
}

.search-form button {
    padding: 8px 15px;
    background: #007cba;
    color: white;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

.suggestions {
    position: relative;
    background: white;
    border: 1px solid #ddd;
    max-height: 200px;
    overflow-y: auto;
    display: none;
}

.suggestions div {
    padding: 10px;
    cursor: pointer;
}

.suggestions div:hover {
    background: #f0f0f0;
}

.notes-list, .moyennes-list {
    list-style: none;
    padding: 0;
}

.note-item, .moyenne-item {
    background: #f9f9f9;
    padding: 10px;
    margin: 5px 0;
    border-left: 4px solid #007cba;
}

.moyenne-generale {
    background: #e8f4f8;
    padding: 15px;
    border-radius: 5px;
    margin: 20px 0;
    text-align: center;
}

.moyenne-generale h4 {
    margin-top: 0;
    color: #007cba;
}

.moyenne-generale p {
    font-size: 1.5em;
    font-weight: bold;
    color: #333;
    margin: 0;
}

.no-data {
    color: #666;
    font-style: italic;
    text-align: center;
    padding: 20px;
}
</style>

<div class="dashboard-container">
    <h3>Vos Notes</h3>

    <!-- Formulaire de recherche avec autocomplétion -->
    <form method="POST" action="" class="search-form">
        <label>
            Rechercher une matière : 
            <input type="text" name="search_matiere" 
                   value="<?php echo htmlspecialchars($data['search_matiere'] ?? ''); ?>" 
                   id="search-matiere" autocomplete="off">
        </label>
        <button type="submit">Rechercher</button>
        <div id="suggestions" class="suggestions"></div>
    </form>

    <!-- Debug : Afficher le contenu de $data -->
    <?php if (isset($_GET['debug'])): ?>
        <div style="background: #ffffcc; padding: 10px; margin: 10px 0; border: 1px solid #ccc;">
            <strong>Debug - Données :</strong>
            <pre><?php print_r($data); ?></pre>
        </div>
    <?php endif; ?>

    <!-- Liste des notes -->
    <h4>Liste des Notes</h4>
    <?php if (isset($data['notes']) && is_array($data['notes']) && count($data['notes']) > 0): ?>
        <ul class="notes-list">
        <?php foreach ($data['notes'] as $note): ?>
            <li class="note-item">
                <?php 
                echo htmlspecialchars($note['nom_matiere'] ?? 'Matière inconnue') . " : " . 
                     htmlspecialchars($note['valeur'] ?? '0') . " / 20"; 
                ?>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="no-data">
            <?php if (isset($data['search_matiere']) && !empty($data['search_matiere'])): ?>
                Aucune note trouvée pour "<?php echo htmlspecialchars($data['search_matiere']); ?>".
            <?php else: ?>
                Aucune note enregistrée.
            <?php endif; ?>
        </p>
    <?php endif; ?>

    <!-- Moyenne générale -->
    <div class="moyenne-generale">
        <h4>Moyenne Générale</h4>
        <p><?php echo isset($data['moyenne_generale']) ? number_format($data['moyenne_generale'], 2) : '0.00'; ?> / 20</p>
    </div>

    <!-- Moyennes par matière -->
    <div class="moyennes-matiere">
        <h4>Moyennes par Matière</h4>
        <?php if (isset($data['moyennes_par_matiere']) && is_array($data['moyennes_par_matiere']) && !empty($data['moyennes_par_matiere'])): ?>
            <ul class="moyennes-list">
            <?php foreach ($data['moyennes_par_matiere'] as $moyenne): ?>
                <li class="moyenne-item">
                    <?php echo htmlspecialchars($moyenne['nom'] ?? 'Matière inconnue') . " : " . 
                              number_format($moyenne['moyenne'] ?? 0, 2) . " / 20"; ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-data">Aucune moyenne calculée.</p>
        <?php endif; ?>
    </div>

    <p style="text-align: center; margin-top: 30px;">
        <a href="/tp_gestion_cours/logout.php" style="color: #007cba; text-decoration: none;">→ Déconnexion</a>
    </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-matiere');
    const suggestionsDiv = document.getElementById('suggestions');

    if (searchInput && suggestionsDiv) {
        searchInput.addEventListener('input', function() {
            const term = this.value;
            if (term.length < 2) {
                suggestionsDiv.style.display = 'none';
                return;
            }

            fetch('/tp_gestion_cours/api/suggest_matieres.php?term=' + encodeURIComponent(term))
                .then(response => response.json())
                .then(data => {
                    suggestionsDiv.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(suggestion => {
                            const div = document.createElement('div');
                            div.textContent = suggestion;
                            div.addEventListener('click', function() {
                                searchInput.value = suggestion;
                                suggestionsDiv.style.display = 'none';
                                document.querySelector('.search-form').submit();
                            });
                            suggestionsDiv.appendChild(div);
                        });
                        suggestionsDiv.style.display = 'block';
                    } else {
                        suggestionsDiv.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    suggestionsDiv.style.display = 'none';
                });
        });

        // Fermer les suggestions si on clique à l'extérieur
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.style.display = 'none';
            }
        });
    }
});
</script>