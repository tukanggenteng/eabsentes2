//fungsi sendiri untuk sidebar aktive
$(function(){
  function stripTrailingSlash(str) {
    if(str.substr(-1) == '/') {
      return str.substr(0, str.length - 1);
    }
    return str;
  }

  var url = window.location.pathname;
  var activePage = stripTrailingSlash(url);

  $('.treeview-menu li a').each(function(){
    var currentPage = stripTrailingSlash($(this).attr('href'));

    if (activePage == currentPage) {
      $(this).parent().parent().parent().addClass('active');
	  $(this).parent().addClass('active');
    }
  });
});

$(document).ready(function(){
  updateInfoTriger();
  var interval = setInterval(updateInfoTriger, 5000);
});

function updateInfoTriger(){
  $.get("http://eabsen.kalselprov.go.id/api/triger", function(data, status){
    var dataTR = data[0];
    var triger ='';
    if(dataTR.status==0) { triger = 'data kosong'; }
    else if (dataTR.status==1) { triger = 'Tambah Pegawai - Admin'; }
    else if (dataTR.status==2) { triger = 'Hapus Pegawai'; }
    else if (dataTR.status==3) { triger = 'Update Software'; }
    else if (dataTR.status==4) { triger = 'Reset Data Mesin'; }
    else { triger = 'Diluar Aturan'; }
    $("#data_triger").fadeOut();
    $("#data_triger").html("<b>"+triger+"</b>");
    $("#data_triger").fadeIn();
  });
}
