<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des livres</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
<?php
    $livres = $livres ?? [];

    $formatStatus = static function ($statusId): array {
        return match ((string) $statusId) {
            '1' => ['Disponible', 'status-disponible'],
            '2' => ['Emprunte', 'status-emprunte'],
            default => ['Statut ' . ($statusId ?: 'inconnu'), 'status-inconnu'],
        };
    };
?>

<div class="container py-5 page-shell">
    <section class="hero-card p-4 p-md-5 mb-4">
        <div class="row g-4 align-items-center position-relative">
            <div class="col-lg-8">
                <span class="hero-badge mb-3">Catalogue bibliotheque</span>
                <h1 class="display-6 fw-bold mb-3">Liste des livres</h1>
                <p class="mb-0 fs-5 opacity-75">
                    Consulte les ouvrages disponibles, retrouve rapidement un auteur, une annee ou un statut,
                    et navigue plus facilement dans ton catalogue.
                </p>
            </div>
            <div class="col-lg-4">
                <div class="stat-card p-4 text-center">
                    <div class="text-uppercase small opacity-75 mb-2">Total des livres</div>
                    <div class="display-5 fw-bold"><?= count($livres) ?></div>
                </div>
            </div>
        </div>
    </section>

    <section class="filter-card p-4 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
            <div>
                <h2 class="h4 section-title mb-1">Barre de recherche</h2>
                <p class="text-secondary mb-0">Filtre la liste en direct avec un mot-cle ou un statut.</p>
            </div>
            <span class="text-secondary small" id="resultCount"><?= count($livres) ?> livre(s) affiche(s)</span>
        </div>

        <div class="row g-3">
            <div class="col-md-8">
                <label for="searchInput" class="form-label fw-semibold text-secondary">Recherche</label>
                <input
                    type="text"
                    id="searchInput"
                    class="form-control"
                    placeholder="Titre, auteur, annee..."
                >
            </div>
            <div class="col-md-4">
                <label for="statusFilter" class="form-label fw-semibold text-secondary">Statut</label>
                <select id="statusFilter" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="Disponible">Disponible</option>
                    <option value="Emprunte">Emprunte</option>
                    <option value="Statut">Autres</option>
                </select>
            </div>
        </div>
    </section>

    <section class="table-card p-3 p-md-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="h4 section-title mb-1">Affichage dynamique des livres</h2>
                <p class="text-secondary mb-0">Champs affiches: Titre, Auteur, Annee, Statut.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0" id="booksTable">
                <thead>
                    <tr>
                        <th scope="col">Titre</th>
                        <th scope="col">Auteur</th>
                        <th scope="col">Annee</th>
                        <th scope="col">Statut</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (! empty($livres)): ?>
                    <?php foreach ($livres as $livre): ?>
                        <?php
                            $datePublication = $livre['date_publication'] ?? null;
                            $annee = $datePublication ? date('Y', strtotime($datePublication)) : '-';
                            [$statusLabel, $statusClass] = $formatStatus($livre['status_id'] ?? null);
                        ?>
                        <tr
                            class="book-row"
                            data-search="<?= esc(strtolower(trim(($livre['titre'] ?? '') . ' ' . ($livre['auteur'] ?? '') . ' ' . $annee . ' ' . $statusLabel))) ?>"
                            data-status="<?= esc($statusLabel) ?>"
                        >
                            <td>
                                <div class="book-title"><?= esc($livre['titre'] ?? 'Titre indisponible') ?></div>
                            </td>
                            <td><?= esc($livre['auteur'] ?? 'Auteur inconnu') ?></td>
                            <td><?= esc($annee) ?></td>
                            <td>
                                <span class="status-badge <?= esc($statusClass) ?>">
                                    <?= esc($statusLabel) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-secondary">
                            Aucun livre n'a ete trouve pour le moment.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="empty-state text-center py-5 mt-3" id="emptyState">
            <h3 class="h5 section-title mb-2">Aucun resultat</h3>
            <p class="text-secondary mb-0">Essaie un autre mot-cle ou change le filtre de statut.</p>
        </div>
    </section>
</div>

<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<script>
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resultCount = document.getElementById('resultCount');
    const emptyState = document.getElementById('emptyState');
    const rows = Array.from(document.querySelectorAll('.book-row'));

    function filterBooks() {
        const query = searchInput.value.trim().toLowerCase();
        const status = statusFilter.value;
        let visibleCount = 0;

        rows.forEach((row) => {
            const rowSearch = row.dataset.search || '';
            const rowStatus = row.dataset.status || '';
            const matchesQuery = rowSearch.includes(query);
            const matchesStatus = status === '' || rowStatus.includes(status);
            const isVisible = matchesQuery && matchesStatus;

            row.style.display = isVisible ? '' : 'none';

            if (isVisible) {
                visibleCount += 1;
            }
        });

        resultCount.textContent = visibleCount + ' livre(s) affiche(s)';
        emptyState.style.display = visibleCount === 0 && rows.length > 0 ? 'block' : 'none';
    }

    searchInput.addEventListener('input', filterBooks);
    statusFilter.addEventListener('change', filterBooks);
</script>
</body>
</html>
