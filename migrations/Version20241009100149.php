<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241009100149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, post_id INT NOT NULL, score INT NOT NULL, INDEX IDX_D8892622A76ED395 (user_id), INDEX IDX_D88926224B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
//        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926224B89032C FOREIGN KEY (post_id) REFERENCES posts (id)');
//        $this->addSql('ALTER TABLE follow CHANGE followed_user_id followed_user_id INT NOT NULL, CHANGE follower_user_id follower_user_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622A76ED395');
//        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926224B89032C');
//        $this->addSql('DROP TABLE rating');
//        $this->addSql('ALTER TABLE follow CHANGE followed_user_id followed_user_id INT DEFAULT NULL, CHANGE follower_user_id follower_user_id INT DEFAULT NULL');
    }
}
