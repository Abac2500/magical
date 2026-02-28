# Magical Forest API

Backend API для социальной сети жителей волшебного леса.  
Проект сделан на Laravel 12+ и включает аутентификацию, профили зверей, дружбу, чаты и механизм рекомендаций.

## Что реализовано

- API с префиксом `/api/v1`
- Регистрация и вход пользователей (Sanctum Bearer Token)
- Регистрация профиля зверя
- Добавление друзей
- Личные и групповые чаты, сообщения до 128 символов
- Рекомендации друзей (до 10 записей) по правилам ТЗ
- Swagger UI и админ-панель Filament

## ТЗ (с главной страницы)

### 1) Регистрация зверя

- Имя — обязательно
- Прозвище — опционально
- Вид животного — каталог: Сова, Заяц, Волк, Мышь (расширяемый)
- Пол — М или Ж
- Дата рождения — обязательно
- Имя лучшего друга — обязательно

### 2) Функционал сети

- Можно заводить друзей
- Можно обмениваться сообщениями в чате с одним или несколькими друзьями
- Сообщение — текст до 128 символов

### 3) Механизм рекомендаций (до 10 записей)

1. Сначала звери, чье имя совпадает или близко к имени лучшего друга.
2. Если не найдено — звери, чье прозвище совпадает или близко к имени лучшего друга.
3. Если не найдено — звери того же вида и противоположного пола.
4. Если не найдено — звери того же вида.

### 4) Дополнительные требования

- Для механизма рекомендаций есть seeder и тесты
- Дополнительные улучшения возможны, но базовое ТЗ должно быть выполнено полностью

## Стек

- PHP 8.5
- Laravel 12
- Laravel Sanctum
- Filament 5
- L5-Swagger
- Pest
- Vite + Tailwind CSS

## Быстрый старт

```bash
composer setup
php artisan db:seed
composer dev
```

Для наполнения тестовыми данными рекомендаций:

```bash
php artisan db:seed --class=RecommendationScenarioSeeder
```

## Полезные точки входа

- Главная с описанием ТЗ: `/`
- API: `/api/v1`
- Swagger UI: `/api/documentation`
- Админ-панель Filament: `/admin`
- Тестовый логин админа (после `php artisan db:seed`): `admin@magical.local / password`

В Swagger: сначала вызовите `/api/v1/auth/login`, затем в `Authorize` вставьте только `<token>`.

## Основные эндпоинты API

Публичные:

- `GET /api/v1/species`
- `POST /api/v1/auth/register`
- `POST /api/v1/auth/login`

Требуют Bearer-токен:

- `POST /api/v1/auth/logout`
- `GET /api/v1/auth/me`
- `POST /api/v1/species`
- `POST /api/v1/animals/register`
- `GET /api/v1/animals/me`
- `GET /api/v1/animals/me/friends`
- `POST /api/v1/friendships`
- `GET /api/v1/animals/me/recommendations`
- `POST /api/v1/chats`
- `GET /api/v1/chats/{chat}/messages`
- `POST /api/v1/chats/{chat}/messages`

## Тесты

```bash
composer test
```
