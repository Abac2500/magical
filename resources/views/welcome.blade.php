<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Magical Forest API — Тестовое задание</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
<main class="mx-auto w-full max-w-5xl px-5 py-8 sm:px-6 lg:px-8">
	<header class="mb-6">
		<h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Magical Forest Social Network — Тестовое задание</h1>
		<p class="mt-3 leading-7 text-slate-600 dark:text-slate-300">Ниже ТЗ в структурированном виде. Проект: Laravel
			12+ + Filament 5+, бэкенд только в формате API для социальной сети жителей волшебного леса.</p>
	</header>
	<section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
		<h2 class="text-xl font-semibold">Техническое задание (структурировано)</h2>
		<h3 class="mt-6 text-base font-semibold">1) Регистрация зверя</h3>
		<ul class="mt-3 list-disc space-y-1 pl-5 text-slate-600 dark:text-slate-300">
			<li><strong>Имя</strong> — обязательно</li>
			<li><strong>Прозвище</strong> — опционально</li>
			<li><strong>Вид животного</strong> — каталог: Сова, Заяц, Волк, Мышь (каталог расширяемый)</li>
			<li><strong>Пол</strong> — М или Ж</li>
			<li><strong>Дата рождения</strong> — обязательно</li>
			<li><strong>Имя лучшего друга</strong> — обязательно</li>
		</ul>
		<h3 class="mt-6 text-base font-semibold">2) Функционал сети</h3>
		<ul class="mt-3 list-disc space-y-1 pl-5 text-slate-600 dark:text-slate-300">
			<li>Можно заводить друзей</li>
			<li>Можно обмениваться сообщениями в виде чата с одним или несколькими друзьями</li>
			<li>Сообщение — текст до <strong>128</strong> символов</li>
		</ul>
		<h3 class="mt-6 text-base font-semibold">3) Механизм рекомендаций (до 10 записей)</h3>
		<ol class="mt-3 list-decimal space-y-1 pl-5 text-slate-600 dark:text-slate-300">
			<li>Сначала звери, чье <strong>имя</strong> совпадает или очень близко с именем лучшего друга (пример: Вася
				и Ваня). Если таких нет — применяется следующее правило.
			</li>
			<li>Далее звери, чье <strong>прозвище</strong> совпадает или очень близко с именем лучшего друга. Если таких
				нет — применяется следующее правило.
			</li>
			<li>Далее звери <strong>того же вида и противоположного пола</strong>. Если таких нет — применяется
				следующее правило.
			</li>
			<li>Далее звери <strong>того же вида</strong>.</li>
		</ol>
		<h3 class="mt-6 text-base font-semibold">4) Обязательные дополнительные требования</h3>
		<ul class="mt-3 list-disc space-y-1 pl-5 text-slate-600 dark:text-slate-300">
			<li>Для механизма рекомендаций должны быть <strong>seeder</strong> и <strong>тесты</strong>, покрывающие
				основную часть механизма
			</li>
			<li>Можно добавлять/усложнять по желанию, но основная задача должна быть выполнена полностью</li>
		</ul>
		<h3 class="mt-6 text-base font-semibold">Полезные точки входа</h3>
		<p class="mt-3 leading-7 text-slate-600 dark:text-slate-300">
			API префикс: <code
					class="rounded bg-sky-100 px-1.5 py-0.5 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">/api/v1</code><br>
			Swagger UI: <code
					class="rounded bg-sky-100 px-1.5 py-0.5 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">/api/documentation</code><br>
			Админ-панель Filament: <code
					class="rounded bg-sky-100 px-1.5 py-0.5 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">/admin</code><br>
			Логин для теста (Filament): <code
					class="rounded bg-sky-100 px-1.5 py-0.5 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">admin@magical.local
				/ password</code><br>
			API защищен Bearer-токеном пользователя (Sanctum)<br>
			В Swagger: сначала вызовите <code
					class="rounded bg-sky-100 px-1.5 py-0.5 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">/api/v1/auth/login</code>,
			затем нажмите <code
					class="rounded bg-sky-100 px-1.5 py-0.5 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">Authorize</code>
			и вставьте только <code
					class="rounded bg-sky-100 px-1.5 py-0.5 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">&lt;token&gt;</code>
		</p>
		<div class="mt-6 flex flex-wrap gap-3">
			<a class="inline-flex items-center rounded-lg border border-sky-500 px-4 py-2 text-sm font-semibold text-sky-600 transition hover:bg-sky-50 dark:border-sky-400 dark:text-sky-300 dark:hover:bg-sky-900/30"
			   href="/api/documentation">Открыть Swagger</a>
			<a class="inline-flex items-center rounded-lg border border-sky-500 px-4 py-2 text-sm font-semibold text-sky-600 transition hover:bg-sky-50 dark:border-sky-400 dark:text-sky-300 dark:hover:bg-sky-900/30"
			   href="/admin">Открыть Filament</a>
		</div>
	</section>
</main>
</body>
</html>
