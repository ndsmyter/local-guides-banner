'use strict';
module.exports = grunt => {

  // Load grunt tasks automatically
  require('load-grunt-tasks')(grunt);

  // Show grunt task time
  require('time-grunt')(grunt);

  grunt.loadNpmTasks('grunt-size-report');

  grunt.loadNpmTasks('grunt-build-number');

  grunt.loadNpmTasks('grunt-json-replace');

  let appConfig = {
    app: 'app',
    dist: 'dist',
    dev: '.tmp',
    pkg: grunt.file.readJSON('package.json'),
    ng_app: 'src',
    ng_dist: 'dist'
  };

  // Project configuration.
  grunt.initConfig({
    appConfig: appConfig,
    buildnumber: {
      package: {}
    },

    'json-replace': {
      options: {
        replace: {
          version: appConfig.pkg.version,
          build: appConfig.pkg.build
        }
      },
      build: {
        files: [{
          src: 'src/assets/version.json',
          dest: 'src/assets/version.json'
        }]
      }
    },
    copy: {
      build: {
        files: [
          {
            expand: true,
            dot: true,
            dest: '<%= appConfig.dist %>',
            src: ['font']
          },
          {
            expand: true,
            dot: true,
            dest: '<%= appConfig.dist %>',
            src: ['*.php']
          },
          {
            expand: true,
            dot: true,
            dest: '<%= appConfig.dist %>/',
            src: ['eye*.png']
          }
        ]
      }
    },
    shell: {
      build: {
        command: 'ng build --base-href local-guides-banner --prod',
        options: {
          execOptions: {
            maxBuffer: 1024 * 1024 // or whatever other large value you want
          }
        }
      }
    },
    size_report: {
      build: {
        files: {
          list: ['dist/*.js', 'dist/*.chunk.js', 'dist/*.bundle.js']
        }
      }
    },
    compress: {
      main: {
        options: {
          archive: '../local-guides-banner_versions/local-guides-banner-<%= appConfig.pkg.version %>.<%= appConfig.pkg.build %>.zip'
        },
        expand: true,
        cwd: 'dist/',
        src: ['**'],
        dest: '/'
      }
    }
  });

  grunt.registerTask('build', [
    'buildnumber',
    'json-replace:build',
    'shell:build',
    'copy:build',
    'size_report:build',
    'compress'
  ]);
}
;
