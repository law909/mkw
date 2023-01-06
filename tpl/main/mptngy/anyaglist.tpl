{extends "base.tpl"}

{block "script"}
    <script src="/js/main/mptngy/anyaglist.js?v=2"></script>
{/block}

{block "body"}
    <div x-data="anyaglist" x-init="getLists">
        <div class="co-container co-header" x-cloak>
            <div class="co-col-50 padding" x-text="me.nev"></div>
            <div class="padding">
                <button class="btn btn-secondary" @click="logout()">{t('Kijelentkezés')}</button>
            </div>
        </div>
        <div class="co-container" x-cloak>
            <div class="co-data-container">
                <div class="co-row co-flex-dir-row">
                    <div class="co-col-100 padding">
                        <h2>{t('Szakmai anyagok')}</h2>
                        <div x-show="!showEditor">
                            <template x-for="anyag in anyaglist">
                                <div>
                                    <span x-text="anyag.cim"></span>
                                    <span x-text="anyag.tulajdonosnev"></span>
                                    <span x-text="anyag.kezdodatum"></span>
                                    <span x-text="anyag.kezdoido"></span>
                                    <button class="btn btn-secondary" @click="">{t('Módosítás')}</button>
                                </div>
                            </template>
                            <div class="co-control-row">
                                <button x-cloak class="btn btn-primary" @click="createNew()">{t('Új anyag')}</button>
                            </div>
                        </div>
                        <div x-show="showEditor">
                            {$uf = t('Új felvitel')}
                            <h4 x-text="anyag.cim ? anyag.cim : '{$uf}'"></h4>
                            <div class="co-control-row">
                                <label for="cimEdit" class="co-label">{t('Cím')}</label>
                                <input
                                    id="cimEdit"
                                    class="co-input"
                                    type="text"
                                    x-model="anyag.cim"
                                >
                            </div>
                            <div class="co-control-row">
                                <label for="tulajEdit" class="co-label">{t('Tulajdonos')}</label>
                                <input
                                    id="tulajEdit"
                                    class="co-input"
                                    type="text"
                                    x-model="anyag.tulajdonosnev"
                                    disabled
                                >
                            </div>
                            <div class="co-control-row">
                                <label for="tipusEdit" class="co-label">{t('Típus')}</label>
                                <select
                                    class="co-input"
                                    x-model="anyag.tipus"
                                >
                                    <option>{t('válasszon')}</option>
                                    <template x-for="tipus in anyagtipuslist" :key="tipus.id">
                                        <option
                                            :value="tipus.id"
                                            x-text="tipus.caption"
                                        ></option>
                                    </template>
                                </select>
                            </div>

                            <div class="co-row co-flex-dir-column">
                                <div class="co-control-row">
                                    <label for="szerzo1Edit" class="co-label">{t('Szerző')} 1 email</label>
                                    <input
                                        id="szerzo1Edit"
                                        class="co-input"
                                        type="email"
                                        x-model="anyag.szerzo1email"
                                        @change="checkSzerzo(1)"
                                    >
                                    <div class="co-hint" x-show="szerzo1unknown">{t('A szerző még nem regisztrált')}</div>
                                </div>
                                <div class="co-control-row">
                                    <label for="szerzo2Edit" class="co-label">{t('Szerző')} 2 email</label>
                                    <input
                                        id="szerzo2Edit"
                                        class="co-input"
                                        type="email"
                                        x-model="anyag.szerzo2email"
                                        @change="checkSzerzo(2)"
                                    >
                                    <div class="co-hint" x-show="szerzo2unknown">{t('A szerző még nem regisztrált')}</div>
                                </div>
                                <div class="co-control-row">
                                    <label for="szerzo3Edit" class="co-label">{t('Szerző')} 3 email</label>
                                    <input
                                        id="szerzo3Edit"
                                        class="co-input"
                                        type="email"
                                        x-model="anyag.szerzo3email"
                                        @change="checkSzerzo(3)"
                                    >
                                    <div class="co-hint" x-show="szerzo3unknown">{t('A szerző még nem regisztrált')}</div>
                                </div>
                                <div class="co-control-row">
                                    <label for="szerzo4Edit" class="co-label">{t('Szerző')} 4 email</label>
                                    <input
                                        id="szerzo4Edit"
                                        class="co-input"
                                        type="email"
                                        x-model="anyag.szerzo4email"
                                        @change="checkSzerzo(4)"
                                    >
                                    <div class="co-hint" x-show="szerzo4unknown">{t('A szerző még nem regisztrált')}</div>
                                </div>
                                <div
                                    class="co-control-row"
                                    x-show="anyag.tipus ? anyagtipuslist.find(el => el.id === parseInt(anyag.tipus)).szimpozium : false"
                                >
                                    <label for="szerzo5Edit" class="co-label">{t('Szerző')} 5 email</label>
                                    <input
                                        id="szerzo5Edit"
                                        class="co-input"
                                        type="email"
                                        x-model="anyag.szerzo5email"
                                        @change="checkSzerzo(5)"
                                    >
                                    <div class="co-hint" x-show="szerzo5unknown">{t('A szerző még nem regisztrált')}</div>
                                </div>
                            </div>
                            <div
                                class="co-row co-flex-dir-column"
                                x-show="anyag.tipus ? anyagtipuslist.find(el => el.id === parseInt(anyag.tipus)).szimpozium : false"
                            >
                                <div class="co-control-row">
                                    <label for="eloadas1Edit" class="co-label">{t('Előadás')} 1</label>
                                    <select class="co-input" x-model="anyag.eloadas1">
                                        <option>{t('válasszon')}</option>
                                        <template x-for="eloadas in sajatanyaglist" :key="eloadas.id">
                                            <option
                                                :value="eloadas.id"
                                                x-text="eloadas.caption"
                                            ></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="co-control-row">
                                    <label for="eloadas2Edit" class="co-label">{t('Előadás')} 2</label>
                                    <select class="co-input" x-model="anyag.eloadas2">
                                        <option>{t('válasszon')}</option>
                                        <template x-for="eloadas in sajatanyaglist" :key="eloadas.id">
                                            <option
                                                :value="eloadas.id"
                                                x-text="eloadas.caption"
                                            ></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="co-control-row">
                                    <label for="eloadas3Edit" class="co-label">{t('Előadás')} 3</label>
                                    <select class="co-input" x-model="anyag.eloadas3">
                                        <option>{t('válasszon')}</option>
                                        <template x-for="eloadas in sajatanyaglist" :key="eloadas.id">
                                            <option
                                                :value="eloadas.id"
                                                x-text="eloadas.caption"
                                            ></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="co-control-row">
                                    <label for="eloadas4Edit" class="co-label">{t('Előadás')} 4</label>
                                    <select class="co-input" x-model="anyag.eloadas4">
                                        <option>{t('válasszon')}</option>
                                        <template x-for="eloadas in sajatanyaglist" :key="eloadas.id">
                                            <option
                                                :value="eloadas.id"
                                                x-text="eloadas.caption"
                                            ></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="co-control-row">
                                    <label for="eloadas5Edit" class="co-label">{t('Előadás')} 5</label>
                                    <select class="co-input" x-model="anyag.eloadas5">
                                        <option>{t('válasszon')}</option>
                                        <template x-for="eloadas in sajatanyaglist" :key="eloadas.id">
                                            <option
                                                :value="eloadas.id"
                                                x-text="eloadas.caption"
                                            ></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            <div class="co-control-row">
                                <label for="tartalomEdit" class="co-label">{t('Tartalom')}</label>
                                <textarea
                                    id="tartalomEdit"
                                    class="co-input"
                                    type="email"
                                    x-model="anyag.tartalom"
                                ></textarea>
                            </div>
                            <div class="co-control-row">
                                <button class="btn btn-primary">{t('Mentés')}</button>
                                <button class="btn btn-secondary">{t('Beküldés')}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}