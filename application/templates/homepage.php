<?php include "application/templates/includes/header.php" ?>

<ul id="headlines">
<?php foreach ( $results['posts'] as $post ) { ?>
	<li>
		<h2>
			<span class="pub-date"><?php echo date('j F', $post->created_date)?></span><a href=".?action=viewPost&amp;postID=<?php echo $post->id?>"><?php echo htmlspecialchars( $post->title )?></a>
		</h2>
		<p class="summary"><?php echo htmlspecialchars( $post->summary )?></p>
	</li>
<?php } ?>
</ul>

<p><a href="./?action=archive">Article Archive</a></p>
<?php include "application/templates/includes/footer.php" ?>