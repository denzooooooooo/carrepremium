<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ChatbotConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Send a support message from chatbot
     */
    public function sendSupportMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000',
            'user_email' => 'nullable|email',
            'user_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create conversation if it doesn't exist
            $conversation = ChatbotConversation::firstOrCreate([
                'session_id' => $request->session_id ?? session()->getId(),
            ], [
                'user_email' => $request->user_email,
                'user_name' => $request->user_name,
                'status' => 'open',
                'channel' => 'chatbot'
            ]);

            // Save the message
            $message = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'user',
                'message' => $request->message,
                'is_support_request' => true
            ]);

            // Log the support request (email functionality removed for simplicity)
            Log::info('New support request from chatbot', [
                'conversation_id' => $conversation->id,
                'message' => $request->message,
                'user_email' => $request->user_email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Votre message a été envoyé à notre équipe de support. Nous vous répondrons dans les plus brefs délais.',
                'conversation_id' => $conversation->id
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot support message error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Get conversation messages (for admin panel)
     */
    public function getConversation($conversationId)
    {
        try {
            $conversation = ChatbotConversation::with('messages')->findOrFail($conversationId);

            return response()->json([
                'success' => true,
                'conversation' => $conversation
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Conversation non trouvée'
            ], 404);
        }
    }

    /**
     * Get all conversations (for admin panel)
     */
    public function getConversations(Request $request)
    {
        try {
            $query = ChatbotConversation::with('messages')
                ->orderBy('updated_at', 'desc');

            if ($request->status) {
                $query->where('status', $request->status);
            }

            $conversations = $query->paginate(20);

            return response()->json([
                'success' => true,
                'conversations' => $conversations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des conversations'
            ], 500);
        }
    }

    /**
     * Update conversation status (for admin panel)
     */
    public function updateConversationStatus(Request $request, $conversationId)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:open,closed,in_progress',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Statut invalide',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $conversation = ChatbotConversation::findOrFail($conversationId);
            $conversation->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Send reply to conversation (for admin panel)
     */
    public function sendReply(Request $request, $conversationId)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Message requis',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $conversation = ChatbotConversation::findOrFail($conversationId);

            // Save admin reply
            $message = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'admin',
                'message' => $request->message,
                'admin_id' => auth()->id()
            ]);

            // Update conversation timestamp
            $conversation->update(['updated_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Réponse envoyée avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de la réponse'
            ], 500);
        }
    }
}
