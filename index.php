<?php
// index.php - AI Creator Marketplace Home
require_once __DIR__ . '/config/db.php';

$db = get_db();
// Dynamically query top verified creators from MySQL
$stmt = $db->query("
    SELECT c.*, u.full_name, u.avatar_url 
    FROM creator_profiles c 
    JOIN users u ON c.user_id = u.id 
    ORDER BY c.rating DESC, c.projects_count DESC 
    LIMIT 4
");
$featured_creators = $stmt->fetchAll();

$page_title = "Home - Find Top AI Content Creators";
require_once __DIR__ . '/includes/header.php';

$feature_items = [
    [
        'icon' => '✓',
        'title' => 'Verified Creators',
        'description' => 'Work with trusted & verified AI professionals',
        'accent' => 'from-[#60a5fa] via-[#7c3aed] to-[#ec4899]',
    ],
    [
        'icon' => '▣',
        'title' => 'Detailed Portfolios',
        'description' => 'Explore 30s ads, past work, skills, and tools',
        'accent' => 'from-[#38bdf8] via-[#3b82f6] to-[#8b5cf6]',
    ],
    [
        'icon' => '⌕',
        'title' => 'Smart Search & Filters',
        'description' => 'Find the perfect creator for your ad campaigns',
        'accent' => 'from-[#22d3ee] via-[#60a5fa] to-[#8b5cf6]',
    ],
    [
        'icon' => '⚡',
        'title' => 'Easy Collaboration',
        'description' => 'Connect, discuss and bring your ideas to life',
        'accent' => 'from-[#f59e0b] via-[#8b5cf6] to-[#ec4899]',
    ],
];

$floating_cards = [
    [
        'title' => 'AI Image',
        'color' => 'from-[#38bdf8] via-[#60a5fa] to-[#2563eb]',
        'position' => 'left-0 top-20',
        'rotate' => 'rotate-[-6deg]',
        'size' => 'w-52 h-64',
        'image' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80',
    ],
    [
        'title' => 'AI Video',
        'color' => 'from-[#f472b6] via-[#a855f7] to-[#7c3aed]',
        'position' => 'left-32 top-0',
        'rotate' => 'rotate-[10deg]',
        'size' => 'w-56 h-64',
        'image' => 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=80',
    ],
    [
        'title' => 'AI Animation',
        'color' => 'from-[#f9a8d4] via-[#ec4899] to-[#7c3aed]',
        'position' => 'right-2 top-20',
        'rotate' => 'rotate-[12deg]',
        'size' => 'w-52 h-64',
        'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=900&q=80',
    ],
    [
        'title' => 'AI Graphic',
        'color' => 'from-[#38bdf8] via-[#a78bfa] to-[#f472b6]',
        'position' => 'right-20 bottom-0',
        'rotate' => 'rotate-[-8deg]',
        'size' => 'w-56 h-64',
        'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80',
    ],
];
?>

<main class="bg-[#0A0B12]">
  <!-- Hero Section -->
  <section class="relative overflow-hidden bg-[#0A0B12] px-4 pb-8 pt-10 sm:px-6 lg:px-8">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(6,182,212,0.14),transparent_40%),radial-gradient(circle_at_bottom_right,rgba(139,92,246,0.18),transparent_40%)]"></div>

    <div class="relative mx-auto max-w-7xl">
      <div class="grid items-center gap-10 lg:grid-cols-[1.06fr_0.94fr]">
        <div class="max-w-[620px]">
          <div class="mb-6 inline-flex items-center rounded-full border border-[#8B5CF6]/40 bg-[#16172B] px-4 py-2 text-[0.68rem] font-bold uppercase tracking-[0.2em] text-[#00F5FF] shadow-sm">
            AI content creator marketplace
          </div>

          <h1 class="max-w-[560px] text-5xl font-black leading-[0.95] tracking-[-0.07em] text-[#F1EEFA] sm:text-6xl lg:text-[5rem]">
            Find the Right AI Creators
            <span class="block bg-gradient-to-r from-[#00F5FF] via-[#A855F7] to-[#EC4899] bg-clip-text text-transparent">
              for Your Next Big Idea
            </span>
          </h1>

          <p class="mt-6 max-w-[560px] text-lg leading-8 text-[#C4BCE3]">
            Connect with talented AI content creators and bring your vision to life with stunning
            30-second video advertisements, images, animations and more.
          </p>

          <div class="mt-8 flex flex-col gap-4 sm:flex-row">
            <a
              href="signup.php?role=creator"
              class="inline-flex items-center justify-center rounded-2xl border border-transparent bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-6 py-4 text-base font-semibold text-white shadow-[0_0_25px_rgba(6,182,212,0.35)] transition hover:brightness-110"
            >
              <span class="mr-3 text-lg">✦</span>
              I'm a Creator
            </a>

            <a
              href="login.php?role=brand"
              class="inline-flex items-center justify-center rounded-2xl border border-[#2E284C] bg-[#16172B] px-6 py-4 text-base font-semibold text-[#F1EEFA] shadow-sm transition hover:border-[#00F5FF] hover:bg-[#1A1C36]"
            >
              <span class="mr-3 text-lg">◫</span>
              I'm a Brand
            </a>
          </div>
        </div>

        <!-- Floating Cards Visual -->
        <div class="relative mx-auto h-[420px] w-full max-w-[620px] lg:h-[500px]">
          <div class="absolute inset-x-0 bottom-0 top-10 mx-auto max-w-[540px] rounded-[32px] border border-[#2E284C] bg-[radial-gradient(circle_at_center,rgba(6,182,212,0.22),transparent_60%)] blur-2xl"></div>

          <?php foreach ($floating_cards as $card): ?>
            <div class="absolute <?= $card['position'] ?> <?= $card['rotate'] ?> <?= $card['size'] ?> overflow-hidden rounded-[28px] border border-[#2E284C] bg-[#121324] shadow-[0_15px_35px_rgba(0,0,0,0.6)] backdrop-blur-sm transition duration-300 hover:scale-105">
              <div class="absolute inset-0 bg-gradient-to-br <?= $card['color'] ?> opacity-65"></div>
              <img
                src="<?= $card['image'] ?>"
                alt="<?= $card['title'] ?>"
                class="h-full w-full object-cover opacity-90 mix-blend-luminosity"
              />
              <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(10,11,18,0.2),rgba(10,11,18,0.85))]"></div>
              <div class="absolute inset-x-0 bottom-0 flex items-center justify-between px-4 pb-4 pt-6">
                <div class="flex items-center gap-2 rounded-full border border-white/30 bg-black/50 px-2.5 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.12em] text-[#F1EEFA] backdrop-blur-sm">
                  <span class="text-lg">▶</span>
                  <?= $card['title'] ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>

          <div class="absolute bottom-3 right-10 rotate-[18deg] rounded-[28px] border border-[#2E284C] bg-[#121324]/95 px-4 py-2 text-[2rem] italic text-[#F1EEFA] shadow-[0_15px_30px_rgba(0,0,0,0.5)] backdrop-blur-sm">
            Real Creators.
            <div class="block text-right text-[1.7rem] italic text-[#00F5FF]">Real Content.</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Search Bar Section -->
  <section class="relative -mt-3 bg-[#0A0B12] px-4 pb-10 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
      <form action="brand_creators.php" method="GET" class="rounded-[28px] border border-[#2E284C] bg-[#121324] p-3 shadow-[0_20px_45px_rgba(0,0,0,0.5)]">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
          <div class="flex flex-1 items-center gap-3 rounded-full bg-[#16172B] px-5 py-4 border border-[#2E284C] shadow-inner">
            <span class="text-2xl text-[#C4BCE3]">⌕</span>
            <input
              type="text"
              name="search"
              placeholder="Search creators, skills, tools, or content type..."
              class="w-full border-0 bg-transparent text-base text-[#F1EEFA] placeholder:text-[#A79DCB] focus:outline-none"
            />
          </div>

          <button
            type="submit"
            class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-8 py-4 text-base font-semibold text-white shadow-[0_0_24px_rgba(6,182,212,0.35)] transition hover:brightness-110"
          >
            <span class="mr-2 text-lg">⌕</span>
            Search
          </button>
        </div>
      </form>

      <div class="mt-5 flex flex-wrap items-center gap-3 text-sm text-[#C4BCE3] lg:justify-center">
        <span class="mr-1 font-semibold text-[#F1EEFA]">Popular Searches:</span>
        <?php foreach (['AI Video', 'Runway', 'Midjourney', '30s Ad', 'Animation', 'Product Ads'] as $term): ?>
          <a
            href="brand_creators.php?search=<?= urlencode($term) ?>"
            class="rounded-full border border-[#2E284C] bg-[#16172B] px-3 py-1.5 text-sm text-[#E8E3F8] shadow-sm transition hover:border-[#00F5FF] hover:text-[#00F5FF]"
          >
            <?= $term ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Features Grid -->
  <section class="bg-[#0A0B12] px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-5 md:grid-cols-2 xl:grid-cols-4">
      <?php foreach ($feature_items as $index => $item): ?>
        <div class="<?= ($index < count($feature_items) - 1) ? 'xl:border-r xl:border-[#26243E] xl:pr-5' : '' ?>">
          <div class="flex flex-col items-start justify-start rounded-3xl border border-[#2E284C] bg-[#121324] p-6 shadow-sm hover:border-[#00F5FF] transition">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br <?= $item['accent'] ?> text-xl text-white shadow-md">
              <?= $item['icon'] ?>
            </div>
            <h3 class="text-[1.1rem] font-bold text-[#F1EEFA]"><?= $item['title'] ?></h3>
            <p class="mt-2 text-sm leading-6 text-[#C4BCE3]"><?= $item['description'] ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Promo Dual Cards -->
  <section class="bg-[#0A0B12] px-4 pb-12 pt-6 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-6 lg:grid-cols-2">
      <!-- For creators card -->
      <div class="relative overflow-hidden rounded-[28px] border border-[#8B5CF6]/50 bg-gradient-to-br from-[#2E1065] via-[#1E1145] to-[#0E0F2B] p-8 shadow-[0_15px_35px_rgba(139,92,246,0.25)] text-white">
        <div class="mb-2 text-sm font-bold uppercase tracking-[0.2em] text-[#00F5FF]">For creators</div>
        <h3 class="text-4xl font-black tracking-[-0.06em] text-[#F1EEFA]">Showcase Your AI Skills</h3>
        <p class="mt-4 max-w-md text-base text-[#C4BCE3]">
          Build your profile, upload your 30s video advertisements, and get discovered by brands looking for unique talent.
        </p>
        <a href="signup.php?role=creator" class="mt-8 inline-flex rounded-full border border-white/30 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/25">
          Join as Creator →
        </a>
      </div>

      <!-- For brands card -->
      <div class="relative overflow-hidden rounded-[28px] border border-[#06B6D4]/40 bg-gradient-to-br from-[#0B1528] via-[#0E0F24] to-[#1E1B4B] p-8 shadow-[0_15px_35px_rgba(6,182,212,0.25)] text-white">
        <div class="mb-2 text-sm font-bold uppercase tracking-[0.2em] text-[#00F5FF]">For brands</div>
        <h3 class="text-4xl font-black tracking-[-0.06em] text-[#F1EEFA]">Find the Perfect Creator</h3>
        <p class="mt-4 max-w-md text-base text-[#C4BCE3]">
          Post your advertising requirements, search and filter verified creators, and bring your ideas to life.
        </p>
        <a href="login.php?role=brand" class="mt-8 inline-flex rounded-full border border-white/30 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/25">
          Brand Login / Register →
        </a>
      </div>
    </div>
  </section>

  <!-- Featured Creators Section (Dynamic from MySQL Database) -->
  <section class="bg-[#0A0B12] border-t border-[#26243E] px-4 pb-14 pt-12 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
      <div class="mb-8 flex items-end justify-between gap-4">
        <div>
          <h2 class="text-4xl font-black tracking-[-0.06em] text-[#F1EEFA]">Featured Creators</h2>
          <p class="mt-2 text-base text-[#C4BCE3]">Discover top AI creators with verified skills and 30-second ad portfolios.</p>
        </div>
        <a href="brand_creators.php" class="text-base font-semibold text-[#00F5FF] transition hover:underline">
          View All →
        </a>
      </div>

      <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        <?php foreach ($featured_creators as $creator): ?>
          <?php 
            $skills_list = array_filter(array_map('trim', explode(',', $creator['skills'] ?? '')));
            $avatar = !empty($creator['avatar_url']) ? $creator['avatar_url'] : 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=500&q=80';
          ?>
          <article class="group relative overflow-hidden rounded-[26px] border border-[#2E284C] bg-[#121324] p-4 shadow-[0_16px_30px_rgba(0,0,0,0.5)] transition hover:-translate-y-1 hover:border-[#00F5FF]">
            <a href="creator_view.php?id=<?= $creator['id'] ?>" class="block">
              <div class="flex items-center gap-3">
                <img
                  src="<?= htmlspecialchars($avatar) ?>"
                  alt="<?= htmlspecialchars($creator['full_name']) ?>"
                  class="h-14 w-14 rounded-full object-cover ring-2 ring-[#8B5CF6]/50"
                />
                <div class="min-w-0 flex-1">
                  <div class="flex items-center gap-2">
                    <h3 class="truncate text-lg font-bold text-[#F1EEFA]"><?= htmlspecialchars($creator['full_name']) ?></h3>
                    <?php if ($creator['verified']): ?>
                      <span class="text-sm text-[#00F5FF]">✓</span>
                    <?php endif; ?>
                  </div>
                  <p class="mt-1 text-sm text-[#C4BCE3] truncate"><?= htmlspecialchars($creator['role_title']) ?></p>
                </div>
              </div>

              <div class="mt-4 flex items-center justify-between gap-2 text-sm text-[#C4BCE3]">
                <div class="flex items-center gap-1.5">
                  <span class="text-amber-400">★</span>
                  <span class="font-semibold text-[#F1EEFA]"><?= htmlspecialchars($creator['rating']) ?></span>
                </div>
                <span>(<?= (int)$creator['reviews_count'] ?> reviews)</span>
              </div>

              <div class="mt-3 flex items-center justify-between text-sm text-[#A79DCB]">
                <span><?= (int)$creator['projects_count'] ?> projects</span>
                <span class="font-bold text-[#00F5FF]"><?= htmlspecialchars($creator['hourly_rate']) ?></span>
              </div>

              <div class="mt-4 flex flex-wrap items-center gap-2">
                <?php foreach (array_slice($skills_list, 0, 3) as $skill): ?>
                  <span class="rounded-full border border-[#2E284C] bg-[#16172B] px-2.5 py-1 text-[0.7rem] font-medium text-[#C4BCE3]">
                    <?= htmlspecialchars($skill) ?>
                  </span>
                <?php endforeach; ?>
              </div>

              <div class="mt-4 pt-2 border-t border-[#26243E]">
                <span class="block text-center rounded-xl bg-[#16172B] text-xs font-semibold text-[#00F5FF] py-2 border border-[#2E284C] group-hover:border-[#00F5FF] transition">
                  View Profile & Portfolios →
                </span>
              </div>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
