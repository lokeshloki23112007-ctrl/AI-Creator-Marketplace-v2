<?php
// creator_dashboard.php - Dynamic Creator Dashboard with Open Brand Briefs
require_once __DIR__ . '/config/db.php';
$user = require_login('creator');

$db = get_db();

// Fetch Creator Profile
$stmt = $db->prepare("SELECT * FROM creator_profiles WHERE user_id = :uid LIMIT 1");
$stmt->execute(['uid' => $user['id']]);
$profile = $stmt->fetch();

if (!$profile) {
    $ins = $db->prepare("INSERT INTO creator_profiles (user_id, role_title, bio, skills, tools) VALUES (:uid, 'AI Creator', '', 'AI Video', 'Runway')");
    $ins->execute(['uid' => $user['id']]);
    $stmt->execute(['uid' => $user['id']]);
    $profile = $stmt->fetch();
}

// Handle Creator Proposal Submission to a Brief
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_brief_id'])) {
    $brief_id = (int)$_POST['apply_brief_id'];
    $pitch = trim($_POST['pitch'] ?? '');
    $proposed_rate = trim($_POST['proposed_rate'] ?? '₹25,000');

    if (!empty($pitch)) {
        // Check if already applied
        $check_app = $db->prepare("SELECT id FROM applications WHERE brief_id = :bid AND creator_id = :cid");
        $check_app->execute(['bid' => $brief_id, 'cid' => $profile['id']]);
        if ($check_app->fetch()) {
            set_flash('error', "You have already submitted a proposal for this campaign brief.");
        } else {
            $ins_app = $db->prepare("
                INSERT INTO applications (brief_id, creator_id, pitch, proposed_rate, status)
                VALUES (:bid, :cid, :pitch, :rate, 'Pending')
            ");
            $ins_app->execute([
                'bid' => $brief_id,
                'cid' => $profile['id'],
                'pitch' => $pitch,
                'rate' => $proposed_rate
            ]);
            set_flash('success', "Your proposal has been submitted to the brand! 🚀");
        }
        header("Location: creator_dashboard.php#briefs-section");
        exit;
    }
}

// Fetch Creator Portfolios
$p_stmt = $db->prepare("SELECT * FROM portfolio_projects WHERE creator_id = :cid ORDER BY created_at DESC");
$p_stmt->execute(['cid' => $profile['id']]);
$portfolios = $p_stmt->fetchAll();

