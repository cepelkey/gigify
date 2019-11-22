<div class="wrap">
    <h1>GIGify Dashboard</h1>
    <?php settings_errors(); ?>

    <h2>Welcome to GIGify</h2>
    <p>This Dashboard will allow you manage the custom features of the plugin. Select the appropriate tab below to get started!</p>

    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-1">Manage Settings</a></li>
        <?php foreach( $this->admin_tabs as $key => $data ): ?>
            <li><a href="#<?php echo str_replace( '_', '-', $key ); ?>"><?php echo $data['label']; ?></a></li>
        <?php endforeach; ?>
    </ul>

	<div class="tab-content">
		<div id="tab-1" class="tab-pane active">

            <form action="options.php" method="post">
                <?php 
                    settings_fields( 'gigify_plugin_settings' );
                    do_settings_sections( 'gigify_plugin' );
                    submit_button();
                ?>
            </form>

            <?php // print_r( get_option( 'gigify_plugin' ) ); ?>
        </div>

        <?php foreach( $this->admin_tabs as $key => $data ): ?>
            <div id="<?php echo str_replace( '_', '-', $key ); ?>" class="tab-pane">
	    		<!-- h3><?php /* echo $data['label']; */ ?></h3 -->
                <h3><?php echo $data['description']; ?></h3>

                <?php 
                    if( file_exists( $data['template'] ) ):
                        require_once( $data['template'] );
                    else:
                        echo '<p>Template File ("' . $data['template'] . '") appears to be missing</p>';
                    endif;
                ?>

    		</div>
        <?php endforeach; ?>
    </div>
</div>
