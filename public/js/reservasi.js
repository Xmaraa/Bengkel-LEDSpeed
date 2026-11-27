/* Form reservasi: ambil sisa kuota jam dari server (route reservasi.slots). */
const $ = s => document.querySelector(s);
const wrap = $('#slots'), tgl = $('#tgl'), jam = $('#jam');
let pick = wrap.dataset.jam || '';

async function loadSlots() {
  const d = tgl.value;
  if (!d) { wrap.innerHTML = '<p class="err" style="color:var(--muted)">Pilih tanggal dulu.</p>'; return; }
  wrap.innerHTML = '<p class="err" style="color:var(--muted)">Memuat jam…</p>';
  try {
    const res = await fetch(wrap.dataset.url + '?tanggal=' + encodeURIComponent(d), { headers: { 'Accept': 'application/json' } });
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const data = await res.json();
    if (data.closed) {
      pick = ''; jam.value = '';
      wrap.innerHTML = '<p class="err">Bengkel tutup hari Minggu. Pilih tanggal lain.</p>';
      return;
    }
    wrap.innerHTML = data.slots.map(s => {
      const off = s.lewat || s.sisa <= 0;
      if (off && pick === s.jam) pick = '';
      return `<button type="button" class="slot" data-j="${s.jam}" aria-pressed="${pick === s.jam}" ${off ? 'disabled' : ''}><b>${s.jam}</b><small>${s.lewat ? 'Lewat' : s.sisa <= 0 ? 'Penuh' : 'Sisa ' + s.sisa}</small></button>`;
    }).join('');
    jam.value = pick;
  } catch (e) {
    wrap.innerHTML = '<p class="err">Gagal memuat jam. Muat ulang halaman.</p>';
  }
}

wrap.addEventListener('click', e => {
  const b = e.target.closest('.slot');
  if (!b || b.disabled) return;
  pick = b.dataset.j; jam.value = pick;
  wrap.querySelectorAll('.slot').forEach(x => x.setAttribute('aria-pressed', x === b));
});
tgl.addEventListener('change', () => { pick = ''; jam.value = ''; loadSlots(); });
loadSlots();
