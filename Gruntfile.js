'use strict';

module.exports = function (grunt) {

    require('load-grunt-tasks')(grunt);

    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        concat: {

            css: {
                src: [
                    'src/vendor/bootstrap/css/bootstrap.css',
                    'src/vendor/website-connect/css/main.css'
                ],
                dest: 'src/css/framework-bundle.css'
            },

            js: {
                src: [
                    'src/vendor/jquery/jquery-1.11.1.js',
                    'src/vendor/bootstrap/js/bootstrap.js',
                    'src/vendor/website-connect/js/WebsiteConnect.js',
                    'src/vendor/website-connect/js/WebsiteConnect.Observable.js',
                    'src/framework/Widget/AjaxLoader/View/js/WebsiteConnect.AjaxLoader.js',
                    'src/framework/Widget/AjaxSearchForm/View/js/WebsiteConnect.AjaxSearchForm.js',
                    'src/framework/Widget/AjaxTable/View/js/WebsiteConnect.AjaxTable.js'
                ],
                dest: 'src/js/framework-bundle.js'
            }
        },

        uglify: {
            dist: {
                files: {
                    'src/js/framework-bundle.min.js': 'src/js/framework-bundle.js'
                }
            }
        },
        cssmin: {
            target: {
                files: {
                    'src/css/framework-bundle.min.css': 'src/css/framework-bundle.css'
                }
            }
        }

    });

    grunt.registerTask('default', ['concat:css', 'concat:js', 'uglify', 'cssmin']);

};
