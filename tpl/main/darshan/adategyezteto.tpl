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
                if (modal) {
                    if (modal.classList.contains("show-modal")) {
                        location.reload();
                    }
                    modal.classList.toggle("show-modal");
                }
            }

            function windowOnClick(event) {
                if (event.target === modal) {
                    toggleModal();
                }
            }

            $('body').on('click', '.js-save', function(e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/adategy/save',
                    type: 'POST',
                    data: {
                        email: $('input[name="email"]').val(),
                        vezeteknev: $('input[name="vezeteknev"]').val(),
                        keresztnev: $('input[name="keresztnev"]').val(),
                        irszam: $('input[name="irszam"]').val(),
                        varos: $('input[name="varos"]').val(),
                        utca: $('input[name="utca"]').val(),
                        hazszam: $('input[name="hazszam"]').val(),
                        hirlevelkell: $('input[name="hirlevelkell"]').val()
                    },
                    success: function () {
                        toggleModal();
                    }
                });
            });
            $('.close-button').click(function(e) {
                e.preventDefault();
                toggleModal();
            });
            $('.js-ok').click(function(e) {
                var email = $('input[name="email"]').val();
                e.preventDefault();
                if (!email) {
                    alert('Kérjük adj meg egy emailcímet!');
                }
                else {
                    $.ajax({
                        url: '/adategy/check',
                        type: 'POST',
                        data: {
                            email: email
                        },
                        success: function (adat) {
                            $('.js-ok').remove();
                            $('#adatlap').html(adat);
                        }
                    });
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
        <form id="adategyezteto-form">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input class="form-control" type="email" name="email">
            </div>
            <button class="js-ok adategyeztetobtn">Egyeztetés</button>
            <div id="adatlap"></div>
        </form>
    </div>
    <div class="modal">
        <div class="modal-content">
            <span class="close-button">×</span>
            <h2>Köszönjük, hogy segíted a munkánkat!</h2>
        </div>
    </div>
</body>
</html>