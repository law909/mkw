<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elfelejtett jelszó</title>
    {if ($developer)}
        <script src="https://cdn.tailwindcss.com"></script>
    {else}
        <link rel="stylesheet" href="/themes/main/mpt/mpt.css">
    {/if}
    <script src="/js/main/mpt/passwordreset.js" defer></script>
    <script src="/js/alpine/cdn.min.js" defer></script>
    <script src="/js/iodine/iodine.min.umd.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<div class="bg-white p-6 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-semibold mb-6 text-center">Új jelszó beállítása</h2>
    <form x-data="resetPasswordForm" @submit.prevent="submitForm">
        <!-- New Password -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Új jelszó</label>
            <input type="password" x-model="password" required
                   class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                   :class="{ 'border-red-500': errors.password }">
            <p class="text-red-500 text-sm mt-1" x-show="errors.password" x-text="errors.password"></p>
        </div>
        <!-- Confirm Password -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Új jelszó megerősítése</label>
            <input type="password" x-model="confirmPassword" required
                   class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                   :class="{ 'border-red-500': errors.confirmPassword }">
            <p class="text-red-500 text-sm mt-1" x-show="errors.confirmPassword" x-text="errors.confirmPassword"></p>
        </div>
        <!-- Submit Button -->
        <div>
            <button type="submit"
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Jelszó beállítása
            </button>
        </div>
        <!-- Back to Login Link -->
        <div class="mt-4 text-center">
            <a href="login.html" class="text-sm text-blue-500 hover:text-blue-700">Vissza a bejelentkezéshez</a>
        </div>
    </form>
</div>
</body>
</html>
