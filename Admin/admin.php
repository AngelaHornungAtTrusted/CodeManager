<?php

?>
<h2>Code Administration</h2>
<div class="row">
    <div class="col-md-6">
        <h5>Document Category Management</h5>
	    <?php //todo get rid of hard coded address, did when tired, dynamic call tries to include mamp in the address (composer autoloader?) ?>
        <form id="cm-code-form" action="http://localhost/devplugin/wp-content/plugins/CodeManager/Admin/code.php" method="post">
            <label class="hidden" for="cm-code">Category Name</label>
            <input type="text" id="cm-code" name="cm-code">
            <label class="hidden" for="cm-post-type">Input Type</label>
            <input type="number" class="hidden" id="cm-post-type" name="cm-post-type" value="0"></input>
            <button type="submit" id="cm-code-submit">Submit</button>
        </form>
    </div>
    <div class="col-md-6">
        <h5>Document Upload Management</h5>
	    <?php //todo get rid of hard coded address, did when tired, dynamic call tries to include mamp in the address (composer auto loader?) ?>
        <form id="cm-doc-form" data-loader="http://localhost/devplugin/wp-content/plugins/Docport/Admin/upload.php" method="post" enctype="multipart/form-data">
            <label class="hidden" for="dp-doc-upload">Document Upload</label>
            <input type="file" name="dp-doc-upload" id="dp-doc-upload">
            <button type="submit" id="dp-doc-submit">Submit</button>
        </form>
    </div>
</div>
<div class="row">
    <div style="height: 200px; overflow:auto; border: 1px; border-color: black; margin-top: 15px;">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Code</th>
                <th scope="col">Message</th>
                <th scope="col">Active</th>
                <th scope="col">Winner</th>
                <!--<th scope="col">Manage</th>-->
            </tr>
            </thead>
			<?php //todo get rid of hard coded address ?>
            <tbody id="cm-code-table" data-loader="http://localhost/devplugin/wp-content/plugins/CodeManager/Admin/code.php"></tbody>
        </table>
    </div>
</div>