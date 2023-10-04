<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231004080054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE infos_user_hobby (infos_user_id INT NOT NULL, hobby_id INT NOT NULL, INDEX IDX_7AEE4491A0D63835 (infos_user_id), INDEX IDX_7AEE4491322B2123 (hobby_id), PRIMARY KEY(infos_user_id, hobby_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE infos_user_hobby ADD CONSTRAINT FK_7AEE4491A0D63835 FOREIGN KEY (infos_user_id) REFERENCES infos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE infos_user_hobby ADD CONSTRAINT FK_7AEE4491322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE infos_user ADD profile_id INT DEFAULT NULL, ADD job_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE infos_user ADD CONSTRAINT FK_AA81A6EACCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE infos_user ADD CONSTRAINT FK_AA81A6EABE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA81A6EACCFA12B8 ON infos_user (profile_id)');
        $this->addSql('CREATE INDEX IDX_AA81A6EABE04EA9 ON infos_user (job_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infos_user_hobby DROP FOREIGN KEY FK_7AEE4491A0D63835');
        $this->addSql('ALTER TABLE infos_user_hobby DROP FOREIGN KEY FK_7AEE4491322B2123');
        $this->addSql('DROP TABLE infos_user_hobby');
        $this->addSql('ALTER TABLE infos_user DROP FOREIGN KEY FK_AA81A6EACCFA12B8');
        $this->addSql('ALTER TABLE infos_user DROP FOREIGN KEY FK_AA81A6EABE04EA9');
        $this->addSql('DROP INDEX UNIQ_AA81A6EACCFA12B8 ON infos_user');
        $this->addSql('DROP INDEX IDX_AA81A6EABE04EA9 ON infos_user');
        $this->addSql('ALTER TABLE infos_user DROP profile_id, DROP job_id');
    }
}
