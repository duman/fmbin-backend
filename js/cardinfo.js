function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

$(document).ready(function(){
  var player_id = getParameterByName('player_id');
  $.ajax({
    url : "../informationdata.php?player_id=" + player_id,
    type : "GET",
    success : function(data){
      cname = [];
      pos = [];
      ovr = [];

      for(var i in data) {
        cname.push(data[i].cname);
        pos.push(data[i].pos);
        ovr.push(data[i].ovr);
      }

      $('#chart-container').before('<div id="card-info"><p class="m1-txt1 p-b-36" style="padding-top: 5em; text-align: center;"><b>' + cname[0] + '</b><br><b>Position:</b> ' + pos[0] + ' - <b>Overall:</b> ' + ovr[0] + '</p></div>');
    },
    error : function(data) {
    }
  });
});