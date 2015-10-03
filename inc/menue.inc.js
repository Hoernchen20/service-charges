function fenster(file) {
  window.open(file + '.php', file, 'scrollbars=no, rezisable=yes, width=500, height=300');
  }
  
function fenster_param(datei, param) {
  window.open(datei + '.php?param=' + param, datei, 'scrollbars=no, rezisable=yes, width=500, height=300');
  }

function fenster_two_param(datei, param_1, param_2) {
  window.open(datei + '.php?param_1=' + param_1 + '&param_2=' + param_2, datei, 'scrollbars=no, rezisable=yes, width=500, height=300');
}
