<?php
add_filter(
	'the_init_classes',
	static function ( array $classes ) {
		$classes['WLD_GF_Load_Optimization'] = false;

		return $classes;
	}
);

// Auto-open OM download modal when ?download=om is in the URL
add_action('wp_footer', function() {
	?>
	<script>
	window.addEventListener('load', function() {
		if (new URLSearchParams(window.location.search).has('download')) {
			setTimeout(function() {
				var btn = document.querySelector('a[href="#popup-download-om"]');
				if (btn) btn.click();
			}, 500);
		}
	});
	</script>
	<?php
});