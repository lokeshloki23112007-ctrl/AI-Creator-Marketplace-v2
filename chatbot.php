<?php
// chatbot.php - Dedicated Full-Page AI Assistant & Creator Recommender
require_once __DIR__ . '/config/db.php';
$logged_user = get_logged_in_user();

$page_title = "AI Chatbot Assistant";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AI Chatbot Assistant - AICreators</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body, * { font-family: 'Times New Roman', Times, serif !important; }
    body { background-color: #0A0B12; color: #F1EEFA; }
    
    .chat-scroll::-webkit-scrollbar {
      width: 6px;
    }
    .chat-scroll::-webkit-scrollbar-track {
      background: #0E0F1B;
    }
    .chat-scroll::-webkit-scrollbar-thumb {
      background: #26243E;
      border-radius: 4px;
    }
    .chat-scroll::-webkit-scrollbar-thumb:hover {
      background: #00F5FF;
    }
  </style>
</head>
<body class="min-h-screen bg-[#0A0B12] px-4 py-6 text-[#F1EEFA] sm:px-6 lg:px-8">

  <div class="mx-auto max-w-7xl overflow-hidden rounded-[30px] border border-[#26243E] bg-[#121324] shadow-[0_20px_60px_rgba(0,0,0,0.6)] flex flex-col min-h-[840px]">
    <!-- Top Header -->
    <header class="flex items-center justify-between border-b border-[#26243E] bg-[#0E0F1B] px-6 py-4">
      <a href="index.php" class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] shadow-[0_0_20px_rgba(0,245,255,0.4)]">
          <span class="text-lg font-black text-white">▶</span>
        </div>
        <div>
          <div class="text-2xl font-black tracking-[-0.06em] text-[#F1EEFA]"><?= ($logged_user && $logged_user['role'] === 'brand') ? 'Brand/Agency' : 'AICreators' ?></div>
          <div class="text-[0.58rem] uppercase tracking-[0.2em] text-[#A79DCB] font-semibold"><?= ($logged_user && $logged_user['role'] === 'brand') ? 'Hire • Campaigns • Scale' : 'Create • Connect • Grow' ?></div>
        </div>
      </a>

      <!-- Header Center Badge -->
      <div class="hidden md:flex items-center gap-2 rounded-full border border-[#26243E] bg-[#16172B] px-4 py-1.5 text-xs font-semibold text-[#C4BCE3]">
        <span class="h-2 w-2 rounded-full bg-[#00F5FF] shadow-[0_0_8px_#00F5FF] animate-pulse"></span>
        <span>AI Assistant Live • Ready to match creators &amp; answer questions</span>
      </div>

      <?php if ($logged_user): ?>
        <a href="<?= $logged_user['role'] === 'brand' ? 'brand_profile.php' : 'creator_profile.php' ?>" class="flex items-center gap-3">
          <?php if (!empty($logged_user['avatar_url'])): ?>
            <img src="<?= htmlspecialchars($logged_user['avatar_url']) ?>" alt="Avatar" class="h-10 w-10 rounded-full object-cover ring-2 ring-[#00F5FF]/60">
          <?php else: ?>
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-sm font-bold text-white shadow-md">
              <?= strtoupper(substr($logged_user['full_name'], 0, 2)) ?>
            </div>
          <?php endif; ?>
          <div>
            <div class="text-sm font-semibold text-[#F1EEFA]"><?= htmlspecialchars($logged_user['full_name']) ?></div>
            <div class="text-xs text-[#A79DCB] capitalize"><?= htmlspecialchars($logged_user['role']) ?></div>
          </div>
        </a>
      <?php else: ?>
        <div class="flex gap-2">
          <a href="login.php" class="rounded-full border border-[#26243E] bg-[#16172B] px-4 py-2 text-xs font-semibold text-[#F1EEFA] hover:bg-[#1E1F38] hover:border-[#00F5FF]/50 transition">Login</a>
          <a href="signup.php" class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-4 py-2 text-xs font-semibold text-white shadow-md hover:brightness-110 transition">Sign Up</a>
        </div>
      <?php endif; ?>
    </header>

    <div class="grid flex-1 lg:grid-cols-[280px_1fr]">
      <!-- Left Quick Topics Sidebar -->
      <aside class="border-r border-[#26243E] bg-[#0E0F1D] p-6 space-y-6 flex flex-col justify-between">
        <div class="space-y-6">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-2xl shadow-[0_0_15px_rgba(0,245,255,0.3)]">
              🤖
            </div>
            <div>
              <h2 class="text-base font-bold text-[#F1EEFA]">AI Assistant</h2>
              <p class="text-xs text-[#00F5FF] font-semibold">Marketplace Copilot</p>
            </div>
          </div>

          <!-- Suggested Topic Buttons -->
          <div>
            <p class="text-[0.68rem] font-bold uppercase tracking-wider text-[#A79DCB] mb-3">Popular Prompts</p>
            <div class="space-y-2">
              <button
                type="button"
                onclick="sendPagePrompt('Recommend top creators for 30s video ads')"
                class="w-full text-left rounded-xl border border-[#26243E] bg-[#16172B] p-3 text-xs text-[#E8E3F8] transition hover:border-[#00F5FF] hover:bg-[#1E1F38] shadow-sm cursor-pointer"
              >
                🎬 <b class="text-[#F1EEFA]">30s Ad Creators</b>
                <p class="mt-0.5 text-[0.68rem] text-[#A79DCB]">Find top verified video directors</p>
              </button>

              <button
                type="button"
                onclick="sendPagePrompt('Find Midjourney and Flux visual artists')"
                class="w-full text-left rounded-xl border border-[#26243E] bg-[#16172B] p-3 text-xs text-[#E8E3F8] transition hover:border-[#00F5FF] hover:bg-[#1E1F38] shadow-sm cursor-pointer"
              >
                🤖 <b class="text-[#F1EEFA]">Midjourney &amp; Flux</b>
                <p class="mt-0.5 text-[0.68rem] text-[#A79DCB]">Generative brand &amp; concept artists</p>
              </button>

              <button
                type="button"
                onclick="sendPagePrompt('Explain budget tiers and pricing in INR')"
                class="w-full text-left rounded-xl border border-[#26243E] bg-[#16172B] p-3 text-xs text-[#E8E3F8] transition hover:border-[#00F5FF] hover:bg-[#1E1F38] shadow-sm cursor-pointer"
              >
                💰 <b class="text-[#F1EEFA]">Budget Tiers (₹)</b>
                <p class="mt-0.5 text-[0.68rem] text-[#A79DCB]">From &lt;₹10,000 to ₹1,00,000+</p>
              </button>

              <button
                type="button"
                onclick="sendPagePrompt('How do commercial rights and licensing work?')"
                class="w-full text-left rounded-xl border border-[#26243E] bg-[#16172B] p-3 text-xs text-[#E8E3F8] transition hover:border-[#00F5FF] hover:bg-[#1E1F38] shadow-sm cursor-pointer"
              >
                ⚖️ <b class="text-[#F1EEFA]">Commercial Rights</b>
                <p class="mt-0.5 text-[0.68rem] text-[#A79DCB]">Paid ads &amp; exclusive ownership</p>
              </button>

              <button
                type="button"
                onclick="sendPagePrompt('How do brands create and post a campaign brief?')"
                class="w-full text-left rounded-xl border border-[#26243E] bg-[#16172B] p-3 text-xs text-[#E8E3F8] transition hover:border-[#00F5FF] hover:bg-[#1E1F38] shadow-sm cursor-pointer"
              >
                📝 <b class="text-[#F1EEFA]">Post a Brief</b>
                <p class="mt-0.5 text-[0.68rem] text-[#A79DCB]">Step-by-step campaign guide</p>
              </button>

              <button
                type="button"
                onclick="sendPagePrompt('Who is available now for urgent projects?')"
                class="w-full text-left rounded-xl border border-[#26243E] bg-[#16172B] p-3 text-xs text-[#E8E3F8] transition hover:border-[#00F5FF] hover:bg-[#1E1F38] shadow-sm cursor-pointer"
              >
                ⏱️ <b class="text-[#F1EEFA]">Available Now</b>
                <p class="mt-0.5 text-[0.68rem] text-[#A79DCB]">Immediate turnaround creators</p>
              </button>
            </div>
          </div>
        </div>

        <div class="pt-4 border-t border-[#26243E] space-y-2">
          <a
            href="brand_creators.php"
            class="block w-full rounded-full border border-[#00F5FF]/30 bg-[#00F5FF]/10 py-2.5 text-center text-xs font-bold text-[#00F5FF] hover:bg-[#00F5FF]/20 transition"
          >
            ← Open 12 Brand Filters
          </a>
          <button
            type="button"
            onclick="clearPageChatHistory()"
            class="block w-full rounded-full border border-[#26243E] bg-[#16172B] py-2 text-center text-xs font-semibold text-[#C4BCE3] hover:bg-[#1E1F38] cursor-pointer shadow-sm transition"
          >
            🗑️ Clear Conversation
          </button>
        </div>
      </aside>

      <!-- Main Chat Area -->
      <main class="flex flex-col bg-[#0A0B12] p-6 lg:p-8">
        <div class="flex-1 flex flex-col rounded-[26px] border border-[#26243E] bg-[#121324] overflow-hidden shadow-sm">
          
          <!-- Chat Feed Header -->
          <div class="flex items-center justify-between border-b border-[#26243E] bg-[#0E0F1B] px-6 py-4">
            <div class="flex items-center gap-3">
              <span class="flex h-3 w-3 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00F5FF] opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-[#00F5FF]"></span>
              </span>
              <div>
                <h3 class="text-sm font-bold text-[#F1EEFA]">AICreators Intelligent Chat</h3>
                <p class="text-[0.65rem] text-[#A79DCB]">Ask any question or prompt for matching creators</p>
              </div>
            </div>

            <a
              href="brand_creators.php"
              class="rounded-full bg-[#16172B] border border-[#26243E] px-4 py-1.5 text-xs font-semibold text-[#F1EEFA] hover:border-[#00F5FF] hover:text-[#00F5FF] shadow-sm transition"
            >
              Search Directory →
            </a>
          </div>

          <!-- Message History Container -->
          <div
            id="page-chat-messages"
            class="flex-1 overflow-y-auto p-6 space-y-4 chat-scroll text-sm leading-relaxed bg-[#0E0F1B]"
            style="scroll-behavior: smooth;"
          >
            <!-- Default Welcome Bot Bubble -->
            <div class="flex items-start gap-3">
              <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-base shadow-[0_0_12px_rgba(0,245,255,0.4)]">
                🤖
              </div>
              <div class="max-w-[80%] rounded-3xl rounded-tl-sm border border-[#26243E] bg-[#16172B] p-5 text-[#E8E3F8] shadow-sm space-y-3">
                <h4 class="text-base font-bold text-[#F1EEFA]">Hello! 👋 Welcome to AICreators Assistant.</h4>
                <p class="text-[#C4BCE3]">
                  I can recommend top AI creators from our verified network for <b class="text-[#F1EEFA]">30-second ad campaigns</b>, explain <b class="text-[#00F5FF]">INR pricing tiers</b>, detail <b class="text-[#C084FC]">commercial licensing</b>, or guide your brief submission.
                </p>
                <div class="flex flex-wrap gap-2 pt-2">
                  <span class="rounded-full bg-[#1E1F38] text-[#C084FC] px-3 py-1 text-xs border border-[#26243E] font-semibold">🎬 30s Ad Validation</span>
                  <span class="rounded-full bg-[#1E1F38] text-[#00F5FF] px-3 py-1 text-xs border border-[#26243E] font-semibold">💰 INR Pricing</span>
                  <span class="rounded-full bg-[#1E1F38] text-[#38BDF8] px-3 py-1 text-xs border border-[#26243E] font-semibold">⚡ Runway &amp; Midjourney</span>
                  <span class="rounded-full bg-[#1E1F38] text-[#F472B6] px-3 py-1 text-xs border border-[#26243E] font-semibold">📐 16:9 &amp; 9:16 Formats</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Chat Input Area -->
          <form id="page-chat-form" onsubmit="handlePageChatSubmit(event)" class="border-t border-[#26243E] bg-[#0E0F1B] p-4">
            <div class="flex items-center gap-3">
              <input
                id="page-chat-input"
                type="text"
                placeholder="Ask about 30s ads, creators, Runway, Kling, pricing, or commercial rights..."
                autocomplete="off"
                class="flex-1 rounded-full border border-[#26243E] bg-[#16172B] px-5 py-3.5 text-sm text-[#F1EEFA] placeholder:text-[#766E99] focus:border-[#00F5FF] focus:bg-[#1A1B30] focus:outline-none shadow-inner transition"
              />
              <button
                type="submit"
                id="page-send-btn"
                class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-7 py-3.5 text-sm font-bold text-white shadow-[0_0_18px_rgba(0,245,255,0.4)] transition hover:brightness-110 cursor-pointer disabled:opacity-50"
              >
                Send ➤
              </button>
            </div>
          </form>

        </div>
      </main>
    </div>
  </div>

  <script>
    function sendPagePrompt(text) {
      const input = document.getElementById('page-chat-input');
      input.value = text;
      handlePageChatSubmit(new Event('submit'));
    }

    function clearPageChatHistory() {
      const messagesEl = document.getElementById('page-chat-messages');
      messagesEl.innerHTML = `
        <div class="flex items-start gap-3">
          <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-base shadow-[0_0_12px_rgba(0,245,255,0.4)]">🤖</div>
          <div class="max-w-[80%] rounded-3xl rounded-tl-sm border border-[#26243E] bg-[#16172B] p-5 text-[#E8E3F8] shadow-sm">
            <h4 class="text-base font-bold text-[#F1EEFA]">Conversation reset! 👋</h4>
            <p class="mt-2 text-[#C4BCE3]">How can I assist your brand or creator journey today?</p>
          </div>
        </div>
      `;
    }

    async function handlePageChatSubmit(e) {
      if (e && e.preventDefault) e.preventDefault();
      const input = document.getElementById('page-chat-input');
      const sendBtn = document.getElementById('page-send-btn');
      const text = input.value.trim();
      if (!text) return;

      appendPageMsg('user', text);
      input.value = '';
      sendBtn.disabled = true;

      const typingId = showPageTyping();

      try {
        const res = await fetch('chatbot_api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ message: text })
        });
        const data = await res.json();
        removePageTyping(typingId);
        appendPageMsg('bot', data.reply, data.creators || []);
      } catch (err) {
        removePageTyping(typingId);
        appendPageMsg('bot', "Could not reach assistant service. Please check back shortly.");
      } finally {
        sendBtn.disabled = false;
        input.focus();
      }
    }

    function appendPageMsg(sender, text, creators = []) {
      const container = document.getElementById('page-chat-messages');
      const row = document.createElement('div');
      row.className = sender === 'user' ? 'flex justify-end' : 'flex items-start gap-3';

      let formattedText = escapeHtml(text)
        .replace(/\*\*(.*?)\*\*/g, '<b class="text-[#F1EEFA]">$1</b>')
        .replace(/\*(.*?)\*/g, '<i>$1</i>')
        .replace(/\n/g, '<br/>')
        .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" class="text-[#00F5FF] underline font-semibold">$1</a>');

      if (sender === 'user') {
        row.innerHTML = `
          <div class="max-w-[75%] rounded-3xl rounded-tr-sm bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] p-4 text-white shadow-[0_0_15px_rgba(0,245,255,0.25)]">
            <p class="leading-relaxed font-semibold">${formattedText}</p>
          </div>
        `;
      } else {
        let cardsHtml = '';
        if (creators && creators.length > 0) {
          cardsHtml = '<div class="mt-4 grid gap-3 sm:grid-cols-2">';
          creators.forEach(c => {
            const dotColor = (c.availability && c.availability.includes('This Week')) ? 'bg-amber-400' : 'bg-[#00F5FF]';
            cardsHtml += `
              <div class="rounded-2xl border border-[#26243E] bg-[#1A1B30] p-4 transition hover:border-[#00F5FF] shadow-sm flex flex-col justify-between">
                <div>
                  <div class="flex items-center gap-3">
                    <img src="${escapeHtml(c.avatar)}" alt="${escapeHtml(c.name)}" class="h-12 w-12 rounded-full object-cover ring-2 ring-[#00F5FF]/60">
                    <div class="min-w-0 flex-1">
                      <h4 class="text-sm font-bold text-[#F1EEFA] truncate">${escapeHtml(c.name)}</h4>
                      <p class="text-xs text-[#00F5FF] font-semibold truncate">${escapeHtml(c.role)}</p>
                      <div class="mt-1 flex items-center gap-2 text-[0.68rem] text-[#A79DCB]">
                        <span class="flex items-center gap-1">
                          <span class="h-2 w-2 rounded-full ${dotColor}"></span>
                          ${escapeHtml(c.availability)}
                        </span>
                        <span>•</span>
                        <span class="text-amber-400 font-bold">★ ${escapeHtml(c.rating)}</span>
                      </div>
                    </div>
                  </div>
                  <div class="mt-3 flex items-center justify-between text-xs pt-2 border-t border-[#26243E]">
                    <span class="text-[#A79DCB]">Rate / Budget:</span>
                    <span class="font-bold text-[#00F5FF]">${escapeHtml(c.rate)}</span>
                  </div>
                </div>
                <a
                  href="${escapeHtml(c.link)}"
                  class="mt-3 block w-full rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] py-2 text-center text-xs font-bold text-white hover:brightness-110 shadow-sm"
                >
                  View Profile &amp; 30s Ad Demos →
                </a>
              </div>
            `;
          });
          cardsHtml += '</div>';
        }

        row.innerHTML = `
          <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-base shadow-[0_0_12px_rgba(0,245,255,0.4)]">🤖</div>
          <div class="max-w-[80%] rounded-3xl rounded-tl-sm border border-[#26243E] bg-[#16172B] p-5 text-[#E8E3F8] shadow-sm leading-relaxed">
            <div>${formattedText}</div>
            ${cardsHtml}
          </div>
        `;
      }

      container.appendChild(row);
      container.scrollTop = container.scrollHeight;
    }

    function showPageTyping() {
      const container = document.getElementById('page-chat-messages');
      const id = 'page-typing-' + Date.now();
      const div = document.createElement('div');
      div.id = id;
      div.className = 'flex items-start gap-3';
      div.innerHTML = `
        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-base shadow-[0_0_12px_rgba(0,245,255,0.4)]">🤖</div>
        <div class="rounded-3xl rounded-tl-sm border border-[#26243E] bg-[#16172B] px-5 py-3 text-[#A79DCB] shadow-sm">
          <span class="inline-flex gap-1.5 items-center">
            <span class="h-2 w-2 rounded-full bg-[#00F5FF] animate-bounce"></span>
            <span class="h-2 w-2 rounded-full bg-[#8B5CF6] animate-bounce" style="animation-delay: 0.2s"></span>
            <span class="h-2 w-2 rounded-full bg-[#EC4899] animate-bounce" style="animation-delay: 0.4s"></span>
          </span>
        </div>
      `;
      container.appendChild(div);
      container.scrollTop = container.scrollHeight;
      return id;
    }

    function removePageTyping(id) {
      const el = document.getElementById(id);
      if (el) el.remove();
    }

    function escapeHtml(str) {
      if (!str) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }
  </script>

</body>
</html>
