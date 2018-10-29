<?php

/**
 * Class to handle posts
 */
class Post {

	// Properties
	/**
	* @var int The article ID from the database
	*/
	public $id = null;
	/**
	* @var int When the article was published
	*/
	public $creation_date = null;
	/**
	* @var int When the article was modified
	*/
	public $modified_date = null;
	/**
	* @var string Full title of the article
	*/
	public $title = null;
	/**
	* @var string A short summary of the article
	*/
	public $summary = null;
	/**
	* @var string The HTML content of the article
	*/
	public $content = null;


	/**
	* Sets the object's properties using the values in the supplied array
	*
	* @param assoc The property values
	*/

	public function __construct( $data=array() ) {
		if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
		if ( isset( $data['created_date'] ) ) $this->created_date = (int) $data['created_date'];
		if ( isset( $data['modified_date'] ) ) $this->modified_date = (int) $data['modified_date'];
		if ( isset( $data['title'] ) ) $this->title = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['title'] );
		if ( isset( $data['summary'] ) ) $this->summary = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['summary'] );
		if ( isset( $data['content'] ) ) $this->content = $data['content'];
	}

	/**
	* Sets the object's properties using the edit form post values in the supplied array
	*
	* @param assoc The form post values
	*/

	public function storeFormValues ( $params ) {

		// Store all the parameters
		$this->__construct( $params );

		// Parse and store the publication date
		if ( isset($params['created_date']) ) {
			$created_date = explode ( '-', $params['created_date'] );

			if ( count($created_date) == 3 ) {
			list ( $y, $m, $d ) = $created_date;
			$this->created_date = mktime ( 0, 0, 0, $m, $d, $y );
			}
		}
	}


	/**
	* Returns an Post object matching the given article ID
	*
	* @param int The article ID
	* @return Post|false The article object, or false if the record was not found or there was a problem
	*/

	public static function getById( $id ) {
		$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
		$sql = "SELECT *, UNIX_TIMESTAMP(created_date) AS created_date FROM posts WHERE id = :id";
		$st = $conn->prepare( $sql );
		$st->bindValue( ":id", $id, PDO::PARAM_INT );
		$st->execute();
		$row = $st->fetch();
		$conn = null;
		if ( $row ) return new Post( $row );
	}


  /**
  * Returns all (or a range of) Post objects in the DB
  *
  * @param int Optional The number of rows to return (default=all)
  * @param string Optional column by which to order the posts (default="created_date DESC")
  * @return Array|false A two-element array : results => array, a list of Post objects; totalRows => Total number of posts
  */

	public static function getList( $numRows=1000000, $order="created_date DESC" ) {
		$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
		$sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(created_date) AS created_date FROM posts
				ORDER BY " . $order . " LIMIT :numRows";

		$st = $conn->prepare( $sql );
		$st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
		$st->execute();
		$list = array();

		while ( $row = $st->fetch() ) {
			$post = new Post( $row );
			$list[] = $post;
		}

		// Now get the total number of posts that matched the criteria
		$sql = "SELECT FOUND_ROWS() AS totalRows";
		$totalRows = $conn->query( $sql )->fetch();
		$conn = null;
		return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
	}


  /**
  * Inserts the current Post object into the database, and sets its ID property.
  */

	public function insert() {

	// Does the Post object already have an ID?
	if ( !is_null( $this->id ) ) trigger_error ( "Post::insert(): Attempt to insert an Post object that already has its ID property set (to $this->id).", E_USER_ERROR );

		// Insert the Post
		$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
		$sql = "INSERT INTO posts ( created_date, title, summary, content ) VALUES ( FROM_UNIXTIME(:created_date), :title, :summary, :content )";
		$st = $conn->prepare ( $sql );
		$st->bindValue( ":created_date", $this->created_date, PDO::PARAM_INT );
		$st->bindValue( ":title", $this->title, PDO::PARAM_STR );
		$st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
		$st->bindValue( ":content", $this->content, PDO::PARAM_STR );
		$st->execute();
		$this->id = $conn->lastInsertId();
		$conn = null;
	}


  /**
  * Updates the current Post object in the database.
  */

	public function update() {

		// Does the Post object have an ID?
		if ( is_null( $this->id ) ) trigger_error ( "Post::update(): Attempt to update an Post object that does not have its ID property set.", E_USER_ERROR );

		// Update the Post
		$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
		$sql = "UPDATE posts SET created_date=FROM_UNIXTIME(:created_date), title=:title, summary=:summary, content=:content WHERE id = :id";
		$st = $conn->prepare ( $sql );
		$st->bindValue( ":created_date", $this->created_date, PDO::PARAM_INT );
		$st->bindValue( ":title", $this->title, PDO::PARAM_STR );
		$st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
		$st->bindValue( ":content", $this->content, PDO::PARAM_STR );
		$st->bindValue( ":id", $this->id, PDO::PARAM_INT );
		$st->execute();
		$conn = null;
	}


	/**
	* Deletes the current Post object from the database.
	*/

	public function delete() {

		// Does the Post object have an ID?
		if ( is_null( $this->id ) ) trigger_error ( "Post::delete(): Attempt to delete an Post object that does not have its ID property set.", E_USER_ERROR );

		// Delete the Post
		$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
		$st = $conn->prepare ( "DELETE FROM posts WHERE id = :id LIMIT 1" );
		$st->bindValue( ":id", $this->id, PDO::PARAM_INT );
		$st->execute();
		$conn = null;
	}

}

?>