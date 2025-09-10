<?php
require_once __DIR__ . '/../lib/HttpClient.php';
require_once __DIR__ . '/../lib/Cache.php';
require_once __DIR__ . '/ProviderInterface.php';

class ANNProvider implements ProviderInterface
{
    private HttpClient $http;
    private Cache $cache;

    public function __construct()
    {
        $this->http = new HttpClient([
            'User-Agent: AnimeHub/1.0 (+https://example.local)',
            'Accept: text/html,application/xhtml+xml;q=0.9,*/*;q=0.8',
        ], 15);
        $this->cache = new Cache();
    }

    public function name(): string
    {
        return 'AnimeNewsNetwork';
    }

    public function search(string $query): array
    {
        $q = trim($query);
        if ($q === '') return [];
        $url = 'https://www.animenewsnetwork.com/encyclopedia/search/name?q=' . rawurlencode($q);

        $cacheKey = 'ann_' . md5($url);
        $cached = $this->cache->get($cacheKey);
        $html = $cached;
        if ($html === null) {
            $res = $this->http->get($url);
            if (!$res['ok'] || $res['status'] !== 200) {
                return [];
            }
            $html = $res['body'];
            $this->cache->set($cacheKey, $html);
        }

        $doc = new DOMDocument();
        // Suppress warnings from malformed HTML
        @$doc->loadHTML($html);
        $xpath = new DOMXPath($doc);

        // On ANN search results, entries are within .encyc-search-results
        $nodes = $xpath->query("//div[contains(@class, 'encyc-search-results')]//li/a");
        $results = [];
        if ($nodes && $nodes->length > 0) {
            $count = 0;
            foreach ($nodes as $a) {
                $title = trim($a->textContent ?? '');
                $href = $a->getAttribute('href');
                if (!$href) continue;
                $urlFull = $this->absoluteUrl('https://www.animenewsnetwork.com', $href);

                $results[] = [
                    'title' => $title !== '' ? $title : 'Untitled',
                    'url' => $urlFull,
                    'thumbnail' => null, // Not present on search page
                    'description' => null,
                    'provider' => $this->name(),
                ];
                $count++;
                if ($count >= 18) break;
            }
        }

        return $results;
    }

    private function absoluteUrl(string $base, string $href): string
    {
        if (str_starts_with($href, 'http://') || str_starts_with($href, 'https://')) {
            return $href;
        }
        return rtrim($base, '/') . '/' . ltrim($href, '/');
    }
}