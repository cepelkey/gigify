<div id="cpt-messages"></div>
<table class="form-table" role="presentation">
    <tbody>
    <?php 
    foreach( $this->post_types as $pt ):
        $name = strtolower( array_pop( explode( '\\', $pt ) ) );
        echo '<tr>';
        echo '<th scope="row"><label for="'.$name.'">' . ucfirst( $name ) . '</label></th><td>';
        $this->checkboxField( array(
            'label_for' => $name,
            'classes'   => 'ui-toggle gigify-custom-post-type',
            'option_name' => 'gigify_plugin_post_types',
            'nonce' => wp_create_nonce( 'gigify_custom_post_type_nonce' )
        ) );
        echo '</td></tr>';
    endforeach;    
    ?>
    </tbody>
</table>

<div>
    <p><strong>Note:</strong> Future updates to this interface will include the ability to modify the Post Type Name, URL Slug, etc.</p>
</div>

<?php 
    // print_r( $this->post_types );
    // print_r( get_option( 'gigify_plugin_post_types' ) );
?>

