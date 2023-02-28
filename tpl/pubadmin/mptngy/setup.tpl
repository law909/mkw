{extends "main.tpl"}

{block "prescript"}
    <script src="/js/pubadmin/mptngy/setup.js?v=1"></script>
{/block}

{block "main"}
    <div class="co-container" x-data="setup">
        <div class="co-data-container">
            <h3>Beállítások</h3>
            <div class="co-row co-flex-dir-row">
                <div class="co-col">
                    <div class="co-control-row">
                        <label for="maxdbEdit" class="co-label">{t('Maximum vállalt absztrakt')}</label>
                        <input
                            id="maxdbEdit"
                            class="co-input"
                            :class="validation.maxdb && !validation.maxdb.valid ? 'error' : ''"
                            type="number"
                            x-model="biralo.maxdb"
                        >
                        <div class="co-error" x-text="validation.maxdb && validation.maxdb.error"></div>
                    </div>
                    <div class="co-control-row" :class="validation.temakorlist && !validation.temakorlist.valid ? 'error-border' : ''">
                        <label
                            for="maxdbEdit"
                            class="co-label"
                        >{t('Témakörök')}
                        </label>
                        <template x-for="tkor in biralo.temakorlist" :key="tkor.id">
                            <div>
                                <label class="co-label">
                                    <input
                                        type="checkbox"
                                        x-model="tkor.selected"
                                    >
                                    <span x-text="tkor.caption"></span>
                                </label>
                            </div>
                        </template>
                        <div class="co-error" x-text="validation.temakorlist && validation.temakorlist.error"></div>
                    </div>
                    <div class="co-control-row">
                        <button class="btn btn-primary" @click="save()">{t('Mentés')}</button>
                        <button class="btn btn-secondary">{t('Mégsem')}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}