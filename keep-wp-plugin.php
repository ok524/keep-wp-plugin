<?php
/*
Plugin Name: KEEP WP plugin
Description: Use CLAP API in a widget
Version: 0.0.1
https://rapidapi.com/blog/how-to-call-an-api-from-wordpress/
*/
putenv( 'PANTHEON_INDEX_HOST=192.168.1.29' );
putenv( 'PANTHEON_INDEX_PORT=8011' );
add_filter( 'solr_scheme', function(){ return 'http'; });
define( 'SOLR_PATH', '/solr/gettingstarted/' );



// Register and load the widget
function weather_load_widget() {
    register_widget( 'keep_wp_ajax_api' );
}
add_action( 'widgets_init', 'weather_load_widget' );
// The widget Class
class keep_wp_ajax_api extends WP_Widget {
  function __construct() {
    parent::__construct(
      // Base ID of your widget
      'keep_wp_ajax_api',
      // Widget name will appear in UI
      __('Weather Widget', 'keep_wp_ajax_api_domain'),
      // Widget description
      array( 'description' => __( 'Show Weather Details in a Widget', 'keep_wp_ajax_api_domain' ), )
    );
  }
  // Creating widget front-end view
  public function widget( $args, $instance ) {
    $title = apply_filters( 'widget_title', $instance['title'] );
    //Only show to me during testing - replace the Xs with your IP address if you want to use this
    //if ($_SERVER['REMOTE_ADDR']==="xxx.xxx.xxx.xxx") {
      // before and after widget arguments are defined by themes
      echo $args['before_widget'];
      if ( ! empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];


      $xml = <<<XML
      <label for="clapRContinentesult">Select your Continent</label>
      <select id="clapRContinentesult" name="ProductName">
          <option value="None">None</option>
          <option value="Asia">Asia</option>
          <option value="North America">North America</option>
      </select>
      <input id="clapKeyword" name="Keyword" value="marketing" />
      <button id="clapSubmit" name="Submit">Submit</button>
      <div id="clapResult" ></div>

      <script type="text/javascript">
      </script>
XML;

      echo $xml;

      // This is where you run the code and display the output
      $curl = curl_init();
      $url = "https://api.keep.edu.hk/clap/search?q=marketing&country=Hong%20Kong&min_date=2020-08-19T11:48:08.325Z";
      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_HTTPHEADER => array(
        //   "x-rapidapi-host: climacell-microweather-v1.p.rapidapi.com",
        //   "x-rapidapi-key: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
        // ),
      ));
      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      if ($err) {
        //Only show errors while testing
        //echo "cURL Error #:" . $err;
      } else {
        //The API returns data in JSON format, so first convert that to an array of data objects
        $responseObj = json_decode($response);
        // var_dump($responseObj->response->docs);
        $aobj = $responseObj->response->docs;

        /*
        //Gather the air quality value and timestamp for the first and last elements
        $firstEPAAQI = "<strong>".$responseObj[0]->epa_aqi->value."</strong>";
        $firstTime = date("Y-m-d H:i:s T",strtotime($responseObj[0]->observation_time->value));
        $numResults = count($responseObj);
        $lastEPAAQI = "<strong>".$responseObj[$numResults-1]->epa_aqi->value."</strong>";
        $lastTime = date("Y-m-d H:i:s T",strtotime($responseObj[$numResults-1]->observation_time->value));
        */

        $numResults = count($aobj);
        $firstTime = "hihi: " . strval(count($aobj));

        //This is the content that gets populated into the widget on your site
        foreach ($aobj as $value) {
        echo "".
              "$value->_version_".
              "$value->category".
              "$value->continent , $value->country"
              ;
        }


      }
      echo $args['after_widget'];
    //} // end check for IP address for testing
  } // end public function widget
  // Widget Backend - this controls what you see in the Widget UI
  //  For this example we are just allowing the widget title to be entered
  public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) {
      $title = $instance[ 'title' ];
    } else {
      $title = __( 'New title', 'wpb_widget_domain' );
    }
    // Widget admin form
    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <?php
  }
  // Updating widget - replacing old instances with new
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    return $instance;
  }
} // Class keep_wp_ajax_api ends here

function tbare_wordpress_plugin_demo($atts) {
	$Content = "<style>\r\n";
	$Content .= "h3.demoClass {\r\n";
	$Content .= "color: #26b158;\r\n";
	$Content .= "}\r\n";
	$Content .= "</style>\r\n";
  $Content .= '<h3 class="demoClass">Check it out!</h3>';

  $Content = <<<XML
      <style>
      .collapse {
        max-height: 100px;
        overflow: hidden;
      }
      .collapse.on {
        max-height: none;
      }
      </style>
      <label for="clapRContinentesult">Select your Continent</label>
      <select id="clapRContinentesult" name="ProductName">
          <option value="None">None</option>
          <option value="Asia">Asia</option>
          <option value="North America">North America</option>
      </select>
      <input id="clapKeyword" name="Keyword" value="marketing" />
      <button id="clapSubmit" name="Submit">Submit</button>
      <div id="clapResult" ></div>

      <script type="text/javascript">
      window.G = {}



      function constructCLAPHTML(list) {
        return list.map((o, idx) => {
          return `<li class="clap_item">
            <h3 class="clap_title">
              <a href="`+ o.url +`" target="_blank">`+ o.title +`</a>
            </h3>
            <p class="clap_location">
              <span>`+ o.country +`</span>,
              <span>`+ o.continent +`</span>
            </p>
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample-`+ idx +`" aria-expanded="false" aria-controls="collapseExample">
              Read more
            </button>
            <div class="collapse" id="collapseExample-`+ idx +`">
              <p class="clap_detail">`+ o.content.replace('\\n', '<br>') +`</p>
            </div>
          </li>`
        })
      }

      function callCLAPApi (query) {
        let {q, continent} = query

        let url = `https://proud-leaf-5fa8.pancrea.workers.dev/corsproxy/`
        let clapapi = `https://api.keep.edu.hk/clap/search?q=` + q + `&continent=` + continent + `&min_date=2020-08-19T11:48:08.325Z`
        let data = {
          action: 'weather_load_widget', // The name of the WP action
          value: continent, // The dataValues
          // if you need it, other dataValues as elements of the object data
          url: clapapi,
        }

        var formData = new FormData();
        formData.append('url', clapapi);

        fetch(url, {
          method: 'POST',
          mode: 'cors',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data) {
            if (data.response) {
              if (data.response.docs) {
                console.log(data.response.docs)

                let html = constructCLAPHTML(data.response.docs)

                jQuery('#clapResult').html("<ul>" + html + "</ul>")
              } else {
                console.log(data.response)
              }
            }
          }
        })
        .catch(err => {
          console.log(err)
        })
      }


      jQuery(document).ready(function ($) {
        jQuery('#clapRContinentesult').on('change', function (e) {
          G['continent'] = jQuery('#clapRContinentesult').val()
          G['q'] = jQuery('#clapKeyword').val()
          callCLAPApi(G)
        });

        jQuery('#clapSubmit').on('click', function (e) {
          G['continent'] = jQuery('#clapRContinentesult').val()
          G['q'] = jQuery('#clapKeyword').val()

          callCLAPApi(G)
        });

        jQuery(document).on('click', '[data-toggle="collapse"]', function (e) {
          let target = jQuery(this).attr('data-target')
          console.log(target)

          jQuery(target).toggleClass('on')
        });
      });
      </script>
XML;

  return $Content;
}
add_shortcode('tbare-plugin-demo', 'tbare_wordpress_plugin_demo');

?>
