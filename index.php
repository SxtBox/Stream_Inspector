<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>TRC4 Broadcast Stream Inspector</title>
<link rel="shortcut icon" href="https://kodi.al/favicon.ico">
<link rel="stylesheet" href="assets/css/trc4.css">
</head>
<body>

<!--
==========================================================
TRC4 NEON HEADER
==========================================================
-->
<!---->
<header class="trc4-header">
    <div class="logo">
    <span class="neon">⚡ TRC4</span>
    <span>Stream Inspector</span>
    </div>
	<!----/>
    <div class="version">V1.0</div>
	<!---->
	<div class="trc4-version">V1.0</div>
</header>
<!---->

<!--======================================================
TRC4 HEADER
=======================================================-->
<!----/>
<header class="trc4-header">
    <div class="trc4-logo">⚡ TRC4 Broadcast
    <span>Stream Inspector</span>
    </div>
    <div class="trc4-version">V1.0</div>
</header>
<!---->
<!--======================================================
MAIN
=======================================================-->

<div class="trc4-container">
    <div class="trc4-card">
    <h2>Paste One or More M3U8 URLs</h2>
    <p>One URL Per Line</p>
    <textarea id="urls" spellcheck="false" placeholder="https://example.com/live/index.m3u8" value="https://abr.de1se01.v2beat.live/playlist.m3u8"></textarea>

<div class="trc4-actions">
<button id="checkBtn">▶ CHECK</button>
<button id="clearBtn">🗑 CLEAR</button>
<button id="copyBtn">📋 COPY</button>
<button id="exportTxt">📄 TXT</button>
<button id="exportJson">🟢 JSON</button>
<button id="exportCsv">📊 CSV</button>
</div>

<div class="trc4-tools">
<input id="searchBox" placeholder="🔍 Search Stream...">

<select id="filterStatus">
<option value="all">All</option>
<option value="live">Live</option>
<option value="offline">Offline</option>
<option value="redirect">Redirect</option>
</select>

<select id="sortBox">
<option value="none">Sort</option>
<option value="speed">Speed</option>
<option value="http">HTTP</option>
</select>

</div>
</div>
</div>

<!--======================================================
TRC4 STATISTICS OLD STYLE
=======================================================-->
<!----/>
<div class="trc4-dashboard">

    <div class="stat">
        <span id="totalCount">0</span>
        <small>Total</small>
    </div>

    <div class="stat">
        <span id="liveCount">0</span>
        <small>Live</small>
    </div>

    <div class="stat">
        <span id="offlineCount">0</span>
        <small>Offline</small>

    </div>
    <div class="stat">
        <span id="corsCount">0</span>
        <small>CORS</small>
    </div>
</div>
<!---->

<!--
==========================================================
TRC4 STATISTICS NEW MODAL STYLE
==========================================================
-->

<section class="stats-panel">
<div class="stat-card">
<span>TOTAL</span>
<strong id="totalCount">0</strong>
</div>

<div class="stat-card live">
<span>LIVE</span>
<strong id="liveCount">0</strong>
</div>

<div class="stat-card offline">
<span>OFFLINE</span>
<strong id="offlineCount">0</strong>
</div>

<div class="stat-card cors">
<span>CORS</span>
<strong id="corsCount">0</strong>
</div>
</section>

<!--======================================================
RESULTS
=======================================================-->

<div class="trc4-results" id="results">
<div class="empty">
⚡
<br>
<br>
No Streams Checked Yet
</div>
</div>

<!--======================================================
LOADER
=======================================================-->

<div id="loader" class="trc4-loader">
<div class="spinner"></div>
<div>Checking Streams...</div>
</div>

<!--======================================================
HINT
=======================================================-->

<div id="hint" class="trc4-hint"> Ready</div>

<!--======================================================
DETAIL MODAL
=======================================================-->

<div id="resultModal" class="trc4-modal">
<div class="trc4-modal-box">
<div class="modal-title">Stream Details</div>

<div id="modalBody">Loading...</div>
<div class="modal-buttons">
<button id="copyDetails">📋 Copy</button>
<button id="closeModal">✖ Close</button>
</div>
</div>
</div>

<!--======================================================
FOOTER
=======================================================-->
<footer class="trc4-footer">
<div>
⚡ TRC4 Broadcast Inspector UI Neon
</div>

<div>
Stream Engine Inspector
</div>
<!----/>
<div>© 2026 TRC4</div>
<!---->
<p>&copy; TRC4 <?php echo (date('Y') - 1)." - ".date('Y'); ?></p>
</footer>
<script src="assets/js/trc4.js"></script>
</body>
</html>