<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChatMessageController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Chat $chat, Request $request)
    {
        $animal = $request->user()->animal;
        if (! $animal || ! $chat->participants()->whereKey($animal->id)->exists()) {
            return response()->json([
                'message' => 'Доступ только к чатам, где участвует ваш зверь.',
            ], Response::HTTP_FORBIDDEN);
        }

        return MessageResource::collection(
            $chat->messages()
                ->with('sender')
                ->orderBy('created_at')
                ->get()
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreMessageRequest $request, Chat $chat)
    {
        $payload = $request->validated();
        $sender = $request->user()->animal;
        if (! $sender) {
            return response()->json([
                'message' => 'Сначала создайте профиль зверя.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $isParticipant = $chat->participants()
            ->whereKey($sender->id)
            ->exists();
        if (! $isParticipant) {
            return response()->json([
                'message' => 'Отправитель не является участником этого чата.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $sender->id,
            'body' => $payload['body'],
        ])
            ->load('sender');

        return (new MessageResource($message))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
