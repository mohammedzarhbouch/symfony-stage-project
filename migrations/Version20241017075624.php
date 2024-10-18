<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241017075624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE comment ADD votes INT NOT NULL');
//        $this->addSql('ALTER TABLE follow CHANGE followed_user_id followed_user_id INT NOT NULL, CHANGE follower_user_id follower_user_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE comment DROP votes');
//        $this->addSql('ALTER TABLE follow CHANGE followed_user_id followed_user_id INT DEFAULT NULL, CHANGE follower_user_id follower_user_id INT DEFAULT NULL');
    }
}
