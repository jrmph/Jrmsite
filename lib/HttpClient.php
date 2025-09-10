<?php

class HttpClient
{
    private array $defaultHeaders;
    private int $timeout;
    private ?string $cookieFile;

    public function __construct(array $headers = [], int $timeout = 15, ?string $cookieFile = null)
    {
        $this->defaultHeaders = $headers ?: [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
            'Accept: text/html,application/json;q=0.9,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.9',
            'Connection: keep-alive',
        ];
        $this->timeout = $timeout;
        $this->cookieFile = $cookieFile;
    }

    public function get(string $url, array $headers = []): array
    {
        return $this->request('GET', $url, null, $headers);
    }

    public function request(string $method, string $url, ?array $data = null, array $headers = []): array
    {
        $ch = curl_init();
        $opts = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => '', // allow all supported encodings (gzip)
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => array_merge($this->defaultHeaders, $headers),
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ];
        if ($this->cookieFile) {
            $opts[CURLOPT_COOKIEJAR] = $this->cookieFile;
            $opts[CURLOPT_COOKIEFILE] = $this->cookieFile;
        }
        if (strtoupper($method) !== 'GET') {
            $opts[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
        }
        if ($data !== null) {
            $opts[CURLOPT_POSTFIELDS] = http_build_query($data);
        }
        curl_setopt_array($ch, $opts);
        $body = curl_exec($ch);
        $err = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return [
            'ok' => $err === '' && is_string($body),
            'status' => (int)($info['http_code'] ?? 0),
            'headers' => $info,
            'body' => is_string($body) ? $body : '',
            'error' => $err,
        ];
    }
}