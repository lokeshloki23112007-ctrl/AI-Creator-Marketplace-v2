<?php
// creator_view.php - Public Creator Profile & 30s Ad Portfolio View
require_once __DIR__ . '/config/db.php';
$logged_user = get_logged_in_user();

$creator_id = (int)($_GET['id'] ?? 1);
$db = get_db();

// Fetch Creator Profile
$stmt = $db->prepare("
    SELECT c.*, u.full_name, u.avatar_url, u.email, u.website 
    FROM creator_profiles c 
    JOIN users u ON c.user_id = u.id 
    WHERE c.id = :id 
    LIMIT 1
");
$stmt->execute(['id' => $creator_id]);
$creator = $stmt->fetch();

if (!$creator) {
    header("Location: brand_creators.php");
    exit;
}

// Fetch Creator's Portfolios from MySQL
$p_stmt = $db->prepare("SELECT * FROM portfolio_projects WHERE creator_id = :cid ORDER BY created_at DESC");
$p_stmt->execute(['cid' => $creator['id']]);
$portfolios = $p_stmt->fetchAll();

$skills = array_filter(array_map('trim', explode(',', $creator['skills'] ?? '')));
$tools = array_filter(array_map('trim', explode(',', $creator['tools'] ?? '')));
$c_avatar = !empty($creator['avatar_url']) ? $creator['avatar_url'] : 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=500&q=80';

// Handle Direct Proposal / Application from brand
$contact_success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $logged_user && $logged_user['role'] === 'brand') {
    $brief_id = (int)($_POST['brief_id'] ?? 0);
    $pitch = trim($_POST['message'] ?? 'Brand is interested in hiring you for a 30s ad campaign.');
    
    if ($brief_id > 0) {
        $ins_app = $db->prepare("INSERT INTO applications (brief_id, creator_id, pitch, proposed_rate, status) VALUES (:bid, :cid, :pitch, :rate, 'Accepted')");
        $ins_app->execute([
            'bid' => $brief_id,
            'cid' => $creator['id'],
            'pitch' => $pitch,
            'rate' => $creator['hourly_rate']
        ]);
        $contact_success = "Campaign proposal sent successfully to {$creator['full_name']}!";
    }
}

// If logged in as brand, get briefs to offer
$brand_briefs = [];
if ($logged_user && $logged_user['role'] === 'brand') {
    $bb_stmt = $db->prepare("SELECT id, campaign_name FROM briefs WHERE brand_id = :bid AND status = 'Active'");
    $bb_stmt->execute(['bid' => $logged_user['id']]);
    $brand_briefs = $bb_stmt->fetchAll();
}

$page_title = htmlspecialchars($creator['full_name']) . " - Profile";
require_once __DIR__ . '/includes/header.php';
?>

