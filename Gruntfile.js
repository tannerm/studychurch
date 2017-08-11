module.exports = function( grunt ) {
	'use strict';

	// Load all grunt tasks
	require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

	// Project configuration
	grunt.initConfig( {
		pkg:    grunt.file.readJSON( 'package.json' ),
		concat: {
			options: {
				stripBanners: true,
				banner: '/*! <%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>\n' +
					' * <%= pkg.homepage %>\n' +
					' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
					' * Licensed GPLv2+' +
					' */\n'
			},
			studychurch: {
				src: [
					'assets/js/src/*.js', 'assets/js/src/app/study-edit-globals.js', 'assets/js/src/app/*.js'
				],
				dest: 'assets/js/studychurch.js'
			},
			foundation: {
				src: [
					'assets/js/lib/foundation/foundation.js',
					'assets/js/lib/foundation/foundation*.js'
				],
					dest: 'assets/js/lib/foundation.js'
			}
		},
		jshint: {
			browser: {
				all: [
					'assets/js/src/**/*.js',
					'assets/js/test/**/*.js'
				],
				options: {
					jshintrc: '.jshintrc'
				}
			},
			grunt: {
				all: [
					'Gruntfile.js'
				],
				options: {
					jshintrc: '.jshintrc'
				}
			}   
		},
		uglify: {
			all: {
				files: {
					'assets/js/studychurch.min.js': ['assets/js/studychurch.js'],
					'assets/js/lib/foundation.min.js': ['assets/js/lib/foundation.js', 'assets/js/lib/foundation/foundation.topbar.js']
				},
				options: {
					banner: '/*! <%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>\n' +
						' * <%= pkg.homepage %>\n' +
						' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
						' * Licensed GPLv2+' +
						' */\n',
					mangle: {
						except: ['jQuery']
					}
				}
			}
		},
		test:   {
			files: ['assets/js/test/**/*.js']
		},
		sass:   {
			all: {
				options: {
					compass: true
				},
				files: {
					'assets/css/studychurch.css': 'assets/css/sass/studychurch.scss'
				}
			}
		},
		compass: {                  // Task
			dist: {                   // Target
				options: {              // Target options
					sassDir: 'assets/css/sass',
						cssDir: 'assets/css',
						environment: 'production'
				}
			}
		},
		cssmin: {
			options: {
				banner: '/*! <%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>\n' +
					' * <%= pkg.homepage %>\n' +
					' * Copyright (c) <%= grunt.template.today("yyyy") %>;' +
					' * Licensed GPLv2+' +
					' */\n'
			},
			minify: {
				expand: true,
				cwd: 'assets/css/',
				src: ['studychurch.css'],
				dest: 'assets/css/',
				ext: '.min.css'
			}
		},
		watch:  {
			sass: {
				files: ['assets/css/sass/**/*.scss'],
				tasks: ['sass', 'cssmin'],
				options: {
					debounceDelay: 500
				}
			},
			scripts: {
				files: ['assets/js/**/*.js'],
				tasks: ['jshint', 'concat', 'uglify'],
				options: {
					debounceDelay: 500
				}
			}
		}
	} );

	// Default task.
	grunt.registerTask( 'default', ['jshint', 'concat', 'uglify', 'sass', 'cssmin'] );
	grunt.registerTask( 'scripts', ['jshint', 'concat', 'uglify'] );

	grunt.util.linefeed = '\n';
};