<?php

namespace App\Repositories;

class TaskRepository implements TaskRepositoryInterface
{
    // Simulamos una "base de datos" en memoria
    private $tasks = [];
    private $nextId = 1;

    public function all(): array
    {
        return $this->tasks;
    }

    public function find(int $id): ?array
    {
        return $this->tasks[$id] ?? null;
    }

    public function create(array $data): array
    {
        $task = [
            'id' => $this->nextId,
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'completed' => $data['completed'] ?? false,
            'created_at' => now(),
            'updated_at' => now()
        ];

        $this->tasks[$this->nextId] = $task;
        $this->nextId++;

        return $task;
    }

    public function update(int $id, array $data): ?array
    {
        if (!isset($this->tasks[$id])) {
            return null;
        }

        $this->tasks[$id] = array_merge($this->tasks[$id], $data);
        $this->tasks[$id]['updated_at'] = now();

        return $this->tasks[$id];
    }

    public function delete(int $id): bool
    {
        if (!isset($this->tasks[$id])) {
            return false;
        }

        unset($this->tasks[$id]);
        return true;
    }
}