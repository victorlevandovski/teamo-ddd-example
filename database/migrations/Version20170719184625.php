<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20170719184625 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE discussions (id CHAR(36) NOT NULL COMMENT \'(DC2Type:DiscussionId)\', project_id CHAR(36) NOT NULL COMMENT \'(DC2Type:ProjectId)\', author_team_member_id CHAR(36) NOT NULL COMMENT \'(DC2Type:TeamMemberId)\', topic VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, attachments TEXT NOT NULL COMMENT \'(DC2Type:AttachmentCollection)\', is_archived TINYINT(1) NOT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:DateTimeImmutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discussion_comments (id CHAR(36) NOT NULL COMMENT \'(DC2Type:CommentId)\', discussion_id CHAR(36) NOT NULL COMMENT \'(DC2Type:DiscussionId)\', author_team_member_id CHAR(36) NOT NULL COMMENT \'(DC2Type:TeamMemberId)\', content LONGTEXT NOT NULL, attachments TEXT NOT NULL COMMENT \'(DC2Type:AttachmentCollection)\', created_on DATETIME NOT NULL COMMENT \'(DC2Type:DateTimeImmutable)\', updated_on DATETIME NOT NULL COMMENT \'(DC2Type:DateTimeImmutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE discussions');
        $this->addSql('DROP TABLE discussion_comments');
    }
}
