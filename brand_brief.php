<?php
// brand_brief.php - Add, Update & Manage Campaign Briefs with 30s Ad Validation
require_once __DIR__ . '/config/db.php';
$brand = require_login('brand');

$db = get_db();
$error = '';
$success = '';

$content_options = ['Video', 'Advertisement', 'Image', 'Animation', 'Social Media', '3D / CGI'];
$style_options = ['Cinematic', 'Natural', 'Luxury', 'Minimal', 'Emotional', 'Commercial', 'Futuristic / Cyberpunk'];
$platform_options = ['Instagram Reels', 'TikTok', 'YouTube Shorts', 'Meta Ads', 'Website / E-Commerce'];
$format_options = ['9:16', '16:9', '1:1', '4:5'];
$commercial_options = ['Paid Advertising + Commercial Rights', 'Commercial Rights Only', 'Social Media Buyout', 'Personal / Non-Commercial'];
$status_options = ['Active', 'In Review', 'Completed', 'Draft'];

// --- 1. HANDLE DELETE ACTION ---
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $chk = $db->prepare("SELECT id, campaign_name FROM briefs WHERE id = :id AND brand_id = :bid");
    $chk->execute(['id' => $del_id, 'bid' => $brand['id']]);
    $to_del = $chk->fetch();

    if ($to_del) {
        $del_stmt = $db->prepare("DELETE FROM briefs WHERE id = :id AND brand_id = :bid");
        $del_stmt->execute(['id' => $del_id, 'bid' => $brand['id']]);
        set_flash('success', "Campaign brief '" . htmlspecialchars($to_del['campaign_name']) . "' has been deleted.");
    }
    header("Location: brand_brief.php");
    exit;
}

// --- 2. HANDLE STATUS TOGGLE ACTION ---
if (isset($_GET['toggle_status']) && isset($_GET['new_status'])) {
    $brief_id = (int)$_GET['toggle_status'];
    $new_status = in_array($_GET['new_status'], $status_options) ? $_GET['new_status'] : 'Active';
    $up_stmt = $db->prepare("UPDATE briefs SET status = :st WHERE id = :id AND brand_id = :bid");
    $up_stmt->execute(['st' => $new_status, 'id' => $brief_id, 'bid' => $brand['id']]);
    set_flash('success', "Brief status updated to '{$new_status}'.");
    header("Location: brand_brief.php");
    exit;
}

// --- 3. CHECK EDIT MODE ---
$edit_mode = false;
$edit_brief = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $e_stmt = $db->prepare("SELECT * FROM briefs WHERE id = :id AND brand_id = :bid");
    $e_stmt->execute(['id' => $edit_id, 'bid' => $brand['id']]);
    $edit_brief = $e_stmt->fetch();
    if ($edit_brief) {
        $edit_mode = true;
    }
}

