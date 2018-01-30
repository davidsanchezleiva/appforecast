<?php 
  session_start(); 
 
  $_SESSION['ID'] = session_id();
  $_SESSION['favs'] = array();
  
  //var_dump($_SESSION);
          
  error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Weather App</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>

<body>
  
  <header class="teal">
     <div class="navbar-fixed">
        <nav>
          <div class="nav-wrapper container">
                <a href="#!" class="brand-logo"><i class="material-icons">cloud</i>What's the weather like in: </a>

                <ul class="left hide-on-med-and-down">
                    <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="formlg">
                        <li>
                            <div class="input-field">
                                <input id="c" type="search" required name="c" placeholder="SEARCH...">
                                <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                                <i class="material-icons">close</i>
                            </div>
                        </li>
                        <li>
                           <button class="btn-floating btn waves-effect waves-light yellow darken-4" type="submit" name="action">
                               <i class="material-icons right">send</i>
                           </button>
                        </li>
                    </form>
                </ul>
                
                
          </div>
        </nav>
     </div>
  </header>

  <main class="container">
    <div class="section">

      <div class="row">
          
          <div class="col s12">
            
            <?php 
                $recibido = trim($_GET["c"]);

                if(!empty($recibido)){

                  // Sanitize
                  $recibido_s = strip_tags($recibido);

                  //Escape
                  $city = htmlspecialchars($recibido_s);

                  define( 'API_KEY', 'bd5e378503939ddaee76f12ad7a97608' );

                  //echo $city;

                  $units = "&units=metric";
                  $clang = "&lang=en";
                  $url = "http://api.openweathermap.org/data/2.5/weather?q=" . $city . $units . $clang . "&APPID=" . API_KEY;

                  // The JSON file is requested from the url that is passed as a parameter and is received as a string
                  $data = file_get_contents($url);

                  // The JSON is converted into a PHP object
                  $json = json_decode($data);

                  if($json==null) {
                      $ok = false; ?>
                      
                <?php  
                  }
                  else { 

                      $ok = true;

                      $estacion = $json->name;
                      $lat = $json->coord->lat;
                      $lon = $json->coord->lon;
                      
                      $temp = $json->main->temp;
                      $tempmax = $json->main->temp_max;
                      $tempmin = $json->main->temp_min;
                      $presion = $json->main->pressure;
                      $humedad = $json->main->humidity;

                      $estadoCielo = $json->weather[0]->main;
                      $descripcion = $json->weather[0]->description;
                      $icono = $json->weather[0]->icon;
                      $URLicono = "http://openweathermap.org/img/w/" . $icono . ".png";
                      $nubosidad = $json->clouds->all;
                  }

                } //end if !empty($recibido)
              ?>
              
              <?php
              
                if (!isset($ok)): ?>
              
                 <ul class="collapsible" data-collapsible="expandable">

                  <li>
                    <div class="collapsible-header active"><i class="material-icons">filter_drama</i>The Weather</div>
                    <div class="collapsible-body">
                        <div class="center">
                            <h2 class="text-long-shadow">App Forecast</h2>
                         </div>
                    </div>
                  </li>

                </ul>
                      
             <?php
              
                elseif ($ok): ?>
              
                <ul class="collapsible" data-collapsible="expandable">

                  <li>
                    <div class="collapsible-header active"><i class="material-icons">filter_drama</i><?php echo $estacion; ?></div>
                    <div class="collapsible-body">
                        <div class="row">
                            <div class="col m6 s12">
                                  <div class="card">
                                    <div id="map" class="card-image">
                                      
                                    </div>
                                    <div class="card-content">
                                      <span class="card-title"><?php echo $estacion; ?></span>
                                      <p><?php echo $descripcion; ?></p>
                                    </div>
                                  </div>
                            </div>
                            
                            <div class="col m6 s12">
                         
                                <div id="the-w">
                                    <?php
                                        echo "<img src = '$URLicono' alt = '$descripcion' >";
                                        echo "<p>State of heaven: " .$estadoCielo ."</p>";
                                        echo "<p>Description: ".$descripcion."</p>";
                                        echo "<p>Cloudiness: " . $nubosidad . "%</p>";
                                        echo "<p>Temperature: ".$temp."&deg;C. &nbsp; [ Max: ".$tempmax."&deg;C,&nbsp; Min: ".$tempmin."&deg;C ]</p>";
                                        echo "<p>Pressure: ".$presion. " millibars</p>";
                                        echo "<p>Humidity: ".$humedad. "%</p>";
                                    ?>
                                   
                                    <form action="fav.php" method="post" id="fav-form">
                                      <input type="hidden" name="favcity" value="<?php echo $estacion; ?>"> 
                                      <button class="btn-floating btn-large waves-effect waves-light red tooltipped" data-position="right" data-delay="50" data-tooltip="Add to favorites" type="submit">
                                         <i class="material-icons right">add</i>
                                      </button>
                                    </form>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                  </li>

                  <li>
                    <div class="collapsible-header"><i class="material-icons">place</i>Favorites</div>
                    <div class="collapsible-body">

                        <table id="fav-cities" class="white bordered highlight striped">
                            <thead>
                                <tr>
                                    <th class="sortable asc">ID</th>
                                    <th class="sortable">Name</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <tr>
                                    <td>0</td>
                                    <td><a class="waves-effect waves-light modal-trigger" href="#modal1">Los Angeles</a></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td><a class="waves-effect waves-light modal-trigger" href="#modal1">London</a></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td><a class="waves-effect waves-light modal-trigger" href="#modal1">Pekin</a></td>
                                </tr>
                               
                            </tbody>
                        </table>

                        <!-- Modal Structure -->
                        <div id="modal1" class="modal bottom-sheet">
                          <div class="modal-content">
                            <h4>Weather Info</h4>
                            <p>A bunch of text</p>
                          </div>
                            
                          <div class="modal-footer">
                               <a class="modal-close btn-floating btn-small waves-effect waves-light tooltipped" data-position="left" data-delay="50" data-tooltip="Close"><i class="material-icons right">clear</i></a>
                          </div>
                        </div>
                        
                        <div class="forget">
                            <a class="btn-floating btn-large waves-effect waves-light purple tooltipped" data-position="right" data-delay="50" data-tooltip="Delete all favorites" href="logout.php" rel="nofollow"><i class="material-icons right">delete_forever</i></a>
                        </div>
                        
                    </div>
                  </li>

                </ul>
                      
             <?php
                else:
             ?>
                                    
              <div id="card-alert" class="card small pink lighten-5">
                <div class="card-content pink-text darken-1">
                  <span class="card-title pink-text darken-1">Opss!</span>
                  <h4>The name of the city you entered does not seem correct.</h4>
                  <p style="font-size:1.3rem;">Please try again.</p>
                </div>
              </div>
                
             <?php
                endif;
                unset($ok);
             ?>
        
          </div>
        
      </div> <!-- end of .row -->

    </div>
  </main>

  <footer class="page-footer teal">

    <div class="footer-copyright">
       <div class="container">
           Copyright &copy; 2018. Powered by David Sanchez Leiva.
       </div>
    </div>
	
  </footer>


  <!--  Scripts-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  
  <script>
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: {lat: <?php echo $lat; ?>, lng: <?php echo $lon; ?>}
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvQ5nktZrdXGfnSuhDvGbs4WkfnP6KZ_c&callback=initMap&language=en&region=US"
    async defer>
    </script>

  </body>
</html>
