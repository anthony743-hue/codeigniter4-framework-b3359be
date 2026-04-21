<?php
/**
 * Page de liste des livres
 * Affiche le catalogue des livres avec vue grille et tableau
 *
 * Variables attendues du contrôleur:
 * - $livres : Array d'objets livre (LivreModel)
 */
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Liste des livres
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
    $livres = $livres ?? [];
    $categories = $categories ?? [];
    $title_filter = $title_filter ?? '';
    $categorie_filter = $categorie_filter ?? '';
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Rechercher des livres</h5>
                <form method="get" action="<?= base_url('livres') ?>" class="row g-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Titre ou auteur</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?= esc($title_filter) ?>" placeholder="Rechercher...">
                    </div>
                    <div class="col-md-4">
                        <label for="categorie" class="form-label">Catégorie</label>
                        <select class="form-select" id="categorie" name="categorie">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>" <?= $categorie_filter == $cat->id ? 'selected' : '' ?>>
                                    <?= esc($cat->nom) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Rechercher</button>
                        <a href="<?= base_url('livres') ?>" class="btn btn-outline-secondary">Réinitialiser</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Liste des livres (<?= count($livres) ?>)</h5>
                <a href="<?= base_url('livres/add') ?>" class="btn btn-success">Ajouter un livre</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Titre</th>
                                <th>Auteur</th>
                                <th>Année</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($livres as $livre): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('livres/detail/' . $livre->id) ?>" class="text-decoration-none fw-bold">
                                            <?= esc($livre->titre) ?>
                                        </a>
                                    </td>
                                    <td><?= esc($livre->auteur) ?></td>
                                    <td><?= date('Y', strtotime($livre->date_publication)) ?></td>
                                    <td>
                                        <span class="badge <?= $livre->status_id == 1 ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $livre->status_id == 1 ? 'Disponible' : 'Prêté' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?php if ($livre->status_id == 1): ?>
                                                <!-- Formulaire d'emprunt inline -->
                                                <form method="post" action="<?= base_url('livres/pret/' . $livre->id) ?>" class="d-inline">
                                                <?= csrf_field() ?>    
                                                <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control form-control-sm" 
                                                               name="emprunteur_nom" placeholder="Nom emprunteur" required>
                                                        <button type="submit" class="btn btn-success btn-sm">Prêter</button>
                                                    </div>
                                                </form>
                                            <?php else: ?>
                                                <!-- Bouton de retour -->
                                                <form method="post" action="<?= base_url('livres/retour/' . $livre->id) ?>" class="d-inline">
                                                <?= csrf_field() ?>    
                                                <button type="submit" class="btn btn-warning btn-sm">Retourner</button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <!-- Bouton supprimer avec confirmation -->
                                            <form method="post" action="<?= base_url('livres/delete/' . $livre->id) ?>" 
                                                  class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')">
                                                <?= csrf_field() ?>
                                                  <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pagination si nécessaire -->
<?php if (isset($pager) && $pager->getPageCount() > 1): ?>
<div class="row mt-4">
    <div class="col-12">
        <nav aria-label="Pagination des livres">
            <ul class="pagination justify-content-center">
                <?php if ($pager->hasPrevious()): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= $pager->getPreviousPage() ?>" aria-label="Précédent">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php foreach ($pager->links() as $link): ?>
                    <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
                    </li>
                <?php endforeach; ?>

                <?php if ($pager->hasNext()): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= $pager->getNextPage() ?>" aria-label="Suivant">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
