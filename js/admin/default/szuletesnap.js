/**
 * Születésnapi tűzijáték: teljes képernyős canvas animáció 30 másodpercig, a képernyő közepén
 * a BOLDOG SZÜLETÉSNAPOT!! felirattal, szintetizált hanggal (nincs hozzá hangfájl).
 * A bejelentkezés utáni első főoldalon a base.tpl indítja ($szuletesnap), utána a szerver
 * eldobja a jelzőt; sysadminként a menü alján lévő gomb is elindítja.
 * Kattintásra és Escape-re korábban is bezárható.
 */
window.szuletesnap = (() => {
    const HOSSZ = 30000;
    // a robbanások alapszínei fokban: minden robbanás egyet kap, a szikrák körülötte szórnak
    const SZINEK = [0, 20, 40, 55, 100, 140, 170, 195, 220, 260, 285, 320, 340];
    const GRAVITACIO = 0.055;
    const LEGELLENALLAS = 0.992;
    const MAX_SZIKRA = 4000;

    function indit() {
        if (document.getElementById('szuletesnap')) {
            return;
        }

        const overlay = document.createElement('div');
        overlay.id = 'szuletesnap';
        const vaszon = document.createElement('canvas');
        vaszon.className = 'szuletesnap-kep';
        const felirat = document.createElement('div');
        felirat.className = 'szuletesnap-felirat';
        felirat.textContent = 'BOLDOG SZÜLETÉSNAPOT!!';
        overlay.appendChild(vaszon);
        overlay.appendChild(felirat);
        document.body.appendChild(overlay);

        const ctx = vaszon.getContext('2d');
        if (!ctx) {
            overlay.remove();
            return;
        }

        let szelesseg = 0;
        let magassag = 0;
        const raketak = [];
        const szikrak = [];
        const veg = Date.now() + HOSSZ;
        let fut = true;
        // háttérfülön a requestAnimationFrame nem fut, tehát a cikluson belüli óra sem jár:
        // ez az időzítő gondoskodik róla, hogy az overlay akkor is eltűnjön
        let vegIdozito = null;

        const hang = hangSzintetizator();

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

        function ujRaketa(indulo) {
            raketak.push({
                x: szelesseg * (0.08 + Math.random() * 0.84),
                y: magassag + 10,
                vx: (Math.random() - 0.5) * 1.2,
                vy: -(magassag * 0.011 + Math.random() * magassag * 0.006),
                szin: SZINEK[Math.floor(Math.random() * SZINEK.length)],
                celY: magassag * (0.08 + Math.random() * 0.45)
            });
            if (!indulo && Math.random() < 0.5) {
                hang.sziszeg();
            }
        }

        /**
         * A robbanás szikrái. A gömb alakú szórás mellé néha gyűrűt is rajzolunk, hogy ne
         * legyen egyforma minden robbanás.
         */
        function robban(r) {
            const gyuru = Math.random() < 0.35;
            const db = gyuru ? 60 + Math.floor(Math.random() * 30) : 70 + Math.floor(Math.random() * 50);
            const alapSebesseg = 1.8 + Math.random() * 2.6;
            // a második szín a szomszédos árnyalatok közül jön, így egy robbanás színben összetartozik
            const masikSzin = (r.szin + 20 + Math.floor(Math.random() * 60)) % 360;
            for (let i = 0; i < db && szikrak.length < MAX_SZIKRA; i++) {
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
                    // hosszú élet: a szikrák végighullanak a képernyőn, mielőtt kialszanak
                    fogyas: 0.0022 + Math.random() * 0.0035,
                    meret: 1 + Math.random() * 1.6
                });
            }
            // a képernyő alján robbanó közelebbinek hat, az szól hangosabban
            if (Math.random() < 0.6) {
                hang.durran(0.55 + (r.y / magassag) * 0.35);
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
            ctx.fillStyle = 'rgba(6, 6, 12, 0.13)';
            ctx.fillRect(0, 0, szelesseg, magassag);

            if (raketak.length < 14 && Math.random() < 0.22) {
                ujRaketa(false);
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
            hang.leallit();
            overlay.remove();
        }

        function escFigyelo(e) {
            if (e.key === 'Escape') {
                bezar();
            }
        }

        meretez();
        for (let i = 0; i < 6; i++) {
            ujRaketa(true);
            raketak[raketak.length - 1].y = magassag * (0.4 + Math.random() * 0.6);
        }

        overlay.addEventListener('click', bezar);
        document.addEventListener('keydown', escFigyelo);
        window.addEventListener('resize', meretez);
        vegIdozito = setTimeout(bezar, HOSSZ);
        requestAnimationFrame(lep);
    }

    /**
     * A hang teljes egészében szintetizált: a felszálló fütty csúszó oszcillátor, a durranás
     * szűrt fehérzaj. A böngésző az első felhasználói kattintásig felfüggesztve tartja a
     * hangot, ezért bejelentkezés után némán indul, és az első kattintásra/billentyűre szólal meg.
     */
    function hangSzintetizator() {
        const Ac = window.AudioContext || window.webkitAudioContext;
        if (!Ac) {
            return {sziszeg: () => {}, durran: () => {}, leallit: () => {}};
        }
        const ac = new Ac();
        const fo = ac.createGain();
        // halkan: a bejelentkezés utáni váratlan hang ne ijesszen meg senkit
        fo.gain.value = 0.15;
        fo.connect(ac.destination);

        const zaj = ac.createBuffer(1, ac.sampleRate, ac.sampleRate);
        const adat = zaj.getChannelData(0);
        for (let i = 0; i < adat.length; i++) {
            adat[i] = Math.random() * 2 - 1;
        }

        function ebreszt() {
            if (ac.state === 'suspended') {
                ac.resume();
            }
        }

        ebreszt();
        document.addEventListener('click', ebreszt);
        document.addEventListener('keydown', ebreszt);

        return {
            sziszeg() {
                if (ac.state !== 'running') {
                    return;
                }
                const t = ac.currentTime;
                const o = ac.createOscillator();
                o.type = 'sine';
                o.frequency.setValueAtTime(280 + Math.random() * 120, t);
                o.frequency.exponentialRampToValueAtTime(1100 + Math.random() * 500, t + 0.7);
                const g = ac.createGain();
                g.gain.setValueAtTime(0.0001, t);
                g.gain.exponentialRampToValueAtTime(0.05, t + 0.06);
                g.gain.exponentialRampToValueAtTime(0.0001, t + 0.72);
                o.connect(g).connect(fo);
                o.start(t);
                o.stop(t + 0.75);
            },
            durran(hangero) {
                if (ac.state !== 'running') {
                    return;
                }
                const t = ac.currentTime;
                const src = ac.createBufferSource();
                src.buffer = zaj;
                src.playbackRate.value = 0.7 + Math.random() * 0.6;
                const szuro = ac.createBiquadFilter();
                szuro.type = 'lowpass';
                szuro.frequency.setValueAtTime(1200, t);
                szuro.frequency.exponentialRampToValueAtTime(110, t + 0.7);
                const g = ac.createGain();
                g.gain.setValueAtTime(0.0001, t);
                g.gain.exponentialRampToValueAtTime(Math.max(0.05, hangero), t + 0.012);
                g.gain.exponentialRampToValueAtTime(0.0001, t + 0.9);
                src.connect(szuro).connect(g).connect(fo);
                src.start(t);
                src.stop(t + 1);
            },
            leallit() {
                document.removeEventListener('click', ebreszt);
                document.removeEventListener('keydown', ebreszt);
                if (ac.state !== 'closed') {
                    ac.close();
                }
            }
        };
    }

    // a sysadmin gombja bárhol lehet a lapon, ezért delegált figyelő
    document.addEventListener('click', e => {
        if (e.target.closest && e.target.closest('.js-szuletesnapteszt')) {
            e.preventDefault();
            indit();
        }
    });

    return {indit: indit};
})();
