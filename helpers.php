<?php

/* String manipulation helpers */
function fridx_titleize( $title ) {
    $title_lower = strtolower( $title );
    $titleized_title = ucwords( $title_lower );

    return $titleized_title;
}

// TODO: this does not work in all cases...
function fridx_sentenceize( $str ) {
    $str_lower = strtolower( $str );
    $str_ary = explode( '.', $str_lower );

    $upper_str_ary = array();
    foreach ( $str_ary as $sentence ) {
        $upper_sentence = ucfirst( $sentence );
        $upper_str_ary[] = $upper_sentence;
    }

    $str_sentenceized = implode( '.', $upper_str_ary );
    return $str_sentenceized;
}

function fridx_capitalize_query( $str ) {
    global $str_upcase;

    $str_ary = explode( ' ', $str );
    $ary_upcase = array();

    foreach ( $str_ary as $slice ) {
        $upslice = ucfirst( $slice );
        $ary_upcase[] = $upslice;
    }

    $str_upcase = implode( '%20', $ary_upcase );
    return $str_upcase;
} 

function fridx_to_slug( $str ) {
    global $str_slugged;

    $str_ary = explode( ' ', $str );
    $str_slugged = implode( '-', $str_ary );
    return $str_slugged;
}

function fridx_from_slug( $str ) {
    global $str_deslugged;

    $str_ary = explode( '-', $str );
    $str_deslugged = implode( '%20', $str_ary );
    return $str_deslugged;
}

function fridx_parse_listing_id( $str ) {
    global $listing_id;

    $str_ary = explode( '-', $str );
    $listing_id = $str_ary[0];
    return $listing_id;
}

function fridx_titleize_from_slug( $str ) {
    global $str_deslugged;

    $str_ary = explode( '-', $str );
    $str_deslugged = implode( ' ', $str_ary );
    return $str_deslugged;
}

/* Makes a select box using a name and an options key=>value array */
function fridx_select_widget( $name, $options_ary, $fname, $fid, $instance ) {
    $select = '<select name="' . $fname . '" id="' . $fid . '" style="width:100%;">';
    foreach ( $options_ary as $key => $value ) {
        if ( isset ( $instance[ $name ] ) && $instance[ $name ] == $value ) {
            $select .= '<option value="' . $value . '" selected="selected"' . '>' . $key . '</option>';
        } else {
            $select .= '<option value="' . $value . '">' . $key . '</option>';
        }
    }
    $select .= '</select>';
    return $select;
}

/* Displays a disclaimer based on a feed */
function fridx_disclaimer() {
    $disclaimer = '<p>';
    // TODO: different disclaimers for different RETS
    $disclaimer .= "The information being provided by CARETS (CLAW, CRISNet MLS, DAMLS, CRMLS, i-Tech MLS, and/or VCRDS) is for the visitor's personal, non-commercial use and may not be used for any purpose other than to identify prospective properties visitor may be interested in purchasing.
        Any information relating to a property referenced on this web site comes from the Internet Data Exchange (IDX) program of CARETS. This web site may reference real estate listing(s) held by a brokerage firm other than the broker and/or agent who owns this web site.
        The accuracy of all information, regardless of source, including but not limited to square footages and lot sizes, is deemed reliable but not guaranteed and should be personally verified through personal inspection by and/or with the appropriate professionals. The data contained herein is copyrighted by CARETS, CLAW, CRISNet MLS, DAMLS, CRMLS, i-Tech MLS and/or VCRDS and is protected by all applicable copyright laws. Any dissemination of this information is in violation of copyright laws and is strictly prohibited.
        CARETS, California Real Estate Technology Services, is a consolidated MLS property listing data feed comprised of CLAW (Combined LA/Westside MLS), CRISNet MLS (Southland Regional AOR), DAMLS (Desert Area MLS), CRMLS (California Regional MLS), i-Tech MLS (Glendale AOR/Pasadena Foothills AOR) and VCRDS (Ventura County Regional Data Share)."; 
    $disclaimer .= '</p>';

    return $disclaimer;
}

?>
