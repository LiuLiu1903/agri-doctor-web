<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Diagnosis;

class AIController extends Controller
{
    public function chatPredict(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'message' => 'nullable|string'
        ]);

        try {
            if ($request->hasFile('image')) {

                $file = $request->file('image');

                /** * BƯỚC QUAN TRỌNG: 
                 * Phải đọc nội dung file ngay lập tức trước khi Spatie di chuyển file đi
                 */
                $fileContents = file_get_contents($file->getRealPath());
                $fileName = $file->getClientOriginalName();

                // 1. Lưu bản ghi vào Database
                $diagnosis = Diagnosis::create([
                    'disease_name' => 'Đang phân tích...',
                    'confidence' => 0
                ]);

                // 2. Thư viện Spatie di chuyển file vào storage (Sau dòng này getRealPath() sẽ bị trống)
                $media = $diagnosis->addMedia($file)
                    ->toMediaCollection('plant_images');

                // 3. Gửi nội dung đã đọc sang Server Python (cổng 8001)
                $response = Http::attach(
                    'file',
                    $fileContents, // Gửi biến đã lưu nội dung
                    $fileName
                )->post('http://127.0.0.1:8001/predict');

                if ($response->successful()) {
                    $ai = $response->json();

                    // 4. Cập nhật kết quả AI trả về vào DB
                    $diagnosis->update([
                        'disease_name' => $ai['prediction'] ?? 'Không xác định',
                        'confidence' => $ai['confidence'] ?? 0
                    ]);

                    return response()->json([
                        'type' => 'prediction',
                        'prediction' => $ai['prediction'] ?? 'Không xác định',
                        'confidence' => $ai['confidence'] ?? 0,
                        'image' => $diagnosis->getFirstMediaUrl('plant_images')
                    ]);
                }

                return response()->json(['error' => 'AI Server không phản hồi'], 500);
            }

            // Nếu chỉ gửi tin nhắn chữ không có ảnh
            return response()->json([
                'type' => 'text',
                'reply' => 'Bạn hãy gửi ảnh để mình phân tích 🌱'
            ]);

        } catch (\Exception $e) {
            // Trả về lỗi chi tiết để dễ dàng kiểm tra
            return response()->json([
                'error' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }
}