// Fetch Active Proposals by this creator
$my_apps_stmt = $db->prepare("
    SELECT a.*, b.campaign_name, b.budget, b.format, u.full_name as brand_name
    FROM applications a
    JOIN briefs b ON a.brief_id = b.id
    JOIN users u ON b.brand_id = u.id
    WHERE a.creator_id = :cid
    ORDER BY a.created_at DESC
");
$my_apps_stmt->execute(['cid' => $profile['id']]);
$my_applications = $my_apps_stmt->fetchAll();
$proposals_count = count($my_applications);

// Fetch Open Brand Campaign Briefs available to apply
$open_briefs_stmt = $db->query("
    SELECT b.*, u.full_name as brand_name, u.avatar_url as brand_avatar
    FROM briefs b
    JOIN users u ON b.brand_id = u.id
    WHERE b.status = 'Active'
    ORDER BY b.created_at DESC
    LIMIT 6
");
$open_briefs = $open_briefs_stmt->fetchAll();

// Dynamic profile completion calculation
$completion = 20;
if (!empty($user['avatar_url']) || !empty($profile['avatar_url'])) $completion += 20;
if (!empty($profile['bio']) && strlen($profile['bio']) > 15) $completion += 20;
if (!empty($profile['skills'])) $completion += 15;
if (!empty($profile['tools'])) $completion += 10;
if (count($portfolios) > 0) $completion += 15;
$completion = min(100, $completion);

$flash = get_flash();
$welcome_param = $_GET['welcome'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creator Dashboard - AICreators</title>
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
          <div class="text-2xl font-black tracking-[-0.06em] text-[#F1EEFA]">AICreators</div>
          <div class="text-[0.58rem] uppercase tracking-[0.2em] text-[#C4BCE3]">Create • Connect • Grow</div>
        </div>
      </a>

      <div class="flex items-center gap-3">
        <?php if (!empty($profile['avatar_url'])): ?>
          <img src="<?= htmlspecialchars($profile['avatar_url']) ?>" alt="Avatar" class="h-10 w-10 rounded-full object-cover ring-2 ring-purple-400 shadow-sm">
        <?php else: ?>
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-sm font-bold text-white shadow-md">
            <?= strtoupper(substr($user['full_name'], 0, 2)) ?>
          </div>
        <?php endif; ?>
        <div>
          <div class="text-sm font-semibold text-[#F1EEFA]"><?= htmlspecialchars($user['full_name']) ?></div>
          <div class="text-xs text-[#A79DCB]">Creator Studio</div>
        </div>
      </div>
    </header>

    <div class="grid min-h-[760px] lg:grid-cols-[260px_1fr]">
      <!-- Left Sidebar -->
      <?php require_once __DIR__ . '/includes/creator_sidebar.php'; ?>

      <!-- Main Content -->
      <main class="p-6 lg:p-8 bg-[#0A0B12]/60 space-y-8">
        <?php if ($flash || $welcome_param): ?>
          <div class="rounded-2xl border border-cyan-500/30 bg-cyan-950/40 p-4 text-sm text-cyan-300 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
              <span class="text-xl">✨</span>
              <span class="font-bold text-[#F1EEFA]">
                <?= htmlspecialchars($flash['message'] ?? ("Welcome, " . $user['full_name'] . "! 👋")) ?>
              </span>
            </div>
          </div>
        <?php endif; ?>

        <!-- Welcome Banner Section -->
        <section class="rounded-[28px] border border-[#26243E] bg-[#121324] p-6 shadow-sm">
          <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
              <p class="text-sm uppercase tracking-[0.18em] text-[#00F5FF] font-bold">Creator studio</p>
              <h1 class="mt-2 text-4xl font-black tracking-[-0.06em] text-[#F1EEFA]">
                Welcome, <?= htmlspecialchars($user['full_name']) ?>! 👋
              </h1>
              <p class="mt-1 text-sm text-[#C4BCE3]">
                <?= htmlspecialchars($profile['role_title'] ?? 'AI Creator') ?> • Ready for commercial brand advertisements.
              </p>
            </div>

            <div class="flex flex-wrap gap-3">
              <a
                href="#briefs-section"
                class="rounded-full border border-[#00F5FF]/40 bg-[#00F5FF]/10 px-5 py-2.5 text-sm font-bold text-[#00F5FF] hover:bg-[#00F5FF]/20 transition shadow-sm flex items-center gap-2"
              >
                <span>📝</span>
                Browse Open Briefs (<?= count($open_briefs) ?>)
              </a>
              <a
                href="creator_portfolio_add.php"
                class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-5 py-2.5 text-sm font-semibold text-white shadow-[0_0_18px_rgba(6,182,212,0.35)] transition hover:brightness-110 flex items-center gap-2"
              >
                <span>🎬</span>
                Upload 30s Ad Video
              </a>
            </div>
          </div>
        </section>

        <!-- Profile Completion & Quick Actions Grid -->
        <section class="grid gap-5 lg:grid-cols-[1.1fr_0.9fr]">
          <div class="rounded-[26px] border border-[#26243E] bg-[#121324] p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
              <h2 class="text-xl font-bold text-[#F1EEFA]">Profile completion</h2>
              <span class="rounded-full border border-cyan-500/30 bg-cyan-950/40 px-3 py-1 text-sm font-bold text-cyan-300"><?= $completion ?>%</span>
            </div>
            <div class="h-3 overflow-hidden rounded-full bg-[#16172B]">
              <div class="h-full rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] transition-all duration-500" style="width: <?= $completion ?>%;"></div>
            </div>
            <div class="mt-4 text-sm text-[#C4BCE3]">
              <?= $completion < 100 
                ? 'Upload a profile photo and your 30s ad portfolio to reach 100% and get featured for brands.' 
                : 'Awesome! Your creator profile is complete and optimized for brand deals.' ?>
            </div>
            <div class="mt-4 flex gap-3">
              <a href="creator_profile.php" class="text-xs font-semibold text-[#00F5FF] hover:underline">
                Update Profile & Photo →
              </a>
            </div>
          </div>

          <div class="rounded-[26px] border border-[#26243E] bg-[#121324] p-6 shadow-sm">
            <h2 class="text-xl font-bold text-[#F1EEFA]">Quick Actions</h2>
            <div class="mt-5 grid gap-3">
              <a
                href="#briefs-section"
                class="rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-left text-sm font-medium text-[#C4BCE3] transition hover:bg-[#1e1f37] hover:text-[#00F5FF] flex items-center justify-between"
              >
                <span>Apply to Brand Campaigns &amp; Briefs</span>
                <span class="text-xs text-[#7A7593]">→</span>
              </a>
              <a
                href="creator_portfolio_add.php"
                class="rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-left text-sm font-medium text-[#C4BCE3] transition hover:bg-[#1e1f37] hover:text-[#00F5FF] flex items-center justify-between"
              >
                <span>Upload New 30-Second Ad Video</span>
                <span class="text-xs text-[#7A7593]">→</span>
              </a>
              <a
                href="creator_portfolio.php"
                class="rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-left text-sm font-medium text-[#C4BCE3] transition hover:bg-[#1e1f37] hover:text-[#00F5FF] flex items-center justify-between"
              >
                <span>View My Portfolio (<?= count($portfolios) ?> items)</span>
                <span class="text-xs text-[#7A7593]">→</span>
              </a>
            </div>
          </div>
        </section>

        <!-- Stats Grid (Dynamic from MySQL) -->
        <section class="grid gap-5 md:grid-cols-3">
          <div class="rounded-[26px] border border-[#26243E] bg-[#121324] p-6 shadow-sm">
            <div class="text-sm text-[#A79DCB] font-semibold">Portfolio Projects</div>
            <div class="mt-3 text-3xl font-black text-[#00F5FF]"><?= str_pad(count($portfolios), 2, '0', STR_PAD_LEFT) ?></div>
            <div class="mt-2 text-xs text-[#A79DCB]">Includes 30s ad showcases</div>
          </div>
          <div class="rounded-[26px] border border-[#26243E] bg-[#121324] p-6 shadow-sm">
            <div class="text-sm text-[#A79DCB] font-semibold">Open Brand Briefs</div>
            <div class="mt-3 text-3xl font-black text-cyan-300"><?= str_pad(count($open_briefs), 2, '0', STR_PAD_LEFT) ?></div>
            <div class="mt-2 text-xs text-[#A79DCB]">Active advertising briefs</div>
          </div>
          <div class="rounded-[26px] border border-[#26243E] bg-[#121324] p-6 shadow-sm">
            <div class="text-sm text-[#A79DCB] font-semibold">My Submitted Proposals</div>
            <div class="mt-3 text-3xl font-black text-purple-400"><?= str_pad($proposals_count, 2, '0', STR_PAD_LEFT) ?></div>
            <div class="mt-2 text-xs text-[#A79DCB]">Campaign applications sent</div>
          </div>
        </section>

        <!-- SECTION: OPEN BRAND CAMPAIGN BRIEFS (Add & Apply to Briefs) -->
        <section id="briefs-section" class="rounded-[28px] border border-[#26243E] bg-[#121324] p-6 lg:p-8 shadow-sm">
          <div class="flex items-center justify-between mb-6">
            <div>
              <p class="text-xs uppercase tracking-[0.18em] text-[#00F5FF] font-bold">Brand Opportunities</p>
              <h2 class="text-2xl font-black text-[#F1EEFA]">Open Campaign Briefs</h2>
              <p class="text-xs text-[#C4BCE3] mt-0.5">Pitch your AI video capabilities directly to verified brands and advertising agencies</p>
            </div>
            <span class="rounded-full border border-cyan-500/30 bg-cyan-950/40 px-3.5 py-1 text-xs font-bold text-cyan-300">
              <?= count($open_briefs) ?> Live Briefs
            </span>
          </div>

          <?php if (empty($open_briefs)): ?>
            <div class="rounded-2xl border border-[#26243E] bg-[#16172B] p-6 text-center text-[#A79DCB]">
              <p class="text-sm">No active brand briefs right now. Please check back shortly!</p>
            </div>
          <?php else: ?>
            <div class="grid gap-5 md:grid-cols-2">
              <?php foreach ($open_briefs as $ob): ?>
                <?php
                  // Check if creator already submitted to this brief
                  $already_applied = false;
                  foreach ($my_applications as $ma) {
                      if ($ma['brief_id'] == $ob['id']) {
                          $already_applied = true;
                          break;
                      }
                  }
                ?>
                <div class="rounded-2xl border border-[#26243E] bg-[#16172B] p-5 flex flex-col justify-between hover:border-[#00F5FF]/50 transition shadow-sm">
                  <div>
                    <div class="flex items-center justify-between gap-2 mb-2.5">
                      <div class="flex items-center gap-2.5">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-[#06b6d4] to-[#8b5cf6] flex items-center justify-center text-xs font-bold text-white shadow">
                          🏢
                        </div>
                        <div>
                          <div class="text-xs font-bold text-[#F1EEFA]"><?= htmlspecialchars($ob['brand_name']) ?></div>
                          <div class="text-[0.68rem] text-[#A79DCB]">Brand Brief #<?= $ob['id'] ?></div>
                        </div>
                      </div>

                      <span class="rounded-full bg-emerald-950/60 text-emerald-300 border border-emerald-500/30 text-[0.68rem] font-bold px-2.5 py-0.5">
                        ● <?= htmlspecialchars($ob['status']) ?>
                      </span>
                    </div>

                    <h3 class="text-base font-bold text-[#F1EEFA] leading-snug mb-2">
                      <?= htmlspecialchars($ob['campaign_name']) ?>
                    </h3>

                    <p class="text-xs text-[#C4BCE3] line-clamp-3 leading-relaxed mb-4">
                      <?= htmlspecialchars($ob['description']) ?>
                    </p>

                    <div class="flex flex-wrap gap-2 text-[0.7rem] pt-3 border-t border-[#26243E]/60 text-[#C4BCE3]">
                      <span class="rounded-full bg-[#121324] border border-[#26243E] px-2.5 py-1">📐 <b><?= htmlspecialchars($ob['format']) ?></b></span>
                      <span class="rounded-full bg-[#121324] border border-[#26243E] px-2.5 py-1">🎬 <?= htmlspecialchars($ob['content_type']) ?></span>
                      <span class="rounded-full bg-[#121324] border border-[#26243E] px-2.5 py-1 text-[#00F5FF] font-bold">💰 <?= htmlspecialchars($ob['budget']) ?></span>
                      <span class="rounded-full bg-[#121324] border border-[#26243E] px-2.5 py-1">⏱️ <?= htmlspecialchars($ob['deadline'] ?? '7–10 days') ?></span>
                    </div>
                  </div>

                  <div class="mt-5 pt-3 border-t border-[#26243E] flex items-center justify-between">
                    <?php if ($already_applied): ?>
                      <span class="rounded-full bg-purple-950/60 text-purple-300 border border-purple-500/30 px-3.5 py-1.5 text-xs font-bold">
                        ✓ Proposal Submitted
                      </span>
                    <?php else: ?>
                      <button
                        type="button"
                        onclick="openApplyModal(<?= $ob['id'] ?>, '<?= htmlspecialchars(addslashes($ob['campaign_name'])) ?>', '<?= htmlspecialchars(addslashes($ob['budget'])) ?>')"
                        class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-5 py-1.5 text-xs font-bold text-white shadow-sm hover:brightness-110 transition cursor-pointer"
                      >
                        Submit Proposal →
                      </button>
                    <?php endif; ?>

                    <?php if (!empty($ob['reference_url'])): ?>
                      <a href="<?= htmlspecialchars($ob['reference_url']) ?>" target="_blank" class="text-xs text-cyan-400 hover:underline">
                        Moodboard Link
                      </a>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </section>

        <!-- Recent Uploaded 30s Video Ads & Projects -->
        <section class="mt-8">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-[#F1EEFA]">My Video Ads &amp; Portfolio</h2>
            <a href="creator_portfolio_add.php" class="text-sm text-[#00F5FF] hover:underline font-semibold">
              + Upload 30s Video
            </a>
          </div>

          <?php if (empty($portfolios)): ?>
            <div class="rounded-[26px] border border-[#26243E] bg-[#121324] p-8 text-center text-[#A79DCB] shadow-sm">
              <p class="text-base font-semibold text-[#F1EEFA]">No portfolio projects uploaded yet.</p>
              <p class="mt-1 text-sm text-[#C4BCE3]">Upload a 30-second advertisement video to showcase your creativity to brands!</p>
              <a href="creator_portfolio_add.php" class="mt-4 inline-block rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:brightness-110">
                Upload 30s Ad Now
              </a>
            </div>
          <?php else: ?>
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
              <?php foreach (array_slice($portfolios, 0, 3) as $proj): ?>
                <div class="overflow-hidden rounded-[24px] border border-[#26243E] bg-[#121324] shadow-sm hover:border-[#00F5FF]/50 hover:shadow-[0_0_20px_rgba(0,245,255,0.15)] transition">
                  <?php if (!empty($proj['video_url'])): ?>
                    <div class="relative bg-black h-44">
                      <video controls preload="metadata" class="w-full h-full object-cover">
                        <source src="<?= htmlspecialchars($proj['video_url']) ?>" type="video/mp4">
                        Your browser does not support video.
                      </video>
                      <?php if (!empty($proj['is_ad_video']) || $proj['video_duration'] <= 30): ?>
                        <div class="absolute top-2 right-2 rounded-full bg-rose-600/90 backdrop-blur-sm px-2.5 py-0.5 text-[0.65rem] font-bold text-white shadow">
                          ⚡ 30s Ad
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php else: ?>
                    <img src="<?= htmlspecialchars($proj['preview_url']) ?>" alt="<?= htmlspecialchars($proj['title']) ?>" class="h-44 w-full object-cover">
                  <?php endif; ?>

                  <div class="p-4">
                    <div class="flex items-center justify-between gap-2">
                      <h3 class="text-base font-bold text-[#F1EEFA] truncate"><?= htmlspecialchars($proj['title']) ?></h3>
                      <a href="creator_portfolio_add.php?edit=<?= $proj['id'] ?>" class="text-[0.68rem] font-bold text-[#00F5FF] hover:underline flex-shrink-0">
                        ✏️ Edit
                      </a>
                    </div>
                    <p class="mt-1 text-xs text-[#A79DCB] truncate"><?= htmlspecialchars($proj['description'] ?? '') ?></p>
                    <div class="mt-3 flex items-center justify-between text-xs text-[#C4BCE3]">
                      <span class="rounded-full bg-[#16172B] border border-[#26243E] px-2.5 py-0.5"><?= htmlspecialchars($proj['content_type']) ?></span>
                      <?php if ($proj['video_duration'] > 0): ?>
                        <span class="text-amber-400 font-bold"><?= $proj['video_duration'] ?>s duration</span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </section>

      </main>
    </div>
  </div>

  <!-- Proposal Application Modal -->
  <div id="applyModal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-[28px] border border-[#26243E] bg-[#121324] p-6 shadow-2xl">
      <div class="flex items-center justify-between pb-4 border-b border-[#26243E]">
        <div>
          <h3 class="text-lg font-bold text-[#F1EEFA]">Submit Proposal to Brand</h3>
          <p id="modalCampaignName" class="text-xs text-[#00F5FF] font-semibold mt-0.5"></p>
        </div>
        <button type="button" onclick="closeApplyModal()" class="text-[#A79DCB] hover:text-white text-lg">✕</button>
      </div>

      <form method="POST" action="creator_dashboard.php" class="mt-4 space-y-4">
        <input type="hidden" id="modalBriefId" name="apply_brief_id" value="">

        <div>
          <label class="mb-1.5 block text-xs font-semibold text-[#C4BCE3]">Proposed Price / Fee (INR) *</label>
          <input
            type="text"
            id="modalProposedRate"
            name="proposed_rate"
            required
            placeholder="e.g. ₹35,000"
            class="w-full rounded-xl border border-[#26243E] bg-[#16172B] px-4 py-2.5 text-sm text-[#F1EEFA] focus:border-[#00F5FF] focus:outline-none"
          />
        </div>

        <div>
          <label class="mb-1.5 block text-xs font-semibold text-[#C4BCE3]">Your Pitch &amp; Creative Approach *</label>
          <textarea
            name="pitch"
            rows="4"
            required
            placeholder="Explain how you will produce the 30-second ad video, the AI models you will use (Runway, Kling, etc.), and delivery timeline..."
            class="w-full rounded-xl border border-[#26243E] bg-[#16172B] px-4 py-2.5 text-sm text-[#F1EEFA] placeholder:text-[#766E99] focus:border-[#00F5FF] focus:outline-none"
          ></textarea>
        </div>

        <div class="pt-2 flex justify-end gap-3">
          <button
            type="button"
            onclick="closeApplyModal()"
            class="rounded-full border border-[#26243E] bg-[#16172B] px-5 py-2 text-xs font-semibold text-[#C4BCE3] hover:text-white"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-6 py-2 text-xs font-bold text-white shadow-md hover:brightness-110"
          >
            Send Proposal 🚀
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openApplyModal(briefId, campaignName, budget) {
      document.getElementById('modalBriefId').value = briefId;
      document.getElementById('modalCampaignName').innerText = campaignName + ' • ' + budget;
      document.getElementById('modalProposedRate').value = budget.includes('–') ? budget.split('–')[0].trim() : budget;
      document.getElementById('applyModal').classList.remove('hidden');
    }
    function closeApplyModal() {
      document.getElementById('applyModal').classList.add('hidden');
    }
  </script>

  <!-- Floating AI Assistant Chatbot -->
  <?php require_once __DIR__ . '/includes/chatbot.php'; ?>

</body>
</html>
