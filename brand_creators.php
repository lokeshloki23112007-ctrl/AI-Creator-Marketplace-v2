<?php
// brand_creators.php - Search & Explore Creators with Comprehensive Brand Filters
require_once __DIR__ . '/config/db.php';
$logged_user = get_logged_in_user();
$db = get_db();

// 1. Definition of All 12 Filter Categories
$filter_definitions = [
    'specialization' => [
        'title' => '1. Specialization',
        'icon' => '🎬',
        'options' => [
            'AI Filmmaker',
            'AI Video Creator',
            'AI Animator',
            'AI Artist',
            'Generative Designer',
            'AI Motion Designer',
            'Product Visualization Artist',
            'Advertising Creative'
        ]
    ],
    'skills' => [
        'title' => '2. Skills',
        'icon' => '⚡',
        'options' => [
            'AI Video',
            'AI Animation',
            'Image Generation',
            'Prompt Engineering',
            'Product Visualization',
            'Storyboarding',
            'Motion Graphics',
            'Video Editing',
            'Generative Design',
            'Character Design',
            '3D Generation',
            'Advertising'
        ]
    ],
    'tools' => [
        'title' => '3. AI Tools / Models',
        'icon' => '🤖',
        'options' => [
            'Runway',
            'Kling',
            'Midjourney',
            'Sora',
            'Veo',
            'Flux',
            'Stable Diffusion',
            'ComfyUI',
            'Adobe Firefly',
            'ElevenLabs',
            'ChatGPT'
        ]
    ],
    'content_types' => [
        'title' => '4. Content Type',
        'icon' => '🎞️',
        'options' => [
            'Video',
            'Image',
            'Animation',
            'Advertisement',
            'Social Media',
            'Product Video',
            'Product Image',
            '3D',
            'Short Film'
        ]
    ],
    'aspect_ratios' => [
        'title' => '5. Aspect Ratio / Format',
        'icon' => '📐',
        'options' => [
            '16:9',
            '9:16',
            '1:1',
            '4:5'
        ]
    ],
    'budget' => [
        'title' => '6. Budget (INR)',
        'icon' => '₹',
        'options' => [
            'Under ₹10,000',
            '₹10,000–₹25,000',
            '₹25,000–₹50,000',
            '₹50,000–₹1,00,000',
            '₹1,00,000+'
        ]
    ],
    'experience' => [
        'title' => '7. Experience Level',
        'icon' => '🎯',
        'options' => [
            'Beginner',
            'Intermediate',
            'Advanced',
            'Expert'
        ]
    ],
    'availability' => [
        'title' => '8. Availability',
        'icon' => '⏱️',
        'options' => [
            'Available Now',
            'Available This Week',
            'Currently Booked'
        ]
    ],
    'commercial_rights' => [
        'title' => '9. Commercial Rights',
        'icon' => '⚖️',
        'options' => [
            'Commercial Use',
            'Paid Advertising',
            'Licensing Available',
            'Exclusive Rights'
        ]
    ],
    'verification' => [
        'title' => '10. Verification',
        'icon' => '🛡️',
        'options' => [
            'Tools Verified',
            'Portfolio Verified',
            'Workflow Verified',
            'Commercial Ready'
        ]
    ],
    'rating_perf' => [
        'title' => '11. Rating / Performance',
        'icon' => '★',
        'options' => [
            'Highest Rated',
            'Most Projects',
            'Most Relevant'
        ]
    ]
];

// Helper to normalize GET array inputs
if (!function_exists('get_filter_array')) {
    function get_filter_array(string $key): array {
        $val = $_GET[$key] ?? [];
        if (is_array($val)) {
            return array_values(array_filter(array_map('trim', $val)));
        }
        if (is_string($val) && trim($val) !== '' && trim($val) !== 'All') {
            return [trim($val)];
        }
        return [];
    }
}

// Read parameters from GET
$search = trim($_GET['search'] ?? '');
$sort_by = trim($_GET['sort_by'] ?? 'best_match');

$sel_specializations = get_filter_array('specialization');
$sel_skills           = get_filter_array('skills');
$sel_tools            = get_filter_array('tools');
$sel_content_types    = get_filter_array('content_types');
$sel_aspect_ratios    = get_filter_array('aspect_ratios');
$sel_budget           = get_filter_array('budget');
$sel_experience       = get_filter_array('experience');
$sel_availability     = get_filter_array('availability');
$sel_commercial       = get_filter_array('commercial_rights');
$sel_verification     = get_filter_array('verification');
$sel_rating_perf      = get_filter_array('rating_perf');

// Support legacy quick category pill click: ?filter=AI Video
$legacy_filter = trim($_GET['filter'] ?? '');
if (!empty($legacy_filter) && $legacy_filter !== 'All') {
    // If matching a specialization or skill, add it
    if (in_array($legacy_filter, $filter_definitions['specialization']['options'])) {
        $sel_specializations[] = $legacy_filter;
    } elseif (in_array($legacy_filter, $filter_definitions['skills']['options'])) {
        $sel_skills[] = $legacy_filter;
    } elseif (in_array($legacy_filter, $filter_definitions['tools']['options'])) {
        $sel_tools[] = $legacy_filter;
    } else {
        $search = $legacy_filter;
    }
}

// 2. Build Dynamic MySQL Query Safely with PDO
$where = ["1=1"];
$params = [];
$p_idx = 0;

