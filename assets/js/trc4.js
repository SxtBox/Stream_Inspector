/*
==========================================================
TRC4 Broadcast Stream Inspector
Hint • Loader • Modal • Copy • Export • UI Controller
JavaScript Core Engine
==========================================================

💚 TRC4 Hint()
💚 Loader()
💚 Open / Close Modal
💚 Copy Results
💚 Copy URL
💚 Export TXT
💚 Export CSV
💚 Export JSON
💚 Clear
💚 Paste
💚 Auto Statistics
💚 Dynamic Result Cards

✅ Hint System
✅ Loader Control
✅ Clear Button
✅ Copy Results
✅ URL Parsing
✅ AJAX Engine për check.php
✅ Result Cards Dynamic
✅ Statistics Counter
✅ Details Modal
✅ Copy Modal Data
✅ HTML Security Escape
✅ TRC4 Console Message
*/

let streamResults = [];

/*
==========================================================
ELEMENT HELPERS
==========================================================
*/

function $(id)
{
    return document.getElementById(id);
}

/*
==========================================================
HINT SYSTEM
==========================================================
*/

function hint(message,type="success")
{

    const h = $("hint");

    if(!h)
        return;

    h.innerHTML = message;

    h.className = "trc4-hint " + type;
    h.style.display="block";

    setTimeout(()=>{
        h.style.display="none";
    },3500);
}

/*
==========================================================
LOADER
==========================================================
*/

function loading(show=true)
{

    const loader=$("loader");
    if(!loader)
        return;
    loader.style.display = show ? "flex" : "none";
}

/*
==========================================================
CLEAR INPUT
==========================================================
*/

$("clearBtn")?.addEventListener(
"click",
()=>{

    $("urls").value="";
    streamResults=[];
    $("results").innerHTML=`
    <div class="empty">
    ⚡
    <br><br>
    No Streams Checked Yet
    </div>
    `;
    updateStats();
    hint("Data Cleared ✔","info");
});

/*
==========================================================
COPY INPUT / RESULTS
==========================================================
*/

$("copyBtn")?.addEventListener(
"click",
()=>{

let text="";

streamResults.forEach(item=>{

text += `${item.url}
STATUS: ${item.status}
HTTP: ${item.http}
TIME: ${item.time}ms
-------------------
`;

});

if(!text)
{

hint("No Results Available","warning");
return;
}

navigator.clipboard.writeText(text);

hint("Results Copied ✔"
);

});

/*
==========================================================
CHECK BUTTON
==========================================================
*/

$("checkBtn")?.addEventListener(
"click",
()=>{

let urls=$("urls").value.trim();

if(!urls)
{

hint("Enter M3U8 URLs First","warning"
);

return;
}

let list = urls.split("\n").map(x=>x.trim()).filter(Boolean);
checkStreams(list);
});

/*
==========================================================
CHECK STREAMS
==========================================================
*/

async function checkStreams(list)
{

streamResults=[];
$("results").innerHTML="";
loading(true);

for(const url of list)
{

try{

let response = await fetch("check.php",
{
method:"POST",
headers:
{
"Content-Type":
"application/json"
},

body:JSON.stringify(
{
url:url
})
});

let data = await response.json();
streamResults.push(data);
renderResult(data);
}

catch(error)
{


let fail={
url:url,
status:"offline",
http:0,
time:0,
error:error.message
};

streamResults.push(fail);
renderResult(fail);
}

}

loading(false);
updateStats();

hint("Scan Completed ✔");
}

/*
==========================================================
RESULT CARD
==========================================================
*/

function renderResult(data)
{

const box=$("results");

let cls="status-offline";

if(data.status==="live")
cls="status-live";

if(data.status==="redirect")
cls="status-redirect";

box.innerHTML +=`
<div class="result-card">
<div class="result-header">
<div class="result-url">
${escapeHTML(data.url)}
</div>

<div class="result-status ${cls}">
${data.status.toUpperCase()}
</div>
</div>

<div class="result-grid">
<div class="result-item">
<label>HTTP</label>
<span>${data.http ?? "-"}</span>
</div>

<div class="result-item">
<label>TIME</label>

<span>${data.time ?? "-"} ms</span>
</div>

<div class="result-item">
<label>SERVER</label>
<span>${data.server ?? "-"}</span>
</div>

<div class="result-item">
<label>CORS</label>
<span>${data.cors ? "YES":"NO"}</span>
</div>
</div>

<!----/>
<button class="details-btn"
onclick='openDetails(${JSON.stringify(data)})'>
DETAILS
</button>

<button class="details-btn" onclick="analyzeM3U8('${data.url}')">📡 ANALYZE M3U8
</button>
<!---->

<!----/>
🔍 DETAILS → lime neon TRC4
📡 ANALYZE M3U8 → cyan neon
hover glow
scan line animation
click effect
dark glass background
<!---->
<button class="details-btn" onclick='openDetails(${JSON.stringify(data)})'>🔍 DETAILS</button>

<button class="details-btn analyze" onclick="analyzeM3U8('${data.url}')">📡 ANALYZE M3U8</button>
</div>
`;
}

/*
==========================================================
STATISTICS
==========================================================
*/

function updateStats()
{

$("totalCount").innerHTML =
streamResults.length;

$("liveCount").innerHTML =

streamResults.filter(
x=>x.status==="live"
).length;

$("offlineCount").innerHTML =

streamResults.filter(
x=>x.status==="offline"
).length;

$("corsCount").innerHTML =

streamResults.filter(
x=>x.cors===true
).length;
}

/*
==========================================================
MODAL
==========================================================
*/

function openDetails(data)
{

$("modalBody").innerHTML=`<pre>${JSON.stringify(data,null,4)}</pre>`;

$("resultModal").style.display="flex";
}

