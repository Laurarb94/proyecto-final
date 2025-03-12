<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250312143237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course ADD category_id INT NOT NULL, ADD subcategory_id INT NOT NULL');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB912469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB95DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES subcategory (id)');
        $this->addSql('CREATE INDEX IDX_169E6FB912469DE2 ON course (category_id)');
        $this->addSql('CREATE INDEX IDX_169E6FB95DC6FE57 ON course (subcategory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB912469DE2');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB95DC6FE57');
        $this->addSql('DROP INDEX IDX_169E6FB912469DE2 ON course');
        $this->addSql('DROP INDEX IDX_169E6FB95DC6FE57 ON course');
        $this->addSql('ALTER TABLE course DROP category_id, DROP subcategory_id');
    }
}
