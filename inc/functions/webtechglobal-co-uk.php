<?php

if ( is_admin() ) {
	new Sample_WP_List();
}

class Sample_WP_List {
	/**
	 * Description
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'network_admin_menu', array( $this, 'add_sample_list_table_mene' ) );
		add_action( 'admin_menu', array( $this, 'add_sample_list_table_mene' ) );
	}

	/**
	 * Menu item will allow us to load the page to display the table
	 */
	public function add_sample_list_table_mene() {
		add_menu_page( 'This MultiSite Plugins', 'This MultiSite Plugins', 'manage_options', 'csc-list-table2.php', array( $this, 'list_table_page' ), 'dashicons-info', 9 );
		// add_submenu_page( 'connect-to-multisite.php', ' MultiSite Plugins', ' MultiSite Plugins', 'manage_options', 'csc-list-table2.php', array( $this, 'list_table_page' ) );
	}

	/**
	 * Display the list table page
	 *
	 * @return Void
	 */
	public function list_table_page() {
		$smpl_list_table = new WORDPRESSPLUGINMULTITOOL_WPTable_Example();
		$smpl_list_table->prepare_items();
		?>

		<div class="wrap">


			<div id="icon-users" class="icon32"></div>
			<form method="post">
				<input type="hidden" name="page" value="my_list_test" />
			</form>

			<form method="post">
				<input type="hidden" name="logs" value="<?php echo $_REQUEST['log']; ?>" />
				<?php $smpl_list_table->display(); ?>
			</form>

		</div>
		<?php
	}
}

// WP_List_Table is not loaded automatically so we need to load it in our application
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Example of WP List Table class for display on this view only.
 * http://www.webtechglobal.co.uk/wordpress/example-extend-wordpress-wp_list_table-class/
 *
 * @author Ryan R. Bayne
 * @package WebTechGlobal WordPress Plugins
 * @version 2.0
 */
class WORDPRESSPLUGINMULTITOOL_WPTable_Example extends WP_List_Table {

	private $bulkid = 'moviesbulk';// ID for checkboxes.
	private $perPage_option = 'items_per_page';// Limits number of records.

	/**
	 * WTG approach to managing actions is a little quicker to configure.
	 *
	 * @var mixed
	 */
	private $full_actions = array(
		'dump' => array(
			'label' => 'Dump',
			'rowaction' => true,
			'capability' => 'developer',
		),
		'delete' => array(
			'label' => 'Delete',
			'rowaction' => true,
			'capability' => 'developer',
		),
	);

	// Column Display Capability Requirements $colcap_
	private $colcap_rating = 'developer';

