/*
 ***********************************************************/
/**
 * @name          : Mac Doc Photogallery.
 * @version	      : 2.3
 * @package       : apptha
 * @subpackage    : mac-doc-photogallery
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 2 or later; see LICENSE.txt
 * @abstract      : The core file of calling Mac Photo Gallery.
 * @Creation Date : June 20 2011
 * @Modified Date : September 30 2011
 * */

/*
 ***********************************************************/

//For Showing the list of Album in adjacent
function macAlbum(pages)
{

if(pages == '')
{
    pages = 1;
}

if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('bind_macAlbum').innerHTML = xmlhttp.responseText
        imagePreview();
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalblist.php?pages='+pages,true);
xmlhttp.send();
 }

 //showing the name edit form
function albumNameform(macAlbum_id)
{
     
 if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('showAlbumedit_'+macAlbum_id).style.display="block";
        document.getElementById('showAlbumedit_'+macAlbum_id).innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macAlbumname_id='+macAlbum_id,true);
xmlhttp.send();

}

//Update the album name
function updAlbname(macAlbum_id)
{

var macAlbum_id = macAlbum_id;
var macAlbum_name   = document.getElementById('macedit_name_'+macAlbum_id).value;
var macAlbum_desc   = document.getElementById('macAlbum_desc_'+macAlbum_id).value;

  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
      document.getElementById('albName_'+macAlbum_id).innerHTML = macAlbum_name;
      document.getElementById('displayAlbum_'+macAlbum_id).innerHTML = macAlbum_desc;
      document.getElementById('showAlbumedit_'+macAlbum_id).style.display="none";
    }
  }
 
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macAlbum_id='+macAlbum_id+'&macAlbum_name='+macAlbum_name+'&macAlbum_desc='+macAlbum_desc,true);
xmlhttp.send();
}

//For Changing Mac Album Page id

function albumPageid(macAlbum_id)
{
  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('showPageedit_'+macAlbum_id).innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macAlbumpage_id='+macAlbum_id,true);
xmlhttp.send();
}

//For Updating Mac Album Page id

function updPageid(macAlbum_id)
{

var macAlbum_pageid = document.getElementById('macedit_pageid_'+macAlbum_id).value;

 if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
      document.getElementById('macPageid_'+macAlbum_id).innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macAlbum_pageid='+macAlbum_pageid+'&macAlbum_id='+macAlbum_id,true);
xmlhttp.send();
}

//For changing the Album Status
 function macAlbum_status(status,macAlbum_id)
 {
   
  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
       document.getElementById('status_bind_'+macAlbum_id).innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?status='+status+'&albid='+macAlbum_id,true);
xmlhttp.send();
 }

// View Album description Update
function albumtoggle(macAlbum_id) {
	var albumele = document.getElementById("albumtoggleText_"+macAlbum_id);
	var albumtext = document.getElementById("albumdisplayText_"+macAlbum_id);
	if(albumele.style.display == "block") {
    		albumele.style.display = "none";
		albumtext.innerHTML = "Edit";
  	}
	else {
		albumele.style.display = "block";
		albumtext.innerHTML = "hide";
	}
}

function macAlbumdesc_updt(macAlbum_id)
{
var macAlbum_desc = document.getElementById('macAlbum_desc_'+macAlbum_id).value;
    if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
      document.getElementById('displayAlbum_'+macAlbum_id).innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macAlbum_desc='+macAlbum_desc+'&macAlbum_id='+macAlbum_id,true);
xmlhttp.send();
}
function macdeleteAlbum(macAlbum_id)
{
  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        window.location = self.location;
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macphtajax.php?macdelAlbum='+macAlbum_id,true);
xmlhttp.send();
}


//For Showing the Multiple Photo Upload in Adjacent
function macPhotos(numFilesQueued,albid)
{
    var show_pht = document.getElementById('bind_value').value;
    document.getElementById('bind_value').value = Number(show_pht)+Number(numFilesQueued);
    var rst_pht = document.getElementById('bind_value').value;
   
   if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('bind_macPhotos').innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macPhotos.php?queue='+rst_pht+'&albid='+albid,true);
xmlhttp.send();
 }
