<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="/js/main/darshan/iframeResizer.contentWindow.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery-1.11.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var modal = document.querySelector(".modal");

            function toggleModal() {
                modal.classList.toggle("show-modal");
            }

            function windowOnClick(event) {
                if (event.target === modal) {
                    toggleModal();
                }
            }

            $('body').on('click', '.js-bejelentkezes', function(e) {
                var $this = $(this);
                e.preventDefault();
                $('input[name="id"]').val($this.data('id'));
                $('input[name="datum"]').val($this.data('datum'));
                toggleModal();
            });
            $('input[name="email"]').change(function(e) {
                var ee = $(this);
                $.ajax({
                    url: '/partner/getdata',
                    type: 'GET',
                    data: {
                        email: ee.val()
                    },
                    success: function(data) {
                        var d = JSON.parse(data);
                        if (d.id) {
                            $('input[name="partnernev"]').val(d.nev);
                        }
                    }
                });
            });
            $('.close-button').click(function(e) {
                e.preventDefault();
                toggleModal();
            });
            $('.js-ok').click(function(e) {
                e.preventDefault();
                // ha nev input hidden
                // akkor lekerdezni, hogy ismerjuk-e az emailt
                //      ha nem, akkor megjeleniteni a nev inputot
                //      es ismeretlen input legyen true
                //      egyebkent ismeretlen input legyen false es menteni
                // egyebkent menteni
                if (!$('input[name="email"]').val()) {
                    alert('Add meg az email címed!');
                }
                else {
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
                                toggleModal();
                                location.reload();
                            }
                        });
                    }
                }
            });
            window.addEventListener("click", windowOnClick);
        });
    </script>
    <style>
        body {
            font-family: 'Arial',Helvetica,Arial,Lucida,sans-serif;
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
        .dtt {
            text-align: left;
        }
        .dttelmarad .dttoranev, .dttelmarad .dtttanar {
            color: white;
        }
        .dttnap {
            clear: both;
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
            float: left;
            margin-bottom: 2px;
            background-color: #fff9f7;
            width: 100%;
        }
        .dttidopont {
            text-align: center;
            float: left;
            padding: 10px 0;
            margin: 0 1%;
            width: 10%;
            border-radius: 3px;
            color: white;
            font-weight: bold;
            background-color: #669999;
        }
        .delelott {
            background-color: #A5C663;
        }
        .dttoranev {
            float: left;
            padding: 10px 0;
            margin-right: 1%;
            width: 45%;
            text-align: center;
        }
        .dtttanar {
            float: left;
            padding: 10px 0;
            margin-right: 1%;
            width: 40%;
            text-align: center;
        }
        .dttprev {
            float: left;
            color: #80008c;
        }
        .dttnext {
            float: right;
        }
        .dttakt {
            margin-left: 40px;
        }
        .dttprev, .dttnext, .dttakt {
            background-color: #80008c;
            font-weight: bold;
            font-variant: all-small-caps;
            font-size: 20px;
            border-radius: 3px;
            padding: 10px;
            margin-bottom: 2px;
            color: white;
        }
        .dttlapozo {
            text-align: center;
        }
        .dttonlinelink {
            font-weight: bold;
        }
        .modal {
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
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 1rem 1.5rem;
            width: 24rem;
            border-radius: 0.1rem;
        }
        .close-button {
            float: right;
            width: 1.5rem;
            line-height: 1.5rem;
            text-align: center;
            cursor: pointer;
            border-radius: 0.25rem;
            background-color: lightgray;
        }
        .close-button:hover {
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
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
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
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        /* Responsive Styles Smartphone Portrait */
        @media all and (max-width: 479px) {
            .dttidopont {
                margin: 0 1%;
                padding: 2px;
                width: 14%;
            }
            .dttoranev {
                width: 46%;
                font-size: 12px;
                font-weight: bold;
            }
            .dtttanar {
                width: 30%;
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
                padding: 2px;
                width: 14%;
            }
            .dttoranev {
                width: 46%;
                font-size: 12px;
                font-weight: bold;
            }
            .dtttanar {
                width: 30%;
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
            <div class="dttoranev"><a href="{if ($ora['oraurl'])}http://jogadarshan.hu/{$ora['oraurl']}{/if}" target="_parent">{if ($ora['elmarad'])}ELMARAD! {/if}{$ora['oranev']}</a>{if ($ora['multilang'])}<span> (HU/EN)</span>{/if}</div>
            <div class="dtttanar">
                <a href="{if ($ora['tanarurl'])}http://jogadarshan.hu/{$ora['tanarurl']}{/if}" target="_parent">{$ora['tanar']}{if ($ora['helyettesito'])} HELYETTESÍT: {$ora['helyettesito']}{/if}{if ($ora['elmarad'])} ELMARAD!{/if}</a>
                {if (!$ora['elmarad'] && $ora['onlineurl'])}<div><a href="{$ora['onlineurl']}" target="_blank" class="dttonlinelink">Csatlakozom</a></div>{/if}
                {if (!$ora['elmarad'] && $ora['bejelentkezeskell'])}
                    <div>
                        <a href="{$ora['onlineurl']}" target="_blank" class="dttonlinelink js-bejelentkezes" data-id="{$ora['id']}" data-datum="{$ora['datum']}">
                            Bejelentkezek (eddig {$ora['bejelentkezesdb']} fő)
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
</body>
</html>