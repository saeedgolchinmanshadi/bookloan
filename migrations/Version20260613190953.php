<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260613190953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, publisher_id INT NOT NULL, INDEX IDX_CBE5A33140C86FCE (publisher_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE book_subject (book_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_68EE16EC16A2B381 (book_id), INDEX IDX_68EE16EC23EDC87 (subject_id), PRIMARY KEY (book_id, subject_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE book_loan (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(20) NOT NULL, borrow_date DATETIME NOT NULL, due_date DATETIME NOT NULL, returned_at DATETIME DEFAULT NULL, book_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_DC4E460B16A2B381 (book_id), INDEX IDX_DC4E460B7597D3FE (member_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, national_code VARCHAR(255) NOT NULL, mobile VARCHAR(11) NOT NULL, is_active TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE publisher (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A33140C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id)');
        $this->addSql('ALTER TABLE book_subject ADD CONSTRAINT FK_68EE16EC16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_subject ADD CONSTRAINT FK_68EE16EC23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_loan ADD CONSTRAINT FK_DC4E460B16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE book_loan ADD CONSTRAINT FK_DC4E460B7597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A33140C86FCE');
        $this->addSql('ALTER TABLE book_subject DROP FOREIGN KEY FK_68EE16EC16A2B381');
        $this->addSql('ALTER TABLE book_subject DROP FOREIGN KEY FK_68EE16EC23EDC87');
        $this->addSql('ALTER TABLE book_loan DROP FOREIGN KEY FK_DC4E460B16A2B381');
        $this->addSql('ALTER TABLE book_loan DROP FOREIGN KEY FK_DC4E460B7597D3FE');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE book_subject');
        $this->addSql('DROP TABLE book_loan');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE publisher');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
