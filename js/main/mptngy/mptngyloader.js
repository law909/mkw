(() => {
    const me = new URL(document.currentScript.src);

    new billyloader(
        'mainframe' + me.searchParams.get('i'),
        me.origin,
        '/login',
        me.searchParams
    );
})();
