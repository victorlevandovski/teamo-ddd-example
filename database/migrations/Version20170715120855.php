<?php

namespace Database\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema as Schema;

class Version20170715120855 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users (id CHAR(36) NOT NULL COMMENT \'(DC2Type:UserId)\', name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, remember_token VARCHAR(255) DEFAULT NULL, preferences_language VARCHAR(255) NOT NULL, preferences_timezone VARCHAR(255) NOT NULL, preferences_date_format VARCHAR(255) NOT NULL, preferences_time_format INT NOT NULL, preferences_first_day_of_week INT NOT NULL, preferences_show_todo_lists_as VARCHAR(255) NOT NULL, notifications_discussion_started TINYINT(1) NOT NULL, notifications_discussion_commented TINYINT(1) NOT NULL, notifications_todo_list_created TINYINT(1) NOT NULL, notifications_todo_commented TINYINT(1) NOT NULL, notifications_todo_assigned_to_me TINYINT(1) NOT NULL, notifications_event_added TINYINT(1) NOT NULL, notifications_event_commented TINYINT(1) NOT NULL, avatar_path_to48px_avatar VARCHAR(255) NOT NULL, avatar_path_to96px_avatar VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects (id CHAR(36) NOT NULL COMMENT \'(DC2Type:ProjectId)\', name VARCHAR(255) NOT NULL, is_archived TINYINT(1) NOT NULL, owner_team_member_id CHAR(36) NOT NULL COMMENT \'(DC2Type:TeamMemberId)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects_team_members (project_id CHAR(36) NOT NULL COMMENT \'(DC2Type:ProjectId)\', team_member_id CHAR(36) NOT NULL COMMENT \'(DC2Type:TeamMemberId)\', INDEX IDX_D382BF92166D1F9C (project_id), INDEX IDX_D382BF92C292CD19 (team_member_id), PRIMARY KEY(project_id, team_member_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_members (id CHAR(36) NOT NULL COMMENT \'(DC2Type:TeamMemberId)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projects_team_members ADD CONSTRAINT FK_D382BF92166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE projects_team_members ADD CONSTRAINT FK_D382BF92C292CD19 FOREIGN KEY (team_member_id) REFERENCES team_members (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE projects_team_members DROP FOREIGN KEY FK_D382BF92166D1F9C');
        $this->addSql('ALTER TABLE projects_team_members DROP FOREIGN KEY FK_D382BF92C292CD19');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE projects_team_members');
        $this->addSql('DROP TABLE team_members');
    }
}
