<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200427083425 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, code SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, code_insee INT NOT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, INDEX IDX_2D5B0234AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, status SMALLINT DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, reset_token LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, label VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, region_id INT NOT NULL, code SMALLINT NOT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_CD1DE18A98260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, grade_id INT NOT NULL, service_id INT NOT NULL, city_id INT NOT NULL, mutation_city_id INT DEFAULT NULL, mutation_department_id INT DEFAULT NULL, mutation_region_id INT NOT NULL, informations VARCHAR(128) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, status SMALLINT DEFAULT 1 NOT NULL, UNIQUE INDEX UNIQ_F65593E5A76ED395 (user_id), INDEX IDX_F65593E5FE19A1A8 (grade_id), INDEX IDX_F65593E5ED5CA9E6 (service_id), INDEX IDX_F65593E58BAC62AF (city_id), INDEX IDX_F65593E5EB93946F (mutation_city_id), INDEX IDX_F65593E598836D41 (mutation_department_id), INDEX IDX_F65593E5702E10D (mutation_region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token_blacklist (id INT AUTO_INCREMENT NOT NULL, token LONGTEXT NOT NULL, expiration DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, recipient_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, read_at DATETIME DEFAULT NULL, status SMALLINT DEFAULT 1 NOT NULL, INDEX IDX_B6BD307FF675F31B (author_id), INDEX IDX_B6BD307FE92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5FE19A1A8 FOREIGN KEY (grade_id) REFERENCES grade (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E58BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5EB93946F FOREIGN KEY (mutation_city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E598836D41 FOREIGN KEY (mutation_department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5702E10D FOREIGN KEY (mutation_region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE92F8F78 FOREIGN KEY (recipient_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A98260155');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5702E10D');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5ED5CA9E6');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E58BAC62AF');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5EB93946F');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5FE19A1A8');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF675F31B');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE92F8F78');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234AE80F5DF');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E598836D41');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE token_blacklist');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE refresh_tokens');
    }
}
