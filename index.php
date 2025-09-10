<?php
// Simple Anime Meta-Aggregator
// Tech: PHP + cURL + DOM + HTML/CSS/JS using CDN assets (jsDelivr/cdnjs)
// Note: Respect each site's Terms of Service and robots.txt. For sites with anti-bot protection,
// server-side scraping may fail without a headless browser. This project focuses on legal,
// metadata-only aggregation for discovery (title/thumbnail/link), not re-hosting streams.
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Anime Meta-Aggregator</title>
  <meta name="description" content="Search anime metadata across multiple providers.">
  <!-- Bootstrap via jsDelivr -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Icons via cdnjs -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" integrity="sha512-eX+czn6t3M1AHP2s0ayH4B4QZXeGd1tO0t0zY2vW2lqE24nQHA+Nf9PMYGU30sYjJZqSxM7Bf1F+9Qd8Q3qkGg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
  <div class="container">
    <a class="navbar-brand" href="/">Anime Hub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="/">Search</a></li>
        <li class="nav-item"><a class="nav-link" href="README.html" target="_blank">Docs</a></li>
      </ul>
      <span class="navbar-text small text-secondary">Demo aggregator · PHP + cURL + DOM</span>
    </div>
  </div>
</nav>

<main class="container my-4">
  <div class="row g-3 align-items-center">
    <div class="col-md-8">
      <input id="q" type="text" class="form-control form-control-lg" placeholder="Search anime (e.g., Bleach, One Piece, Jujutsu Kaisen)">
    </div>
    <div class="col-md-2 d-grid">
      <button id="searchBtn" class="btn btn-primary btn-lg"><i class="bi bi-search"></i> Search</button>
    </div>
    <div class="col-md-2 d-grid">
      <button id="clearBtn" class="btn btn-outline-secondary btn-lg"><i class="bi bi-x-circle"></i> Clear</button>
    </div>
  </div>

  <div id="providers" class="my-3">
    <span class="badge text-bg-secondary me-1">Providers enabled:</span>
    <span class="badge text-bg-info">Kitsu (API)</span>
    <span class="badge text-bg-info">Anime News Network (scrape)</span>
  </div>

  <div id="alerts"></div>

  <div id="results" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 mt-2"></div>
</main>

<footer class="bg-body-tertiary border-top py-4 mt-auto">
  <div class="container small text-secondary">
    <div class="d-flex justify-content-between">
      <div>
        Built with
        <a href="https://www.php.net/manual/en/book.curl.php" target="_blank" rel="noopener">PHP cURL</a>,
        <a href="https://developer.mozilla.org/docs/Web/API/Document_Object_Model" target="_blank" rel="noopener">DOM</a>,
        <a href="https://getbootstrap.com/" target="_blank" rel="noopener">Bootstrap</a>,
        <a href="https://cdn.jsdelivr.net/" target="_blank" rel="noopener">jsDelivr</a>,
        <a href="https://cdnjs.com/" target="_blank" rel="noopener">cdnjs</a>.
      </div>
      <div>
        <a href="README.html" target="_blank" rel="noopener">How it works</a>
      </div>
    </div>
  </div>
</footer>

<!-- Axios via cdnjs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.7.2/axios.min.js" integrity="sha512-/N7n2xUeWq1v2gRzseJm3yQKxVIkFIalh4j3E3mDYVeZl8qF4T6L8r3g9i2rVxW9uVIVk9B7mXjXz8cZ6oO2wA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Bootstrap JS via jsDelivr -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="assets/js/app.js"></script>
</body>
</html>