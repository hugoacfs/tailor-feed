<script data-main="js/main" src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.6/require.min.js" crossorigin="anonymous"></script>
<script>
    requirejs.config({
        baseUrl: 'js',
        paths: {
            jquery: ['https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min'],
            fontawesome: ['https://kit.fontawesome.com/2a953cdc29'],
            bootstrap: 'dependencies/bootstrap/bootstrap.bundle.min',
            toggle: '../vendor/bootstrap4-toggle-master/js/bootstrap4-toggle',
            lodash: '../vendor/lodash/lodash',
            sortable: '../vendor/sortable/js/sortable.min',
            methods: 'methods'
        },
        shim: {
            "toggle": ["jquery"],
            "sortable": ["bootstrap"]
        }
    });
</script>