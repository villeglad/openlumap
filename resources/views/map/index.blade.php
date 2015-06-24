<!DOCTYPE html>
<html>
<head>
  <title>openlumap</title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta charset="utf-8">
  <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
  <script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
  <link rel="stylesheet" href="markercluster/MarkerCluster.css" />
  <script src="markercluster/leaflet.markercluster-src.js"></script>
  <link rel="stylesheet" href="markercluster/MarkerCluster.Default.css" />

  <style>
    body {
      padding: 0;
      margin: 0;
      font-family: verdana;
    }
    html, body
    {
      height: 100%;
    }
    #map-wrapper {
      position: relative;
    }
    #map {
      height: 100%;
    }
    #lista {
      display: none;
      position: absolute;
      right: 0px;
      top: 40px;
      height: 80%;
      background-color: #fff;
      z-index: 9999;
      font-size: 12px;
      opacity: 0.75;
      width: 40%;

    }

    #navi {
      position: absolute;
      top: 0px;
      right: 0px;
      width: 100%;
      background-color: #fff;
      z-index: 9999;
      font-size: 12px;
      opacity: 0.75;
      height: 40px;
    }
    .inline {
      display: inline;
      padding: 0 10px 0 10px;
    }
    #right {
      text-align: right;
      padding: 0 20px 0 20px;
    }
    form {
      padding: 5px 0 5px 0;
    }
    input[type="text"] {
      border: 1px solid #c7c7c7;
      font-size: 16px;
      padding: 5px;
      width: 100%;
    }
    input[type="search"] {
      border: 1px solid #c7c7c7;
      font-size: 16px;
      padding: 5px;
      width: 50%;
  
    }
    input[type="checkbox"] {
      border: 1px solid #c7c7c7;
      font-size: 16px;
      padding: 5px;
      margin: 0 3px 0 20px;

    }
    input[type="submit"] {
      border-style: none;
      font-size: 16px;
      padding: 5px;width: 50px;
      background-color: green;
      color: #fff;
      margin: 0 20px 0 20px;
 
    }
    label {
      font-size:16px;

    }
    #hide_lista {
      display:block;
      position: absolute;
      right: 0px;
      top: 50%;
      background-color: #e7e7e8;
      z-index: 19999;
      font-size: 16px;
      padding: 15px;
      opacity: 0.75;
      border-style: none;

    }
    #scroll_lista {
      overflow-y:scroll;
      height: 80%;
    }
    #tuloslista {
       list-style-type: none;
       line-height: 1.5;
    }
    #loading {
       position:absolute;
       z-index: 19999;
       right: 10px;
      top: 10px;
    }
  </style>
