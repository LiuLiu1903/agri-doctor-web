<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agri-Doctor AI Chat</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade {
            animation: fadeIn 0.3s ease forwards;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 bg-[#f8f9fa] overflow-hidden">

    <div class="flex h-screen w-full">

        <div
            class="w-[260px] shrink-0 bg-white border-r border-gray-200 flex flex-col hidden md:flex z-20 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
            <div class="p-4">
                <button onclick="window.location.href='/dashboard'"
                    class="w-full flex items-center justify-center gap-2 bg-white border border-gray-200 hover:border-[#2d6a4f] hover:text-[#2d6a4f] hover:shadow-sm text-gray-700 px-4 py-2.5 rounded-[12px] transition-all font-semibold">
                    <i class="fa-solid fa-pen-to-square"></i> Đoạn chẩn đoán mới
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-3 space-y-1">
                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider px-3 mb-2 mt-2">Gần đây</div>

                @forelse($conversations as $conversation)
                    <div class="flex items-center gap-1 group px-2">
                        <a href="{{ route('dashboard', $conversation->id) }}"
                            class="flex-1 flex items-center gap-3 px-3 py-2.5 rounded-[12px] hover:bg-[#f8f9fa] transition-all {{ isset($currentConversation) && $currentConversation->id == $conversation->id ? 'bg-[#f0fdf4] text-[#2d6a4f] font-medium' : '' }}">
                            <i class="fa-regular fa-message text-sm"></i>
                            <span class="truncate w-32 text-[14px]">{{ $conversation->title }}</span>
                        </a>

                        <form action="{{ route('chat.destroy', $conversation->id) }}" method="POST"
                            onsubmit="return confirm('Xóa đoạn chat này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                                title="Xóa">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="px-3 py-2 text-sm text-gray-400 italic">Chưa có lịch sử</div>
                @endforelse
            </div>

            <div class="p-4 border-t border-gray-100 flex items-center gap-3 relative group">
                <div
                    class="w-9 h-9 rounded-full bg-[#2d6a4f] text-white flex items-center justify-center font-bold text-sm shadow-sm">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="text-sm font-bold text-gray-700 truncate flex-1">{{ Auth::user()->name }}</div>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition cursor-pointer p-2"
                        title="Đăng xuất">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 flex flex-col relative overflow-hidden bg-[#f8f9fa]">

            <div
                class="md:hidden p-4 border-b border-gray-200 flex justify-between items-center bg-white z-10 shadow-sm">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-green-50 flex justify-center items-center text-[#2d6a4f]"><i
                            class="fa-solid fa-robot"></i></div>
                    <span class="font-bold text-gray-800">Agri-Doctor</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-red-500"><i
                            class="fa-solid fa-arrow-right-from-bracket text-xl"></i></button>
                </form>
            </div>

            <div id="chatbox" class="flex-1 overflow-y-auto p-4 sm:p-6 flex flex-col items-center scroll-smooth">
                <div class="w-full max-w-4xl flex flex-col space-y-6 pb-32 pt-4">

                    @if($currentConversation)

                        @foreach($currentConversation->messages as $message)

                            @if($message->role === 'user')
                                <div class="flex items-end gap-2 self-end justify-end w-full max-w-[75%] animate-fade">
                                    <div
                                        class="bg-[#2d6a4f] text-white px-5 py-3.5 rounded-[18px] rounded-br-[2px] shadow-sm flex flex-col items-end">

                                        @if($message->getFirstMediaUrl('plant_images'))
                                            <img src="{{ $message->getFirstMediaUrl('plant_images') }}"
                                                class="rounded-[12px] mb-2 max-h-48 object-cover border border-white/20">
                                        @endif

                                        @if($message->content)
                                            <span class="whitespace-pre-wrap">{{ $message->content }}</span>
                                        @endif

                                    </div>
                                </div>

                            @else

                                @php $result = json_decode($message->content, true); @endphp

                                <div class="flex items-end gap-2 self-start w-full max-w-[75%] animate-fade">
                                    <div
                                        class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#2d6a4f] border shadow-sm">
                                        <i class="fa-solid fa-robot text-sm"></i>
                                    </div>

                                    <div
                                        class="bg-white border shadow-sm text-gray-800 px-5 py-3.5 rounded-[18px] rounded-bl-[2px] leading-relaxed text-[15px] w-full">

                                        <p class="mb-3 font-bold text-[#2d6a4f]">Kết quả chẩn đoán:</p>

                                        <div class="bg-[#f8f9fa] border border-gray-200 rounded-[12px] p-4 mb-3">
                                            <div class="mb-1 text-sm">
                                                <span class="text-gray-500">Cây:</span>
                                                <b>{{ $result['plant'] ?? '' }}</b>
                                            </div>

                                            <div class="text-[#2d6a4f] font-bold text-lg mb-2 italic underline">
                                                {{ $result['disease'] ?? '' }}
                                            </div>

                                            @if(isset($result['confidence']))
                                                <div class="text-sm text-gray-500">
                                                    Độ tin cậy: {{ round($result['confidence'] * 100, 1) }}%
                                                </div>
                                            @endif
                                        </div>

                                        <div class="bg-blue-50 border border-blue-100 rounded-[12px] p-4">
                                            <p class="text-blue-700 text-[14px]">
                                                {{ $result['treatment'] ?? '' }}
                                            </p>
                                        </div>

                                    </div>
                                </div>

                            @endif

                        @endforeach

                    @else

                        <div class="flex items-end gap-2 self-start w-full max-w-[85%] sm:max-w-[75%] animate-fade">
                            <div
                                class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#2d6a4f] shrink-0 mb-1 border border-gray-200 shadow-sm">
                                <i class="fa-solid fa-robot text-sm"></i>
                            </div>

                            <div
                                class="bg-white border border-gray-100 shadow-sm text-gray-800 px-5 py-3.5 rounded-[18px] rounded-bl-[2px] leading-relaxed text-[15px]">
                                Xin chào 👋 Hãy gửi ảnh lá cây để bắt đầu chẩn đoán
                            </div>
                        </div>

                    @endif

                </div>
            </div>

            <div id="image-preview-container"
                class="hidden absolute bottom-[90px] w-full flex justify-center z-10 pointer-events-none">
                <div class="w-full max-w-4xl px-4 flex">
                    <div
                        class="relative inline-block pointer-events-auto bg-white p-2 rounded-[16px] shadow-lg border border-gray-200 animate-fade">
                        <img id="preview-img" src=""
                            class="h-16 w-16 object-cover rounded-[8px] border border-gray-100">
                        <button type="button" onclick="clearSelectedImage()"
                            class="absolute -top-2 -right-2 bg-gray-800 text-white rounded-full w-5 h-5 flex items-center justify-center hover:bg-red-500 shadow-md transition-colors">
                            <i class="fa-solid fa-xmark text-[10px]"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div
                class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-[#f8f9fa] via-[#f8f9fa] to-transparent pt-10 pb-6 px-4 flex justify-center z-20 pointer-events-none">
                <div class="w-full max-w-4xl pointer-events-auto">
                    <form id="chat-form"
                        class="flex items-end gap-2 bg-white rounded-[24px] pl-2 pr-2 py-2 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.08)] border border-gray-200 focus-within:border-[#2d6a4f] focus-within:ring-2 focus-within:ring-[#2d6a4f]/20 transition-all duration-300"
                        onsubmit="handleSendMessage(event)">

                        <input type="file" id="image-upload" name="image" accept="image/*" class="hidden"
                            onchange="previewImage(event)">

                        <button type="button" onclick="document.getElementById('image-upload').click()"
                            class="w-10 h-10 mb-0.5 flex items-center justify-center text-gray-400 hover:text-[#2d6a4f] hover:bg-gray-50 rounded-full transition-colors shrink-0 outline-none">
                            <i class="fa-solid fa-paperclip text-lg"></i>
                        </button>

                        <textarea id="chat-input" rows="1" placeholder="Hỏi Agri-Doctor..."
                            class="flex-1 bg-transparent border-none focus:ring-0 resize-none py-3 px-2 text-gray-800 placeholder-gray-400 text-[15px] leading-relaxed"
                            oninput="this.style.height = '';this.style.height = Math.min(this.scrollHeight, 150) + 'px'"></textarea>

                        <button type="submit" id="send-btn"
                            class="w-10 h-10 mb-0.5 bg-[#2d6a4f] hover:bg-[#1b4332] text-white rounded-full transition-transform transform hover:scale-105 flex items-center justify-center shrink-0 disabled:opacity-50 disabled:scale-100 shadow-sm outline-none">
                            <i class="fa-solid fa-arrow-up text-sm"></i>
                        </button>
                    </form>
                    <div class="text-center mt-2 text-[11px] text-gray-400">
                        AI có thể mắc lỗi. Vui lòng kiểm tra lại thông tin trước khi áp dụng thực tế.
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const chatbox = document.getElementById('chatbox');
        const imageUpload = document.getElementById('image-upload');
        const chatInput = document.getElementById('chat-input');
        const previewContainer = document.getElementById('image-preview-container');
        const previewImg = document.getElementById('preview-img');
        const sendBtn = document.getElementById('send-btn');

        function scrollToBottom() {
            chatbox.scrollTop = chatbox.scrollHeight;
        }

        window.onload = scrollToBottom;

        function previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function clearSelectedImage() {
            imageUpload.value = '';
            previewImg.src = '';
            previewContainer.classList.add('hidden');
        }

        async function handleSendMessage(e) {
            e.preventDefault();

            const file = imageUpload.files[0];
            const message = chatInput.value.trim();

            if (!file) {
                alert('Vui lòng chọn ảnh lá cây');
                return;
            }

            sendBtn.disabled = true;
            const formData = new FormData();
            formData.append('image', file);
            if (message) formData.append('message', message);

            @if(isset($currentConversation))
                formData.append('conversation_id', '{{ $currentConversation->id }}');
            @endif

    try {
                const response = await fetch('{{ route("chat.predict") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.status === 'error') {
                    alert(data.message);
                } else if (data.conversation_id) {
                    window.location.href = '/dashboard/' + data.conversation_id;
                }

            } catch (error) {
                alert('Lỗi kết nối server AI');
            } finally {
                sendBtn.disabled = false;
                clearSelectedImage();
                chatInput.value = '';
            }
        }
    </script>
</body>

</html>