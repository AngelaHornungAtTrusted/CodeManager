<?php
?>
<h2>Check Code</h2>
<!--http://localhost/devplugin/wp-content/plugins/CodeManager/Shortcode/code.php-->
<!-- echo home_url($wp->request) . '/wp-content/plugins/CodeManager/Shortcode/code.php'; -->
<?php //todo figure out dynamic calling from this page and then update admin.php with that method ?>
<form id="code-check-form" action="<?php echo home_url() . '/wp-content/plugins/CodeManager/Shortcode/code.php'; ?>" method="get">
    <label for="code">Code</label>
    <input type="text" id="code" name="code">
    <button type="submit" id="code-form-button">Submit</button>
</form>
