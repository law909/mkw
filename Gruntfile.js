module.exports = function (grunt) {
    const sass = require('sass');

    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-less');

    grunt.initConfig({
        concat: {
            mkwlib: {
                src: [
                    'js/main/mkwcansas/jquery-1.11.1.min.js',
                    'js/main/mkwcansas/mkwerrorlog.js',
                    'js/main/mkwcansas/jquery-migrate-1.2.1.js',
                    'js/main/mkwcansas/jquery.magnific-popup.min.js',
                    'js/main/mkwcansas/jquery.slider.min.js',
                    'js/main/mkwcansas/jquery.royalslider.min.js',
                    'js/main/mkwcansas/jquery.debounce.min.js',
                    'js/main/mkwcansas/jquery.inputmask.min.js',
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
            mkwcode: {
                src: [
                    'js/main/mkwcansas/mkwmsg.js',
                    'js/main/mkwcansas/mkw.js',
                    'js/main/mkwcansas/checks.js',
                    'js/main/mkwcansas/checkout.js',
                    'js/main/mkwcansas/cart.js',
                    'js/main/mkwcansas/fiok.js',
                    'js/main/mkwcansas/termekertekeles.js',
                    'js/main/mkwcansas/mkwcansas.js'
                ],
                dest: 'js/main/mkwcansas/mkwapp.js'
            },
            mkwcss: {
                src: [
                    'themes/main/mkwcansas/bootstrap.min.css',
                    'themes/main/mkwcansas/bootstrap-responsive.min.css',
                    'themes/main/mkwcansas/jquery.slider.min.css',
                    'themes/main/mkwcansas/magnific-popup.css'
                ],
                dest: 'themes/main/mkwcansas/mkw.css'
            },
            mugenracelib: {
                src: [
                    'js/main/mugenrace/jquery-1.11.1.min.js',
                    'js/main/mugenrace/mgrerrorlog.js',
                    'js/main/mugenrace/jquery-migrate-1.2.1.js',
                    'js/main/mugenrace/jquery.magnific-popup.min.js',
                    'js/main/mugenrace/jquery.slider.min.js',
                    'js/main/mugenrace/jquery.royalslider.min.js',
                    'js/main/mugenrace/jquery.debounce.min.js',
                    'js/main/mugenrace/jquery.magnify.js',
                    'js/main/mugenrace/jquery.magnify-mobile.js',
                    'js/main/mugenrace/bootstrap-transition.js',
                    'js/main/mugenrace/bootstrap-modal.js',
                    'js/main/mugenrace/bootstrap-tab.js',
                    'js/main/mugenrace/bootstrap-typeahead.js',
                    'js/main/mugenrace/bootstrap-tooltip.js',
                    'js/main/mugenrace/h5f.js',
                    'js/main/mugenrace/matt-accordion.js'
                ],
                dest: 'js/main/mugenrace/mgrbootstrap.js'
            },
            mugenracecode: {
                src: [
                    'js/main/mugenrace/mgrmsg.js',
                    'js/main/mugenrace/mgr.js',
                    'js/main/mugenrace/checks.js',
                    'js/main/mugenrace/checkout.js',
                    'js/main/mugenrace/cart.js',
                    'js/main/mugenrace/fiok.js',
                    'js/main/mugenrace/mugenrace.js'
                ],
                dest: 'js/main/mugenrace/mgrapp.js'
            },
            mugenrace2026code: {
                src: [
                    'js/main/mugenrace2026/mgrmsg.js',
                    'js/main/mugenrace2026/mgr.js',
                    'js/main/mugenrace2026/checks.js',
                    'js/main/mugenrace2026/checkout.js',
                    'js/main/mugenrace2026/cart.js',
                    'js/main/mugenrace2026/fiok.js',
                    'js/main/mugenrace2026/mugenrace.js'
                ],
                dest: 'js/main/mugenrace2026/mgrapp.js'
            },
            mugenracecss: {
                src: [
                    'themes/main/mugenrace/bootstrap.min.css',
                    'themes/main/mugenrace/bootstrap-responsive.min.css',
                    'themes/main/mugenrace/jquery.slider.min.css',
                    'themes/main/mugenrace/magnific-popup.css'
                ],
                dest: 'themes/main/mugenrace/mgr.css'
            }

        },

        less: {
            mkw: {
                files: {
                    'themes/main/mkwcansas/style.css': 'themes/main/mkwcansas/style.less'
                }
            },
            mugenrace: {
                files: {
                    'themes/main/mugenrace/style.css': 'themes/main/mugenrace/style.less'
                }
            }
        },
        sass: {
            options: {
                implementation: sass
            },
            mugenrace: {
                options: {
                    sourceMap: false
                },
                files: {
                    'themes/main/mugenrace2026/style-2.css': 'themes/main/mugenrace2026/scss/style-2.scss'
                }
            }
        }
    });
    grunt.registerTask('default', ['sass', 'less', 'concat']);
}