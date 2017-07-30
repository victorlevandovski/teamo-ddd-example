<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Teamo\Project\Domain\Model\Project\TodoList\TodoId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListRepository;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Team\TeamMemberId;
use Tests\TestCase;

class DoctrineTodoListRepositoryTest extends TestCase
{
    /** @var TodoListRepository */
    private $todoListRepository;

    /** @var EntityManagerInterface */
    private $em;

    public function setUp()
    {
        parent::setUp();

        $this->em = app(EntityManagerInterface::class);
        $this->todoListRepository = $this->em->getRepository(TodoList::class);
    }

    public function testRepositoryCanAddAndRemoveTodoLists()
    {
        $projectId = new ProjectId(uniqid('unit_test_'));
        $todoListId = new TodoListId(uniqid('unit_test_'));
        $teamMemberId = new TeamMemberId(uniqid('unit_test_'));

        $todoList = new TodoList($projectId, $todoListId, $teamMemberId, 'Topic');
        $this->todoListRepository->add($todoList);
        $this->em->flush();

        $savedTodoList = $this->todoListRepository->ofId($todoListId, $projectId);
        $this->assertEquals('Topic', $savedTodoList->name());
        $this->assertEquals($teamMemberId, $savedTodoList->creator());

        $todoId = new TodoId(uniqid('unit_test_'));
        $savedTodoList->addTodo($todoId, $teamMemberId, 'Todo 1');
        $this->em->flush();

        $savedWithTodo = $this->todoListRepository->ofId($todoListId, $projectId);
        $this->assertCount(1, $savedWithTodo->todos());
        $this->assertEquals('Todo 1', $savedWithTodo->todos()[0]->name());

        $savedWithTodo->renameTodo($todoId, 'New Todo');
        $this->em->flush();

        $withRenamedTodo = $this->todoListRepository->ofId($todoListId, $projectId);
        $this->assertEquals('New Todo', $withRenamedTodo->todos()[0]->name());

        $this->todoListRepository->remove($withRenamedTodo);
        $this->em->flush();

        $this->expectException(\InvalidArgumentException::class);
        $this->todoListRepository->ofId($todoListId, $projectId);
    }

    public function testRepositoryReturnsOnlyActiveOrArchivedTodoLists()
    {
        $todoListId1 = new TodoListId(uniqid('unit_test_'));
        $todoListId2 = new TodoListId(uniqid('unit_test_'));

        $projectId = new ProjectId(uniqid('unit_test_'));
        $teamMemberId = new TeamMemberId(uniqid('unit_test_'));

        $todoList1 = new TodoList($projectId, $todoListId1, $teamMemberId, 'Todo list 2');
        $todoList2 = new TodoList($projectId, $todoListId2, $teamMemberId, 'Todo list 2');
        $todoList2->archive();

        $this->todoListRepository->add($todoList1);
        $this->todoListRepository->add($todoList2);
        $this->em->flush();

        $activeTodoLists = $this->todoListRepository->allActive($projectId);
        $this->assertCount(1, $activeTodoLists);
        $this->assertEquals($todoListId1, $activeTodoLists[0]->todoListId());

        $archivedTodoLists = $this->todoListRepository->allArchived($projectId);
        $this->assertCount(1, $archivedTodoLists);
        $this->assertEquals($todoListId2, $archivedTodoLists[0]->todoListId());

        $this->todoListRepository->remove($todoList1);
        $this->todoListRepository->remove($todoList2);
        $this->em->flush();
    }
}
