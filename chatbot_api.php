<?php
// chatbot_api.php - AI Creator Marketplace Intelligent Assistant API
require_once __DIR__ . '/config/db.php';

header('Content-Type: application/json; charset=utf-8');

// Read JSON input or POST form data
$input_raw = file_get_contents('php://input');
$data = json_decode($input_raw, true) ?: $_POST;
$message = trim($data['message'] ?? '');

if (empty($message)) {
    echo json_encode([
        'reply' => "I didn't catch that. Could you please ask a question or pick one of the quick suggestions?",
        'creators' => [],
        'suggestions' => ['Recommend creators', '30s Ad requirements', 'Pricing & Budgets', 'Filter by AI Tools']
    ]);
    exit;
}

$db = get_db();
$msg_lower = strtolower($message);

$reply = "";
$creators = [];
$suggestions = [];

// Helper function to query creators from MySQL
function search_creators(PDO $db, string $term, int $limit = 3): array {
    $stmt = $db->prepare("
        SELECT c.*, u.full_name, u.avatar_url, u.email 
        FROM creator_profiles c 
        JOIN users u ON c.user_id = u.id 
        WHERE u.full_name LIKE :t1 
           OR c.role_title LIKE :t2 
           OR c.specialization LIKE :t3 
           OR c.skills LIKE :t4 
           OR c.tools LIKE :t5 
           OR c.content_types LIKE :t6 
           OR c.bio LIKE :t7
        ORDER BY c.rating DESC, c.projects_count DESC
        LIMIT :lim
    ");
    $term_like = '%' . $term . '%';
    $stmt->bindValue(':t1', $term_like, PDO::PARAM_STR);
    $stmt->bindValue(':t2', $term_like, PDO::PARAM_STR);
    $stmt->bindValue(':t3', $term_like, PDO::PARAM_STR);
    $stmt->bindValue(':t4', $term_like, PDO::PARAM_STR);
    $stmt->bindValue(':t5', $term_like, PDO::PARAM_STR);
    $stmt->bindValue(':t6', $term_like, PDO::PARAM_STR);
    $stmt->bindValue(':t7', $term_like, PDO::PARAM_STR);
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// 1. Creator Recommendations & Search
if (
    preg_match('/\b(recommend|find|search|suggest|look for|who can|creator|artist|filmmaker|animator|designer)\b/i', $message) ||
    preg_match('/\b(runway|kling|midjourney|flux|sora|veo|comfyui|elevenlabs|firefly)\b/i', $message) ||
    preg_match('/\b(video|ad|commercial|animation|3d|image|motion)\b/i', $message)
) {
    // Detect search keywords
    $term = '';
    if (stripos($msg_lower, 'video') !== false || stripos($msg_lower, 'ad') !== false || stripos($msg_lower, 'commercial') !== false) {
        $term = 'Video';
    } elseif (stripos($msg_lower, 'animation') !== false || stripos($msg_lower, 'animator') !== false) {
        $term = 'Animation';
    } elseif (stripos($msg_lower, 'filmmaker') !== false || stripos($msg_lower, 'film') !== false) {
        $term = 'Filmmaker';
    } elseif (stripos($msg_lower, 'midjourney') !== false) {
        $term = 'Midjourney';
    } elseif (stripos($msg_lower, 'runway') !== false) {
        $term = 'Runway';
    } elseif (stripos($msg_lower, 'kling') !== false) {
        $term = 'Kling';
    } elseif (stripos($msg_lower, 'flux') !== false) {
        $term = 'Flux';
    } elseif (stripos($msg_lower, 'motion') !== false) {
        $term = 'Motion';
    } elseif (stripos($msg_lower, '3d') !== false) {
        $term = '3D';
    } elseif (stripos($msg_lower, 'available') !== false) {
        $term = 'Available Now';
    } else {
        $term = 'Video';
    }

    $creators = search_creators($db, $term, 3);
    if (!empty($creators)) {
        $reply = "Here are top verified AI creators matching **'{$term}'** from our database! You can view their full profiles, verified badges, and 30-second ad video demonstrations:";
        $suggestions = ['Show available now', 'Filter by Budget', 'How to post a brief', 'Tell me about 30s ads'];
    }
}

// 2. 30-Second Ad Video Information
if (empty($reply) && (stripos($msg_lower, '30s') !== false || stripos($msg_lower, '30 second') !== false || stripos($msg_lower, 'video ad') !== false || stripos($msg_lower, 'advertisement') !== false)) {
    $reply = "🎬 **About 30-Second Advertisement Videos:**\n\n"
           . "• **Duration Rule:** Our platform enforces a maximum duration of **30 seconds** for advertisement showcase reels, validated automatically on upload.\n"
           . "• **Formats Supported:** High-definition MP4 and WebM videos in **16:9** (landscape / YouTube / TV) and **9:16** (vertical / Instagram Reels / TikTok).\n"
           . "• **Purpose:** High-impact, viral-ready creative showcasing AI camera motion, synthetic actors, voiceovers, and product lighting.\n"
           . "• Creators can upload their 30s ad portfolio via the Creator Dashboard, and Brands can post briefs specifically for 30s campaigns!";
    $suggestions = ['Recommend video creators', 'Upload 30s ad video', 'Create a brand brief', 'Pricing in INR'];
}

// 3. Budget & Pricing Information (INR)
elseif (empty($reply) && (stripos($msg_lower, 'budget') !== false || stripos($msg_lower, 'price') !== false || stripos($msg_lower, 'rate') !== false || stripos($msg_lower, 'cost') !== false || stripos($msg_lower, 'inr') !== false || stripos($msg_lower, 'rupee') !== false || stripos($msg_lower, '₹') !== false)) {
    $reply = "💰 **Budget & Pricing Tiers (INR):**\n\n"
           . "Our marketplace categorizes creator packages into 5 transparent budget tiers:\n"
           . "1. **Under ₹10,000:** Ideal for single concept images, social snippets, or trial reels.\n"
           . "2. **₹10,000 – ₹25,000:** High-converting vertical reels, motion posters, and e-commerce ads.\n"
           . "3. **₹25,000 – ₹50,000:** Complete 30-second commercial with AI voiceover, color grading, and SFX.\n"
           . "4. **₹50,000 – ₹1,00,000:** Multi-scene 3D animations, custom character design, and VFX commercials.\n"
           . "5. **₹1,00,000+:** Full-scale cinematic brand anthems, TVC campaigns, and exclusive rights.\n\n"
           . "You can filter by exact budget on the **Brand Filters** directory!";
    $suggestions = ['Find creators under ₹10,000', 'Find creators ₹25k-₹50k', 'Commercial rights info', 'Explore creators'];
}

// 4. Commercial Rights & Licensing
elseif (empty($reply) && (stripos($msg_lower, 'commercial') !== false || stripos($msg_lower, 'right') !== false || stripos($msg_lower, 'license') !== false || stripos($msg_lower, 'exclusive') !== false || stripos($msg_lower, 'copyright') !== false)) {
    $reply = "⚖️ **Commercial Rights & Licensing Guide:**\n\n"
           . "Every project on AICreators clarifies commercial usage upfront:\n"
           . "• **Commercial Use:** Full permission to use the generated output for brand websites, email, and organic social media.\n"
           . "• **Paid Advertising:** Certified rights to run the 30s video in Meta, Google, TikTok, and OTT ad campaigns.\n"
           . "• **Licensing Available:** Tiered commercial license options depending on campaign scale.\n"
           . "• **Exclusive Rights:** Complete exclusive ownership of generated prompts, assets, and final render.\n\n"
           . "Look for the **'Commercial Ready'** badge on creator profiles!";
    $suggestions = ['Find Exclusive Rights creators', 'How to hire a creator', 'Explore Brand Filters'];
}

// 5. How to Post a Brief / Hire
elseif (empty($reply) && (stripos($msg_lower, 'brief') !== false || stripos($msg_lower, 'hire') !== false || stripos($msg_lower, 'campaign') !== false || stripos($msg_lower, 'post') !== false)) {
    $reply = "📝 **How Brands Hire Creators:**\n\n"
           . "1. **Create a Campaign Brief:** Go to **Create Brief** from your Brand Dashboard, enter your campaign vision, target platform (Instagram, TikTok, YouTube), format (16:9, 9:16), and budget.\n"
           . "2. **Direct Proposal:** Browse the **Search Creators** directory, click on any creator's profile, and send a direct proposal for your 30s ad!\n"
           . "3. **Creator Review:** Verified creators submit their proposals, custom pitches, and estimated timelines.";
    $suggestions = ['Post a Brief now', 'Search Creators', 'Recommend top creators'];
}

// 6. Supported AI Tools & Models
elseif (empty($reply) && (stripos($msg_lower, 'tool') !== false || stripos($msg_lower, 'model') !== false || stripos($msg_lower, 'software') !== false || stripos($msg_lower, 'ai') !== false && (stripos($msg_lower, 'support') !== false || stripos($msg_lower, 'what') !== false))) {
    $reply = "⚡ **Supported AI Tools & Generative Models:**\n\n"
           . "Our creators work with state-of-the-art tools:\n"
           . "• **Video & Motion:** Runway Gen-3, Kling AI, OpenAI Sora, Google Veo, Luma Dream Machine\n"
           . "• **Image Generation:** Midjourney v6, Flux.1, Stable Diffusion XL, Adobe Firefly\n"
           . "• **VFX & Workflow:** ComfyUI custom nodes, Blender, After Effects\n"
           . "• **Audio & Voice:** ElevenLabs AI voice synthesis, Suno, Udio\n\n"
           . "You can filter creators specifically by the AI models they master!";
    $suggestions = ['Show Runway creators', 'Show Midjourney artists', 'Filter by AI Tools'];
}

// 7. General Help / Greeting
elseif (empty($reply) && (stripos($msg_lower, 'hello') !== false || stripos($msg_lower, 'hi') !== false || stripos($msg_lower, 'hey') !== false || stripos($msg_lower, 'start') !== false)) {
    $reply = "👋 **Welcome to AICreators Assistant!**\n\n"
           . "I am your smart guide to discovering top AI talent and launching high-converting 30-second ad campaigns.\n\n"
           . "Here is what I can do for you:\n"
           . "• 🎬 **Recommend AI creators** for video ads, animation, or generative branding\n"
           . "• 💰 **Explain pricing tiers** and budget ranges in INR (₹)\n"
           . "• 📐 **Advise on aspect ratios** (16:9, 9:16, 1:1, 4:5)\n"
           . "• 📝 **Guide you to post a brief** or hire a creator directly\n"
           . "• ⚡ **Search by specific AI tools** (Runway, Kling, Midjourney, etc.)\n\n"
           . "How can I assist you right now?";
    $suggestions = ['Recommend video creators', 'Explain budget tiers', 'How to post a brief', 'Who is available now?'];
}

// 8. Fallback / Default
if (empty($reply)) {
    // Try to search database for any words in message
    $words = array_filter(explode(' ', preg_replace('/[^a-zA-Z0-9 ]/', '', $msg_lower)));
    $found_creators = [];
    foreach ($words as $w) {
        if (strlen($w) > 3) {
            $found_creators = search_creators($db, $w, 2);
            if (!empty($found_creators)) break;
        }
    }

    if (!empty($found_creators)) {
        $reply = "I found verified creators that might match what you're looking for:";
        $creators = $found_creators;
        $suggestions = ['View all creators', 'Filter by Budget', '30s Ad requirements'];
    } else {
        $reply = "I understand you're asking about **" . htmlspecialchars($message) . "**.\n\n"
               . "You can explore our **12 Brand Filters** on the creators directory to find creators by Specialization, Skills, AI Tools (Runway, Kling, Midjourney), Aspect Ratio, Budget (INR), Availability, and Commercial Rights!\n\n"
               . "Would you like me to recommend top creators or help you post a brief?";
        $suggestions = ['Recommend top creators', 'Explain budget tiers', 'How to post a brief', 'Filter directory'];
    }
}

// Format creators array for JSON
$formatted_creators = [];
foreach ($creators as $c) {
    $formatted_creators[] = [
        'id' => (int)$c['id'],
        'name' => $c['full_name'],
        'role' => $c['specialization'] ?: $c['role_title'],
        'rate' => $c['hourly_rate'],
        'budget_tier' => $c['budget_tier'] ?? '₹25,000–₹50,000',
        'rating' => $c['rating'],
        'availability' => $c['availability'] ?? 'Available Now',
        'avatar' => $c['avatar_url'] ?: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80',
        'tools' => $c['tools'] ?? '',
        'link' => 'creator_view.php?id=' . $c['id']
    ];
}

echo json_encode([
    'reply' => $reply,
    'creators' => $formatted_creators,
    'suggestions' => $suggestions
], JSON_UNESCAPED_UNICODE);
