<?php
// creator_portfolio_add.php - Add & Edit Portfolio Project with Image Upload and 30s Ad Video Validation
require_once __DIR__ . '/config/db.php';
$user = require_login('creator');

$db = get_db();
$stmt = $db->prepare("SELECT * FROM creator_profiles WHERE user_id = :uid LIMIT 1");
$stmt->execute(['uid' => $user['id']]);
$profile = $stmt->fetch();

if (!$profile) {
    header("Location: creator_profile.php");
    exit;
}

$error = '';
$edit_mode = false;
$edit_project = null;

// Check if Edit mode is active
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $chk_stmt = $db->prepare("SELECT * FROM portfolio_projects WHERE id = :id AND creator_id = :cid LIMIT 1");
    $chk_stmt->execute(['id' => $edit_id, 'cid' => $profile['id']]);
    $edit_project = $chk_stmt->fetch();
    if ($edit_project) {
        $edit_mode = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update_id = isset($_POST['update_project_id']) ? (int)$_POST['update_project_id'] : null;
    $title = trim($_POST['title'] ?? '');
    $content_type = trim($_POST['contentType'] ?? 'Video');
    $description = trim($_POST['description'] ?? '');
    $tools = trim($_POST['tools'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $video_duration = (int)($_POST['video_duration'] ?? 0);
    $video_url = null;
    $preview_url = null;
    $is_ad_video = 0;

    if (empty($title) || empty($content_type)) {
        $error = 'Project title and content type are required.';
    } else {
        // If editing, retrieve existing project
        $existing = null;
        if ($update_id) {
            $e_stmt = $db->prepare("SELECT * FROM portfolio_projects WHERE id = :id AND creator_id = :cid LIMIT 1");
            $e_stmt->execute(['id' => $update_id, 'cid' => $profile['id']]);
            $existing = $e_stmt->fetch();
            if ($existing) {
                $preview_url = $existing['preview_url'];
                $video_url = $existing['video_url'];
                $is_ad_video = $existing['is_ad_video'];
                if ($video_duration === 0) {
                    $video_duration = (int)$existing['video_duration'];
                }
            }
        }

        // 1. Handle Project Image File Upload (Replaces old Video/Image URL text field)
        if (isset($_FILES['project_image']) && $_FILES['project_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $img_res = handle_file_upload(
                $_FILES['project_image'],
                'uploads/projects',
                ['jpg', 'jpeg', 'png', 'webp'],
                15 * 1024 * 1024 // 15MB limit
            );

            if ($img_res['success']) {
                $preview_url = $img_res['path'];
            } else {
                $error = 'Image Upload Error: ' . $img_res['error'];
            }
        }

        // Fallback default image for new project if none uploaded
        if (empty($preview_url)) {
            $preview_url = 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80';
        }

        // 2. Handle 30-Second Advertisement Video File Upload
        if (isset($_FILES['video_ad']) && $_FILES['video_ad']['error'] !== UPLOAD_ERR_NO_FILE) {
            $video_res = handle_file_upload(
                $_FILES['video_ad'],
                'uploads/videos',
                ['mp4', 'webm', 'mov'],
                50 * 1024 * 1024 // 50MB limit
            );

            if ($video_res['success']) {
                $video_url = $video_res['path'];
                $is_ad_video = 1;
            } else {
                $error = 'Video Upload Error: ' . $video_res['error'];
            }
        }

        if (empty($error)) {
            if ($update_id && $existing) {
                // UPDATE EXISTING PROJECT
                $up = $db->prepare("
                    UPDATE portfolio_projects SET
                        title = :title,
                        content_type = :ctype,
                        description = :descr,
                        tools = :tools,
                        skills = :skills,
                        preview_url = :prev,
                        video_url = :vurl,
                        video_duration = :vduration,
                        is_ad_video = :is_ad
                    WHERE id = :id AND creator_id = :cid
                ");
                $up->execute([
                    'title' => $title,
                    'ctype' => $content_type,
                    'descr' => $description,
                    'tools' => $tools,
                    'skills' => $skills,
                    'prev' => $preview_url,
                    'vurl' => $video_url,
                    'vduration' => $video_duration,
                    'is_ad' => $is_ad_video,
                    'id' => $update_id,
                    'cid' => $profile['id']
                ]);

                set_flash('success', "Project '{$title}' updated successfully! You can view or re-edit it anytime.");
                header("Location: creator_portfolio.php");
                exit;
            } else {
                // INSERT NEW PROJECT
                $ins = $db->prepare("
                    INSERT INTO portfolio_projects 
                    (creator_id, title, content_type, description, tools, skills, preview_url, video_url, video_duration, is_ad_video)
                    VALUES (:cid, :title, :ctype, :descr, :tools, :skills, :prev, :vurl, :vduration, :is_ad)
                ");
                $ins->execute([
                    'cid' => $profile['id'],
                    'title' => $title,
                    'ctype' => $content_type,
                    'descr' => $description,
                    'tools' => $tools,
                    'skills' => $skills,
                    'prev' => $preview_url,
                    'vurl' => $video_url,
                    'vduration' => $video_duration,
                    'is_ad' => $is_ad_video,
                ]);

                // Increment creator's project count
                $db->prepare("UPDATE creator_profiles SET projects_count = projects_count + 1 WHERE id = :cid")
                   ->execute(['cid' => $profile['id']]);

                set_flash('success', "Project '{$title}' added to your portfolio! Edit options are available below.");
                header("Location: creator_portfolio.php");
                exit;
            }
        }
    }
}

// Pre-populate fields
$val_title = $edit_mode ? $edit_project['title'] : ($_POST['title'] ?? '');
$val_ctype = $edit_mode ? $edit_project['content_type'] : ($_POST['contentType'] ?? 'Video');
$val_desc = $edit_mode ? $edit_project['description'] : ($_POST['description'] ?? '');
$val_tools = $edit_mode ? $edit_project['tools'] : ($_POST['tools'] ?? 'Runway, Kling');
$val_skills = $edit_mode ? $edit_project['skills'] : ($_POST['skills'] ?? 'AI Video, Advertisement');
$val_prev = $edit_mode ? $edit_project['preview_url'] : null;
$val_vid = $edit_mode ? $edit_project['video_url'] : null;
$val_vduration = $edit_mode ? (int)$edit_project['video_duration'] : 0;

$avatar = !empty($user['avatar_url']) ? $user['avatar_url'] : ($profile['avatar_url'] ?? null);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $edit_mode ? 'Edit Portfolio Project' : 'Upload Portfolio Project' ?> - AICreators</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body, * { font-family: 'Times New Roman', Times, serif !important; }
    body { background-color: #0A0B12; color: #F1EEFA; }
  </style>
</head>
<body class="min-h-screen bg-[#0A0B12] px-4 py-6 text-[#F1EEFA] sm:px-6 lg:px-8">

  <div class="mx-auto max-w-7xl overflow-hidden rounded-[30px] border border-[#26243E] bg-[#0E0F1B] shadow-[0_20px_60px_rgba(0,0,0,0.8)]">
    <!-- Top Header -->
    <header class="flex items-center justify-between border-b border-[#26243E] bg-[#0E0F1B]/95 px-6 py-4">
      <a href="index.php" class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] shadow-md">
          <span class="text-lg font-black text-white">▶</span>
        </div>
        <div>
          <div class="text-2xl font-black tracking-[-0.06em] text-[#F1EEFA]">AICreators</div>
          <div class="text-[0.58rem] uppercase tracking-[0.2em] text-[#C4BCE3] font-semibold">Create • Connect • Grow</div>
        </div>
      </a>

      <div class="flex items-center gap-3">
        <?php if (!empty($avatar)): ?>
          <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="h-10 w-10 rounded-full object-cover ring-2 ring-cyan-400">
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

      <!-- Main Form Content -->
      <main class="p-6 lg:p-8 bg-[#0A0B12]/60">
        <div class="rounded-[28px] border border-[#26243E] bg-[#121324] p-6 lg:p-8 shadow-sm">
          
          <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <p class="text-xs uppercase tracking-[0.18em] text-[#00F5FF] font-bold">Portfolio Studio</p>
              <h1 class="mt-1 text-3xl font-black tracking-[-0.04em] text-[#F1EEFA]">
                <?= $edit_mode ? 'Edit Portfolio Project' : 'Upload Portfolio Project &amp; 30s Ad' ?>
              </h1>
              <p class="text-xs text-[#C4BCE3] mt-1">
                <?= $edit_mode ? 'Update project title, description, tools, image, or video ad.' : 'Upload an image thumbnail and an optional 30-second video advertisement to showcase your work.' ?>
              </p>
            </div>

            <a
              href="creator_portfolio.php"
              class="inline-flex items-center gap-1.5 rounded-full border border-[#26243E] bg-[#16172B] px-4 py-2 text-xs font-semibold text-[#C4BCE3] hover:text-[#00F5FF] hover:border-[#00F5FF]/50 transition"
            >
              <span>← Back to My Portfolio</span>
            </a>
          </div>

          <?php if (!empty($error)): ?>
            <div class="mb-6 rounded-2xl border border-rose-500/30 bg-rose-950/40 p-4 text-sm text-rose-300 flex items-center gap-2">
              <span class="font-bold text-rose-400">⚠</span>
              <span><?= htmlspecialchars($error) ?></span>
            </div>
          <?php endif; ?>

          <?php if ($edit_mode): ?>
            <div class="mb-6 rounded-2xl border border-amber-500/30 bg-amber-950/30 p-4 text-sm text-amber-200 flex items-center justify-between shadow-sm">
              <div class="flex items-center gap-2">
                <span>✏️</span>
                <span><b>Editing Project:</b> <?= htmlspecialchars($edit_project['title']) ?> (ID #<?= $edit_project['id'] ?>)</span>
              </div>
              <a href="creator_portfolio_add.php" class="text-xs text-amber-300 underline font-semibold hover:text-white">
                + Switch to Add New Project
              </a>
            </div>
          <?php endif; ?>

          <form id="portfolioForm" method="POST" action="creator_portfolio_add.php" enctype="multipart/form-data" class="space-y-6">
            <?php if ($edit_mode): ?>
              <input type="hidden" name="update_project_id" value="<?= $edit_project['id'] ?>">
            <?php endif; ?>

            <!-- Hidden input for validated video duration in seconds -->
            <input type="hidden" id="video_duration" name="video_duration" value="<?= $val_vduration ?>">

            <!-- 1. PROJECT IMAGE UPLOADING SECTION (Replaces URL input) -->
            <div class="rounded-[24px] border border-[#26243E] bg-[#16172B] p-6">
              <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                <div>
                  <div class="inline-flex items-center gap-2 rounded-full bg-cyan-950/60 border border-cyan-500/30 px-3 py-1 text-xs font-bold text-cyan-300">
                    <span>🖼️ PROJECT IMAGE UPLOAD</span>
                    <span>•</span>
                    <span>JPG, PNG, WebP</span>
                  </div>
                  <h3 class="mt-2 text-lg font-bold text-[#F1EEFA]">Upload Project Cover Image</h3>
                  <p class="text-xs text-[#C4BCE3]">
                    Select an image from your device to represent this project in your portfolio and marketplace searches.
                  </p>
                </div>

                <label class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-[#06b6d4] to-[#8b5cf6] px-5 py-2.5 text-xs font-bold text-white cursor-pointer hover:brightness-110 shadow-md">
                  <span>📁 Choose Image File</span>
                  <input
                    type="file"
                    id="image_input"
                    name="project_image"
                    accept="image/jpeg,image/png,image/webp,image/jpg"
                    class="hidden"
                    onchange="previewProjectImage(event)"
                  >
                </label>
              </div>

              <!-- Live Image Preview Container -->
              <div id="image_preview_container" class="<?= empty($val_prev) ? 'hidden' : '' ?> rounded-2xl border border-[#26243E] bg-[#0E0F1B] p-4 shadow-sm">
                <div class="flex flex-col sm:flex-row items-center gap-4">
                  <div class="relative w-full sm:w-56 h-36 bg-black rounded-xl overflow-hidden shadow-inner flex items-center justify-center">
                    <img
                      id="preview_image_tag"
                      src="<?= !empty($val_prev) ? htmlspecialchars($val_prev) : '' ?>"
                      alt="Project Preview"
                      class="w-full h-full object-cover"
                    >
                  </div>
                  <div class="flex-1 space-y-1 text-xs text-[#C4BCE3]">
                    <p id="image_filename" class="font-bold text-[#F1EEFA]">
                      <?= $edit_mode && !empty($val_prev) ? 'Current Image Attached' : 'Selected Image' ?>
                    </p>
                    <p class="text-[#A79DCB]">This image will be displayed on your portfolio cards and brand search views.</p>
                    <button
                      type="button"
                      onclick="clearImageSelection()"
                      class="text-xs text-rose-400 hover:text-rose-300 underline font-semibold cursor-pointer pt-2"
                    >
                      Remove / Change Image
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- 2. OPTIONAL 30-SECOND AD VIDEO FILE UPLOAD -->
            <div class="rounded-[24px] border border-[#26243E] bg-[#16172B] p-6">
              <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                <div>
                  <div class="inline-flex items-center gap-2 rounded-full bg-rose-950/60 border border-rose-500/30 px-3 py-1 text-xs font-bold text-rose-300">
                    <span>⚡ 30S AD VIDEO FILE</span>
                    <span>•</span>
                    <span>Optional • Max 30s</span>
                  </div>
                  <h3 class="mt-2 text-lg font-bold text-[#F1EEFA]">Upload 30-Second Advertisement Video</h3>
                  <p class="text-xs text-[#C4BCE3]">
                    Attach an MP4 or WebM video file. Automatically validated to ensure video duration is 30 seconds or less.
                  </p>
                </div>

                <label class="inline-flex items-center gap-2 rounded-full border border-[#26243E] bg-[#121324] px-5 py-2.5 text-xs font-bold text-[#F1EEFA] cursor-pointer hover:border-[#00F5FF] transition shadow-md">
                  <span>📹 Select 30s Video File</span>
                  <input
                    type="file"
                    id="video_input"
                    name="video_ad"
                    accept="video/mp4,video/webm,video/quicktime"
                    class="hidden"
                    onchange="validateVideoDuration(event)"
                  >
                </label>
              </div>

              <!-- Live Video Player & Validation Preview -->
              <div id="video_preview_container" class="<?= empty($val_vid) ? 'hidden' : '' ?> rounded-2xl border border-[#26243E] bg-[#0E0F1B] p-4 shadow-sm">
                <div class="flex flex-col sm:flex-row items-center gap-4">
                  <div class="relative w-full sm:w-64 h-36 bg-black rounded-xl overflow-hidden shadow-inner">
                    <video id="preview_video" controls class="w-full h-full object-contain">
                      <?php if (!empty($val_vid)): ?>
                        <source src="<?= htmlspecialchars($val_vid) ?>" type="video/mp4">
                      <?php endif; ?>
                    </video>
                  </div>
                  <div class="flex-1 space-y-2">
                    <div id="duration_status" class="text-sm font-bold text-emerald-400 flex items-center gap-2">
                      <span class="font-bold">✓</span>
                      <span id="duration_text">
                        <?= !empty($val_vid) ? "Duration: {$val_vduration}s (Attached 30s Ad)" : "Valid 30s advertisement" ?>
                      </span>
                    </div>
                    <p id="filename_text" class="text-xs text-[#C4BCE3] break-all">
                      <?= !empty($val_vid) ? htmlspecialchars($val_vid) : '' ?>
                    </p>
                    <button
                      type="button"
                      onclick="clearVideoSelection()"
                      class="text-xs text-rose-400 hover:text-rose-300 underline font-semibold cursor-pointer"
                    >
                      Remove video
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- 3. Project Metadata Fields -->
            <div class="grid gap-5 md:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-bold text-[#C4BCE3]">Project Title *</label>
                <input
                  type="text"
                  name="title"
                  required
                  value="<?= htmlspecialchars($val_title) ?>"
                  placeholder="e.g. 30s Cinematic Nature Commercial"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] placeholder:text-[#766E99] focus:border-[#00F5FF] focus:outline-none transition"
                />
              </div>

              <div>
                <label class="mb-2 block text-sm font-bold text-[#C4BCE3]">Content Type *</label>
                <select
                  name="contentType"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] focus:border-[#00F5FF] focus:outline-none transition"
                >
                  <option value="Video" <?= $val_ctype === 'Video' ? 'selected' : '' ?> class="bg-[#121324]">Video (Commercial / Ad)</option>
                  <option value="Animation" <?= $val_ctype === 'Animation' ? 'selected' : '' ?> class="bg-[#121324]">3D Animation Ad</option>
                  <option value="Image" <?= $val_ctype === 'Image' ? 'selected' : '' ?> class="bg-[#121324]">AI Visuals / Lookbook</option>
                  <option value="Social Media" <?= $val_ctype === 'Social Media' ? 'selected' : '' ?> class="bg-[#121324]">Social Reel / TikTok Ad</option>
                  <option value="Product Visualization" <?= $val_ctype === 'Product Visualization' ? 'selected' : '' ?> class="bg-[#121324]">Product Visualization</option>
                </select>
              </div>
            </div>

            <div>
              <label class="mb-2 block text-sm font-bold text-[#C4BCE3]">Description / Creative Concept</label>
              <textarea
                name="description"
                rows="3"
                placeholder="Brief description of the ad concept, prompt workflow, lighting style, and camera movement..."
                class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] placeholder:text-[#766E99] focus:border-[#00F5FF] focus:outline-none transition leading-relaxed"
              ><?= htmlspecialchars($val_desc) ?></textarea>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
              <div>
                <label class="mb-2 block text-sm font-bold text-[#C4BCE3]">AI Tools &amp; Models Used</label>
                <input
                  type="text"
                  name="tools"
                  value="<?= htmlspecialchars($val_tools) ?>"
                  placeholder="e.g. Runway Gen-3, Kling 1.5, Midjourney v6"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] placeholder:text-[#766E99] focus:border-[#00F5FF] focus:outline-none transition"
                />
              </div>

              <div>
                <label class="mb-2 block text-sm font-bold text-[#C4BCE3]">Skills / Tags (comma separated)</label>
                <input
                  type="text"
                  name="skills"
                  value="<?= htmlspecialchars($val_skills) ?>"
                  placeholder="e.g. AI Video, 30s Ads, Prompt Engineering, Color Grading"
                  class="w-full rounded-2xl border border-[#26243E] bg-[#16172B] px-4 py-3 text-[#F1EEFA] placeholder:text-[#766E99] focus:border-[#00F5FF] focus:outline-none transition"
                />
              </div>
            </div>

            <div class="flex flex-col gap-3 pt-4 sm:flex-row sm:justify-end">
              <a
                href="creator_portfolio.php"
                class="rounded-full border border-[#26243E] bg-[#16172B] px-6 py-3 text-sm font-bold text-[#C4BCE3] transition hover:text-[#F1EEFA] text-center"
              >
                Cancel
              </a>

              <button
                type="submit"
                class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-8 py-3 text-sm font-bold text-white shadow-md transition hover:brightness-110 cursor-pointer text-center"
              >
                <?= $edit_mode ? '✓ Update Project' : '+ Publish Project' ?>
              </button>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>

  <!-- Client-side Image Preview & Video 30s Duration Validation Script -->
  <script>
    // Preview selected image file
    function previewProjectImage(event) {
      const file = event.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = function(e) {
        const previewImg = document.getElementById('preview_image_tag');
        previewImg.src = e.target.result;
        document.getElementById('image_filename').textContent = file.name + ' (' + (file.size / (1024*1024)).toFixed(1) + ' MB)';
        document.getElementById('image_preview_container').classList.remove('hidden');
      };
      reader.readAsDataURL(file);
    }

    function clearImageSelection() {
      const input = document.getElementById('image_input');
      input.value = '';
      <?php if (!$edit_mode): ?>
        document.getElementById('image_preview_container').classList.add('hidden');
      <?php else: ?>
        document.getElementById('image_filename').textContent = 'Original image will be preserved if no new file selected.';
      <?php endif; ?>
    }

    // Validate 30-second video duration
    function validateVideoDuration(event) {
      const file = event.target.files[0];
      if (!file) return;

      const videoElement = document.createElement('video');
      videoElement.preload = 'metadata';

      videoElement.onloadedmetadata = function() {
        window.URL.revokeObjectURL(videoElement.src);
        const duration = Math.round(videoElement.duration);

        if (duration > 30.5) {
          alert('⚠ Video duration exceeds limit!\n\nThis video is ' + duration + ' seconds long. Advertisements must be 30 seconds or less.\n\nPlease select a video under 30 seconds.');
          clearVideoSelection();
        } else {
          document.getElementById('video_duration').value = duration;
          document.getElementById('duration_text').textContent = 'Duration: ' + duration + 's (Valid 30s advertisement)';
          document.getElementById('filename_text').textContent = file.name + ' (' + (file.size / (1024*1024)).toFixed(1) + ' MB)';
          
          const previewVid = document.getElementById('preview_video');
          previewVid.src = URL.createObjectURL(file);
          
          document.getElementById('video_preview_container').classList.remove('hidden');
        }
      };

      videoElement.src = URL.createObjectURL(file);
    }

    function clearVideoSelection() {
      document.getElementById('video_input').value = '';
      document.getElementById('video_duration').value = '0';
      const previewVid = document.getElementById('preview_video');
      previewVid.src = '';
      document.getElementById('video_preview_container').classList.add('hidden');
    }
  </script>

  <!-- Floating AI Assistant Chatbot -->
  <?php require_once __DIR__ . '/includes/chatbot.php'; ?>

</body>
</html>
