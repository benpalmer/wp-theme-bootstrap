'use strict';
module.exports = function(grunt) {

    // load all grunt tasks matching the `grunt-*` pattern
    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        // watch for changes and trigger sass, jshint, uglify and livereload
        watch: {
            styles: {
                // Which files to watch (all .less files recursively in the less directory)
                files: ['less/**/*.less'],
                tasks: ['less'],
                options: {
                    nospawn: true
                }
            },
            js: {
                files: '<%= jshint.all %>',
                tasks: ['jshint', 'uglify']
            }
        },

        // sass
        less: {
          development: {
            options: {
                compress: true,
                yuicompress: false,
                optimization: 2
            },
            files: {
              // target.css file: source.less file
              "css/style.css": "less/style.less"
            }
          }
        },

        // javascript linting with jshint
        jshint: {
            options: {
                jshintrc: '.jshintrc',
                "force": true
            },
            all: [
                'Gruntfile.js',
                'js/source/**/*.js'
            ]
        },
 
        // uglify to concat, minify, and make source maps
        uglify: {
            options: {
                banner: '/*! <%= pkg.author %> - v<%= pkg.version %> - ' + '<%= grunt.template.today("yyyy-mm-dd") %> */',
                mangle: true,
                dropConsole: true
            },
            dist: {
                options: {
                    sourceMap: 'js/map/source-map.js'
                },
                files: {
                    'js/main.min.js': [
                        'js/source/main.js',
                    ]
                }
            }
        }

    });

    // rename tasks
    grunt.renameTask('rsync', 'deploy');

    // register task
    grunt.registerTask('default', ['uglify', 'watch']);

};