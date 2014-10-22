module.exports = function(grunt) {
    grunt.loadNpmTasks('grunt-contrib-concat');

    grunt.initConfig({
        concat: {
            lib: {
                src: [
                    'js/main/mkwcansas/jquery-1.11.1.min.js',
                    'js/main/mkwcansas/jquery-migrate-1.2.1.js',
                    'js/main/mkwcansas/jquery.magnific-popup.min.js',
                    'js/main/mkwcansas/jquery.slider.min.js',
                    'js/main/mkwcansas/jquery.royalslider.min.js',
                    'js/main/mkwcansas/jquery.debounce.min.js',
                    'js/main/mkwcansas/bootstrap-transition.js',
                    'js/main/mkwcansas/bootstrap-modal.js',
                    'js/main/mkwcansas/bootstrap-tab.js',
                    'js/main/mkwcansas/bootstrap-typeahead.js',
                    'js/main/mkwcansas/bootstrap-tooltip.js',
                    'js/main/mkwcansas/h5f.js',
                    'js/main/mkwcansas/matt-accordion.js'
                ],
                dest: 'js/main/mkwcansas/mkwbootstrap.js'
            },
            code: {
                src: [
                    'js/main/mkwcansas/mkwmsg.js',
                    'js/main/mkwcansas/mkw.js',
                    'js/main/mkwcansas/checks.js',
                    'js/main/mkwcansas/checkout.js',
                    'js/main/mkwcansas/cart.js',
                    'js/main/mkwcansas/fiok.js',
                    'js/main/mkwcansas/mkwcansas.js'
                ],
                dest: 'js/main/mkwcansas/mkwapp.js'
            },
            css: {
                src: [
                    'themes/main/mkwcansas/bootstrap.min.css',
                    'themes/main/mkwcansas/bootstrap-responsive.min.css',
                    'themes/main/mkwcansas/jquery.slider.min.css',
                    'themes/main/mkwcansas/magnific-popup.css'
                ],
                dest: 'themes/main/mkwcansas/mkw.css'
            }
        },
    });
}