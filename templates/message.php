<div class="wrapper">
    <div class="adapchat">
        <div class="adapchat-item">
            <div class="adapchat-chater">
                <img src="images/chater-1.jpg" alt="" class="adapchat-photo">
            </div>
            <div class="adapchat-text">
                <div class="subject_matter">
	                <?php echo $message_data['title'];?>
                </div>
                <div class="adapchat-message">
	                <?php echo $message_data['content'];?>
                </div>
                <div class="adapchat-date">
		            <?php echo $message_data['datetime'];?>
                </div>
            </div>
        </div>
    </div>
</div>