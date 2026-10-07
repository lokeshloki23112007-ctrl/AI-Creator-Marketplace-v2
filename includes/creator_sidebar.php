<?php
// includes/creator_sidebar.php
$cur = basename($_SERVER['PHP_SELF']);
$sidebar_items = [
    ['label' => 'Dashboard', 'href' => 'creator_dashboard.php', 'icon' => '◫'],
    ['label' => 'Edit Profile & Photo', 'href' => 'creator_profile.php', 'icon' => '👤'],
    ['label' => 'My Portfolio', 'href' => 'creator_portfolio.php', 'icon' => '📁'],
    ['label' => 'Upload 30s Ad Video', 'href' => 'creator_portfolio_add.php', 'icon' => '🎬'],
    ['label' => 'AI Tools & Models', 'href' => 'creator_tools.php', 'icon' => '⚡'],
    ['label' => 'Explore Marketplace', 'href' => 'brand_creators.php', 'icon' => '🌐'],
    ['label' => 'AI Chatbot', 'href' => 'chatbot.php', 'icon' => '🤖'],
    ['label' => 'Logout', 'href' => 'logout.php', 'icon' => '🚪'],
];
?>
<aside class="border-r border-[#26243E] bg-[#0E0F1D] p-5">
  <nav class="space-y-2">
    <?php foreach ($sidebar_items as $item): ?>
      <?php $active = ($cur === $item['href']); ?>
      <a
        href="<?= $item['href'] ?>"
        class="flex w-full items-center justify-between rounded-2xl px-4 py-3 text-left text-sm font-medium transition <?= $active 
          ? 'bg-gradient-to-r from-[#06b6d4]/15 via-[#8b5cf6]/20 to-[#ec4899]/15 text-[#00F5FF] shadow-sm border border-[#06B6D4]/40 font-semibold' 
          : 'text-[#C4BCE3] hover:bg-[#16172B] hover:text-[#00F5FF]' ?>"
      >
        <span class="flex items-center gap-2.5">
          <span class="text-xs opacity-75"><?= $item['icon'] ?></span>
          <span><?= htmlspecialchars($item['label']) ?></span>
        </span>
        <?php if ($active): ?>
          <span class="h-2 w-2 rounded-full bg-[#00F5FF] shadow-[0_0_8px_#00F5FF]"></span>
        <?php endif; ?>
      </a>
    <?php endforeach; ?>
  </nav>
</aside>
