<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201106005451 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE merge_request (id INT AUTO_INCREMENT NOT NULL, gitlab_id INT NOT NULL, gitlab_internal_id INT NOT NULL, redmine_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, source_branch VARCHAR(255) NOT NULL, target_branch VARCHAR(255) NOT NULL, upvotes LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', downvotes LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', author LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', has_conflicts TINYINT(1) NOT NULL, unresolved_blocking_discussions INT NOT NULL, blocking_discussions_resolved TINYINT(1) NOT NULL, do_not_merge_bitch TINYINT(1) NOT NULL, complexity INT DEFAULT NULL, pipeline LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE merge_request');
    }
}
