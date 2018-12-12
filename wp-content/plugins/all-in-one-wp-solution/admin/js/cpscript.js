function killCopy(e){
return false
}
function reEnable(){
return true
}
document.onselectstart=new Function ("return false")
if (window.sidebar){
document.onmousedown=killCopy
document.onclick=reEnable
}


var disabled_message = "";
document.oncontextmenu = function() { 
   return false; 
}
document.onmousedown = function md(e) { 
  try { 
     if (event.button==2||event.button==3) {
        if (disabled_message != '')
           alert(disabled_message);
        return false; 
     }
  }  
  catch (e) { 
     if (e.which == 3) return false; 
  } 
}