// Search Query across multiple columns
if (!empty($search)) {
    $p1 = "s" . (++$p_idx);
    $p2 = "s" . (++$p_idx);
    $p3 = "s" . (++$p_idx);
    $p4 = "s" . (++$p_idx);
    $p5 = "s" . (++$p_idx);
    $p6 = "s" . (++$p_idx);
    $p7 = "s" . (++$p_idx);
    $p8 = "s" . (++$p_idx);
    $where[] = "(u.full_name LIKE :$p1 OR c.role_title LIKE :$p2 OR c.specialization LIKE :$p3 OR c.skills LIKE :$p4 OR c.tools LIKE :$p5 OR c.content_types LIKE :$p6 OR c.bio LIKE :$p7 OR c.commercial_rights LIKE :$p8)";
    $s_val = '%' . $search . '%';
    $params[$p1] = $s_val;
    $params[$p2] = $s_val;
    $params[$p3] = $s_val;
    $params[$p4] = $s_val;
    $params[$p5] = $s_val;
    $params[$p6] = $s_val;
    $params[$p7] = $s_val;
    $params[$p8] = $s_val;
}

// 1. Specialization
if (!empty($sel_specializations)) {
    $sub = [];
    foreach ($sel_specializations as $spec) {
        $k1 = "sp_" . (++$p_idx);
        $k2 = "sp_" . (++$p_idx);
        $sub[] = "(c.specialization LIKE :$k1 OR c.role_title LIKE :$k2)";
        $params[$k1] = '%' . $spec . '%';
        $params[$k2] = '%' . $spec . '%';
    }
    $where[] = "(" . implode(" OR ", $sub) . ")";
}

// 2. Skills
if (!empty($sel_skills)) {
    $sub = [];
    foreach ($sel_skills as $sk) {
        $k = "sk_" . (++$p_idx);
        $sub[] = "c.skills LIKE :$k";
        $params[$k] = '%' . $sk . '%';
    }
    $where[] = "(" . implode(" OR ", $sub) . ")";
}

// 3. AI Tools / Models
if (!empty($sel_tools)) {
    $sub = [];
    foreach ($sel_tools as $tl) {
        $k = "tl_" . (++$p_idx);
        $sub[] = "c.tools LIKE :$k";
        $params[$k] = '%' . $tl . '%';
    }
    $where[] = "(" . implode(" OR ", $sub) . ")";
}

// 4. Content Type
if (!empty($sel_content_types)) {
    $sub = [];
    foreach ($sel_content_types as $ct) {
        $k = "ct_" . (++$p_idx);
        $sub[] = "c.content_types LIKE :$k";
        $params[$k] = '%' . $ct . '%';
    }
    $where[] = "(" . implode(" OR ", $sub) . ")";
}

// 5. Aspect Ratio / Format
if (!empty($sel_aspect_ratios)) {
    $sub = [];
    foreach ($sel_aspect_ratios as $ar) {
        $k = "ar_" . (++$p_idx);
        $sub[] = "c.aspect_ratios LIKE :$k";
        $params[$k] = '%' . $ar . '%';
    }
    $where[] = "(" . implode(" OR ", $sub) . ")";
}

// 6. Budget (INR)
if (!empty($sel_budget)) {
    $sub = [];
    foreach ($sel_budget as $b) {
        if ($b === 'Under ₹10,000') {
            $sub[] = "(c.price_num <= 10000 OR c.budget_tier LIKE '%Under ₹10,000%')";
        } elseif ($b === '₹10,000–₹25,000') {
            $sub[] = "((c.price_num >= 10000 AND c.price_num <= 25000) OR c.budget_tier LIKE '%₹10,000–₹25,000%')";
        } elseif ($b === '₹25,000–₹50,000') {
            $sub[] = "((c.price_num >= 25000 AND c.price_num <= 50000) OR c.budget_tier LIKE '%₹25,000–₹50,000%')";
        } elseif ($b === '₹50,000–₹1,00,000') {
            $sub[] = "((c.price_num >= 50000 AND c.price_num <= 100000) OR c.budget_tier LIKE '%₹50,000–₹1,00,000%')";
        } elseif ($b === '₹1,00,000+') {
            $sub[] = "(c.price_num >= 100000 OR c.budget_tier LIKE '%₹1,00,000+%')";
        } else {
            $k = "bg_" . (++$p_idx);
            $sub[] = "c.budget_tier LIKE :$k";
            $params[$k] = '%' . $b . '%';
        }
    }
    $where[] = "(" . implode(" OR ", $sub) . ")";
}

// 7. Experience Level
if (!empty($sel_experience)) {
    $sub = [];
    foreach ($sel_experience as $exp) {
        $k = "exp_" . (++$p_idx);
        $sub[] = "c.experience_level LIKE :$k";
        $params[$k] = '%' . $exp . '%';
    }
    $where[] = "(" . implode(" OR ", $sub) . ")";
}

// 8. Availability
if (!empty($sel_availability)) {
    $sub = [];
    foreach ($sel_availability as $av) {
        $k = "av_" . (++$p_idx);
        $sub[] = "c.availability LIKE :$k";
        $params[$k] = '%' . $av . '%';
    }
    $where[] = "(" . implode(" OR ", $sub) . ")";
}

// 9. Commercial Rights
if (!empty($sel_commercial)) {
    $sub = [];
    foreach ($sel_commercial as $cr) {
        $k = "cr_" . (++$p_idx);
        $sub[] = "c.commercial_rights LIKE :$k";
        $params[$k] = '%' . $cr . '%';
    }
    $where[] = "(" . implode(" OR ", $sub) . ")";
}

// 10. Verification
if (!empty($sel_verification)) {
    $sub = [];
    foreach ($sel_verification as $ver) {
        $k = "vr_" . (++$p_idx);
        $sub[] = "c.verification_tags LIKE :$k";
        $params[$k] = '%' . $ver . '%';
    }
    $where[] = "(" . implode(" OR ", $sub) . ")";
}

