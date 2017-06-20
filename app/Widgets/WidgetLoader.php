<?php
namespace BookmarkManager\Widgets;

use BookmarkManager\BookmarkManager;

class WidgetLoader extends BookmarkManager {

  function __construct() {

    // Register widgets
    add_action( 'widgets_init', function() {
      register_widget( new My_Widget_Class() );
    });

  }

}

