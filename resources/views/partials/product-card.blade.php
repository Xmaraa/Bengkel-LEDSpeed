<article class="card pcard">
  <span class="chip-tag">{{ $p->kategori_label }}</span>
  <h3>{{ $p->merek }} {{ $p->nama }}</h3>
  @if($p->spesifikasi)<p class="spec">{{ $p->spesifikasi }}</p>@endif
  <p class="price">Rp{{ number_format($p->harga, 0, ',', '.') }}</p>
</article>
