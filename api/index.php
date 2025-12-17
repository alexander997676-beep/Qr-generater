<?php
// --- 2028 ENGINE: SECURE FILE UPLOAD ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    function uploadFile($inputName) {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == 0) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fileName = "v28_" . uniqid() . "_" . basename($_FILES[$inputName]["name"]);
            $targetFilePath = $targetDir . $fileName;
            if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFilePath)) {
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                return $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/" . $targetFilePath;
            }
        }
        return null;
    }

    if ($_POST['action'] === 'upload_logo') {
        echo json_encode(['status' => 'success', 'url' => uploadFile('logo')]);
    } elseif ($_POST['action'] === 'upload_image') {
        echo json_encode(['status' => 'success', 'url' => uploadFile('image')]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Vision 2028 - Cyber Edition</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap');
        
        body { 
            font-family: 'Space Grotesk', sans-serif; 
            background-color: #0f172a; /* Dark Background */
            color: #e2e8f0;
            background-image: radial-gradient(circle at 50% 0%, #1e293b 0%, #0f172a 100%);
        }

        /* Neon Glow Inputs */
        .neon-input {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid #334155;
            color: white;
            transition: all 0.3s ease;
        }
        .neon-input:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.3);
            outline: none;
        }

        /* Glass Cards */
        .glass-card {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Floating Dock (Mobile) */
        .floating-dock {
            position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(16px);
            border-radius: 20px;
            padding: 10px 20px;
            display: flex; gap: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            z-index: 50;
        }
        .dock-item {
            color: #64748b; font-size: 1.2rem; transition: 0.3s;
            position: relative; padding: 5px;
        }
        .dock-item.active { color: #22d3ee; transform: scale(1.1); }
        .dock-item.active::after {
            content: ''; position: absolute; bottom: -5px; left: 50%; transform: translateX(-50%);
            width: 4px; height: 4px; background: #22d3ee; border-radius: 50%; box-shadow: 0 0 10px #22d3ee;
        }

        /* Sidebar Button */
        .side-btn.active {
            background: linear-gradient(90deg, rgba(34, 211, 238, 0.1) 0%, transparent 100%);
            border-left: 3px solid #22d3ee;
            color: #22d3ee;
        }

        /* Loader Animation */
        .scanner-line {
            position: absolute; top: 0; left: 0; width: 100%; height: 2px;
            background: #22d3ee;
            box-shadow: 0 0 10px #22d3ee;
            animation: scan 2s infinite linear;
        }
        @keyframes scan { 0% {top: 0;} 50% {top: 100%;} 100% {top: 0;} }
    </style>
</head>
<body class="flex flex-col md:flex-row h-screen overflow-hidden">

    <!-- DESKTOP SIDEBAR -->
    <aside class="hidden md:flex w-72 border-r border-slate-800 flex-col z-20 bg-[#0f172a]">
        <div class="p-8">
            <h1 class="text-3xl font-bold text-white tracking-tighter">
                VISION <span class="text-cyan-400">2028</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1 uppercase tracking-widest">Enhanced Edition</p>
        </div>
        
        <nav class="flex-1 px-4 space-y-2 mt-4">
            <button onclick="setMode('link')" id="d-link" class="side-btn active w-full text-left px-5 py-4 rounded-r-xl transition flex items-center gap-4 font-medium text-slate-400 hover:text-white">
                <i class="fa-solid fa-link"></i> Website Link
            </button>
            <button onclick="setMode('wifi')" id="d-wifi" class="side-btn w-full text-left px-5 py-4 rounded-r-xl transition flex items-center gap-4 font-medium text-slate-400 hover:text-white">
                <i class="fa-solid fa-wifi"></i> Wi-Fi Access
            </button>
            <button onclick="setMode('vcard')" id="d-vcard" class="side-btn w-full text-left px-5 py-4 rounded-r-xl transition flex items-center gap-4 font-medium text-slate-400 hover:text-white">
                <i class="fa-solid fa-id-badge"></i> Smart Contact
            </button>
            <button onclick="setMode('whatsapp')" id="d-whatsapp" class="side-btn w-full text-left px-5 py-4 rounded-r-xl transition flex items-center gap-4 font-medium text-slate-400 hover:text-white">
                <i class="fa-brands fa-whatsapp"></i> WhatsApp
            </button>
            <button onclick="setMode('photo')" id="d-photo" class="side-btn w-full text-left px-5 py-4 rounded-r-xl transition flex items-center gap-4 font-medium text-slate-400 hover:text-white">
                <i class="fa-solid fa-image"></i> Hologram Photo
            </button>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-y-auto relative no-scrollbar">
        <div class="max-w-6xl mx-auto p-6 md:p-12 pb-32">
            
            <!-- Mobile Header -->
            <div class="md:hidden text-center mb-8">
                <h1 class="text-2xl font-bold text-white">VISION <span class="text-cyan-400">2028</span></h1>
            </div>

            <div class="flex flex-col lg:flex-row gap-10">
                
                <!-- LEFT: INPUTS -->
                <div class="flex-1 space-y-8">
                    
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2" id="header-title">Destination Link</h2>
                        <div class="h-1 w-20 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-full"></div>
                    </div>

                    <!-- FORMS -->
                    <div class="glass-card p-6 rounded-2xl shadow-2xl shadow-cyan-900/10">
                        
                        <!-- Link -->
                        <div id="form-link" class="input-group">
                            <label class="block text-xs font-bold text-cyan-400 uppercase mb-2">URL / Link</label>
                            <input type="text" id="inp-url" class="neon-input w-full p-4 rounded-xl" placeholder="https://future.com" oninput="debounceGen()">
                        </div>

                        <!-- Wi-Fi -->
                        <div id="form-wifi" class="input-group hidden space-y-4">
                            <input type="text" id="wf-ssid" class="neon-input w-full p-4 rounded-xl" placeholder="Network Name" oninput="debounceGen()">
                            <input type="text" id="wf-pass" class="neon-input w-full p-4 rounded-xl" placeholder="Password" oninput="debounceGen()">
                            <select id="wf-enc" class="neon-input w-full p-4 rounded-xl" onchange="debounceGen()">
                                <option value="WPA">WPA/WPA2 (Secure)</option>
                                <option value="nopass">Open Network</option>
                            </select>
                        </div>

                        <!-- VCard -->
                        <div id="form-vcard" class="input-group hidden space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" id="vc-fn" class="neon-input w-full p-4 rounded-xl" placeholder="First Name" oninput="debounceGen()">
                                <input type="text" id="vc-ln" class="neon-input w-full p-4 rounded-xl" placeholder="Last Name" oninput="debounceGen()">
                            </div>
                            <input type="text" id="vc-ph" class="neon-input w-full p-4 rounded-xl" placeholder="Phone Number" oninput="debounceGen()">
                        </div>

                        <!-- WhatsApp -->
                        <div id="form-whatsapp" class="input-group hidden space-y-4">
                            <input type="text" id="wa-num" class="neon-input w-full p-4 rounded-xl" placeholder="Phone with Code (923...)" oninput="debounceGen()">
                            <input type="text" id="wa-msg" class="neon-input w-full p-4 rounded-xl" placeholder="Start Message" oninput="debounceGen()">
                        </div>

                        <!-- Photo -->
                        <div id="form-photo" class="input-group hidden">
                            <div class="border-2 border-dashed border-slate-700 hover:border-cyan-500 rounded-2xl p-10 text-center transition cursor-pointer relative group">
                                <input type="file" id="inp-img" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" onchange="uploadImage()">
                                <i class="fa-solid fa-cloud-arrow-up text-4xl text-slate-500 group-hover:text-cyan-400 mb-3 transition"></i>
                                <p class="text-slate-400 group-hover:text-white">Upload Image File</p>
                                <p id="img-status" class="text-xs text-slate-600 mt-2">Server Upload</p>
                            </div>
                        </div>

                    </div>

                    <!-- CUSTOMIZATION -->
                    <div class="glass-card p-6 rounded-2xl">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-white">Appearance</h3>
                            <span class="text-xs bg-cyan-900 text-cyan-300 px-2 py-1 rounded">PRO</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-slate-500 uppercase">QR Color</label>
                                <input type="color" id="col-qr" value="#00e5ff" class="w-full h-10 rounded-lg border-0 bg-slate-800 p-1 mt-1 cursor-pointer" oninput="debounceGen()">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 uppercase">Background</label>
                                <input type="color" id="col-bg" value="#0f172a" class="w-full h-10 rounded-lg border-0 bg-slate-800 p-1 mt-1 cursor-pointer" oninput="debounceGen()">
                            </div>
                        </div>
                        <div class="mt-4">
                             <label class="text-xs text-slate-500 uppercase">Style</label>
                             <select id="qr-style" class="neon-input w-full p-2 rounded-lg mt-1 text-sm" onchange="debounceGen()">
                                 <option value="square">Cyber Square</option>
                                 <option value="circle">Neon Dots</option>
                             </select>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: PREVIEW -->
                <div class="w-full lg:w-[400px]">
                    <div class="sticky top-6">
                        <div class="glass-card p-8 rounded-[2rem] text-center relative overflow-hidden border-t border-cyan-500/30">
                            
                            <h3 class="text-xs font-bold text-cyan-500 uppercase tracking-[0.2em] mb-8">Holographic Preview</h3>
                            
                            <div class="relative inline-block p-4 bg-white/5 rounded-2xl border border-white/10 shadow-2xl shadow-cyan-500/20">
                                <img id="qr-preview" src="" class="w-56 h-56 object-contain rounded-xl">
                                <!-- Scanner Effect -->
                                <div id="loader" class="absolute inset-0 hidden">
                                    <div class="scanner-line"></div>
                                    <div class="absolute inset-0 bg-slate-900/80 flex items-center justify-center">
                                        <span class="text-cyan-400 text-xs animate-pulse">GENERATING...</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 grid grid-cols-2 gap-4">
                                <button onclick="downloadQR()" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-3 rounded-xl transition shadow-[0_0_20px_rgba(8,145,178,0.4)]">
                                    <i class="fa-solid fa-download mr-2"></i> Save
                                </button>
                                <button onclick="shareQR()" class="bg-slate-700 hover:bg-slate-600 text-white font-bold py-3 rounded-xl transition">
                                    <i class="fa-solid fa-share-nodes mr-2"></i> Share
                                </button>
                            </div>

                        </div>

                        <!-- HISTORY WIDGET (NEW) -->
                        <div class="mt-6 glass-card p-5 rounded-2xl">
                            <h4 class="text-sm font-bold text-slate-400 mb-3 uppercase">Recent History</h4>
                            <div id="history-list" class="space-y-2 text-sm text-slate-500 max-h-32 overflow-y-auto no-scrollbar">
                                <p class="text-xs italic">No recent scans.</p>
                            </div>
                        </div>

                        <!-- COPYRIGHT FOOTER -->
                        <div class="mt-8 text-center opacity-60">
                            <p class="text-xs text-slate-500 font-medium">
                                &copy; 2025 Faizan Ahmad All Rights Reserved.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- FLOATING DOCK (MOBILE) -->
    <div class="floating-dock md:hidden">
        <div onclick="setMode('link')" class="dock-item active" id="m-link"><i class="fa-solid fa-link"></i></div>
        <div onclick="setMode('wifi')" class="dock-item" id="m-wifi"><i class="fa-solid fa-wifi"></i></div>
        <div onclick="setMode('vcard')" class="dock-item" id="m-vcard"><i class="fa-solid fa-id-badge"></i></div>
        <div onclick="setMode('whatsapp')" class="dock-item" id="m-whatsapp"><i class="fa-brands fa-whatsapp"></i></div>
        <div onclick="setMode('photo')" class="dock-item" id="m-photo"><i class="fa-solid fa-image"></i></div>
    </div>

    <!-- LOGIC -->
    <script>
        let mode = 'link';
        let imgLink = '';
        let timer;
        let history = JSON.parse(localStorage.getItem('qr_history_28')) || [];

        function setMode(m) {
            mode = m;
            // UI Toggles
            document.querySelectorAll('.input-group').forEach(e => e.classList.add('hidden'));
            document.getElementById('form-' + mode).classList.remove('hidden');

            document.querySelectorAll('.dock-item').forEach(e => e.classList.remove('active'));
            const mBtn = document.getElementById('m-' + mode);
            if(mBtn) mBtn.classList.add('active');

            document.querySelectorAll('.side-btn').forEach(e => e.classList.remove('active', 'text-white'));
            const dBtn = document.getElementById('d-' + mode);
            if(dBtn) dBtn.classList.add('active', 'text-white');

            const titles = {link:'Destination Link', wifi:'Secure Wi-Fi', vcard:'Digital Identity', whatsapp:'Chat Link', photo:'Visual Hologram'};
            document.getElementById('header-title').innerText = titles[mode];

            debounceGen();
        }

        function getData() {
            if(mode === 'link') return document.getElementById('inp-url').value || 'https://faizan.com';
            if(mode === 'wifi') return `WIFI:S:${document.getElementById('wf-ssid').value};P:${document.getElementById('wf-pass').value};T:${document.getElementById('wf-enc').value};;`;
            if(mode === 'vcard') return `BEGIN:VCARD\nVERSION:3.0\nFN:${document.getElementById('vc-fn').value} ${document.getElementById('vc-ln').value}\nTEL:${document.getElementById('vc-ph').value}\nEND:VCARD`;
            if(mode === 'whatsapp') return `https://wa.me/${document.getElementById('wa-num').value}?text=${encodeURIComponent(document.getElementById('wa-msg').value)}`;
            if(mode === 'photo') return imgLink || 'https://example.com';
        }

        function gen() {
            const data = getData();
            const qrColor = document.getElementById('col-qr').value.replace('#','');
            const bgColor = document.getElementById('col-bg').value.replace('#','');
            const style = document.getElementById('qr-style').value;

            let url = `https://quickchart.io/qr?text=${encodeURIComponent(data)}&size=500&ecLevel=H&margin=1&dark=${qrColor}&light=${bgColor}`;
            if(style === 'circle') url += '&format=png&rounded=100';

            const loader = document.getElementById('loader');
            loader.classList.remove('hidden');

            const img = new Image();
            img.src = url;
            img.onload = () => { 
                document.getElementById('qr-preview').src = url; 
                loader.classList.add('hidden'); 
                addToHistory(mode, new Date().toLocaleTimeString());
            };
        }

        function addToHistory(type, time) {
            const list = document.getElementById('history-list');
            const item = `<div class="flex justify-between items-center bg-white/5 p-2 rounded hover:bg-white/10 transition"><span class="capitalize text-cyan-400">${type}</span> <span>${time}</span></div>`;
            list.innerHTML = item + list.innerHTML;
        }

        function debounceGen() { clearTimeout(timer); timer = setTimeout(gen, 500); }

        function uploadImage() {
            document.getElementById('img-status').innerText = "Uploading to Cloud...";
            let fd = new FormData();
            fd.append('action', 'upload_image');
            fd.append('image', document.getElementById('inp-img').files[0]);

            fetch('', {method:'POST', body:fd}).then(r=>r.json()).then(d=>{
                if(d.status === 'success') {
                    imgLink = d.url;
                    document.getElementById('img-status').innerText = "Upload Complete";
                    debounceGen();
                }
            });
        }

        function downloadQR() {
            const a = document.createElement('a'); a.href = document.getElementById('qr-preview').src; a.download = 'vision-2028.png'; a.click();
        }
        
        async function shareQR() {
            if(navigator.share) {
                const res = await fetch(document.getElementById('qr-preview').src);
                const blob = await res.blob();
                navigator.share({title:'Vision 2028 QR', files:[new File([blob],'qr.png',{type:blob.type})]});
            } else { alert('Device not supported'); }
        }

        gen(); // Init
    </script>
</body>
</html>
