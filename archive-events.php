<?php
  
  get_header();

  global $dp_options;
	if ( ! $dp_options ) $dp_options = get_design_plus_option();

  $is_day = false;
	
	//
  //$this_date = array( (int) current_time( 'Y' ), (int) current_time( 'm' ), (int) current_time( 'j' ) );
	$this_date[] = 2026;
	$this_date[] = 11;
	$this_date[] = 11;
	
  $get_date = isset( $_GET["calender"] ) ? array_map( 'intval', explode( '-', $_GET["calender"] ) ) : array();

  if( !empty( $get_date ) ){

    // 3桁かつ日付が正式な場合
    if( count( $get_date ) === 3 ){
      if( checkdate( $get_date[1], $get_date[2], $get_date[0] ) ){
        $is_day = true;
        $this_date = $get_date;
      }
    }

    // 2桁かつ日付が正式な場合
    if( count( $get_date ) === 2 ){
      $get_date[] = 1;
      if( checkdate( $get_date[1], $get_date[2], $get_date[0] ) ){
        $this_date = $get_date;
      }
    }

  }

?>
    <div class="p-archive p-archive--events">
<?php

  // 日別アーカイブ
  if( $is_day ){

    do_action( 'child_tcd_events_archive_day', $this_date[0], $this_date[1], $this_date[2] );
  
  // 月別アーカイブ
  }else{

    do_action( 'child_tcd_events_archive_calender', $this_date[0], $this_date[1], $this_date[2] );

  }

?>
    </div>
<?php

  get_footer();
