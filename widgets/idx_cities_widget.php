<?php
    /**
    * The areas widget links to communities as selected by enduser
    */

    class FridxAreasWidget extends WP_Widget {

        /* Register widget */
        function __construct() {
            parent::__construct(
                'fridx_areas_widget', // Base ID
                'IDX Areas', // NAME
                array( 'description' => __( 'Make links to communities you serve', 'text_domain' ), ) // Args
            );
        }

        /* Actual widget */
        public function widget( $args, $instance ) {
            extract( $args );
            $title = apply_filters( 'widget_title', $instance[ 'title' ] );

            echo $before_widget;
            if ( ! empty( $title )  )
            echo $before_title . $title . $after_title;
        ?>
        <ul id="fridx-areas">
            <?php if ( isset ( $instance[ 'neighborhoods' ] ) ) { 
                    foreach ( $instance[ 'neighborhoods' ] as $neighborhood ) {  ?>
                        <li><a href="<?php echo get_bloginfo( 'wpurl' ); ?>/idx/search/?city=<?php echo ucwords( $neighborhood ); ?>"><?php echo ucwords( $neighborhood ); ?></a></li>
            <?php } } ?>
        </ul>

        <?php
            echo $after_widget;
        }

        /* Update Form*/
        // TODO: sanitize the title without destroying the areas array

        /* Widget Form */
        public function form( $instance ) {
            if ( isset ( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            } else {
                $title = __( 'My Neighborhoods', 'text_domain' );
            }
            if ( isset ( $instance[ 'neighborhoods' ] ) ) {
                $neighborhoods = $instance[ 'neighborhoods' ];
            } else {
                $neighborhoods = array();
            }

            // get cities
            $jsonurl = "http://fallriveridx.heroku.com";
            $jsonurl .= "/api/autocomplete/cities.json";
            $json_output = fridx_get_json_object( $jsonurl );

        ?>
        <p class="fridx-areas-widget">
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title); ?>" />
        </p>
        <h3>Areas</h3>
        <select data-placeholder="Choose as many areas as you like..." multiple class="chzn-select" style="width:220px;" id="<?php echo $this->get_field_id( 'neighborhoods' ); ?>" name="<?php echo $this->get_field_name( 'neighborhoods' ); ?>[]" ><?php echo esc_attr( $neighborhoods ); ?>
            <option></option>
            <?php foreach ( $neighborhoods as $neighborhood ) { ?>
            <option value="<?php echo $neighborhood ?>" selected="selected"><?php echo $neighborhood ?></option>
            <?php } ?>
            <?php foreach ( $json_output as $city ) { ?>
            <option value="<?php echo $city ?>"><?php echo $city ?></option>
            <?php } ?>
        </select>

        <?php
        }
    }
?>
