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
    },
    error : function(data) {
    }
  });
});