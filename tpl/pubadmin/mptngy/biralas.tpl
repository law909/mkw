{extends "main.tpl"}

{block "prescript"}
    <script src="/js/pubadmin/mptngy/biralas.js?v=4"></script>
{/block}

{block "main"}
    <div class="co-container" x-data="biralas">
        <div class="co-data-container">
            <h3>Szakmai anyagok</h3>
            <div
                class="co-row co-flex-dir-row"
                x-show="!showEditor"
            >
                <div class="co-col">
                    <table>
                        <thead>
                        <tr>
                            <th class="th-w-10"></th>
                            <th>{t('Cím')}</th>
                            <th>{t('Típus')}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="any in anyaglist" :key="any.id">
                            <tr :class="!any.allszerzoregistered ? 'red' : ''">
                                <td x-text="any.id" data-label="{t('Azonosító')}"></td>
                                <td data-label="{t('Cím')}">
                                    <div x-text="any.cim"></div>
                                    <template x-if="any.szimpozium">
                                        <div class="margin-top">
                                            <div>{t('Előadások')}:</div>
                                            <div x-text="any.eloadas1cim"></div>
                                            <div x-text="any.eloadas2cim"></div>
                                            <div x-text="any.eloadas3cim"></div>
                                            <div x-text="any.eloadas4cim"></div>
                                            <div x-text="any.eloadas5cim"></div>
                                        </div>
                                    </template>
                                </td>
                                <td x-text="any.tipusnev" data-label="{t('Típus')}"></td>
                                <td>
                                    <button
                                        class="btn btn-secondary"
                                        @click="edit(any.id)"
                                        x-show="loaded >= loadCount && !any.bbiralatkesz"
                                    >{t('Bírálat')}</button>
                                    <span x-show="loaded >= loadCount && any.bbiralatkesz">Beküldve</span>
                                </td>
                            </tr>
                        </template>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="co-row co-flex-dir-row" x-show="showEditor">
                <div class="co-col-100">
                    <div class="co-control-row" x-text="anyag.cim"></div>
                    <template x-if="anyag.szimpozium">
                        <div class="co-control-row">
                            <div>{t('Előadások')}:</div>
                            <div x-text="anyag.eloadas1cim"></div>
                            <div x-text="anyag.eloadas2cim"></div>
                            <div x-text="anyag.eloadas3cim"></div>
                            <div x-text="anyag.eloadas4cim"></div>
                            <div x-text="anyag.eloadas5cim"></div>
                        </div>
                    </template>
                    <div class="co-control-row">
                        <textarea
                            rows="20"
                            class="co-input"
                            x-text="anyag.tartalom"
                            disabled
                        ></textarea>
                    </div>
                    <div class="co-control-row co-col-container">
                        <div class="co-col co-col-33">
                            <label for="szempont1Edit" class="co-label" x-text="szempontlist[1] + ' (0-5 pont)'">{t('Szempont 1')} (0-5 pont)</label>
                            <input
                                id="szempont1Edit"
                                class="co-input"
                                :class="validation.szempont1 && !validation.szempont1.valid ? 'error' : ''"
                                type="number"
                                x-model="anyag.szempont1"
                                min="0"
                                max="5"
                            >
                            <div class="co-error" x-text="validation.szempont1 && validation.szempont1.error"></div>
                        </div>
                        <div class="co-col co-col-33">
                            <label for="szempont2Edit" class="co-label" x-text="szempontlist[2] + ' (0-5 pont)'">{t('Szempont 2')} (0-5 pont)</label>
                            <input
                                id="szempont2Edit"
                                class="co-input"
                                :class="validation.szempont2 && !validation.szempont2.valid ? 'error' : ''"
                                type="number"
                                x-model="anyag.szempont2"
                                min="0"
                                max="5"
                            >
                            <div class="co-error" x-text="validation.szempont2 && validation.szempont2.error"></div>
                        </div>
                        <div class="co-col co-col-33">
                            <label for="szempont3Edit" class="co-label" x-text="szempontlist[3] + ' (0-5 pont)'">{t('Szempont 3')} (0-5 pont)</label>
                            <input
                                id="szempont3Edit"
                                class="co-input"
                                :class="validation.szempont3 && !validation.szempont3.valid ? 'error' : ''"
                                type="number"
                                x-model="anyag.szempont3"
                                min="0"
                                max="5"
                            >
                            <div class="co-error" x-text="validation.szempont3 && validation.szempont3.error"></div>
                        </div>

                    </div>
                    <div class="co-control-row co-col-container">
                        <div class="co-col co-col-50">
                            <label for="szempont4Edit" class="co-label" x-text="szempontlist[4] + ' (0-5 pont)'">{t('Szempont 4')} (0-5 pont)</label>
                            <input
                                id="szempont4Edit"
                                class="co-input"
                                :class="validation.szempont4 && !validation.szempont4.valid ? 'error' : ''"
                                type="number"
                                x-model="anyag.szempont4"
                                min="0"
                                max="5"
                            >
                            <div class="co-error" x-text="validation.szempont4 && validation.szempont4.error"></div>
                        </div>
                        <div class="co-col co-col-50">
                            <label for="szempont5Edit" class="co-label" x-text="szempontlist[5] + ' (0-5 pont)'">{t('Szempont 5')} (0-5 pont)</label>
                            <input
                                id="szempont5Edit"
                                class="co-input"
                                :class="validation.szempont5 && !validation.szempont5.valid ? 'error' : ''"
                                type="number"
                                x-model="anyag.szempont5"
                                min="0"
                                max="5"
                            >
                            <div class="co-error" x-text="validation.szempont5 && validation.szempont5.error"></div>
                        </div>
                    </div>
                    <div class="co-control-row">
                        <label for="szovegesertekelesEdit" class="co-label">{t('Szöveges értékelés')}</label>
                        <textarea
                            id="szovegesertekelesEdit"
                            rows="10"
                            class="co-input"
                            :class="validation.szovegesertekeles && !validation.szovegesertekeles.valid ? 'error' : ''"
                            x-model="anyag.szovegesertekeles"
                        ></textarea>
                        <div class="co-error" x-text="validation.szovegesertekeles&& validation.szovegesertekeles.error"></div>
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
{/block}