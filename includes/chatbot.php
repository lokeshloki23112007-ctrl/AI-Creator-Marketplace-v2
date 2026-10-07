<?php
// includes/chatbot.php - Interactive AI Chatbot Widget for AICreators Marketplace (Misty Moon White Theme)
?>
<!-- AI Marketplace Floating Chatbot Component -->
<div id="ai-chatbot-root" class="fixed bottom-6 right-6 z-50 select-none">
  
  <!-- Floating Trigger Button -->
  <button
    id="chatbot-toggle-btn"
    type="button"
    onclick="toggleChatbot()"
    class="group relative flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-tr from-[#7c3aed] via-[#8b5cf6] to-[#ec4899] text-white shadow-[0_0_25px_rgba(139,92,246,0.5)] transition-all duration-300 hover:scale-110 hover:shadow-[0_0_35px_rgba(236,72,153,0.7)] focus:outline-none cursor-pointer"
    aria-label="Open AI Assistant"
  >
    <!-- Pulsing halo animation -->
    <span class="absolute -inset-1 rounded-full bg-gradient-to-r from-[#7c3aed] to-[#ec4899] opacity-75 blur-sm transition group-hover:opacity-100 group-hover:blur animate-pulse"></span>
    
    <!-- Bot Icon -->
    <span id="chatbot-icon-open" class="relative text-2xl transition duration-300">🤖</span>
    <span id="chatbot-icon-close" class="relative hidden text-xl font-bold transition duration-300">✕</span>

    <!-- Online green dot badge -->
    <span class="absolute top-0 right-0 flex h-3.5 w-3.5">
      <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
      <span class="relative inline-flex h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-500"></span>
    </span>

    <!-- Tooltip label -->
    <span class="pointer-events-none absolute right-16 top-1/2 -translate-y-1/2 whitespace-nowrap rounded-full border border-[#26243E] bg-[#16172B] px-3 py-1.5 text-xs font-semibold text-[#F1EEFA] shadow-xl opacity-0 transition-opacity duration-300 group-hover:opacity-100">
      AI Assistant • Chat with Bot
    </span>
  </button>

  <!-- Chatbot Window Panel -->
  <div
    id="chatbot-window"
    class="hidden absolute bottom-16 right-0 w-[360px] sm:w-[410px] max-w-[94vw] h-[580px] max-h-[82vh] flex-col rounded-[28px] border border-[#2E284C] bg-[#0E0F1B]/98 shadow-[0_25px_65px_rgba(0,0,0,0.85)] backdrop-blur-2xl transition-all duration-300 overflow-hidden"
    style="font-family: 'Times New Roman', Times, serif !important;"
  >
    <!-- Chat Header -->
    <header class="flex items-center justify-between border-b border-[#26243E] bg-[#121324] px-4 py-3.5">
      <div class="flex items-center gap-3">
        <div class="relative flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-lg text-white shadow-md">
          <span>🤖</span>
          <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-[#121324] bg-emerald-400"></span>
        </div>
        <div>
          <div class="flex items-center gap-2">
            <h3 class="text-sm font-bold text-[#F1EEFA] tracking-wide">AICreators Assistant</h3>
            <span class="rounded bg-[#06B6D4]/15 px-1.5 py-0.2 text-[0.6rem] font-bold text-[#00F5FF] border border-[#06B6D4]/30">AI BOT</span>
          </div>
          <p class="text-[0.68rem] text-[#C4BCE3]">Online • 30s Ads &amp; Creator Recommender</p>
        </div>
      </div>

      <div class="flex items-center gap-1.5">
        <!-- Clear Chat button -->
        <button
          type="button"
          onclick="clearChatbotHistory()"
          title="Clear Conversation"
          class="rounded-lg p-1.5 text-[#C4BCE3] transition hover:bg-[#16172B] hover:text-[#00F5FF]"
        >
          🗑️
        </button>

        <!-- Close button -->
        <button
          type="button"
          onclick="toggleChatbot(false)"
          title="Minimize Chatbot"
          class="rounded-lg p-1.5 text-[#C4BCE3] transition hover:bg-[#16172B] hover:text-[#00F5FF]"
        >
          ✕
        </button>
      </div>
    </header>

    <!-- Message Feed -->
    <div
      id="chatbot-messages"
      class="flex-1 space-y-3.5 overflow-y-auto bg-[#0A0B12] p-4 text-xs leading-relaxed filter-scroll"
      style="scroll-behavior: smooth;"
    >
      <!-- Initial Welcome Message from Bot -->
      <div class="flex items-start gap-2.5">
        <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-xl bg-[#8B5CF6]/20 text-[#00F5FF] text-sm shadow-sm border border-[#8B5CF6]/30">
          🤖
        </div>
        <div class="max-w-[85%] rounded-2xl rounded-tl-sm border border-[#2E284C] bg-[#16172B] p-3 text-[#E8E3F8] shadow-sm space-y-2">
          <p class="font-bold text-[#F1EEFA]">Hello! 👋 Welcome to AICreators Assistant.</p>
          <p class="text-[#C4BCE3]">
            I can recommend top AI creators for your <b>30-second ad campaigns</b>, explain pricing tiers in INR (₹), or guide you through commercial licensing &amp; tools (Runway, Kling, Midjourney, Flux, etc.).
          </p>
          <p class="text-[0.7rem] text-[#00F5FF] font-semibold">What would you like to explore today?</p>
        </div>
      </div>
    </div>

    <!-- Quick Suggestion Chips -->
    <div id="chatbot-chips" class="border-t border-[#26243E] bg-[#121324] p-2.5">
      <div class="flex gap-1.5 overflow-x-auto pb-1 filter-scroll text-[0.68rem]">
        <button
          type="button"
          onclick="sendQuickPrompt('Recommend creators for 30s video ads')"
          class="whitespace-nowrap rounded-full border border-[#8B5CF6]/40 bg-[#16172B] px-2.5 py-1 text-[#E8E3F8] transition hover:border-[#00F5FF] hover:text-[#00F5FF]"
        >
          🎬 30s Video Creators
        </button>
        <button
          type="button"
          onclick="sendQuickPrompt('Explain budget tiers and pricing in INR')"
          class="whitespace-nowrap rounded-full border border-emerald-500/40 bg-[#16172B] px-2.5 py-1 text-emerald-300 transition hover:border-emerald-300"
        >
          💰 Budget Tiers (₹)
        </button>
        <button
          type="button"
          onclick="sendQuickPrompt('Find Midjourney and Flux artists')"
          class="whitespace-nowrap rounded-full border border-sky-500/40 bg-[#16172B] px-2.5 py-1 text-sky-300 transition hover:border-sky-300"
        >
          🤖 Midjourney &amp; Flux
        </button>
        <button
          type="button"
          onclick="sendQuickPrompt('How do commercial rights and licensing work?')"
          class="whitespace-nowrap rounded-full border border-purple-500/40 bg-[#16172B] px-2.5 py-1 text-purple-300 transition hover:border-purple-300"
        >
          ⚖️ Commercial Rights
        </button>
        <button
          type="button"
          onclick="sendQuickPrompt('How to post a campaign brief?')"
          class="whitespace-nowrap rounded-full border border-pink-500/40 bg-[#16172B] px-2.5 py-1 text-pink-300 transition hover:border-pink-300"
        >
          📝 Post a Brief
        </button>
      </div>
    </div>

    <!-- Input Form -->
    <form id="chatbot-form" onsubmit="handleChatSubmit(event)" class="border-t border-[#26243E] bg-[#121324] p-3">
      <div class="flex items-center gap-2">
        <input
          id="chatbot-input"
          type="text"
          placeholder="Ask about 30s ads, creators, tools, pricing..."
          autocomplete="off"
          class="flex-1 rounded-full border border-[#2E284C] bg-[#16172B] px-3.5 py-2.5 text-xs text-[#F1EEFA] placeholder:text-[#A79DCB] focus:border-[#00F5FF] focus:outline-none"
        />
        <button
          type="submit"
          id="chatbot-send-btn"
          class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-white shadow-md transition hover:scale-105 hover:brightness-110 cursor-pointer disabled:opacity-50"
          title="Send Message"
        >
          ➤
        </button>
      </div>
      <div class="mt-1 flex items-center justify-between px-2 text-[0.6rem] text-[#A79DCB]">
        <span>Powered by AICreators Engine</span>
        <a href="brand_creators.php" class="text-[#00F5FF] hover:underline">Explore 12 Filters →</a>
      </div>
    </form>
  </div>

</div>

<script>
// Chatbot Client Logic
(function() {
  const CHAT_STORAGE_KEY = 'aicreators_chatbot_history_v1';
  let isChatOpen = false;

  window.toggleChatbot = function(forceState) {
    const windowEl = document.getElementById('chatbot-window');
    const iconOpen = document.getElementById('chatbot-icon-open');
    const iconClose = document.getElementById('chatbot-icon-close');

    if (forceState !== undefined) {
      isChatOpen = forceState;
    } else {
      isChatOpen = !isChatOpen;
    }

    if (isChatOpen) {
      windowEl.classList.remove('hidden');
      windowEl.classList.add('flex');
      iconOpen.classList.add('hidden');
      iconClose.classList.remove('hidden');
      setTimeout(() => {
        document.getElementById('chatbot-input')?.focus();
        scrollChatToBottom();
      }, 100);
    } else {
      windowEl.classList.add('hidden');
      windowEl.classList.remove('flex');
      iconOpen.classList.remove('hidden');
      iconClose.classList.add('hidden');
    }
  };

  window.sendQuickPrompt = function(promptText) {
    const inputEl = document.getElementById('chatbot-input');
    if (inputEl) {
      inputEl.value = promptText;
      handleChatSubmit(new Event('submit'));
    }
  };

  window.clearChatbotHistory = function() {
    sessionStorage.removeItem(CHAT_STORAGE_KEY);
    const messagesEl = document.getElementById('chatbot-messages');
    messagesEl.innerHTML = `
      <div class="flex items-start gap-2.5">
        <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-xl bg-[#8B5CF6]/20 text-[#00F5FF] text-sm shadow-sm border border-[#8B5CF6]/30">🤖</div>
        <div class="max-w-[85%] rounded-2xl rounded-tl-sm border border-[#2E284C] bg-[#16172B] p-3 text-[#E8E3F8] shadow-sm space-y-2">
          <p class="font-bold text-[#F1EEFA]">Conversation reset! 👋</p>
          <p class="text-[#C4BCE3]">How can I assist you with your AI content creation needs?</p>
        </div>
      </div>
    `;
  };

  window.handleChatSubmit = async function(e) {
    if (e && e.preventDefault) e.preventDefault();
    const inputEl = document.getElementById('chatbot-input');
    const sendBtn = document.getElementById('chatbot-send-btn');
    const message = inputEl.value.trim();
    if (!message) return;

    // 1. Append User Message
    appendMessage('user', message);
    inputEl.value = '';
    sendBtn.disabled = true;

    // 2. Show Typing Indicator
    const typingId = showTypingIndicator();

    try {
      // 3. Fetch from chatbot_api.php
      const res = await fetch('chatbot_api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: message })
      });
      const data = await res.json();
      removeTypingIndicator(typingId);

      // 4. Append Bot Response
      appendMessage('bot', data.reply, data.creators || []);

      // 5. Update chips if provided
      if (data.suggestions && data.suggestions.length > 0) {
        updateChips(data.suggestions);
      }
    } catch (err) {
      removeTypingIndicator(typingId);
      appendMessage('bot', "I'm having trouble connecting right now. Please explore our creators directly at [Search Creators](brand_creators.php).");
    } finally {
      sendBtn.disabled = false;
      inputEl.focus();
    }
  };

  function appendMessage(sender, text, creators = []) {
    const messagesEl = document.getElementById('chatbot-messages');
    const wrapper = document.createElement('div');
    wrapper.className = sender === 'user' ? 'flex justify-end' : 'flex items-start gap-2.5';

    // Format markdown bold and links
    let formattedText = escapeHtml(text)
      .replace(/\*\*(.*?)\*\*/g, '<b>$1</b>')
      .replace(/\*(.*?)\*/g, '<i>$1</i>')
      .replace(/\n/g, '<br/>')
      .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" class="text-[#00F5FF] underline font-semibold">$1</a>');

    if (sender === 'user') {
      wrapper.innerHTML = `
        <div class="max-w-[85%] rounded-2xl rounded-tr-sm bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] p-3 text-white shadow-md">
          <p class="leading-relaxed">${formattedText}</p>
        </div>
      `;
    } else {
      let creatorsHtml = '';
      if (creators && creators.length > 0) {
        creatorsHtml = '<div class="mt-2.5 space-y-2">';
        creators.forEach(c => {
          const dotColor = (c.availability && c.availability.includes('This Week')) ? 'bg-amber-400' : 'bg-emerald-400';
          creatorsHtml += `
            <div class="rounded-xl border border-[#2E284C] bg-[#121324] p-2.5 transition hover:border-[#00F5FF] shadow-sm">
              <div class="flex items-center gap-2.5">
                <img src="${escapeHtml(c.avatar)}" alt="${escapeHtml(c.name)}" class="h-9 w-9 rounded-full object-cover ring-1 ring-[#8B5CF6]/50">
                <div class="flex-1 min-w-0">
                  <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold text-[#F1EEFA] truncate">${escapeHtml(c.name)}</h4>
                    <span class="text-[0.65rem] font-bold text-[#00F5FF]">${escapeHtml(c.rate)}</span>
                  </div>
                  <p class="text-[0.65rem] text-[#C4BCE3] truncate font-medium">${escapeHtml(c.role)}</p>
                  <div class="mt-0.5 flex items-center gap-2 text-[0.6rem] text-[#A79DCB]">
                    <span class="flex items-center gap-1">
                      <span class="h-1.5 w-1.5 rounded-full ${dotColor}"></span>
                      ${escapeHtml(c.availability || 'Available')}
                    </span>
                    <span class="text-amber-400">★ ${escapeHtml(c.rating || '4.9')}</span>
                  </div>
                </div>
              </div>
              <a
                href="${escapeHtml(c.link)}"
                class="mt-2 block w-full rounded-lg bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] py-1 text-center text-[0.65rem] font-bold text-white hover:brightness-110 shadow-sm"
              >
                View Profile &amp; 30s Ad Demos →
              </a>
            </div>
          `;
        });
        creatorsHtml += '</div>';
      }

      wrapper.innerHTML = `
        <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-xl bg-[#8B5CF6]/20 text-[#00F5FF] text-sm shadow-sm border border-[#8B5CF6]/30">🤖</div>
        <div class="max-w-[85%] rounded-2xl rounded-tl-sm border border-[#2E284C] bg-[#16172B] p-3 text-[#E8E3F8] shadow-sm leading-relaxed">
          <div>${formattedText}</div>
          ${creatorsHtml}
        </div>
      `;
    }

    messagesEl.appendChild(wrapper);
    scrollChatToBottom();
  }

  function showTypingIndicator() {
    const messagesEl = document.getElementById('chatbot-messages');
    const id = 'typing-' + Date.now();
    const typing = document.createElement('div');
    typing.id = id;
    typing.className = 'flex items-start gap-2.5';
    typing.innerHTML = `
      <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-xl bg-[#8B5CF6]/20 text-[#00F5FF] text-sm shadow-sm border border-[#8B5CF6]/30">🤖</div>
      <div class="rounded-2xl rounded-tl-sm border border-[#2E284C] bg-[#16172B] px-4 py-2.5 text-[#C4BCE3] shadow-sm">
        <span class="inline-flex gap-1 items-center">
          <span class="h-1.5 w-1.5 rounded-full bg-[#00F5FF] animate-bounce"></span>
          <span class="h-1.5 w-1.5 rounded-full bg-[#8B5CF6] animate-bounce" style="animation-delay: 0.2s"></span>
          <span class="h-1.5 w-1.5 rounded-full bg-[#EC4899] animate-bounce" style="animation-delay: 0.4s"></span>
        </span>
      </div>
    `;
    messagesEl.appendChild(typing);
    scrollChatToBottom();
    return id;
  }

  function removeTypingIndicator(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
  }

  function updateChips(suggestions) {
    const chipsContainer = document.querySelector('#chatbot-chips .flex');
    if (!chipsContainer || !suggestions || suggestions.length === 0) return;
    chipsContainer.innerHTML = '';
    suggestions.forEach(s => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'whitespace-nowrap rounded-full border border-[#8B5CF6]/40 bg-[#16172B] px-2.5 py-1 text-[#E8E3F8] transition hover:border-[#00F5FF] hover:text-[#00F5FF]';
      btn.textContent = s;
      btn.onclick = () => sendQuickPrompt(s);
      chipsContainer.appendChild(btn);
    });
  }

  function scrollChatToBottom() {
    const el = document.getElementById('chatbot-messages');
    if (el) el.scrollTop = el.scrollHeight;
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
})();
</script>
