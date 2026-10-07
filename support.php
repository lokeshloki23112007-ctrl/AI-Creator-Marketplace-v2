<?php
// support.php - Support & Help Desk
require_once __DIR__ . '/config/db.php';
$page_title = "Help & Support";
require_once __DIR__ . '/includes/header.php';
?>

<main class="min-h-screen bg-[#0A0B12] px-4 py-10 text-[#F1EEFA] sm:px-6 lg:px-8 flex-1">
  <section class="mx-auto max-w-4xl rounded-[32px] border border-[#26243E] bg-[#121324] p-6 shadow-[0_20px_60px_rgba(0,0,0,0.6)] sm:p-8">
    <div class="mb-6 inline-flex rounded-full border border-[#00F5FF]/30 bg-[#00F5FF]/10 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-[#00F5FF]">
      Help &amp; Documentation
    </div>

    <h1 class="text-4xl font-black tracking-[-0.04em] text-[#F1EEFA]">Help &amp; Support</h1>

    <div class="mt-6 space-y-4 text-base leading-7 text-[#C4BCE3]">
      <p>
        Need assistance setting up your creator portfolio, uploading a 30-second advertisement video, or configuring your brand campaign? Our support team is here to help you get the most out of AICreators.
      </p>

      <div class="grid gap-4 md:grid-cols-2 pt-4">
        <div class="rounded-2xl border border-[#26243E] bg-[#16172B] p-5 shadow-sm">
          <h3 class="text-base font-bold text-[#F1EEFA] mb-1">📹 Video Upload Guidelines</h3>
          <p class="text-xs text-[#C4BCE3]">
            Ensure your advertisement videos are formatted in MP4 or WebM and are 30 seconds or less in length for optimal playback and brand conversion.
          </p>
        </div>

        <div class="rounded-2xl border border-[#26243E] bg-[#16172B] p-5 shadow-sm">
          <h3 class="text-base font-bold text-[#F1EEFA] mb-1">💻 XAMPP Hosting &amp; Setup</h3>
          <p class="text-xs text-[#C4BCE3]">
            For local hosting in XAMPP, place this folder inside <code class="text-[#00F5FF] font-bold">xampp/htdocs/</code> and import <code class="text-[#00F5FF] font-bold">database.sql</code> into phpMyAdmin.
          </p>
        </div>
      </div>
    </div>

    <div class="mt-8 flex gap-4">
      <a href="index.php" class="rounded-full border border-[#26243E] bg-[#16172B] px-6 py-3 text-sm font-semibold text-[#F1EEFA] transition hover:bg-[#1E1F38] hover:border-[#00F5FF]/50">
        Back to Home
      </a>
      <a href="creator_dashboard.php" class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-6 py-3 text-sm font-semibold text-white shadow-[0_0_20px_rgba(0,245,255,0.35)] hover:brightness-110 transition">
        Creator Studio →
      </a>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
