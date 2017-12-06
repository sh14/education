<?php
/**
 * Created by PhpStorm.
 * User: Повелитель
 * Date: 19.11.2017
 * Time: 20:31
 */?>
<script src="js/userpic.js"></script>
<script src="js/jquery-3.2.1.min.js"></script>


<div id="userpic" class="userpic">
    <div class="js-preview userpic__preview"></div>
    <div class="btn btn-success js-fileapi-wrapper">
        <div class="js-browse">
            <span class="btn-txt">Choose</span>
            <input type="file" name="filedata">
        </div>
        <div class="js-upload" style="display: none;">
            <div class="progress progress-success"><div class="js-progress bar"></div></div>
            <span class="btn-txt">Uploading</span>
        </div>
    </div>
</div>

