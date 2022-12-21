{extends "base.tpl"}

{block "script"}
    <script src="/js/main/mugenrace2021/kosar.js?v=1"></script>
{/block}

{block "body"}
    <div class="cart-nav-spacer"></div>
<div x-data="{ imagepath: '{$imagepath}'}">
    <div class="cart-container " x-data="kosar" x-init="getLists">
        <div class="cart-table" x-cloak>
            <template x-for="tetel in tetellist">
                <template x-if="!tetel.noedit">
                    <div class="cart-table-row">
                        <div class="cart-table-img-cell padding">
                            <img
                                x-show="tetel.kiskepurl"
                                :src="imagepath + tetel.kiskepurl"
                                :alt="tetel.caption"
                                :title="tetel.caption"
                            >
                        </div>
                        <div class="cart-table-data-cell padding">
                            <div class="cart-table-data-line padding-bottom border-bottom-light">
                                <div>
                                    <div x-text="tetel.caption"></div>
                                    <div class="termek-cikkszam" x-text="tetel.cikkszam"></div>
                                    <div class="termek-cikkszam" x-text="tetel.rovidleiras"></div>
                                </div>
                                <div>
                                    <button class="btn btn-secondary">{t('Törlés')}</button>
                                </div>
                            </div>
                            <div class="cart-table-data-line padding-top">
                                <div class="cart-table-data-table">
                                    <div class="">
                                        <div class="co-termek-tul">{t('Méret')}:</div>
                                        <div class="co-termek-tul">{t('Szín')}:</div>
                                        <div class="co-termek-ar">{t('Ár')}:</div>
                                    </div>
                                    <div class="">
                                        <div class="co-termek-tul" x-text="tetel.meret"></div>
                                        <div class="co-termek-tul" x-text="tetel.szin"></div>
                                        <div class="co-termek-ar" x-text="tetel.bruttoegysar + ' ' + valutanem"></div>
                                    </div>
                                </div>
                                <div>
                                    <input type="number" x-model="tetel.mennyiseg">
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
        </div>
        <div class="cart-summary padding-left padding-right" x-cloak>
            <template x-for="tetel in tetellist">
                <div class="cart-summary-row padding-top padding-bottom">
                    <div class="cart-summary-data-row">
                        <div class="cart-summary-data-cell">
                            <div x-text="tetel.caption"></div>
                            <div class="termek-cikkszam" x-text="tetel.cikkszam"></div>
                            <div class="termek-cikkszam" x-text="tetel.rovidleiras"></div>
                            <div class="co-termek-tul" x-text="tetel.meret"></div>
                            <div class="co-termek-tul" x-text="tetel.szin"></div>
                        </div>
                        <div class="cart-summary-img-cell">
                            <img
                                x-show="tetel.kiskepurl"
                                :src="imagepath + tetel.kiskepurl"
                                :alt="tetel.caption"
                                :title="tetel.caption"
                            >
                        </div>
                    </div>
                    <div class="cart-summary-ar-row">
                        <div class="co-termek-ar font-bold" x-text="tetel.mennyiseg + ' {t('db')}'"></div>
                        <div class="co-termek-ar font-bold cart-summary-termek-ar" x-text="tetel.bruttoegysar + ' ' + valutanem"></div>
                    </div>
                </div>
            </template>
            <div class="cart-summary-summary padding-top padding-bottom">
                <div class="red">{t('Összesen')}</div>
                <div class="cart-summary-ar red" x-text="sum + ' ' + valutanem"></div>
            </div>
            <button class="cart-save-button btn btn-primary" @click="location = '/checkout'">{t('Tovább a fizetéshez')}</button>
        </div>
    </div>
</div>
{/block}
