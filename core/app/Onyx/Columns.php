<?php
/**
 * Columns
 *
 * Used to help manage a post types columns in the admin table
 * Modified by André Mácola Machado
 *
 * @package PostTypes
 * @link    https://github.com/jjgrainger/PostTypes/
 * @author  jjgrainger
 * @link    https://jjgrainger.co.uk
 * @version 2.0
 * @license https://opensource.org/licenses/mit-license.html MIT License
 */

namespace Onyx;

class Columns {

	/**
	 * Holds an array of all the defined columns.
	 *
	 * @var array
	 */
	public $items = [];

	/**
	 * An array of columns to add.
	 *
	 * @var array
	 */
	public $add = [];

	/**
	 * An array of columns to hide.
	 *
	 * @var array
	 */
	public $hide = [];

	/**
	 * An array of columns to reposition.
	 *
	 * @var array
	 */
	public $positions = [];

	/**
	 * An array of custom populate callbacks.
	 *
	 * @var array
	 */
	public $populate = [];

	/**
	 * An array of columns that are sortable.
	 *
	 * @var array
	 */
	public $sortable = [];

	/**
	 * Set the all columns
	 *
	 * @param array $columns an array of all the columns to replace
	 */
	public function set( $columns ) {
		$this->items = $columns;
	}

	/**
	 * Add a new column
	 *
	 * @param string $columns the slug of the column
	 * @param string $label the label for the column
	 * @return void
	 */
	public function add( $columns, $label = null ) {
		if ( ! is_array( $columns ) ) {
			$columns = [ $columns => $label ];
		}

		foreach ( $columns as $column => $label ) {
			if ( is_null( $label ) ) {
				$label = str_replace( [ '_', '-' ], ' ', ucfirst( $column ) );
			}
			$column = sanitize_title( $column );

			$this->add[$column] = $label;
		}
	}

	/**
	 * Add a column to hide
	 *
	 * @param string $columns the slug of the column to hdie
	 */
	public function hide( $columns ) {
		if ( ! is_array( $columns ) ) {
			$columns = [ $columns ];
		}

		foreach ( $columns as $column ) {
			$this->hide[] = sanitize_title( $column );
		}
	}

	/**
	 * Set a custom callback to populate a column
	 *
	 * @param string $column the column slug
	 * @param mixed  $callback callback function
	 */
	public function populate( $column, $callback ) {
		$this->populate[sanitize_title( $column )] = $callback;
	}

	/**
	 * Define the postion for a columns
	 *
	 * @param array $columns an array of columns
	 */
	public function order( $columns ) {
		$columns = array_flip( $columns );
		foreach ( $columns as $column => $position ) {
			$this->positions[sanitize_title( $column )] = $position + 1;
		}
	}

	/**
	 * Set columns that are sortable
	 *
	 * @param array $sort_columns Columns to sort
	 */
	public function set_sortable( $sort_columns ) {
		foreach ( $sort_columns as $column => $options ) {
			$this->sortable[sanitize_title( $column )] = $options;
		}
	}

	/**
	 * Check if an orderby field is a custom sort option.
	 * Refactor for better understanding \Onyx\Cpt->orderby_columns()
	 *
	 * @param string $orderby the orderby value from query params
	 */
	public function is_sortable( $orderby ) {
		foreach ( $this->sortable as $column => $options ) {
			if ( is_string( $options ) && $options === $orderby ) {
				return true;
			}
			if ( is_array( $options ) && isset( $options[0] ) && $options[0] === $orderby ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get meta key for an orderby.
	 * Callable from \Onyx\Cpt->orderby_columns()
	 *
	 * @param string $orderby the orderby value from query params
	 */
	public function sortable_meta_options( $orderby ) {
		foreach ( $this->sortable as $column => $options ) {
			$sort_field = $options[0];
			$sort_type  = $options[1];
			if ( is_array( $options ) && isset( $sort_field ) && $sort_field === $orderby ) {
				return $options;
			}
		}

		return '';
	}

	/**
	 * Modify the columns for the object
	 *
	 * @param array $columns WordPress default columns
	 * @return array The modified columns
	 */
	public function manage_columns( $columns ) {

		// if user defined set columns, return those
		if ( ! empty( $this->items ) ) {
			return $this->items;
		}

		// add additional columns
		if ( ! empty( $this->add ) ) {
			foreach ( $this->add as $key => $label ) {
				$columns[$key] = $label;
			}
		}

		// unset hidden columns
		if ( ! empty( $this->hide ) ) {
			foreach ( $this->hide as $key ) {
				unset( $columns[$key] );
			}
		}

		// if user has made added custom columns
		if ( ! empty( $this->positions ) ) {
			foreach ( $this->positions as $key => $position ) {
				// find index of the element in the array
				$index = array_search( $key, array_keys( $columns ) );
				// retrieve the element in the array of columns
				$item = array_slice( $columns, $index, 1 );
				// remove item from the array
				unset( $columns[$key] );

				// split columns array into two at the desired position
				$start = array_slice( $columns, 0, $position, true );
				$end   = array_slice( $columns, $position, count( $columns ) - 1, true );

				// insert column into position
				$columns = $start + $item + $end;
			}
		}

		return $columns;
	}

	/**
	 * Default populate callback
	 *
	 * @return void
	 */
	public function populate_empty() {
		echo '<i>' . esc_html__( 'No data provided', 'onyx-theme' ) . '</i>';
	}

	/**
	 * Register columns with all parameters
	 *
	 * @see conf/cpts.php
	 * @param array $columns [required]
	 * @return void
	 */
	public function register_columns( $columns ) {
		$add_columns      = [];
		$sort_columns     = [];
		$populate_columns = [];
		foreach ( $columns as $key => $column ) {
			$add_columns[$key]      = $column['label'];
			$populate_columns[$key] = $column['populate'];

			if ( ! empty( $column['sort'] ) ) {
				$sort_columns[$key] = [ $column['sort'], $column['numeric'] ];
			}
		}

		if ( ! empty( $add_columns ) ) {
			$this->add( $add_columns );
		}

		if ( ! empty( $sort_columns ) ) {
			$this->set_sortable( $sort_columns );
		}

		if ( ! empty( $populate_columns ) ) {
			foreach ( $populate_columns as $key => $callback ) {
				$callback = ($callback) ? $callback : [ $this, 'populate_empty' ];
				$this->populate( $key, $callback );
			}
		}
	}

}
