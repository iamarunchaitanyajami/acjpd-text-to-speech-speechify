<?php
/**
 * Post type query class.
 *
 * @package acjpd-speechify-text-to-speech
 * @sub-package WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\Admin;

/**
 * Post Query.
 */
class PostQuery {
	/**
	 * WordPress query object.
	 *
	 * @var \WP_Query
	 */
	private \WP_Query $query;

	/**
	 * Constructor.
	 *
	 * @param \WP_Query $query Query.
	 */
	public function __construct( \WP_Query $query ) {
		$this->query = $query;
	}

	/**
	 * Initialize the repository.
	 *
	 * @return PostQuery
	 */
	public static function init(): PostQuery {
		return new self( new \WP_Query() );
	}

	/**
	 * Find posts written by the given author.
	 *
	 * @param \WP_User $author Author Object.
	 * @param int      $limit Limit query.
	 *
	 * @return \WP_Post[]
	 */
	public function find_by_author( \WP_User $author, int $limit = 10 ): array {
		return $this->find(
			array(
				'author'         => $author->ID,
				'posts_per_page' => $limit,
			) 
		);
	}

	/**
	 * Find a post using the given post ID.
	 *
	 * @param int $id Object I`d.
	 *
	 * @return \WP_Post|null
	 */
	public function find_by_id( int $id ): ?\WP_Post {
		return $this->find_one( array( 'p' => $id ) );
	}

	/**
	 * Save a post into the repository. Returns the post ID or a WP_Error.
	 *
	 * @param array $post Post array.
	 * @param bool  $do_update Flag to update or exclude update.
	 *
	 * @return int|\WP_Error
	 */
	public function save( array $post, bool $do_update = true ): \WP_Error|int {
		if ( ! empty( $post['ID'] ) ) {
			if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
				remove_all_actions( 'pre_post_update' );
			}

			return $do_update ? wp_update_post( $post ) : (int) $post['ID'];
		}

		return wp_insert_post( $post );
	}

	/**
	 * Find all post objects for the given query.
	 *
	 * @param array $query Query args.
	 *
	 * @return \WP_Post[]
	 */
	public function find( array $query ): array {
		$query = array_merge(
			array(
				'no_found_rows'          => true,
				'update_post_meta_cache' => true,
				'update_post_term_cache' => false,
				'post_type'              => 'any',
			),
			$query 
		);

		return $this->query->query( $query );
	}

	/**
	 * Find a single post object for the given query. Returns null
	 * if it doesn't find one.
	 *
	 * @param array $query Query args.
	 *
	 * @return \WP_Post|null
	 */
	public function find_one( array $query ): ?\WP_Post {
		$query = array_merge(
			$query,
			array(
				'posts_per_page' => 1,
				'post_status'    => 'any',
			) 
		);

		$posts = $this->find( $query );

		return ! empty( $posts[0] ) ? $posts[0] : null;
	}
}
