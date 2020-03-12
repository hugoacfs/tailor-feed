<script data-main="js/main" src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.6/require.min.js" crossorigin="anonymous"></script>
<script>
    requirejs.config({
        baseUrl: 'js',
        paths: {
            jquery: ['https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min'],
            fontawesome: ['https://kit.fontawesome.com/2a953cdc29'],
            bootstrap: ['https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min', 'dependencies/bootstrap/bootstrap.min'],
            popper: ['https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min', 'dependencies/popper.min'],
            toggle: '../vendor/bootstrap4-toggle-master/js/bootstrap4-toggle',
            lodash: '../vendor/lodash/lodash',
            sortable: '../vendor/sortable/js/sortable.min',
            methods: 'methods'
        },
        shim: {
            // "lodash": "jquery",
            'bootstrap': {
                'deps': ['jquery']
            },
            "toggle": ["jquery"],
            "sortable": ["bootstrap"]
        },
        map: {
            '*': {
                'popper.js': 'popper'
            }
        }
    });
</script>
<!-- https://stackoverflow.com/questions/46004087/issue-loading-popperjs-and-bootstrap-via-requirejs-even-after-using-recommended -->