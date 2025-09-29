<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Maharaja Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              brandBlue1: '#2563eb', // primary brand color stop 1
              brandBlue2: '#1d4ed8', // primary brand color stop 2
              successGreen: '#10b981', // accent for success
              warnYellow: '#f59e0b', // accent for waiting
              neutralGray: '#374151', // neutral gray for text
            },
            boxShadow: {
              glow: '0 10px 30px rgba(37,99,235,0.45)',
            },
          },
        },
      }
    </script>
    <script>
      (function initThemeEarly() {
        try {
          const pref = localStorage.getItem('theme')
          const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
          const shouldDark = pref ? pref === 'dark' : prefersDark
          const root = document.documentElement
          if (shouldDark) root.classList.add('dark'); else root.classList.remove('dark')
        } catch(_) {}
      })();
    </script>
    <style>
      /* Smooth scroll and basic motion preferences */
      html { scroll-behavior: smooth; }
      @media (prefers-reduced-motion: reduce) {
        html { scroll-behavior: auto; }
        .transition-all, .transition-colors, .transition { transition: none !important; }
      }
    </style>
  </head>
  <body class="antialiased bg-white text-neutral-800 dark:bg-neutral-900 dark:text-neutral-100">
    <!-- Navigation -->
    <header id="siteHeader" class="fixed top-0 inset-x-0 z-50 transition-colors">
      <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <a href="#home" class="font-semibold tracking-tight text-white drop-shadow">Maharaja Management</a>
          <button
            id="mobileMenuBtn"
            class="lg:hidden inline-flex items-center justify-center rounded-md p-2 text-white/90 hover:text-white"
            aria-label="Open menu"
            aria-expanded="false"
          >
            <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M3 5h14v2H3V5zm0 4h14v2H3V9zm0 4h14v2H3v-2z" clip-rule="evenodd" />
            </svg>
          </button>
          <div class="hidden lg:flex items-center gap-6">
            <a href="#home" class="text-white/90 hover:text-white text-sm font-medium">Home</a>
            <a href="#payment" class="text-white/90 hover:text-white text-sm font-medium">Payment</a>
            <a href="#vote" data-requires-paid class="text-white/90 hover:text-white text-sm font-medium">Vote</a>
            <a href="#results" class="text-white/90 hover:text-white text-sm font-medium">Results</a>
            <!-- add desktop theme toggle -->
            <button id="themeToggleDesktop" type="button"
              class="ml-2 rounded-md bg-white/10 px-3 py-1.5 text-xs font-medium text-white hover:bg-white/15"
              aria-pressed="false" aria-label="Toggle dark mode">
              Dark: Off
            </button>
          </div>
        </div>
        <div id="mobileMenu" class="lg:hidden hidden pb-4">
          <div class="flex flex-col gap-2">
            <a href="#home" class="text-white/90 hover:text-white text-sm font-medium py-1">Home</a>
            <a href="#payment" class="text-white/90 hover:text-white text-sm font-medium py-1">Payment</a>
            <a href="#vote" data-requires-paid class="text-white/90 hover:text-white text-sm font-medium py-1">Vote</a>
            <a href="#results" class="text-white/90 hover:text-white text-sm font-medium py-1">Results</a>
            <!-- add mobile theme toggle -->
            <button id="themeToggleMobile" type="button"
              class="mt-2 self-start rounded-md bg-white/10 px-3 py-1.5 text-xs font-medium text-white hover:bg-white/15"
              aria-pressed="false" aria-label="Toggle dark mode">
              Dark: Off
            </button>
          </div>
        </div>
      </nav>
    </header>

    <!-- Home / Hero -->
    <section id="home" class="relative min-h-[92vh] flex items-center">
    <div class="absolute inset-0">
  <img src="/img/background.png" class="w-full h-full object-cover brightness-75" />
  <div class="absolute inset-0 bg-black/30"></div> <!-- optional overlay untuk gelapkan -->
