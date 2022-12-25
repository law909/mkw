
import billyloader from "./billyloader.js";

const me = new URL(document.currentScript.src);
new billyloader(
    'rendezvenyregframe' + me.searchParams.get('i'),
    me.origin,
    new URLSearchParams(me.search)
);