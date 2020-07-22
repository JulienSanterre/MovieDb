<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191112162451 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE movie ADD popularity DOUBLE PRECISION DEFAULT NULL, ADD vote_count VARCHAR(255) DEFAULT NULL, ADD adult TINYINT(1) DEFAULT NULL, ADD backdrop_path VARCHAR(255) DEFAULT NULL, ADD original_language VARCHAR(255) DEFAULT NULL, ADD original_title VARCHAR(255) DEFAULT NULL, ADD vote_average DOUBLE PRECISION DEFAULT NULL, ADD overview LONGTEXT DEFAULT NULL, ADD release_date DATE DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE movie DROP popularity, DROP vote_count, DROP adult, DROP backdrop_path, DROP original_language, DROP original_title, DROP vote_average, DROP overview, DROP release_date');
    }
}
