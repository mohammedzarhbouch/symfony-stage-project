<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241009112311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE follow CHANGE followed_user_id followed_user_id INT NOT NULL, CHANGE follower_user_id follower_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE posts ADD total_rating_score INT NOT NULL, ADD amount_of_ratings INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE follow CHANGE followed_user_id followed_user_id INT DEFAULT NULL, CHANGE follower_user_id follower_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE posts DROP total_rating_score, DROP amount_of_ratings');
    }
}
