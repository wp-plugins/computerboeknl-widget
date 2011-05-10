<?php
/*
Plugin Name: Computerboek.nl widget
Plugin URI: http://www.Computerboek.nl
Description: Voeg een widget toe met Computerboeken
Author: Dennis Lutz
Version: 1.0.3
Author URI: http://www.Computerboek.nl/
*/

  /**
   * echo widget
   * 
   * @return string
   */
  function comwidget($options) {
    if( $options['num'] == "" ) $options['num'] = 10;
    $code = "
      <script src=\"http://static.managementboek.nl/widget/affwidget.js\" type=\"text/javascript\"></script>
      <script type=\"text/javascript\">
        //  mogelijke vars
        //  sort = auteur|7d|14d|30d|60d|90d|lm|titel
        //  desc = 0|1
        //  taal = nl|en
        //  rubriek = 'a|b'
        //  trefwoord = 'a,b'
        //  num = X
        //  q = 'zoekoptie'
        //  timer = tijd tussen automatisch
        var options = {
            site:'com',
            affiliate:{$options['affiliate']},
            sort:'{$options['sort']}',
            num:{$options['num']},
            taal:'{$options['taal']}',
            desc:{$options['desc']},
            rubriek:'{$options['rubriek']}',
            trefwoord: '{$options['trefwoord']}',
            q:'{$options['q']}',
            timer:{$options['timer']}
        };
        initMgtBoekWidget(options);
      </script>
    ";
    echo $code;
  }

  /**
   * echo the widget
   */
  function widget_comboek($args)  {
    extract($args);
    $options = get_option("widget_comboek");
    //when there are no options set, use these values
    if (!is_array( $options )) {
      $options = array(
        'titel' => 'Computerboek.nl Boeken',
        'affiliate'=>0,
        'sort'=>'',
        'num'=>10,
        'taal'=>'nl',
        'desc'=>0,
        'rubriek'=>'',
        'trefwoord'=>'',
        'q'=>'',
        'timer'=>5000
      );
    }
    echo $before_widget;
      echo $before_title . $options['titel'] . $after_title;
      echo comwidget( $options );
    echo $after_widget;
  }
  /**
   * add widget and widgetcontrol to wordpress-admin
   */
  function comboek_init() {
    register_sidebar_widget(__('Computerboek boeken'), 'widget_comboek');    
    register_widget_control(   'Computerboek boeken', 'comboek_control', 200, 200 );
  }
  
  /**
   * echo the widgetControl in wordpress-admin
   */
  function comboek_control() {
    $options = get_option("widget_comboek");
    if (!is_array( $options  )) {
      //no options found for widget_comboek, use these defaults in the widgetform
      $options = array(
        'titel' => 'Computerboek.nl boeken',
        'affiliate'=>0,
        'sort'=>'',
        'num'=>10,
        'taal'=>'nl',
        'desc'=>0,
        'rubriek'=>'',
        'trefwoord'=>'',
        'q'=>'',
        'timer'=>5000
      );
    }
    //Set the variables if form is submitted
    if ($_POST['comboekTitel-Submit']) {
      $options['titel']     = htmlspecialchars($_POST['comboekTitel']);
      $options['affiliate'] = (integer)$_POST['comboekAffiliate'];
      $options['sort']      = $_POST['comboekSort'];
      $options['num']       = (integer)$_POST['comboekNum'];
      $options['taal']      = $_POST['comboekTaal'];
      $options['desc']      = $_POST['comboekDesc'];
      $options['rubriek']   = $_POST['comboekRubriek'];
      $options['trefwoord'] = $_POST['comboekTrefwoord'];
      $options['q']         = $_POST['comboekZoek'];
      $options['timer']     = $_POST['comboekTimer'];
      update_option("widget_comboek", $options);
    }
    //write the formfields
  ?>
    <input type="hidden" id="comboekTitel-Submit" name="comboekTitel-Submit" value="1" />
    <table>
    <tr><td><label for="comboekTitel">Titel</label></td><td><input type="text" id="comboekTitel" name="comboekTitel" value="<?=$options['titel']?>" /></td></tr>
    <tr><td><label for="comboekAffiliate">Affiliate</label></td><td><input type="text" id="comboekAffiliate" name="comboekAffiliate" value="<?=$options['affiliate']?>" /></td></tr>
    <!--
    <tr><td><label for="comboekSort">Sort</label></td><td><input type="text" id="comboekSort" name="comboekSort" value="<?=$options['sort']?>" /></td></tr>
    -->
    <tr><td><label for="comboekNum">Aantal</label></td><td><input type="text" id="comboekNum" name="comboekNum" value="<?=$options['num']?>" /></td></tr>
    <tr>
      <td><label for="comboekTaal">Taal</label></td>
      <td>
        <select id="comboekTaal" name="comboekTaal">
         <option value="nl"<?php if($options['taal'] == "nl") echo " selected=\"selected\""; ?>>Nederlands</option>
         <option value="en"<?php if($options['taal'] == "en") echo " selected=\"selected\""; ?>>Engels</option>
       </select>
      </td>
    </tr>
    <tr>
      <td><label for="comboekDesc">Volgorde</label></td>
      <td>
        <select id="comboekDesc" name="comboekDesc">
         <option value="0"<?php if($options['desc'] == "0") echo " selected=\"selected\""; ?>>Oplopend</option>
         <option value="1"<?php if($options['desc'] == "1") echo " selected=\"selected\""; ?>>Aflopend</option>
       </select>
      </td>
    </tr>
    <!--
    <tr>
      <td><label for="comboekRubriek">Rubriek</label></td>
      <td>
        <select id="comboekRubriek" name="comboekRubriek">
         <option value=""<?php if($options['rubriek'] == "") echo " selected=\"selected\""; ?>>- alle rubrieken -</option>
       </select>
      </td>
    </tr>
    -->
    <tr><td><label for="comboekTrefwoord">Trefwoord</label></td><td><input type="text" id="comboekTrefwoord" name="comboekTrefwoord" value="<?=$options['trefwoord']?>" /></td></tr>
    <tr><td><label for="comboekZoek">Zoek</label></td><td><input type="text" id="comboekZoek" name="comboekZoek" value="<?=$options['q']?>" /></td></tr>
    <tr><td><label for="comboekTimer">Refresh(ms)</label></td><td><input type="text" id="comboekTimer" name="comboekTimer" value="<?=$options['timer']?>" /></td></tr>  
  </table>
  <?php    
  };

  add_action("plugins_loaded", "comboek_init");

?>