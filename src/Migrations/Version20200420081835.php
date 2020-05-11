<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200420081835 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admin CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE article CHANGE review review VARCHAR(255) DEFAULT NULL, CHANGE author author VARCHAR(255) DEFAULT \'Говоруша\' NOT NULL, CHANGE slug slug VARCHAR(255) DEFAULT NULL, CHANGE featured_image featured_image VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE attachements CHANGE article_id article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client CHANGE phone phone VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE questions CHANGE answered_by_id answered_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE schedule CHANGE client_id client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE specialist ADD phone VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admin CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE article CHANGE review review VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE author author VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'\'\'Говоруша\'\'\' NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE featured_image featured_image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE attachements CHANGE article_id article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client CHANGE phone phone VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE questions CHANGE answered_by_id answered_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE schedule CHANGE client_id client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE specialist DROP phone');
    }
}
