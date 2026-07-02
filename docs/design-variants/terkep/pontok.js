// Térkép-mockupok közös minta-adatai (holborozzak.hu design-variánsok)
var PONTOK = [
  { t: 'Budapesti Borfesztivál',      lat: 47.4961, lng: 19.0398, d: 'szept. 10–13.', c: 'Budapest',     v: 'Budai Vár',            free: false, cats: ['Borfesztivál', 'Kóstoló'], reg: 'etyek-buda' },
  { t: 'Etyeki Piknik',               lat: 47.4460, lng: 18.7530, d: 'aug. 28–30.',   c: 'Etyek',        v: 'Újhegy',               free: false, cats: ['Piknik', 'Gasztronómia'], reg: 'etyek-buda' },
  { t: 'Soproni Borfesztivál',        lat: 47.6817, lng: 16.5845, d: 'aug. 15–17.',   c: 'Sopron',       v: 'Fő tér',               free: false, cats: ['Borfesztivál'],           reg: 'sopron' },
  { t: 'Tokaji Szüreti Napok',        lat: 48.1180, lng: 21.4090, d: 'okt. 2–4.',     c: 'Tokaj',        v: 'Fő utca',              free: true,  cats: ['Szüreti rendezvény'],     reg: 'tokaj' },
  { t: 'Tokaji Nyáresti Kóstoló',     lat: 48.1230, lng: 21.4150, d: 'júl. 11.',      c: 'Tokaj',        v: 'Pincesor',             free: false, cats: ['Kóstoló'],                reg: 'tokaj' },
  { t: 'Villányi Vörösbor Fesztivál', lat: 45.8680, lng: 18.4530, d: 'okt. 9–11.',    c: 'Villány',      v: 'Pincesor',             free: false, cats: ['Borfesztivál'],           reg: 'villany' },
  { t: 'Egri Bikavér Ünnep',          lat: 47.9025, lng: 20.3772, d: 'júl. 24–26.',   c: 'Eger',         v: 'Dobó tér',             free: false, cats: ['Gasztronómia'],           reg: 'eger' },
  { t: 'Balatonfüredi Borhetek',      lat: 46.9560, lng: 17.8890, d: 'júl. 10–26.',   c: 'Balatonfüred', v: 'Tagore sétány',        free: true,  cats: ['Borfesztivál'],           reg: 'balaton' },
  { t: 'Szent György-hegy Hajnalig',  lat: 46.8430, lng: 17.4380, d: 'júl. 18–20.',   c: 'Tapolca',      v: 'Szent György-hegy',    free: true,  cats: ['Borvidéki program'],      reg: 'balaton' },
  { t: 'Szekszárdi Pinceséta',        lat: 46.3470, lng: 18.7060, d: 'júl. 5.',       c: 'Szekszárd',    v: 'Pincesor',             free: false, cats: ['Kóstoló'],                reg: 'szekszard' }
];

var KEP = '../../../public/assets/hero.jpg';

function grapeDot() {
  return L.divIcon({ className: 'grape-dot', html: '', iconSize: [18, 18], iconAnchor: [9, 9], popupAnchor: [0, -10] });
}

function alapTerkep(elemId, opts) {
  var map = L.map(elemId, Object.assign({ scrollWheelZoom: true }, opts || {})).setView([47.16, 19.50], 7);
  L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    maxZoom: 19, subdomains: 'abcd', attribution: '&copy; OpenStreetMap, &copy; CARTO'
  }).addTo(map);
  return map;
}

function popupHtml(p) {
  var tags = p.cats.map(function (c) { return '<span class="tag">' + c + '</span>'; }).join('');
  if (p.free) { tags += '<span class="tag tag--free" style="background:#e6efe0;color:#3f5a2a;">Ingyenes</span>'; }
  return '<div class="mp">'
    + '<img class="mp__img" src="' + KEP + '" alt="">'
    + '<div class="mp__body"><b>' + p.t + '</b>'
    + '<span class="mp__date">' + p.d + '</span>'
    + '<span class="mp__loc">📍 ' + p.v + ', ' + p.c + '</span>'
    + '<span class="mp__tags">' + tags + '</span></div></div>';
}
