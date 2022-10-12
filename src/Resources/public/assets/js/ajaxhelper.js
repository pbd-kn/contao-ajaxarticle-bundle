function cursorwait () {
  jQuery('html, body, button').addClass("cursor-wait"); 
}
function cursordefault () {
  jQuery('html, body, button').removeClass("cursor-wait"); 
}

function AjaxGetArticle(article) {
  console.log ("AjaxGetArticle Artikel: " + article);
  cursorwait();
  jQuery.ajax(
    {
	  type: "GET",
	  url:  "/ajax_article/get_article/"+article,
      error: function (xhr, ajaxOptions, thrownError) {
               cursordefault();
               alert("status: " + xhr.status);
               alert("error: " + thrownError);
               console.log("ajaxstatus " + xhr.status + " thrownError " + thrownError);
               console.log("xhr.getAllResponseHeaders() " + xhr.getAllResponseHeaders());
             },
      success: function(result) {
        cursordefault();                    // cursor zurueck
        div=createPopupDiv('modal');        // return div content container
debugger;
        div.innerHTML=result['artikel'];    
        var modal = document.getElementById("modal"); 
        var span = document.getElementsByClassName("close")[0];
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
          modal.style.display = "none";
          modal.remove(); // Entfernt das div Element mit der id 'div-02'
        }
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target.id != 'modal_content') {
            modal.style.display = "none";
            modal.remove(); // Entfernt das div Element mit der id 'div-02'
          }
        }
      },
    }
  );
};
function createPopupDiv (id) {  
  if (id === undefined) {
    id = "id_div";
  } 
  if (jQuery('#'+id).length > 0) {
    return jQuery('#' +id + '_content');
  }

  // Create the span
  var span = document.createElement('span');
  span.className="close";
  span.innerHTML="&times";          // close
  var spanDiv = document.createElement('div');
  spanDiv.id = id + '_close'; 
  spanDiv.appendChild(span);
  var contenDiv = document.createElement('div');
  contenDiv.id = id + '_content' ;
  // The variable iDiv is still good... Just append to it.
  var iDiv = document.createElement('div');
  iDiv.id = id 
  iDiv.appendChild(spanDiv);
  iDiv.appendChild(contenDiv);

  // Then append the whole thing onto the body
  //document.getElementsByTagName('body')[0].appendChild(iDiv);
  //var wr =document.getElementById('wrapper');
  document.getElementById('container').appendChild(iDiv);
  return contenDiv;
};