// 11. Rating / Performance
if (!empty($sel_rating_perf)) {
    foreach ($sel_rating_perf as $rp) {
        if ($rp === 'Highest Rated') {
            $where[] = "c.rating >= 4.8";
        } elseif ($rp === 'Most Projects') {
            $where[] = "c.projects_count >= 20";
        }
    }
}

// 12. Sort By
$order_by = "ORDER BY c.rating DESC, c.projects_count DESC"; // Best Match / Most Relevant
if ($sort_by === 'price_asc') {
    $order_by = "ORDER BY c.price_num ASC";
} elseif ($sort_by === 'price_desc') {
    $order_by = "ORDER BY c.price_num DESC";
} elseif ($sort_by === 'newest') {
    $order_by = "ORDER BY c.id DESC";
} elseif ($sort_by === 'rating') {
    $order_by = "ORDER BY c.rating DESC, c.reviews_count DESC";
} elseif ($sort_by === 'projects') {
    $order_by = "ORDER BY c.projects_count DESC";
}

// Execute Query
$query = "
    SELECT c.*, u.full_name, u.avatar_url, u.email 
    FROM creator_profiles c 
    JOIN users u ON c.user_id = u.id 
    WHERE " . implode(" AND ", $where) . " 
    " . $order_by;

$stmt = $db->prepare($query);
$stmt->execute($params);
$creators = $stmt->fetchAll();

// Total count of active filters
$total_active_filters = count($sel_specializations) + count($sel_skills) + count($sel_tools) + 
                        count($sel_content_types) + count($sel_aspect_ratios) + count($sel_budget) + 
                        count($sel_experience) + count($sel_availability) + count($sel_commercial) + 
                        count($sel_verification) + count($sel_rating_perf);

