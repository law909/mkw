
const me = new URL(document.currentScript.src);

let billyloader = await import(me.origin + "/js/main/darshan/billyloader.js");

new billyloader(
    'rendezvenyregframe' + me.searchParams.get('i'),
    me.origin,
    new URLSearchParams(me.search)
);
