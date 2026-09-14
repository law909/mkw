<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="/js/main/darshan/iframeResizer.contentWindow.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery-3.7.1.min.js"></script>
    <script type="text/javascript">
    {literal}
        $(document).ready(function () {
            const modal = document.querySelector('.modal'),
                HIBA_ALAP = 'A kérés nem sikerült, kérjük, próbáld újra.';

            function parseValasz(res) {
                return (typeof res === 'string') ? (res ? JSON.parse(res) : null) : res;
            }

            function showHiba(msg) {
                $('.js-hiba').text(msg).toggle(!!msg);
            }

            // mentés után a bezárás újratölt, hogy a mentett adatok látsszanak
            function toggleModal() {
                if (modal.classList.contains('show-modal')) {
                    location.reload();
                }
                modal.classList.toggle('show-modal');
            }

            $('body').on('click', '.js-save', function (e) {
                const $gomb = $(this),
                    data = {
                        e: $('input[name="e"]').val(),
                        l: $('input[name="l"]').val(),
                        h: $('input[name="h"]').val(),
                        vezeteknev: $('input[name="vezeteknev"]').val(),
                        keresztnev: $('input[name="keresztnev"]').val(),
                        irszam: $('input[name="irszam"]').val(),
                        varos: $('input[name="varos"]').val(),
                        utca: $('input[name="utca"]').val(),
                        hazszam: $('input[name="hazszam"]').val()
                    };
                e.preventDefault();
                if ($('input[name="hirlevelkell"]').prop('checked')) {
                    data.hirlevelkell = 1;
                }
                showHiba('');
                $gomb.prop('disabled', true);
                $.ajax({
                    url: '/adategy/save',
                    type: 'POST',
                    data: data,
                    success: function (res) {
                        const adat = parseValasz(res);
                        if (!adat || !adat.ok) {
                            showHiba((adat && adat.msg) || HIBA_ALAP);
                            return;
                        }
                        toggleModal();
                    },
                    error: function () {
                        showHiba(HIBA_ALAP);
                    },
                    complete: function () {
                        $gomb.prop('disabled', false);
                    }
                });
            });
            $('.close-button').click(function (e) {
                e.preventDefault();
                toggleModal();
            });
            $('.js-ok').click(function (e) {
                const $gomb = $(this),
                    email = ($('input[name="email"]').val() || '').trim();
                e.preventDefault();
                if (!email) {
                    showHiba('Kérjük, adj meg egy emailcímet!');
                    return;
                }
                showHiba('');
                $gomb.prop('disabled', true);
                $.ajax({
                    url: '/adategy/check',
                    type: 'POST',
                    data: {
                        email: email
                    },
                    success: function (res) {
                        const adat = parseValasz(res);
                        if (!adat || !adat.ok) {
                            showHiba((adat && adat.msg) || HIBA_ALAP);
                            $gomb.prop('disabled', false);
                            return;
                        }
                        $('.js-uzenet').text(adat.msg).show();
                    },
                    error: function () {
                        showHiba(HIBA_ALAP);
                        $gomb.prop('disabled', false);
                    }
                });
            });
            window.addEventListener('click', function (event) {
                if (event.target === modal) {
                    toggleModal();
                }
            });
        });
    {/literal}
    </script>
    <style>
        body {
            font-family: 'Arial',Helvetica,Arial,Lucida,sans-serif;
            font-size: 16px;
            font-weight: 500;
            color: #666;
            padding: 0 10px 5px 10px;
        }
        a {
            color: #b63535;
            text-decoration: none;
        }
        a:hover {
            text-decoration: none;
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
        .adategyeztetobtn {
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
        .hiba {
            color: #B63535;
            font-weight: bold;
        }
        .uzenet {
            color: #000;
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
    </style>

</head>

<body>
    <div>
        <h1>Adategyeztető</h1>
        {if ($link|default)}
            <form id="adatlap-form">
                {include 'adategyeztetoadatlap.tpl'}
            </form>
        {else}
            {if ($hiba|default)}
                <p class="hiba">{$hiba|escape}</p>
            {/if}
            <p>Írd be az emailcímed, és nyomd meg az „Egyeztetés” gombot. Küldünk egy levelet egy linkkel, amellyel
                megnézheted és javíthatod az adataidat.</p>
            <form id="adategyezteto-form">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email">
                </div>
                <p class="hiba js-hiba" style="display: none"></p>
                <p class="uzenet js-uzenet" style="display: none"></p>
                <button class="js-ok adategyeztetobtn">Egyeztetés</button>
            </form>
        {/if}
    </div>
    <div class="modal">
        <div class="modal-content">
            <span class="close-button">×</span>
            <h2>Köszönjük, hogy segíted a munkánkat!</h2>
        </div>
    </div>
</body>
</html>