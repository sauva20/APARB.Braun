<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Inspeksi Selesai</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Setup Rotis Sans Serif */
        @font-face {
            font-family: 'Rotis Sans Serif';
            src: url('/fonts/RotisSansSerif.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Rotis Sans Serif';
            src: url('/fonts/RotisSansSerif-Bold.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
            font-display: swap;
        }

        body {
            font-family: 'Rotis Sans Serif', sans-serif;
            background-color: #F0F0F0;
            color: #1A1A1A;
            -webkit-tap-highlight-color: transparent;
        }
        
        [x-cloak] { display: none !important; }
        
        #reader {
            width: 100%;
            border-radius: 1rem;
            overflow: hidden;
            border: none;
        }
        #reader video {
            object-fit: cover;
            border-radius: 1rem;
        }
    </style>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- HTML5 QR Code -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="bg-[#F0F0F0] text-[#1A1A1A] relative min-h-[100dvh] w-full flex flex-col items-center justify-center overflow-x-hidden overflow-y-auto">

    <!-- Decorative Background Elements -->
    <div class="absolute top-[-10%] left-[-10%] w-[60%] h-[40%] bg-[#009B77] rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none"></div>
    <div class="absolute bottom-10 right-[-10%] w-[50%] h-[40%] bg-[#8A4B9F] rounded-full mix-blend-multiply filter blur-[100px] opacity-15 pointer-events-none"></div>

    <div class="w-full min-h-[100dvh] max-w-md mx-auto relative z-10 flex flex-col items-center justify-center p-6" x-data="scannerApp()">
        
        <div x-show="!scanning" class="bg-white w-full rounded-3xl shadow-xl p-8 flex flex-col items-center text-center border border-slate-100"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mb-6">
                <i class="ph-fill ph-check-circle text-5xl text-[#009B77]"></i>
            </div>
            
            <h1 class="text-2xl font-bold text-slate-800 mb-2">Inspeksi Selesai!</h1>
            <p class="text-sm font-medium text-slate-500 mb-8">Data inspeksi untuk APAR <span class="font-bold text-slate-800">{{ $apar->kode }}</span> telah berhasil disimpan.</p>
            
            <button @click="startScanner()" class="w-full py-4 bg-[#009B77] hover:bg-[#008264] text-white rounded-xl font-bold text-[15px] shadow-lg shadow-[#009B77]/30 transition-all flex items-center justify-center gap-2">
                <i class="ph-bold ph-scan text-xl"></i> Scan APAR Lain
            </button>
            

        </div>

        <!-- Scanner View -->
        <div x-show="scanning" class="fixed inset-0 z-50 bg-black flex flex-col" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-full">
            
            <!-- Header Overlay -->
            <div class="absolute top-0 inset-x-0 z-20 p-6 flex items-center justify-between bg-gradient-to-b from-black/60 to-transparent">
                <h2 class="font-bold text-white text-lg flex items-center gap-2 drop-shadow-md">
                    <i class="ph-bold ph-scan"></i> Scan QR Code
                </h2>
                <button @click="stopScanner()" class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-white/30 transition-all">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>
            
            <!-- Camera Viewport -->
            <div class="flex-1 relative overflow-hidden bg-black flex items-center justify-center">
                <div id="reader" class="w-full h-full object-cover"></div>
                
                <!-- Scanning Guidelines Overlay -->
                <div class="absolute inset-0 pointer-events-none z-10 flex flex-col items-center justify-center">
                    <!-- Overlay Mask (Dimming edges) -->
                    <div class="absolute inset-0 border-[60px] border-black/40"></div>
                    
                    <!-- Scanner Frame -->
                    <div class="relative w-64 h-64 border-2 border-white/20 rounded-xl overflow-hidden shadow-[0_0_0_4000px_rgba(0,0,0,0.5)]">
                        <!-- Corner brackets -->
                        <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-[#009B77] rounded-tl-xl"></div>
                        <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-[#009B77] rounded-tr-xl"></div>
                        <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-[#009B77] rounded-bl-xl"></div>
                        <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-[#009B77] rounded-br-xl"></div>
                        
                        <!-- Scanning line animation -->
                        <div class="absolute top-0 inset-x-0 h-0.5 bg-[#009B77] shadow-[0_0_15px_3px_rgba(0,155,119,0.7)] w-full animate-[scan_2s_ease-in-out_infinite]"></div>
                    </div>
                    
                    <p class="text-white font-medium text-sm mt-8 drop-shadow-md z-20">Arahkan kamera ke QR Code APAR</p>
                </div>
            </div>
        </div>

        <style>
            @keyframes scan {
                0%, 100% { top: 0%; opacity: 0; }
                10%, 90% { opacity: 1; }
                50% { top: 100%; opacity: 1; }
            }
            #reader { border: none !important; }
            #reader video { object-fit: cover !important; width: 100% !important; height: 100% !important; border-radius: 0 !important; }
            #reader__dashboard_section_csr { display: none !important; }
        </style>

    </div>

    <script>
        function scannerApp() {
            return {
                scanning: false,
                html5QrcodeScanner: null,
                
                startScanner() {
                    this.scanning = true;
                    
                    setTimeout(() => {
                        if (!this.html5QrcodeScanner) {
                            this.html5QrcodeScanner = new Html5Qrcode("reader");
                        }
                        
                        const config = { fps: 10, aspectRatio: 1.0 };
                        
                        this.html5QrcodeScanner.start(
                            { facingMode: "environment" },
                            config,
                            (decodedText, decodedResult) => {
                                // Jika berhasil, hentikan kamera dan redirect
                                this.html5QrcodeScanner.stop().then(() => {
                                    window.location.href = decodedText;
                                }).catch((err) => {
                                    window.location.href = decodedText;
                                });
                            },
                            (errorMessage) => {
                                // ignore error
                            }
                        ).catch((err) => {
                            alert("Kamera tidak dapat diakses atau diblokir. Pastikan memberi izin kamera.");
                            this.scanning = false;
                        });
                    }, 300);
                },

                stopScanner() {
                    if (this.html5QrcodeScanner) {
                        this.html5QrcodeScanner.stop().then(() => {
                            this.scanning = false;
                        }).catch(err => console.error("Error stopping scanner", err));
                    } else {
                        this.scanning = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
