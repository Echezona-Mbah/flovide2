@php $activeMode = old('mode', session('mode', $mode ?? 'live')); @endphp

{{-- MODE BANNER: thin strip, sits above everything, never affects layout --}}
<div id="modeBanner"
     style="position:fixed;top:0;left:0;right:0;z-index:9999;height:32px;display:flex;align-items:center;justify-content:space-between;padding:0 16px;font-family:inherit;transition:background 0.3s;"
     class="{{ $activeMode === 'test' ? 'bg-amber-400' : 'bg-emerald-600' }}">

  {{-- Left: dot + text --}}
  <div style="display:flex;align-items:center;gap:8px;">
    <span id="bannerDot"
          style="width:7px;height:7px;border-radius:50%;animation:pulse 1.5s infinite;"
          class="{{ $activeMode === 'test' ? 'bg-amber-900/60' : 'bg-white/60' }}"></span>
    <span id="bannerLabel"
          style="font-size:10px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;line-height:1;"
          class="{{ $activeMode === 'test' ? 'text-amber-900' : 'text-white' }}">
      {{ $activeMode === 'test' ? 'Test Mode — No real transactions' : 'Live Mode — Real transactions active' }}
    </span>
  </div>

  {{-- Right: Live / Test pill --}}
  <div id="bannerPill"
       style="display:flex;align-items:center;gap:2px;border-radius:999px;padding:2px;"
       class="{{ $activeMode === 'test' ? 'bg-amber-500/30' : 'bg-emerald-700/40' }}">
    <button id="bannerLiveBtn" onclick="switchBannerMode('live')"
            style="border:none;cursor:pointer;border-radius:999px;padding:2px 10px;font-size:10px;font-weight:700;transition:all .2s;"
            class="{{ $activeMode === 'live' ? 'bg-white text-emerald-700 shadow' : 'bg-transparent text-white/70' }}">
      Live
    </button>
    <button id="bannerTestBtn" onclick="switchBannerMode('test')"
            style="border:none;cursor:pointer;border-radius:999px;padding:2px 10px;font-size:10px;font-weight:700;transition:all .2s;"
            class="{{ $activeMode === 'test' ? 'bg-white text-amber-700 shadow' : 'bg-transparent text-white/70' }}">
      Test
    </button>
  </div>
</div>

{{-- Push page content down by exactly banner height --}}
<div style="height:32px;"></div>

<style>
  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50%       { opacity: .4; }
  }
</style>

<script>
  function switchBannerMode(mode) {
    const banner  = document.getElementById('modeBanner');
    const label   = document.getElementById('bannerLabel');
    const dot     = document.getElementById('bannerDot');
    const pill    = document.getElementById('bannerPill');
    const liveBtn = document.getElementById('bannerLiveBtn');
    const testBtn = document.getElementById('bannerTestBtn');

    if (mode === 'live') {
      banner.classList.replace('bg-amber-400',    'bg-emerald-600');
      pill.classList.replace('bg-amber-500/30',   'bg-emerald-700/40');
      label.textContent = 'Live Mode — Real transactions active';
      label.classList.replace('text-amber-900',   'text-white');
      dot.classList.replace('bg-amber-900/60',    'bg-white/60');
      liveBtn.classList.add('bg-white','text-emerald-700','shadow');
      liveBtn.classList.remove('bg-transparent','text-white/70');
      testBtn.classList.remove('bg-white','text-amber-700','shadow');
      testBtn.classList.add('bg-transparent','text-white/70');
    } else {
      banner.classList.replace('bg-emerald-600',  'bg-amber-400');
      pill.classList.replace('bg-emerald-700/40', 'bg-amber-500/30');
      label.textContent = 'Test Mode — No real transactions';
      label.classList.replace('text-white',       'text-amber-900');
      dot.classList.replace('bg-white/60',        'bg-amber-900/60');
      testBtn.classList.add('bg-white','text-amber-700','shadow');
      testBtn.classList.remove('bg-transparent','text-white/70');
      liveBtn.classList.remove('bg-white','text-emerald-700','shadow');
      liveBtn.classList.add('bg-transparent','text-white/70');
    }

    // Sync webhook page form if present
    const modeInput = document.getElementById('modeInput');
    if (modeInput) modeInput.value = mode;

    document.querySelectorAll('.mode-tab').forEach(t => {
      const active = t.dataset.mode === mode;
      t.classList.toggle('bg-[#162033]',   active);
      t.classList.toggle('text-white',     active);
      t.classList.toggle('text-slate-600', !active);
    });

    document.querySelectorAll('.mode-panel').forEach(p => {
      p.classList.toggle('hidden', p.dataset.panel !== mode);
    });
  }

  
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const modeButtons = document.querySelectorAll('[data-mode-switch]');
  // Each button should have data-mode-switch="live" or data-mode-switch="test"

  modeButtons.forEach(btn => {
    btn.addEventListener('click', async () => {
      const mode = btn.dataset.modeSwitch;

      try {
        const res = await fetch("{{ route('switch.mode') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ mode })
        });

        const data = await res.json();

        if (data.success) {
          // Full reload so the controller re-renders with new session mode
          window.location.reload();
        } else {
          Swal.fire('Error', 'Could not switch mode', 'error');
        }
      } catch (err) {
        console.error('[mode switch] failed:', err);
        Swal.fire('Error', 'Network error while switching mode', 'error');
      }
    });
  });
});
</script><script>
async function switchBannerMode(mode) {
  const liveBtn = document.getElementById('bannerLiveBtn');
  const testBtn = document.getElementById('bannerTestBtn');

  // Optional: disable buttons while request is in flight
  liveBtn.disabled = true;
  testBtn.disabled = true;

  try {
    const res = await fetch("{{ route('switch.mode') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ mode })
    });

    const data = await res.json();

    if (data.success) {
      // Reload so controller re-renders with the new session mode
      window.location.reload();
    } else {
      liveBtn.disabled = false;
      testBtn.disabled = false;
      if (window.Swal) {
        Swal.fire('Error', 'Could not switch mode', 'error');
      } else {
        alert('Could not switch mode');
      }
    }
  } catch (err) {
    console.error('[mode switch] failed:', err);
    liveBtn.disabled = false;
    testBtn.disabled = false;
    if (window.Swal) {
      Swal.fire('Error', 'Network error while switching mode', 'error');
    } else {
      alert('Network error while switching mode');
    }
  }
}
</script>