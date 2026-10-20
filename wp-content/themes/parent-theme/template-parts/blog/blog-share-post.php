<?php
$post_url     = get_the_permalink();
$post_title   = get_the_title();
$facebook_url = add_query_arg(
	array(
		'u' => $post_url,
	),
	'https://www.facebook.com/sharer/sharer.php'
);
$linkedin_url = add_query_arg(
	array(
		'mini'  => 'true',
		'url'   => $post_url,
		'title' => $post_title,
	),
	'https://www.linkedin.com/shareArticle'
);
$twitter_url  = add_query_arg(
	array(
		'text' => $post_url,
	),
	'https://twitter.com/intent/tweet'
);
$email_url    = add_query_arg(
	array(
		'subject' => $post_title,
		'body'    => $post_url,
	),
	'mailto:'
);
?>
<div class="blog-post-content__share">
	<h2 class="blog-post-content__share-title"><?php esc_html_e( 'Don’t forget to share this post!', 'theme' ); ?></h2>
	<div class="blog-post-content__share-wrapper">
		<a href="<?php echo esc_url( $facebook_url ); ?>" class="blog-post-content__share-link">
			<img
				src="<?php echo esc_url( WLD_File::get_assets_url( 'images/share-icons/share-facebook.svg' ) ); ?>"
				class="blog-post-content__share-image"
				alt=""
				aria-hidden="true"
				width="50"
				height="50"
			>
			<span class="screen-reader-text"><?php esc_html_e( 'Facebook', 'theme' ); ?></span>
		</a>
		<a href="<?php echo esc_url( $linkedin_url ); ?>" class="blog-post-content__share-link">
			<img
				src="<?php echo esc_url( WLD_File::get_assets_url( 'images/share-icons/share-linkedin.svg' ) ); ?>"
				class="blog-post-content__share-image"
				alt=""
				aria-hidden="true"
				width="50"
				height="50"
			>
			<span class="screen-reader-text"><?php esc_html_e( 'LinkedIn', 'theme' ); ?></span>
		</a>
		<a href="<?php echo esc_url( $twitter_url ); ?>" class="blog-post-content__share-link">
			<img
				src="<?php echo esc_url( WLD_File::get_assets_url( 'images/share-icons/share-twitter.svg' ) ); ?>"
				class="blog-post-content__share-image"
				alt=""
				aria-hidden="true"
				width="50"
				height="50"
			>
			<span class="screen-reader-text"><?php esc_html_e( 'Twitter', 'theme' ); ?></span>
		</a>
		<a href="<?php echo esc_url( $email_url ); ?>" class="blog-post-content__share-link">
			<img
				src="<?php echo esc_url( WLD_File::get_assets_url( 'images/share-icons/share-gmail.svg' ) ); ?>"
				class="blog-post-content__share-image"
				alt=""
				aria-hidden="true"
				width="50"
				height="50"
			>
			<span class="screen-reader-text"><?php esc_html_e( 'Email', 'theme' ); ?></span>
		</a>
	</div>
</div>
