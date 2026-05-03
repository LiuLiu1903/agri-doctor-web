<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;
use App\Models\Message;

class AIController extends Controller
{
    public function index($id = null)
    {
        $conversations = Conversation::where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get();

        $currentConversation = $id
            ? Conversation::with('messages')
            ->where('user_id', Auth::id())
            ->findOrFail($id)
            : null;

        return view('dashboard', compact(
            'conversations',
            'currentConversation'
        ));
    }

    public function chatPredict(Request $request)
{
    $request->validate([
        'image' => 'required|image|max:10240',
        'message' => 'nullable|string',
        'conversation_id' => 'nullable|integer'
    ]);

    $file = $request->file('image');
    $fileContent = file_get_contents($file->getRealPath());
    $fileName = $file->getClientOriginalName();
    $conversationId = $request->conversation_id;

    $response = Http::attach('file', $fileContent, $fileName)
        ->post('http://127.0.0.1:8001/predict');

    $ai = $response->json();

    if (isset($ai['status']) && $ai['status'] === 'error') {
        return response()->json([
            'status' => 'error',
            'message' => $ai['message'] ?? 'Ảnh không hợp lệ. Vui lòng chụp lại lá cây.'
        ]);
    }

    if (!$conversationId) {
        $conversation = Conversation::create([
            'user_id' => Auth::id(),
            'title' => ($ai['plant'] ?? 'Cây') . ' - ' . ($ai['disease'] ?? 'Đang khám')
        ]);
        $conversationId = $conversation->id;
    }

    $userMessage = Message::create([
        'conversation_id' => $conversationId,
        'role' => 'user',
        'content' => $request->message ?: 'Ảnh chẩn đoán'
    ]);

    $userMessage->addMedia($file)->toMediaCollection('plant_images');

    Message::create([
        'conversation_id' => $conversationId,
        'role' => 'assistant',
        'content' => json_encode($ai)
    ]);

    return response()->json([
        'status' => 'success',
        'conversation_id' => $conversationId,
        'plant' => $ai['plant'] ?? '',
        'disease' => $ai['disease'] ?? '',
        'treatment' => $ai['treatment'] ?? '',
        'confidence' => $ai['confidence'] ?? 0
    ]);
}

    public function destroy($id)
    {
        $conversation = Conversation::where('user_id', Auth::id())->findOrFail($id);
        $conversation->delete();

        return redirect()->route('dashboard')->with('success', 'Đã xóa đoạn chat');
    }
}
