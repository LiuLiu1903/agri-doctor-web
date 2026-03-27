<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agri-Doctor AI | Chẩn đoán bệnh cây trồng</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Hiệu ứng chuyển đổi mượt mà giữa các Tab */
        .fade-in {
            animation: fadeIn 0.4s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="antialiased font-sans bg-gradient-to-br from-green-50 via-teal-50 to-emerald-100 min-h-screen flex flex-col">

    <nav class="bg-white/90 backdrop-blur-lg shadow-md sticky top-0 z-50 border-b border-green-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <span class="text-3xl">🌱</span>
                    <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-green-700 to-emerald-500 tracking-tight hidden sm:block">Agri-Doctor</span>
                </div>
                
                <div class="hidden md:flex space-x-1 sm:space-x-4">
                    <button onclick="changeMainTab('main-tab-1', this)" class="main-tab-btn active px-4 py-2 text-sm font-bold text-green-700 border-b-4 border-green-600 transition">Hệ thống chẩn đoán</button>
                    <button onclick="changeMainTab('main-tab-2', this)" class="main-tab-btn px-4 py-2 text-sm font-bold text-gray-500 border-b-4 border-transparent hover:text-green-600 transition">Tính năng nổi bật</button>
                    <button onclick="changeMainTab('main-tab-3', this)" class="main-tab-btn px-4 py-2 text-sm font-bold text-gray-500 border-b-4 border-transparent hover:text-green-600 transition">Hướng dẫn sử dụng</button>
                </div>

                <div class="flex space-x-3 items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-green-600 text-white hover:bg-green-700 px-5 py-2 rounded-full font-medium transition shadow-sm">Vào Bảng Điều Khiển</a>
                    @else
                        <a href="{{ route('login') }}" class="text-green-700 font-medium hover:text-green-900 transition">Đăng nhập</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-green-600 to-emerald-500 text-white hover:from-green-700 hover:to-emerald-600 px-5 py-2 rounded-full font-medium transition shadow-md hidden sm:block">Đăng ký ngay</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
        <div class="md:hidden flex justify-around border-t border-gray-100 bg-white p-2">
             <button onclick="changeMainTab('main-tab-1', this)" class="main-tab-btn active text-xs font-bold text-green-700 border-b-2 border-green-600 pb-1">Chẩn đoán</button>
             <button onclick="changeMainTab('main-tab-2', this)" class="main-tab-btn text-xs font-bold text-gray-500 border-b-2 border-transparent pb-1">Tính năng</button>
             <button onclick="changeMainTab('main-tab-3', this)" class="main-tab-btn text-xs font-bold text-gray-500 border-b-2 border-transparent pb-1">Hướng dẫn</button>
        </div>
    </nav>

    <main class="flex-grow flex items-center py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">

            <div id="main-tab-1" class="main-tab-content fade-in block">
                <div class="bg-white/60 backdrop-blur-xl p-8 md:p-16 rounded-3xl shadow-2xl border border-white/50 text-center relative overflow-hidden">
                    <div class="absolute top-[-50px] left-[-50px] w-64 h-64 bg-green-200 rounded-full mix-blend-multiply filter blur-2xl opacity-50"></div>
                    <div class="absolute bottom-[-50px] right-[-50px] w-64 h-64 bg-emerald-200 rounded-full mix-blend-multiply filter blur-2xl opacity-50"></div>
                    
                    <div class="relative z-10">
                        <span class="inline-block py-1 px-3 rounded-full bg-green-100 text-green-800 text-sm font-semibold mb-4 border border-green-200">Phiên bản 1.0</span>
                        <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 tracking-tight mb-6">
                            Bác Sĩ Cây Trồng <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-500">AI</span>
                        </h1>
                        <p class="text-xl md:text-2xl text-gray-700 mb-8 max-w-3xl mx-auto font-light">
                            Phát hiện sớm sâu bệnh trong <b>3 giây</b> chỉ với một bức ảnh.
                        </p>
                        
                        <div class="flex justify-center mt-10">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-10 py-4 rounded-full text-xl font-bold shadow-xl hover:shadow-2xl hover:scale-105 transition-all flex items-center gap-2">
                                    📸 Bắt đầu chẩn đoán ngay
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-10 py-4 rounded-full text-xl font-bold shadow-xl hover:shadow-2xl hover:scale-105 transition-all flex items-center gap-2">
                                    Tạo tài khoản & Dùng thử 
                                </a>
                            @endauth
                        </div>

                        <div class="mt-16 text-right">
                            <button onclick="document.querySelectorAll('.main-tab-btn')[1].click()" class="text-green-700 font-semibold hover:text-green-900 flex items-center justify-end w-full gap-1">
                                Xem tính năng hệ thống <span class="text-xl">👉</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="main-tab-2" class="main-tab-content fade-in hidden">
                <div class="text-center mb-10">
                    <h2 class="text-4xl font-extrabold text-gray-900 drop-shadow-sm">Tính năng cốt lõi</h2>
                    <p class="text-lg text-gray-600 mt-3">Sức mạnh của Trí Tuệ Nhân Tạo phục vụ nông nghiệp</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white/80 backdrop-blur-sm p-8 rounded-3xl shadow-lg border-t-4 border-green-500 hover:-translate-y-2 transition-transform duration-300">
                        <div class="text-5xl mb-4">🧠</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Deep Learning</h3>
                        <p class="text-gray-600">Mô hình AI được huấn luyện chuyên sâu, nhận diện cực kỳ chính xác các loại bệnh trên bề mặt lá.</p>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm p-8 rounded-3xl shadow-lg border-t-4 border-blue-500 hover:-translate-y-2 transition-transform duration-300">
                        <div class="text-5xl mb-4">💬</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Chatbox Tự Nhiên</h3>
                        <p class="text-gray-600">Trò chuyện trực tiếp với AI như một kỹ sư nông nghiệp thực thụ, hỏi đáp mọi thắc mắc về cây trồng.</p>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm p-8 rounded-3xl shadow-lg border-t-4 border-orange-500 hover:-translate-y-2 transition-transform duration-300">
                        <div class="text-5xl mb-4">📋</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Phác đồ chi tiết</h3>
                        <p class="text-gray-600">Cung cấp hướng dẫn điều trị, tên các loại thuốc bảo vệ thực vật cần dùng và liều lượng phù hợp.</p>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm p-8 rounded-3xl shadow-lg border-t-4 border-purple-500 hover:-translate-y-2 transition-transform duration-300">
                        <div class="text-5xl mb-4">⏱️</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Kết quả tức thì</h3>
                        <p class="text-gray-600">Hệ thống xử lý nhanh chóng, trả kết quả chỉ trong vòng vài giây, tiết kiệm thời gian chờ đợi.</p>
                    </div>
                </div>

                <div class="mt-12 flex justify-between">
                    <button onclick="document.querySelectorAll('.main-tab-btn')[0].click()" class="text-gray-500 font-semibold hover:text-green-700 flex items-center gap-1">
                        <span class="text-xl">👈</span> Quay lại
                    </button>
                    <button onclick="document.querySelectorAll('.main-tab-btn')[2].click()" class="text-green-700 font-semibold hover:text-green-900 flex items-center gap-1">
                        Xem hướng dẫn sử dụng <span class="text-xl">👉</span>
                    </button>
                </div>
            </div>

            <div id="main-tab-3" class="main-tab-content fade-in hidden">
                <div class="bg-white/80 backdrop-blur-xl p-8 md:p-12 rounded-3xl shadow-xl border border-white/50 max-w-4xl mx-auto">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-8 text-center border-b pb-4">3 Bước Sử Dụng Đơn Giản</h2>
                    
                    <div class="space-y-8">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 group">
                            <div class="flex-shrink-0 w-16 h-16 rounded-full bg-green-100 border-4 border-green-500 flex items-center justify-center text-2xl font-black text-green-700 shadow-md group-hover:scale-110 transition-transform">1</div>
                            <div class="text-center sm:text-left pt-2">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Đăng nhập / Đăng ký</h3>
                                <p class="text-gray-600 text-lg">Tạo một tài khoản miễn phí để hệ thống có thể lưu trữ lịch sử chẩn đoán khu vườn của bạn một cách an toàn.</p>
                            </div>
                        </div>

                        <div class="hidden sm:block ml-7 w-2 h-8 bg-gradient-to-b from-green-500 to-blue-500"></div>

                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 group">
                            <div class="flex-shrink-0 w-16 h-16 rounded-full bg-blue-100 border-4 border-blue-500 flex items-center justify-center text-2xl font-black text-blue-700 shadow-md group-hover:scale-110 transition-transform">2</div>
                            <div class="text-center sm:text-left pt-2">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Tải ảnh lên khung Chat</h3>
                                <p class="text-gray-600 text-lg">Vào Bảng điều khiển, chụp một bức ảnh rõ nét chiếc lá bị bệnh và bấm nút đính kèm để gửi cho AI.</p>
                            </div>
                        </div>

                        <div class="hidden sm:block ml-7 w-2 h-8 bg-gradient-to-b from-blue-500 to-orange-500"></div>

                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 group">
                            <div class="flex-shrink-0 w-16 h-16 rounded-full bg-orange-100 border-4 border-orange-500 flex items-center justify-center text-2xl font-black text-orange-700 shadow-md group-hover:scale-110 transition-transform">3</div>
                            <div class="text-center sm:text-left pt-2">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Nhận kết quả điều trị</h3>
                                <p class="text-gray-600 text-lg">Đọc kết quả phân tích trên màn hình. Bạn có thể chat hỏi thêm AI về nơi mua thuốc hoặc cách phòng ngừa bệnh tái phát.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 text-center">
                        <a href="{{ route('register') }}" class="inline-block bg-gray-900 text-white px-8 py-3 rounded-xl text-lg font-bold shadow-lg hover:bg-gray-800 transition-colors">Bắt đầu ngay bây giờ</a>
                    </div>

                    <div class="mt-8">
                        <button onclick="document.querySelectorAll('.main-tab-btn')[1].click()" class="text-gray-500 font-semibold hover:text-green-700 flex items-center gap-1">
                            <span class="text-xl">👈</span> Quay lại tính năng
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="bg-white/50 backdrop-blur-md border-t border-green-200 py-6 text-center mt-auto">
        <p class="text-green-800 font-medium text-sm">&copy; {{ date('Y') }} Agri-Doctor AI. Phát triển bằng Laravel & Deep Learning.</p>
    </footer>

    <script>
        function changeMainTab(tabId, clickedBtn) {
            // Ẩn tất cả nội dung
            document.querySelectorAll('.main-tab-content').forEach(tab => {
                tab.classList.add('hidden');
                tab.classList.remove('block');
            });
            
            // Xóa style active của các nút (xử lý cả mobile và desktop)
            document.querySelectorAll('.main-tab-btn').forEach(btn => {
                btn.classList.remove('text-green-700', 'border-green-600', 'active');
                btn.classList.add('text-gray-500', 'border-transparent');
            });

            // Hiện tab được chọn
            const targetTab = document.getElementById(tabId);
            targetTab.classList.remove('hidden');
            targetTab.classList.add('block');
            
            // Highlight nút tương ứng (phải tìm tất cả các nút có cùng chỉ số để highlight cả bản mobile và desktop)
            const index = Array.from(clickedBtn.parentNode.children).indexOf(clickedBtn);
            
            // Desktop btn
            const desktopBtns = document.querySelectorAll('.hidden.md\\:flex .main-tab-btn');
            if(desktopBtns[index]) {
                desktopBtns[index].classList.remove('text-gray-500', 'border-transparent');
                desktopBtns[index].classList.add('text-green-700', 'border-green-600', 'active');
            }
            
            // Mobile btn
            const mobileBtns = document.querySelectorAll('.md\\:hidden .main-tab-btn');
            if(mobileBtns[index]) {
                mobileBtns[index].classList.remove('text-gray-500', 'border-transparent');
                mobileBtns[index].classList.add('text-green-700', 'border-green-600', 'active');
            }
        }
    </script>
</body>
</html>