	/**
	 * Class constructor
	 *
	 * @version 1.0
	 */
	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Movie', 'multitool' ), // singular name of the listed records
			'plural' => __( 'Movies', 'multitool' ), // plural name of the listed records
			'ajax' => true,// should this table support ajax?
		] );

	}

	/**
	 * Prepare the items for the table to process.
	 *
	 * @return Void
	 * @version 1.0
	 */
	public function prepare_items1() {
		// Process bulk action.
		$this->process_bulk_action();

		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();
		$data = $this->table_data();
		usort( $data, array( &$this, 'sort_data' ) );

		$perPage = $this->get_items_per_page( $this->perPage_option, 5 );
		$currentPage = $this->get_pagenum();
		$totalItems = count( $data );
		$this->set_pagination_args( array(
			'total_items' => $totalItems,
			'per_page' => $perPage,
		) );
		$data = array_slice( $data,(($currentPage -1) * $perPage),$perPage );
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items = $data;
	}
	public function prepare_items() {
		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$data = $this->table_data();
		usort( $data, array( &$this, 'sort_data' ) );

		$perPage = 20;
		$currentPage = $this->get_pagenum();
		$totalItems = count( $data );

		$this->set_pagination_args( array(
			'total_items' => $totalItems,
			'per_page'    => $perPage,
		) );

		$data = array_slice( $data, ( ( $currentPage -1 ) * $perPage ),$perPage );

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items = $data;
	}

	/**
	 * Override the parent columns method. Defines the
	 * columns to use in your listing table.
	 *
	 * @version 1.0
	 * @return Array
	 */
	public function get_columns() {
		// Add all columns to this array.
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'year' => 'Year',
			'director' => 'Director',
			'rating' => 'Rating',
		);

		// Columns not permitted to be seen by current user will be removed here.
		foreach ( $columns as $the_column => $the_label ) {

			// Check for class private variable holding capabilitiy.
			$one = 'colcap_' . $the_column;
			eval( '$cap = $this->$one;' );

			if ( $cap != null && ! current_user_can( $cap ) ) {
				unset( $columns[ $the_column ] );
			}
		}

		return $columns;
	}

	/**
	 * Define which columns are hidden.
	 *
	 * @version 1.0
	 * @return Array
	 */
	public function get_hidden_columns() {
		return array();
	}

	/**
	 * Define the sortable columns.
	 *
	 * @version 1.0
	 * @return Array
	 */
	public function get_sortable_columns() {
		return array(
			'title' => array( 'title', false ),
		);
	}

	/**
	 * Get the table data.
	 *
	 * In example we use an array but a live table would query data or use
	 * a cache. Keep in mind a cache must be destroyed if changes to the original
	 * source or bulk actions on the table.
	 *
	 * @version 1.0
	 * @return Array
	 */
	private function table_data() {
		$data = array();

		$data[] = array(
					'id'          => 1,
					'title'       => 'The Shawshank Redemption',
					'description' => 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.',
					'year'        => '1994',
					'director'    => 'Frank Darabont',
					'rating'      => '9.3',
					);

		$data[] = array(
					'id'          => 2,
					'title'       => 'The Godfather',
					'description' => 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.',
					'year'        => '1972',
					'director'    => 'Francis Ford Coppola',
					'rating'      => '9.2',
					);

		$data[] = array(
					'id'          => 3,
					'title'       => 'The Godfather: Part II',
					'description' => 'The early life and career of Vito Corleone in 1920s New York is portrayed while his son, Michael, expands and tightens his grip on his crime syndicate stretching from Lake Tahoe, Nevada to pre-revolution 1958 Cuba.',
					'year'        => '1974',
					'director'    => 'Francis Ford Coppola',
					'rating'      => '9.0',
					);

		$data[] = array(
					'id'          => 4,
					'title'       => 'Pulp Fiction',
					'description' => 'The lives of two mob hit men, a boxer, a gangster\'s wife, and a pair of diner bandits intertwine in four tales of violence and redemption.',
					'year'        => '1994',
					'director'    => 'Quentin Tarantino',
					'rating'      => '9.0',
					);

		$data[] = array(
					'id'          => 5,
					'title'       => 'The Good, the Bad and the Ugly',
					'description' => 'A bounty hunting scam joins two men in an uneasy alliance against a third in a race to find a fortune in gold buried in a remote cemetery.',
					'year'        => '1966',
					'director'    => 'Sergio Leone',
					'rating'      => '9.0',
					);

		$data[] = array(
					'id'          => 6,
					'title'       => 'The Dark Knight',
					'description' => 'When Batman, Gordon and Harvey Dent launch an assault on the mob, they let the clown out of the box, the Joker, bent on turning Gotham on itself and bringing any heroes down to his level.',
					'year'        => '2008',
					'director'    => 'Christopher Nolan',
					'rating'      => '9.0',
					);

		$data[] = array(
					'id'          => 7,
					'title'       => '12 Angry Men',
					'description' => 'A dissenting juror in a murder trial slowly manages to convince the others that the case is not as obviously clear as it seemed in court.',
					'year'        => '1957',
					'director'    => 'Sidney Lumet',
					'rating'      => '8.9',
					);

		$data[] = array(
					'id'          => 8,
					'title'       => 'Schindler\'s List',
					'description' => 'In Poland during World War II, Oskar Schindler gradually becomes concerned for his Jewish workforce after witnessing their persecution by the Nazis.',
					'year'        => '1993',
					'director'    => 'Steven Spielberg',
					'rating'      => '8.9',
					);

		$data[] = array(
					'id'          => 9,
					'title'       => 'The Lord of the Rings: The Return of the King',
					'description' => 'Gandalf and Aragorn lead the World of Men against Sauron\'s army to draw his gaze from Frodo and Sam as they approach Mount Doom with the One Ring.',
					'year'        => '2003',
					'director'    => 'Peter Jackson',
					'rating'      => '8.9',
					);

		$data[] = array(
					'id'          => 10,
					'title'       => 'Fight Club',
					'description' => 'An insomniac office worker looking for a way to change his life crosses paths with a devil-may-care soap maker and they form an underground fight club that evolves into something much, much more...',
					'year'        => '1999',
					'director'    => 'David Fincher',
					'rating'      => '8.8',
					);

		return $data;
	}

	/**
	 * Display message when no items are available.
	 *
	 * @version 1.0
	 */
	public function no_items() {
		_e( 'No movies avaliable.', 'sp' );
	}

	/**
	 * Define what data to show on each column of the table.
	 *
	 * @version 1.0
	 *
	 * @param Array  $item Data
	 * @param String $column_name - Current column name
	 *
	 * @return Mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'cb':
			case 'id':
			case 'title':
			case 'description':
			case 'year':
			case 'director':
			case 'rating':
			return $item[ $column_name ];
			default:
			return print_r( $item, true );
		}
	}

	/**
	 * Allows you to sort the data by the variables set in the $_GET
	 *
	 * @version 1.0
	 *
	 * @return Mixed
	 */
	private function sort_data( $a, $b ) {
		// Set defaults
		$orderby = 'title';
		$order = 'asc';
		// If orderby is set, use this as the sort column
		if ( ! empty( $_GET['orderby'] ) ) {
			$orderby = $_GET['orderby'];
		}
		// If order is set use this as the order
		if ( ! empty( $_GET['order'] ) ) {
			$order = $_GET['order'];
		}
		$result = strcmp( $a[ $orderby ], $b[ $orderby ] );
		if ( $order === 'asc' ) {
			return $result;
		}
		return -$result;
	}

	/**
	 * Setup bulk actions. Customized by WebTechGlobal to use a more global
	 * array which is needed when applying capabilities per action throughout
	 * the class.
	 *
	 * @version 1.0
	 */
	function get_bulk_actions( $return = 'normal' ) {
		// Bulk actions not permitted by current user will be removed here.
		foreach ( $this->full_actions as $the_action => $a ) {

			// Check for class private variable holding capabilitiy.
			$one = 'bulkcap_' . $the_action;
			eval( '$cap = $this->$one;' );

			if ( $cap != null && ! current_user_can( $a['capability'] ) ) {
				unset( $actions[ $the_action ] );
			}
		}

		// Build the standard actions array.
		foreach ( $this->full_actions as $the_action => $a ) {

			$actions[ $the_action ] = $a['label'];
		}

		// Return the standard array needed by WP core approach.
		return $actions;
	}

	/**
	 * Checkboxes for bulk actions.
	 *
	 * @version 1.0
	 *
	 * @param mixed $item
	 */
	function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="moviesbulk[]" value="%s" />', $item['id'] );
	}

	/**
	 * ID Column Method - often the first column and the one with row actions.
	 *
	 * @version 1.0
	 * @param array $item an array of DB data
	 * @return string
	 */
	function column_id( $item ) {

		$title = '<strong>' . $item['id'] . '</strong>';

		return $title . $this->row_actions( $this->build_row_actions( $item['id'] ) );
	}

	/**
	 * Builds the <a href for the main row actions - usually the first column.
	 * This method is an addition by Ryan R. Bayne to work alongside the actions
	 * array which was also added by Ryan. You will not find this approach in most * examples of the WP_List_Table use. However it is a totally acceptable
	 * approach as security is still applied.
	 *
	 * @version 1.0
	 * @param mixed $item_id
	 */
	function build_row_actions( $item_id ) {
		 // Final Actions Array
	 	$final_actions = array();
	 	foreach ( $this->full_actions as $the_action => $a ) {
			// Does current user have permission to view and use this action?
			if ( ! current_user_can( $a['capability'] ) ) {
				continue;
			}

			 // Create a nonce for this action.
			 $nonce = wp_create_nonce( 'multitool_' . $the_action . '_items' );

			 // Build action link.
			 $final_actions[ $the_action ] = sprintf( '<a href="?page=%s&action=%s&item=%s&_wpnonce=%s">' . $a['label'] . '</a>',
				 esc_attr( $_REQUEST['page'] ),
				 $the_action,
				 absint( $item_id ),
				 $nonce
			 );
		}

		return $final_actions;

	}

	/**
	 * Process bulk actions.
	 *
	 * @version 1.0
	 */
	public function process_bulk_action() {
		if ( ! $this->current_action() ) { return; }

		// User must have permission or die!
		if ( ! current_user_can( $this->full_actions[ $this->current_action() ]['capability'] ) ) {
			die( 'Naughty script kiddie detected website will self-destruct in 30 seconds!' );
		}

		// Nonce must be correct or die!
		if ( ! wp_verify_nonce( esc_attr( $_REQUEST['_wpnonce'] ), 'multitool_' . $this->current_action() . '_items' ) ) {
			die( 'Low rate hacker detected the system will laugh for 30 seconds!' );
		}

		switch ( $this->current_action() ) {
			case 'dump':

				if ( isset( $_POST[ $this->bulkid ] ) ) {
					// Operated using checkboxes.
					var_dump( $_POST[ $this->bulkid ] );
				} else {
					// Operated using a single link often styled like a button.
					var_dump( $_GET['item'] );
				}

			break;
			case 'delete':

				// Detect row action click to delete a single row.
				if ( 'delete' === $this->current_action() ) {

					self::delete_item( absint( $_GET['item'] ) );

					wp_redirect( esc_url( add_query_arg() ) );
					exit;

				}

				// If the delete bulk action is submitted.
				if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
				|| ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
				) {

					$delete_ids = esc_sql( $_POST['bulk-delete'] );

					// loop over the array of record IDs and delete them
					foreach ( $delete_ids as $id ) {
							self::delete_item( $id );
					}

					wp_redirect( esc_url( add_query_arg() ) );
					exit;
				}

			break;
			default;
				wp_die( __( 'Bulk action not added to process_bulk_action() please report this.', 'multitool' ) );
			break;
		}// End switch().

	}

	/**
	 * Delete an item.
	 *
	 * @version 1.0
	 * @param int $id item ID
	 */
	public static function delete_item( $id ) {
		global $wpdb;

		/*
		 Perform query here for deleting record
		$wpdb->delete(
		"{$wpdb->prefix}customers",
		[ 'ID' => $id ],
		[ '%d' ]
		);
		*/
	}

	/**
	 * Add extra markup in the toolbars before or after the list
	 *
	 * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list.
	 *
	 * This function can be removed.
	 *
	 * @version 1.0
	 */
	function extra_tablenav( $which ) {
		if ( $which == 'top' ) {
			// The code that goes before the table is here
			echo"Hello, I'm before the table";
		}

		if ( $which == 'bottom' ) {
			// The code that goes after the table is there
			echo '</h4>' . __FUNCTION__ . ' adds code here</h4>';
		}
	}
}
