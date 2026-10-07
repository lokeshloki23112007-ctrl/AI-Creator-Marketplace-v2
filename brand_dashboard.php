<?php
// brand_dashboard.php - Dynamic Brand Dashboard
require_once __DIR__ . '/config/db.php';
$brand = require_login('brand');

$db = get_db();

// Fetch Active Briefs
$b_stmt = $db->prepare("SELECT * FROM briefs WHERE brand_id = :bid ORDER BY created_at DESC");
$b_stmt->execute(['bid' => $brand['id']]);
$all_briefs = $b_stmt->fetchAll();

$active_briefs = array_filter($all_briefs, fn($b) => $b['status'] === 'Active');

// Fetch Proposals received
$app_stmt = $db->prepare("
    SELECT a.*, c.role_title, c.avatar_url, u.full_name as creator_name, b.campaign_name
    FROM applications a
    JOIN creator_profiles c ON a.creator_id = c.id
    JOIN users u ON c.user_id = u.id
    JOIN briefs b ON a.brief_id = b.id
    WHERE b.brand_id = :bid
    ORDER BY a.created_at DESC
    LIMIT 5
");
$app_stmt->execute(['bid' => $brand['id']]);
$recent_proposals = $app_stmt->fetchAll();

// Total creators in marketplace
$tot_stmt = $db->query("SELECT COUNT(*) as cnt FROM creator_profiles");
$total_creators = $tot_stmt->fetch()['cnt'] ?? 0;

$flash = get_flash();
$welcome_brand = $_GET['welcome'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Brand Dashboard - AICreators</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body, * { font-family: 'Times New Roman', Times, serif !important; }
    body { background-color: #0A0B12; color: #F1EEFA; }
  </style>
</head>
<body class="min-h-screen bg-[#0A0B12] px-4 py-6 text-[#F1EEFA] sm:px-6 lg:px-8">

  <div class="mx-auto max-w-7xl overflow-hidden rounded-[30px] border border-[#26243E] bg-[#0E0F1B] shadow-[0_20px_65px_rgba(0,0,0,0.8)]">
    <!-- Top Header -->
    <header class="flex items-center justify-between border-b border-[#26243E] bg-[#0E0F1B]/95 px-6 py-4">
      <a href="index.php" class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] shadow-[0_0_20px_rgba(6,182,212,0.4)]">
          <span class="text-lg font-black text-white">▶</span>
        </div>
        <div>
          <div class="text-2xl font-black tracking-[-0.06em] text-[#F1EEFA]">Brand/Agency</div>
          <div class="text-[0.58rem] uppercase tracking-[0.2em] text-[#C4BCE3]">Hire • Campaigns • Scale</div>
        </div>
      </a>

      <!-- Real Logged In Brand Name & Avatar -->
      <a href="brand_profile.php" class="flex items-center gap-3 group">
        <?php if (!empty($brand['avatar_url'])): ?>
          <img src="<?= htmlspecialchars($brand['avatar_url']) ?>" alt="Brand Logo" class="h-10 w-10 rounded-full object-cover ring-2 ring-cyan-400 shadow-sm">
        <?php else: ?>
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-sm font-bold text-white shadow-md">
            <?= strtoupper(substr($brand['full_name'], 0, 2)) ?>
          </div>
        <?php endif; ?>
        <div>
          <div class="text-sm font-semibold text-[#F1EEFA] group-hover:text-[#00F5FF] transition"><?= htmlspecialchars($brand['full_name']) ?></div>
          <div class="text-xs text-[#A79DCB]">Verified Brand</div>
        </div>
      </a>
    </header>

    <div class="grid min-h-[760px] lg:grid-cols-[260px_1fr]">
      <!-- Left Sidebar -->
      <?php require_once __DIR__ . '/includes/brand_sidebar.php'; ?>

      <!-- Main Content -->
      <main class="p-6 lg:p-8 bg-[#0A0B12]/60">
        <?php if ($flash || $welcome_brand): ?>
          <div class="mb-6 rounded-2xl border border-cyan-500/30 bg-cyan-950/40 p-4 text-sm text-cyan-300 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
              <span class="text-xl">🏢</span>
              <span class="font-bold text-[#F1EEFA]">
                <?= htmlspecialchars($flash['message'] ?? ("Welcome, " . $brand['full_name'] . "! 👋")) ?>
              </span>
            </div>
          </div>
        <?php endif; ?>

        <!-- Welcome Banner Section (Prominently mentions real brand name) -->
        <section class="rounded-[28px] border border-[#26243E] bg-[#121324] p-6 shadow-sm">
          <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
              <p class="text-sm uppercase tracking-[0.18em] text-[#00F5FF] font-bold">Brand dashboard</p>
              <h1 class="mt-2 text-4xl font-black tracking-[-0.06em] text-[#F1EEFA]">
                Welcome, <?= htmlspecialchars($brand['full_name']) ?>! 👋
              </h1>
              <p class="mt-1 text-sm text-[#C4BCE3]">
                Manage your advertising briefs, connect with creators, and review high-converting 30-second ad videos.
              </p>
            </div>

            <div class="flex flex-wrap gap-3">
              <a
                href="brand_brief.php"
                class="rounded-full border border-[#26243E] bg-[#16172B] px-5 py-2.5 text-sm font-semibold text-[#F1EEFA] transition hover:bg-[#1f2038] shadow-sm flex items-center gap-2"
              >
                <span>+</span>
                Create New Brief
              </a>
              <a
                href="brand_creators.php"
                class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-5 py-2.5 text-sm font-semibold text-white shadow-[0_0_18px_rgba(6,182,212,0.35)] transition hover:brightness-110 flex items-center gap-2"
              >
                <span>⌕</span>
                Search Creators
              </a>
              <a
                href="brand_profile.php"
                class="rounded-full border border-[#26243E] bg-[#16172B] px-4 py-2.5 text-sm font-semibold text-[#C4BCE3] transition hover:text-[#F1EEFA] shadow-sm flex items-center gap-2"
              >
                <span>🏢</span>
                Edit Profile
              </a>
            </div>
          </div>
        </section>

        <!-- Dynamic Stats Grid from MySQL -->
        <section class="mt-8 grid gap-5 md:grid-cols-3">
          <div class="rounded-[26px] border border-[#26243E] bg-[#121324] p-6 shadow-sm">
            <div class="text-sm text-[#A79DCB] font-semibold">Active Briefs</div>
            <div class="mt-3 text-3xl font-black text-[#00F5FF]"><?= str_pad(count($active_briefs), 2, '0', STR_PAD_LEFT) ?></div>
            <div class="mt-2 text-xs text-[#A79DCB]">Live campaigns accepting proposals</div>
          </div>
          <div class="rounded-[26px] border border-[#26243E] bg-[#121324] p-6 shadow-sm">
            <div class="text-sm text-[#A79DCB] font-semibold">Proposals Received</div>
            <div class="mt-3 text-3xl font-black text-purple-400"><?= str_pad(count($recent_proposals), 2, '0', STR_PAD_LEFT) ?></div>
            <div class="mt-2 text-xs text-[#A79DCB]">From verified AI creators</div>
          </div>
          <div class="rounded-[26px] border border-[#26243E] bg-[#121324] p-6 shadow-sm">
            <div class="text-sm text-[#A79DCB] font-semibold">Available Creators</div>
            <div class="mt-3 text-3xl font-black text-cyan-300"><?= str_pad($total_creators, 2, '0', STR_PAD_LEFT) ?></div>
            <div class="mt-2 text-xs text-[#A79DCB]">Ready for hire in marketplace</div>
          </div>
        </section>

        <!-- Quick Actions & Recent Briefs Grid -->
        <section class="mt-8 grid gap-5 lg:grid-cols-[1.1fr_0.9fr]">
          <!-- Left: Quick Actions -->
          <div class="rounded-[26px] border border-[#26243E] bg-[#121324] p-6 shadow-sm">
            <h2 class="text-xl font-bold text-[#F1EEFA]">Quick Actions</h2>
            <div class="mt-5 grid gap-3">
              <a
                href="brand_brief.php"
                class="rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-left text-sm font-medium text-[#C4BCE3] transition hover:bg-[#1e1f37] hover:text-[#00F5FF] flex items-center justify-between"
              >
                <span>Post a New 30s Ad Campaign Brief</span>
                <span class="text-xs text-[#7A7593]">→</span>
              </a>
              <a
                href="brand_creators.php"
                class="rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-left text-sm font-medium text-[#C4BCE3] transition hover:bg-[#1e1f37] hover:text-[#00F5FF] flex items-center justify-between"
              >
                <span>Explore Top AI Video Creators (<?= $total_creators ?> Available)</span>
                <span class="text-xs text-[#7A7593]">→</span>
              </a>
              <a
                href="brand_profile.php"
                class="rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-left text-sm font-medium text-[#C4BCE3] transition hover:bg-[#1e1f37] hover:text-[#00F5FF] flex items-center justify-between"
              >
                <span>Update Brand Profile &amp; Company Logo</span>
                <span class="text-xs text-[#7A7593]">→</span>
              </a>
            </div>
          </div>

          <!-- Right: Recent Briefs (Dynamic from MySQL) -->
          <div id="briefs" class="rounded-[26px] border border-[#26243E] bg-[#121324] p-6 shadow-sm">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-xl font-bold text-[#F1EEFA]">My Active Briefs</h2>
                <p class="text-xs text-[#A79DCB]">Add, update, or track campaign briefs</p>
              </div>
              <div class="flex items-center gap-2">
                <a href="brand_brief.php" class="rounded-full border border-[#00F5FF]/30 bg-[#00F5FF]/10 px-3 py-1 text-xs font-bold text-[#00F5FF] hover:bg-[#00F5FF]/20 transition">+ New Brief</a>
                <a href="brand_brief.php#manage-briefs" class="text-xs text-[#C4BCE3] hover:text-[#00F5FF] font-semibold underline">Manage All →</a>
              </div>
            </div>

            <div class="mt-5 space-y-3 text-sm text-[#C4BCE3]">
              <?php if (empty($all_briefs)): ?>
                <div class="rounded-2xl border border-[#26243E] bg-[#16172B] p-5 text-center text-[#A79DCB]">
                  <p class="text-xs">No active campaign briefs created yet.</p>
                  <a href="brand_brief.php" class="mt-2 inline-block rounded-full bg-gradient-to-r from-[#06b6d4] to-[#8b5cf6] px-4 py-1.5 text-xs font-bold text-white shadow-sm hover:brightness-110">
                    + Create your first brief
                  </a>
                </div>
              <?php else: ?>
                <?php foreach (array_slice($all_briefs, 0, 5) as $b): ?>
                  <div class="rounded-2xl border border-[#26243E] bg-[#16172B] p-3.5 flex items-center justify-between hover:border-[#00F5FF]/40 transition">
                    <div class="min-w-0 flex-1 mr-3">
                      <div class="font-bold text-[#F1EEFA] truncate"><?= htmlspecialchars($b['campaign_name']) ?></div>
                      <div class="text-[0.72rem] text-[#A79DCB] mt-0.5 truncate">
                        <?= htmlspecialchars($b['content_type']) ?> • <?= htmlspecialchars($b['format']) ?> • <b class="text-[#00F5FF]"><?= htmlspecialchars($b['budget']) ?></b>
                      </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                      <span class="rounded-full bg-emerald-950/60 text-emerald-300 border border-emerald-500/30 text-[0.65rem] font-bold px-2 py-0.5">
                        <?= htmlspecialchars($b['status']) ?>
                      </span>
                      <a
                        href="brand_brief.php?edit=<?= $b['id'] ?>"
                        class="rounded-full border border-[#26243E] bg-[#121324] px-2.5 py-1 text-[0.68rem] font-bold text-[#00F5FF] hover:border-[#00F5FF] transition"
                        title="Update this brief"
                      >
                        ✏️ Edit
                      </a>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </section>

        <!-- Proposals Received from Creators -->
        <?php if (!empty($recent_proposals)): ?>
          <section class="mt-8">
            <h2 class="text-xl font-bold text-[#F1EEFA] mb-4">Recent Creator Applications</h2>
            <div class="grid gap-4 md:grid-cols-2">
              <?php foreach ($recent_proposals as $prop): ?>
                <div class="rounded-[24px] border border-[#26243E] bg-[#121324] p-5 shadow-sm">
                  <div class="flex items-center justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3">
                      <?php if (!empty($prop['avatar_url'])): ?>
                        <img src="<?= htmlspecialchars($prop['avatar_url']) ?>" alt="Avatar" class="h-10 w-10 rounded-full object-cover ring-1 ring-cyan-400">
                      <?php else: ?>
                        <div class="h-10 w-10 rounded-full bg-purple-700 flex items-center justify-center font-bold text-white text-xs">
                          <?= strtoupper(substr($prop['creator_name'], 0, 2)) ?>
                        </div>
                      <?php endif; ?>
                      <div>
                        <h4 class="text-sm font-bold text-[#F1EEFA]"><?= htmlspecialchars($prop['creator_name']) ?></h4>
                        <p class="text-xs text-cyan-300 font-medium"><?= htmlspecialchars($prop['role_title']) ?></p>
                      </div>
                    </div>
                    <span class="text-xs font-bold text-emerald-300 bg-emerald-950/60 border border-emerald-500/30 px-2 py-0.5 rounded-full"><?= htmlspecialchars($prop['proposed_rate']) ?></span>
                  </div>

                  <p class="text-xs text-[#C4BCE3] mb-3 bg-[#16172B] border border-[#26243E] p-3 rounded-xl leading-relaxed">
                    "<?= htmlspecialchars($prop['pitch']) ?>"
                  </p>

                  <div class="text-[0.68rem] text-[#A79DCB] flex items-center justify-between">
                    <span>Applied to: <b class="text-[#F1EEFA]"><?= htmlspecialchars($prop['campaign_name']) ?></b></span>
                    <a href="creator_view.php?id=<?= $prop['creator_id'] ?>" class="text-[#00F5FF] font-semibold hover:underline">
                      View Creator Portfolio →
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </section>
        <?php endif; ?>

      </main>
    </div>
  </div>

  <!-- Floating AI Assistant Chatbot -->
  <?php require_once __DIR__ . '/includes/chatbot.php'; ?>

</body>
</html>
