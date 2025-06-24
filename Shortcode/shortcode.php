<?php
?>
<script>
    //Wordpress admin ajax controller
    CM_AJAX_URL = '<?php echo esc_url(admin_url('admin-ajax.php', 'relative')); ?>';
</script>
<h2>Check Code</h2>
<form id="code-check-form">
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