$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Explore Creators &amp; Brand Filters - AICreators</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body, * { font-family: 'Times New Roman', Times, serif !important; }
    body { background-color: #0A0B12; color: #F1EEFA; }
    
    /* Custom scrollbar for sidebar */
    .filter-scroll::-webkit-scrollbar {
      width: 5px;
    }
    .filter-scroll::-webkit-scrollbar-track {
      background: #0A0B12;
    }
    .filter-scroll::-webkit-scrollbar-thumb {
      background: #2E284C;
      border-radius: 4px;
    }
    .filter-scroll::-webkit-scrollbar-thumb:hover {
      background: #00F5FF;
    }
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
          <div class="text-2xl font-black tracking-[-0.06em] text-[#F1EEFA]"><?= ($logged_user && $logged_user['role'] === 'brand') ? 'Brand/Agency' : 'AICreators' ?></div>
          <div class="text-[0.58rem] uppercase tracking-[0.2em] text-[#C4BCE3]"><?= ($logged_user && $logged_user['role'] === 'brand') ? 'Hire • Campaigns • Scale' : 'Create • Connect • Grow' ?></div>
        </div>
      </a>

      <?php if ($logged_user): ?>
        <a href="<?= $logged_user['role'] === 'brand' ? 'brand_profile.php' : 'creator_profile.php' ?>" class="flex items-center gap-3">
          <?php if (!empty($logged_user['avatar_url'])): ?>
            <img src="<?= htmlspecialchars($logged_user['avatar_url']) ?>" alt="Avatar" class="h-10 w-10 rounded-full object-cover ring-2 ring-cyan-400">
          <?php else: ?>
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] text-sm font-bold text-white shadow-md">
              <?= strtoupper(substr($logged_user['full_name'], 0, 2)) ?>
            </div>
          <?php endif; ?>
          <div>
            <div class="text-sm font-semibold text-[#F1EEFA]"><?= htmlspecialchars($logged_user['full_name']) ?></div>
            <div class="text-xs text-[#A79DCB] capitalize"><?= htmlspecialchars($logged_user['role']) ?></div>
          </div>
        </a>
      <?php else: ?>
        <div class="flex gap-2">
          <a href="login.php" class="rounded-full border border-cyan-400/50 bg-[#16172B] px-4 py-2 text-xs font-semibold text-[#00F5FF] hover:bg-cyan-950/40">Login</a>
          <a href="signup.php" class="rounded-full bg-gradient-to-r from-[#06b6d4] to-[#8b5cf6] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:brightness-110">Sign Up</a>
        </div>
      <?php endif; ?>
    </header>

    <div class="grid min-h-[760px] lg:grid-cols-[260px_1fr]">
      <!-- Left Dashboard Navigation Sidebar -->
      <?php if ($logged_user && $logged_user['role'] === 'creator'): ?>
        <?php require_once __DIR__ . '/includes/creator_sidebar.php'; ?>
      <?php else: ?>
        <?php require_once __DIR__ . '/includes/brand_sidebar.php'; ?>
      <?php endif; ?>

      <!-- Main Content Area -->
      <main class="p-6 lg:p-8 bg-[#0A0B12]/60">
        <div class="rounded-[28px] border border-[#26243E] bg-[#121324] p-6 lg:p-8 shadow-sm">
          
          <?php if ($flash): ?>
            <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-950/40 p-4 text-sm text-emerald-300">
              ✓ <?= htmlspecialchars($flash['message']) ?>
            </div>
          <?php endif; ?>

          <!-- Filter Form Starts Here -->
          <form method="GET" action="brand_creators.php" id="filterForm">
            <!-- Top Controls Bar: Search & Sort & Apply -->
            <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
              
              <!-- Search Box -->
              <div class="flex-1">
                <div class="flex items-center gap-3 rounded-full border border-[#26243E] bg-[#16172B] px-4 py-3 shadow-inner">
                  <span class="text-lg text-[#7A7593]">⌕</span>
                  <input
                    type="text"
                    name="search"
                    placeholder="Search creators, skills, AI models, 30s ads, or commercial terms..."
                    value="<?= htmlspecialchars($search) ?>"
                    class="w-full bg-transparent text-sm text-[#F1EEFA] placeholder:text-[#7A7593] focus:outline-none"
                  />
                  <?php if (!empty($search)): ?>
                    <a href="brand_creators.php" class="text-xs text-[#7A7593] hover:text-[#00F5FF]">Clear</a>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Sort By Dropdown -->
              <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 rounded-full border border-[#26243E] bg-[#16172B] px-4 py-2.5">
                  <span class="text-xs text-[#A79DCB]">Sort By:</span>
                  <select
                    name="sort_by"
                    onchange="document.getElementById('filterForm').submit();"
                    class="bg-transparent text-xs font-semibold text-[#F1EEFA] focus:outline-none cursor-pointer"
                  >
                    <option value="best_match" class="bg-[#16172B] text-[#F1EEFA]" <?= $sort_by === 'best_match' ? 'selected' : '' ?>>Best Match</option>
                    <option value="price_asc" class="bg-[#16172B] text-[#F1EEFA]" <?= $sort_by === 'price_asc' ? 'selected' : '' ?>>Price: Low → High</option>
                    <option value="price_desc" class="bg-[#16172B] text-[#F1EEFA]" <?= $sort_by === 'price_desc' ? 'selected' : '' ?>>Price: High → Low</option>
                    <option value="newest" class="bg-[#16172B] text-[#F1EEFA]" <?= $sort_by === 'newest' ? 'selected' : '' ?>>Newest</option>
                    <option value="rating" class="bg-[#16172B] text-[#F1EEFA]" <?= $sort_by === 'rating' ? 'selected' : '' ?>>Highest Rated</option>
                    <option value="projects" class="bg-[#16172B] text-[#F1EEFA]" <?= $sort_by === 'projects' ? 'selected' : '' ?>>Most Projects</option>
                  </select>
                </div>

                <!-- Submit Button -->
                <button
                  type="submit"
                  class="rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-6 py-2.5 text-xs font-bold text-white shadow-[0_0_18px_rgba(6,182,212,0.35)] transition hover:brightness-110 cursor-pointer"
                >
                  Apply Filters
                </button>

                <?php if ($total_active_filters > 0 || !empty($search)): ?>
                  <a
                    href="brand_creators.php"
                    class="rounded-full border border-rose-500/30 bg-rose-950/40 px-4 py-2 text-xs font-semibold text-rose-300 hover:bg-rose-900/60"
                    title="Reset all filters"
                  >
                    Reset (<?= $total_active_filters ?>)
                  </a>
                <?php endif; ?>
              </div>
            </div>

            <!-- Active Filter Badges Bar -->
            <?php if ($total_active_filters > 0 || !empty($search)): ?>
              <div class="mb-6 flex flex-wrap items-center gap-2 rounded-2xl border border-[#26243E] bg-[#16172B] p-3">
                <span class="text-xs font-semibold text-[#A79DCB]">Active Filters:</span>
                
                <?php if (!empty($search)): ?>
                  <span class="inline-flex items-center gap-1.5 rounded-full border border-cyan-500/30 bg-cyan-950/40 px-3 py-1 text-xs text-cyan-300 font-semibold">
                    Search: "<?= htmlspecialchars($search) ?>"
                  </span>
                <?php endif; ?>

                <?php foreach ([
                  'Specialization' => $sel_specializations,
                  'Skills' => $sel_skills,
                  'Tools' => $sel_tools,
                  'Content' => $sel_content_types,
                  'Aspect' => $sel_aspect_ratios,
                  'Budget' => $sel_budget,
                  'Level' => $sel_experience,
                  'Status' => $sel_availability,
                  'Rights' => $sel_commercial,
                  'Verified' => $sel_verification,
                  'Perf' => $sel_rating_perf,
                ] as $label => $group): ?>
                  <?php foreach ($group as $item): ?>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-purple-500/30 bg-purple-950/40 px-3 py-1 text-xs text-purple-200">
                      <span class="text-[0.68rem] text-[#A79DCB]"><?= $label ?>:</span>
                      <b><?= htmlspecialchars($item) ?></b>
                    </span>
                  <?php endforeach; ?>
                <?php endforeach; ?>

                <a href="brand_creators.php" class="ml-auto text-xs text-rose-400 hover:underline">Clear All Filters</a>
              </div>
            <?php endif; ?>

            <!-- Main Layout: 12 Filters Sidebar + Creators Listing Grid -->
            <div class="grid gap-6 xl:grid-cols-[300px_1fr]">
              
              <!-- Brand Filters Sidebar -->
              <aside class="rounded-[24px] border border-[#26243E] bg-[#16172B] p-5 filter-scroll max-h-[1100px] overflow-y-auto space-y-5">
                
                <div class="flex items-center justify-between border-b border-[#26243E] pb-3">
                  <div class="flex items-center gap-2">
                    <span class="text-lg">⚙</span>
                    <h3 class="text-base font-bold text-[#F1EEFA] tracking-wide">Brand Filters</h3>
                  </div>
                  <?php if ($total_active_filters > 0): ?>
                    <span class="rounded-full bg-purple-950/60 text-purple-300 text-[0.68rem] font-bold px-2.5 py-0.5 border border-purple-500/30">
                      <?= $total_active_filters ?> active
                    </span>
                  <?php endif; ?>
                </div>

                <!-- 1. Specialization -->
                <div class="space-y-2 border-b border-[#26243E] pb-4">
                  <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-purple-400 tracking-wider uppercase">1. Specialization</label>
                    <?php if (count($sel_specializations) > 0): ?>
                      <span class="text-[0.65rem] text-purple-300 font-semibold">(<?= count($sel_specializations) ?>)</span>
                    <?php endif; ?>
                  </div>
                  <div class="space-y-1.5 pl-1">
                    <?php foreach ($filter_definitions['specialization']['options'] as $opt): ?>
                      <label class="flex items-center gap-2 text-xs text-[#C4BCE3] hover:text-[#F1EEFA] cursor-pointer select-none">
                        <input
                          type="checkbox"
                          name="specialization[]"
                          value="<?= htmlspecialchars($opt) ?>"
                          <?= in_array($opt, $sel_specializations) ? 'checked' : '' ?>
                          class="rounded border-[#26243E] bg-[#0E0F1B] text-purple-600 focus:ring-purple-400"
                        />
                        <span class="<?= in_array($opt, $sel_specializations) ? 'text-[#00F5FF] font-bold' : '' ?>"><?= htmlspecialchars($opt) ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- 2. Skills -->
                <div class="space-y-2 border-b border-[#26243E] pb-4">
                  <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-cyan-400 tracking-wider uppercase">2. Skills</label>
                    <?php if (count($sel_skills) > 0): ?>
                      <span class="text-[0.65rem] text-cyan-300 font-semibold">(<?= count($sel_skills) ?>)</span>
                    <?php endif; ?>
                  </div>
                  <div class="space-y-1.5 pl-1 max-h-48 overflow-y-auto filter-scroll pr-1">
                    <?php foreach ($filter_definitions['skills']['options'] as $opt): ?>
                      <label class="flex items-center gap-2 text-xs text-[#C4BCE3] hover:text-[#F1EEFA] cursor-pointer select-none">
                        <input
                          type="checkbox"
                          name="skills[]"
                          value="<?= htmlspecialchars($opt) ?>"
                          <?= in_array($opt, $sel_skills) ? 'checked' : '' ?>
                          class="rounded border-[#26243E] bg-[#0E0F1B] text-cyan-500 focus:ring-cyan-400"
                        />
                        <span class="<?= in_array($opt, $sel_skills) ? 'text-[#00F5FF] font-bold' : '' ?>"><?= htmlspecialchars($opt) ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- 3. AI Tools / Models -->
                <div class="space-y-2 border-b border-[#26243E] pb-4">
                  <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-emerald-400 tracking-wider uppercase">3. AI Tools / Models</label>
                    <?php if (count($sel_tools) > 0): ?>
                      <span class="text-[0.65rem] text-emerald-300 font-semibold">(<?= count($sel_tools) ?>)</span>
                    <?php endif; ?>
                  </div>
                  <div class="space-y-1.5 pl-1 max-h-48 overflow-y-auto filter-scroll pr-1">
                    <?php foreach ($filter_definitions['tools']['options'] as $opt): ?>
                      <label class="flex items-center gap-2 text-xs text-[#C4BCE3] hover:text-[#F1EEFA] cursor-pointer select-none">
                        <input
                          type="checkbox"
                          name="tools[]"
                          value="<?= htmlspecialchars($opt) ?>"
                          <?= in_array($opt, $sel_tools) ? 'checked' : '' ?>
                          class="rounded border-[#26243E] bg-[#0E0F1B] text-emerald-500 focus:ring-emerald-400"
                        />
                        <span class="<?= in_array($opt, $sel_tools) ? 'text-[#00F5FF] font-bold' : '' ?>"><?= htmlspecialchars($opt) ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- 4. Content Type -->
                <div class="space-y-2 border-b border-[#26243E] pb-4">
                  <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-pink-400 tracking-wider uppercase">4. Content Type</label>
                    <?php if (count($sel_content_types) > 0): ?>
                      <span class="text-[0.65rem] text-pink-300 font-semibold">(<?= count($sel_content_types) ?>)</span>
                    <?php endif; ?>
                  </div>
                  <div class="space-y-1.5 pl-1">
                    <?php foreach ($filter_definitions['content_types']['options'] as $opt): ?>
                      <label class="flex items-center gap-2 text-xs text-[#C4BCE3] hover:text-[#F1EEFA] cursor-pointer select-none">
                        <input
                          type="checkbox"
                          name="content_types[]"
                          value="<?= htmlspecialchars($opt) ?>"
                          <?= in_array($opt, $sel_content_types) ? 'checked' : '' ?>
                          class="rounded border-[#26243E] bg-[#0E0F1B] text-pink-500 focus:ring-pink-400"
                        />
                        <span class="<?= in_array($opt, $sel_content_types) ? 'text-[#00F5FF] font-bold' : '' ?>"><?= htmlspecialchars($opt) ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- 5. Aspect Ratio / Format -->
                <div class="space-y-2 border-b border-[#26243E] pb-4">
                  <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-amber-400 tracking-wider uppercase">5. Aspect Ratio / Format</label>
                    <?php if (count($sel_aspect_ratios) > 0): ?>
                      <span class="text-[0.65rem] text-amber-300 font-semibold">(<?= count($sel_aspect_ratios) ?>)</span>
                    <?php endif; ?>
                  </div>
                  <div class="grid grid-cols-2 gap-2 pl-1">
                    <?php foreach ($filter_definitions['aspect_ratios']['options'] as $opt): ?>
                      <label class="flex items-center gap-2 rounded-xl border border-[#26243E] bg-[#0E0F1B] px-2.5 py-1.5 text-xs text-[#C4BCE3] hover:bg-[#121324] cursor-pointer select-none <?= in_array($opt, $sel_aspect_ratios) ? 'border-cyan-400/50 bg-cyan-950/40 text-cyan-300 font-bold' : '' ?>">
                        <input
                          type="checkbox"
                          name="aspect_ratios[]"
                          value="<?= htmlspecialchars($opt) ?>"
                          <?= in_array($opt, $sel_aspect_ratios) ? 'checked' : '' ?>
                          class="rounded border-[#26243E] bg-[#0E0F1B] text-cyan-400 focus:ring-cyan-400"
                        />
                        <span><?= htmlspecialchars($opt) ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- 6. Budget (INR) -->
                <div class="space-y-2 border-b border-[#26243E] pb-4">
                  <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-emerald-400 tracking-wider uppercase">6. Budget (INR)</label>
                    <?php if (count($sel_budget) > 0): ?>
                      <span class="text-[0.65rem] text-emerald-300 font-semibold">(<?= count($sel_budget) ?>)</span>
                    <?php endif; ?>
                  </div>
                  <div class="space-y-1.5 pl-1">
                    <?php foreach ($filter_definitions['budget']['options'] as $opt): ?>
                      <label class="flex items-center gap-2 text-xs text-[#C4BCE3] hover:text-[#F1EEFA] cursor-pointer select-none">
                        <input
                          type="checkbox"
                          name="budget[]"
                          value="<?= htmlspecialchars($opt) ?>"
                          <?= in_array($opt, $sel_budget) ? 'checked' : '' ?>
                          class="rounded border-[#26243E] bg-[#0E0F1B] text-emerald-500 focus:ring-emerald-400"
                        />
                        <span class="<?= in_array($opt, $sel_budget) ? 'text-[#00F5FF] font-bold' : '' ?>"><?= htmlspecialchars($opt) ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- 7. Experience Level -->
                <div class="space-y-2 border-b border-[#26243E] pb-4">
                  <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-cyan-400 tracking-wider uppercase">7. Experience Level</label>
                    <?php if (count($sel_experience) > 0): ?>
                      <span class="text-[0.65rem] text-cyan-300 font-semibold">(<?= count($sel_experience) ?>)</span>
                    <?php endif; ?>
                  </div>
                  <div class="space-y-1.5 pl-1">
                    <?php foreach ($filter_definitions['experience']['options'] as $opt): ?>
                      <label class="flex items-center gap-2 text-xs text-[#C4BCE3] hover:text-[#F1EEFA] cursor-pointer select-none">
                        <input
                          type="checkbox"
                          name="experience[]"
                          value="<?= htmlspecialchars($opt) ?>"
                          <?= in_array($opt, $sel_experience) ? 'checked' : '' ?>
                          class="rounded border-[#26243E] bg-[#0E0F1B] text-cyan-500 focus:ring-cyan-400"
                        />
                        <span class="<?= in_array($opt, $sel_experience) ? 'text-[#00F5FF] font-bold' : '' ?>"><?= htmlspecialchars($opt) ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- 8. Availability -->
                <div class="space-y-2 border-b border-[#26243E] pb-4">
                  <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-green-400 tracking-wider uppercase">8. Availability</label>
                    <?php if (count($sel_availability) > 0): ?>
                      <span class="text-[0.65rem] text-green-300 font-semibold">(<?= count($sel_availability) ?>)</span>
                    <?php endif; ?>
                  </div>
                  <div class="space-y-1.5 pl-1">
                    <?php foreach ($filter_definitions['availability']['options'] as $opt): ?>
                      <label class="flex items-center gap-2 text-xs text-[#C4BCE3] hover:text-[#F1EEFA] cursor-pointer select-none">
                        <input
                          type="checkbox"
                          name="availability[]"
                          value="<?= htmlspecialchars($opt) ?>"
                          <?= in_array($opt, $sel_availability) ? 'checked' : '' ?>
                          class="rounded border-[#26243E] bg-[#0E0F1B] text-green-500 focus:ring-green-400"
                        />
                        <span class="<?= in_array($opt, $sel_availability) ? 'text-[#00F5FF] font-bold' : '' ?>"><?= htmlspecialchars($opt) ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- 9. Commercial Rights -->
                <div class="space-y-2 border-b border-[#26243E] pb-4">
                  <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-violet-400 tracking-wider uppercase">9. Commercial Rights</label>
                    <?php if (count($sel_commercial) > 0): ?>
                      <span class="text-[0.65rem] text-violet-300 font-semibold">(<?= count($sel_commercial) ?>)</span>
                    <?php endif; ?>
                  </div>
                  <div class="space-y-1.5 pl-1">
                    <?php foreach ($filter_definitions['commercial_rights']['options'] as $opt): ?>
                      <label class="flex items-center gap-2 text-xs text-[#C4BCE3] hover:text-[#F1EEFA] cursor-pointer select-none">
                        <input
                          type="checkbox"
                          name="commercial_rights[]"
                          value="<?= htmlspecialchars($opt) ?>"
                          <?= in_array($opt, $sel_commercial) ? 'checked' : '' ?>
                          class="rounded border-[#26243E] bg-[#0E0F1B] text-violet-500 focus:ring-violet-400"
                        />
                        <span class="<?= in_array($opt, $sel_commercial) ? 'text-[#00F5FF] font-bold' : '' ?>"><?= htmlspecialchars($opt) ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- 10. Verification -->
                <div class="space-y-2 border-b border-[#26243E] pb-4">
                  <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-blue-400 tracking-wider uppercase">10. Verification</label>
                    <?php if (count($sel_verification) > 0): ?>
                      <span class="text-[0.65rem] text-blue-300 font-semibold">(<?= count($sel_verification) ?>)</span>
                    <?php endif; ?>
                  </div>
                  <div class="space-y-1.5 pl-1">
                    <?php foreach ($filter_definitions['verification']['options'] as $opt): ?>
                      <label class="flex items-center gap-2 text-xs text-[#C4BCE3] hover:text-[#F1EEFA] cursor-pointer select-none">
                        <input
                          type="checkbox"
                          name="verification[]"
                          value="<?= htmlspecialchars($opt) ?>"
                          <?= in_array($opt, $sel_verification) ? 'checked' : '' ?>
                          class="rounded border-[#26243E] bg-[#0E0F1B] text-blue-500 focus:ring-blue-400"
                        />
                        <span class="<?= in_array($opt, $sel_verification) ? 'text-[#00F5FF] font-bold' : '' ?>"><?= htmlspecialchars($opt) ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- 11. Rating / Performance -->
                <div class="space-y-2 pb-2">
                  <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-amber-400 tracking-wider uppercase">11. Rating / Performance</label>
                    <?php if (count($sel_rating_perf) > 0): ?>
                      <span class="text-[0.65rem] text-amber-300 font-semibold">(<?= count($sel_rating_perf) ?>)</span>
                    <?php endif; ?>
                  </div>
                  <div class="space-y-1.5 pl-1">
                    <?php foreach ($filter_definitions['rating_perf']['options'] as $opt): ?>
                      <label class="flex items-center gap-2 text-xs text-[#C4BCE3] hover:text-[#F1EEFA] cursor-pointer select-none">
                        <input
                          type="checkbox"
                          name="rating_perf[]"
                          value="<?= htmlspecialchars($opt) ?>"
                          <?= in_array($opt, $sel_rating_perf) ? 'checked' : '' ?>
                          class="rounded border-[#26243E] bg-[#0E0F1B] text-amber-500 focus:ring-amber-400"
                        />
                        <span class="<?= in_array($opt, $sel_rating_perf) ? 'text-[#00F5FF] font-bold' : '' ?>"><?= htmlspecialchars($opt) ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- Bottom Filter Actions inside sidebar -->
                <div class="pt-4 border-t border-[#26243E] flex flex-col gap-2">
                  <button
                    type="submit"
                    class="w-full rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] py-2.5 text-xs font-bold text-white shadow-md hover:brightness-110 cursor-pointer"
                  >
                    Apply Filtered Results
                  </button>
                  <a
                    href="brand_creators.php"
                    class="w-full rounded-full border border-[#26243E] bg-[#0E0F1B] py-2 text-center text-xs font-medium text-[#A79DCB] hover:bg-[#16172B] hover:text-[#F1EEFA]"
                  >
                    Reset All Filters
                  </a>
                </div>

              </aside>

              <!-- Right: Creators Results Listing -->
              <div>
                <div class="mb-4 flex items-center justify-between text-xs text-[#A79DCB]">
                  <span>Found <b class="text-[#00F5FF] font-bold text-sm"><?= count($creators) ?></b> creators matching your criteria</span>
                  <span class="hidden sm:inline text-[#C4BCE3]">All creators verified for commercial projects</span>
                </div>

                <?php if (empty($creators)): ?>
                  <div class="rounded-[24px] border border-[#26243E] bg-[#16172B] p-12 text-center text-[#A79DCB]">
                    <span class="text-5xl">🔍</span>
                    <h3 class="mt-4 text-xl font-bold text-[#F1EEFA]">No creators matched your filters</h3>
                    <p class="mt-2 text-sm text-[#C4BCE3] max-w-md mx-auto">Try relaxing your budget, content type, or tool filters to discover more matching talent.</p>
                    <a href="brand_creators.php" class="mt-5 inline-block rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-6 py-2.5 text-xs font-bold text-white shadow-md hover:brightness-110">
                      Reset All Filters
                    </a>
                  </div>
                <?php else: ?>
                  <div class="grid gap-5 md:grid-cols-2">
                    <?php foreach ($creators as $creator): ?>
                      <?php
                        $skills = array_filter(array_map('trim', explode(',', $creator['skills'] ?? '')));
                        $tools = array_filter(array_map('trim', explode(',', $creator['tools'] ?? '')));
                        $formats = array_filter(array_map('trim', explode(',', $creator['aspect_ratios'] ?? '16:9, 9:16')));
                        $c_types = array_filter(array_map('trim', explode(',', $creator['content_types'] ?? 'Video')));
                        $v_tags = array_filter(array_map('trim', explode(',', $creator['verification_tags'] ?? 'Portfolio Verified')));
                        $c_avatar = !empty($creator['avatar_url']) ? $creator['avatar_url'] : 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=500&q=80';
                        
                        // Availability status indicator color
                        $avail_status = $creator['availability'] ?? 'Available Now';
                        $status_dot_class = 'bg-emerald-400';
                        if (stripos($avail_status, 'This Week') !== false) {
                            $status_dot_class = 'bg-amber-400';
                        } elseif (stripos($avail_status, 'Booked') !== false) {
                            $status_dot_class = 'bg-slate-500';
                        }
                      ?>
                      <article class="rounded-[24px] border border-[#26243E] bg-[#0E0F1B] p-5 shadow-sm flex flex-col justify-between transition hover:border-[#00F5FF]/50 hover:shadow-[0_0_25px_rgba(0,245,255,0.15)]">
                        <div>
                          <!-- Header with Avatar and Basic Info -->
                          <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                              <img src="<?= htmlspecialchars($c_avatar) ?>" alt="<?= htmlspecialchars($creator['full_name']) ?>" class="h-14 w-14 rounded-full object-cover ring-2 ring-purple-500/40 shadow-sm" />
                              <div>
                                <div class="flex items-center gap-2">
                                  <h3 class="text-base font-bold text-[#F1EEFA]"><?= htmlspecialchars($creator['full_name']) ?></h3>
                                  <?php if ($creator['verified']): ?>
                                    <span class="text-sm text-[#00F5FF]" title="Verified Creator">✓</span>
                                  <?php endif; ?>
                                </div>
                                <p class="text-xs text-cyan-300 font-semibold"><?= htmlspecialchars($creator['specialization'] ?: $creator['role_title']) ?></p>
                                
                                <!-- Availability Badge with Dot -->
                                <div class="mt-1 flex items-center gap-1.5 text-[0.68rem] text-[#C4BCE3]">
                                  <span class="h-2 w-2 rounded-full <?= $status_dot_class ?> animate-pulse"></span>
                                  <span><?= htmlspecialchars($avail_status) ?></span>
                                  <span>•</span>
                                  <span class="text-cyan-400 font-medium"><?= htmlspecialchars($creator['experience_level'] ?? 'Expert') ?></span>
                                </div>
                              </div>
                            </div>

                            <!-- Pricing Badge -->
                            <div class="text-right">
                              <span class="inline-block rounded-full bg-emerald-950/50 text-emerald-300 text-xs font-bold px-2.5 py-1 border border-emerald-500/30">
                                <?= htmlspecialchars($creator['hourly_rate']) ?>
                              </span>
                              <div class="mt-1 text-[0.62rem] text-[#A79DCB]">
                                Tier: <?= htmlspecialchars($creator['budget_tier'] ?? '₹25,000–₹50,000') ?>
                              </div>
                            </div>
                          </div>

                          <!-- Bio snippet -->
                          <?php if (!empty($creator['bio'])): ?>
                            <p class="mt-3 text-xs text-[#C4BCE3] line-clamp-2 leading-relaxed"><?= htmlspecialchars($creator['bio']) ?></p>
                          <?php endif; ?>

                          <!-- Ratings & Stats -->
                          <div class="mt-3 flex items-center justify-between text-xs text-[#A79DCB] pt-2 border-t border-[#26243E]">
                            <div class="flex items-center gap-1.5">
                              <span class="text-[#f59e0b]">★</span>
                              <span class="font-bold text-[#F1EEFA]"><?= htmlspecialchars($creator['rating']) ?></span>
                              <span class="text-[#A79DCB]">(<?= (int)$creator['reviews_count'] ?> reviews)</span>
                            </div>
                            <span class="text-[#A79DCB] font-medium"><?= (int)$creator['projects_count'] ?> projects completed</span>
                          </div>

                          <!-- Formats & Content Types Badges -->
                          <div class="mt-3 flex flex-wrap gap-1.5 items-center">
                            <span class="text-[0.62rem] text-[#A79DCB] font-semibold mr-1">Formats:</span>
                            <?php foreach ($formats as $fmt): ?>
                              <span class="rounded-md border border-amber-500/30 bg-amber-950/40 px-1.5 py-0.5 text-[0.62rem] font-bold text-amber-300">
                                <?= htmlspecialchars($fmt) ?>
                              </span>
                            <?php endforeach; ?>

                            <?php foreach (array_slice($c_types, 0, 2) as $ctype): ?>
                              <span class="rounded-md border border-pink-500/30 bg-pink-950/40 px-1.5 py-0.5 text-[0.62rem] text-pink-300">
                                <?= htmlspecialchars($ctype) ?>
                              </span>
                            <?php endforeach; ?>
                          </div>

                          <!-- Tools & Skills Badges -->
                          <div class="mt-2.5 flex flex-wrap gap-1.5">
                            <?php foreach (array_slice($tools, 0, 2) as $tool): ?>
                              <span class="rounded-full border border-cyan-500/30 bg-cyan-950/40 px-2 py-0.5 text-[0.65rem] text-cyan-300 font-medium">
                                ⚡ <?= htmlspecialchars($tool) ?>
                              </span>
                            <?php endforeach; ?>
                            <?php foreach (array_slice($skills, 0, 2) as $skill): ?>
                              <span class="rounded-full border border-purple-500/30 bg-purple-950/40 px-2 py-0.5 text-[0.65rem] text-purple-300">
                                <?= htmlspecialchars($skill) ?>
                              </span>
                            <?php endforeach; ?>
                          </div>

                          <!-- Verification Badges -->
                          <?php if (!empty($v_tags)): ?>
                            <div class="mt-2.5 flex flex-wrap gap-1">
                              <?php foreach (array_slice($v_tags, 0, 2) as $vtag): ?>
                                <span class="rounded border border-blue-500/30 bg-blue-950/40 px-1.5 py-0.5 text-[0.6rem] text-blue-300">
                                  ✓ <?= htmlspecialchars($vtag) ?>
                                </span>
                              <?php endforeach; ?>
                            </div>
                          <?php endif; ?>

                        </div>

                        <!-- Action Button -->
                        <a
                          href="creator_view.php?id=<?= $creator['id'] ?>"
                          class="mt-5 block w-full rounded-full bg-gradient-to-r from-[#06b6d4] via-[#8b5cf6] to-[#ec4899] px-4 py-2.5 text-center text-xs font-semibold text-white shadow-sm transition hover:brightness-110"
                        >
                          View Profile &amp; 30s Ad Demos →
                        </a>
                      </article>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>

            </div>
          </form>
          <!-- Filter Form Ends Here -->

        </div>
      </main>
    </div>
  </div>

  <!-- Floating AI Assistant Chatbot -->
  <?php require_once __DIR__ . '/includes/chatbot.php'; ?>

</body>
</html>
