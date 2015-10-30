module.exports = function(grunt) {
    'use strict';

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        
        concat: {
            options: {
                stripBanners: true,
            },
            vendors: {
                src: [
                    'bower_components/jquery/dist/jquery.min.js',
                    'bower_components/nette-forms/src/assets/netteForms.js',
                    'bower_components/nette.ajax.js/nette.ajax.js'<% if (useUnderscoreJs) { %>,
                    'bower_components/underscore/underscore-min.js',
                    'bower_components/underscore.string/dist/underscore.string.min.js'<% } %>,
                    //  'bower_components/bootstrap/js/transition.js',
                    //  'bower_components/bootstrap/js/alert.js',
                    //  'bower_components/bootstrap/js/button.js',
                    //  'bower_components/bootstrap/js/carousel.js',
                    //  'bower_components/bootstrap/js/collapse.js',
                    //  'bower_components/bootstrap/js/dropdown.js',
                    //  'bower_components/bootstrap/js/modal.js',
                    //  'bower_components/bootstrap/js/tooltip.js',
                    //  'bower_components/bootstrap/js/popover.js',
                    //  'bower_components/bootstrap/js/scrollspy.js',
                    //  'bower_components/bootstrap/js/tab.js',
                    //  'bower_components/bootstrap/js/affix.js'
                ],
                dest: 'www/dist/js/vendors.js'
            },
            application: {
                src: ['www/src/js/*.js', 'www/src/js/**/*.js'],
                dest: 'www/dist/js/main.js'
            }
        },

        uglify: {
            options: {
                report: 'gzip',
                preserveComments: false
            },
            vendors: {
                files: {
                    'www/dist/js/vendors.js': 'www/dist/js/vendors.js'
                }
            },
            application: {
                files: {
                    'www/dist/js/main.js': 'www/dist/js/main.js'
                }
            }
        },

        jshint: {
            main: ['www/src/js/*.js', 'www/src/js/**/*.js'],
            options: {
                curly: true,
                eqeqeq: true,
                immed: true,
                latedef: true,
                newcap: true,
                noarg: true,
                sub: true,
                undef: true,
                unused: true,
                boss: true,
                eqnull: true,
                browser: true,
                globals: {
                    jQuery: true
                },
            }
        },

        less: {
            main: {
                files: {
                    "www/dist/style/main.css": "www/src/style/main.less",
                }
            }
        },

        postcss: {
            options: {
                processors: [
                    require('pixrem')(),
                    require('autoprefixer')({ 
                        browsers: ['> 4% in CZ', 'last 5 version']
                    })
                ]
            },
            no_dest: {
                src: 'www/dist/style/main.css' 
            },
        },

        cssmin: {
            style: {
                files: {
                    'www/dist/style/main.css': 'www/dist/style/main.css'
                }
            }
        },

        copy: {
            main: {
                src: 'bower_components/lt-ie-9/lt-ie-9.min.js',
                dest: 'www/dist/js/lt-ie-9.min.js'
            }
        },

        clean: {
            style: 'www/webtemp/*.css',
            script: 'www/webtemp/*.js'
        },

        watch: {
            options: {
                livereload: true,
                interrupt: true,
                debounceDelay: 400
            },
            style: {
                files: ['www/src/style/*.less', 'www/src/style/**/*.less'],
                tasks: ['clean:style', 'less']
            },
            script: {
                files: ['www/src/js/*.js', 'www/src/js/**/*.js'],
                tasks: ['clean:script', 'jshint', 'concat']
            },
        }
    });

    require('jit-grunt')(grunt, {
        concat: 'grunt-contrib-concat'
    });

    grunt.registerTask('default', ['clean', 'copy', 'less', 'jshint', 'concat', 'watch']);
    grunt.registerTask('dist', ['clean', 'copy', 'less', 'postcss', 'cssmin', 'jshint', 'concat', 'uglify']);
};