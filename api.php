<?php
/**
 * ============================================
 * bidernet group - Backend API (v2.3.3-php)
 * ============================================
 * Single-file PHP API with all endpoints needed
 * by the React frontend.
 *
 * CONFIGURATION:
 * Update the database credentials in config.php
 * (separate file - do NOT commit credentials to Git)
 * ============================================
 */

// ----- CORS / Headers -----
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Max-Age: 86400');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ----- Load Database Config -----
$configFile = __DIR__ . '/config.php';
if (!file_exists($configFile)) {
    http_response_code(500);
    echo json_encode(['error' => 'Missing config.php file. Please create it with database credentials.']);
    exit;
}
require_once $configFile;

// ----- Database Connection -----
try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed', 'detail' => $e->getMessage()]);
    exit;
}

// ----- Parse Request -----
$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';
$input = json_decode(file_get_contents('php://input'), true) ?? [];

// ----- Response Helper -----
function respond($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function error($msg, $code = 400) {
    respond(['error' => $msg], $code);
}

// ============================================
// ROUTES
// ============================================

try {
    switch ($action) {

        // ============== USERS ==============
        case 'users':
            if ($method === 'GET') {
                $rows = $pdo->query("SELECT * FROM users ORDER BY createdAt DESC")->fetchAll();
                foreach ($rows as &$row) {
                    if ($row['packageData']) {
                        $row['package'] = json_decode($row['packageData'], true);
                    }
                    unset($row['packageData']);
                }
                respond($rows);
            }
            if ($method === 'POST') {
                $id = $input['id'] ?? uniqid('user_', true);
                $packageData = isset($input['package']) ? json_encode($input['package'], JSON_UNESCAPED_UNICODE) : null;
                $stmt = $pdo->prepare("
                    INSERT INTO users (id, username, password, name, email, phone, role, businessName, logoData, logoName, shareToken, packageData, packageSize, packagePeriod)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                      password=VALUES(password), name=VALUES(name), email=VALUES(email),
                      phone=VALUES(phone), role=VALUES(role), businessName=VALUES(businessName),
                      logoData=VALUES(logoData), logoName=VALUES(logoName),
                      shareToken=VALUES(shareToken), packageData=VALUES(packageData),
                      packageSize=VALUES(packageSize), packagePeriod=VALUES(packagePeriod)
                ");
                $stmt->execute([
                    $id,
                    $input['username'],
                    $input['password'] ?? '',
                    $input['name'] ?? '',
                    $input['email'] ?? null,
                    $input['phone'] ?? null,
                    $input['role'] ?? 'client',
                    $input['businessName'] ?? null,
                    $input['logoData'] ?? null,
                    $input['logoName'] ?? null,
                    $input['shareToken'] ?? null,
                    $packageData,
                    $input['packageSize'] ?? null,
                    $input['packagePeriod'] ?? null
                ]);
                respond(['ok' => true, 'id' => $id]);
            }
            if ($method === 'DELETE') {
                $id = $_GET['id'] ?? '';
                if (!$id) error('Missing id');
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$id]);
                respond(['ok' => true]);
            }
            break;

        // ============== LOGIN ==============
        case 'login':
            if ($method !== 'POST') error('POST required');
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ? LIMIT 1");
            $stmt->execute([$input['username'] ?? '', $input['password'] ?? '']);
            $user = $stmt->fetch();
            if (!$user) error('שם משתמש או סיסמה שגויים', 401);
            if ($user['packageData']) {
                $user['package'] = json_decode($user['packageData'], true);
            }
            unset($user['packageData']);
            respond($user);
            break;

        // ============== SHARE LINK LOOKUP ==============
        case 'share':
            if ($method !== 'GET') error('GET required');
            $token = $_GET['token'] ?? '';
            if (!$token) error('Missing token');
            $stmt = $pdo->prepare("SELECT * FROM users WHERE shareToken = ? AND role = 'client' LIMIT 1");
            $stmt->execute([$token]);
            $user = $stmt->fetch();
            if (!$user) error('הקישור אינו תקף', 404);
            if ($user['packageData']) {
                $user['package'] = json_decode($user['packageData'], true);
            }
            unset($user['packageData']);
            respond($user);
            break;

        // ============== POSTS ==============
        case 'posts':
            if ($method === 'GET') {
                $business = $_GET['business'] ?? '';
                if ($business) {
                    $stmt = $pdo->prepare("SELECT * FROM posts WHERE businessName = ? ORDER BY date DESC, time DESC");
                    $stmt->execute([$business]);
                } else {
                    $stmt = $pdo->query("SELECT * FROM posts ORDER BY date DESC, time DESC");
                }
                $rows = $stmt->fetchAll();
                foreach ($rows as &$row) {
                    if ($row['platforms']) $row['platforms'] = json_decode($row['platforms'], true);
                    if ($row['clientApproval']) $row['clientApproval'] = json_decode($row['clientApproval'], true);
                    if ($row['chatMessages']) $row['chatMessages'] = json_decode($row['chatMessages'], true);
                    if ($row['editHistory']) $row['editHistory'] = json_decode($row['editHistory'], true);
                    if ($row['publishedTo']) $row['publishedTo'] = json_decode($row['publishedTo'], true);
                }
                respond($rows);
            }
            if ($method === 'POST') {
                $id = $input['id'] ?? uniqid('post_', true);
                $platforms = isset($input['platforms']) ? json_encode($input['platforms'], JSON_UNESCAPED_UNICODE) : null;
                $approval = isset($input['clientApproval']) ? json_encode($input['clientApproval'], JSON_UNESCAPED_UNICODE) : null;
                $chatMessages = isset($input['chatMessages']) ? json_encode($input['chatMessages'], JSON_UNESCAPED_UNICODE) : null;
                $editHistory = isset($input['editHistory']) ? json_encode($input['editHistory'], JSON_UNESCAPED_UNICODE) : null;
                $publishedTo = isset($input['publishedTo']) ? json_encode($input['publishedTo'], JSON_UNESCAPED_UNICODE) : null;

                $stmt = $pdo->prepare("
                    INSERT INTO posts (id, businessName, title, content, date, time, platforms,
                                       mediaUrl, mediaData, mediaName, mediaType, category,
                                       status, clientApproval, publishStatus, publishedAt, publishedTo,
                                       chatMessages, editHistory, createdBy)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                      businessName=VALUES(businessName), title=VALUES(title), content=VALUES(content),
                      date=VALUES(date), time=VALUES(time), platforms=VALUES(platforms),
                      mediaUrl=VALUES(mediaUrl), mediaData=VALUES(mediaData),
                      mediaName=VALUES(mediaName), mediaType=VALUES(mediaType),
                      category=VALUES(category), status=VALUES(status),
                      clientApproval=VALUES(clientApproval), publishStatus=VALUES(publishStatus),
                      publishedAt=VALUES(publishedAt), publishedTo=VALUES(publishedTo),
                      chatMessages=VALUES(chatMessages), editHistory=VALUES(editHistory)
                ");
                $stmt->execute([
                    $id,
                    $input['businessName'] ?? '',
                    $input['title'] ?? null,
                    $input['content'] ?? null,
                    $input['date'] ?? null,
                    $input['time'] ?? null,
                    $platforms,
                    $input['mediaUrl'] ?? null,
                    $input['mediaData'] ?? null,
                    $input['mediaName'] ?? null,
                    $input['mediaType'] ?? null,
                    $input['category'] ?? null,
                    $input['status'] ?? 'draft',
                    $approval,
                    $input['publishStatus'] ?? null,
                    $input['publishedAt'] ?? null,
                    $publishedTo,
                    $chatMessages,
                    $editHistory,
                    $input['createdBy'] ?? null
                ]);
                respond(['ok' => true, 'id' => $id]);
            }
            if ($method === 'DELETE') {
                $id = $_GET['id'] ?? '';
                if (!$id) error('Missing id');
                $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
                $stmt->execute([$id]);
                respond(['ok' => true]);
            }
            break;

        // ============== BRANDING ==============
        case 'branding':
            if ($method === 'GET') {
                $row = $pdo->query("SELECT * FROM branding WHERE id = 1 LIMIT 1")->fetch();
                if ($row && $row['extraData']) {
                    $extra = json_decode($row['extraData'], true);
                    if ($extra) $row = array_merge($row, $extra);
                }
                respond($row ?: []);
            }
            if ($method === 'POST') {
                // Known columns
                $known = ['companyName','tagline','logoData','primaryColor','secondaryColor','loginWelcome','theme'];
                $extra = [];
                foreach ($input as $k => $v) {
                    if (!in_array($k, $known)) $extra[$k] = $v;
                }
                $stmt = $pdo->prepare("
                    INSERT INTO branding (id, companyName, tagline, logoData, primaryColor, secondaryColor, loginWelcome, theme, extraData)
                    VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                      companyName=VALUES(companyName), tagline=VALUES(tagline), logoData=VALUES(logoData),
                      primaryColor=VALUES(primaryColor), secondaryColor=VALUES(secondaryColor),
                      loginWelcome=VALUES(loginWelcome), theme=VALUES(theme), extraData=VALUES(extraData)
                ");
                $stmt->execute([
                    $input['companyName'] ?? null,
                    $input['tagline'] ?? null,
                    $input['logoData'] ?? null,
                    $input['primaryColor'] ?? null,
                    $input['secondaryColor'] ?? null,
                    $input['loginWelcome'] ?? null,
                    $input['theme'] ?? null,
                    $extra ? json_encode($extra, JSON_UNESCAPED_UNICODE) : null
                ]);
                respond(['ok' => true]);
            }
            break;

        // ============== TEAM CHAT ==============
        case 'team_chat':
            if ($method === 'GET') {
                $rows = $pdo->query("SELECT * FROM team_chat ORDER BY createdAt ASC")->fetchAll();
                respond($rows);
            }
            if ($method === 'POST') {
                $id = $input['id'] ?? uniqid('tc_', true);
                $stmt = $pdo->prepare("
                    INSERT INTO team_chat (id, senderUsername, senderName, message, editedAt)
                    VALUES (?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                      message=VALUES(message), editedAt=VALUES(editedAt)
                ");
                $stmt->execute([
                    $id,
                    $input['senderUsername'] ?? '',
                    $input['senderName'] ?? '',
                    $input['message'] ?? '',
                    $input['editedAt'] ?? null
                ]);
                respond(['ok' => true, 'id' => $id]);
            }
            if ($method === 'DELETE') {
                $id = $_GET['id'] ?? '';
                if (!$id) error('Missing id');
                $stmt = $pdo->prepare("DELETE FROM team_chat WHERE id = ?");
                $stmt->execute([$id]);
                respond(['ok' => true]);
            }
            break;

        // ============== TEMPLATES ==============
        case 'templates':
            if ($method === 'GET') {
                $rows = $pdo->query("SELECT * FROM templates ORDER BY createdAt DESC")->fetchAll();
                foreach ($rows as &$row) {
                    if ($row['platforms']) $row['platforms'] = json_decode($row['platforms'], true);
                }
                respond($rows);
            }
            if ($method === 'POST') {
                $id = $input['id'] ?? uniqid('tpl_', true);
                $platforms = isset($input['platforms']) ? json_encode($input['platforms'], JSON_UNESCAPED_UNICODE) : null;
                $stmt = $pdo->prepare("
                    INSERT INTO templates (id, name, content, platforms)
                    VALUES (?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE name=VALUES(name), content=VALUES(content), platforms=VALUES(platforms)
                ");
                $stmt->execute([$id, $input['name'] ?? '', $input['content'] ?? null, $platforms]);
                respond(['ok' => true, 'id' => $id]);
            }
            if ($method === 'DELETE') {
                $id = $_GET['id'] ?? '';
                if (!$id) error('Missing id');
                $stmt = $pdo->prepare("DELETE FROM templates WHERE id = ?");
                $stmt->execute([$id]);
                respond(['ok' => true]);
            }
            break;

        // ============== GANTT APPROVALS (חדש!) ==============
        case 'gantt_approvals':
            if ($method === 'GET') {
                $rows = $pdo->query("SELECT * FROM gantt_approvals ORDER BY updatedAt DESC")->fetchAll();
                // Convert array to dictionary keyed by businessName
                $result = [];
                foreach ($rows as $row) {
                    $biz = $row['businessName'];
                    unset($row['businessName']);
                    $result[$biz] = $row;
                }
                respond($result);
            }
            if ($method === 'POST') {
                // Expects: { businessName, status, comment, approvedAt, approvedBy, postCount }
                // OR a dictionary { "BusinessA": {...}, "BusinessB": {...} }
                if (isset($input['businessName'])) {
                    // Single approval
                    $entries = [$input];
                } else {
                    // Dictionary - convert
                    $entries = [];
                    foreach ($input as $biz => $approval) {
                        $entries[] = array_merge(['businessName' => $biz], (array)$approval);
                    }
                }
                $stmt = $pdo->prepare("
                    INSERT INTO gantt_approvals (businessName, status, comment, approvedAt, approvedBy, postCount)
                    VALUES (?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                      status=VALUES(status), comment=VALUES(comment),
                      approvedAt=VALUES(approvedAt), approvedBy=VALUES(approvedBy),
                      postCount=VALUES(postCount)
                ");
                foreach ($entries as $entry) {
                    if (!isset($entry['businessName'])) continue;
                    $stmt->execute([
                        $entry['businessName'],
                        $entry['status'] ?? null,
                        $entry['comment'] ?? null,
                        $entry['approvedAt'] ?? null,
                        $entry['approvedBy'] ?? null,
                        $entry['postCount'] ?? null
                    ]);
                }
                respond(['ok' => true]);
            }
            if ($method === 'DELETE') {
                $biz = $_GET['businessName'] ?? '';
                if (!$biz) error('Missing businessName');
                $stmt = $pdo->prepare("DELETE FROM gantt_approvals WHERE businessName = ?");
                $stmt->execute([$biz]);
                respond(['ok' => true]);
            }
            break;

        // ============== PING (test endpoint) ==============
        case 'ping':
            respond(['ok' => true, 'message' => 'pong', 'time' => date('c'), 'db' => 'connected', 'version' => 'v2.4.1-php']);
            break;

        default:
            error('Unknown action. Valid: users, login, share, posts, branding, team_chat, templates, gantt_approvals, ping', 404);
    }
} catch (PDOException $e) {
    error('Database error: ' . $e->getMessage(), 500);
} catch (Exception $e) {
    error('Server error: ' . $e->getMessage(), 500);
}
