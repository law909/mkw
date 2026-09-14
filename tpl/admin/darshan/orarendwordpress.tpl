<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="/js/main/darshan/iframeResizer.contentWindow.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery-3.7.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var modal = document.querySelector('.modal'),
                lemondmodal = document.querySelector('.lemondmodal');

            function toggleModal(y) {
                modal.classList.toggle("show-modal");
                let content = document.querySelector('.modal > .modal-content');
                if (y + content.getBoundingClientRect().height >= window.innerHeight) {
                    y = window.innerHeight - content.getBoundingClientRect().height;
                }
                content.style.top = y + 'px';
            }

            function toggleLemondmodal(y) {
                lemondmodal.classList.toggle("show-modal");
                let content = document.querySelector('.lemondmodal > .modal-content');
                if (y + content.getBoundingClientRect().height >= window.innerHeight) {
                    y = window.innerHeight - content.getBoundingClientRect().height;
                }
                content.style.top = y + 'px';
            }

            function windowOnClick(event) {
                if (event.target === modal) {
                    closeBejelentkezes();
                } else if (event.target === lemondmodal) {
                    toggleLemondmodal(0);
                }
            }

            const EMAILMINTA = /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                CIMSZOVEG_ALAP = 'Ha először jössz, add meg a címed a számlához. A már megadott adataidat nem írjuk felül.',
                HIBA_ALAP = 'A bejelentkezés nem sikerült, kérjük, próbáld újra.';
            // sikeres bejelentkezés után a bezárás újratölt, hogy a szabad helyek száma frissüljön
            let bejelentkezesSikeres = false;

            function parseValasz(res) {
                return (typeof res === 'string') ? (res ? JSON.parse(res) : null) : res;
            }

            function showBejelentkezesHiba(msg) {
                $('.js-hiba').text(msg).toggle(!!msg);
            }

            function resetBejelentkezes() {
                bejelentkezesSikeres = false;
                $('#modal-form').show().find('input[type="email"], input[type="text"]').val('');
                $('.js-siker').text('').hide();
                $('.js-bezaras').hide();
                $('.js-cimszoveg').text(CIMSZOVEG_ALAP);
                $('.js-cimmezok').show();
                showBejelentkezesHiba('');
            }

            function closeBejelentkezes() {
                toggleModal(0);
                if (bejelentkezesSikeres) {
                    location.reload();
                }
            }

            $('body').on('click', '.js-bejelentkezes', function (e) {
                const $this = $(this);
                e.preventDefault();
                resetBejelentkezes();
                $('input[name="id"]').val($this.data('id'));
                $('input[name="datum"]').val($this.data('datum'));
                $('.js-alcim').text($this.attr('data-oranev') + ' – ' + $this.attr('data-idopont'));
                toggleModal(this.getBoundingClientRect().y);
            });
            $('body').on('click', '.js-lemondas', function (e) {
                var $this = $(this);
                e.preventDefault();
                $('input[name="lemondid"]').val($this.data('id'));
                $('input[name="lemonddatum"]').val($this.data('datum'));
                toggleLemondmodal(this.getBoundingClientRect().y);
            });
            // csak azt tudjuk meg, kell-e címet kérni: nevet, címet a szerver nem ad ki
            $('input[name="email"]').on('change', function () {
                const email = $(this).val().trim();
                if (!EMAILMINTA.test(email)) {
                    return;
                }
                $.ajax({
                    url: '/jelentkezes/emailellenor',
                    type: 'POST',
                    data: {
                        email: email
                    },
                    success: function (res) {
                        const adat = parseValasz(res);
                        if (!adat || $('input[name="email"]').val().trim() !== email) {
                            return;
                        }
                        if (adat.ismert && !adat.cimhianyos) {
                            $('.js-cimmezok').hide().find('input').val('');
                            $('.js-cimszoveg').text('Ezzel az emailcímmel már jártál nálunk, a számlázási adataid megvannak.');
                        } else {
                            $('.js-cimmezok').show();
                            $('.js-cimszoveg').text(adat.ismert
                                ? 'Ezzel az emailcímmel már jártál nálunk, de a számládhoz hiányzik a címed. Kérjük, add meg.'
                                : 'Első alkalom? Add meg a címed, hogy ki tudjuk állítani a számlát az óráról.');
                        }
                    }
                });
            });
            $('.close-button').click(function (e) {
                e.preventDefault();
                closeBejelentkezes();
            });
            $('.js-bezaras').click(function (e) {
                e.preventDefault();
                closeBejelentkezes();
            });
            $('.lemondclose-button').click(function (e) {
                e.preventDefault();
                toggleLemondmodal(0);
            });
            $('.js-ok').click(function (e) {
                const $gomb = $(this),
                    nev = ($('input[name="partnernev"]').val() || '').trim(),
                    email = ($('input[name="email"]').val() || '').trim();
                e.preventDefault();
                if (!email || !nev) {
                    showBejelentkezesHiba('Add meg az emailcímed és a teljes neved.');
                } else if (nev.split(/\s+/).length < 2) {
                    // a számlához vezeték- és keresztnév is kell, a szerver is ezt kéri
                    showBejelentkezesHiba('Kérjük, add meg a teljes neved (vezeték- és keresztnév).');
                } else if (!EMAILMINTA.test(email)) {
                    showBejelentkezesHiba('Kérjük, ellenőrizd az emailcímed.');
                } else {
                    showBejelentkezesHiba('');
                    $gomb.prop('disabled', true);
                    $.ajax({
                        url: '/orarend/bejelentkezes',
                        type: 'POST',
                        data: {
                            id: $('input[name="id"]').val(),
                            datum: $('input[name="datum"]').val(),
                            partnernev: nev,
                            email: email,
                            irszam: $('input[name="irszam"]').val(),
                            varos: $('input[name="varos"]').val(),
                            utca: $('input[name="utca"]').val()
                        },
                        success: function (res) {
                            const adat = parseValasz(res);
                            if (!adat || !adat.ok) {
                                showBejelentkezesHiba((adat && adat.msg) || HIBA_ALAP);
                                return;
                            }
                            bejelentkezesSikeres = true;
                            $('#modal-form').hide();
                            $('.js-siker').text(adat.msg).show();
                            $('.js-bezaras').show();
                        },
                        error: function () {
                            showBejelentkezesHiba(HIBA_ALAP);
                        },
                        complete: function () {
                            $gomb.prop('disabled', false);
                        }
                    });
                }
            });
            $('.js-lemondok').click(function (e) {
                e.preventDefault();
                // ha nev input hidden
                // akkor lekerdezni, hogy ismerjuk-e az emailt
                //      ha nem, akkor megjeleniteni a nev inputot
                //      es ismeretlen input legyen true
                //      egyebkent ismeretlen input legyen false es menteni
                // egyebkent menteni
                if (!$('input[name="lemondemail"]').val()) {
                    alert('Add meg az email címed!');
                } else {
                    $.ajax({
                        url: '/orarend/lemondas',
                        type: 'POST',
                        data: {
                            id: $('input[name="lemondid"]').val(),
                            datum: $('input[name="lemonddatum"]').val(),
                            email: $('input[name="lemondemail"]').val()
                        },
                        success: function () {
                            toggleLemondmodal(0);
                            location.reload();
                        }
                    });
                }
            });
            window.addEventListener("click", windowOnClick);
        });
    </script>
    <style>
        body {
            font-family: 'Arial', Helvetica, Arial, Lucida, sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: #666;
        }

        a {
            color: #b63535;
            text-decoration: none;
        }

        a:hover {
            text-decoration: none;
        }

        .margin-bottom-5 {
            margin-bottom: 5px;
        }

        .dtt {
            text-align: left;
        }

        .dttelmarad .dttoranev, .dttelmarad .dtttanar {
            color: white;
        }

        .dttnapnev {
            text-align: center;
            width: 100%;
            border-radius: 3px;
            padding: 10px 0;
            margin-bottom: 2px;
            color: #669999;
            font-weight: bold;
            font-variant: all-small-caps;
            font-size: 20px;
            background-color: #ded4d4;
        }

        .dttora {
            display: flex;
            margin-bottom: 2px;
            background-color: #fff9f7;
            width: 100%;
        }

        .dttidopont {
            text-align: center;
            margin: 0 1%;
            padding: 0 2px;
            flex-basis: 16%;
            border-radius: 3px;
            color: white;
            font-weight: bold;
            background-color: #669999;
        }

        .delelott {
            background-color: #A5C663;
        }

        .pirosszoveg {
            color: #B63535;
            font-size: 16px;
            font-weight: bold;
        }

        .dttoranev {
            padding: 10px 0;
            margin-right: 1%;
            flex-basis: 60%;
            text-align: center;
        }

        .dtttanar {
            padding: 10px 0;
            margin-right: 1%;
            flex-basis: 26%;
            text-align: center;
        }

        .dttprev {
            color: #80008c;
        }

        .dttprev, .dttnext, .dttakt {
            background-color: #80008c;
            font-weight: bold;
            font-variant: all-small-caps;
            font-size: 20px;
            border-radius: 3px;
            padding: 10px;
            margin: 5px;
            color: white;
            flex-basis: 33.333%;
        }

        .dttlapozo {
            text-align: center;
            display: flex;
        }

        .dttonlinelink {
            font-weight: bold;
        }

        .dttorarendbutton {
            background-color: #80008c;
            color: white;
            border-radius: 3px;
            padding: 10px;
            display: block;
        }

        .modal, .lemondmodal {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transform: scale(1.1);
            transition: visibility 0s linear 0.25s, opacity 0.25s 0s, transform 0.25s;
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, 0%);
            background-color: white;
            padding: 1rem 1.5rem;
            width: 80%;
            border-radius: 0.1rem;
        }

        .close-button, .lemondclose-button {
            float: right;
            width: 1.5rem;
            line-height: 1.5rem;
            text-align: center;
            cursor: pointer;
            border-radius: 0.25rem;
            background-color: lightgray;
        }

        .close-button:hover, .lemondclose-button:hover {
            background-color: darkgray;
        }

        .show-modal {
            opacity: 1;
            visibility: visible;
            transform: scale(1.0);
            transition: visibility 0s linear 0s, opacity 0.25s 0s, transform 0.25s;
        }

        .bejelentkezesbtn {
            color: #fff;
            background-color: #80008C;
            display: inline-block;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            border: 1px solid #80008C;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .25rem;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .form-group {
            margin-bottom: 1rem;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .form-hint {
            margin: 0 0 .5rem;
            font-size: .9rem;
            line-height: 1.4;
        }

        .form-alcim {
            margin: 0 0 1rem;
            font-weight: bold;
        }

        .form-hiba {
            margin: 0 0 1rem;
            color: #B63535;
            font-weight: bold;
        }

        .form-siker {
            margin: 0 0 1rem;
            color: #000;
            font-size: 16px;
        }

        .form-label {
            padding-top: calc(.375rem + 1px);
            padding-bottom: calc(.375rem + 1px);
            margin-bottom: 0;
            font-size: 1rem;
            line-height: 1.5;
        }

        .form-control {
            display: block;
            width: 100%;
            height: calc(2.25rem + 2px);
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        /* Responsive Styles Smartphone Portrait */
        @media all and (max-width: 479px) {
            .dttlapozo {
                flex-direction: column;
            }

            .dttrow {
                width: 94%;
            }
        }

        /* Responsive Styles Smartphone Landscape */
        @media all and (max-width: 980px) {
            .dttlapozo {
                flex-direction: column;
            }

            .dttrow {
                width: 94%;
            }
        }
    </style>

</head>

<body>
<div class="dtt">
    <div class="dttlapozo">
        <a href="/orarend/wp?o={$prevoffset}{$szuroparam}" class="dttprev">Előző hét</a>
        <a href="/orarend/wp?o=0{$szuroparam}" class="dttakt">Aktuális hét</a>
        <a href="/orarend/wp?o={$nextoffset}{$szuroparam}" class="dttnext">Következő hét</a>
    </div>
    {foreach $orarend as $nap}
        <div class="dttnap">
            <div class="dttnapnev">{$nap['napnev']} - {$nap['napdatum']}</div>
            {foreach $nap['orak'] as $ora}
                <div class="dttora">
                    <div class="dttidopont{if ($ora['delelott'])} delelott{/if}">{$ora['kezdet']}-{$ora['veg']}</div>
                    <div class="dttoranev">
                        <div class="margin-bottom-5">
                            <a href="{if ($ora['oraurl'])}{prefixUrl('http://jogadarshan.hu/', $ora['oraurl'])}{/if}"
                               target="_parent">{if ($ora['elmarad'])}ELMARAD! {/if}{$ora['oranev']}</a>{if ($ora['multilang'])}<span> (HU/EN)</span>{/if}
                        </div>
                        <div class="margin-bottom-5">
                            <a href="{if ($ora['tanarurl'])}{prefixUrl('http://jogadarshan.hu/', $ora['tanarurl'])}{/if}"
                               target="_parent">{$ora['tanar']}{if ($ora['helyettesito'])} HELYETTESÍT: {$ora['helyettesito']}{/if}{if ($ora['elmarad'])} ELMARAD!{/if}</a>
                        </div>
                        {if ($ora['maxbejelentkezes'] > 0)}
                            <div>{$ora['szabadhely']} szabad hely</div>
                        {/if}
                    </div>
                    <div class="dtttanar">
                        {if (!$ora['elmarad'] && $ora['bejelentkezeskell'] && $ora['megvanhely'])}
                            <div>
                                <a href="#" class="dttonlinelink dttorarendbutton margin-bottom-5 js-bejelentkezes" data-id="{$ora['id']}"
                                   data-datum="{$ora['datum']}" data-oranev="{$ora['oranev']|escape}"
                                   data-idopont="{$nap['napnev']|escape} {$nap['napdatum']|escape} {$ora['kezdet']|escape}">
                                    {if ($ora['onlineurl'])}1. {/if}Bejelentkezek
                                </a>
                            </div>
                        {elseif (!$ora['megvanhely'])}
                            <div class="pirosszoveg">
                                BETELT
                            </div>
                        {/if}
                        {if (!$ora['elmarad'] && $ora['onlineurl'])}
                            <div>
                                <a href="{$ora['onlineurl']}" target="_blank" class="dttonlinelink dttorarendbutton margin-bottom-5">
                                    {if ($ora['bejelentkezeskell'])}2. {/if}Csatlakozom
                                </a>
                            </div>
                        {/if}
                        {if (!$ora['elmarad'] && $ora['bejelentkezeskell'])}
                            <div>
                                <a href="#" class="dttonlinelink dttorarendbutton js-lemondas" data-id="{$ora['id']}" data-datum="{$ora['datum']}">
                                    Lemondom
                                </a>
                            </div>
                        {/if}
                    </div>
                </div>
            {/foreach}
        </div>
    {/foreach}
</div>
<div class="modal">
    <div class="modal-content">
        <span class="close-button">×</span>
        <h1>Bejelentkezés</h1>
        <p class="form-alcim js-alcim"></p>
        <form id="modal-form">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input class="form-control" type="email" name="email" required>
            </div>
            <div class="form-group">
                <label class="form-label">Teljes név</label>
                <input class="form-control" type="text" name="partnernev" required>
            </div>
            <p class="form-hint">Vezeték- és keresztnév.</p>
            <p class="form-hint js-cimszoveg">Ha először jössz, add meg a címed a számlához. A már megadott adataidat nem írjuk felül.</p>
            <div class="js-cimmezok">
                <div class="form-group">
                    <label class="form-label">Irányítószám</label>
                    <input class="form-control" type="text" name="irszam" maxlength="10">
                </div>
                <div class="form-group">
                    <label class="form-label">Város</label>
                    <input class="form-control" type="text" name="varos">
                </div>
                <div class="form-group">
                    <label class="form-label">Utca, házszám</label>
                    <input class="form-control" type="text" name="utca">
                </div>
            </div>
            <input type="hidden" name="id">
            <input type="hidden" name="datum">
            <p class="form-hiba js-hiba" style="display: none"></p>
            <button class="js-ok bejelentkezesbtn">Bejelentkezem</button>
        </form>
        <p class="form-siker js-siker" style="display: none"></p>
        <button class="bejelentkezesbtn js-bezaras" style="display: none">Bezárás</button>
    </div>
</div>
<div class="lemondmodal">
    <div class="modal-content">
        <span class="lemondclose-button">×</span>
        <h1>Lemondás</h1>
        <form id="lemondmodal-form">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input class="form-control" type="email" name="lemondemail" required>
            </div>
            <input type="hidden" name="lemondid">
            <input type="hidden" name="lemonddatum">
            <button class="js-lemondok bejelentkezesbtn">OK</button>
        </form>
    </div>
</div>
</body>
</html>