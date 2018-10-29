<?php include "application/templates/includes/header.php" ?>

      <h1>Article Archive</h1>

      <ul id="headlines" class="archive">

<?php foreach ( $results['posts'] as $post ) { ?>
        <li>
          <h2>
            <span class="pubDate"><?php echo date('j F Y', $post->created_date)?></span><a href=".?action=viewArticle&amp;articleId=<?php echo $post->id?>"><?php echo htmlspecialchars( $post->title )?></a>
          </h2>
          <p class="summary"><?php echo htmlspecialchars( $post->summary )?></p>
        </li>
<?php } ?>

      </ul>

      <p><?php echo $results['totalRows']?> post<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>

      <p><a href="./">Return to Homepage</a></p>

<?php include "application/templates/includes/footer.php" ?>