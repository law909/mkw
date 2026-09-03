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

    .dttadatok {
        display: flex;
        flex: 1 1 auto;
        min-width: 0;
    }

    .dttidopont {
        text-align: center;
        margin: 0 1%;
        padding: 0 2px;
        flex: 0 0 20%;
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
        flex: 1 1 auto;
        text-align: left;
    }

    .dttgombok {
        padding: 10px 0;
        margin-right: 1%;
        flex: 0 0 26%;
        text-align: center;
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

    .dttorarendbutton {
        background-color: #80008c;
        color: white;
        border-radius: 3px;
        padding: 10px;
        display: block;
        font-weight: bold;
    }

    .dttures {
        text-align: center;
        padding: 20px 0;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        padding-top: calc(.375rem + 1px);
        padding-bottom: calc(.375rem + 1px);
        margin-bottom: 0;
        font-size: 1rem;
        line-height: 1.5;
    }

    .form-control {
        display: block;
        width: 100%;
        max-width: 400px;
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
    }

    .foglalasbtn {
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
        cursor: pointer;
    }

    .lemondasbtn {
        color: #80008C;
        background-color: #fff;
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
        cursor: pointer;
    }

    .lemondasblokk {
        border-top: 1px solid #ded4d4;
        margin-top: 20px;
        padding-top: 15px;
    }

    .foglalasfejlec {
        background-color: #fff9f7;
        border-radius: 3px;
        padding: 10px;
        margin-bottom: 15px;
    }

    .foglalashiba {
        color: #B63535;
        font-weight: bold;
        margin-bottom: 15px;
    }

    /* Responsive Styles Smartphone Landscape */
    @media all and (max-width: 980px) {
        .dttlapozo {
            flex-direction: column;
        }

        .dttora, .dttadatok {
            flex-direction: column;
        }

        .dttidopont {
            flex: 0 0 auto;
        }
    }
</style>
