<?php
//TODO implement settings such as automatic code expiration and associated settings table
?>
<script>
    //Wordpress admin ajax controller
    CM_AJAX_URL = '<?php echo esc_url(admin_url('admin-ajax.php', 'relative')); ?>';
</script>
<h2>Code Administration</h2>
<div class="row">
    <div class="accordion" id="administrationAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                        aria-expanded="true" aria-controls="collapseOne">
                    Product Management
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                 data-bs-parent="#administrationAccordion">
                <div class="accordion-body">
                    <h5>Code Settings</h5>
                    <input type="checkbox" id="cm-autoinactive">
                    <label for="cm-autoinactive">Automatically Deactivate Codes Upon Redemption</label>
                    <br>
                    <input type="checkbox" id="cm-autodelete">
                    <label for="cm-autodelete">Automatically Delete Codes Upon Their Expiration Date</label>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Accordion Item #2
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                 data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Code Management</h5>
                            <form id="cm-code-form">
                                <label class="hidden" for="cm-code">Code</label>
                                <input type="text" id="cm-code" name="cm-code">
                                <label class="hidden" for="cm-post-type">Input Type</label>
                                <input type="number" class="hidden" id="cm-post-type" name="cm-post-type" value="0">
                                <button type="submit" id="cm-code-submit">Submit</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <h5>Document Upload Management</h5>
                            <form id="cm-doc-form" enctype="multipart/form-data">
                                <label class="hidden" for="dp-doc-upload">Document Upload</label>
                                <input type="file" name="dp-doc-upload" id="dp-doc-upload">
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div style="overflow:auto; border: 1px; border-color: black; margin-top: 15px;">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Code</th>
                                    <th scope="col">Message</th>
                                    <th scope="col">Expiration</th>
                                    <th scope="col">Active</th>
                                    <th scope="col">Winner</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                <tbody id="cm-code-table"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>