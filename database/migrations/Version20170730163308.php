<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20170730163308 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE todos (id CHAR(36) NOT NULL COMMENT \'(DC2Type:TodoId)\', todo_list_id CHAR(36) NOT NULL COMMENT \'(DC2Type:TodoListId)\', creator_team_member_id CHAR(36) NOT NULL COMMENT \'(DC2Type:TeamMemberId)\', name VARCHAR(255) NOT NULL, assignee_team_member_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:TeamMemberId)\', deadline DATETIME DEFAULT NULL COMMENT \'(DC2Type:DateTimeImmutable)\', position INT NOT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:DateTimeImmutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE todo_comments (id CHAR(36) NOT NULL COMMENT \'(DC2Type:CommentId)\', todo_id CHAR(36) NOT NULL COMMENT \'(DC2Type:TodoId)\', author_team_member_id CHAR(36) NOT NULL COMMENT \'(DC2Type:TeamMemberId)\', content LONGTEXT NOT NULL, attachments TEXT NOT NULL COMMENT \'(DC2Type:AttachmentCollection)\', created_on DATETIME NOT NULL COMMENT \'(DC2Type:DateTimeImmutable)\', updated_on DATETIME NOT NULL COMMENT \'(DC2Type:DateTimeImmutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE todo_lists (id CHAR(36) NOT NULL COMMENT \'(DC2Type:TodoListId)\', project_id CHAR(36) NOT NULL COMMENT \'(DC2Type:ProjectId)\', creator_team_member_id CHAR(36) NOT NULL COMMENT \'(DC2Type:TeamMemberId)\', name VARCHAR(255) NOT NULL, is_archived TINYINT(1) NOT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:DateTimeImmutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE todo_lists_todos (todo_list_id CHAR(36) NOT NULL COMMENT \'(DC2Type:TodoListId)\', todo_id CHAR(36) NOT NULL COMMENT \'(DC2Type:TodoId)\', INDEX IDX_BEB75672E8A7DCFA (todo_list_id), UNIQUE INDEX UNIQ_BEB75672EA1EBC33 (todo_id), PRIMARY KEY(todo_list_id, todo_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE todo_lists_todos ADD CONSTRAINT FK_BEB75672E8A7DCFA FOREIGN KEY (todo_list_id) REFERENCES todo_lists (id)');
        $this->addSql('ALTER TABLE todo_lists_todos ADD CONSTRAINT FK_BEB75672EA1EBC33 FOREIGN KEY (todo_id) REFERENCES todos (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE todo_lists_todos DROP FOREIGN KEY FK_BEB75672EA1EBC33');
        $this->addSql('ALTER TABLE todo_lists_todos DROP FOREIGN KEY FK_BEB75672E8A7DCFA');
        $this->addSql('DROP TABLE todos');
        $this->addSql('DROP TABLE todo_comments');
        $this->addSql('DROP TABLE todo_lists');
        $this->addSql('DROP TABLE todo_lists_todos');
    }
}
