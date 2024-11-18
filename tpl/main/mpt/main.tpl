<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Adataim</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3" defer></script>
    <script src="https://unpkg.com/iodine/dist/iodine.min.js"></script>
    <script src="/js/main/mpt/main.js" defer></script>
</head>
<body class="bg-gray-100" x-data="mainData">
<!-- Navigation Menu -->
<nav class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between">
        <div>
            <button class="mr-4" :class="{ 'font-bold': page === 'myData' }" @click="page = 'myData'">Adataim</button>
            <button class="mr-4" :class="{ 'font-bold': page === 'finances' }" @click="page = 'finances'">Pénzügyek</button>
            <button class="mr-4" :class="{ 'font-bold': page === 'changePassword' }" @click="page = 'changePassword'">Jelszó módosítás</button>
        </div>
        <div>
            <button class="text-red-500" @click="logout()">Kilépés</button>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mx-auto px-4 py-6">
    <!-- My Data Page -->
    <div x-show="page === 'myData'">
        <!-- First Group: Tagság -->
        <div class="bg-white p-6 mb-4 shadow rounded">
            <h2 class="text-lg font-semibold mb-4">Tagság</h2>
            <div class="flex flex-wrap -mx-2">
                <!-- Tagság kezdete -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tagság kezdete</label>
                    <p x-text="data.tagsagKezdete || '—'" class="mt-1"></p>
                </div>
                <!-- Tagkártya száma -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tagkártya száma</label>
                    <p x-text="data.tagkartyaSzama || '—'" class="mt-1"></p>
                </div>
            </div>
        </div>

        <!-- Second Group: Személyes Adatok -->
        <div class="bg-white p-6 mb-4 shadow rounded">
            <h2 class="text-lg font-semibold mb-4">Személyes Adatok</h2>
            <div class="flex flex-wrap -mx-2">
                <!-- Megszólítás -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Megszólítás</label>
                    <input type="text" x-model="data.megszolitas" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <!-- Név -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Név</label>
                    <input type="text" x-model="data.nev" class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                           :class="{ 'border-red-500': errors.nev }">
                    <p class="text-red-500 text-sm mt-1" x-show="errors.nev" x-text="errors.nev"></p>
                </div>
                <!-- Vezetéknév -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Vezetéknév</label>
                    <input type="text" x-model="data.vezeteknev" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <!-- Keresztnév -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Keresztnév</label>
                    <input type="text" x-model="data.keresztnev" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
            </div>
        </div>

        <!-- Third Group: Elérhetőség -->
        <div class="bg-white p-6 mb-4 shadow rounded">
            <h2 class="text-lg font-semibold mb-4">Elérhetőség</h2>
            <div class="flex flex-wrap -mx-2">
                <!-- Email -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" x-model="data.email" class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                           :class="{ 'border-red-500': errors.email }">
                    <p class="text-red-500 text-sm mt-1" x-show="errors.email" x-text="errors.email"></p>
                </div>
                <!-- Privát Email -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Privát Email</label>
                    <input type="email" x-model="data.privatEmail" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <!-- Telefon -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Telefon</label>
                    <input type="text" x-model="data.telefon" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
            </div>
        </div>

        <!-- Fourth Group: Számlázási Adatok -->
        <div class="bg-white p-6 mb-4 shadow rounded">
            <h2 class="text-lg font-semibold mb-4">Számlázási Adatok</h2>
            <div class="flex flex-wrap -mx-2">
                <!-- Számlázási név -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Számlázási név</label>
                    <input type="text" x-model="data.szamlazasiNev" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <!-- Adószám -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Adószám</label>
                    <input type="text" x-model="data.adoszam" placeholder="99999999-9-99" class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                           :class="{ 'border-red-500': errors.adoszam }">
                    <p class="text-red-500 text-sm mt-1" x-show="errors.adoszam" x-text="errors.adoszam"></p>
                </div>
            </div>
            <!-- Cím -->
            <div class="mt-4">
                <h3 class="text-md font-semibold mb-2">Cím</h3>
                <div class="flex flex-wrap -mx-2">
                    <!-- Irányítószám -->
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="block text-sm font-medium text-gray-700">Irányítószám</label>
                        <input type="text" x-model="data.cim.iranyitoszam" maxlength="10" class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                               :class="{ 'border-red-500': errors['cim.iranyitoszam'] }">
                        <p class="text-red-500 text-sm mt-1" x-show="errors['cim.iranyitoszam']" x-text="errors['cim.iranyitoszam']"></p>
                    </div>
                    <!-- Város -->
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="block text-sm font-medium text-gray-700">Város</label>
                        <input type="text" x-model="data.cim.varos" class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                               :class="{ 'border-red-500': errors['cim.varos'] }">
                        <p class="text-red-500 text-sm mt-1" x-show="errors['cim.varos']" x-text="errors['cim.varos']"></p>
                    </div>
                    <!-- Utca -->
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="block text-sm font-medium text-gray-700">Utca</label>
                        <input type="text" x-model="data.cim.utca" class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                               :class="{ 'border-red-500': errors['cim.utca'] }">
                        <p class="text-red-500 text-sm mt-1" x-show="errors['cim.utca']" x-text="errors['cim.utca']"></p>
                    </div>
                    <!-- Házszám -->
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="block text-sm font-medium text-gray-700">Házszám</label>
                        <input type="text" x-model="data.cim.hazszam" class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                               :class="{ 'border-red-500': errors['cim.hazszam'] }">
                        <p class="text-red-500 text-sm mt-1" x-show="errors['cim.hazszam']" x-text="errors['cim.hazszam']"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fifth Group: Munkahely és Lakcím -->
        <div class="bg-white p-6 mb-4 shadow rounded">
            <h2 class="text-lg font-semibold mb-4">Munkahely és Lakcím</h2>
            <!-- Munkahely -->
            <div class="flex flex-wrap -mx-2">
                <!-- Munkahely neve -->
                <div class="px-2 w-full">
                    <label class="block text-sm font-medium text-gray-700">Munkahely neve</label>
                    <input type="text" x-model="data.munkahelyNeve" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
            </div>
            <!-- Munkahely címe -->
            <div class="mt-4">
                <h3 class="text-md font-semibold mb-2">Munkahely címe</h3>
                <div class="flex flex-wrap -mx-2">
                    <!-- Irányítószám -->
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="block text-sm font-medium text-gray-700">Irányítószám</label>
                        <input type="text" x-model="data.munkahelyCim.iranyitoszam" maxlength="10"
                               class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                    <!-- Város -->
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="block text-sm font-medium text-gray-700">Város</label>
                        <input type="text" x-model="data.munkahelyCim.varos" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                    <!-- Utca -->
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="block text-sm font-medium text-gray-700">Utca</label>
                        <input type="text" x-model="data.munkahelyCim.utca" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                    <!-- Házszám -->
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="block text-sm font-medium text-gray-700">Házszám</label>
                        <input type="text" x-model="data.munkahelyCim.hazszam" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                </div>
            </div>
            <!-- Lakcím -->
            <div class="mt-4">
                <h3 class="text-md font-semibold mb-2">Lakcím</h3>
                <div class="flex flex-wrap -mx-2">
                    <!-- Irányítószám -->
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="block text-sm font-medium text-gray-700">Irányítószám</label>
                        <input type="text" x-model="data.lakcim.iranyitoszam" maxlength="10" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                    <!-- Város -->
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="block text-sm font-medium text-gray-700">Város</label>
                        <input type="text" x-model="data.lakcim.varos" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                    <!-- Utca -->
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="block text-sm font-medium text-gray-700">Utca</label>
                        <input type="text" x-model="data.lakcim.utca" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                    <!-- Házszám -->
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="block text-sm font-medium text-gray-700">Házszám</label>
                        <input type="text" x-model="data.lakcim.hazszam" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sixth Group: Végzettség -->
        <div class="bg-white p-6 mb-4 shadow rounded">
            <h2 class="text-lg font-semibold mb-4">Végzettség</h2>
            <div class="flex flex-wrap -mx-2">
                <!-- Végzettség -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Végzettség</label>
                    <input type="text" x-model="data.vegzettseg" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <!-- Tudományos fokozat -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tudományos fokozat</label>
                    <input type="text" x-model="data.tudomanyosFokozat" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <!-- Diploma éve -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Diploma éve</label>
                    <input type="number" x-model="data.diplomaEve" maxlength="4" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <!-- Diploma iskolája -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Diploma iskolája</label>
                    <input type="text" x-model="data.diplomaIskolaja" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
                <!-- Egyéb diplomák -->
                <div class="px-2 w-full">
                    <label class="block text-sm font-medium text-gray-700">Egyéb diplomák</label>
                    <input type="text" x-model="data.egyebDiplomak" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
            </div>
        </div>

        <!-- Seventh Group: Születési Dátum -->
        <div class="bg-white p-6 mb-4 shadow rounded">
            <h2 class="text-lg font-semibold mb-4">Születési Dátum</h2>
            <div class="flex flex-wrap -mx-2">
                <!-- Születési dátum -->
                <div class="px-2 w-full">
                    <label class="block text-sm font-medium text-gray-700">Születési dátum</label>
                    <input type="date" x-model="data.szuletesiDatum" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>
            </div>
        </div>

        <!-- Eighth Group: Tagság Részletei -->
        <div class="bg-white p-6 mb-4 shadow rounded">
            <h2 class="text-lg font-semibold mb-4">Tagság Részletei</h2>
            <div class="flex flex-wrap -mx-2">
                <!-- Tagság forma -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tagság forma</label>
                    <select x-model="data.tagsagForma" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option value="">Válassz</option>
                        <option value="rendestag">Rendes tag</option>
                        <option value="parolotag">Pártoló tag</option>
                    </select>
                </div>
                <!-- Tagozat -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Tagozat</label>
                    <select x-model="data.tagozat" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option value="">Válassz</option>
                        <option value="fiatalok">Fiatalok</option>
                        <option value="kozepkoruak">Középkorúak</option>
                        <option value="idosek">Idősek</option>
                    </select>
                </div>
                <!-- Szekció 1 -->
                <div class="px-2 w-full sm:w-1/3">
                    <label class="block text-sm font-medium text-gray-700">Szekció 1</label>
                    <select x-model="data.szekcio1" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option value="">Válassz</option>
                        <!-- Add options as needed -->
                    </select>
                </div>
                <!-- Szekció 2 -->
                <div class="px-2 w-full sm:w-1/3">
                    <label class="block text-sm font-medium text-gray-700">Szekció 2</label>
                    <select x-model="data.szekcio2" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option value="">Válassz</option>
                        <!-- Add options as needed -->
                    </select>
                </div>
                <!-- Szekció 3 -->
                <div class="px-2 w-full sm:w-1/3">
                    <label class="block text-sm font-medium text-gray-700">Szekció 3</label>
                    <select x-model="data.szekcio3" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option value="">Válassz</option>
                        <!-- Add options as needed -->
                    </select>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="mt-6">
            <button @click="saveData()" class="bg-blue-500 text-white px-4 py-2 rounded">Mentés</button>
        </div>
    </div>

    <!-- Finances Page -->
    <div x-show="page === 'finances'">
        <!-- Finances content remains unchanged -->
        <div class="bg-white p-6 shadow rounded">
            <h2 class="text-lg font-semibold mb-4">Pénzügyek</h2>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Év</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Típus</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Összeg</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bizonylatszám</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dátum</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="item in finances" :key="item[3]">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="item[0]"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="item[1]"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="item[2]"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="item[3]"></td>
                        <td class="px-6 py-4 whitespace-nowrap" x-text="item[4]"></td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Change Password Page -->
    <div x-show="page === 'changePassword'">
        <!-- Change Password content remains unchanged -->
        <div class="bg-white p-6 shadow rounded">
            <h2 class="text-lg font-semibold mb-4">Jelszó módosítás</h2>
            <div class="flex flex-wrap -mx-2">
                <!-- New Password -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Új jelszó</label>
                    <input type="password" x-model="password.new" class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                           :class="{ 'border-red-500': passwordErrors.new }">
                    <p class="text-red-500 text-sm mt-1" x-show="passwordErrors.new" x-text="passwordErrors.new"></p>
                </div>
                <!-- Confirm Password -->
                <div class="px-2 w-full sm:w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Új jelszó megerősítése</label>
                    <input type="password" x-model="password.confirm" class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                           :class="{ 'border-red-500': passwordErrors.confirm }">
                    <p class="text-red-500 text-sm mt-1" x-show="passwordErrors.confirm" x-text="passwordErrors.confirm"></p>
                </div>
            </div>
            <!-- Save Password Button -->
            <div class="mt-6">
                <button @click="changePassword()" class="bg-blue-500 text-white px-4 py-2 rounded">Mentés</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
