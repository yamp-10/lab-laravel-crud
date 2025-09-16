<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;

class FileTaskRepository implements TaskRepositoryInterface
{
    private string $folder = 'private/tasks';

    private function ensureFolderExists(): void
    {
        if (!Storage::exists($this->folder)) {
            Storage::makeDirectory($this->folder);
        }
    }

    public function all(): array
    {
        $this->ensureFolderExists();
        $tasks = [];
        
        foreach (Storage::files($this->folder) as $file) {
            $tasks[] = json_decode(Storage::get($file), true);
        }
        
        return $tasks;
    }

    public function find(int $id): ?array
    {
        $this->ensureFolderExists();
        $filePath = $this->folder . '/' . $id . '.json';
        
        if (!Storage::exists($filePath)) {
            return null;
        }
        
        return json_decode(Storage::get($filePath), true);
    }

    public function create(array $data): array
    {
        $this->ensureFolderExists();
        
        // Generar ID único
        $id = uniqid();
        $data['id'] = $id;
        $data['created_at'] = now()->toDateTimeString();
        $data['updated_at'] = now()->toDateTimeString();
        
        $filePath = $this->folder . '/' . $id . '.json';
        Storage::put($filePath, json_encode($data, JSON_PRETTY_PRINT));
        
        return $data;
    }

    public function update(int $id, array $data): ?array
    {
        $this->ensureFolderExists();
        $filePath = $this->folder . '/' . $id . '.json';
        
        if (!Storage::exists($filePath)) {
            return null;
        }
        
        $currentData = json_decode(Storage::get($filePath), true);
        $updatedData = array_merge($currentData, $data);
        $updatedData['updated_at'] = now()->toDateTimeString();
        
        Storage::put($filePath, json_encode($updatedData, JSON_PRETTY_PRINT));
        
        return $updatedData;
    }

    public function delete(int $id): bool
    {
        $this->ensureFolderExists();
        $filePath = $this->folder . '/' . $id . '.json';
        
        if (!Storage::exists($filePath)) {
            return false;
        }
        
        return Storage::delete($filePath);
    }
}