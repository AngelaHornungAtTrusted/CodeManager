<?php
?>
<h2>Check Code</h2>
<form id="code-check-form" action="<?php echo home_url() . '/wp-content/plugins/CodeManager/Shortcode/code.php'; ?>" method="get">
    <label for="code">Code</label>
    <input type="text" id="code" name="code">
    <button type="submit" id="code-form-button">Submit</button>
</form>
<div class="modal" id="code-modal" hidden="hidden">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>test</p>
    </div>
</div>
