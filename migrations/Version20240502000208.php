<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240502000208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fournisseur ADD id INT AUTO_INCREMENT NOT NULL, DROP id_fournisseur, CHANGE nom nom VARCHAR(150) NOT NULL, CHANGE prenom prenom VARCHAR(150) NOT NULL, CHANGE type type VARCHAR(150) NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE ingredient_meal DROP FOREIGN KEY ingredient_meal_ibfk_1');
        $this->addSql('DROP INDEX ingredient_id ON ingredient_meal');
        $this->addSql('ALTER TABLE ingredient_meal DROP FOREIGN KEY ingredient_meal_ibfk_2');
        $this->addSql('ALTER TABLE ingredient_meal ADD id INT AUTO_INCREMENT NOT NULL, ADD ingredients_id INT DEFAULT NULL, DROP ingredient_id, CHANGE meal_id meal_id INT DEFAULT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE ingredient_meal ADD CONSTRAINT FK_C0A73E0A3EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id)');
        $this->addSql('CREATE INDEX IDX_C0A73E0A3EC4DCE ON ingredient_meal (ingredients_id)');
        $this->addSql('DROP INDEX meal_id ON ingredient_meal');
        $this->addSql('CREATE INDEX IDX_C0A73E0A639666D6 ON ingredient_meal (meal_id)');
        $this->addSql('ALTER TABLE ingredient_meal ADD CONSTRAINT ingredient_meal_ibfk_2 FOREIGN KEY (meal_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE meal CHANGE image_url imgurl VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE organisateur CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE numero numero VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY fk_fournisseur_id');
        $this->addSql('DROP INDEX fk_fournisseur_id ON produit');
        $this->addSql('ALTER TABLE produit ADD id_fournisseur_id INT DEFAULT NULL, DROP id_fournisseur, CHANGE nom nom VARCHAR(150) NOT NULL, CHANGE cout cout INT NOT NULL, CHANGE description description VARCHAR(150) NOT NULL, CHANGE image image VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC275A6AC879 FOREIGN KEY (id_fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC275A6AC879 ON produit (id_fournisseur_id)');
        $this->addSql('ALTER TABLE regime DROP FOREIGN KEY fk_useridregime');
        $this->addSql('DROP INDEX client ON regime');
        $this->addSql('ALTER TABLE regime ADD clientid_id INT DEFAULT NULL, DROP clientId, CHANGE startDate startdate VARCHAR(255) NOT NULL, CHANGE endDate enddate VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE regime ADD CONSTRAINT FK_AA864A7CF3FD2D2E FOREIGN KEY (clientid_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AA864A7CF3FD2D2E ON regime (clientid_id)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY fk_competition_id');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY fk_client_id');
        $this->addSql('ALTER TABLE reservation CHANGE fk_client_id fk_client_id INT DEFAULT NULL, CHANGE fk_competition_id fk_competition_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX fk_competition_id ON reservation');
        $this->addSql('CREATE INDEX IDX_42C84955E373AFB ON reservation (fk_competition_id)');
        $this->addSql('DROP INDEX fk_client_id ON reservation');
        $this->addSql('CREATE INDEX IDX_42C8495578B2BEB1 ON reservation (fk_client_id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_competition_id FOREIGN KEY (fk_competition_id) REFERENCES competition (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_client_id FOREIGN KEY (fk_client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE salle CHANGE nom nom VARCHAR(150) NOT NULL, CHANGE description description VARCHAR(150) NOT NULL, CHANGE lieu lieu VARCHAR(150) NOT NULL, CHANGE image image VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE motDePasse motDePasse VARCHAR(255) DEFAULT NULL');
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
        $this->addSql('ALTER TABLE fournisseur MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON fournisseur');
        $this->addSql('ALTER TABLE fournisseur ADD id_fournisseur INT NOT NULL, DROP id, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE type type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE fournisseur ADD PRIMARY KEY (id_fournisseur)');
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
        $this->addSql('ALTER TABLE meal CHANGE imgurl image_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE organisateur CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE numero numero VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC275A6AC879');
        $this->addSql('DROP INDEX IDX_29A5EC275A6AC879 ON produit');
        $this->addSql('ALTER TABLE produit ADD id_fournisseur INT NOT NULL, DROP id_fournisseur_id, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE cout cout DOUBLE PRECISION NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT fk_fournisseur_id FOREIGN KEY (id_fournisseur) REFERENCES fournisseur (id_fournisseur)');
        $this->addSql('CREATE INDEX fk_fournisseur_id ON produit (id_fournisseur)');
        $this->addSql('ALTER TABLE regime DROP FOREIGN KEY FK_AA864A7CF3FD2D2E');
        $this->addSql('DROP INDEX IDX_AA864A7CF3FD2D2E ON regime');
        $this->addSql('ALTER TABLE regime ADD clientId INT NOT NULL, DROP clientid_id, CHANGE startdate startDate DATE NOT NULL, CHANGE enddate endDate DATE NOT NULL');
        $this->addSql('ALTER TABLE regime ADD CONSTRAINT fk_useridregime FOREIGN KEY (clientId) REFERENCES user (id)');
        $this->addSql('CREATE INDEX client ON regime (clientId)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955E373AFB');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495578B2BEB1');
        $this->addSql('ALTER TABLE reservation CHANGE fk_competition_id fk_competition_id INT NOT NULL, CHANGE fk_client_id fk_client_id INT NOT NULL');
        $this->addSql('DROP INDEX idx_42c84955e373afb ON reservation');
        $this->addSql('CREATE INDEX fk_competition_id ON reservation (fk_competition_id)');
        $this->addSql('DROP INDEX idx_42c8495578b2beb1 ON reservation');
        $this->addSql('CREATE INDEX fk_client_id ON reservation (fk_client_id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955E373AFB FOREIGN KEY (fk_competition_id) REFERENCES competition (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495578B2BEB1 FOREIGN KEY (fk_client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE salle CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE lieu lieu VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE motDePasse motDePasse VARCHAR(255) NOT NULL');
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