</head>
<body>
  
    <div id="navi">

      <div id="loading">
        	<img id="loader-gif" src="ajax-loader.gif" />
      	</div>
      <div id="right">
      
        <form method="get" action="#">
          <input type="search" name="q" id="q" placeholder="Hae yrityksen nimellä, paikkakunnalla tms" autocomplete="off">
          <input type="submit" name="submit" id="submit" value="Etsi" >
        </form>
        
      </div>

    </div>
   
    <button id="hide_lista"><<</button>
    
    <div id="lista">
      <p><u>Hakutulokset</u></p>
      <p>Löytyi <span id="tulos_count"></span></p>
      <div id="scroll_lista">
        <ul id="tuloslista">
        </ul>
      </div>
    </div>
  
 
  <div id="map">
     

  </div>
  <script>
  
  var map;
  var ajaxRequest;
  var plotlist;
  var plotlayers=[];
  var loadergif = $('#loader-gif').hide();

  function initmap(lat, lng) {
    // set up the map
    map = new L.Map('map', {zoomControl: false});

    L.tileLayer('http://{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png', {
      maxZoom: 18,
      attribution: 'Tiles Courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png"> ',
      subdomains: ['otile1','otile2','otile3','otile4'],
    }).addTo(map);

  }
  var marker = new Array();
 // var markers = L.FeatureGroup();

 var  markers = L.markerClusterGroup({
      singleMarkerMode: true,
      iconCreateFunction: function(cluster) {
        var childCount = cluster.getChildCount();
        var c = ' marker-cluster-';
        if (childCount < 2) {
          c += 'single';
        }
          else if (childCount < 10) {
          c += 'small';
        } else if (childCount < 100) {
          c += 'medium';
        } else {
          c += 'large';
        }

        return new L.DivIcon({ html: '<div><span>' + childCount + '</span></div>', className: 'marker-cluster' + c, iconSize: new L.Point(40, 40) });
      }
    });


 	//markerlistiä tarvitaan kun haetaan haluttu marker kartalta ja zoomataan siihen
	var markerList = [];


  function addMarker(title, ac, lat, lng, id) {
  
    var marker = L.marker([lat, lng]);
   // var marker = new L.CircleMarker([lat,lng], { /* Options */ });
    marker.bindPopup("<b>"+ title + "</b><br /> AC: " + ac );
    marker._myid = id;
   // marker.addTo(map);
    markers.addLayer(marker);
    markerList.push(marker);
  
  }
  /**
  * 1. Tyhjennä data, 2. hae parametrin mukaan data, 3. luo markerit, 4. aseta markerit
  *
  */
  //////////
  function findCustomers(type) {
    loadergif.show();
    //tyhjennä markerit ennen uutta hakua
    markers.clearLayers();
   	markerList = [];

    console.log()
    var query = $('input[name=q]').val();

    if(query === 'null') {
      query = 'empty';
      loadergif.hide();
      event.preventDefault();
      return;
    }


    console.log(query);
    var url = null;
 
    if(type === 'query') url = '/api/find';
    console.log(url);
    $.ajax({
      type: 'GET',
      dataType: "json",
      data: {
        search: query,
      },
      processData: false,
      //crossDomain: true,
      //jsonp: false,
      url: url,
      success: function (responseData, textStatus, jqXHR) {
          $("#tuloslista").html("");
          console.log(responseData.length);
          $("#tulos_count").html(responseData.length);
          $.each(responseData, function(key, value) {
             addMarker(value.name, value.vatcode, value.lat, value.lng);
             $("#tuloslista").append('<li class="customer" id="' + value.vatcode + '"><a href="#">' + value.name + '</a></li>');
             $("#tuloslista").scroll();

          });
        
    	  toggleLista('show');
          loadergif.hide();
         
      },
      error: function (responseData, textStatus, errorThrown) {
          alert('Query failed.');
          loadergif.hide();
      }
    });
     event.preventDefault();
    

    map.addLayer(markers);
    markers.on('clusterclick', function (a) {
      a.layer.zoomToBounds();
    });
    console.log(markerList);

  }
  //////

  function toggleLista(kumpi) {
  	if(kumpi == 'show') {
  		$("#lista").show();
  	}
  	else if(kumpi == 'hide') {
  		$("#lista").hide();
  	}
  	else { $("#lista").toggle(); 
  	}
  	
      if ( $("#lista").css("display") == "block" ) {
        $("#hide_lista").html(">>");
      }
      else $("#hide_lista").html("<<");
  }
 
  $(function() {

    initmap('64.0000', '26.0000');
    new L.Control.Zoom({ position: 'bottomleft' }).addTo(map);
    map.locate({setView: true, maxZoom: 16}); 
    $("#submit").click( function() { findCustomers('query'); });
    $("#hide_lista").click(function() { 
    	toggleLista();
    });
      

    $('#tuloslista').on('click', 'li', function(event){
      var id = $(this).attr('id');
      $.each(markerList, function (i, item) {
        if(item._myid == id){
          markers.zoomToShowLayer(item, function () {
				item.openPopup();
			});
        }       
      })
    });

  });
  </script>
</body>
</html>

