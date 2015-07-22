var modalBox = {
  show: function(title, content, type)
  {
    $('body').addClass('modal-lock');

    var modal		= document.createElement('div'),
        modalIt	= document.createElement('div'),
          modalClose	= document.createElement('div'),
            modalCloseIcon = document.createElement('i'),
          modalTitle	= document.createElement('p'),
          modalBody	= document.createElement('div');

    //modalTitle.setAttribute('class', 'title');
    //modalTitle.appendChild(document.createTextNode(title));

    modalClose.setAttribute('class', 'close');
    modalClose.setAttribute('onclick', 'javascript:modalBox.hide();');
    modalCloseIcon.setAttribute('class', 'fa fa-times');

    modalClose.appendChild(modalCloseIcon);

    modalBody.setAttribute('class', 'body');
    modalBody.appendChild(document.createTextNode('Carregando...'));

    modalIt.setAttribute('class', 'modal');
    modalIt.appendChild(modalClose);
    //modalIt.appendChild(modalTitle);
    modalIt.appendChild(modalBody);

    modal.setAttribute('class', 'modal-overlay');
    modal.appendChild(modalIt);

    $('body').append(modal);


    $('.modal-overlay').fadeIn();
    if(type == 3) {
    $.get(content, function(res) {
      console.log(res);
      $('.modal-overlay>.modal>.body').html(res);
      $('.modal-overlay>.modal').css({width: ($('.modal-overlay>.modal>.body').outerWidth()  + 10) + 'px'});
    });
    }
    else if(type == 2) {
      var theFrame = document.createElement('iframe');
      theFrame.setAttribute('src', content);
      theFrame.setAttribute('frameBorder', '0');
      theFrame.setAttribute('width', '800px');
      theFrame.setAttribute('height', '500px');
      theFrame.setAttribute('style', 'margin: auto');
      $('.modal-overlay>.modal>.body').html('');
      $('.modal-overlay>.modal>.body').append(theFrame);
      $('.modal-overlay>.modal').css({width: ($('.modal-overlay>.modal>.body').outerWidth() + 10) + 'px'});
    }
    else if(type==1){
      $('.modal-overlay>.modal>.body').html($(content));
      //$('.modal-overlay>.modal').css({width: '600px', height: '300px'});
    }		else {
      $('.modal-overlay>.modal>.body').html(content);
      $('.modal-overlay>.modal').css({width: '600px', height: '300px'});
    }
    $(document).click(function (event) {
      cur_target = event.target;

        if ($(cur_target).closest($('.modal-overlay')).length && !$(cur_target).closest($('.modal')).length) {
          modalBox.hide();
        }
    });
  },
  hide: function(){
    $('.modal-overlay').fadeOut('fast', function(){
      $(this).remove();
      $('body').removeClass('modal-lock');
    });
  }
};
function parseDate(d) {
    var monthNames = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "August", "Sep", "Oct", "Nov", "Dec" ],
        d2 = d.getDate() +'/'+monthNames[d.getMonth()]+'/'+d.getFullYear() +' '+d.getHours() +':'+d.getMinutes();
    return d2;
}
var getIPInfo = function(e){
  var ip = $(e).attr('data-ip'),
  $box = $('<div class="box" id="ipinfo"></div>');
  $box.append('<center><i class="fa fa-spinner fa-spin"></i> loading...</center>');
  modalBox.show('epa', $box, 1);
  //
  $.getJSON('https://www.telize.com/geoip/'+ip+'?callback=?',
    function(res)
    {
      var $dv  = $('<div class="table-wrapper">'),
          $tbl = $('<table class="alt" style="margin-bottom:0px; padding-bottom:0px">'),
          $tbd = $('<tbody style="margin-bottom:0px; padding-bottom:0px">');
      $tbd.append('<tr><td class="v"><b>Country</b></td><td style="width:70%">'+res.country+'</td></tr>');
      $tbd.append('<tr><td class="v"><b>Region</b></td><td style="width:70%">'+res.region+'</td></tr>');
      $tbd.append('<tr><td class="v"><b>City</b></td><td style="width:70%">'+res.city+'</td></tr>');
      $tbd.append('<tr><td class="v"><b>Timezone</b></td><td style="width:70%">'+res.timezone+'</td></tr>');
      $tbl.append($tbd);
      $dv.append($tbl);
      $('#ipinfo').html("<h4>Information about <b>"+res.ip+"</b></h4>");
      $('#ipinfo').append($dv);
      $('#ipinfo').append("<h5>Approximate location</h5>");
      $('#ipinfo').append('<iframe width="500" height="450" frameborder="0" style="border:0; margin:auto;display:block; " src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAcRlf2Y6MBIH9iymF-_HYalUXhiZUox4Q&q='+res.latitude+','+res.longitude+'" allowfullscreen></iframe>');

    }
  );
};
(function($) {
  logs.forEach(
    function(e)
    {
      $tr = $('<tr>'),
      dt = new Date(e.date * 1000);

      $tr.append('<td> ' + parseDate(dt) + '</td>');
      $tr.append('<td> <a class="ip-info" href="#" onclick="getIPInfo(this);return false;" data-ip="' + e.ipAddress + '">' + e.ipAddress + '</a> </td>');
      $tr.append('<td> ' + e.userAgent + '</td>');
      $tr.append('<td> ' + e.refURL + '</td>');
      $('#logs').append($tr);
    }
  );
})(jQuery);
