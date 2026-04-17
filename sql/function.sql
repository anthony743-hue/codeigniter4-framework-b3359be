CREATE OR REPLACE FUNCTION retourner_livre(p_livre_id INTEGER)
RETURNS TABLE (success BOOLEAN, message TEXT) AS $$
DECLARE
    v_emprunt_id INTEGER;
BEGIN
    -- Vérifier que le livre existe
    IF NOT EXISTS (SELECT 1 FROM livre WHERE id = p_livre_id) THEN
        RETURN QUERY SELECT false::boolean, 'Livre introuvable'::TEXT;
        RETURN;
    END IF;

    -- Trouver l'emprunt actif
    SELECT id INTO v_emprunt_id FROM emprunt 
    WHERE livre_id = p_livre_id AND actif = TRUE
    LIMIT 1;

    IF v_emprunt_id IS NULL THEN
        RETURN QUERY SELECT false::boolean, 'Aucun emprunt actif pour ce livre'::TEXT;
        RETURN;
    END IF;

    -- Mettre à jour l'emprunt
    UPDATE emprunt 
    SET 
        date_retour_reel = CURRENT_TIMESTAMP,
        actif = FALSE
    WHERE id = v_emprunt_id;

    -- Mettre à jour la disponibilité du livre
    UPDATE livre 
    SET 
        disponible = TRUE,
        date_derniere_disponibilite = CURRENT_TIMESTAMP
    WHERE id = p_livre_id;

    RETURN QUERY SELECT true::boolean, 'Livre retourné avec succès'::TEXT;
END;
$$ LANGUAGE plpgsql;


-- Fonction : Emprunter un livre
CREATE OR REPLACE FUNCTION emprunter_livre(
    p_livre_id INTEGER, 
    p_utilisateur_id INTEGER,
    p_date_retour_prevu TIMESTAMP DEFAULT (CURRENT_TIMESTAMP + INTERVAL '21 days')
)
RETURNS TABLE (success BOOLEAN, message TEXT) AS $$
DECLARE
    v_livre_exists BOOLEAN;
    v_livre_disponible BOOLEAN;
BEGIN
    -- Vérifier le livre
    SELECT EXISTS(SELECT 1 FROM livre WHERE id = p_livre_id), disponible
    INTO v_livre_exists, v_livre_disponible
    FROM livre WHERE id = p_livre_id;

    IF NOT v_livre_exists THEN
        RETURN QUERY SELECT false::boolean, 'Livre introuvable'::TEXT;
        RETURN;
    END IF;

    IF NOT v_livre_disponible THEN
        RETURN QUERY SELECT false::boolean, 'Livre non disponible'::TEXT;
        RETURN;
    END IF;

    -- Vérifier l'utilisateur
    IF NOT EXISTS (SELECT 1 FROM utilisateur WHERE id = p_utilisateur_id AND actif = TRUE) THEN
        RETURN QUERY SELECT false::boolean, 'Utilisateur introuvable ou inactif'::TEXT;
        RETURN;
    END IF;

    -- Créer l'emprunt
    INSERT INTO emprunt (livre_id, utilisateur_id, date_retour_prevu)
    VALUES (p_livre_id, p_utilisateur_id, p_date_retour_prevu);

    -- Mettre à jour la disponibilité du livre
    UPDATE livre 
    SET disponible = FALSE
    WHERE id = p_livre_id;

    RETURN QUERY SELECT true::boolean, 'Emprunt créé avec succès'::TEXT;
EXCEPTION WHEN OTHERS THEN
    RETURN QUERY SELECT false::boolean, 'Erreur lors de l''emprunt'::TEXT;
END;
$$ LANGUAGE plpgsql;



-- Fonction : Obtenir l'historique d'un livre
CREATE OR REPLACE FUNCTION get_historique_livre(p_livre_id INTEGER)
RETURNS TABLE (
    utilisateur_nom TEXT,
    date_emprunt TIMESTAMP,
    date_retour TIMESTAMP
) AS $$
BEGIN
    RETURN QUERY
    SELECT 
        CONCAT(u.prenom, ' ', u.nom) as utilisateur_nom,
        e.date_emprunt,
        e.date_retour_reel
    FROM emprunt e
    JOIN utilisateur u ON e.utilisateur_id = u.id
    WHERE e.livre_id = p_livre_id
    ORDER BY e.date_emprunt DESC;
END;
$$ LANGUAGE plpgsql;