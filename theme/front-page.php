<?php get_header(); ?>

	<main role="main" aria-label="Content">
    <section
      class="festival-hero"
      style="background-image: url(<?php echo esc_url( get_template_directory_uri() ); ?>/img/sample-festival-splash.jpg)"
    >
      <div class="layout-clamp">
        <div class="festival-hero__content">
          <h1>Stavanger Strikkefestival</h1>
        </div>
      </div>
    </section>

    <section>
			<?php get_template_part( 'loop' ); ?>

			<?php get_template_part( 'pagination' ); ?>

		</section>
	</main>

<?php get_footer(); ?>
