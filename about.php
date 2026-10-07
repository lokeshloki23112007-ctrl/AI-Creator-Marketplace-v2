<?php
// about.php - About AICreators
require_once __DIR__ . '/config/db.php';
$page_title = "About AICreators";
require_once __DIR__ . '/includes/header.php';
?>

<main class="min-h-screen bg-[#0A0B12] px-4 py-10 text-[#F1EEFA] sm:px-6 lg:px-8 flex-1">
  <section class="mx-auto max-w-4xl rounded-[32px] border border-[#26243E] bg-[#121324] p-6 shadow-[0_20px_60px_rgba(0,0,0,0.6)] sm:p-8">
    <div class="mb-6 inline-flex rounded-full border border-[#00F5FF]/30 bg-[#00F5FF]/10 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-[#00F5FF]">
      About The Platform
    </div>

    <h1 class="text-4xl font-black tracking-[-0.04em] text-[#F1EEFA]">About AICreators Marketplace</h1>

    <div class="mt-6 space-y-4 text-base leading-7 text-[#C4BCE3]">
      <p>
        AICreators connects ambitious brands with elite independent AI artists and content creators. Specializing in high-impact 30-second video advertisements, 3D animations, and social-first commercial storytelling, our marketplace simplifies discovery, hiring, and collaboration.
      </p>
      <p>
        Built on a reliable MySQL and PHP architecture, creators can manage dynamic portfolios, upload video advertisements, and showcase mastery over tools like Runway Gen-3, Kling AI, Midjourney, and Adobe Firefly.
      </p>
      <p>
        Brands can easily post creative briefs, set budgets in INR (₹), review tailored creator pitches, and commission high-converting advertising assets at a fraction of traditional agency timelines.
      </p>
    </div>

    <div class="mt-8 flex gap-4">
      <a href="index.php" class="rounded-full border border-[#26243E] bg-[#16172B] px-6 py-3 text-sm font-semibold text-[#F1EEFA] transition hover:bg-[#1E1F38] hover:border-[#00F5FF]/50">
        Back to Home
      </a>
      <a href="brand_creators.php" class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-6 py-3 text-sm font-semibold text-white shadow-[0_0_20px_rgba(0,245,255,0.35)] hover:brightness-110 transition">
        Explore Creators →
      </a>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
