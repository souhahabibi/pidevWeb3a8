<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240427155034 extends AbstractMigration
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
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY fk_user_cours');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY fk_user_exercice');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY fk_cours');
        $this->addSql('ALTER TABLE ingredient_meal DROP FOREIGN KEY ingredient_meal_ibfk_1');
        $this->addSql('ALTER TABLE ingredient_meal DROP FOREIGN KEY ingredient_meal_ibfk_2');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY materiel_ibfk_1');
        $this->addSql('ALTER TABLE regime DROP FOREIGN KEY fk_useridregime');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY fk_client_id');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY fk_competition_id');
        $this->addSql('ALTER TABLE usersmeal DROP FOREIGN KEY fk_usermealid');
        $this->addSql('ALTER TABLE usersmeal DROP FOREIGN KEY fk_mealid');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE competition');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE ingredients');
        $this->addSql('DROP TABLE ingredient_meal');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE meal');
        $this->addSql('DROP TABLE organisateur');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE regime');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP TABLE usersmeal');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY fk_user_id');
        $this->addSql('DROP INDEX fk_user_id ON commande');
        $this->addSql('ALTER TABLE commande ADD id_user_id INT DEFAULT NULL, DROP id_user');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D79F37AE5 ON commande (id_user_id)');
        $this->addSql('ALTER TABLE fournisseur CHANGE nom nom VARCHAR(150) NOT NULL, CHANGE prenom prenom VARCHAR(150) NOT NULL, CHANGE type type VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY fk_fournisseur_id');
        $this->addSql('ALTER TABLE produit CHANGE id_fournisseur id_fournisseur INT DEFAULT NULL, CHANGE nom nom VARCHAR(150) NOT NULL, CHANGE cout cout INT NOT NULL, CHANGE description description VARCHAR(150) NOT NULL, CHANGE image image VARCHAR(150) NOT NULL');
        $this->addSql('DROP INDEX fk_fournisseur_id ON produit');
        $this->addSql('CREATE INDEX IDX_29A5EC272E8C07C5 ON produit (id_fournisseur)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT fk_fournisseur_id FOREIGN KEY (id_fournisseur) REFERENCES fournisseur (id_fournisseur)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, montant INT NOT NULL, duree INT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, FK_idSalle INT NOT NULL, INDEX FK_idSalle (FK_idSalle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE competition (id INT AUTO_INCREMENT NOT NULL, fk_organisateur_id INT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date DATE NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, capacite INT NOT NULL, videoURL VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_organisateur_id (fk_organisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE coupon (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nomSociete VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, code INT NOT NULL, valeur INT NOT NULL, dateExpiration VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, UNIQUE INDEX code (code), INDEX fk_userid (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, niveau VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, commentaire VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, planning VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_user_cours (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE exercice (id INT NOT NULL, user_id INT NOT NULL, idE INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, etape TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_cours (id), INDEX fk_user_exercice (user_id), PRIMARY KEY(idE)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ingredients (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, calories INT NOT NULL, total_fat INT NOT NULL, protein INT NOT NULL, imgUrl VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ingredient_meal (id INT AUTO_INCREMENT NOT NULL, ingredient_id INT NOT NULL, meal_id INT NOT NULL, INDEX ingredient_id (ingredient_id), INDEX meal_id (meal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, age INT NOT NULL, quantite INT NOT NULL, prix INT NOT NULL, FK_idSalle INT NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX FK_idSalle (FK_idSalle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE meal (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image_url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Recipe VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Calories INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE organisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, numero VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE personne (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, age INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE regime (id INT AUTO_INCREMENT NOT NULL, startDate DATE NOT NULL, endDate DATE NOT NULL, Duration INT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, goal VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, clientId INT NOT NULL, INDEX client (clientId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, fk_client_id INT NOT NULL, fk_competition_id INT NOT NULL, score INT NOT NULL, INDEX fk_client_id (fk_client_id), INDEX fk_competition_id (fk_competition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, lieu VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE usersmeal (id INT AUTO_INCREMENT NOT NULL, userId INT NOT NULL, mealId INT NOT NULL, INDEX fk_usermealid (userId), INDEX fk_mealid (mealId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT abonnement_ibfk_1 FOREIGN KEY (FK_idSalle) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE competition ADD CONSTRAINT fk_organisateur_id FOREIGN KEY (fk_organisateur_id) REFERENCES organisateur (id)');
        $this->addSql('ALTER TABLE coupon ADD CONSTRAINT fk_userid FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT fk_user_cours FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT fk_user_exercice FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT fk_cours FOREIGN KEY (id) REFERENCES cours (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient_meal ADD CONSTRAINT ingredient_meal_ibfk_1 FOREIGN KEY (ingredient_id) REFERENCES ingredients (id)');
        $this->addSql('ALTER TABLE ingredient_meal ADD CONSTRAINT ingredient_meal_ibfk_2 FOREIGN KEY (meal_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT materiel_ibfk_1 FOREIGN KEY (FK_idSalle) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE regime ADD CONSTRAINT fk_useridregime FOREIGN KEY (clientId) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_client_id FOREIGN KEY (fk_client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_competition_id FOREIGN KEY (fk_competition_id) REFERENCES competition (id)');
        $this->addSql('ALTER TABLE usersmeal ADD CONSTRAINT fk_usermealid FOREIGN KEY (userId) REFERENCES user (id)');
        $this->addSql('ALTER TABLE usersmeal ADD CONSTRAINT fk_mealid FOREIGN KEY (mealId) REFERENCES meal (id)');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D79F37AE5');
        $this->addSql('DROP INDEX IDX_6EEAA67D79F37AE5 ON commande');
        $this->addSql('ALTER TABLE commande ADD id_user INT NOT NULL, DROP id_user_id');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT fk_user_id FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('CREATE INDEX fk_user_id ON commande (id_user)');
        $this->addSql('ALTER TABLE fournisseur CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE type type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC272E8C07C5');
        $this->addSql('ALTER TABLE produit CHANGE id_fournisseur id_fournisseur INT NOT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE cout cout DOUBLE PRECISION NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX idx_29a5ec272e8c07c5 ON produit');
        $this->addSql('CREATE INDEX fk_fournisseur_id ON produit (id_fournisseur)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC272E8C07C5 FOREIGN KEY (id_fournisseur) REFERENCES fournisseur (id_fournisseur)');
    }
}
