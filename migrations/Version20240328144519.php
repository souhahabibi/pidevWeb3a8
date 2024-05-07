<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240328144519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY abonnement_ibfk_1');
        $this->addSql('ALTER TABLE competition DROP FOREIGN KEY fk_organisateur_id');
        $this->addSql('ALTER TABLE coupon DROP FOREIGN KEY fk_userid');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY fk_cours');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY materiel_ibfk_1');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY fk_fournisseur_id');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY fk_client_id');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY fk_competition_id');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE competition');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE organisateur');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE salle');
        $this->addSql('ALTER TABLE ingredient_meal DROP FOREIGN KEY ingredient_meal_ibfk_1');
        $this->addSql('DROP INDEX ingredient_id ON ingredient_meal');
        $this->addSql('ALTER TABLE ingredient_meal DROP FOREIGN KEY ingredient_meal_ibfk_2');
        $this->addSql('ALTER TABLE ingredient_meal ADD id INT AUTO_INCREMENT NOT NULL, ADD ingredients_id INT DEFAULT NULL, DROP ingredient_id, CHANGE meal_id meal_id INT DEFAULT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE ingredient_meal ADD CONSTRAINT FK_C0A73E0A3EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id)');
        $this->addSql('CREATE INDEX IDX_C0A73E0A3EC4DCE ON ingredient_meal (ingredients_id)');
        $this->addSql('DROP INDEX meal_id ON ingredient_meal');
        $this->addSql('CREATE INDEX IDX_C0A73E0A639666D6 ON ingredient_meal (meal_id)');
        $this->addSql('ALTER TABLE ingredient_meal ADD CONSTRAINT ingredient_meal_ibfk_2 FOREIGN KEY (meal_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE regime DROP FOREIGN KEY fk_useridregime');
        $this->addSql('ALTER TABLE regime CHANGE clientId clientId INT DEFAULT NULL');
        $this->addSql('DROP INDEX client ON regime');
        $this->addSql('CREATE INDEX IDX_AA864A7CEA1CE9BE ON regime (clientId)');
        $this->addSql('ALTER TABLE regime ADD CONSTRAINT fk_useridregime FOREIGN KEY (clientId) REFERENCES user (id)');
        $this->addSql('ALTER TABLE usersmeal DROP FOREIGN KEY fk_mealid');
        $this->addSql('ALTER TABLE usersmeal DROP FOREIGN KEY fk_usermealid');
        $this->addSql('DROP INDEX fk_usermealid ON usersmeal');
        $this->addSql('DROP INDEX fk_mealid ON usersmeal');
        $this->addSql('ALTER TABLE usersmeal ADD mealid_id INT DEFAULT NULL, ADD userid_id INT DEFAULT NULL, DROP userId, DROP mealId');
        $this->addSql('ALTER TABLE usersmeal ADD CONSTRAINT FK_E046005038BF3DB8 FOREIGN KEY (mealid_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE usersmeal ADD CONSTRAINT FK_E046005058E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E046005038BF3DB8 ON usersmeal (mealid_id)');
        $this->addSql('CREATE INDEX IDX_E046005058E0A285 ON usersmeal (userid_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, montant INT NOT NULL, duree INT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, FK_idSalle INT NOT NULL, INDEX FK_idSalle (FK_idSalle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE competition (id INT AUTO_INCREMENT NOT NULL, fk_organisateur_id INT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date DATE NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, capacite INT NOT NULL, videoURL VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_organisateur_id (fk_organisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE coupon (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nomSociete VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, code INT NOT NULL, valeur INT NOT NULL, dateExpiration VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_userid (user_id), UNIQUE INDEX code (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, niveau VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, commentaire VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, planning VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE exercice (id INT NOT NULL, idE INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, etape TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_cours (id), PRIMARY KEY(idE)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE fournisseur (id_fournisseur INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, numero INT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id_fournisseur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, age INT NOT NULL, quantite INT NOT NULL, prix INT NOT NULL, FK_idSalle INT NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX FK_idSalle (FK_idSalle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE organisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, numero VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE personne (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, age INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE produit (id_produit INT AUTO_INCREMENT NOT NULL, id_fournisseur INT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, quantite INT NOT NULL, cout DOUBLE PRECISION NOT NULL, date_expiration DATE NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_fournisseur_id (id_fournisseur), PRIMARY KEY(id_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, fk_client_id INT NOT NULL, fk_competition_id INT NOT NULL, score INT NOT NULL, INDEX fk_competition_id (fk_competition_id), INDEX fk_client_id (fk_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, lieu VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT abonnement_ibfk_1 FOREIGN KEY (FK_idSalle) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE competition ADD CONSTRAINT fk_organisateur_id FOREIGN KEY (fk_organisateur_id) REFERENCES organisateur (id)');
        $this->addSql('ALTER TABLE coupon ADD CONSTRAINT fk_userid FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT fk_cours FOREIGN KEY (id) REFERENCES cours (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT materiel_ibfk_1 FOREIGN KEY (FK_idSalle) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT fk_fournisseur_id FOREIGN KEY (id_fournisseur) REFERENCES fournisseur (id_fournisseur)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_client_id FOREIGN KEY (fk_client_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_competition_id FOREIGN KEY (fk_competition_id) REFERENCES competition (id)');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE ingredient_meal MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE ingredient_meal DROP FOREIGN KEY FK_C0A73E0A3EC4DCE');
        $this->addSql('DROP INDEX IDX_C0A73E0A3EC4DCE ON ingredient_meal');
        $this->addSql('DROP INDEX `primary` ON ingredient_meal');
        $this->addSql('ALTER TABLE ingredient_meal DROP FOREIGN KEY FK_C0A73E0A639666D6');
        $this->addSql('ALTER TABLE ingredient_meal ADD ingredient_id INT NOT NULL, DROP id, DROP ingredients_id, CHANGE meal_id meal_id INT NOT NULL');
        $this->addSql('ALTER TABLE ingredient_meal ADD CONSTRAINT ingredient_meal_ibfk_1 FOREIGN KEY (ingredient_id) REFERENCES ingredients (id)');
        $this->addSql('CREATE INDEX ingredient_id ON ingredient_meal (ingredient_id)');
        $this->addSql('DROP INDEX idx_c0a73e0a639666d6 ON ingredient_meal');
        $this->addSql('CREATE INDEX meal_id ON ingredient_meal (meal_id)');
        $this->addSql('ALTER TABLE ingredient_meal ADD CONSTRAINT FK_C0A73E0A639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE regime DROP FOREIGN KEY FK_AA864A7CEA1CE9BE');
        $this->addSql('ALTER TABLE regime CHANGE clientId clientId INT NOT NULL');
        $this->addSql('DROP INDEX idx_aa864a7cea1ce9be ON regime');
        $this->addSql('CREATE INDEX client ON regime (clientId)');
        $this->addSql('ALTER TABLE regime ADD CONSTRAINT FK_AA864A7CEA1CE9BE FOREIGN KEY (clientId) REFERENCES user (id)');
        $this->addSql('ALTER TABLE usersmeal DROP FOREIGN KEY FK_E046005038BF3DB8');
        $this->addSql('ALTER TABLE usersmeal DROP FOREIGN KEY FK_E046005058E0A285');
        $this->addSql('DROP INDEX IDX_E046005038BF3DB8 ON usersmeal');
        $this->addSql('DROP INDEX IDX_E046005058E0A285 ON usersmeal');
        $this->addSql('ALTER TABLE usersmeal ADD userId INT NOT NULL, ADD mealId INT NOT NULL, DROP mealid_id, DROP userid_id');
        $this->addSql('ALTER TABLE usersmeal ADD CONSTRAINT fk_mealid FOREIGN KEY (mealId) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE usersmeal ADD CONSTRAINT fk_usermealid FOREIGN KEY (userId) REFERENCES user (id)');
        $this->addSql('CREATE INDEX fk_usermealid ON usersmeal (userId)');
        $this->addSql('CREATE INDEX fk_mealid ON usersmeal (mealId)');
    }
}
