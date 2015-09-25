function fenster_new(file) {
    window.open(file + '.php', file, 'scrollbars=no, rezisable=yes, width=500, height=300');
  }

function fenster_change(datei, param) {
    window.open(datei + '.php?id=' + param, datei, 'scrollbars=no, rezisable=yes, width=500, height=300');
  }
