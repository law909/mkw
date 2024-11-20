<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    {if ($developer)}
        <script src="https://cdn.tailwindcss.com"></script>
    {else}
        <link rel="stylesheet" href="/themes/main/mpt/mpt.css">
    {/if}
    <script src="/js/main/mpt/login.js" defer></script>
    <script src="/js/alpine/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<div class="bg-white p-6 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-semibold mb-6 text-center">Bejelentkezés</h2>
    <form x-data="loginForm" @submit.prevent="submitForm">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email cím</label>
            <input type="email" x-model="email" required class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                   :class="{ 'border-red-500': errors.email }">
            <p class="text-red-500 text-sm mt-1" x-show="errors.email" x-text="errors.email"></p>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Jelszó</label>
            <input type="password" x-model="password" required class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                   :class="{ 'border-red-500': errors.password }">
            <p class="text-red-500 text-sm mt-1" x-show="errors.password" x-text="errors.password"></p>
        </div>
        <div class="flex items-center mb-4">
            <input type="checkbox" x-model="rememberMe" id="rememberMe" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="rememberMe" class="ml-2 block text-sm text-gray-900">
                Emlékezz rám
            </label>
        </div>
        <div>
            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Bejelentkezés</button>
        </div>
        <div class="mt-4 text-center">
            <a href="#" class="text-sm text-blue-500 hover:text-blue-700">Elfelejtetted a jelszavad?</a>
        </div>
    </form>
</div>
</body>
</html>