<main class="min-h-screen bg-[#0A0B12] px-4 py-8 sm:px-6 lg:px-8 text-[#F1EEFA]">
  <div class="mx-auto max-w-7xl">
    
    <div class="mb-6 flex items-center justify-between">
      <a href="brand_creators.php" class="text-sm font-semibold text-[#A79DCB] hover:text-[#00F5FF] flex items-center gap-2">
        <span>←</span>
        <span>Back to Creators Directory</span>
      </a>
    </div>

    <?php if (!empty($contact_success)): ?>
      <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-950/40 p-4 text-sm text-emerald-300">
        ✓ <?= htmlspecialchars($contact_success) ?>
      </div>
    <?php endif; ?>

    <!-- Creator Profile Hero Card -->
    <div class="overflow-hidden rounded-[30px] border border-[#26243E] bg-[#121324] p-8 shadow-[0_20px_60px_rgba(0,0,0,0.8)]">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 pb-8 border-b border-[#26243E]">
        <div class="flex items-center gap-6">
          <img src="<?= htmlspecialchars($c_avatar) ?>" alt="<?= htmlspecialchars($creator['full_name']) ?>" class="h-24 w-24 rounded-full object-cover ring-4 ring-purple-500/40 shadow-md">
          <div>
            <div class="flex items-center gap-3">
              <h1 class="text-3xl font-black text-[#F1EEFA]"><?= htmlspecialchars($creator['full_name']) ?></h1>
              <?php if ($creator['verified']): ?>
                <span class="rounded-full bg-cyan-950/60 text-[#00F5FF] px-2.5 py-0.5 text-xs font-bold border border-cyan-400/40">✓ Verified</span>
              <?php endif; ?>
            </div>
            <p class="text-base text-cyan-300 mt-1 font-semibold"><?= htmlspecialchars($creator['specialization'] ?: $creator['role_title']) ?></p>
            
            <div class="mt-2.5 flex flex-wrap items-center gap-3 text-xs text-[#C4BCE3]">
              <!-- Availability badge with colored dot -->
              <?php 
                $avail = $creator['availability'] ?? 'Available Now';
                $dot_color = 'bg-emerald-400';
                if (stripos($avail, 'This Week') !== false) $dot_color = 'bg-amber-400';
                elseif (stripos($avail, 'Booked') !== false) $dot_color = 'bg-slate-500';
              ?>
              <span class="inline-flex items-center gap-1.5 rounded-full border border-[#26243E] bg-[#16172B] px-2.5 py-0.5 font-medium">
                <span class="h-2 w-2 rounded-full <?= $dot_color ?> animate-pulse"></span>
                <?= htmlspecialchars($avail) ?>
              </span>

              <span class="rounded-full border border-cyan-500/30 bg-cyan-950/40 px-2.5 py-0.5 text-cyan-300 font-semibold">
                Level: <?= htmlspecialchars($creator['experience_level'] ?? 'Expert') ?>
              </span>

              <span class="text-amber-400 font-bold">★ <?= htmlspecialchars($creator['rating']) ?> (<?= (int)$creator['reviews_count'] ?> reviews)</span>
              
              <span class="text-emerald-400 font-bold"><?= htmlspecialchars($creator['hourly_rate']) ?></span>
              <span class="text-[#A79DCB] text-[0.7rem]">(Tier: <?= htmlspecialchars($creator['budget_tier'] ?? '₹25,000–₹50,000') ?>)</span>
              
              <span>•</span>
              <span class="text-[#A79DCB]"><?= htmlspecialchars($creator['location']) ?></span>
            </div>
          </div>
        </div>

        <!-- Hire / Contact Button -->
        <div class="flex gap-3">
          <?php if ($logged_user && $logged_user['role'] === 'brand'): ?>
            <button
              type="button"
              onclick="document.getElementById('hire-modal').classList.remove('hidden')"
              class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-6 py-3 text-sm font-semibold text-white shadow-[0_0_18px_rgba(6,182,212,0.35)] hover:brightness-110 cursor-pointer"
            >
              💼 Hire for 30s Ad Campaign
            </button>
          <?php elseif (!$logged_user): ?>
            <a
              href="login.php?role=brand"
              class="rounded-full bg-gradient-to-r from-[#06b6d4] to-[#8b5cf6] px-6 py-3 text-sm font-semibold text-white shadow-md hover:brightness-110"
            >
              Login as Brand to Hire
            </a>
          <?php endif; ?>
        </div>
      </div>

      <!-- Bio & Attributes Grid -->
      <div class="mt-8 grid gap-8 md:grid-cols-3">
        <div class="md:col-span-2 space-y-6">
          <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#A79DCB] mb-2">About the Creator</h3>
            <p class="text-sm leading-relaxed text-[#C4BCE3]">
              <?= nl2br(htmlspecialchars($creator['bio'] ?: 'Passionate AI content creator delivering cinematic 30-second video advertisements, 3D animations, and social-first creative campaigns.')) ?>
            </p>
          </div>

          <!-- Formats & Content Types -->
          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <h3 class="text-xs font-bold uppercase tracking-wider text-amber-400 mb-2">Supported Aspect Ratios</h3>
              <div class="flex flex-wrap gap-1.5">
                <?php 
                  $formats = array_filter(array_map('trim', explode(',', $creator['aspect_ratios'] ?? '16:9, 9:16')));
                  foreach ($formats as $fmt): 
                ?>
                  <span class="rounded-lg border border-amber-500/30 bg-amber-950/40 px-2.5 py-1 text-xs font-bold text-amber-300">
                    📐 <?= htmlspecialchars($fmt) ?>
                  </span>
                <?php endforeach; ?>
              </div>
            </div>

            <div>
              <h3 class="text-xs font-bold uppercase tracking-wider text-pink-400 mb-2">Content Types</h3>
              <div class="flex flex-wrap gap-1.5">
                <?php 
                  $c_types = array_filter(array_map('trim', explode(',', $creator['content_types'] ?? 'Video, Advertisement')));
                  foreach ($c_types as $ct): 
                ?>
                  <span class="rounded-lg border border-pink-500/30 bg-pink-950/40 px-2.5 py-1 text-xs font-bold text-pink-300">
                    🎞️ <?= htmlspecialchars($ct) ?>
                  </span>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- Commercial Rights & Verifications -->
          <div class="space-y-3 pt-2">
            <div>
              <h3 class="text-xs font-bold uppercase tracking-wider text-violet-400 mb-2">Commercial Rights &amp; Licensing</h3>
              <div class="flex flex-wrap gap-1.5">
                <?php 
                  $c_rights = array_filter(array_map('trim', explode(',', $creator['commercial_rights'] ?? 'Commercial Use, Paid Advertising')));
                  foreach ($c_rights as $cr): 
                ?>
                  <span class="rounded-full border border-violet-500/30 bg-violet-950/40 px-2.5 py-1 text-xs text-violet-300 font-medium">
                    ⚖️ <?= htmlspecialchars($cr) ?>
                  </span>
                <?php endforeach; ?>
              </div>
            </div>

            <div>
              <h3 class="text-xs font-bold uppercase tracking-wider text-blue-400 mb-2">Verification Badges</h3>
              <div class="flex flex-wrap gap-1.5">
                <?php 
                  $v_tags = array_filter(array_map('trim', explode(',', $creator['verification_tags'] ?? 'Tools Verified, Portfolio Verified, Commercial Ready')));
                  foreach ($v_tags as $vt): 
                ?>
                  <span class="rounded-full border border-blue-500/30 bg-blue-950/40 px-2.5 py-1 text-xs text-blue-300 font-medium">
                    🛡️ <?= htmlspecialchars($vt) ?>
                  </span>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

        </div>

        <!-- Sidebar Tools & Skills -->
        <div class="space-y-6 md:border-l md:border-[#26243E] md:pl-8">
          <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-cyan-400 mb-2">Core Skills</h3>
            <div class="flex flex-wrap gap-1.5">
              <?php foreach ($skills as $s): ?>
                <span class="rounded-full border border-[#26243E] bg-[#16172B] px-2.5 py-1 text-xs text-[#C4BCE3] font-medium">
                  <?= htmlspecialchars($s) ?>
                </span>
              <?php endforeach; ?>
            </div>
          </div>

          <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-400 mb-2">AI Tools Used</h3>
            <div class="flex flex-wrap gap-1.5">
              <?php foreach ($tools as $t): ?>
                <span class="rounded-full border border-cyan-500/30 bg-cyan-950/40 px-2.5 py-1 text-xs text-cyan-300 font-medium">
                  ⚡ <?= htmlspecialchars($t) ?>
                </span>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Portfolio & 30s Ad Showcase -->
    <div class="mt-12">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-2xl font-black text-[#F1EEFA]">30s Advertisement Video Demos &amp; Portfolio</h2>
          <p class="text-sm text-[#A79DCB]">Review commercial work, pacing, and style produced with AI tools.</p>
        </div>
        <span class="rounded-full border border-[#26243E] bg-[#121324] px-3 py-1 text-xs text-[#C4BCE3] font-semibold shadow-sm">
          <?= count($portfolios) ?> Projects
        </span>
      </div>

      <?php if (empty($portfolios)): ?>
        <div class="rounded-[24px] border border-[#26243E] bg-[#121324] p-8 text-center text-[#A79DCB] shadow-sm">
          <p class="text-sm">This creator has not uploaded any portfolio projects yet.</p>
        </div>
      <?php else: ?>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <?php foreach ($portfolios as $proj): ?>
            <article class="overflow-hidden rounded-[24px] border border-[#26243E] bg-[#121324] shadow-sm hover:border-[#00F5FF]/50 hover:shadow-[0_0_20px_rgba(0,245,255,0.15)] transition flex flex-col justify-between">
              <div>
                <!-- Video Player or Image -->
                <?php if (!empty($proj['video_url'])): ?>
                  <div class="relative bg-black h-52">
                    <video controls preload="metadata" class="w-full h-full object-cover">
                      <source src="<?= htmlspecialchars($proj['video_url']) ?>" type="video/mp4">
                    </video>
                    <div class="absolute top-2 right-2 rounded-full bg-rose-600/90 backdrop-blur-sm px-2.5 py-0.5 text-[0.65rem] font-bold text-white shadow">
                      ⚡ 30s Ad Video
                    </div>
                  </div>
                <?php else: ?>
                  <img src="<?= htmlspecialchars($proj['preview_url']) ?>" alt="<?= htmlspecialchars($proj['title']) ?>" class="h-52 w-full object-cover" />
                <?php endif; ?>

                <div class="p-5">
                  <div class="flex items-center justify-between text-xs text-[#A79DCB] mb-1">
                    <span class="text-cyan-300 font-semibold"><?= htmlspecialchars($proj['content_type']) ?></span>
                    <?php if ($proj['video_duration'] > 0): ?>
                      <span class="text-amber-400 font-bold"><?= $proj['video_duration'] ?>s ad</span>
                    <?php endif; ?>
                  </div>

                  <h3 class="text-lg font-bold text-[#F1EEFA]"><?= htmlspecialchars($proj['title']) ?></h3>
                  <?php if (!empty($proj['description'])): ?>
                    <p class="mt-2 text-xs text-[#C4BCE3] leading-relaxed"><?= htmlspecialchars($proj['description']) ?></p>
                  <?php endif; ?>

                  <div class="mt-4 flex flex-wrap gap-1.5">
                    <?php foreach (array_filter(array_map('trim', explode(',', $proj['tools'] ?? ''))) as $tool): ?>
                      <span class="rounded-full border border-cyan-500/30 bg-cyan-950/40 px-2 py-0.5 text-[0.65rem] font-medium text-cyan-300">
                        <?= htmlspecialchars($tool) ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>
</main>

<!-- Hire Proposal Modal for Brands -->
<?php if ($logged_user && $logged_user['role'] === 'brand'): ?>
  <div id="hire-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="max-w-md w-full rounded-[28px] border border-[#26243E] bg-[#121324] p-6 shadow-2xl text-[#F1EEFA]">
      <div class="flex items-center justify-between mb-4 border-b border-[#26243E] pb-3">
        <h3 class="text-lg font-bold text-[#F1EEFA]">Hire <?= htmlspecialchars($creator['full_name']) ?></h3>
        <button onclick="document.getElementById('hire-modal').classList.add('hidden')" class="text-[#A79DCB] hover:text-[#00F5FF]">✕</button>
      </div>

      <form method="POST" action="creator_view.php?id=<?= $creator['id'] ?>" class="space-y-4">
        <div>
          <label class="block text-xs font-semibold text-[#C4BCE3] mb-1">Select Campaign Brief</label>
          <select name="brief_id" required class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] p-3 text-sm text-[#F1EEFA] focus:outline-none focus:border-cyan-400">
            <?php if (empty($brand_briefs)): ?>
              <option value="" class="bg-[#16172B]">No active briefs found. (Create one first)</option>
            <?php else: ?>
              <?php foreach ($brand_briefs as $bb): ?>
                <option value="<?= $bb['id'] ?>" class="bg-[#16172B]"><?= htmlspecialchars($bb['campaign_name']) ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <div>
          <label class="block text-xs font-semibold text-[#C4BCE3] mb-1">Invitation Message / Details</label>
          <textarea name="message" rows="3" class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] p-3 text-sm text-[#F1EEFA] placeholder:text-[#7A7593] focus:outline-none focus:border-cyan-400" placeholder="We loved your 30s video ads and want to hire you for our campaign!"></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-2">
          <button type="button" onclick="document.getElementById('hire-modal').classList.add('hidden')" class="rounded-full border border-[#26243E] bg-[#16172B] px-4 py-2 text-xs font-semibold text-[#C4BCE3] hover:text-[#F1EEFA]">Cancel</button>
          <button type="submit" class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-5 py-2 text-xs font-semibold text-white shadow-sm hover:brightness-110">Send Proposal</button>
        </div>
      </form>
    </div>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
