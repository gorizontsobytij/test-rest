<?php

namespace Src\Controllers;

use Src\db\Task;
use Src\helpers\DataParser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//todo: validation not included!
class TaskController extends Controller
{
    use DataParser;

    private Task $task;
    private array $payload;
    protected string $status, $priority, $title, $description;

    public function __construct(Request $request, array $attributes)
    {
        parent::__construct($request, $attributes);
        $this->task = new Task();

        if ($this->request->getContent()) {
            $this->payload = json_decode($this->request->getContent(), true);
            $this->status = $this->payload["status"] ?? "";
            $this->priority = $this->payload["priority"] ?? "";
            $this->title = $this->payload["title"] ?? "";
            $this->description = $this->payload["description"] ?? "";
        }

    }

    final public function create(): Response
    {
        $parent_id = $this->payload["parent_id"] ?: null;
        $this->task->create($this->status, $this->priority, $this->title, $this->description, $parent_id);

        return $this->response
            ->setStatusCode(201)
            ->send();
    }

    final public function show(): Response
    {
        $status = $this->request->get("status", "");
        $title = $this->request->get("title", "");
        $sort_by = $this->request->get("sort_by", "");

        $tasks = $this->task->show($sort_by, $status, $title);

        return $this->response->setStatusCode(200)
            ->setContent(json_encode($tasks))
            ->send();

    }

    final public function edit(): Response
    {
        $task_id = $this->payload["task_id"];
        $this->task->edit($task_id, $this->title, $this->description, $this->priority);
        return $this->response->setStatusCode(204)
            ->send();
    }

    final public function changeStatus(): Response
    {
        $task_id = $this->payload["task_id"];
        $current_date = date('d-m-y h:i:s');
        $tasks = $this->task->show("id", "todo");

        if ($this->elementHasParent($task_id, $tasks)){    //crappy decision(((
            return $this->response->setStatusCode(200)
                ->setContent(json_encode(['error' => 'Cant change status.']))
                ->send();
        }
        $this->task->changeStatus($task_id, $this->status, $current_date);
        return $this->response->setStatusCode(204)
            ->send();
    }

    final public function delete(): Response
    {
        $task_id = $this->payload["task_id"];
        $rows_quantity = $this->task->delete($task_id);
        if ($rows_quantity > 0) {
            return $this->response->setStatusCode(200)
                ->send();
        }
        return $this->response->setStatusCode(200)
            ->setContent(["error"=> "task already done"])
            ->send();
    }





}