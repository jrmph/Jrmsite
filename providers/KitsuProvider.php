<?php
require_once __DIR__ . '/../lib/HttpClient.php';
require_once __DIR__ . '/ProviderInterface.php';

class KitsuProvider implements ProviderInterface
{
    private HttpClient $http;

    public function __construct()
    {
        $this->http = new HttpClient([
            'User-Agent: AnimeHub/1.0 (+https://example.local)',
            'Accept: application/vnd.api+json',
        ], 12);
    }

    public function name(): string
    {
        return 'Kitsu';
    }

    public function search(string $query): array
    {
        $url = 'https://kitsu.io/api/edge/anime?filter%5Btext%5D=' . rawurlencode($query) . '&page%5Blimit%5D=18';
        $res = $this->http->get($url);
        if (!$res['ok'] || $res['status'] !== 200) {
            return [];
        }
        $json = json_decode($res['body'], true);
        if (!isset($json['data']) || !is_array($json['data'])) {
            return [];
        }
        $out = [];
        foreach ($json['data'] as $item) {
            $attr = $item['attributes'] ?? [];
            $poster = $attr['posterImage']['medium'] ?? ($attr['posterImage']['small'] ?? null);
            $slug = $attr['slug'] ?? '';
            $out[] = [
                'title' => $attr['canonicalTitle'] ?? ($attr['titles']['en'] ?? 'Untitled'),
                'url' => $slug ? 'https://kitsu.io/anime/' . $slug : 'https://kitsu.io/anime',
                'thumbnail' => $poster,
                'description' => $attr['synopsis'] ?? null,
                'provider' => $this->name(),
            ];
        }
        return $out;
    }
}