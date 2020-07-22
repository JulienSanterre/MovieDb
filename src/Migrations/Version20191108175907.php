<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191108175907 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team CHANGE movie_id movie_id INT NOT NULL');
        $this->addSql('ALTER TABLE job CHANGE department_id department_id INT NOT NULL');
        $this->addSql('ALTER TABLE casting CHANGE movie_id movie_id INT NOT NULL, CHANGE person_id person_id INT NOT NULL, CHANGE role role VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE casting CHANGE movie_id movie_id INT DEFAULT NULL, CHANGE person_id person_id INT DEFAULT NULL, CHANGE role role VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE job CHANGE department_id department_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team CHANGE movie_id movie_id INT DEFAULT NULL');
    }
}
