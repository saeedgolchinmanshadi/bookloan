<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260613191601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Limit member.national_code to 10 characters';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE member CHANGE national_code national_code VARCHAR(10) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE member CHANGE national_code national_code VARCHAR(255) NOT NULL');
    }
}