</div>

      <div class="relative mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-28 sm:py-32 text-center text-white">
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-balance">
          LKBB MAHASERTA 2025
        </h1>
        <p class="mt-3 sm:mt-4 text-white/90 leading-relaxed text-pretty">
        Pilih kandidat sekolah favorit Anda. Pembayaran melalui QRIS diperlukan untuk memastikan partisipasi yang adil dan terverifikasi.
        </p>
        <div class="mt-8">
          <a
            id="ctaPayVote"
            href="#payment"
            class="inline-flex items-center justify-center rounded-xl bg-white/10 px-6 py-3 text-base sm:text-lg font-semibold shadow-glow ring-1 ring-white/20 hover:scale-[1.03] hover:bg-white/15 transition transform"
          >
            Bayar &amp; Vote Sekarang
          </a>
        </div>
      </div>

      <!-- Live Preview directly below hero content -->
      <div class="absolute bottom-0 left-0 right-0 translate-y-1/2">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
          <!-- add dark styles for card -->
          <div class="rounded-2xl bg-white dark:bg-neutral-800 dark:text-neutral-100 border border-transparent dark:border-neutral-700 shadow-lg p-5 sm:p-6">
            <div class="flex items-center justify-between mb-3">
              <h3 class="font-semibold text-neutral-800 dark:text-neutral-100">Live Results Preview</h3>
              <button
                id="refreshPreviewBtn"
                class="text-xs rounded-md px-3 py-1.5 bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 transition"
                type="button"
              >
                Refresh
              </button>
            </div>
            <div id="previewBars" class="space-y-3">
              <!-- injected preview progress bars -->
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Spacer to account for preview overlap -->
    <div class="h-28 sm:h-24"></div>

    <!-- Payment -->
    <section id="payment" class="py-14 sm:py-16">
      <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-6">
          <!-- add dark styles for payment card -->
          <div class="rounded-2xl bg-white dark:bg-neutral-800 dark:text-neutral-100 border border-transparent dark:border-neutral-700 shadow-lg p-6">
            <h2 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">QRIS Payment</h2>
            <p class="text-sm text-neutral-600 dark:text-neutral-300 mt-1">Scan and pay to unlock voting</p>

            <div class="mt-5 grid sm:grid-cols-2 gap-5 items-start">
              <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-3 bg-white dark:bg-neutral-800">
                <img
                  src="/placeholder.svg?height=360&width=360"
                  alt="QRIS dummy code"
                  class="w-full h-auto rounded-lg"
                />
                <p class="text-xs text-neutralGray/60 mt-2 text-center">Sample QRIS code for demo</p>
              </div>

              <div>
                <!-- order details panel dark styles -->
                <div class="rounded-xl bg-neutral-50 dark:bg-neutral-900/40 border border-neutral-200 dark:border-neutral-700 p-4">
                  <div class="flex items-center justify-between">
                    <label for="qtyVotes" class="text-sm text-neutral-600 dark:text-neutral-300">Votes to buy (1–1000)</label>
                    <input id="qtyVotes" type="number" min="1" max="1000" value="1" class="w-24 text-right border rounded px-2 py-1 bg-white dark:bg-neutral-800 dark:border-neutral-700" />
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-neutral-600 dark:text-neutral-300">Order ID</span>
                    <span id="orderId" class="font-semibold text-neutral-800 dark:text-neutral-100">#ORD-0000</span>
                  </div>
                  <div class="mt-2 flex items-center justify-between">
                    <span class="text-sm text-neutral-600 dark:text-neutral-300">Amount</span>
                    <span id="orderAmount" class="font-bold text-neutral-800 dark:text-neutral-100">Rp 10.000</span>
                  </div>
                  <div class="mt-4 rounded-lg bg-yellow-50 border border-yellow-200 p-3 flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-warnYellow"></span>
                    </span>
                    <span id="paymentStatus" class="text-sm font-medium text-amber-700">
                      Waiting for Payment...
                    </span>
                  </div>
                  <div class="mt-3">
                    <label class="block text-sm text-neutral-600 dark:text-neutral-300 mb-1">Upload payment proof</label>
                    <input id="proofFile" type="file" accept="image/*,.pdf" class="w-full text-sm" />
                  </div>
                  <div class="grid grid-cols-2 gap-2 mt-3">
                    <button
                      id="createOrderBtn"
                      class="w-full rounded-lg bg-neutral-200 hover:bg-neutral-300 text-neutral-800 font-semibold py-2 transition"
                      type="button"
                    >
                      Create/Update Order
                    </button>
                    <button
                      id="simulatePaymentBtn"
                      class="w-full rounded-lg bg-brandBlue1 hover:bg-brandBlue2 text-white font-semibold py-2.5 transition shadow disabled:opacity-60 disabled:cursor-not-allowed"
                      type="button"
                    >
                      Submit Bukti Pembayaran & Sedang Diproses
                    </button>
                  </div>
                  <p class="text-xs text-neutralGray/60 mt-2">
                    For demo purposes only. On success, you will be redirected to the voting page.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- helpful copy card dark styles -->
          <div class="rounded-2xl bg-white dark:bg-neutral-800 dark:text-neutral-100 border border-transparent dark:border-neutral-700 shadow-lg p-6 h-fit">
            <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100">How it works</h3>
            <ol class="list-decimal pl-5 mt-3 space-y-2 text-sm leading-relaxed text-neutral-700 dark:text-neutral-300">
              <li>Scan the QRIS code and complete the payment.</li>
              <li>Click “Simulate Payment Success” to mark your payment as received (demo).</li>
              <li>After payment, you will be redirected to the voting page.</li>
              <li>Choose one candidate and submit your vote.</li>
              <li>See live results in real time on the Results page.</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Voting -->
    <section id="vote" class="py-14 sm:py-16">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
          <div>
            <!-- headings adjust for dark -->
            <h2 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">Cast Your Vote</h2>
            <p class="text-sm text-neutral-600 dark:text-neutral-300 mt-1">Select one candidate below and submit.</p>
          </div>
          <div>
            <span id="voteLockBadge" class="hidden rounded-full bg-yellow-50 text-amber-700 border border-yellow-200 text-xs px-3 py-1.5">
              Payment required to vote
            </span>
          </div>
        </div>

        <div id="choicesGrid" class="mt-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-7 gap-4">
          <!-- injected 70 candidate cards -->
        </div>

        <div class="mt-6 flex items-center justify-end">
          <button
            id="submitVoteBtn"
            class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-brandBlue1 to-brandBlue2 text-white font-semibold px-5 py-3 shadow-glow hover:scale-[1.02] transition disabled:opacity-50 disabled:cursor-not-allowed"
            type="button"
          >
            Submit Vote
          </button>
        </div>
      </div>
    </section>

    <!-- Results -->
    <!-- section background for dark -->
    <section id="results" class="py-14 sm:py-16 bg-neutral-50 dark:bg-neutral-900">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
          <div>
            <h2 class="text-xl font-bold text-neutral-800 dark:text-neutral-100">Live Results</h2>
            <p class="text-sm text-neutral-600 dark:text-neutral-300 mt-1">Auto-updating every few seconds.</p>
          </div>
          <div class="flex items-center gap-2">
            <span class="inline-flex items-center rounded-full bg-green-50 text-green-700 border border-green-200 text-xs px-3 py-1.5">
              <span class="h-2 w-2 rounded-full bg-successGreen mr-2"></span> Live
            </span>
            <button
              id="shareBtn"
              class="rounded-lg bg-white text-neutralGray border border-neutral-200 px-3 py-2 text-sm hover:bg-neutral-100 transition"
              type="button"
            >
              Share Results
            </button>
          </div>
        </div>

        <div class="mt-6 grid lg:grid-cols-3 gap-6">
          <!-- results summary card dark styles -->
          <div class="rounded-2xl bg-white dark:bg-neutral-800 dark:text-neutral-100 border border-transparent dark:border-neutral-700 shadow p-5">
            <div class="flex items-center justify-between">
              <span class="text-sm text-neutral-800 dark:text-neutral-100">Total Votes</span>
              <span id="totalVotes" class="text-xl font-bold">0</span>
            </div>
            <div class="mt-4">
              <h4 class="font-semibold text-neutral-800 dark:text-neutral-100">Top 3 Leaders</h4>
              <ol id="leadersList" class="mt-3 space-y-2 text-sm">
                <!-- injected leader items -->
              </ol>
            </div>
          </div>

          <!-- results list card dark styles -->
          <div class="rounded-2xl bg-white dark:bg-neutral-800 dark:text-neutral-100 border border-transparent dark:border-neutral-700 shadow p-5 lg:col-span-2">
            <div class="flex items-center justify-between">
              <h4 class="font-semibold text-neutral-800 dark:text-neutral-100">All Candidates</h4>
              <button id="refreshResultsBtn" class="text-xs rounded-md px-3 py-1.5 bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 transition" type="button">
                Refresh
              </button>
            </div>
            <div id="resultsBars" class="mt-4 space-y-3 max-h-[520px] overflow-auto pr-2">
              <!-- injected full progress bars -->
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Confirm Modal -->
    <div id="confirmModal" class="fixed inset-0 hidden items-center justify-center z-[60]">
      <div class="absolute inset-0 bg-black/50"></div>
      <!-- modal dark styles -->
      <div class="relative bg-white dark:bg-neutral-800 dark:text-neutral-100 rounded-2xl shadow-lg w-[92%] max-w-md p-6">
        <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-100">Confirm Your Vote</h3>
        <p id="confirmText" class="text-sm text-neutral-700 dark:text-neutral-300 mt-2">You selected: —</p>
        <div class="mt-5 flex items-center justify-end gap-2">
          <button id="cancelConfirmBtn" class="rounded-lg border border-neutral-200 dark:border-neutral-700 px-4 py-2 text-sm hover:bg-neutral-50 dark:hover:bg-neutral-700" type="button">
            Cancel
          </button>
          <button id="okConfirmBtn" class="rounded-lg bg-brandBlue1 hover:bg-brandBlue2 text-white px-4 py-2 text-sm font-semibold" type="button">
            Confirm
          </button>
        </div>
      </div>
    </div>

    <!-- Toasts -->
    <div id="toastHost" class="fixed bottom-4 right-4 z-[70] space-y-2"></div>

    <script>
      // -----------------------------
      // Global state
      // -----------------------------
      let hasPaid = localStorage.getItem('hasPaid') === 'true'
      let candidates = []
      let selectedChoice = null
      let pollTimer = null
      let currentOrderId = null

      // Utilities
      function fmtCurrencyIDR(n) {
        try { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n) } catch { return 'Rp ' + (n||0).toLocaleString('id-ID') }
      }
      function computeAmountFromQty(q) {
        const qty = Math.max(1, Math.min(1000, parseInt(q || '1', 10)))
        return qty * 3000
      }
      function showToast(message, type = 'info') {
        const host = document.getElementById('toastHost')
        const wrap = document.createElement('div')
        const dark = isDarkMode()
        const base = dark
          ? 'rounded-xl shadow-lg border p-3 text-sm flex items-start gap-2 bg-neutral-800 border-neutral-700 text-neutral-100'
          : 'rounded-xl shadow-lg border p-3 text-sm flex items-start gap-2 bg-white border-neutral-200 text-neutralGray'
        const tone =
          type === 'error'
            ? (dark ? ' border-red-400 text-red-300' : ' border-red-300 text-red-700')
            : type === 'success'
              ? (dark ? ' border-green-400 text-green-300' : ' border-green-300 text-green-700')
              : ''
        wrap.className = base + tone
        wrap.innerHTML = `
          <div class="mt-0.5">
            ${type === 'error'
              ? '<span class="inline-block h-2.5 w-2.5 rounded-full bg-red-500"></span>'
              : type === 'success'
                ? '<span class="inline-block h-2.5 w-2.5 rounded-full bg-successGreen"></span>'
                : '<span class="inline-block h-2.5 w-2.5 rounded-full bg-brandBlue1"></span>'}
          </div>
          <div class="max-w-xs">${message}</div>
        `
        host.appendChild(wrap)
        setTimeout(() => {
          wrap.classList.add('opacity-0', 'transition', 'duration-300')
          setTimeout(() => wrap.remove(), 300)
        }, 2600)
      }

      // Navbar scroll behavior
      function updateHeaderStyle() {
        const header = document.getElementById('siteHeader')
        const atTop = window.scrollY < 60
        const dark = isDarkMode()
        const base = 'fixed top-0 inset-x-0 z-50 transition-colors '
        if (atTop) {
          header.className = base
        } else {
          header.className = base + (dark ? 'bg-blue-700/90 backdrop-blur shadow' : 'bg-blue-600/95 backdrop-blur shadow')
        }
      }

      // Mobile menu toggling
      function initMobileMenu() {
        const btn = document.getElementById('mobileMenuBtn')
        const menu = document.getElementById('mobileMenu')
        if (!btn || !menu) return
        btn.addEventListener('click', () => {
          const isOpen = !menu.classList.contains('hidden')
          if (isOpen) {
            menu.classList.add('hidden')
            btn.setAttribute('aria-expanded', 'false')
          } else {
            menu.classList.remove('hidden')
            btn.setAttribute('aria-expanded', 'true')
          }
        })
        // Close menu on link click
        menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
          menu.classList.add('hidden')
          btn.setAttribute('aria-expanded', 'false')
        }))
      }

      // Generate voting grid
      function buildChoices() {
        const grid = document.getElementById('choicesGrid')
        grid.innerHTML = ''
        candidates.forEach((c, idx) => {
          const card = document.createElement('label')
          card.className = 'group relative flex flex-col rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-3 cursor-pointer select-none transition transform hover:shadow hover:-translate-y-0.5'
          card.setAttribute('tabindex', '0')
          card.innerHTML = `
            <img src="${c.photo ? c.photo : '/placeholder.svg?height=120&width=160'}" alt="${c.name} photo" class="w-full h-28 object-cover rounded-lg" />
            <div class="mt-2 flex items-center justify-between gap-2">
              <div class="text-sm font-semibold text-neutral-800 dark:text-neutral-100 line-clamp-1">${c.name}</div>
              <input type="radio" name="candidate" value="${c.id}" class="h-4 w-4 text-brandBlue1 accent-brandBlue1" aria-label="Select ${c.name}" />
            </div>
          `
          const input = card.querySelector('input[type="radio"]')
          input.addEventListener('change', () => { selectedChoice = idx })
          // Keyboard selection
          card.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); input.checked = true; input.dispatchEvent(new Event('change')) }
          })
          grid.appendChild(card)
        })
      }

      // Build progress bars (preview and results)
      function renderBars(targetEl, limit = null) {
        const total = candidates.reduce((acc, c) => acc + (c.votes||0), 0) || 1
        const items = candidates.map((c) => ({ ...c, count: c.votes||0, pct: ((c.votes||0) / total) * 100 }))
        items.sort((a, b) => b.count - a.count)
        const list = limit ? items.slice(0, limit) : items
        targetEl.innerHTML = ''
        list.forEach((item) => {
          const row = document.createElement('div')
          row.innerHTML = `
            <div class="flex items-center justify-between text-sm">
              <span class="font-medium text-neutral-800 dark:text-neutral-100">${item.name}</span>
              <span class="tabular-nums text-neutral-600 dark:text-neutral-300">${item.count} (${item.pct.toFixed(1)}%)</span>
            </div>
            <div class="h-2.5 w-full bg-neutral-100 dark:bg-neutral-700 rounded-full overflow-hidden">
              <div class="h-full bg-brandBlue1 rounded-full transition-all duration-700" style="width: ${item.pct}%;"></div>
            </div>
          `
          row.className = 'space-y-1'
          targetEl.appendChild(row)
        })
      }

      function updateLeadersAndTotals() {
        const total = candidates.reduce((acc, c) => acc + (c.votes||0), 0)
        document.getElementById('totalVotes').textContent = total.toLocaleString()

        const items = [...candidates].map((c) => ({ ...c, count: c.votes||0 }))
        items.sort((a, b) => b.count - a.count)
        const leaders = items.slice(0, 3)
        const list = document.getElementById('leadersList')
        list.innerHTML = ''
        leaders.forEach((it, idx) => {
          const li = document.createElement('li')
          li.className = 'flex items-center justify-between rounded-lg border border-neutral-200 dark:border-neutral-700 px-3 py-2'
          li.innerHTML = `
            <span class="text-sm font-medium text-neutral-800 dark:text-neutral-100">${idx + 1}. ${it.name}</span>
            <span class="text-sm tabular-nums text-neutral-800 dark:text-neutral-100">${it.count}</span>
          `
          list.appendChild(li)
        })
      }

      async function fetchResults() {
        try {
          const res = await fetch('results.php', { cache: 'no-store' })
          const json = await res.json()
          if (Array.isArray(json.candidates)) {
            candidates = json.candidates
            buildChoices()
            renderBars(document.getElementById('previewBars'), 5)
            renderBars(document.getElementById('resultsBars'), null)
            updateLeadersAndTotals()
          }
        } catch (e) {
          // ignore
        }
      }

      // Voting flow
      function openConfirmModal() {
        const modal = document.getElementById('confirmModal')
        const text = document.getElementById('confirmText')
        const choiceName = candidates[selectedChoice]?.name || '—'
        text.textContent = `You selected: ${choiceName}`
        modal.classList.remove('hidden')
        modal.classList.add('flex')
      }
      function closeConfirmModal() {
        const modal = document.getElementById('confirmModal')
        modal.classList.add('hidden')
        modal.classList.remove('flex')
      }
      async function recordVoteAndGoResults() {
        if (selectedChoice == null) return
        const candidateId = candidates[selectedChoice]?.id
        if (!candidateId) return
        try {
          const form = new FormData()
          form.append('candidate_id', String(candidateId))
          const res = await fetch('vote.php', { method: 'POST', body: form })
          const json = await res.json()
          if (json && json.message) {
            hasPaid = false
            localStorage.setItem('hasPaid', 'false')
            showToast('Thanks! Your vote has been recorded.', 'success')
            await fetchResults()
            location.hash = '#results'
          } else if (json && json.error) {
            showToast(json.error, 'error')
          }
        } catch (e) {
          showToast('Failed to submit vote.', 'error')
        }
      }

      function enforceVoteGateOnNavClicks() {
        document.querySelectorAll('a[data-requires-paid]').forEach((a) => {
          a.addEventListener('click', (e) => {
            if (!hasPaid) {
              e.preventDefault()
              showToast('❌ Please complete payment first', 'error')
              location.hash = '#payment'
            }
          })
        })
      }

      function reflectVoteLockUI() {
        const badge = document.getElementById('voteLockBadge')
        const submitBtn = document.getElementById('submitVoteBtn')
        if (hasPaid) {
          badge.classList.add('hidden')
          submitBtn.disabled = false
        } else {
          badge.classList.remove('hidden')
          submitBtn.disabled = true
        }
      }

      function initPaymentCard() {
        const statusEl = document.getElementById('paymentStatus')
        const btnSim = document.getElementById('simulatePaymentBtn')
        const btnCreate = document.getElementById('createOrderBtn')
        const qtyInput = document.getElementById('qtyVotes')
        const orderAmountEl = document.getElementById('orderAmount')
        const proofInput = document.getElementById('proofFile')

        function updateAmountUI() {
          const qty = Math.max(1, Math.min(1000, parseInt(qtyInput.value || '1', 10)))
          orderAmountEl.textContent = fmtCurrencyIDR(computeAmountFromQty(qty))
        }

        async function createOrUpdateOrder() {
          const qty = Math.max(1, Math.min(1000, parseInt(qtyInput.value || '1', 10)))
          qtyInput.value = String(qty)
          const form = new FormData()
          form.append('qty', String(qty))
          try {
            const res = await fetch('order.php', { method: 'POST', body: form })
            const json = await res.json()
            if (json && json.order_id) {
              currentOrderId = json.order_id
              document.getElementById('orderId').textContent = json.order_id
              document.getElementById('orderAmount').textContent = fmtCurrencyIDR(json.amount)
              statusEl.textContent = 'Waiting for Payment...'
              statusEl.className = 'text-sm font-medium text-amber-700'
              hasPaid = false
              localStorage.setItem('hasPaid', 'false')
              reflectVoteLockUI()
              showToast('Order created. Please simulate payment.', 'success')
            } else if (json && json.error) {
              showToast(json.error, 'error')
            }
          } catch (e) {
            showToast('Failed to create order', 'error')
          }
        }

        btnCreate.addEventListener('click', createOrUpdateOrder)
        qtyInput.addEventListener('input', updateAmountUI)
        qtyInput.addEventListener('change', (e) => { updateAmountUI(); createOrUpdateOrder() })

        // initialize amount display
        updateAmountUI()

        if (hasPaid) {
          statusEl.textContent = 'Payment received'
          statusEl.className = 'text-sm font-medium text-green-700'
        }

        async function uploadProof() {
          if (!currentOrderId) {
            await createOrUpdateOrder()
          }
          const file = proofInput.files && proofInput.files[0]
          if (!file) { showToast('Please choose a proof file first.', 'error'); return }
          try {
            const form = new FormData()
            form.append('order_id', currentOrderId)
            form.append('proof', file)
            const res = await fetch('upload_proof.php', { method: 'POST', body: form })
            const json = await res.json()
            if (json && (json.status === 'awaiting_review')) {
              statusEl.textContent = 'Awaiting admin review…'
              statusEl.className = 'text-sm font-medium text-amber-700'
              showToast('Proof submitted. Waiting for approval.', 'success')
              // Start polling for approval
              startApprovalPolling()
            } else if (json && json.error) {
              showToast(json.error, 'error')
            }
          } catch (e) {
            showToast('Failed to upload proof', 'error')
          }
        }

        btnSim.addEventListener('click', uploadProof)

        let approvalTimer = null
        async function pollOrderStatus() {
          if (!currentOrderId) return
          try {
            const res = await fetch('order_status.php?order_id=' + encodeURIComponent(currentOrderId), { cache: 'no-store' })
            const json = await res.json()
            if (json && json.status) {
              if (json.status === 'approved') {
                clearInterval(approvalTimer)
                approvalTimer = null
                // claim votes
                const form = new FormData()
                form.append('order_id', currentOrderId)
                const res2 = await fetch('claim_order.php', { method: 'POST', body: form })
                const claim = await res2.json()
                if (claim && claim.message) {
                  hasPaid = true
                  localStorage.setItem('hasPaid', 'true')
                  statusEl.textContent = 'Payment approved'
                  statusEl.className = 'text-sm font-medium text-green-700'
                  showToast('Payment approved! Votes credited. You can vote now.', 'success')
                  reflectVoteLockUI()
                  setTimeout(() => { location.hash = '#vote' }, 800)
                }
              } else if (json.status === 'rejected') {
                clearInterval(approvalTimer)
                approvalTimer = null
                statusEl.textContent = 'Payment rejected'
                statusEl.className = 'text-sm font-medium text-red-700'
                showToast('Payment was rejected. Please contact admin.', 'error')
              } else if (json.status === 'awaiting_review') {
                // keep waiting
              }
            }
          } catch (e) {
            // ignore transient errors
          }
        }

        function startApprovalPolling() {
          if (approvalTimer) clearInterval(approvalTimer)
          approvalTimer = setInterval(pollOrderStatus, 3000)
        }
      }

      function initSubmitFlow() {
        const submitBtn = document.getElementById('submitVoteBtn')
        submitBtn.addEventListener('click', () => {
          if (!hasPaid) {
            showToast('❌ Please complete payment first', 'error')
            location.hash = '#payment'
            return
          }
          if (selectedChoice == null) {
            showToast('Please select a candidate before submitting.', 'error')
            return
          }
          openConfirmModal()
        })

        document.getElementById('cancelConfirmBtn').addEventListener('click', closeConfirmModal)
        document.getElementById('okConfirmBtn').addEventListener('click', () => {
          closeConfirmModal()
          recordVoteAndGoResults()
        })
      }

      function initResultsControls() {
        document.getElementById('shareBtn').addEventListener('click', async () => {
          const url = location.href
          try {
            await navigator.clipboard.writeText(url)
            showToast('Link copied to clipboard!', 'success')
          } catch {
            showToast('Unable to copy. Select the URL from the address bar.', 'info')
          }
        })
        document.getElementById('refreshPreviewBtn').addEventListener('click', () => fetchResults())
        document.getElementById('refreshResultsBtn').addEventListener('click', () => fetchResults())
      }

      function initPolling() {
        if (pollTimer) clearInterval(pollTimer)
        pollTimer = setInterval(fetchResults, 4000)
      }

      function initHeaderLinks() {
        // Ensure gating when hash changes to #vote
        window.addEventListener('hashchange', () => {
          if (location.hash === '#vote' && !hasPaid) {
            showToast('❌ Please complete payment first', 'error')
            location.hash = '#payment'
          }
        })
      }

      // Theme helpers and toggle wiring
      function isDarkMode() {
        return document.documentElement.classList.contains('dark')
      }
      function setTheme(mode) {
        const root = document.documentElement
        const toDark = mode === 'dark'
        root.classList.toggle('dark', toDark)
        try { localStorage.setItem('theme', toDark ? 'dark' : 'light') } catch(_) {}
        // reflect label state
        const label = toDark ? 'Dark: On' : 'Dark: Off'
        const pressed = String(toDark)
        const desk = document.getElementById('themeToggleDesktop')
        const mob = document.getElementById('themeToggleMobile')
        if (desk) { desk.textContent = label; desk.setAttribute('aria-pressed', pressed) }
        if (mob) { mob.textContent = label; mob.setAttribute('aria-pressed', pressed) }
        // refresh header style to adjust bg tone
        updateHeaderStyle()
      }
      function initThemeToggle() {
        const pref = localStorage.getItem('theme')
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
        const startDark = pref ? pref === 'dark' : prefersDark
        setTheme(startDark ? 'dark' : 'light')
        document.getElementById('themeToggleDesktop')?.addEventListener('click', () => {
          setTheme(isDarkMode() ? 'light' : 'dark')
        })
        document.getElementById('themeToggleMobile')?.addEventListener('click', () => {
          setTheme(isDarkMode() ? 'light' : 'dark')
        })
      }

      // Initial gating if landing directly at #vote
      function enforceInitialHashGate() {
        if (location.hash === '#vote' && !hasPaid) {
          showToast('❌ Please complete payment first', 'error')
          location.hash = '#payment'
        }
      }

      // -----------------------------
      // Boot
      // -----------------------------
      document.addEventListener('DOMContentLoaded', () => {
        initThemeToggle()
        // Initial header style, and on scroll
        updateHeaderStyle()
        window.addEventListener('scroll', updateHeaderStyle, { passive: true })

        initMobileMenu()
        enforceVoteGateOnNavClicks()
        initHeaderLinks()

        reflectVoteLockUI()
        initPaymentCard()
        initSubmitFlow()
        initResultsControls()

        // Initial fetch
        fetchResults()
        initPolling()

        // Initial gating if landing directly at #vote
        enforceInitialHashGate()
      })
    </script>
  </body>
</html>
