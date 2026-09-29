/**
 * Feltöltési hiba szövege egy sikertelen $.ajax hívásból – a médiatár és a dokumentum fül
 * „Azonnali feltöltés”-e közös. A nem-JSON válaszok (403, 413, PHP fatal, üres törzs) is
 * mondjanak valamit, ne csak annyit, hogy „nem sikerült”.
 */
window.uploadErrorText = function (xhr, status) {
    'use strict';
    try {
        const d = JSON.parse(xhr.responseText);
        if (d && d.error) {
            return d.error;
        }
    } catch (e) {
        // nem JSON
    }
    if (status === 'timeout') {
        return 'időtúllépés: a szerver nem válaszolt időben';
    }
    if (xhr.status === 0) {
        return 'megszakadt a kapcsolat a szerverrel';
    }
    if (xhr.status === 413) {
        return 'a fájl nagyobb, mint amit a webszerver elfogad (HTTP 413)';
    }
    if (xhr.status === 401 || xhr.status === 403) {
        return `nincs jogosultság, vagy lejárt a bejelentkezés (HTTP ${xhr.status})`;
    }
    if (xhr.status >= 500) {
        return `szerverhiba a feldolgozás közben (HTTP ${xhr.status})`;
    }
    if ((xhr.responseText || '').trim() === '') {
        return 'a szerver üres választ adott, a fájl nem mentődött el (lehet, hogy túl nagy, vagy a szerver ideiglenes mappája nem írható)';
    }
    return `a szerver nem várt választ adott (HTTP ${xhr.status})`;
};
