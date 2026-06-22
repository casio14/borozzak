/* holborozzak.hu — progresszív fejlesztés.
 * A tabok és szűrők csak az #esemenyek-region tartalmát töltik újra (nincs
 * felugrás a lap tetejére), az URL frissül. JS nélkül minden sima linkként/
 * űrlapként működik (szerveroldali renderelés), így SEO-barát marad. */
(function () {
  'use strict';
  document.documentElement.classList.add('js');

  var REGION = 'esemenyek-region';

  function buildQuery(form) {
    var params = new URLSearchParams(new FormData(form));
    var clean = new URLSearchParams();
    params.forEach(function (v, k) { if (v !== '') { clean.append(k, v); } });
    var qs = clean.toString();
    return qs ? ('?' + qs) : './';
  }

  function load(url, push) {
    var current = document.getElementById(REGION);
    if (!current) { window.location.href = url; return; }
    current.classList.add('is-loading');

    fetch(url, { headers: { 'X-Requested-With': 'fetch' } })
      .then(function (res) { return res.text(); })
      .then(function (html) {
        var doc = new DOMParser().parseFromString(html, 'text/html');
        var next = doc.getElementById(REGION);
        if (!next) { window.location.href = url; return; }
        current.replaceWith(document.importNode(next, true));
        if (push) { history.pushState({ url: url }, '', url); }
        if (doc.title) { document.title = doc.title; }
      })
      .catch(function () { window.location.href = url; });
  }

  // Tab- és „Szűrők törlése” linkek
  document.addEventListener('click', function (e) {
    var a = e.target.closest('#' + REGION + ' .tabs a, #' + REGION + ' .facets__clear');
    if (!a) { return; }
    e.preventDefault();
    load(a.getAttribute('href'), true);
  });

  // Legördülő változás → azonnali szűrés
  document.addEventListener('change', function (e) {
    var sel = e.target.closest('#' + REGION + ' .facets select');
    if (!sel || !sel.form) { return; }
    load(buildQuery(sel.form), true);
  });

  // Űrlap-küldés (no-JS gomb) elfogása
  document.addEventListener('submit', function (e) {
    var form = e.target.closest('#' + REGION + ' .facets');
    if (!form) { return; }
    e.preventDefault();
    load(buildQuery(form), true);
  });

  // Vissza/előre gomb
  window.addEventListener('popstate', function () {
    load(window.location.href, false);
  });
})();
