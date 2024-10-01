ALTER TABLE cookie_consents ADD COLUMN send_consent_default_command BOOLEAN NOT NULL DEFAULT false;
ALTER TABLE cookie_consents ADD COLUMN send_consent_update_command BOOLEAN NOT NULL DEFAULT false;
