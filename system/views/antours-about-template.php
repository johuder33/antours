<?php

// get the content saved
$section_id = "antours-about";
$section_group = "editor_about_group";
$editor_id = "editor_aboutus";
$content = get_option($editor_id);

var_dump($content);

?>

<form method='post' action='options.php' enctype='multipart/form-data'>
    <div style="max-width: 600px;">
        <?php
            settings_fields($section_group);
            do_settings_sections($section_id);
        ?>
        <?php wp_editor( $content, $editor_id, $settings = array("textarea_name" => $editor_id, "drag_drop_upload" => false,"media_buttons" => false, "textarea_rows" => 8) ); ?>
        <?php submit_button(); ?>
    </div>
</form>