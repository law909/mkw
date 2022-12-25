(() => {
    const me = new URL(document.currentScript.src);

    new billyloader(
        'rendezvenyregframe' + me.searchParams.get('i'),
        me.origin,
        '/rendezveny/reg?',
        me.searchParams
    );
})();
