$( document ).ready(function() {

  function refresh(state) {

    $( "#buffer" ).html('');

    $.getJSON( "/gameoflife?state=" + state, function( data ) {
      var htmlData = '';
      var pClass = '';
      for(i=0; i<data['result'].length; i++) {
        htmlData += '<div class="tablerow">';
        for(j=0; j<data['result'][i].length; j++) {
          if(1 == data['result'][i][j]) {
            pClass = 'live';
          } else {
            pClass = 'dead';
          }
          htmlData += '<button class="pixel'+i+j+' '+ pClass +'"></button>';
        }
        htmlData += '</div>';
      }

      $('#buffer').html(htmlData);
      $('#table').html($('#buffer').html());

    });

  }

  refresh('last');

  $('#next').click(function() {
    refresh('next');
  });

  var timer = null;
  var input = 1;

  function tick() {
      ++input.value;
      refresh('next');
      start();
  };

  function start() {
      timer = setTimeout(tick, 500);
  };

  function stop() {
      clearTimeout(timer);
  };

  $('#play').bind("click", start);
  $('#stop').bind("click", stop);

  $('#setwh').click(function() {

    $.post( "/gameoflife", { w: $('#w').val(), h: $('#h').val() })
      .done(function( data ) {
        refresh('next');
      });

  });

});
