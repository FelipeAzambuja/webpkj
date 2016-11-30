function shortcut(shortcut,evento) {
  $(document).bind("keydown",function (ev) {
    cond = "";
    indice = 0;
    codes = shortcut.split('+');
    for (code in codes) {
    	if (indice > 0 ) {
    		cond += " && ";
    	}
    	code = trim(codes[code]);
    	if (code == "ctrl") {
        cond += " ev.ctrlKey ";
    	}else if (code == "shift") {
        cond += " ev.shiftKey ";
    	}else if (code == "alt") {
        cond += " ev.altKey ";
    	}else{
        cond += " ev.keyCode == "+code+" ";
    	}
    	indice ++;
    }
    ret = null;
    eval('ret = '+cond+';');
    if(ret){
      ev.preventDefault();
      evento();
    }
  });
}