// --- 4. HANDLE ADD & UPDATE FORM SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update_id = isset($_POST['update_brief_id']) ? (int)$_POST['update_brief_id'] : null;
    $campaign_name = trim($_POST['campaignName'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $content_type = trim($_POST['contentType'] ?? 'Video');
    $style = trim($_POST['style'] ?? 'Cinematic');
    $platform = trim($_POST['platform'] ?? 'Instagram Reels');
    $format = trim($_POST['format'] ?? '9:16');
    $commercial_use = trim($_POST['commercialUse'] ?? 'Paid Advertising + Commercial Rights');
    $budget = trim($_POST['budget'] ?? '₹25,000–₹50,000');
    $deadline = trim($_POST['deadline'] ?? '7–10 days');
    $reference = trim($_POST['reference'] ?? '');
    $status = trim($_POST['status'] ?? 'Active');
    $ad_video_path = null;

    if (empty($campaign_name) || empty($description)) {
        $error = 'Campaign name and ad objective / requirements are required.';
    } else {
        // Optional 30s ad reference video file upload
        if (isset($_FILES['reference_video']) && $_FILES['reference_video']['error'] !== UPLOAD_ERR_NO_FILE) {
            $vid_res = handle_file_upload(
                $_FILES['reference_video'],
                'uploads/videos',
                ['mp4', 'webm', 'mov'],
                50 * 1024 * 1024
            );
            if ($vid_res['success']) {
                $ad_video_path = $vid_res['path'];
            }
        }

        if ($update_id) {
            // UPDATE EXISTING BRIEF
            $chk = $db->prepare("SELECT id, ad_video_url FROM briefs WHERE id = :id AND brand_id = :bid");
            $chk->execute(['id' => $update_id, 'bid' => $brand['id']]);
            $existing = $chk->fetch();

            if ($existing) {
                $final_vid = $ad_video_path ? $ad_video_path : $existing['ad_video_url'];
                $up_sql = "
                    UPDATE briefs SET
                        campaign_name = :cname,
                        description = :descr,
                        content_type = :ctype,
                        style = :style,
                        platform = :plat,
                        format = :fmt,
                        commercial_use = :comm,
                        budget = :budget,
                        deadline = :deadline,
                        reference_url = :ref,
                        ad_video_url = :ad_vid,
                        status = :status
                    WHERE id = :id AND brand_id = :bid
                ";
                $up_stmt = $db->prepare($up_sql);
                $up_stmt->execute([
                    'cname' => $campaign_name,
                    'descr' => $description,
                    'ctype' => $content_type,
                    'style' => $style,
                    'plat' => $platform,
                    'fmt' => $format,
                    'comm' => $commercial_use,
                    'budget' => $budget,
                    'deadline' => $deadline,
                    'ref' => $reference,
                    'ad_vid' => $final_vid,
                    'status' => $status,
                    'id' => $update_id,
                    'bid' => $brand['id']
                ]);

                set_flash('success', "Campaign brief '{$campaign_name}' updated successfully! ✨");
                header("Location: brand_brief.php#manage-briefs");
                exit;
            } else {
                $error = "Unauthorized brief update attempt.";
            }
        } else {
            // INSERT NEW BRIEF
            $ins = $db->prepare("
                INSERT INTO briefs 
                (brand_id, campaign_name, description, content_type, style, platform, format, commercial_use, budget, deadline, reference_url, ad_video_url, status)
                VALUES (:bid, :cname, :descr, :ctype, :style, :plat, :fmt, :comm, :budget, :deadline, :ref, :ad_vid, :status)
            ");
            $ins->execute([
                'bid' => $brand['id'],
                'cname' => $campaign_name,
                'descr' => $description,
                'ctype' => $content_type,
                'style' => $style,
                'plat' => $platform,
                'fmt' => $format,
                'comm' => $commercial_use,
                'budget' => $budget,
                'deadline' => $deadline,
                'ref' => $reference,
                'ad_vid' => $ad_video_path,
                'status' => $status,
            ]);

            set_flash('success', "Campaign brief '{$campaign_name}' created and published live!");
            header("Location: brand_brief.php#manage-briefs");
            exit;
        }
    }
}

// Fetch all briefs owned by this brand with applicants count
$my_briefs_stmt = $db->prepare("
    SELECT b.*, 
           COUNT(a.id) as applicants_count
    FROM briefs b
    LEFT JOIN applications a ON b.id = a.brief_id
    WHERE b.brand_id = :bid
    GROUP BY b.id
    ORDER BY b.created_at DESC
");
$my_briefs_stmt->execute(['bid' => $brand['id']]);
$my_briefs = $my_briefs_stmt->fetchAll();

// Pre-fill values depending on edit mode or post
$val_name = $edit_mode ? $edit_brief['campaign_name'] : ($_POST['campaignName'] ?? '');
$val_desc = $edit_mode ? $edit_brief['description'] : ($_POST['description'] ?? '');
$val_ctype = $edit_mode ? $edit_brief['content_type'] : ($_POST['contentType'] ?? 'Video');
$val_style = $edit_mode ? $edit_brief['style'] : ($_POST['style'] ?? 'Cinematic');
$val_plat = $edit_mode ? $edit_brief['platform'] : ($_POST['platform'] ?? 'Instagram Reels');
$val_fmt = $edit_mode ? $edit_brief['format'] : ($_POST['format'] ?? '9:16');
$val_comm = $edit_mode ? $edit_brief['commercial_use'] : ($_POST['commercialUse'] ?? 'Paid Advertising + Commercial Rights');
$val_budget = $edit_mode ? $edit_brief['budget'] : ($_POST['budget'] ?? '₹25,000–₹50,000');
$val_deadline = $edit_mode ? $edit_brief['deadline'] : ($_POST['deadline'] ?? '7–10 days');
$val_ref = $edit_mode ? $edit_brief['reference_url'] : ($_POST['reference'] ?? '');
$val_status = $edit_mode ? $edit_brief['status'] : ($_POST['status'] ?? 'Active');
$val_ad_vid = $edit_mode ? $edit_brief['ad_video_url'] : null;

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $edit_mode ? 'Update Campaign Brief' : 'Add Campaign Brief' ?> - Brand Portal</title>
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

      <div class="flex items-center gap-3">
        <?php if (!empty($brand['avatar_url'])): ?>
          <img src="<?= htmlspecialchars($brand['avatar_url']) ?>" alt="Logo" class="h-10 w-10 rounded-full object-cover ring-2 ring-cyan-400 shadow-sm">
        <?php else: ?>
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-sm font-bold text-white shadow-md">
            <?= strtoupper(substr($brand['full_name'], 0, 2)) ?>
          </div>
        <?php endif; ?>
        <div>
          <div class="text-sm font-semibold text-[#F1EEFA]"><?= htmlspecialchars($brand['full_name']) ?></div>
          <div class="text-xs text-[#A79DCB]">Brand Account</div>
        </div>
      </div>
    </header>

    <div class="grid min-h-[760px] lg:grid-cols-[260px_1fr]">
      <!-- Left Sidebar -->
      <?php require_once __DIR__ . '/includes/brand_sidebar.php'; ?>

      <!-- Main Content -->
      <main class="p-6 lg:p-8 bg-[#0A0B12]/60 space-y-8">
        
        <?php if ($flash): ?>
          <div class="rounded-2xl border border-cyan-500/30 bg-cyan-950/40 p-4 text-sm text-cyan-300 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
              <span class="text-lg">✓</span>
              <span class="font-bold text-[#F1EEFA]"><?= htmlspecialchars($flash['message']) ?></span>
            </div>
          </div>
        <?php endif; ?>

        <?php if ($edit_mode): ?>
          <!-- Edit Notice Banner -->
          <div class="rounded-2xl border border-amber-500/30 bg-amber-950/30 p-4 text-sm text-amber-200 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
              <span class="text-lg">✏️</span>
              <div>
                <b>Editing Brief:</b> <?= htmlspecialchars($edit_brief['campaign_name']) ?> (ID #<?= $edit_brief['id'] ?>)
                <p class="text-xs text-amber-300/80 mt-0.5">Modify any fields below and click "Update Campaign Brief" to save changes.</p>
              </div>
            </div>
            <a href="brand_brief.php" class="rounded-full border border-amber-500/40 bg-amber-900/30 px-3 py-1 text-xs font-semibold text-amber-200 hover:bg-amber-900/60">
              ✕ Cancel Edit
            </a>
          </div>
        <?php endif; ?>

        <!-- SECTION 1: ADD / UPDATE BRIEF FORM -->
        <section id="brief-form-section" class="rounded-[28px] border border-[#26243E] bg-[#121324] p-6 lg:p-8 shadow-sm">
          <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <p class="text-xs uppercase tracking-[0.18em] text-[#00F5FF] font-bold">Campaign Brief Creator</p>
              <h1 class="mt-1 text-3xl font-black tracking-[-0.04em] text-[#F1EEFA]">
                <?= $edit_mode ? 'Update Campaign Brief' : 'Post a New Campaign Brief' ?>
              </h1>
              <p class="text-xs text-[#C4BCE3] mt-1">
                Set production guidelines, 30s ad video specifications, budget tiers, and commercial rights.
              </p>
            </div>

            <?php if (!$edit_mode): ?>
              <a
                href="#manage-briefs"
                class="inline-flex items-center gap-1.5 rounded-full border border-[#26243E] bg-[#16172B] px-4 py-2 text-xs font-semibold text-[#00F5FF] hover:border-[#00F5FF]/50 transition"
              >
                <span>↓ View My Briefs (<?= count($my_briefs) ?>)</span>
              </a>
            <?php endif; ?>
          </div>

          <?php if (!empty($error)): ?>
            <div class="mb-6 rounded-2xl border border-rose-500/30 bg-rose-950/40 p-4 text-sm text-rose-300 flex items-center gap-2">
              <span>⚠</span>
              <span><?= htmlspecialchars($error) ?></span>
            </div>
          <?php endif; ?>

          <form method="POST" action="brand_brief.php" enctype="multipart/form-data" class="space-y-6">
            <?php if ($edit_mode): ?>
              <input type="hidden" name="update_brief_id" value="<?= $edit_brief['id'] ?>">
            <?php endif; ?>

            <div class="grid gap-5 md:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Campaign Name *</label>
                <input
                  type="text"
                  name="campaignName"
                  required
                  placeholder="e.g. 30s Cinematic Nature Tourism Ad"
                  value="<?= htmlspecialchars($val_name) ?>"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] placeholder:text-[#766E99] focus:border-[#00F5FF] focus:outline-none transition"
                />
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Content Type *</label>
                <select
                  name="contentType"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] focus:border-[#00F5FF] focus:outline-none transition"
                >
                  <?php foreach ($content_options as $opt): ?>
                    <option value="<?= $opt ?>" <?= $val_ctype === $opt ? 'selected' : '' ?> class="bg-[#121324]"><?= $opt ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div>
              <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Campaign Objective &amp; Requirements *</label>
              <textarea
                name="description"
                rows="4"
                required
                placeholder="Describe your target audience, core visual concept, required scenes (e.g. waterfalls, mountains, product closeups), and 30-second pacing..."
                class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] placeholder:text-[#766E99] focus:border-[#00F5FF] focus:outline-none leading-relaxed transition"
              ><?= htmlspecialchars($val_desc) ?></textarea>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
              <div>
                <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Visual Style</label>
                <select
                  name="style"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] focus:border-[#00F5FF] focus:outline-none transition"
                >
                  <?php foreach ($style_options as $opt): ?>
                    <option value="<?= $opt ?>" <?= $val_style === $opt ? 'selected' : '' ?> class="bg-[#121324]"><?= $opt ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Target Platform</label>
                <select
                  name="platform"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] focus:border-[#00F5FF] focus:outline-none transition"
                >
                  <?php foreach ($platform_options as $opt): ?>
                    <option value="<?= $opt ?>" <?= $val_plat === $opt ? 'selected' : '' ?> class="bg-[#121324]"><?= $opt ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Format / Aspect Ratio</label>
                <select
                  name="format"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] focus:border-[#00F5FF] focus:outline-none transition"
                >
                  <?php foreach ($format_options as $opt): ?>
                    <option value="<?= $opt ?>" <?= $val_fmt === $opt ? 'selected' : '' ?> class="bg-[#121324]"><?= $opt ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
              <div>
                <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Commercial Rights</label>
                <select
                  name="commercialUse"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] focus:border-[#00F5FF] focus:outline-none transition"
                >
                  <?php foreach ($commercial_options as $opt): ?>
                    <option value="<?= $opt ?>" <?= $val_comm === $opt ? 'selected' : '' ?> class="bg-[#121324]"><?= $opt ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Campaign Budget (INR)</label>
                <input
                  type="text"
                  name="budget"
                  value="<?= htmlspecialchars($val_budget) ?>"
                  placeholder="e.g. ₹25,000–₹50,000"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] focus:border-[#00F5FF] focus:outline-none transition"
                />
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Production Timeline</label>
                <input
                  type="text"
                  name="deadline"
                  value="<?= htmlspecialchars($val_deadline) ?>"
                  placeholder="e.g. 7–10 days"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] focus:border-[#00F5FF] focus:outline-none transition"
                />
              </div>
            </div>

            <!-- Campaign Status & Reference Assets -->
            <div class="grid gap-5 md:grid-cols-3">
              <div>
                <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Campaign Status</label>
                <select
                  name="status"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] focus:border-[#00F5FF] focus:outline-none transition"
                >
                  <?php foreach ($status_options as $st): ?>
                    <option value="<?= $st ?>" <?= $val_status === $st ? 'selected' : '' ?> class="bg-[#121324]"><?= $st ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Moodboard / Reference URL</label>
                <input
                  type="url"
                  name="reference"
                  value="<?= htmlspecialchars($val_ref) ?>"
                  placeholder="https://pinterest.com/... or https://figma.com/..."
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] placeholder:text-[#766E99] focus:border-[#00F5FF] focus:outline-none transition"
                />
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-[#C4BCE3]">Optional 30s Ad Reference Video</label>
                <input
                  type="file"
                  name="reference_video"
                  accept="video/mp4,video/webm"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-2.5 text-xs text-[#C4BCE3] file:mr-3 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:bg-gradient-to-r file:from-[#06b6d4] file:to-[#8b5cf6] file:text-white cursor-pointer"
                />
                <?php if ($val_ad_vid): ?>
                  <div class="mt-1 text-[0.68rem] text-cyan-300 truncate">Current video attached: <?= htmlspecialchars($val_ad_vid) ?></div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Submit & Action Buttons -->
            <div class="flex flex-col gap-3 pt-4 sm:flex-row sm:justify-end">
              <?php if ($edit_mode): ?>
                <a
                  href="brand_brief.php"
                  class="rounded-full border border-[#26243E] bg-[#16172B] px-6 py-3 text-sm font-semibold text-[#C4BCE3] transition hover:text-[#F1EEFA] text-center shadow-sm"
                >
                  Cancel Edit
                </a>
                <button
                  type="submit"
                  class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-8 py-3 text-sm font-bold text-white shadow-[0_0_20px_rgba(0,245,255,0.4)] transition hover:brightness-110 cursor-pointer text-center"
                >
                  ✓ Update Campaign Brief
                </button>
              <?php else: ?>
                <a
                  href="brand_dashboard.php"
                  class="rounded-full border border-[#26243E] bg-[#16172B] px-6 py-3 text-sm font-semibold text-[#C4BCE3] transition hover:text-[#F1EEFA] text-center shadow-sm"
                >
                  Cancel
                </a>
                <button
                  type="submit"
                  class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-8 py-3 text-sm font-bold text-white shadow-[0_0_20px_rgba(0,245,255,0.4)] transition hover:brightness-110 cursor-pointer text-center"
                >
                  + Publish Campaign Brief
                </button>
              <?php endif; ?>
            </div>
          </form>
        </section>

        <!-- SECTION 2: MANAGE & UPDATE EXISTING BRIEFS LIST -->
        <section id="manage-briefs" class="rounded-[28px] border border-[#26243E] bg-[#121324] p-6 lg:p-8 shadow-sm">
          <div class="flex items-center justify-between mb-6">
            <div>
              <p class="text-xs uppercase tracking-[0.18em] text-[#00F5FF] font-bold">Brief Management</p>
              <h2 class="text-2xl font-black text-[#F1EEFA]">My Campaign Briefs (<?= count($my_briefs) ?>)</h2>
              <p class="text-xs text-[#C4BCE3] mt-0.5">Edit parameters, track incoming creator proposals, and change campaign status</p>
            </div>
            
            <a
              href="brand_brief.php#brief-form-section"
              class="rounded-full border border-[#00F5FF]/40 bg-[#00F5FF]/10 px-4 py-2 text-xs font-bold text-[#00F5FF] hover:bg-[#00F5FF]/20 transition flex items-center gap-1.5"
            >
              <span>+</span>
              <span>Post New Brief</span>
            </a>
          </div>

          <?php if (empty($my_briefs)): ?>
            <div class="rounded-2xl border border-[#26243E] bg-[#16172B] p-8 text-center text-[#A79DCB]">
              <div class="text-4xl mb-3">📝</div>
              <h3 class="text-base font-bold text-[#F1EEFA]">No Campaign Briefs Created Yet</h3>
              <p class="text-xs text-[#C4BCE3] max-w-md mx-auto mt-1 mb-4">
                Use the form above to post your first 30-second ad video campaign brief. Elite AI video creators will apply with tailored pitches.
              </p>
              <a
                href="#brief-form-section"
                class="inline-block rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-6 py-2.5 text-xs font-bold text-white shadow-md hover:brightness-110"
              >
                Create First Brief Now →
              </a>
            </div>
          <?php else: ?>
            <div class="space-y-4">
              <?php foreach ($my_briefs as $b): ?>
                <?php
                  $status_badge_class = 'bg-emerald-950/60 text-emerald-300 border-emerald-500/30';
                  if ($b['status'] === 'In Review') $status_badge_class = 'bg-sky-950/60 text-sky-300 border-sky-500/30';
                  elseif ($b['status'] === 'Completed') $status_badge_class = 'bg-purple-950/60 text-purple-300 border-purple-500/30';
                  elseif ($b['status'] === 'Draft') $status_badge_class = 'bg-slate-800/60 text-slate-300 border-slate-600/30';
                ?>
                <div class="rounded-2xl border border-[#26243E] bg-[#16172B] p-5 transition hover:border-[#00F5FF]/60 shadow-sm flex flex-col justify-between gap-4">
                  
                  <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                    <div class="space-y-1.5 flex-1">
                      <div class="flex flex-wrap items-center gap-2.5">
                        <h3 class="text-lg font-bold text-[#F1EEFA]"><?= htmlspecialchars($b['campaign_name']) ?></h3>
                        <span class="rounded-full border px-2.5 py-0.5 text-[0.68rem] font-bold <?= $status_badge_class ?>">
                          ● <?= htmlspecialchars($b['status']) ?>
                        </span>
                        <?php if ((int)$b['applicants_count'] > 0): ?>
                          <span class="rounded-full border border-purple-500/40 bg-purple-950/40 px-2.5 py-0.5 text-[0.68rem] font-bold text-purple-300">
                            ★ <?= (int)$b['applicants_count'] ?> Proposal<?= (int)$b['applicants_count'] > 1 ? 's' : '' ?> Received
                          </span>
                        <?php else: ?>
                          <span class="rounded-full border border-[#26243E] bg-[#121324] px-2.5 py-0.5 text-[0.68rem] font-semibold text-[#A79DCB]">
                            0 Proposals
                          </span>
                        <?php endif; ?>
                      </div>

                      <p class="text-xs text-[#C4BCE3] line-clamp-2 leading-relaxed">
                        <?= htmlspecialchars($b['description']) ?>
                      </p>

                      <div class="flex flex-wrap items-center gap-3 pt-2 text-[0.72rem] text-[#A79DCB]">
                        <span><b>Type:</b> <?= htmlspecialchars($b['content_type']) ?></span>
                        <span>•</span>
                        <span><b>Format:</b> <?= htmlspecialchars($b['format']) ?></span>
                        <span>•</span>
                        <span><b>Style:</b> <?= htmlspecialchars($b['style']) ?></span>
                        <span>•</span>
                        <span><b>Platform:</b> <?= htmlspecialchars($b['platform']) ?></span>
                        <span>•</span>
                        <span><b>Budget:</b> <b class="text-[#00F5FF]"><?= htmlspecialchars($b['budget']) ?></b></span>
                        <span>•</span>
                        <span><b>Timeline:</b> <?= htmlspecialchars($b['deadline'] ?? '7–10 days') ?></span>
                      </div>
                    </div>

                    <!-- Right Quick Actions -->
                    <div class="flex flex-wrap items-center gap-2 pt-2 md:pt-0">
                      <!-- Edit Button -->
                      <a
                        href="brand_brief.php?edit=<?= $b['id'] ?>#brief-form-section"
                        class="rounded-full border border-[#00F5FF]/40 bg-[#00F5FF]/10 px-4 py-1.5 text-xs font-bold text-[#00F5FF] hover:bg-[#00F5FF]/25 transition flex items-center gap-1 shadow-sm"
                      >
                        <span>✏️</span>
                        <span>Edit / Update</span>
                      </a>

                      <!-- Status Changer Dropdown/Links -->
                      <?php if ($b['status'] === 'Active'): ?>
                        <a
                          href="brand_brief.php?toggle_status=<?= $b['id'] ?>&new_status=Completed"
                          class="rounded-full border border-purple-500/30 bg-purple-950/30 px-3 py-1.5 text-xs font-semibold text-purple-300 hover:bg-purple-900/50 transition"
                          title="Mark brief as completed"
                        >
                          Mark Done
                        </a>
                      <?php else: ?>
                        <a
                          href="brand_brief.php?toggle_status=<?= $b['id'] ?>&new_status=Active"
                          class="rounded-full border border-emerald-500/30 bg-emerald-950/30 px-3 py-1.5 text-xs font-semibold text-emerald-300 hover:bg-emerald-900/50 transition"
                          title="Activate campaign"
                        >
                          Activate
                        </a>
                      <?php endif; ?>

                      <!-- Search Creators Matching This Brief -->
                      <a
                        href="brand_creators.php?format=<?= urlencode($b['format']) ?>"
                        class="rounded-full border border-[#26243E] bg-[#121324] px-3.5 py-1.5 text-xs font-semibold text-[#F1EEFA] hover:border-[#00F5FF]/50 transition"
                      >
                        Match Creators →
                      </a>

                      <!-- Delete Button -->
                      <a
                        href="brand_brief.php?delete=<?= $b['id'] ?>"
                        onclick="return confirm('Are you sure you want to delete this brief? Any applications linked to it will also be removed.');"
                        class="rounded-full border border-rose-500/30 bg-rose-950/30 px-3 py-1.5 text-xs font-semibold text-rose-300 hover:bg-rose-900/60 transition"
                      >
                        🗑️ Delete
                      </a>
                    </div>
                  </div>

                  <!-- Reference preview if available -->
                  <?php if (!empty($b['ad_video_url']) || !empty($b['reference_url'])): ?>
                    <div class="pt-3 border-t border-[#26243E] flex flex-wrap items-center gap-4 text-xs">
                      <?php if (!empty($b['reference_url'])): ?>
                        <a href="<?= htmlspecialchars($b['reference_url']) ?>" target="_blank" class="text-cyan-400 hover:underline flex items-center gap-1 font-semibold">
                          <span>🔗</span> Reference Moodboard
                        </a>
                      <?php endif; ?>
                      <?php if (!empty($b['ad_video_url'])): ?>
                        <span class="text-[#A79DCB] flex items-center gap-1">
                          <span>🎬</span> 30s Ad Reference: <code class="text-purple-300 font-mono"><?= htmlspecialchars($b['ad_video_url']) ?></code>
                        </span>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>

                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </section>

      </main>
    </div>
  </div>

  <!-- Floating AI Assistant Chatbot -->
  <?php require_once __DIR__ . '/includes/chatbot.php'; ?>

</body>
</html>
