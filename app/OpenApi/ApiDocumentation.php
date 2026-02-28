<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Magical Forest Social Network API',
    description: 'Swagger документация API. Шаги: вызовите /api/v1/auth/register или /api/v1/auth/login, скопируйте access_token, нажмите Authorize и вставьте только token (префикс Bearer Swagger добавит автоматически).'
)]
#[OA\Server(url: '/', description: 'Current server')]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Token',
    description: 'Нажмите Authorize и вставьте только token из /api/v1/auth/register или /api/v1/auth/login (Bearer добавится автоматически).'
)]
#[OA\Tag(name: 'Auth', description: 'Регистрация и токены')]
#[OA\Tag(name: 'Species', description: 'Каталог видов')]
#[OA\Tag(name: 'Animals', description: 'Профиль зверя пользователя')]
#[OA\Tag(name: 'Friendships', description: 'Дружба')]
#[OA\Tag(name: 'Recommendations', description: 'Рекомендации')]
#[OA\Tag(name: 'Chats', description: 'Чаты и сообщения')]
class ApiDocumentation
{
    #[OA\Post(
        path: '/api/v1/auth/register',
        operationId: 'authRegister',
        tags: ['Auth'],
        summary: 'Регистрация пользователя',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Forest User'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'forest@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'password'),
                    new OA\Property(property: 'device_name', type: 'string', example: 'swagger-ui'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Создано',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
                        new OA\Property(property: 'access_token', type: 'string', example: '1|sanctum-token'),
                    ]
                )
            ),
        ]
    )]
    public function register(): void
    {
    }

    #[OA\Post(
        path: '/api/v1/auth/login',
        operationId: 'authLogin',
        tags: ['Auth'],
        summary: 'Логин пользователя',
        description: 'Возвращает тот же access_token пользователя, пока не вызван /api/v1/auth/logout.',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'forest@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
                    new OA\Property(property: 'device_name', type: 'string', example: 'swagger-ui'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успех',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
                        new OA\Property(property: 'access_token', type: 'string', example: '1|sanctum-token'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Неверные учетные данные'),
        ]
    )]
    public function login(): void
    {
    }

    #[OA\Get(
        path: '/api/v1/auth/me',
        operationId: 'authMe',
        tags: ['Auth'],
        summary: 'Текущий пользователь',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Успех'),
            new OA\Response(response: 401, description: 'Не авторизован'),
        ]
    )]
    public function me(): void
    {
    }

    #[OA\Post(
        path: '/api/v1/auth/logout',
        operationId: 'authLogout',
        tags: ['Auth'],
        summary: 'Выход и отзыв токена',
        description: 'Удаляет токен пользователя; при следующем логине будет выдан новый.',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Успех'),
            new OA\Response(response: 401, description: 'Не авторизован'),
        ]
    )]
    public function logout(): void
    {
    }

    #[OA\Get(
        path: '/api/v1/species',
        operationId: 'speciesIndex',
        tags: ['Species'],
        summary: 'Список видов',
        responses: [
            new OA\Response(response: 200, description: 'Успех'),
        ]
    )]
    public function speciesIndex(): void
    {
    }

    #[OA\Post(
        path: '/api/v1/species',
        operationId: 'speciesStore',
        tags: ['Species'],
        summary: 'Создать вид',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Лиса'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Создано'),
            new OA\Response(response: 401, description: 'Не авторизован'),
        ]
    )]
    public function speciesStore(): void
    {
    }

    #[OA\Post(
        path: '/api/v1/animals/register',
        operationId: 'animalsRegister',
        tags: ['Animals'],
        summary: 'Создать профиль зверя текущего пользователя',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'species_id', 'gender', 'birth_date', 'best_friend_name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Луна'),
                    new OA\Property(property: 'nickname', type: 'string', nullable: true, example: 'Лу'),
                    new OA\Property(property: 'species_id', type: 'integer', example: 1),
                    new OA\Property(property: 'gender', type: 'string', enum: ['M', 'Ж'], example: 'Ж'),
                    new OA\Property(property: 'birth_date', type: 'string', format: 'date', example: '2020-03-10'),
                    new OA\Property(property: 'best_friend_name', type: 'string', example: 'Ваня'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Создано'),
            new OA\Response(response: 422, description: 'Профиль уже существует/ошибка валидации'),
        ]
    )]
    public function animalsRegister(): void
    {
    }

    #[OA\Get(
        path: '/api/v1/animals/me',
        operationId: 'animalsMe',
        tags: ['Animals'],
        summary: 'Профиль текущего пользователя',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Успех'),
            new OA\Response(response: 404, description: 'Профиль не найден'),
        ]
    )]
    public function animalsMe(): void
    {
    }

    #[OA\Get(
        path: '/api/v1/animals/me/friends',
        operationId: 'friendsIndex',
        tags: ['Friendships'],
        summary: 'Список друзей текущего пользователя',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Успех'),
            new OA\Response(response: 422, description: 'Сначала создайте профиль зверя'),
        ]
    )]
    public function friendsIndex(): void
    {
    }

    #[OA\Post(
        path: '/api/v1/friendships',
        operationId: 'friendshipsStore',
        tags: ['Friendships'],
        summary: 'Добавить друга текущему зверю',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['friend_id'],
                properties: [
                    new OA\Property(property: 'friend_id', type: 'integer', example: 2),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Создано'),
            new OA\Response(response: 422, description: 'Ошибка валидации/логики'),
        ]
    )]
    public function friendshipsStore(): void
    {
    }

    #[OA\Get(
        path: '/api/v1/animals/me/recommendations',
        operationId: 'recommendationsIndex',
        tags: ['Recommendations'],
        summary: 'Рекомендации друзей текущего пользователя',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Успех'),
            new OA\Response(response: 422, description: 'Сначала создайте профиль зверя'),
        ]
    )]
    public function recommendationsIndex(): void
    {
    }

    #[OA\Post(
        path: '/api/v1/chats',
        operationId: 'chatsStore',
        tags: ['Chats'],
        summary: 'Создать чат',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['participant_ids'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', nullable: true, example: 'Лесной чат'),
                    new OA\Property(
                        property: 'participant_ids',
                        type: 'array',
                        items: new OA\Items(type: 'integer', example: 2)
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Создано'),
            new OA\Response(response: 422, description: 'Ошибка валидации/логики'),
        ]
    )]
    public function chatsStore(): void
    {
    }

    #[OA\Get(
        path: '/api/v1/chats/{chat}/messages',
        operationId: 'chatMessagesIndex',
        tags: ['Chats'],
        summary: 'Сообщения чата',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'chat', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Успех'),
            new OA\Response(response: 403, description: 'Нет доступа'),
        ]
    )]
    public function chatMessagesIndex(): void
    {
    }

    #[OA\Post(
        path: '/api/v1/chats/{chat}/messages',
        operationId: 'chatMessagesStore',
        tags: ['Chats'],
        summary: 'Отправить сообщение в чат',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'chat', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['body'],
                properties: [
                    new OA\Property(property: 'body', type: 'string', maxLength: 128, example: 'Привет!'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Создано'),
            new OA\Response(response: 422, description: 'Ошибка валидации/логики'),
        ]
    )]
    public function chatMessagesStore(): void
    {
    }
}
