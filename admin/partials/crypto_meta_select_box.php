<?php
/**
 * Displays the user interface for the Single Post Meta Manager meta box.
 *
 * This is a partial template that is included by the Single Post Meta Manager
 * Admin class that is used to display all of the information that is related
 * to the post meta data for the given post.
 *
 * @package    SPMM
 */
?>
<div id="single-post-meta-manager">

    <?php 
        
        global $post;
        $values = get_post_custom( $post->ID );
        if( array_key_exists("crypto_symbols", $values) )
            $selected = explode( ',', implode(',', $values['crypto_symbols']) );

        /**
         * Initialize API Handler Class and use all/coinlist method to return
         * all the coins that CryptoCompare has added to the website.
         */
        $compare = new API_Handler();
        $compare->add_queries([]);
        $result = $compare->run('all/coinlist');
    ?>

    <select id="crypto_symbols" name="crypto_symbols[]" class="select-cryptocoin-multiple"  multiple="multiple" style="width: 100%;">
        
        <?php
            /**
             * Loop through all the coins.
             */
            foreach( $result['Data'] as $data ) {
                echo '<option value="'. $data['Symbol'] .'"';
                if( in_array( $data['Symbol'], $selected ) )
                    echo 'selected';
                echo '>'. $data['FullName'] .'</option>';
            } 
        ?>
    </select>

</div><!-- #single-post-meta-manager -->