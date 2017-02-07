$( document ).ready(function() {

  // iteration count
  var iter = 0;

  // painted pixels
  var clickedPoints = {};

  // refresh table
  // state
  //    last - get last state
  //    next - get next state
  // iteration - iteration number
  // w - width
  // h - height
  function refresh(state, iteration, w, h) {

    $( "#buffer" ).html('');

    // API call for table, add all parameters, now its slow, basic solution by iteration
    // TODO: store all points and send it to API
    $.getJSON( "/gameoflife?state=" + state + "&iteration=" + iteration + "&w=" + w + "&h=" + h + "&extra=" + JSON.stringify(clickedPoints) + "&template=" + $("#templates option:selected").val(), function( data ) {
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
          htmlData += '<button class="pixel_'+i+'-'+j+' '+ pClass +'" data-key="'+i+''+j+'" data-i="'+i+'" data-j="'+j+'"></button>';
        }
        htmlData += '</div>';
      }

      // double buffer
      $('#buffer').html(htmlData);
      $('#table').html($('#buffer').html());

      // draw it
      $('#table button').on('click', function() {
        $(this).toggleClass('dead').toggleClass('live');
        if($(this).hasClass('dead')) {
          delete clickedPoints[$(this).data('key')];
        } else {
          clickedPoints[$(this).data('key')] = [$(this).data('i'), $(this).data('j')];
        }

      });

    });

  }

  // init table
  refresh('last', iter, $('#w').val(), $('#h').val());

  // set next button
  $('#next').click(function() {
    refresh('next', ++iter, $('#w').val(), $('#h').val());
  });

  // set the ticker for play button
  var timer = null;
  //var input = 1;

  function tick() {
      //++input.value;
      refresh('next', ++iter, $('#w').val(), $('#h').val());
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

  // set width, height
  $('#setwh').click(function() {
    refresh('next', ++iter, $('#w').val(), $('#h').val());
  });

  // choose from templates
  $('#templates').change(function() {
    refresh('last', iter, $('#w').val(), $('#h').val());
  });


});
