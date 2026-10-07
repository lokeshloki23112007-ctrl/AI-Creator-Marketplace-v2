<?php
// privacy.php - Privacy Policy
require_once __DIR__ . '/config/db.php';
$page_title = "Privacy Policy";
require_once __DIR__ . '/includes/header.php';
?>

<main class="min-h-screen bg-[#0A0B12] px-4 py-10 text-[#F1EEFA] sm:px-6 lg:px-8 flex-1">
  <section class="mx-auto max-w-4xl rounded-[32px] border border-[#26243E] bg-[#121324] p-6 shadow-[0_20px_60px_rgba(0,0,0,0.6)] sm:p-8">
    <div class="mb-6 inline-flex rounded-full border border-[#00F5FF]/30 bg-[#00F5FF]/10 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-[#00F5FF]">
      Legal &amp; Privacy
    </div>

    <h1 class="text-4xl font-black tracking-[-0.04em] text-[#F1EEFA]">Privacy Policy</h1>

    <div class="mt-6 space-y-4 text-base leading-7 text-[#C4BCE3]">
      <p>
        At AICreators, we respect your privacy and are committed to protecting your personal information. This Privacy Policy explains how our platform collects, stores, and protects data when you register as a creator or brand.
      </p>
      <p>
        All user profile information, uploaded photos, and portfolio video advertisements are securely stored within our relational MySQL database and dedicated upload directories. Password credentials are encrypted using industry-standard bcrypt hashing algorithms.
      </p>
      <p>
        We do not sell, rent, or trade your personal information to third parties. Uploaded portfolio files and 30-second advertisements remain your creative property and are displayed for marketplace discovery purposes.
      </p>
    </div>

    <div class="mt-8">
      <a href="index.php" class="rounded-full border border-[#26243E] bg-[#16172B] px-6 py-3 text-sm font-semibold text-[#F1EEFA] transition hover:bg-[#1E1F38] hover:border-[#00F5FF]/50">
        Back to Home
      </a>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