$("closeModal")
?.addEventListener("click",()=>{

$("resultModal").style.display="none";
});

/*
==========================================================
COPY DETAILS
==========================================================
*/

$("copyDetails")?.addEventListener("click",()=>{

navigator.clipboard.writeText($("modalBody").innerText);

hint("Details Copied ✔");
});

/*
==========================================================
HTML ESCAPE
==========================================================
*/

function escapeHTML(text)
{

return String(text)
.replace(/&/g,"&amp;")
.replace(/</g,"&lt;")
.replace(/>/g,"&gt;")
.replace(/"/g,"&quot;")
.replace(/'/g,"&#039;"
);
}

/*
==========================================================
INIT
==========================================================
*/

console.log("%cTRC4 Stream Inspector V1.0 Ready","color:#66ff00;font-size:16px");

/*
==========================================================
EXPORT SYSTEM
==========================================================
*/
/* Export System • Filters • Search • Sort • Result Tools

✅ Export TXT
✅ Export JSON
✅ Export CSV
✅ Copy All Results
✅ Search Results
✅ Filter Live / Offline / Redirect
✅ Sort Speed / HTTP / Status
✅ Report Generator
✅ Professional Stream Management
*/

function downloadFile(content,filename,type)
{

const blob = new Blob(
[
content
],
{
type:type
}
);

const url = URL.createObjectURL(blob);

const a = document.createElement("a");

a.href=url;
a.download=filename;
a.click();
URL.revokeObjectURL(url);
}

/*
==========================================================
EXPORT TXT
==========================================================
*/

$("exportTxt")?.addEventListener("click",()=>{

if(!streamResults.length)
{

hint("No Results","warning");

return;
}

let txt="TRC4 Stream Report\n\n";

streamResults.forEach(s=>{
txt +=`
URL:${s.url}
STATUS:${s.status}
HTTP:${s.http}
TIME:${s.time}ms
SERVER:${s.server}
---------------------
`;

});
downloadFile(
txt,
"trc4-report.txt",
"text/plain"
);

hint("TXT Exported ✔");
});

/*
==========================================================
EXPORT JSON
==========================================================
*/

$("exportJson")?.addEventListener("click",()=>{

downloadFile(JSON.stringify(streamResults,
null,
4
),
"trc4-report.json",
"application/json"
);

hint("JSON Exported ✔");
});

/*
==========================================================
EXPORT CSV
==========================================================
*/

$("exportCsv")?.addEventListener("click",()=>{

let csv = "URL,STATUS,HTTP,TIME,SERVER,CORS\n";

streamResults.forEach(
s=>{

csv +=
`"${s.url}",
"${s.status}",
"${s.http}",
"${s.time}",
"${s.server}",
"${s.cors}"
\n`;
});

downloadFile(
csv,
"trc4_csv_report.csv",
"text/csv"
);

hint("CSV Exported ✔");
});

/*
==========================================================
SEARCH
==========================================================
*/

$("searchBox")
?.addEventListener("input",function()
{

let value = this.value.toLowerCase();

document.querySelectorAll(".result-card"
)

.forEach(card=>{
card.style.display = card.innerText.toLowerCase().includes(value) ? "flex" : "none";
});

});

/*
==========================================================
FILTER STATUS
==========================================================
*/

$("filterStatus")
?.addEventListener("change",function(){

let value=this.value;

document.querySelectorAll(".result-card").forEach(card=>{

if(value==="all")
{
card.style.display="flex";
return;
}

card.style.display = card.innerText.toLowerCase().includes(value) ? "flex" : "none";
});

});

/*
==========================================================
SORT RESULTS
==========================================================
*/

$("sortBox")
?.addEventListener("change",function(){

let mode=this.value;

if(mode==="none")
return;

if(mode==="speed")
{

streamResults.sort(
(a,b)=>
a.time-b.time
);

}

if(mode==="http")
{
streamResults.sort(
(a,b)=>

a.http-b.http
);
}

$("results").innerHTML="";

streamResults.forEach(renderResult);

hint("Sorted ✔");
});

/*
==========================================================
TRC4 Broadcast Stream Inspector

📡 Parse M3U8 playlist
🎞 Resolution
🔊 Audio tracks
📶 Bandwidth
🔐 Encryption AES-128
⏱ Segment duration
🌍 CDN Detection
📦 Playlist size
🟢 Stream Quality Badge

Advanced M3U8 Analyzer Engine
Playlist • Quality • Audio • Encryption • CDN Detection

Në këtë pjesë e kthejmë checker-in nga një kontrollues linku në një M3U8 Analyzer.

✅ Lexim real i playlistës .m3u8
✅ Detect Master Playlist
✅ Detect Resolution
✅ Detect Bandwidth
✅ Detect Audio Tracks
✅ Detect Encryption AES-128
✅ Segment Count
✅ Segment Duration
✅ CDN Provider Detection
✅ JSON Output për UI
Advanced M3U8 Analyzer
==========================================================
*/
/*
==========================================================
M3U8 ANALYZER CALL

✅ Link Checker
✅ HTTP Analyzer
✅ CORS Detector
✅ Speed Test
✅ Export System
✅ M3U8 Analyzer
✅ Quality Detection
✅ CDN Detection
==========================================================
*/

async function analyzeM3U8(url)
{

loading(true);

try{

const res = await fetch("M3U8_Analyzer.php",
{

method:"POST",
headers:
{
"Content-Type":
"application/json"
},

body:JSON.stringify(
{
url:url
})
});

const data = await res.json();
openDetails(data);
}

catch(e)
{

hint("Analyzer Error","error"
);

}

loading(false);
}