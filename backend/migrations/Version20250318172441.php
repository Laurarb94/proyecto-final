<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250318172441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crea la columna roles como JSON';
    }
    
    public function up(Schema $schema): void
    {
        // Crear la columna roles como JSON
        $this->addSql('ALTER TABLE user ADD COLUMN roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // Eliminar la columna roles en caso de reversión
        $this->addSql('ALTER TABLE user DROP COLUMN roles');
    }
}
