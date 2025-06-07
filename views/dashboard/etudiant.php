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

.dashboard-title {
    text-align: center;
    color: #007cba;
}

.section-matieres, .section-notes {
    margin-bottom: 30px;
}

.section-title {
    font-size: 1.5em;
    margin-bottom: 15px;
    color: #333;
}

.search-form {
    background: #f5f5f5;
    padding: 15px;
    border-radius: 5px;
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

.search-form select {
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
}

.matieres-list {
    list-style: none;
    padding: 0;
}

.matiere-item {
    background: #f9f9f9;
    padding: 10px;
    margin: 5px 0;
    border-left: 4px solid #007cba;
}

.note-value {
    float: right;
    font-size: 1.2em;
    font-weight: bold;
}

.stats-container {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.stat-card {
    background: #e8f4f8;
    padding: 15px;
    border-radius: 5px;
    text-align: center;
    flex: 1;
    margin: 0 10px;
}

.stat-number {
    font-size: 2em;
    font-weight: bold;
    color: #007cba;
    margin: 0;
}

.stat-label {
    color: #666;
    font-size: 0.9em;
}

.no-data {
    color: #666;
    font-style: italic;
    text-align: center;
    padding: 20px;
}

.btn-submit {
    padding: 8px 15px;
    background: #007cba;
    color: white;
    border: none;
    text-decoration: none;
    border-radius: 3px;
}

.btn-logout {
    display: block;
    text-align: center;
    padding: 10px;
    background: #d9534f;
    color: white;
    text-decoration: none;
    border-radius: 3px;
    margin-top: 20px;
}

.btn-logout:hover {
    background: #c9302c;
}
</style>

<div class="dashboard-container">
    <h3 class="dashboard-title">Tableau de Bord Étudiant</h3>

    <!-- Formulaire de recherche avec liste déroulante -->
    <div class="section-matieres">
        <h4 class="section-title">Filtrer par Matière</h4>
        <form method="POST" action="" class="search-form">
            <div class="form-group">
                <label for="search-matiere">Sélectionner une matière :</label>
                <select name="search_matiere" id="search-matiere">
                    <option value="">Toutes les matières</option>
                    <?php if (isset($data['matieres']) && is_array($data['matieres'])): ?>
                        <?php foreach ($data['matieres'] as $matiere): ?>
                            <option value="<?php echo htmlspecialchars($matiere['nom']); ?>" 
                                    <?php echo (isset($data['search_matiere']) && $data['search_matiere'] === $matiere['nom']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($matiere['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <button type="submit" class="btn-submit">Filtrer</button>
            <?php if (!empty($data['search_matiere'])): ?>
                <a href="dashboard.php" class="btn-submit">Afficher toutes les notes</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Section des Notes -->
    <div class="section-notes">
        <h4 class="section-title">
            <?php if (!empty($data['search_matiere'])): ?>
                Notes pour "<?php echo htmlspecialchars($data['search_matiere']); ?>"
            <?php else: ?>
                Toutes vos Notes
            <?php endif; ?>
        </h4>

        <?php if (isset($data['notes']) && is_array($data['notes']) && count($data['notes']) > 0): ?>
            <ul class="matieres-list">
                <?php foreach ($data['notes'] as $note): ?>
                    <li class="matiere-item">
                        <strong><?php echo htmlspecialchars($note['nom_matiere'] ?? 'Matière inconnue'); ?></strong>
                        <span class="note-value">
                            <?php echo htmlspecialchars($note['valeur'] ?? '0'); ?> / 20
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-data">
                <?php if (!empty($data['search_matiere'])): ?>
                    Aucune note trouvée pour "<?php echo htmlspecialchars($data['search_matiere']); ?>".
                    <br><a href="dashboard.php" class="btn-submit">Voir toutes vos notes</a>
                <?php else: ?>
                    Aucune note n'a encore été enregistrée pour vous.
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <!-- Stats Container -->
        <div class="stats-container">
            <!-- Moyenne Générale -->
            <div class="stat-card">
                <div class="stat-number">
                    <?php echo isset($data['moyenne_generale']) ? number_format($data['moyenne_generale'], 2) : '0.00'; ?>
                </div>
                <div class="stat-label">Moyenne Générale</div>
            </div>

            <!-- Nombre de Notes -->
            <div class="stat-card">
                <div class="stat-number">
                    <?php echo isset($data['notes']) ? count($data['notes']) : '0'; ?>
                </div>
                <div class="stat-label">Notes Totales</div>
            </div>
        </div>

        <!-- Moyennes par matière -->
        <h4 class="section-title">Moyennes par Matière</h4>
        <?php if (isset($data['moyennes_par_matiere']) && is_array($data['moyennes_par_matiere']) && !empty($data['moyennes_par_matiere'])): ?>
            <ul class="matieres-list">
                <?php foreach ($data['moyennes_par_matiere'] as $moyenne): ?>
                    <li class="matiere-item">
                        <strong><?php echo htmlspecialchars($moyenne['nom'] ?? 'Matière inconnue'); ?></strong>
                        <span class="note-value">
                            <?php echo number_format($moyenne['moyenne'] ?? 0, 2); ?> / 20
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-data">Aucune moyenne calculée.</p>
        <?php endif; ?>
    </div>

    <a href="/tp_gestion_cours/logout.php" class="btn-logout">Déconnexion</a>
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