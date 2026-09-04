/**
 * Születésnapi tűzijáték: teljes képernyős canvas animáció 30 másodpercig, a képernyő közepén
 * a BOLDOG SZÜLETÉSNAPOT!! felirattal. A bejelentkezés utáni első főoldalon indul
 * (base.tpl, $szuletesnap), utána a szerver eldobja a jelzőt.
 * Kattintásra és Escape-re korábban is bezárható.
 */
(() => {
    const HOSSZ = 30000;
    // a rakéták színe: minden robbanás ezek egyikét kapja, a szikrák körülötte szórnak
    const SZINEK = [0, 20, 40, 55, 100, 140, 170, 195, 220, 260, 285, 320, 340];
    const GRAVITACIO = 0.055;
    const LEGELLENALLAS = 0.985;

    const overlay = document.getElementById('szuletesnap');
    const vaszon = overlay ? overlay.querySelector('.szuletesnap-kep') : null;
    if (!vaszon || !vaszon.getContext) {
        return;
    }
    const ctx = vaszon.getContext('2d');

    let szelesseg = 0;
    let magassag = 0;

    function meretez() {
        const arany = window.devicePixelRatio || 1;
        szelesseg = vaszon.clientWidth;
        magassag = vaszon.clientHeight;
        vaszon.width = Math.round(szelesseg * arany);
        vaszon.height = Math.round(magassag * arany);
        ctx.setTransform(arany, 0, 0, arany, 0, 0);
        ctx.fillStyle = '#06060c';
        ctx.fillRect(0, 0, szelesseg, magassag);
    }

    const raketak = [];
    const szikrak = [];
    const veg = Date.now() + HOSSZ;
    let fut = true;
    // háttérfülön a requestAnimationFrame nem fut, tehát a cikluson belüli óra sem jár:
    // ez az időzítő gondoskodik róla, hogy az overlay akkor is eltűnjön
    let vegIdozito = null;

    function ujRaketa() {
        const szin = SZINEK[Math.floor(Math.random() * SZINEK.length)];
        raketak.push({
            x: szelesseg * (0.08 + Math.random() * 0.84),
            y: magassag + 10,
            vx: (Math.random() - 0.5) * 1.2,
            vy: -(magassag * 0.011 + Math.random() * magassag * 0.006),
            szin: szin,
            celY: magassag * (0.08 + Math.random() * 0.45)
        });
    }

    /**
     * A robbanás szikrái. A gömb alakú szórás mellé néha gyűrűt is rajzolunk, hogy ne
     * legyen egyforma minden robbanás.
     */
    function robban(r) {
        const gyuru = Math.random() < 0.35;
        const db = gyuru ? 70 + Math.floor(Math.random() * 40) : 90 + Math.floor(Math.random() * 70);
        const alapSebesseg = 1.8 + Math.random() * 2.6;
        // a második szín a szomszédos árnyalatok közül jön, így egy robbanás színben összetartozik
        const masikSzin = (r.szin + 20 + Math.floor(Math.random() * 60)) % 360;
        for (let i = 0; i < db; i++) {
            const szog = Math.random() * Math.PI * 2;
            const sebesseg = gyuru
                ? alapSebesseg * (0.92 + Math.random() * 0.16)
                : alapSebesseg * Math.sqrt(Math.random());
            szikrak.push({
                x: r.x,
                y: r.y,
                vx: Math.cos(szog) * sebesseg,
                vy: Math.sin(szog) * sebesseg,
                szin: Math.random() < 0.3 ? masikSzin : r.szin,
                vilagossag: 55 + Math.random() * 35,
                elet: 1,
                fogyas: 0.006 + Math.random() * 0.010,
                meret: 1 + Math.random() * 1.6
            });
        }
    }

    function lep() {
        if (!fut) {
            return;
        }
        if (Date.now() >= veg) {
            bezar();
            return;
        }

        // a rajz nem törlődik, csak halványul: ettől húznak csóvát a szikrák
        ctx.globalCompositeOperation = 'source-over';
        ctx.fillStyle = 'rgba(6, 6, 12, 0.22)';
        ctx.fillRect(0, 0, szelesseg, magassag);

        if (raketak.length < 14 && Math.random() < 0.22) {
            ujRaketa();
        }

        ctx.globalCompositeOperation = 'lighter';

        for (let i = raketak.length - 1; i >= 0; i--) {
            const r = raketak[i];
            r.x += r.vx;
            r.y += r.vy;
            r.vy += GRAVITACIO * 1.6;
            ctx.fillStyle = 'hsl(' + r.szin + ', 100%, 72%)';
            ctx.beginPath();
            ctx.arc(r.x, r.y, 2.2, 0, Math.PI * 2);
            ctx.fill();
            if (r.vy >= 0 || r.y <= r.celY) {
                robban(r);
                raketak.splice(i, 1);
            }
        }

        for (let i = szikrak.length - 1; i >= 0; i--) {
            const s = szikrak[i];
            s.x += s.vx;
            s.y += s.vy;
            s.vx *= LEGELLENALLAS;
            s.vy = s.vy * LEGELLENALLAS + GRAVITACIO;
            s.elet -= s.fogyas;
            if (s.elet <= 0 || s.y > magassag + 20) {
                szikrak.splice(i, 1);
                continue;
            }
            ctx.fillStyle = 'hsla(' + s.szin + ', 100%, ' + s.vilagossag + '%, ' + s.elet.toFixed(3) + ')';
            ctx.beginPath();
            ctx.arc(s.x, s.y, s.meret, 0, Math.PI * 2);
            ctx.fill();
        }

        ctx.globalCompositeOperation = 'source-over';
        requestAnimationFrame(lep);
    }

    function bezar() {
        fut = false;
        clearTimeout(vegIdozito);
        document.removeEventListener('keydown', escFigyelo);
        window.removeEventListener('resize', meretez);
        overlay.remove();
    }

    function escFigyelo(e) {
        if (e.key === 'Escape') {
            bezar();
        }
    }

    meretez();
    // indulásnál legyen már mit nézni
    for (let i = 0; i < 6; i++) {
        ujRaketa();
        raketak[raketak.length - 1].y = magassag * (0.4 + Math.random() * 0.6);
    }

    overlay.addEventListener('click', bezar);
    document.addEventListener('keydown', escFigyelo);
    window.addEventListener('resize', meretez);
    vegIdozito = setTimeout(bezar, HOSSZ);
    requestAnimationFrame(lep);
})();
