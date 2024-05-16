<?php

?>

<div class-"wrap">
    <h2>WP Affiliate Disclaimer</h2>
    <form action="options.php" method="POST">
        <?php
            settings_fields( 'wpaffiliatedisclaimer' );
            do_settings_sections( 'wp_affiliate_disclaimer' );
            submit_button();
        ?>
    </form>
</div>