<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260619120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make book_loan.due_date nullable for optional reservation due dates';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE book_loan CHANGE due_date due_date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE book_loan CHANGE due_date due_date DATETIME NOT NULL');
    }
}
