<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260618150202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70E4FA78D3C17DD2 ON member (national_code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70E4FA783C7323E0 ON member (mobile)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_70E4FA78D3C17DD2 ON member');
        $this->addSql('DROP INDEX UNIQ_70E4FA783C7323E0 ON member');
    }
}
