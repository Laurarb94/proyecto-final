<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250412204503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        /*
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD cv VARCHAR(255) DEFAULT NULL');
        */

        // Comprobar si la columna ya existe para que no de error la migración
        $this->addSql("SHOW COLUMNS FROM user LIKE 'cv'");
        $result = $this->connection->fetchAssociative("SHOW COLUMNS FROM user LIKE 'cv'");

        // Si la columna no existe, agregarla
        if (!$result) {
            $this->addSql('ALTER TABLE user ADD cv VARCHAR(255) DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        /*
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP cv');
        */

        // Comprobar si la columna existe antes de eliminarla
        $this->addSql("SHOW COLUMNS FROM user LIKE 'cv'");
        $result = $this->connection->fetchAssociative("SHOW COLUMNS FROM user LIKE 'cv'");

        // Si la columna existe, eliminarla
        if ($result) {
            $this->addSql('ALTER TABLE user DROP cv');
        }
    }
}
