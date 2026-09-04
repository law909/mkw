/**
 * Születésnapi ASCII tűzijáték: teljes képernyős karakteres animáció 30 másodpercig, a
 * képernyő közepén a BOLDOG SZÜLETÉSNAPOT!! felirattal. A bejelentkezés utáni első főoldalon
 * indul (base.tpl, $szuletesnap), utána a szerver eldobja a jelzőt.
 * Kattintásra és Escape-re korábban is bezárható.
 */
(() => {
    const HOSSZ = 30000;
    const KEP_MS = 60;
    // a robbanás gyűrűi kifelé haladva egyre halványabb karaktert kapnak
    const SZIKRA = ['@', '#', '*', '+', ':', '.'];
    const SZINEK = ['#ffd166', '#ef476f', '#06d6a0', '#4cc9f0', '#f78c6b', '#c77dff'];

    const overlay = document.getElementById('szuletesnap');
    const kepernyo = overlay ? overlay.querySelector('.szuletesnap-kep') : null;
    if (!kepernyo) {
        return;
    }

    // a karakterrács mérete a doboz betűméretéből jön ki, hogy kitöltse a képernyőt
    const proba = document.createElement('span');
    proba.textContent = 'X';
    proba.style.position = 'absolute';
    proba.style.visibility = 'hidden';
    kepernyo.appendChild(proba);
    const meret = proba.getBoundingClientRect();
    proba.remove();

    const oszlop = Math.max(40, Math.floor(kepernyo.clientWidth / (meret.width || 8)));
    const sor = Math.max(20, Math.floor(kepernyo.clientHeight / (meret.height || 16)));
    // annyi rakéta legyen egyszerre, hogy a képernyő szélességéhez mérten ne tűnjön üresnek
    const MAX_RAKETA = Math.max(6, Math.round(oszlop / 16));

    const raketak = [];
    const veg = Date.now() + HOSSZ;
    let idozito = null;

    function ujRaketa() {
        return {
            x: 4 + Math.random() * (oszlop - 8),
            y: sor - 1,
            celY: 6 + Math.random() * (sor * 0.5),
            szin: SZINEK[Math.floor(Math.random() * SZINEK.length)],
            robbant: false,
            kor: 0,
            maxKor: 9 + Math.floor(Math.random() * 8)
        };
    }

    /** A karakterek kétszer olyan magasak, mint szélesek: vízszintesen tágabb a kör. */
    function korRajzol(racs, r, sugar, kar) {
        if (sugar < 1) {
            return;
        }
        const agak = Math.max(10, Math.round(2 * Math.PI * sugar * 1.6));
        for (let a = 0; a < agak; a++) {
            const szog = (Math.PI * 2 * a) / agak;
            const x = Math.round(r.x + Math.cos(szog) * sugar * 2);
            const y = Math.round(r.y + Math.sin(szog) * sugar);
            if (y >= 0 && y < sor && x >= 0 && x < oszlop) {
                racs[y][x] = {kar: kar, szin: r.szin};
            }
        }
    }

    function rajzol() {
        const racs = [];
        for (let y = 0; y < sor; y++) {
            racs.push(new Array(oszlop).fill(null));
        }

        raketak.forEach(r => {
            if (!r.robbant) {
                // rövid csóva a rakéta alatt
                for (let t = 0; t < 3; t++) {
                    const x = Math.round(r.x);
                    const y = Math.round(r.y) + t;
                    if (y >= 0 && y < sor && x >= 0 && x < oszlop) {
                        racs[y][x] = {kar: t === 0 ? '|' : (t === 1 ? ':' : '.'), szin: r.szin};
                    }
                }
                return;
            }
            // a robbanás három gyűrűje: a külső a friss, a belsők halványodva maradnak utána
            for (let g = 0; g < 3; g++) {
                const sugar = r.kor - g;
                const kar = SZIKRA[Math.min(SZIKRA.length - 1, Math.floor(((r.kor + g) / (r.maxKor + 2)) * SZIKRA.length))];
                korRajzol(racs, r, sugar, kar);
            }
        });

        let html = '';
        for (let y = 0; y < sor; y++) {
            for (let x = 0; x < oszlop; x++) {
                const cella = racs[y][x];
                html += cella ? '<i style="color:' + cella.szin + '">' + cella.kar + '</i>' : ' ';
            }
            html += '\n';
        }
        kepernyo.innerHTML = html;
    }

    function lep() {
        if (Date.now() >= veg) {
            bezar();
            return;
        }
        if (raketak.length < MAX_RAKETA && Math.random() < 0.5) {
            raketak.push(ujRaketa());
        }
        for (let i = raketak.length - 1; i >= 0; i--) {
            const r = raketak[i];
            if (!r.robbant) {
                r.y -= 1.6;
                if (r.y <= r.celY) {
                    r.robbant = true;
                }
            } else {
                r.kor++;
                if (r.kor > r.maxKor) {
                    raketak.splice(i, 1);
                }
            }
        }
        rajzol();
    }

    function bezar() {
        if (idozito) {
            clearInterval(idozito);
            idozito = null;
        }
        document.removeEventListener('keydown', escFigyelo);
        overlay.remove();
    }

    function escFigyelo(e) {
        if (e.key === 'Escape') {
            bezar();
        }
    }

    // indulásnál legyen már mit nézni
    for (let i = 0; i < MAX_RAKETA; i++) {
        const r = ujRaketa();
        r.y = r.celY + Math.random() * (sor - r.celY);
        raketak.push(r);
    }

    overlay.addEventListener('click', bezar);
    document.addEventListener('keydown', escFigyelo);
    idozito = setInterval(lep, KEP_MS);
    rajzol();
})();
