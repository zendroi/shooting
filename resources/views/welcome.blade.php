<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Input Kode Akses</title>
  <style>
    :root {
      --bg-black: #000000;
      --text: #e5e7eb;
      --muted: #9ca3af;
      --accent: #22c55e;
      --danger: #ef4444;
      --green-bg: #16a34a;
      --red-bg: #dc2626;
      --btn: #374151;
      --btn-hover: #4b5563;
      --ring: #f59e0b;
    }

    * { box-sizing: border-box; }

    html, body { height: 100%; }

    body {
      margin: 0;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
      color: var(--text);
      background: var(--bg-black);
    }

    .screen { display: none; min-height: 100vh; width: 100%; }
    .screen.active { display: flex; }

    .center {
      margin: 0 auto;
      padding: 32px 20px;
      width: 100%;
      max-width: 720px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 24px;
      text-align: center;
    }

    h1 { margin: 0; font-size: clamp(24px, 4vw, 36px); letter-spacing: 0.04em; font-weight: 700; }

    .subtitle { color: var(--muted); font-size: 14px; }

    .inputs {
      display: flex;
      gap: 12px;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
    }

    .digit {
      width: 68px;
      height: 72px;
      border-radius: 12px;
      border: 1px solid #1f2937;
      background: #0b0b0b;
      text-align: center;
      color: var(--text);
      font-size: 28px;
      font-weight: 700;
      outline: none;
      transition: border-color .15s, box-shadow .15s, transform .08s ease-out;
    }

    .digit:focus { border-color: var(--ring); box-shadow: 0 0 0 4px rgba(245, 158, 11, .12); }

    .hint { color: var(--muted); font-size: 12px; }

    /* Loading overlay */
    .overlay { position: fixed; inset: 0; background: rgba(0,0,0,.9); display: none; align-items: center; justify-content: center; z-index: 50; backdrop-filter: blur(1px); }
    .overlay.active { display: flex; }
    .spinner { width: 70px; height: 70px; border-radius: 50%; border: 6px solid rgba(255,255,255,.15); border-top-color: #ffffff; animation: spin 1s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Result screens */
    .result { min-height: 100vh; display: none; align-items: center; justify-content: center; text-align: center; }
    .result.active { display: flex; }
    .result h2 { margin: 0 0 16px; font-size: clamp(28px, 5vw, 48px); font-weight: 900; letter-spacing: .06em; text-transform: uppercase; color: #0a0a0a; text-shadow: 0 1px 0 rgba(255,255,255,.3); }
    .result p { margin: 0; color: #0a0a0a; font-weight: 600; }
    .result .card { background: rgba(255,255,255,.88); padding: 28px 28px 22px; border-radius: 16px; box-shadow: 0 20px 70px rgba(0,0,0,.35); border: 1px solid rgba(255,255,255,.7); min-width: min(92vw, 560px); }
    .result .card .btn { margin-top: 18px; }

    .bg-green { background: var(--green-bg); }
    .bg-red { background: var(--red-bg); }

    .btn { background: var(--btn); color: var(--text); border: 1px solid #111827; border-radius: 10px; padding: 12px 18px; font-size: 16px; font-weight: 600; letter-spacing: .02em; cursor: pointer; transition: background .15s ease, transform .06s ease-out, opacity .15s ease; }
    .btn:hover { background: var(--btn-hover); }
    .btn:active { transform: translateY(1px); }
    .btn:disabled { opacity: .45; cursor: not-allowed; }

    /* Fullscreen toggle */
    .fs-toggle { position: fixed; top: 12px; right: 12px; z-index: 60; }
    .hidden-while-fs { opacity: 0; color: transparent; background: transparent; border-color: transparent; box-shadow: none; transition: opacity .2s ease; }

    @media (max-width: 420px) { .digit { width: 58px; height: 64px; font-size: 24px; } }
  </style>
</head>
<body>
  <div class="fs-toggle">
    <button id="btn-fullscreen" class="btn">Full Layar</button>
  </div>
  <!-- Input Screen (Background Hitam) -->
  <section id="input-screen" class="screen active" aria-label="Input Kode Akses">
    <div class="center">
      <h1>Input Kode Akses</h1>
      <div class="subtitle">Masukkan 2 atau 5 angka, lalu tekan Periksa.</div>

      <div class="inputs" role="group" aria-label="Masukan Kode">
        <input class="digit" type="text" inputmode="numeric" maxlength="2" aria-label="Angka 1" />
        <input class="digit" type="text" inputmode="numeric" maxlength="2" aria-label="Angka 2" />
        <input class="digit" type="text" inputmode="numeric" maxlength="2" aria-label="Angka 3" />
        <input class="digit" type="text" inputmode="numeric" maxlength="2" aria-label="Angka 4" />
        <input class="digit" type="text" inputmode="numeric" maxlength="2" aria-label="Angka 5" />
      </div>

      <button id="btn-check" class="btn" disabled>Periksa</button>
      <div class="hint">Gunakan hanya angka (maks 2 digit per kotak). Anda boleh mengisi hanya 2 kotak atau semua 5 kotak.</div>
    </div>
  </section>

  <!-- Loading Overlay -->
  <div id="loading" class="overlay" aria-live="polite" aria-busy="true">
    <div class="spinner" aria-label="Memproses"></div>
  </div>

  <!-- Result Screens -->
  <section id="result-ok" class="result bg-green" aria-label="Hasil: Kode Benar">
    <div class="card">
      <h2>Kode Benar</h2>
      <p>Akses diterima.</p>
      <button class="btn" onclick="resetFlow()">Kembali</button>
    </div>
  </section>

  <section id="result-fail" class="result bg-red" aria-label="Hasil: Kode Salah">
    <div class="card">
      <h2>Kode Salah</h2>
      <p>Urutan/angka tidak sesuai.</p>
      <button class="btn" onclick="resetFlow()">Kembali</button>
    </div>
  </section>

  <script>
    // Kode valid: panjang 2 atau 5
    const validCodes2 = [ [68, 69], [77, 65] ];
    const validCodes5 = [ [80, 75, 75, 77, 66], [69, 84, 65, 76, 65] ];

    const inputScreen = document.getElementById('input-screen');
    const resultOk = document.getElementById('result-ok');
    const resultFail = document.getElementById('result-fail');
    const loading = document.getElementById('loading');

    const inputs = Array.from(document.querySelectorAll('.digit'));
    const btnCheck = document.getElementById('btn-check');

    // Fokus awal
    inputs[0].focus();

    // Input handling
    inputs.forEach((el, index) => {
      el.addEventListener('input', (e) => {
        const onlyDigits = e.target.value.replace(/\D+/g, '').slice(0, 2);
        e.target.value = onlyDigits;
        if (onlyDigits.length === 2 && index < inputs.length - 1) {
          inputs[index + 1].focus();
          inputs[index + 1].select();
        }
        updateCheckButtonState();
      });

      el.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && el.value.length === 0 && index > 0) {
          inputs[index - 1].focus();
          inputs[index - 1].select();
        }
        if (e.key === 'ArrowLeft' && index > 0) {
          inputs[index - 1].focus();
          inputs[index - 1].select();
        }
        if (e.key === 'ArrowRight' && index < inputs.length - 1) {
          inputs[index + 1].focus();
          inputs[index + 1].select();
        }
        if (e.key === 'Enter' && !btnCheck.disabled) {
          verifyNow();
        }
      });
    });

    btnCheck.addEventListener('click', () => {
      verifyNow();
    });

    function getFilledValues() {
      // Ambil nilai yang terisi saja, urut dari kiri ke kanan
      return inputs.map(i => i.value.trim()).filter(v => v.length > 0).map(v => parseInt(v, 10));
    }

    function updateCheckButtonState() {
      const filled = inputs.filter(i => /^\d{1,2}$/.test(i.value));
      const filledCount = filled.length;
      const enable = (filledCount === 2 || filledCount === 5);
      btnCheck.disabled = !enable;
    }

    function isValidCode(values) {
      if (values.length === 2) {
        return validCodes2.some(code => code.every((v, i) => v === values[i]));
      }
      if (values.length === 5) {
        return validCodes5.some(code => code.every((v, i) => v === values[i]));
      }
      return false;
    }

    function verifyNow() {
      const values = getFilledValues();
      const isCorrect = isValidCode(values);
      showLoadingThenResult(isCorrect);
    }

    function showLoadingThenResult(isCorrect) {
      inputScreen.classList.remove('active');
      loading.classList.add('active');
      setTimeout(() => {
        loading.classList.remove('active');
        (isCorrect ? resultOk : resultFail).classList.add('active');
      }, 5000);
    }

    function resetFlow() {
      resultOk.classList.remove('active');
      resultFail.classList.remove('active');
      inputs.forEach(i => i.value = '');
      inputScreen.classList.add('active');
      inputs[0].focus();
      updateCheckButtonState();
    }

    window.resetFlow = resetFlow;

    // Init state
    updateCheckButtonState();

    // Fullscreen helpers
    const fsBtn = document.getElementById('btn-fullscreen');

    function isFullscreen() {
      return document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement;
    }

    function enterFullscreen() {
      const el = document.documentElement;
      if (el.requestFullscreen) return el.requestFullscreen();
      if (el.webkitRequestFullscreen) return el.webkitRequestFullscreen();
      if (el.msRequestFullscreen) return el.msRequestFullscreen();
      // Fallback notice
      alert('Fitur full layar tidak didukung oleh browser ini. Coba tekan F11.');
      return Promise.reject();
    }

    function exitFullscreen() {
      if (document.exitFullscreen) return document.exitFullscreen();
      if (document.webkitExitFullscreen) return document.webkitExitFullscreen();
      if (document.msExitFullscreen) return document.msExitFullscreen();
      return Promise.resolve();
    }

    function updateFsButton() {
      const active = !!isFullscreen();
      fsBtn.textContent = active ? 'Keluar Full Layar' : 'Full Layar';
      fsBtn.classList.toggle('hidden-while-fs', active);
    }

    function tryLockLandscape() {
      if (screen.orientation && screen.orientation.lock) {
        screen.orientation.lock('landscape').catch(() => {});
      }
    }

    fsBtn.addEventListener('click', () => {
      if (isFullscreen()) {
        exitFullscreen().finally(updateFsButton);
      } else {
        enterFullscreen().then(() => {
          tryLockLandscape();
          updateFsButton();
        }).catch(() => {});
      }
    });

    document.addEventListener('fullscreenchange', updateFsButton);
    document.addEventListener('webkitfullscreenchange', updateFsButton);
    document.addEventListener('msfullscreenchange', updateFsButton);

    // Set initial label
    updateFsButton();
  </script>
</body>
</html> 
