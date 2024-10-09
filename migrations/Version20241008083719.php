<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008083719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE follow DROP FOREIGN KEY FK_6834447071A4D032');
        $this->addSql('ALTER TABLE follow DROP FOREIGN KEY FK_683444709281982E');
        $this->addSql('DROP INDEX IDX_6834447071A4D032 ON follow');
        $this->addSql('DROP INDEX IDX_683444709281982E ON follow');
        $this->addSql('ALTER TABLE follow ADD followed_user_id INT DEFAULT NULL, ADD follower_user_id INT DEFAULT NULL, DROP followed_user_id_id, DROP follower_user_id_id');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_68344470AF2612FD FOREIGN KEY (followed_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_6834447070FC2906 FOREIGN KEY (follower_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_68344470AF2612FD ON follow (followed_user_id)');
        $this->addSql('CREATE INDEX IDX_6834447070FC2906 ON follow (follower_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE follow DROP FOREIGN KEY FK_68344470AF2612FD');
        $this->addSql('ALTER TABLE follow DROP FOREIGN KEY FK_6834447070FC2906');
        $this->addSql('DROP INDEX IDX_68344470AF2612FD ON follow');
        $this->addSql('DROP INDEX IDX_6834447070FC2906 ON follow');
        $this->addSql('ALTER TABLE follow ADD followed_user_id_id INT DEFAULT NULL, ADD follower_user_id_id INT DEFAULT NULL, DROP followed_user_id, DROP follower_user_id');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_6834447071A4D032 FOREIGN KEY (followed_user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_683444709281982E FOREIGN KEY (follower_user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6834447071A4D032 ON follow (followed_user_id_id)');
        $this->addSql('CREATE INDEX IDX_683444709281982E ON follow (follower_user_id_id)');
    }
}
