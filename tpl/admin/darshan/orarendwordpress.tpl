<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="/js/main/darshan/iframeResizer.contentWindow.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery-1.11.1.min.js"></script>
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
                    toggleModal(0);
                } else if (event.target === lemondmodal) {
                    toggleLemondmodal(0);
                }
            }

            $('body').on('click', '.js-bejelentkezes', function (e) {
                var $this = $(this);
                e.preventDefault();
                $('input[name="id"]').val($this.data('id'));
                $('input[name="datum"]').val($this.data('datum'));
                toggleModal(this.getBoundingClientRect().y);
            });
            $('body').on('click', '.js-lemondas', function (e) {
                var $this = $(this);
                e.preventDefault();
                $('input[name="lemondid"]').val($this.data('id'));
                $('input[name="lemonddatum"]').val($this.data('datum'));
                toggleLemondmodal(this.getBoundingClientRect().y);
            });
            $('input[name="email"]').change(function (e) {
                var ee = $(this);
                $.ajax({
                    url: '/partner/getdata',
                    type: 'GET',
                    data: {
                        email: ee.val()
                    },
                    success: function (data) {
                        var d = JSON.parse(data);
                        if (d.id) {
                            $('input[name="partnernev"]').val(d.nev);
                        }
                    }
                });
            });
            $('.close-button').click(function (e) {
                e.preventDefault();
                toggleModal(0);
            });
            $('.lemondclose-button').click(function (e) {
                e.preventDefault();
                toggleLemondmodal(0);
            });
            $('.js-ok').click(function (e) {
                e.preventDefault();
                // ha nev input hidden
                // akkor lekerdezni, hogy ismerjuk-e az emailt
                //      ha nem, akkor megjeleniteni a nev inputot
                //      es ismeretlen input legyen true
                //      egyebkent ismeretlen input legyen false es menteni
                // egyebkent menteni
                if (!$('input[name="email"]').val()) {
                    alert('Add meg az email címed!');
                } else {
                    if (!$('input[name="partnernev"]').val()) {
                        alert('Add meg a neved!');
                    } else {
                        $.ajax({
                            url: '/orarend/bejelentkezes',
                            type: 'POST',
                            data: {
                                id: $('input[name="id"]').val(),
                                datum: $('input[name="datum"]').val(),
                                partnernev: $('input[name="partnernev"]').val(),
                                email: $('input[name="email"]').val()
                            },
                            success: function () {
                                toggleModal(0);
                                location.reload();
                            }
                        });
                    }
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

            .dttidopont {
                margin: 0 1%;
                flex-basis: 14%;
                flex-shrink: 0;
            }

            .dttoranev {
                flex-basis: 60%;
                font-size: 12px;
                font-weight: bold;
            }

            .dtttanar {
                flex-basis: 26%;
                font-size: 12px;
            }

            .dttrow {
                width: 94%;
            }
        }

        /* Responsive Styles Smartphone Landscape */
        @media all and (max-width: 980px) {
            .dttidopont {
                margin: 0 1%;
                flex-basis: 14%;
                flex-shrink: 0;
            }

            .dttoranev {
                flex-basis: 60%;
                font-size: 12px;
                font-weight: bold;
            }

            .dtttanar {
                flex-basis: 26%;
                font-size: 12px;
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
        <a href="/orarend/wp?o={$prevoffset}{if ($tanarkod)}&t={$tanarkod}{/if}" class="dttprev">Előző hét</a>
        <a href="/orarend/wp{if ($tanarkod)}?t={$tanarkod}{/if}" class="dttakt">Aktuális hét</a>
        <a href="/orarend/wp?o={$nextoffset}{if ($tanarkod)}&t={$tanarkod}{/if}" class="dttnext">Következő hét</a>
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
                        {if (($ora['szabadhely'] <= 5) && ($ora['szabadhely'] > 0))}
                            <div>{$ora['szabadhely']} szabad hely</div>
                        {/if}
                    </div>
                    <div class="dtttanar">
                        {if (!$ora['elmarad'] && $ora['bejelentkezeskell'] && $ora['megvanhely'])}
                            <div>
                                <a href="#" class="dttonlinelink dttorarendbutton margin-bottom-5 js-bejelentkezes" data-id="{$ora['id']}"
                                   data-datum="{$ora['datum']}">
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
        <h1>Add meg az adataidat</h1>
        <form id="modal-form">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input class="form-control" type="email" name="email" required>
            </div>
            <div class="form-group">
                <label class="form-label">Név</label>
                <input class="form-control" type="text" name="partnernev" required>
            </div>
            <input type="hidden" name="id">
            <input type="hidden" name="datum">
            <button class="js-ok bejelentkezesbtn">OK</button>
        </form>
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