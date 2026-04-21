<?php
/**
 * Page de retour d'un livre
 * Formulaire pour retourner un livre emprunté
 *
 * Variables attendues du contrôleur:
 * - $livre : Objet livre (LivreModel)
 * - $error : Message d'erreur (optionnel)
 */
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Retourner un livre
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <nav class="mb-4">
            <a href="<?= base_url('livres') ?>" class="text-decoration-none text-dark small">← Retour au catalogue</a>
        </nav>

        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">Retourner : <?= esc($livre->titre) ?></h4>
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
                        <span class="badge bg-danger">Emprunté</span>
                    </p>
                </div>

                <div class="alert alert-info">
                    <strong>Information :</strong> Confirmer le retour du livre pour le remettre à disposition.
                </div>

                <form action="<?= base_url('livres/retour/' . $livre->id) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="date_retour" class="form-label">Date de retour</label>
                        <input type="date" class="form-control" id="date_retour" name="date_retour"
                               value="<?= date('Y-m-d') ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmer_retour" name="confirmer_retour" required>
                            <label class="form-check-label" for="confirmer_retour">
                                Je confirme le retour du livre et accepte que son statut passe à "Disponible"
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Confirmer le retour</button>
                        <a href="<?= base_url('livres/detail/' . $livre->id) ?>" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>