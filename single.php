<?php get_header(); ?>

<main class="main">
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <article <?php post_class(); ?>>
        <h1><?php the_title(); ?></h1>
        <div><?php the_content(); ?></div>
      </article>
    <?php endwhile; ?>
  </div>
</main>

<?php get_footer(); ?>