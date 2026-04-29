<?php
/**
 * Page d'ajout d'un livre
 * Formulaire pour ajouter un nouveau livre
 *
 * Variables attendues du contrôleur:
 * - $errors : Array d'erreurs de validation (optionnel)
 */
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Ajouter un livre
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Ajouter un nouveau livre</h4>
            </div>
            <div class="card-body">
                <?php if (isset($errors['general'])): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors['general'] as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('livres/add') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre *</label>
                        <input type="text" class="form-control <?= isset($errors['titre']) ? 'is-invalid' : '' ?>"
                               id="titre" name="titre" value="<?= old('titre') ?>">
                        <?php if (isset($errors['titre'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['titre']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="auteur" class="form-label">Auteur *</label>
                        <input type="text" class="form-control <?= isset($errors['auteur']) ? 'is-invalid' : '' ?>"
                               id="auteur" name="auteur" value="<?= old('auteur') ?>">
                        <?php if (isset($errors['auteur'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['auteur']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN *</label>
                        <input type="text" class="form-control <?= isset($errors['isbn']) ? 'is-invalid' : '' ?>"
                               id="isbn" name="isbn" value="<?= old('isbn') ?>">
                        <?php if (isset($errors['isbn'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['isbn']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="date_publication" class="form-label">Date de publication *</label>
                        <input type="date" class="form-control <?= isset($errors['date_publication']) ? 'is-invalid' : '' ?>"
                               id="date_publication" name="date_publication" value="<?= old('date_publication') ?>">
                        <?php if (isset($errors['date_publication'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['date_publication']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="categorie_id" class="form-label">Catégorie</label>
                        <select class="form-select" id="categorie_id" name="categorie_id">
                            <option value="">Sélectionner une catégorie</option>
                            <?php if (isset($categories) && is_array($categories)): ?>
                                <?php foreach ($categories as $categorie): ?>
                                    <option value="<?= $categorie->id ?>" <?= old('categorie_id') == $categorie->id ? 'selected' : '' ?>>
                                        <?= esc($categorie->nom) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="resume" class="form-label">Résumé</label>
                        <textarea class="form-control" id="resume" name="resume" rows="4"><?= old('resume') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image_couverture" class="form-label">Image de couverture</label>
                        <input type="file" class="form-control <?= isset($errors['image_couverture']) ? 'is-invalid' : '' ?>"
                               id="image_couverture" name="image_couverture" accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">Formats acceptés : JPEG, PNG, WEBP. Taille maximale : 2 Mo.</div>
                        <?php if (isset($errors['image_couverture'])): ?>
                            <div class="invalid-feedback d-block"><?= esc($errors['image_couverture']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Ajouter le livre</button>
                        <a href="<?= base_url('livres') ?>" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>