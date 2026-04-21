<?php
/**
 * Page d'emprunt d'un livre
 * Formulaire pour emprunter un livre
 *
 * Variables attendues du contrôleur:
 * - $livre : Objet livre (LivreModel)
 * - $error : Message d'erreur (optionnel)
 */
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Emprunter un livre
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <nav class="mb-4">
            <a href="<?= base_url('livres') ?>" class="text-decoration-none text-dark small">← Retour au catalogue</a>
        </nav>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Emprunter : <?= esc($livre->titre) ?></h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?= esc($error) ?>
                    </div>
                <?php endif; ?>

                <div class="mb-4">
                    <h5 class="text-muted">Détails du livre</h5>
                    <p><strong>Auteur :</strong> <?= esc($livre->auteur) ?></p>
                    <p><strong>ISBN :</strong> <?= esc($livre->isbn) ?></p>
                    <p><strong>Statut :</strong>
                        <span class="badge bg-success">Disponible</span>
                    </p>
                </div>

                <form action="<?= base_url('livres/pret/' . $livre->id) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="etudiant_id" class="form-label">Étudiant *</label>
                        <select class="form-select" id="etudiant_id" name="etudiant_id" required>
                            <option value="">Sélectionner un étudiant</option>
                            <!-- TODO: Charger les étudiants depuis la base de données -->
                            <option value="1">Jean Dupont</option>
                            <option value="2">Marie Martin</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date_emprunt" class="form-label">Date d'emprunt</label>
                        <input type="date" class="form-control" id="date_emprunt" name="date_emprunt"
                               value="<?= date('Y-m-d') ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="date_retour_prevue" class="form-label">Date de retour prévue *</label>
                        <input type="date" class="form-control" id="date_retour_prevue" name="date_retour_prevue"
                               value="<?= date('Y-m-d', strtotime('+15 days')) ?>" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Confirmer l'emprunt</button>
                        <a href="<?= base_url('livres/detail/' . $livre->id) ?>" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>