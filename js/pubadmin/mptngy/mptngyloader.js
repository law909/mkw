(() => {
    const me = new URL(document.currentScript.src);

    new billyloader(
        'mainframe' + me.searchParams.get('i'),
        me.origin,
        '/pubadmin/login',
        me.searchParams
    );
})();
