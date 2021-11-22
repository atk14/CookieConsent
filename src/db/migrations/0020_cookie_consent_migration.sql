-- migration from package atk14/cookie-consent

CREATE SEQUENCE seq_cookie_consent_categories START WITH 11;
CREATE TABLE cookie_consent_categories (
	id INT PRIMARY KEY DEFAULT NEXTVAL('seq_cookie_consent_categories'),
	--
	code VARCHAR(255) NOT NULL,
	--
	active BOOLEAN NOT NULL DEFAULT TRUE,
	necessary BOOLEAN NOT NULL DEFAULT FALSE,
	cookies_regexp VARCHAR(255), -- e.g. '/^ab_/', '/^(gm_tracking|gm_identity)/'
	version INT NOT NULL DEFAULT 1, -- when something important is changed in this category, version needs to be increased
	--
	rank INTEGER DEFAULT 999 NOT NULL,
	--
	created_by_user_id INT,
	updated_by_user_id INT,
	--
	created_at TIMESTAMP NOT NULL DEFAULT NOW(),
	updated_at TIMESTAMP,
	--
	CONSTRAINT unq_cookieconsentcategories_code UNIQUE (code),
	CONSTRAINT fk_cookieconsentcategories_cr_users FOREIGN KEY (created_by_user_id) REFERENCES users,
	CONSTRAINT fk_cookieconsentcategories_upd_users FOREIGN KEY (updated_by_user_id) REFERENCES users
);
