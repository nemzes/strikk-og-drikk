      <!-- footer -->
      <footer class="ssod-footer" role="contentinfo">
        <div class="ssod-layout-clamp">
          <p class="copyright">
            &copy; <?php echo esc_html(date('Y')); ?> Copyright <?php bloginfo('name'); ?>
          </p>

          <?php if (is_active_sidebar('ssod-widgets-footer')) : ?>
            <?php dynamic_sidebar('ssod-widgets-footer'); ?>
          <?php endif; ?>
          <?php wp_footer(); ?>
        </div>
      </footer>
    </div>
  </body>

</html>