<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240508115143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, montant INT NOT NULL, duree INT NOT NULL, description VARCHAR(150) NOT NULL, FK_idSalle INT DEFAULT NULL, INDEX IDX_351268BB64D0811E (FK_idSalle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competition (id INT AUTO_INCREMENT NOT NULL, fk_organisateur_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, date DATE NOT NULL, description VARCHAR(255) NOT NULL, capacite INT NOT NULL, videourl VARCHAR(255) NOT NULL, INDEX IDX_B50A2CB195E6CF (fk_organisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coupon (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nomSociete VARCHAR(255) NOT NULL, code INT NOT NULL, valeur INT NOT NULL, dateExpiration VARCHAR(255) NOT NULL, INDEX IDX_64BF3F02A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, niveau VARCHAR(255) NOT NULL, commentaire VARCHAR(255) NOT NULL, planning TIMESTAMP DEFAULT CURRENT_TIMESTAMP, INDEX IDX_FDCA8C9CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice (id INT DEFAULT NULL, user_id INT DEFAULT NULL, idE INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, etape VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_E418C74DBF396750 (id), INDEX IDX_E418C74DA76ED395 (user_id), PRIMARY KEY(idE)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id_fournisseur INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, prenom VARCHAR(150) NOT NULL, numero INT NOT NULL, type VARCHAR(150) NOT NULL, PRIMARY KEY(id_fournisseur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient_meal (id INT AUTO_INCREMENT NOT NULL, ingredients_id INT DEFAULT NULL, meal_id INT DEFAULT NULL, INDEX IDX_C0A73E0A3EC4DCE (ingredients_id), INDEX IDX_C0A73E0A639666D6 (meal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredients (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, calories INT NOT NULL, total_fat INT NOT NULL, protein INT NOT NULL, imgurl VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material (id INT AUTO_INCREMENT NOT NULL, fk_idsalle_id INT DEFAULT NULL, nom VARCHAR(150) NOT NULL, age INT NOT NULL, quantite INT NOT NULL, prix INT NOT NULL, image VARCHAR(150) NOT NULL, INDEX IDX_7CBE7595868BC2AB (fk_idsalle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, age INT NOT NULL, quantite INT NOT NULL, prix INT NOT NULL, image VARCHAR(150) NOT NULL, FK_idSalle INT DEFAULT NULL, INDEX IDX_18D2B09164D0811E (FK_idSalle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meal (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, recipe VARCHAR(255) NOT NULL, calories INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, numero VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id_produit INT AUTO_INCREMENT NOT NULL, id_fournisseur INT DEFAULT NULL, nom VARCHAR(150) NOT NULL, quantite INT NOT NULL, cout INT NOT NULL, date_expiration DATE NOT NULL, description VARCHAR(150) NOT NULL, image VARCHAR(150) NOT NULL, INDEX IDX_29A5EC272E8C07C5 (id_fournisseur), PRIMARY KEY(id_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regime (id INT AUTO_INCREMENT NOT NULL, startdate DATE NOT NULL, enddate DATE NOT NULL, duration INT NOT NULL, description VARCHAR(255) NOT NULL, goal VARCHAR(255) NOT NULL, verified TINYINT(1) NOT NULL, clientId INT DEFAULT NULL, INDEX IDX_AA864A7CEA1CE9BE (clientId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, fk_competition_id INT DEFAULT NULL, fk_client_id INT DEFAULT NULL, score INT NOT NULL, INDEX IDX_42C84955E373AFB (fk_competition_id), INDEX IDX_42C8495578B2BEB1 (fk_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reviewmeal (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, idmeal_id INT DEFAULT NULL, rate DOUBLE PRECISION NOT NULL, comment VARCHAR(255) NOT NULL, created_date DATE NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_DFAA9BAFA76ED395 (user_id), INDEX IDX_DFAA9BAFBC9234B8 (idmeal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, description VARCHAR(150) NOT NULL, lieu VARCHAR(150) NOT NULL, image VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, motDePasse VARCHAR(255) DEFAULT NULL, specialite VARCHAR(255) DEFAULT NULL, numero INT NOT NULL, recommandation VARCHAR(3) NOT NULL, poids INT NOT NULL, taille INT NOT NULL, niveau VARCHAR(255) NOT NULL, role VARCHAR(10) NOT NULL, mailcode VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usersmeal (id INT AUTO_INCREMENT NOT NULL, mealid_id INT DEFAULT NULL, userid_id INT DEFAULT NULL, INDEX IDX_E046005038BF3DB8 (mealid_id), INDEX IDX_E046005058E0A285 (userid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB64D0811E FOREIGN KEY (FK_idSalle) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE competition ADD CONSTRAINT FK_B50A2CB195E6CF FOREIGN KEY (fk_organisateur_id) REFERENCES organisateur (id)');
        $this->addSql('ALTER TABLE coupon ADD CONSTRAINT FK_64BF3F02A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74DBF396750 FOREIGN KEY (id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ingredient_meal ADD CONSTRAINT FK_C0A73E0A3EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id)');
        $this->addSql('ALTER TABLE ingredient_meal ADD CONSTRAINT FK_C0A73E0A639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE7595868BC2AB FOREIGN KEY (fk_idsalle_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B09164D0811E FOREIGN KEY (FK_idSalle) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC272E8C07C5 FOREIGN KEY (id_fournisseur) REFERENCES fournisseur (id_fournisseur)');
        $this->addSql('ALTER TABLE regime ADD CONSTRAINT FK_AA864A7CEA1CE9BE FOREIGN KEY (clientId) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955E373AFB FOREIGN KEY (fk_competition_id) REFERENCES competition (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495578B2BEB1 FOREIGN KEY (fk_client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reviewmeal ADD CONSTRAINT FK_DFAA9BAFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reviewmeal ADD CONSTRAINT FK_DFAA9BAFBC9234B8 FOREIGN KEY (idmeal_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE usersmeal ADD CONSTRAINT FK_E046005038BF3DB8 FOREIGN KEY (mealid_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE usersmeal ADD CONSTRAINT FK_E046005058E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB64D0811E');
        $this->addSql('ALTER TABLE competition DROP FOREIGN KEY FK_B50A2CB195E6CF');
        $this->addSql('ALTER TABLE coupon DROP FOREIGN KEY FK_64BF3F02A76ED395');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CA76ED395');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74DBF396750');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74DA76ED395');
        $this->addSql('ALTER TABLE ingredient_meal DROP FOREIGN KEY FK_C0A73E0A3EC4DCE');
        $this->addSql('ALTER TABLE ingredient_meal DROP FOREIGN KEY FK_C0A73E0A639666D6');
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE7595868BC2AB');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B09164D0811E');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC272E8C07C5');
        $this->addSql('ALTER TABLE regime DROP FOREIGN KEY FK_AA864A7CEA1CE9BE');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955E373AFB');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495578B2BEB1');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE reviewmeal DROP FOREIGN KEY FK_DFAA9BAFA76ED395');
        $this->addSql('ALTER TABLE reviewmeal DROP FOREIGN KEY FK_DFAA9BAFBC9234B8');
        $this->addSql('ALTER TABLE usersmeal DROP FOREIGN KEY FK_E046005038BF3DB8');
        $this->addSql('ALTER TABLE usersmeal DROP FOREIGN KEY FK_E046005058E0A285');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE competition');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE ingredient_meal');
        $this->addSql('DROP TABLE ingredients');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE meal');
        $this->addSql('DROP TABLE organisateur');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE regime');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE reviewmeal');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE usersmeal');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
