<?php

namespace Src\helpers;

trait DataParser
{
    public function elementHasParent(string $start, array $tasks): bool
    {
        static $counter = 0;
        foreach ($tasks as $task) {
            if ($task['id'] === $start && $task['parent_id']) {
                $node = $task['parent_id'];
                $counter++;
                $this->elementHasParent($node, $tasks);
            }
        }
        return $counter > 1 ? true : false;
    }

}