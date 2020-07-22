<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191122225409 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE casting ADD api_person_id_id INT DEFAULT NULL, DROP api_person_id');
        $this->addSql('ALTER TABLE casting ADD CONSTRAINT FK_D11BBA50D918583E FOREIGN KEY (api_person_id_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_D11BBA50D918583E ON casting (api_person_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE casting DROP FOREIGN KEY FK_D11BBA50D918583E');
        $this->addSql('DROP INDEX IDX_D11BBA50D918583E ON casting');
        $this->addSql('ALTER TABLE casting ADD api_person_id INT NOT NULL, DROP api_person_id_id');
    }
}
