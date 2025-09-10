(function () {
  const q = document.getElementById('q');
  const searchBtn = document.getElementById('searchBtn');
  const clearBtn = document.getElementById('clearBtn');
  const resultsEl = document.getElementById('results');
  const alertsEl = document.getElementById('alerts');

  function alert(msg, type = 'info') {
    const el = document.createElement('div');
    el.className = `alert alert-${type}`;
    el.textContent = msg;
    alertsEl.appendChild(el);
    setTimeout(() => el.remove(), 5000);
  }

  function card(item) {
    const thumb = item.thumbnail || 'https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/svg/1f3a5.svg';
    return `
      <div class="col">
        <div class="card h-100 shadow-sm">
          <img src="${thumb}" class="card-img-top" alt="${escapeHtml(item.title || 'Anime')}">
          <div class="card-body d-flex flex-column">
            <h6 class="card-title">${escapeHtml(item.title || 'Untitled')}</h6>
            <p class="card-text small text-secondary flex-grow-1">${escapeHtml(item.description || '').slice(0, 160)}</p>
            <div class="d-flex justify-content-between align-items-center">
              <span class="badge text-bg-light">${escapeHtml(item.provider)}</span>
              ${item.url ? `<a class="btn btn-sm btn-primary" href="${item.url}" target="_blank" rel="noopener">Open</a>` : ''}
            </div>
          </div>
        </div>
      </div>
    `;
  }

  function escapeHtml(s) {
    if (!s) return '';
    return s.replace(/[&<>"']/g, m =&gt; ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
  }

  async function doSearch() {
    const term = (q.value || '').trim();
    if (!term) {
      alert('Enter a search term.', 'warning');
      q.focus();
      return;
    }
    alertsEl.innerHTML = '';
    resultsEl.innerHTML = '<div class="text-center py-5 w-100"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

    try {
      const res = await axios.get(`api/search.php?q=${encodeURIComponent(term)}`);
      const items = res.data?.results || [];
      if (!Array.isArray(items) || items.length === 0) {
        resultsEl.innerHTML = '<div class="text-center text-secondary py-5 w-100">No results.</div>';
        return;
      }
      resultsEl.innerHTML = items.map(card).join('');
    } catch (e) {
      console.error(e);
      resultsEl.innerHTML = '';
      alert('Search failed. See console for details.', 'danger');
    }
  }

  searchBtn.addEventListener('click', doSearch);
  clearBtn.addEventListener('click', () => {
    q.value = '';
    resultsEl.innerHTML = '';
    alertsEl.innerHTML = '';
    q.focus();
  });
  q.addEventListener('keydown', (ev) => {
    if (ev.key === 'Enter') doSearch();
  });
})();