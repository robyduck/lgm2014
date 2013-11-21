<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Expound
 */
?>

	</div><!-- #main -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="social">
			<dl>
				<dd>
				<a href="https://www.facebook.com/groups/libregraphicsmeeting/">
				<img alt="Follow LGM on Facebook" src="<?=get_template_directory_uri() ?>/images/facebook.png">
				</a>
				<a href="https://twitter.com/LGMLeipzig">
				<img alt="Follow LGM on Twitter" src="<?=get_template_directory_uri() ?>/images/twitter.png">
				</a>
				<a href="https://plus.google.com/u/0/110964138103273662022">
				<img alt="Follow LGM on Google+" src="<?=get_template_directory_uri() ?>/images/gplus.png">
				<a href="http://www.flickr.com/groups/libregfx/pool/with/2493672905/">
				<img alt="Follow LGM on Flickr" src="<?=get_template_directory_uri() ?>/images/flickr.png">
				</a>
				</dd>
			</dl>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>