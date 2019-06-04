<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190604135243 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE _directory.org (id INT AUTO_INCREMENT NOT NULL, owner INT DEFAULT NULL, created_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, description VARCHAR(1024) DEFAULT NULL, lends VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_58E4F175E237E06 (name), UNIQUE INDEX UNIQ_58E4F17CF60E67C (owner), INDEX IDX_58E4F17DE12AB56 (created_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE _directory.org_site (id INT AUTO_INCREMENT NOT NULL, org INT DEFAULT NULL, name VARCHAR(128) NOT NULL, description VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, country VARCHAR(2) NOT NULL, latitude VARCHAR(32) DEFAULT NULL, longitude VARCHAR(32) DEFAULT NULL, postcode VARCHAR(32) NOT NULL, status VARCHAR(16) NOT NULL, added_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_B30DB8C97215BA80 (org), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE _directory.contact (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', first_name VARCHAR(32) DEFAULT NULL, last_name VARCHAR(32) DEFAULT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, email_canonical VARCHAR(255) DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, username_canonical VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_832E3950C05FB297 (confirmation_token), INDEX IDX_832E3950DE12AB56 (created_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE _directory.org ADD CONSTRAINT FK_58E4F17CF60E67C FOREIGN KEY (owner) REFERENCES _directory.contact (id)');
        $this->addSql('ALTER TABLE _directory.org ADD CONSTRAINT FK_58E4F17DE12AB56 FOREIGN KEY (created_by) REFERENCES _directory.contact (id)');
        $this->addSql('ALTER TABLE _directory.org_site ADD CONSTRAINT FK_B30DB8C97215BA80 FOREIGN KEY (org) REFERENCES _directory.org (id)');
        $this->addSql('ALTER TABLE _directory.contact ADD CONSTRAINT FK_832E3950DE12AB56 FOREIGN KEY (created_by) REFERENCES _directory.contact (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE _directory.org');
        $this->addSql('DROP TABLE _directory.org_site');
        $this->addSql('DROP TABLE _directory.contact');
    }
}
