<?php include "application/templates/includes/header.php"; ?>

<h1><?php echo htmlspecialchars( $results['post']->title )?></h1>
<div><?php echo htmlspecialchars( $results['post']->summary )?></div>
<div><?php echo $results['post']->content?></div>
<p class="pub-date">Published on <?php echo date('j F Y', $results['post']->created_date)?></p>

<p><a href="./">Return to Homepage</a></p>
<?php include "application/templates/includes/footer.php" ?>