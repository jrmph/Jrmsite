<?php
interface ProviderInterface
{
    /**
     * Search for anime by query.
     * Returns an array of associative arrays with keys:
     * - title (string)
     * - url (string)
     * - thumbnail (string|null)
     * - description (string|null)
     * - provider (string)
     */
    public function search(string $query): array;

    /** Provider name */
    public function name(): string;
}