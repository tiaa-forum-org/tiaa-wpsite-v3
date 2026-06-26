<?php
/**
 * TIAA Forum v3 — Document Library
 * Version: 1.0.2
 * Updated: 2026-06-26 01:36 UTC
 *
 * Auto-lists dated subdirectories as document links.
 * Symlink-safe: derives base URL from DOCUMENT_ROOT + __DIR__
 * so paths resolve correctly even when symlinked at web root.
 *
 * URL pattern: /wp-content/plugins/tiaa-wpsite-v3/docs/style-guide/
 */

// Base URL: derive web path from physical __DIR__ via DOCUMENT_ROOT.
// Using REQUEST_URI dirname() breaks when the symlink is at web root
// (e.g. /style-guide.php -> dirname = "/", dropping the real subpath).
$doc_root  = realpath($_SERVER['DOCUMENT_ROOT']);
$phys_dir  = realpath(__DIR__);
$base_url  = rtrim(str_replace($doc_root, '', $phys_dir), '/');

// Scan for subdirectories, sort newest first
$dirs = array_filter(glob(__DIR__ . '/*'), 'is_dir');
usort($dirs, function($a, $b) {
    return strcmp(basename($b), basename($a));
});

function get_doc_meta($dir) {
    $name  = basename($dir);
    $files = glob($dir . '/*.*');
    $ext   = $files ? strtolower(pathinfo($files[0], PATHINFO_EXTENSION)) : 'html';
    $icons = [
        'html' => ['icon' => '🎨', 'class' => 'html', 'label' => 'HTML'],
        'pdf'  => ['icon' => '📄', 'class' => 'pdf',  'label' => 'PDF'],
        'docx' => ['icon' => '📝', 'class' => 'doc',  'label' => 'Word'],
        'doc'  => ['icon' => '📝', 'class' => 'doc',  'label' => 'Word'],
    ];
    $type = $icons[$ext] ?? ['icon' => '📁', 'class' => 'generic', 'label' => strtoupper($ext)];

    preg_match('/(\d{4}-\d{2}-\d{2})/', $name, $m);
    $date = $m[1] ?? '';

    $title = trim(preg_replace('/[-_]?\d{4}-\d{2}-\d{2}/', '', $name), '-_ ');
    $title = ucwords(str_replace(['-', '_'], ' ', $title));

    return [
        'name'  => $name,
        'title' => $title ?: $name,
        'date'  => $date,
        'type'  => $type,
        'entry' => 'index.html',
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TIAA Forum v3 — Document Library</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'Inter', sans-serif;
    background: #F8F9FA;
    color: #2B2E60;
    min-height: 100vh;
  }
  .page-header {
    background: #1E2050;
    color: white;
    padding: 40px 48px;
  }
  .page-header h1 { font-size: 26px; font-weight: 700; margin-bottom: 4px; }
  .page-header p  { font-size: 14px; color: #C8D5DC; margin-top: 4px; }
  .page-header .version {
    display: inline-block;
    margin-top: 14px;
    background: rgba(255,255,255,0.1);
    color: #C8D5DC;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.08em;
    padding: 3px 10px;
    border-radius: 100px;
    font-family: 'Courier New', monospace;
  }

  .content {
    max-width: 860px;
    margin: 40px auto;
    padding: 0 24px 80px;
  }

  .section { margin-bottom: 40px; }
  .section-title {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #31BBA6;
    margin-bottom: 12px;
  }

  .doc-list {
    background: white;
    border: 1px solid #E4E4ED;
    border-radius: 10px;
    overflow: hidden;
  }
  .doc-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #E4E4ED;
    text-decoration: none;
    color: inherit;
    transition: background 0.12s;
  }
  .doc-item:last-child { border-bottom: none; }
  .doc-item:hover { background: #F9FAFD; }

  .doc-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
    margin-right: 14px;
  }
  .doc-icon.html    { background: #e0f5f1; }
  .doc-icon.pdf     { background: #fef3f2; }
  .doc-icon.doc     { background: #eff6ff; }
  .doc-icon.generic { background: #f5f5f5; }

  .doc-info { flex: 1; min-width: 0; }
  .doc-name {
    font-size: 14px;
    font-weight: 600;
    color: #2B2E60;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .doc-meta { font-size: 12px; color: #6C757D; }

  .doc-badge {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 3px 8px;
    border-radius: 100px;
    background: #E9ECEF;
    color: #6C757D;
    margin-left: 12px;
    flex-shrink: 0;
  }
  .doc-arrow { color: #C8D5DC; font-size: 18px; margin-left: 12px; flex-shrink: 0; }
  .doc-left { display: flex; align-items: center; flex: 1; min-width: 0; }

  .empty-state {
    padding: 32px 20px;
    text-align: center;
    color: #6C757D;
    font-size: 14px;
  }

  .page-footer {
    text-align: center;
    font-size: 12px;
    color: #6C757D;
    padding: 24px;
    border-top: 1px solid #E4E4ED;
    margin-top: 40px;
  }
  .page-footer code {
    font-family: 'Courier New', monospace;
    font-size: 11px;
    background: #F8F9FA;
    padding: 2px 6px;
    border-radius: 4px;
  }
</style>
</head>
<body>

<div class="page-header">
  <h1>TIAA Forum v3 — Document Library</h1>
  <p>Style guides, references, and exported design documents</p>
  <p>tiaa-wpsite-v3 / docs/style-guide</p>
  <span class="version">v1.0.2 · 2026-06-26 01:36 UTC</span>
</div>

<div class="content">
  <div class="section">
    <div class="section-title">Documents (<?= count($dirs) ?>)</div>
    <div class="doc-list">
      <?php if (empty($dirs)): ?>
        <div class="empty-state">No documents found.</div>
      <?php else: ?>
        <?php foreach ($dirs as $dir):
          $meta = get_doc_meta($dir);
          $url  = $base_url . '/' . $meta['name'] . '/' . $meta['entry'];
        ?>
        <a class="doc-item" href="<?= htmlspecialchars($url) ?>">
          <div class="doc-left">
            <div class="doc-icon <?= $meta['type']['class'] ?>"><?= $meta['type']['icon'] ?></div>
            <div class="doc-info">
              <div class="doc-name"><?= htmlspecialchars($meta['title']) ?></div>
              <div class="doc-meta"><?= $meta['type']['label'] ?> · <?= htmlspecialchars($meta['date']) ?></div>
            </div>
          </div>
          <span class="doc-badge"><?= $meta['type']['label'] ?></span>
          <div class="doc-arrow">›</div>
        </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="page-footer">
  TIAA Forum v3 · Internal reference · Rendered <?= date('Y-m-d H:i:s T') ?>
  &nbsp;·&nbsp; <code><?= htmlspecialchars($base_url) ?></code>
</div>

</body>
</html>
