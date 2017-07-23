<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20170723091812 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE events (id CHAR(36) NOT NULL COMMENT \'(DC2Type:EventId)\', project_id CHAR(36) NOT NULL COMMENT \'(DC2Type:ProjectId)\', creator_team_member_id CHAR(36) NOT NULL COMMENT \'(DC2Type:TeamMemberId)\', name VARCHAR(255) NOT NULL, details LONGTEXT NOT NULL, is_archived TINYINT(1) NOT NULL, occurs_on DATETIME NOT NULL COMMENT \'(DC2Type:DateTimeImmutable)\', created_on DATETIME NOT NULL COMMENT \'(DC2Type:DateTimeImmutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_comments (id CHAR(36) NOT NULL COMMENT \'(DC2Type:CommentId)\', event_id CHAR(36) NOT NULL COMMENT \'(DC2Type:EventId)\', author_team_member_id CHAR(36) NOT NULL COMMENT \'(DC2Type:TeamMemberId)\', content LONGTEXT NOT NULL, attachments TEXT NOT NULL COMMENT \'(DC2Type:AttachmentCollection)\', created_on DATETIME NOT NULL COMMENT \'(DC2Type:DateTimeImmutable)\', updated_on DATETIME NOT NULL COMMENT \'(DC2Type:DateTimeImmutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE event_comments');
    }
}
