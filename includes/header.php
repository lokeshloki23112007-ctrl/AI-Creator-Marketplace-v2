<?php
// includes/header.php
require_once __DIR__ . '/../config/db.php';
$current_user = get_logged_in_user();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - AICreators' : 'AICreators - AI Content Creator Marketplace' ?></title>
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Times New Roman"', 'Times', 'serif'],
            serif: ['"Times New Roman"', 'Times', 'serif'],
          },
          colors: {
            nearBlack: '#0A0B12',
            lunarLavender: '#E8E3F8',
            lunarLilac: '#C4BCE3',
            lunarCard: '#121324',
            lunarSurface: '#16172B',
            lunarBorder: '#2E284C',
            neonCyan: '#06B6D4',
            cosmicPurple: '#8B5CF6',
          }
        }
      }
    }
  </script>

  <style>
    body, * {
      font-family: 'Times New Roman', Times, serif !important;
    }
    body {
      background-color: #0A0B12;
      color: #E8E3F8;
    }
    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }
    ::-webkit-scrollbar-track {
      background: #0A0B12;
    }
    ::-webkit-scrollbar-thumb {
      background: #2E284C;
      border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #06B6D4;
    }
  </style>
</head>
<body class="min-h-screen bg-[#0A0B12] text-[#E8E3F8] antialiased flex flex-col justify-between">

  <!-- Top Navigation Bar -->
  <header class="sticky top-0 z-50 border-b border-[#26243E] bg-[#0E0F1B]/95 backdrop-blur-xl shadow-[0_4px_30px_rgba(0,0,0,0.6)]">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
      
      <!-- Brand Logo -->
      <a href="index.php" class="flex items-center gap-3 group">
        <div class="relative flex h-10 w-10 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] shadow-[0_0_20px_rgba(139,92,246,0.5)] transition group-hover:scale-105">
          <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.4),transparent_60%)]"></div>
          <span class="relative text-lg font-black text-white">▶</span>
        </div>
        <div>
          <div class="text-[1.75rem] font-black leading-none tracking-[-0.06em] text-[#F1EEFA]">
            <?= ($current_user && $current_user['role'] === 'brand') ? 'Brand/Agency' : 'AICreators' ?>
          </div>
          <div class="mt-1 text-[0.58rem] font-medium uppercase tracking-[0.24em] text-[#C4BCE3]">
            <?= ($current_user && $current_user['role'] === 'brand') ? 'Hire • Campaigns • Scale' : 'Create • Connect • Grow' ?>
          </div>
        </div>
      </a>

      <!-- Center Navigation Links -->
      <div class="hidden items-center gap-8 lg:flex">
        <a href="index.php" class="relative text-sm transition duration-200 <?= ($current_page === 'index.php') ? 'font-semibold text-[#00F5FF]' : 'text-[#C4BCE3] hover:text-[#00F5FF]' ?>">
          Home
          <?php if ($current_page === 'index.php'): ?>
            <span class="absolute -bottom-3 left-0 h-0.5 w-full rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899]"></span>
          <?php endif; ?>
        </a>

        <a href="brand_creators.php" class="relative text-sm transition duration-200 <?= ($current_page === 'brand_creators.php') ? 'font-semibold text-[#00F5FF]' : 'text-[#C4BCE3] hover:text-[#00F5FF]' ?>">
          Explore Creators
          <?php if ($current_page === 'brand_creators.php'): ?>
            <span class="absolute -bottom-3 left-0 h-0.5 w-full rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899]"></span>
          <?php endif; ?>
        </a>

        <a href="<?= ($current_user && $current_user['role'] === 'brand') ? 'brand_dashboard.php' : 'login.php?role=brand' ?>" class="relative text-sm transition duration-200 <?= (strpos($current_page, 'brand_') === 0 && $current_page !== 'brand_creators.php') ? 'font-semibold text-[#00F5FF]' : 'text-[#C4BCE3] hover:text-[#00F5FF]' ?>">
          For Brands
          <?php if (strpos($current_page, 'brand_') === 0 && $current_page !== 'brand_creators.php'): ?>
            <span class="absolute -bottom-3 left-0 h-0.5 w-full rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899]"></span>
          <?php endif; ?>
        </a>

        <a href="creator_dashboard.php" class="relative text-sm transition duration-200 <?= (strpos($current_page, 'creator_') === 0) ? 'font-semibold text-[#00F5FF]' : 'text-[#C4BCE3] hover:text-[#00F5FF]' ?>">
          For Creators
          <?php if (strpos($current_page, 'creator_') === 0): ?>
            <span class="absolute -bottom-3 left-0 h-0.5 w-full rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899]"></span>
          <?php endif; ?>
        </a>

        <a href="chatbot.php" class="relative text-sm transition duration-200 <?= ($current_page === 'chatbot.php') ? 'font-semibold text-[#22D3EE]' : 'text-[#22D3EE] hover:text-[#00F5FF]' ?> flex items-center gap-1.5">
          <span>🤖</span>
          <span>AI Chatbot</span>
          <?php if ($current_page === 'chatbot.php'): ?>
            <span class="absolute -bottom-3 left-0 h-0.5 w-full rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899]"></span>
          <?php endif; ?>
        </a>

        <a href="about.php" class="relative text-sm transition duration-200 <?= ($current_page === 'about.php') ? 'font-semibold text-[#00F5FF]' : 'text-[#C4BCE3] hover:text-[#00F5FF]' ?>">
          About
          <?php if ($current_page === 'about.php'): ?>
            <span class="absolute -bottom-3 left-0 h-0.5 w-full rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899]"></span>
          <?php endif; ?>
        </a>
      </div>

      <!-- Right Action Buttons / User Menu -->
      <div class="flex items-center gap-3 sm:gap-4">
        <a href="brand_creators.php" aria-label="Search" class="flex h-10 w-10 items-center justify-center rounded-full border border-[#2E284C] bg-[#16172B] text-lg text-[#C4BCE3] shadow-sm transition hover:border-[#06B6D4] hover:text-[#00F5FF]">
          ⌕
        </a>

        <?php if ($current_user): ?>
          <!-- Logged in State -->
          <div class="relative group">
            <a href="<?= $current_user['role'] === 'brand' ? 'brand_dashboard.php' : 'creator_dashboard.php' ?>" class="flex items-center gap-3 rounded-full border border-[#2E284C] bg-[#16172B] px-3 py-1.5 shadow-sm transition hover:border-[#06B6D4]">
              <?php if (!empty($current_user['avatar_url'])): ?>
                <img src="<?= htmlspecialchars($current_user['avatar_url']) ?>" alt="Avatar" class="h-8 w-8 rounded-full object-cover ring-2 ring-[#8b5cf6]/50">
              <?php else: ?>
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-xs font-bold text-white shadow-sm">
                  <?= strtoupper(substr($current_user['full_name'], 0, 2)) ?>
                </div>
              <?php endif; ?>
              <div class="hidden text-left sm:block pr-1">
                <div class="text-xs font-semibold text-[#F1EEFA] leading-tight"><?= htmlspecialchars($current_user['full_name']) ?></div>
                <div class="text-[0.68rem] text-[#C4BCE3] capitalize"><?= htmlspecialchars($current_user['role']) ?></div>
              </div>
            </a>

            <!-- Dropdown on hover -->
            <div class="absolute right-0 top-full mt-2 hidden w-48 rounded-2xl border border-[#2E284C] bg-[#121324] p-2 shadow-2xl backdrop-blur-xl group-hover:block z-50">
              <a href="<?= $current_user['role'] === 'brand' ? 'brand_dashboard.php' : 'creator_dashboard.php' ?>" class="block rounded-xl px-3 py-2 text-xs font-medium text-[#C4BCE3] hover:bg-[#191A30] hover:text-[#F1EEFA]">
                Dashboard
              </a>
              <a href="<?= $current_user['role'] === 'brand' ? 'brand_profile.php' : 'creator_profile.php' ?>" class="block rounded-xl px-3 py-2 text-xs font-medium text-[#C4BCE3] hover:bg-[#191A30] hover:text-[#F1EEFA]">
                Edit Profile & Photo
              </a>
              <?php if ($current_user['role'] === 'creator'): ?>
                <a href="creator_portfolio_add.php" class="block rounded-xl px-3 py-2 text-xs font-medium text-[#C4BCE3] hover:bg-[#191A30] hover:text-[#F1EEFA]">
                  Upload Ad Video (30s)
                </a>
              <?php else: ?>
                <a href="brand_brief.php" class="block rounded-xl px-3 py-2 text-xs font-medium text-[#C4BCE3] hover:bg-[#191A30] hover:text-[#F1EEFA]">
                  Create Brief
                </a>
              <?php endif; ?>
              <div class="my-1 border-t border-[#26243E]"></div>
              <a href="logout.php" class="block rounded-xl px-3 py-2 text-xs font-medium text-rose-400 hover:bg-rose-500/10">
                Log Out
              </a>
            </div>
          </div>
        <?php else: ?>
          <!-- Logged out State -->
          <a href="login.php" class="rounded-full border border-[#8B5CF6] bg-transparent px-5 py-2 text-sm font-semibold text-[#E8E3F8] shadow-sm transition hover:bg-[#8B5CF6]/15 hover:border-[#06B6D4]">
            Login
          </a>

          <a href="signup.php" class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-5 py-2 text-sm font-semibold text-white shadow-[0_0_20px_rgba(6,182,212,0.4)] transition hover:brightness-110">
            Sign Up
          </a>
        <?php endif; ?>
      </div>

    </nav>
  </header>
