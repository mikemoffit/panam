<?php get_header(); ?>

<main class="main">
  <div class="container">
    <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div><?php the_excerpt(); ?></div>
        </article>
        <hr />
      <?php endwhile; ?>

      <div>
        <?php the_posts_pagination(); ?>
      </div>
    <?php else : ?>
      <p>No posts found.</p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>