<?php
/**
 * Page de détails d'un livre
 * Affiche les informations complètes d'un livre avec son statut et ses emprunts
 *
 * Variables attendues du contrôleur:
 * - $livre : Objet livre (LivreModel)
 * - $status : Objet statut (StatusModel)
 * - $categorie : Objet catégorie (CategorieModel)
 * - $dernierEmprunt : Dernier emprunt du livre (EmpruntModel) ou null
 * - $utilisateur : Utilisateur actuellement connecté (optionnel)
 */
var_dump($livre);
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= esc($livre->titre ?? 'Détails du livre') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<nav class="mb-4">
    <a href="<?= base_url('livres') ?>" class="text-decoration-none text-dark small">← Retour au catalogue</a>
</nav>

<div class="row align-items-start g-5">
    <div class="col-md-5 col-lg-4">
        <div class="bg-light rounded shadow-sm d-flex align-items-center justify-content-center" style="aspect-ratio: 2/3;">
            <?php if (!empty($livre->image_url)): ?>
                <img src="<?= base_url($livre->image_url) ?>" class="img-fluid rounded shadow" alt="Couverture">
            <?php else: ?>
                <span class="text-muted h1 fw-bold text-center p-3"><?= esc($livre->titre) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-7 col-lg-8">
        <div class="mb-3">
            <span class="badge bg-light text-dark border"><?= esc($categorie->nom) ?></span>
            <span class="text-muted ms-2 small"><?= date('Y', strtotime($livre->date_publication)) ?></span>
        </div>

        <h1 class="display-4 fw-bold mb-2"><?= esc($livre->titre) ?></h1>
        <h3 class="text-muted mb-4 fs-4">par <?= esc($livre->auteur) ?></h3>

        <div class="my-4 py-3 border-top border-bottom">
            <p class="lead text-secondary">
                <?= esc($livre->resume ?? 'Aucun résumé disponible.') ?>
            </p>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <small class="text-muted d-block">ISBN</small>
                <strong><?= esc($livre->isbn) ?></strong>
            </div>
            <div class="col-6">
                <small class="text-muted d-block">Statut</small>
                <!-- <strong class="<?= $livre->isdisponible ? 'text-success' : 'text-danger' ?>">
                    <?= $livre->isdisponible ? 'Disponible immédiatement' : 'Actuellement emprunté' ?>
                </strong> -->
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex">
            <?php if ($livre->isdisponible): ?>
                <button class="btn btn-primary px-5 py-2">Emprunter</button>
            <?php else: ?>
                <button class="btn btn-outline-secondary px-5 py-2" disabled>Indisponible</button>
            <?php endif; ?>
            <button class="btn btn-link text-dark">Ajouter aux favoris</button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/livre-detail.js') ?>"></script>
<?= $this->endSection() ?>