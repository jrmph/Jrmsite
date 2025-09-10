<?php

class Cache
{
    private string $dir;
    private int $ttl;

    public function __construct(string $dir = __DIR__ . '/../.cache', int $ttl = 600)
    {
        $this->dir = rtrim($dir, '/');
        $this->ttl = $ttl;
        if (!is_dir($this->dir)) {
            @mkdir($this->dir, 0777, true);
        }
    }

    public function get(string $key): ?string
    {
        $path = $this->path($key);
        if (!is_file($path)) return null;
        if ((time() - filemtime($path)) > $this->ttl) return null;
        $data = @file_get_contents($path);
        return $data === false ? null : $data;
    }

    public function set(string $key, string $value): void
    {
        $path = $this->path($key);
        @file_put_contents($path, $value);
    }

    private function path(string $key): string
    {
        $safe = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $key);
        return "{$this->dir}/{$safe}.cache";
    }
}