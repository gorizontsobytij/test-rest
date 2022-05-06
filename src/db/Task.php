<?php

namespace Src\db;

//todo: implement errors handling
class Task extends DbConnector
{
    public function create(string $status, string $priority, string $title, string $description, ?string $parent_id = null): void
    {

        $task = $this->connect()->prepare("
            INSERT INTO tasks(status, priority, title, description, parent_id) 
            VALUES (?, ?, ?, ?, ?);
			");
        $task->execute([$status, $priority, $title, $description, $parent_id]);
    }

    public function show(string $sort_by, string $status = "", string $title = ""): array
    {
        $sql = "SELECT * FROM tasks ";
        if ($status) {
            $sql .= "WHERE (status = '$status') ";
            if ($title) {
                $sql .= "AND title LIKE '%$title%' ";
            }
        } elseif($title) {
            $sql .= "WHERE title LIKE '%$title%' ";
        }
        if ($sort_by) {
            $sql .= "ORDER BY $sort_by";
        }

        return $this->connect()->query($sql)->fetchAll();
    }

    public function edit(string $task_id, string $title, string $description, string $priority): void
    {
        $task = $this->connect()->prepare("
            UPDATE tasks 
            SET title = ?, description = ?, priority = ?
            WHERE (id = '$task_id');
			");
        $task->execute([$title, $description, $priority]);
    }

    public function changeStatus(string $task_id, string $status, string $current_date): void
    {
        $task = $this->connect()->prepare("
            UPDATE tasks 
            SET status = ?, status_changed_at = ? 
            WHERE (id = '$task_id');
			");
        $task->execute([$status, $current_date]);
    }

    public function delete(string $task_id): int
    {
        return $this->connect()->exec("DELETE FROM tasks 
                WHERE id = '$task_id' 
              AND status != 'done'");
    }







}