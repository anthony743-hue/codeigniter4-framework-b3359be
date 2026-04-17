
-- Trigger pour mettre à jour updated_at automatiquement
CREATE OR REPLACE FUNCTION update_livre_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_livre_updated_at
BEFORE UPDATE ON livre
FOR EACH ROW
EXECUTE FUNCTION update_livre_updated_at();

CREATE OR REPLACE FUNCTION update_emprunt_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_emprunt_updated_at
BEFORE UPDATE ON emprunt
FOR EACH ROW
EXECUTE FUNCTION update_emprunt_updated_at();