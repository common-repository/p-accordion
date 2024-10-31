 jQuery(document).ready(function () { 

			jQuery(".accordion").click(function(){
               var xid = this.id;
			   this.classList.toggle("active");
			    ResertButton (xid);
				var xpanel = xid.replace("btn", "pnl");
				var panel = jQuery('#' + xpanel )[0];
				//var panel = document.getElementById(xpanel);
				var id_select = panel.id;
				collapseFAQ(id_select);
 				if (panel.style.maxHeight){
				  panel.style.maxHeight = null;
				} else {
				  panel.style.maxHeight = panel.scrollHeight + "px";
				}   
             }); 
			
			
			function collapseFAQ (idx) {
			  var xpanel = document.getElementsByClassName("panel");
			  var x;
			  for (x = 0; x < xpanel.length; x++) {
				  var xtarget = xpanel[x].id;
				  
				  if (idx != xtarget){
					jQuery("#" + xtarget).css('max-height', '');
				  }	else {		
					var altezza = document.getElementById(xtarget).scrollHeight;
					jQuery("#" + xtarget).css("height", altezza + "px" );
				  }
					  
			    }				
			}
			
			function ResertButton(idbutton){
				var xbottoni = document.getElementsByClassName("accordion");
				var i
				for (i = 0; i < xbottoni.length; i++) {
					if (xbottoni[i].id != idbutton ) {
					jQuery( "#" + xbottoni[i].id ).removeClass("active");						
					} else {
					jQuery( "#" + xbottoni[i].id ).addClass("active");	
					}
				}
			}
								
			
			   jQuery(".accordion-categorie").click(function(){
                   var xid = this.id;
			       this.classList.toggle("active");
				   var panel_categorie = this.nextElementSibling;
				   var id_select_categorie = panel_categorie.id;
				   collapseCategorie(id_select_categorie); 
				   if (panel_categorie.style.display=="block"){
				        jQuery('#' + id_select_categorie).css("display", "none");
				    } else {
				        jQuery('#' + id_select_categorie).css("display", "block");
				    }         
                }); 
			
			
			function collapseCategorie (idx) {
			  var xpanel = document.getElementsByClassName("panel-categories");
			  var x;
			  for (x = 0; x < xpanel.length; x++) {
				  var xtarget = xpanel[x].id;
				  if (idx != xtarget){
					jQuery('#' + xtarget).css("display", "none");
				  }	else {
					jQuery('#' + xtarget).css("display", "display");
					jQuery("#" + xtarget).css("height",  "auto" );
				  }
					  
			    }				
			} 
	
});