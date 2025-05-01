<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501161457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $schemaManager = $this->connection->createSchemaManager();
        $columns = $schemaManager->listTableColumns('job_offer');
        
        if (!array_key_exists('category_id', $columns)) {
            $this->addSql('ALTER TABLE job_offer ADD category_id INT NOT NULL');
            $this->addSql('CREATE INDEX IDX_288A3A4E12469DE2 ON job_offer (category_id)');
            $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        }
        
        if (!array_key_exists('subcategory_id', $columns)) {
            $this->addSql('ALTER TABLE job_offer ADD subcategory_id INT NOT NULL');
            $this->addSql('CREATE INDEX IDX_288A3A4E5DC6FE57 ON job_offer (subcategory_id)');
            $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E5DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id)');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E12469DE2');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E5DC6FE57');
        $this->addSql('DROP INDEX IDX_288A3A4E12469DE2 ON job_offer');
        $this->addSql('DROP INDEX IDX_288A3A4E5DC6FE57 ON job_offer');
        $this->addSql('ALTER TABLE job_offer DROP category_id, DROP subcategory_id');
    }
}
