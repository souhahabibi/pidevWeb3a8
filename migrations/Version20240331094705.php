<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240331094705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, fk_idsalle_id INT DEFAULT NULL, montant INT NOT NULL, duree INT NOT NULL, description VARCHAR(150) NOT NULL, INDEX IDX_351268BB868BC2AB (fk_idsalle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competition (id INT AUTO_INCREMENT NOT NULL, fk_organisateur_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, date DATE NOT NULL, description VARCHAR(255) NOT NULL, capacite INT NOT NULL, videourl VARCHAR(255) NOT NULL, INDEX IDX_B50A2CB195E6CF (fk_organisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coupon (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nomSociete VARCHAR(255) NOT NULL, code INT NOT NULL, valeur INT NOT NULL, dateExpiration VARCHAR(255) NOT NULL, INDEX IDX_64BF3F02A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, niveau VARCHAR(255) NOT NULL, commentaire VARCHAR(255) NOT NULL, planning VARCHAR(255) NOT NULL, INDEX IDX_FDCA8C9CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice (ide INT AUTO_INCREMENT NOT NULL, id_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, etape VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_E418C74D7F449E57 (id_id), INDEX IDX_E418C74DA76ED395 (user_id), PRIMARY KEY(ide)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material (id INT AUTO_INCREMENT NOT NULL, fk_idsalle_id INT DEFAULT NULL, nom VARCHAR(150) NOT NULL, age INT NOT NULL, quantite INT NOT NULL, prix INT NOT NULL, image VARCHAR(150) NOT NULL, INDEX IDX_7CBE7595868BC2AB (fk_idsalle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, numero VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, fk_competition_id INT DEFAULT NULL, fk_client_id INT DEFAULT NULL, score INT NOT NULL, INDEX IDX_42C84955E373AFB (fk_competition_id), INDEX IDX_42C8495578B2BEB1 (fk_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, description VARCHAR(150) NOT NULL, lieu VARCHAR(150) NOT NULL, image VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB868BC2AB FOREIGN KEY (fk_idsalle_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE competition ADD CONSTRAINT FK_B50A2CB195E6CF FOREIGN KEY (fk_organisateur_id) REFERENCES organisateur (id)');
        $this->addSql('ALTER TABLE coupon ADD CONSTRAINT FK_64BF3F02A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74D7F449E57 FOREIGN KEY (id_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE7595868BC2AB FOREIGN KEY (fk_idsalle_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955E373AFB FOREIGN KEY (fk_competition_id) REFERENCES competition (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495578B2BEB1 FOREIGN KEY (fk_client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE regime ADD verified TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB868BC2AB');
        $this->addSql('ALTER TABLE competition DROP FOREIGN KEY FK_B50A2CB195E6CF');
        $this->addSql('ALTER TABLE coupon DROP FOREIGN KEY FK_64BF3F02A76ED395');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CA76ED395');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74D7F449E57');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74DA76ED395');
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE7595868BC2AB');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955E373AFB');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495578B2BEB1');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE competition');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE organisateur');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE salle');
        $this->addSql('ALTER TABLE regime DROP verified');
    }
}
