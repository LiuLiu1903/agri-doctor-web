<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chẩn Đoán Bệnh Cây Trồng</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Quicksand', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .blob {
            position: absolute;
            filter: blur(40px);
            z-index: -1;
            opacity: 0.6;
            animation: move 10s infinite alternate;
        }
        @keyframes move {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(20px, -20px) scale(1.1); }
        }
        .progress-bar { transition: width 1.5s ease-in-out; }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 to-teal-100 min-h-screen flex flex-col items-center justify-center p-4 relative overflow-y-auto">

    <div class="blob bg-green-300 w-96 h-96 rounded-full top-0 left-0 mix-blend-multiply fixed"></div>
    <div class="blob bg-teal-300 w-96 h-96 rounded-full bottom-0 right-0 mix-blend-multiply animation-delay-2000 fixed"></div>

    <div class="container mx-auto max-w-5xl z-10 my-10">
        <div class="glass-effect rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[600px]">
            
            <div class="md:w-5/12 bg-gradient-to-br from-green-600 to-teal-700 p-10 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-green-600">
                            <i class="fa-solid fa-leaf text-xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold tracking-wide">Agri-Doctor AI</h1>
                    </div>
                    <h2 class="text-4xl font-bold mb-4 leading-tight">Chẩn đoán bệnh cây trồng</h2>
                    <p class="text-green-100 text-lg opacity-90">Hệ thống sử dụng Deep Learning để phát hiện sớm sâu bệnh, giúp bảo vệ mùa màng.</p>
                </div>
                
                <i class="fa-solid fa-seedling absolute -bottom-10 -right-10 text-[15rem] text-white opacity-10 transform rotate-12"></i>
            </div>

            <div class="md:w-7/12 p-8 md:p-12 bg-white flex flex-col">
                
                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex items-center">
                        <i class="fa-solid fa-circle-exclamation mr-3 text-xl"></i>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <form action="{{ route('diagnose.predict') }}" method="POST" enctype="multipart/form-data" id="uploadForm" onsubmit="return validateAndSubmit()">
                    @csrf
                    
                    <div class="mb-2">
                        <label class="block text-gray-700 font-bold mb-2 text-lg">Tải ảnh lá cây lên</label>
                    </div>

                    <div class="relative group mb-6">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-green-300 border-dashed rounded-2xl cursor-pointer bg-green-50/50 hover:bg-green-100/50 transition-all duration-300 overflow-hidden relative">
                            
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center p-4 transition-opacity duration-300" id="upload-placeholder">
                                <div class="w-16 h-16 mb-4 rounded-full bg-green-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-green-600"></i>
                                </div>
                                <p class="mb-2 text-sm text-gray-600"><span class="font-bold text-green-600">Bấm để chọn ảnh</span> hoặc kéo thả vào đây</p>
                            </div>

                            <img id="preview-image" class="absolute inset-0 w-full h-full object-contain hidden bg-black/5 p-2" />
                            
                            <input id="dropzone-file" name="image" type="file" class="hidden" accept="image/*" onchange="handleFileSelect(event)" />
                        </label>

                        <button type="button" id="remove-btn" onclick="removeImage()" class="hidden absolute top-2 right-2 bg-red-500 text-white w-8 h-8 rounded-full hover:bg-red-600 shadow-md items-center justify-center z-20 transition-colors">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <button type="submit" id="submit-btn" class="w-full text-white bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 focus:ring-4 focus:ring-green-300 font-bold rounded-xl text-lg px-5 py-4 text-center shadow-lg transform transition active:scale-95 flex items-center justify-center">
                        <span id="btn-text" class="flex items-center"><i class="fa-solid fa-magnifying-glass mr-2"></i>Phân Tích Ngay</span>
                        <span id="btn-loader" class="hidden flex items-center"><i class="fa-solid fa-circle-notch fa-spin mr-2"></i>Đang xử lý...</span>
                    </button>
                </form>

                @if(isset($result))
                <div id="result-section" class="mt-8 pt-6 border-t border-dashed border-gray-300 animate-fade-in-up pb-10">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fa-solid fa-clipboard-check text-green-600 mr-2"></i> Kết quả chẩn đoán
                    </h3>
                    
                    <div class="flex flex-col md:flex-row gap-4">
                        
                        @if(isset($imagePath))
                        <div class="w-full md:w-1/3">
                            <div class="bg-gray-100 rounded-xl p-2 h-full flex items-center justify-center border border-gray-200">
                                <img src="{{ asset($imagePath) }}" class="rounded-lg max-h-40 object-cover shadow-sm" alt="Ảnh phân tích">
                            </div>
                        </div>
                        @endif

                        <div class="w-full @if(isset($imagePath)) md:w-2/3 @endif">
                            <div class="bg-gradient-to-r from-green-50 to-teal-50 p-5 rounded-xl border border-green-200 shadow-sm relative overflow-hidden h-full flex flex-col justify-center">
                                <p class="text-sm text-gray-500 uppercase tracking-wider font-semibold">Bệnh phát hiện</p>
                                <p class="text-3xl font-bold text-green-700 mt-1 capitalize">{{ $result['prediction'] }}</p>
                                
                                <div class="mt-4">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600 font-medium">Độ tin cậy</span>
                                        <span class="font-bold text-green-600">{{ round($result['confidence'] * 100, 2) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-green-500 to-teal-500 h-3 rounded-full progress-bar shadow-[0_0_10px_rgba(16,185,129,0.5)]" style="width: 0%" data-width="{{ $result['confidence'] * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <details class="mt-4 group">
                        <summary class="text-xs text-gray-400 cursor-pointer hover:text-green-600 transition-colors list-none flex items-center">
                            <i class="fa-solid fa-code mr-1"></i> JSON Data
                        </summary>
                        <pre class="mt-2 bg-gray-900 text-green-400 p-3 rounded-lg text-xs overflow-x-auto shadow-inner">{{ json_encode($result, JSON_PRETTY_PRINT) }}</pre>
                    </details>
                </div>
                
                <script>
                    setTimeout(() => {
                        document.querySelectorAll('.progress-bar').forEach(bar => {
                            bar.style.width = bar.getAttribute('data-width');
                        });
                        const element = document.getElementById('result-section');
                        if(element) {
                            element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    }, 500);
                </script>
                @endif

            </div>
        </div>
        
        <div class="text-center mt-6 text-green-800/60 text-sm font-semibold pb-6">
            &copy; 2026 Graduation Project - Hệ thống chẩn đoán thông minh
        </div>
    </div>

    <script>
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('preview-image');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('opacity-0');
                    document.getElementById('remove-btn').classList.remove('hidden');
                    document.getElementById('remove-btn').classList.add('flex');
                }
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            document.getElementById('dropzone-file').value = ""; 
            document.getElementById('preview-image').classList.add('hidden');
            document.getElementById('preview-image').src = "";
            document.getElementById('upload-placeholder').classList.remove('opacity-0');
            document.getElementById('remove-btn').classList.add('hidden');
            document.getElementById('remove-btn').classList.remove('flex');
        }

        function validateAndSubmit() {
            const fileInput = document.getElementById('dropzone-file');
            if(fileInput.files.length === 0) {
                alert("🌱 Vui lòng chọn ảnh lá cây trước khi phân tích!");
                return false; 
            }
            const btn = document.getElementById('submit-btn');
            btn.classList.add('opacity-75', 'cursor-not-allowed'); 
            document.getElementById('btn-text').classList.add('hidden'); 
            document.getElementById('btn-loader').classList.remove('hidden'); 
            return true; 
        }
    </script>
</body>
</html>