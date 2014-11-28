// toggle visibility - Thank you Jeffrey and others mentioned in Designing With Web Standards http://zeldman.com/dwws/

function toggle( targetId ){
  if (document.getElementById){
  		target = document.getElementById( targetId );
  			if (target.style.display == "none"){
  				target.style.display = "";
  			} else {
  				target.style.display = "none";
  			}
  	}
}