//For Delete from the upload images
function macdelAjax(macPhoto_id)
{
     if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('remve_macPhotos_'+macPhoto_id).innerHTML += xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macDelid='+macPhoto_id,true);
xmlhttp.send();
}
//For showing the name,description box adjacent to photos

 function maceditPhotos(rst)
{
  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('edit_macPhotos').innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macphtajax.php?macEdit='+rst,true);
xmlhttp.send();
 }

//Update Name and Description

function upd_disphoto(queue,albid)
{

    for(i=1;i<=queue;i++)
    {
     var macedit_phtid = document.getElementById("macedit_id_"+i).value;
     var macedit_name  = document.getElementById("macedit_name_"+i).value;
     var macedit_desc  = document.getElementById("macedit_desc_"+i).value;
dragdr = jQuery.noConflict();
dragdr.ajax({
    method:"GET",
       url: site_url+'/wp-content/plugins/mac-dock-gallery/macphtajax.php',
       data : "macedit_phtid="+macedit_phtid+"&macedit_name="+macedit_name+"&macedit_desc="+macedit_desc,
       asynchronous:false,
       error: function(html){
            },
      success: function(html){
    	  
          window.location = site_url+'/wp-admin/admin.php?page=macPhotos&action=viewPhotos&albid='+albid;
           }
       });
  }
alert('Updated sucessfully');
}

//Mac Individual Photo Delete
 function macdeletePhoto(macdeleteId)
 {
var agree=confirm("Are you sure you want to delete ?");
if (agree)
{
  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('photo_delete_'+macdeleteId).style.visibility = 'hidden';
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macphtajax.php?macdeleteId='+macdeleteId,true);
xmlhttp.send();
 }
 }


 function macdesEdit(macPhoto_id)
 {

  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('edit_macDesc').innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macphtajax.php?macphotoDesc_id='+macPhoto_id,true);
xmlhttp.send();
 }

function photosNameform(macPhotos_id) {
 
  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('showPhotosedit_'+macPhotos_id).innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macPhotoname_id='+macPhotos_id,true);
xmlhttp.send();
}
 //Update the Photo name
function updPhotoname(macPhotos_id)
{

var macPhoto_name = document.getElementById('macPhoto_name_'+macPhotos_id).value;

 if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
       
      document.getElementById('macPhotos_'+macPhotos_id).innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macPhoto_name='+macPhoto_name+'&macPhotos_id='+macPhotos_id,true);
xmlhttp.send();
}
// View Photo description Update
function phototoggle(macPhoto_id) {
	var ele = document.getElementById("toggleText"+macPhoto_id);
     	var text = document.getElementById("displayText_"+macPhoto_id);
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		text.innerHTML = "Edit";
  	}
	else {
		ele.style.display = "block";
		
	}
}

function macdesc_updt(macPhoto_id)
{
var macPhoto_desc = document.getElementById('macPhoto_desc_'+macPhoto_id).value;
var ele = document.getElementById("toggleText"+macPhoto_id);
    if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
      document.getElementById('display_txt_'+macPhoto_id).innerHTML = xmlhttp.responseText
      ele.style.display = "none";
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macphtajax.php?macPhoto_desc='+macPhoto_desc+'&macPhoto_id='+macPhoto_id,true);

xmlhttp.send();
}
function macAlbcover_status(addCover,albumId,macPhoto_id)
{
    if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
      window.location = self.location;
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?albumCover='+addCover+'&albumId='+albumId+'&macCovered_id='+macPhoto_id,true);
xmlhttp.send();
}

//Photos Status Changing
function macPhoto_status(status,macPhoto_id)
{
  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
       document.getElementById('photoStatus_bind_'+macPhoto_id).innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?status='+status+'&macPhoto_id='+macPhoto_id,true);
xmlhttp.send();
}
function fbcomments(pid,title,siteurl) {


   if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
   httpxml=new XMLHttpRequest();
//alert(xmlhttp);
  }
httpxml.onreadystatechange=function()
  {
  if (httpxml.readyState==4 && httpxml.status==200)
    {
  
    var fbComments = httpxml.responseText;
    document.getElementById("facebook").innerHTML = fbComments;
     getfacebook();
     return false;
    }
  }
httpxml.open("GET",site_url+"/wp-content/plugins/"+mac_folder+"/macfbcomment.php?pid="+pid+'&phtName='+title+'&site_url='+siteurl+'&appId='+appId,true);
httpxml.send();
}
function CancelAlbum(macAlbum_id)
{
    document.getElementById('showAlbumedit_'+macAlbum_id).style.display="none";
}