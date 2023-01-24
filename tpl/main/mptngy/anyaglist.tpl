{extends "base.tpl"}

{block "script"}
    <script src="/js/main/mptngy/anyaglist.js?v=8"></script>
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
                                <table>
                                    <thead>
                                        <tr>
                                            <th>{t('Cím')}</th>
                                            <th>{t('Tulajdonos')}</th>
                                            <th>{t('Típus')}</th>
                                            <th>{t('Kezdés')}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <template x-for="any in anyaglist" :key="any.id">
                                        <tr :class="!any.allszerzoregistered ? 'red' : ''">
                                            <td x-text="any.cim" data-label="{t('Cím')}"></td>
                                            <td x-text="any.tulajdonosnev" data-label="{t('Tulajdonos')}"></td>
                                            <td x-text="any.tipusnev" data-label="{t('Típus')}"></td>
                                            <td x-text="any.kezdodatumstr + ' - ' + any.kezdoido" data-label="{t('Kezdés')}"></td>
                                            <td>
                                                <button
                                                    class="btn btn-secondary"
                                                    @click="edit(any.id)"
                                                    x-show="!any.vegleges && any.editable && loaded >= loadCount"
                                                >{t('Módosítás')}</button>
                                                <span x-show="any.vegleges">Beküldve</span>
                                            </td>
                                        </tr>
                                    </template>

                                    </tbody>
                                </table>
                            <div class="co-control-row">
                                <button
                                    x-cloak
                                    class="btn btn-primary"
                                    @click="createNew()"
                                    x-show="sajatanyaglistLoaded && sajatanyaglist.length < 2"
                                >{t('Új anyag')}</button>
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
                                    :class="validation.cim && !validation.cim.valid ? 'error' : ''"
                                    type="text"
                                    x-model="anyag.cim"
                                >
                                <div class="co-error" x-text="validation.cim && validation.cim.error"></div>
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
                                <div class="co-error" x-text="validation.tulajdonosnev ? validation.tulajdonosnev.error : ''"></div>
                            </div>
                            <div class="co-control-row">
                                <label for="tipusEdit" class="co-label">{t('Típus')}</label>
                                <select
                                    id="tipusEdit"
                                    class="co-input"
                                    :class="validation.tipus && !validation.tipus.valid ? 'error' : ''"
                                    x-model="anyag.tipus"
                                >
                                    <option value="">{t('válasszon')}</option>
                                    <template x-for="tipus in anyagtipuslist" :key="tipus.id">
                                        <option
                                            :value="tipus.id"
                                            x-text="tipus.caption"
                                        ></option>
                                    </template>
                                </select>
                                <div class="co-error" x-text="validation.tipus ? validation.tipus.error : ''"></div>
                            </div>
                            <div class="co-control-row co-col-container">
                                <div class="co-col co-col-50">
                                    <label for="kezdodatumEdit" class="co-label">{t('Kezdő dátum')}</label>
                                    <select
                                        id="kezdodatumEdit"
                                        class="co-input"
                                        x-model="anyag.kezdodatum"
                                        disabled
                                    >
                                        <option value="">{t('válasszon')}</option>
                                        <template x-for="datum in datumlist" :key="datum.id">
                                            <option
                                                :value="datum.id"
                                                x-text="datum.caption"
                                            ></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="co-col co-col-50">
                                    <label for="kezdoidoEdit" class="co-label">{t('Kezdő időpont')}</label>
                                    <input
                                        id="kezdoidoEdit"
                                        class="co-input"
                                        type="text"
                                        x-model="anyag.kezdoido"
                                        disabled
                                    >
                                </div>
                            </div>

                            <div class="co-row co-flex-dir-column">
                                <div class="co-control-row co-col-container">
                                    <div class="co-col co-col-50">
                                        <label for="szerzo1Edit" class="co-label">{t('Szerző')} 1 email</label>
                                        <input
                                            id="szerzo1Edit"
                                            class="co-input"
                                            :class="validation.szerzo1email && !validation.szerzo1email.valid ? 'error' : ''"
                                            type="email"
                                            x-model="anyag.szerzo1email"
                                            @change="checkSzerzo(1)"
                                        >
                                        <div class="co-hint red" x-show="szerzo1unknown">{t('A szerző még nem regisztrált')}</div>
                                        <div class="co-error" x-text="validation.szerzo1email ? validation.szerzo1email.error : ''"></div>
                                    </div>
                                    <div class="co-col co-col-50">
                                        <label for="szerzo2Edit" class="co-label">{t('Szerző')} 2 email</label>
                                        <input
                                            id="szerzo2Edit"
                                            class="co-input"
                                            :class="validation.szerzo2email && !validation.szerzo2email.valid ? 'error' : ''"
                                            type="email"
                                            x-model="anyag.szerzo2email"
                                            @change="checkSzerzo(2)"
                                        >
                                        <div class="co-hint red" x-show="szerzo2unknown">{t('A szerző még nem regisztrált')}</div>
                                        <div class="co-error" x-text="validation.szerzo2email ? validation.szerzo2email.error : ''"></div>
                                    </div>
                                </div>
                                <div class="co-control-row co-col-container">
                                    <div class="co-col co-col-50">
                                        <label for="szerzo3Edit" class="co-label">{t('Szerző')} 3 email</label>
                                        <input
                                            id="szerzo3Edit"
                                            class="co-input"
                                            :class="validation.szerzo3email && !validation.szerzo3email.valid ? 'error' : ''"
                                            type="email"
                                            x-model="anyag.szerzo3email"
                                            @change="checkSzerzo(3)"
                                        >
                                        <div class="co-hint red" x-show="szerzo3unknown">{t('A szerző még nem regisztrált')}</div>
                                        <div class="co-error" x-text="validation.szerzo3email ? validation.szerzo3email.error : ''"></div>
                                    </div>
                                    <div class="co-col co-col-50">
                                        <label for="szerzo4Edit" class="co-label">{t('Szerző')} 4 email</label>
                                        <input
                                            id="szerzo4Edit"
                                            class="co-input"
                                            :class="validation.szerzo4email && !validation.szerzo4email.valid ? 'error' : ''"
                                            type="email"
                                            x-model="anyag.szerzo4email"
                                            @change="checkSzerzo(4)"
                                        >
                                        <div class="co-hint red" x-show="szerzo4unknown">{t('A szerző még nem regisztrált')}</div>
                                        <div class="co-error" x-text="validation.szerzo4email ? validation.szerzo4email.error : ''"></div>
                                    </div>
                                </div>
                                <div
                                    class="co-control-row"
                                    x-show="szimpozium"
                                >
                                    <label for="szerzo5Edit" class="co-label">{t('Opponens')} email</label>
                                    <input
                                        id="szerzo5Edit"
                                        class="co-input"
                                        :class="validation.szerzo5email && !validation.szerzo5email.valid ? 'error' : ''"
                                        type="email"
                                        x-model="anyag.szerzo5email"
                                        @change="checkSzerzo(5)"
                                    >
                                    <div class="co-hint red" x-show="szerzo5unknown">{t('Az opponens még nem regisztrált')}</div>
                                    <div class="co-error" x-text="validation.szerzo5email ? validation.szerzo5email.error : ''"></div>
                                </div>
                            </div>
                            <div
                                class="co-row co-flex-dir-column"
                                x-show="szimpozium"
                            >
                                <div class="co-control-row">
                                    <label for="eloadas1Edit" class="co-label">{t('Előadás')} 1</label>
                                    <select
                                        id="eloadas1Edit"
                                        class="co-input"
                                        :class="validation.eloadas1 && !validation.eloadas1.valid ? 'error' : ''"
                                        x-model="anyag.eloadas1"
                                    >
                                        <option value="">{t('válasszon')}</option>
                                        <template x-for="eloadas in sajatanyaglist" :key="eloadas.id">
                                            <option
                                                :value="eloadas.id"
                                                x-text="eloadas.cim"
                                            ></option>
                                        </template>
                                    </select>
                                    <div class="co-error" x-text="validation.eloadas1 ? validation.eloadas1.error : ''"></div>
                                </div>
                                <div class="co-control-row">
                                    <label for="eloadas2Edit" class="co-label">{t('Előadás')} 2</label>
                                    <select
                                        id="eloadas2Edit"
                                        class="co-input"
                                        :class="validation.eloadas1 && !validation.eloadas1.valid ? 'error' : ''"
                                        x-model="anyag.eloadas2"
                                    >
                                        <option value="">{t('válasszon')}</option>
                                        <template x-for="eloadas in sajatanyaglist" :key="eloadas.id">
                                            <option
                                                :value="eloadas.id"
                                                x-text="eloadas.cim"
                                            ></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="co-control-row">
                                    <label for="eloadas3Edit" class="co-label">{t('Előadás')} 3</label>
                                    <select
                                        id="eloadas3Edit"
                                        class="co-input"
                                        :class="validation.eloadas1 && !validation.eloadas1.valid ? 'error' : ''"
                                        x-model="anyag.eloadas3"
                                    >
                                        <option value="">{t('válasszon')}</option>
                                        <template x-for="eloadas in sajatanyaglist" :key="eloadas.id">
                                            <option
                                                :value="eloadas.id"
                                                x-text="eloadas.cim"
                                            ></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="co-control-row">
                                    <label for="eloadas4Edit" class="co-label">{t('Előadás')} 4</label>
                                    <select
                                        id="eloadas4Edit"
                                        class="co-input"
                                        :class="validation.eloadas1 && !validation.eloadas1.valid ? 'error' : ''"
                                        x-model="anyag.eloadas4"
                                    >
                                        <option value="">{t('válasszon')}</option>
                                        <template x-for="eloadas in sajatanyaglist" :key="eloadas.id">
                                            <option
                                                :value="eloadas.id"
                                                x-text="eloadas.cim"
                                            ></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            <div class="co-row co-flex-dir-column">
                                <div class="co-control-row co-col-container">
                                    <div class="co-col co-col-33">
                                        <label for="temakor1Edit" class="co-label">{t('Témakör')} 1</label>
                                        <select
                                            id="temakor1Edit"
                                            class="co-input"
                                            :class="validation.temakor1 && !validation.temakor1.valid ? 'error' : ''"
                                            x-model="anyag.temakor1"
                                        >
                                            <option value="">{t('válasszon')}</option>
                                            <template x-for="temakor in temakorlist" :key="temakor.id">
                                                <option
                                                    :value="temakor.id"
                                                    x-text="temakor.caption"
                                                ></option>
                                            </template>
                                        </select>
                                        <div class="co-error" x-text="validation.temakor1 ? validation.temakor1.error : ''"></div>
                                    </div>
                                    <div class="co-col co-col-33">
                                        <label for="temakor2Edit" class="co-label">{t('Témakör')} 2</label>
                                        <select
                                            id="temakor2Edit"
                                            class="co-input"
                                            :class="validation.temakor1 && !validation.temakor1.valid ? 'error' : ''"
                                            x-model="anyag.temakor2"
                                        >
                                            <option value="">{t('válasszon')}</option>
                                            <template x-for="temakor in temakorlist" :key="temakor.id">
                                                <option
                                                    :value="temakor.id"
                                                    x-text="temakor.caption"
                                                ></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="co-col co-col-33">
                                        <label for="temakor3Edit" class="co-label">{t('Témakör')} 3</label>
                                        <select
                                            id="temakor3Edit"
                                            class="co-input"
                                            :class="validation.temakor1 && !validation.temakor1.valid ? 'error' : ''"
                                            x-model="anyag.temakor3"
                                        >
                                            <option value="">{t('válasszon')}</option>
                                            <template x-for="temakor in temakorlist" :key="temakor.id">
                                                <option
                                                    :value="temakor.id"
                                                    x-text="temakor.caption"
                                                ></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="co-control-row co-col-container">
                                    <div class="co-col co-col-33">
                                        <label for="kulcsszo1Edit" class="co-label">{t('Kulcsszó')} 1</label>
                                        <input
                                            id="kulcsszo1Edit"
                                            class="co-input"
                                            :class="validation.kulcsszo1 && !validation.kulcsszo1.valid ? 'error' : ''"
                                            type="text"
                                            x-model="anyag.kulcsszo1"
                                        >
                                        <div class="co-error" x-text="validation.kulcsszo1 ? validation.kulcsszo1.error : ''"></div>
                                    </div>
                                    <div class="co-col co-col-33">
                                        <label for="kulcsszo2Edit" class="co-label">{t('Kulcsszó')} 2</label>
                                        <input
                                            id="kulcsszo2Edit"
                                            class="co-input"
                                            :class="validation.kulcsszo1 && !validation.kulcsszo1.valid ? 'error' : ''"
                                            type="text"
                                            x-model="anyag.kulcsszo2"
                                        >
                                    </div>
                                    <div class="co-col co-col-33">
                                        <label for="kulcsszo3Edit" class="co-label">{t('Kulcsszó')} 3</label>
                                        <input
                                            id="kulcsszo3Edit"
                                            class="co-input"
                                            :class="validation.kulcsszo1 && !validation.kulcsszo1.valid ? 'error' : ''"
                                            type="text"
                                            x-model="anyag.kulcsszo3"
                                        >
                                    </div>
                                </div>
                                <div class="co-control-row co-col-container">
                                    <div class="co-col co-col-50">
                                        <label for="kulcsszo4Edit" class="co-label">{t('Kulcsszó')} 4</label>
                                        <input
                                            id="kulcsszo4Edit"
                                            class="co-input"
                                            :class="validation.kulcsszo1 && !validation.kulcsszo1.valid ? 'error' : ''"
                                            type="text"
                                            x-model="anyag.kulcsszo4"
                                        >
                                    </div>
                                    <div class="co-col co-col-50">
                                        <label for="kulcsszo5Edit" class="co-label">{t('Kulcsszó')} 5</label>
                                        <input
                                            id="kulcsszo5Edit"
                                            class="co-input"
                                            :class="validation.kulcsszo1 && !validation.kulcsszo1.valid ? 'error' : ''"
                                            type="text"
                                            x-model="anyag.kulcsszo5"
                                        >
                                    </div>
                                </div>
                                <div class="co-control-row">
                                    <label for="tartalomEdit" class="co-label">{t('Tartalom')} (<span x-text="anyag.tartalom.length"></span> karakter)</label>
                                    <textarea
                                        id="tartalomEdit"
                                        class="co-input"
                                        :class="validation.tartalom && !validation.tartalom.valid ? 'error' : ''"
                                        rows="10"
                                        x-model="anyag.tartalom"
                                    ></textarea>
                                    <div class="co-error" x-text="validation.tartalom ? validation.tartalom.error : ''"></div>
                                </div>
                            </div>
                            <div class="co-control-row">
                                <button class="btn btn-primary" @click="save(false)">{t('Mentés')}</button>
                                <button class="btn btn-secondary" @click="save(true)">{t('Beküldés')}</button>
                                <button class="btn btn-secondary" @click="cancel()">{t('Mégsem')}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}