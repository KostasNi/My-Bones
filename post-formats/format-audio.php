

              <article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article" itemscope itemtype="http://schema.org/AudioObject">

                <header class="article-header">

                  <h1 class="entry-title single-title" itemprop="name"><?php the_title(); ?></h1>

                  <p class="byline entry-meta vcard">
                    <?php printf( __( 'Posted', 'bonestheme' ).' %1$s %2$s',
                       /* the time the post was published */
                       '<time class="updated entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>',
                       /* the author of the post */
                       '<span class="by">'.__( 'by', 'bonestheme' ).'</span> <span class="entry-author author" itemprop="author" itemscope itemptype="http://schema.org/Person">' . get_the_author_link( get_the_author_meta( 'ID' ) ) . '</span>'
                    ); ?>
                  </p>

                </header> <?php // end article header ?>

                <section class="entry-content cf" itemprop="description">
                  <?php
                    the_content();
					
                    wp_link_pages( array(
                      'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'bonestheme' ) . '</span>',
                      'after'       => '</div>',
                      'link_before' => '<span>',
                      'link_after'  => '</span>',
                    ) );
                  ?>
                </section> <?php // end article section ?>

                <footer class="article-footer">
                  <?php the_tags( '<p class="tags"><span class="tags-title">' . __( 'Tags:', 'bonestheme' ) . '</span> ', ', ', '</p>' ); ?>

                </footer> <?php // end article footer ?>

	              <?php
	              // If comments are open or we have at least one comment, load up the comment template
	              if ( comments_open() || get_comments_number() ) :
		              comments_template();
	              endif;
	              ?>

              </article> <?php // end article ?>
