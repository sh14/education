<?php
delete_message();
?>
<article class="article" id="">
    <div class="header">
        <h2><?php echo $row['post_title']; ?></h2>
        <div class="meta"><?php echo $row['post_date']; ?></div>
    </div>
	<div class="content"><?php echo $row['post_content']; ?></div>
	<?php if (authorization() == true) { ?><div>
            <form action="?message=delete" method="post">
                <button id="<?php echo $row['ID']; ?>" type="submit" class="btn btn-primary pull-right"
                        name="delete_button" value="<?php echo $row['ID']; ?>">Удалить</button>
            </form>
                <button id="<?php echo $row['ID']; ?>" type="button" class="btn btn-primary pull-right margin-right"
                        name="edit_button" value="<?php echo $row['ID']; ?>">Редактировать</button>
        </div>
	<?php } ?>
</article>
