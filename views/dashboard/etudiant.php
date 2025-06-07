<?php
?>
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

    <!-- Liste des notes -->
    <h4>Liste des Notes</h4>
    <?php if (isset($data['notes']) && count($data['notes']) > 0): ?>
        <ul class="notes-list">
        <?php foreach ($data['notes'] as $note): ?>
            <li class="note-item">
                <?php echo htmlspecialchars($note['nom_matiere']) . " : " . 
                          htmlspecialchars($note['valeur']) . " / 20"; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="no-data">Aucune note enregistrée.</p>
    <?php endif; ?>

    <!-- Moyenne générale -->
    <div class="moyenne-generale">
        <h4>Moyenne Générale</h4>
        <p><?php echo isset($data['moyenne_generale']) ? number_format($data['moyenne_generale'], 2) : '0.00'; ?> / 20</p>
    </div>

    <!-- Moyennes par matière -->
    <div class="moyennes-matiere">
        <h4>Moyennes par Matière</h4>
        <?php if (!empty($data['moyennes_par_matiere'])): ?>
            <ul class="moyennes-list">
            <?php foreach ($data['moyennes_par_matiere'] as $moyenne): ?>
                <li class="moyenne-item">
                    <?php echo htmlspecialchars($moyenne['nom']) . " : " . 
                              number_format($moyenne['moyenne'], 2) . " / 20"; ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-data">Aucune moyenne calculée.</p>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-matiere');
    const suggestionsDiv = document.getElementById('suggestions');

    searchInput.addEventListener('input', function() {
        const term = this.value;
        if (term.length < 2) {
            suggestionsDiv.innerHTML = '';
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
                            suggestionsDiv.innerHTML = '';
                            document.querySelector('.search-form').submit();
                        });
                        suggestionsDiv.appendChild(div);
                    });
                }
            })
            .catch(error => console.error('Erreur:', error));
    });

    // Fermer les suggestions si on clique à l'extérieur
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
            suggestionsDiv.innerHTML = '';
        }
    });
});
</script>