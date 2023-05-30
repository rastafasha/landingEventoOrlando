<?php
/**** 
 * @desc: Q Database Abstract Layer Class
 * @author: Carlos Hernandez <neo.generis@gmail.com>
 * @version: 1.1
 */
class Q {
	
	static private $db;
	static private $countRow;
	
	private function __construct(){}
	private function __clone(){}
	
	
	/** Establece
	 *	@desc Establece una única conexión persitente con la base de datos.
	 *  @sample 
	 *   <code>
	 *	    Q::setup('mysql:host=localhost;dbname=prueba', $usuario, $contraseña);
	 *	</code>
	 */
	
	static public function setup($sdn , $user, $password){
		$retry = 3;	
		if(!extension_loaded("pdo_mysql")) die("No hay extension");
			
			while($retry > 0) try {
				if(!self::$db){
					  self::$db = @new PDO($sdn, $user, $password);
					//self::$db = @new PDO($sdn, $user, $password, array(PDO::ATTR_PERSISTENT => TRUE));
					//self::$db->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, TRUE );
					//self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
					//self::$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE,\PDO::FETCH_ASSOC);
					self::killUnusedConnections();
				}
                $retry=0;
			}catch(PDOException $e){
				
				//print "¡Error!: " . $e->getMessage() . "<br/>";
				$retry--;
				usleep(500);
			}
			
				
		
	}//end setup
	
	static public function getDB(){
		return self::$db;  
	}
	
	static public function setDB(PDO $pdo){
		self::$db = $pdo;
	}
	
	static public function killUnusedConnections(){
		$timeout = 20;
		$dbname = self::getDatabase(); 
		
		$sql = "SELECT concat('KILL ',id,';') FROM information_schema.processlist WHERE COMMAND like 'Sleep' AND time >{$timeout}  AND DB like '{$dbname}'; ";
		$rows = self::getAll($sql);
		foreach($rows as $row){
			$sql = Q::getFirstField($row);
			Q::exec($sql);
		}
		
	}
	
	static public function begin(){
		self::$db->beginTransaction();
	}
	
	static public function commit(){
		self::$db->commit();
	}
	
	static public function rollback(){
		self::$db->rollback();
	}
	
	
	static public function trans($func){
		self::begin();
		$func();
		self::commit();
	}
	
	
	/** Ping
	 *
 	 *	@desc Valida que el servidor está respondiendo.
	 *  @return bool
	 *
	 *  @example
     *	<code>
	 *     if( Q::ping() ){
	 *	    	$r = Q::call("database");
	 *     } 
	 *     
	 *     print_r($r);     //  ->  "test"
	  
	 */
	
	
	static public function ping(){
		
		try{
			$r = true && self::getOne("SELECT 0"); //intento consultar la base de datos;	
		}catch(Exception $e){
			$r = false; 
		}
		return $r;
	}
	
	
	
	/** Obtener Todos
	 *
	 *  @desc Ejecuta un comando SQL y devuelve todos los registros en un arreglo de arreglos.
	 *  @return array
	 *
	 *  @example
     *	<code>
	 *     $sql = " show databases ";
	 *     $rows = Q::getAll($sql); 
	 *     
	 *     print_r($r);     //  ->  Array( [0] => Array( ...) ... )
	 */
	
	static public function getAll($sql){
		$r = array();
		try{
			$q  = self::$db->query($sql);
			if($q){
				self::$countRow = $q->rowCount();
				$r = $q->fetchAll(2);
				return $r;
			}
		}catch(PDOException $e){
			return $r;
		}
	
	}
	
	/** Obtener Uno
	 *
	 *	@desc Ejecuta un comando SQL preparado para arrojar un único registro.
	 *  @return array
	 *
	 *  @example
     *	<code>
	 *     $sql = " SELECT database() ";
	 *     $row = Q::getOne($sql); 
	 *     $r   = Q::getFirstField(); 
	 *     
	 *     echo($r);     //  ->  test
     *
	 */
	
	static public function getOne($sql){
		$r = array();
		$q  = self::$db->query($sql);
		if($q) $r = $q->fetch(2);
		return  $r;
	}
	
	static public function getField($field,$row){
		return $row[$field];
	}
	
	static public function getFirstField($row){
		return isset($row[key($row)] )? $row[key($row)]:null;
	}
	
	/** Ejecuta SQL
	 *
	 *	@desc Ejecuta un comando SQL y retorna un status de la operacion.
	 *  @return array
	 */
	
	static public function exec($sql){
		self::getAll($sql);
		return self::getStatus();
	}
	
	/** Obtener Ultimo Id
	 * 
	 * @desc Esta función puede ser llamada luego de insertar para obtener el ID del último registro.
	 *
	 *  @example
     *	<code>
	 *     $user = new StdClass();
	 *     $user->id = 7;
	 *     $user->username = "admin";
     *     $user->password = "manager";	 
	 *     Q::insertRow("users",$user); 
	 *
	 *     $r = Q::getLastId() ;
	 *     echo($r);     //  ->  7
     *  </code>	 
	 *
	 */
	
	static public function getLastId(){
		return self::$db->lastInsertId(); 
	}
	
	/** Obtener registros afectados
	 * @desc Esta función obtiene la cantidad de registros afectados
	 *
	 *  @example
     *	<code>
	 *     $user = new StdClass();
	 *     $user->id = 7;
	 *     $user->username = "admin";
     *     $user->password = "manager";	 
	 *     Q::insertRow("users",$user); 
	 *
	 *     $r = Q::getAffectedRows() ;
	 *     echo($r);     //  ->  1
     *  </code>	 
	 
	 */
	static public function getAffectedRows(){
		return self::$countRow;
	}
	
	/** Obtener Status
	 *
     *	 @desc Devuelve el status de la última operación. 
	 *   @return array 
	 *
	 *  @example
     *	<code>
	 *     $user = new StdClass();
	 *     $user->id = 7;
	 *     $user->username = "admin";
     *     $user->password = "manager";	 
	 *     Q::insertRow("users",$user); 
	 *
	 *     $r = Q::getStatus() ;
	 *     print_r($r);     //  -> Array( [status] => 1[countRow] => 1 [lastInsertId] => 7 [msg] => OK )
     *  </code>	 
	 */
	
	static public function getStatus(){
			$result   = self::$db->errorInfo();
			$status   = false;	
			$msg      = empty($result[2])?"OK":$result[2];		
		
			$countRow = self::getAffectedRows();      //Cantidad de registros afectados
			$id       = self::getLastId();            //Ultimo Id Insertado
			
			$status   = ($result[0]==0);              //Si la operacion fue exitosa.
		
		return array("status"  =>$status , "countRow" => $countRow , "lastInsertId" =>$id , "msg" => $msg );
	}
	
	
	static public function getColumnIdsFromQuery($sql){
		$sql = " SELECT * FROM ($sql) AS COLUMNIDS LIMIT 1 ";
		$columnIds  = self::getOne($sql);
		$columnIds = array_keys($columnIds);
		$columnIds = array_combine($columnIds,$columnIds);
		return $columnIds;
	}
	
	/* Obtener los Id de columna
	 *	@param string $table	
	 *	@desc Obtiene una lista con los nombres que identifican las columnas en la tabla correspondiente
	 *  @return array
	 *  @example
	 *	<code>
	 *     $r = Q::getColumnIds("users") ;
	 *     print_r($r); 
     *  </code>	 
	 *
	 */
	static public function getColumnIds($table){
		$result = array();
		$sql = " DESCRIBE {$table} ";
		$rows = self::getAll($sql);
		foreach($rows as $row){
			$columnId = $row['Field'];
			$result[$columnId]=$columnId;
		}
		return $result; 
	}	
	
	/** Selecciona Registro
	 *
	 *	@param string $table
	 *  @param array  $row
	 *  @param array  $select
	 *  @desc Selecciona un registro de una tabla, usar el argumento $row para filtrar 
	 *  @see getOne
	 *  @see getAll
	 *  @see exec
	 *
	 *  @example
     *	<code>
	 *
	 *     $r = Q::selectRow("users",array('id'=>7),array('id','username')) ;
	 *   </code>
	 */
		
	static public function selectRow($table,$row="",$select=""){
		$r = array();
		if($select){
			$valid = implode(',',self::validRow($table,$select));
		}else{
			$valid = "*";
		}
		
		if(!empty($row) && is_array($row)){
			$filter = self::filterRow($table,$row);
			if(!empty($filter)){
				$where = " WHERE {$filter} ";
			}else{
				$where = "";
			}
			$sql = "SELECT * FROM {$table} {$where} LIMIT 1; ";
			$r   = Q::getOne($sql);
		}
		
		return $r;
	}
	
	/** Obtener registro
	 *
	 *	@param string $table   
	 *  @param array $row      
     *  @desc Dado el nombre de la tabla y un arreglo con los valores por los que se desea filtrar, se obtendrá un registro.
     *  @return array
     *  @example
     *	<code>
	 *     $r = Q::getRow("users",array('id'=>7)) ;
	 *     print_r($r); 
     *  </code>	 
	 */
	 
	static public function getRow($table,$row){
		$r = self::selectRow($table,$row);
		return $r;
	}
	
	
	static public function insertRowBuilder($table,$row,$opt=false){
		$row = (array) $row;
		
		$fields = array();
		$values = array();
		
		$validColumnIds = self::getColumnIds($table); 
		
		if($validColumnIds && is_array($validColumnIds)){
			foreach($validColumnIds as $id){
				if(isset($row[$id])){
					if(!empty($row[$id]) || $opt ){
						$fields[]=$id;
						$values[]= !empty($row[$id])? "'$row[$id]'" : "NULL" ;
					}
				}//end if
			}//end foreach
			$fields    = implode(',' , $fields);
			$values    = implode(',' , $values);
			$sql       = " INSERT INTO `".$table."` ($fields) VALUES ($values); ";
			return $sql;
		}//end if
	}//end Builder
	
	
	/** Insertar Registro
	 *
	 *	@param string $table
	 *  @param array  $row
	 *  @desc Inserta un registro en una tabla.
	 *  @example
     *	<code>
	 *     $user = new StdClass();
	 *     $user->id = 7;
	 *     $user->username = "admin";
     *     $user->password = "manager";	 
	 *     Q::insertRow("users",$user); 
	 *
	 *     $r = Q::getStatus() ;
	 *     print_r($r);     //  -> Array( [status] => 1[countRow] => 1 [lastInsertId] => 7 [msg] => OK )
     *     
	 *    //Forma Corta:
     *     $r = Q::insertRow("users" , array('id'=>7,'username'=>'admin','password'=>'manager') );
	 *     print_r($r);     //  -> Array( [status] => 1[countRow] => 1 [lastInsertId] => 7 [msg] => OK )
	 *   </code>
	 */
	
	static public function insertRow($table,$row){
		$sql =  self::insertRowBuilder($table,$row);
		return self::exec($sql);
	}//end addRow
	
	static public function insertMultipleRows($table,array $rows,array $commons=array(),$callback=null){
		$count = 0; 
		if(is_array($rows))
		foreach($rows as $row){
			if(self::insertRow($table,$commons+$row)){
				$count++;
				
				if($callback){
					$callback(Q::getLastId(),$commons+$row);
				}  
			}
		}
		return $count;
	}
	
	
	
	static public function replaceRowBuilder($table,$row,$opt=false){
		$row = (array) $row;
		
		$fields = array();
		$values = array();
		
		$validColumnIds = self::getColumnIds($table); 
		
		if($validColumnIds && is_array($validColumnIds)){
			foreach($validColumnIds as $id){
				if(isset($row[$id])){
					if(!empty($row[$id]) || $opt ){
						$fields[]=$id;
						$values[]= !empty($row[$id])? "'$row[$id]'" : "NULL" ;
					}
				}//end if
			}//end foreach
			$fields    = implode(',' , $fields);
			$values    = implode(',' , $values);
			$sql       = " REPLACE INTO `".$table."` ($fields) VALUES ($values); ";
			return $sql;
		}//end if
	}//end Builder
	
	
	/** Reemplaza Registro
	 *
	 *	@param string $table
	 *  @param array  $row
	 *  @desc Se usa igual que insertRow, la diferencia radica en que si ya existe el registro lo borra antes de insertar.
	 *  @example
     *	<code>
	 *     $user = new StdClass();
	 *     $user->id = 7;
	 *     $user->username = "admin";
     *     $user->password = "manager";	 
	 *     Q::replaceRow("users",$user); 
	 *
	 *     $r = Q::getStatus() ;
	 *     print_r($r);     //  -> Array( [status] => 1[countRow] => 1 [lastInsertId] => 7 [msg] => OK )
     *     
	 *    //forma corta:
     *     if( Q::replaceRow("users",array('id'=>7,'username'=>'admin','password'=>'manager')) ){ echo "OK"; }
	 *   </code>
	 */
	
	
	static public function replaceRow($table,$row){
		$sql =  self::replaceRowBuilder($table,$row);
		return self::exec($sql);
	}//end addRow
	
	/* Reemplaza Multiples Registros
	 *
	 *	@param string $table
	 *  @param array  $rows
	 *  @desc Permite insertar o reemplazar multiples rows, si la clave primara de cada registro coincide con los
	 *        previamente registrados, estos registros seran reemplazados, en caso contrario serán registrados 
	 *        como nuevos.
	 *  @see replaceRow
	 *  @see insertMultipleRows
	 *  @example
     *	<code>
	 *	 $rows = array(
	 *		1 => array("firstname" => "carlos","lastname"=>"hernandez","persontype_id"=>1),
	 *		2 => array("firstname" => "alberto","lastname"=>"fernandez","persontype_id"=>1)
	 *	);
	 *	Q::replaceMultipleRows("persons",$rows);
	 *	</code>
	 *
	 */
	
	static public function replaceMultipleRows($table,$rows){
		$count = 0;
		if(is_array($rows))
		foreach($rows as $row){
			if(self::replaceRow($table,$row)){
				$count++;
			}
		}
		return $count;
	}
	
	
	static public function fieldset($row){
		$o=array();
		foreach($row as $field => $value){
			$o[] = "`{$field}`='{$value}'";
		}//end foreach
		return $o;
	}
	
	
	static public function andFilter($row){
		$fieldset = self::fieldset($row);
		return implode(" AND ",$fieldset);
	}
	
	static public function filterRow($table,$row){
		$row = self::validRow($table,$row);
		return self::andFilter($row);
	}
	
	static public function deleteRowBuilder($table,$row){
		$sql = " DELETE FROM {$table} WHERE ".self::filterRow($table,$row);
		return $sql;
	}
	
	/** Borra un Registro
	 *
	 *	@param string $table
	 *  @param array  $row
	 *  @desc Borra un registro de una tabla dada.
	 *  @example
     *	<code>
	 *     $r = Q::deleteRow("users",array('username'=>'admin')); 
	 *
	 *     print_r($r);     //  -> Array( [status] => 1[countRow] => 1 [lastInsertId] => 7 [msg] => OK )
     *     
	 *   </code>
	 */
	
	static public function deleteRow($table,$row){
		return self::exec(self::deleteRowBuilder($table,$row));
	}
	
	static public function validRow($table,$row){
		$r = array();
		
		$validColumnIds = self::getColumnIds($table); 
		if($validColumnIds && is_array($validColumnIds)){
			foreach($row as $columnId => $value){
				if(in_array($columnId, $validColumnIds)){
					$r[$columnId] = $value;
				}
			}
		}
		return $r;
	} 
	
	static public function updateRowBuilder($table,$row,$newRow){	
		$newRow = self::validRow($table,$newRow);
		$fieldset = self::fieldset($newRow);
		$fieldset = implode(",",$fieldset);
		$sql = " UPDATE {$table} SET {$fieldset} WHERE ".self::filterRow($table,$row);
		return $sql;
	}
	
	
	/** Actualiza Registro
	 *
	 *	@param string $table
	 *  @param array  $row
	 *  @param array  $newRow
	 *  @desc Actualiza un registro de una tabla. El argumento $row funciona de filtro mientras que el $newRow actualiza los nuevos valores.
	 *  @example
     *	<code>
	 *     $r = Q::updateRow("users",array('username'=>'admin') ,array('password' => sha1('manager')) ); 
	 *
	 *     print_r($r);     //  ->   Array( [status] => 1[countRow] => 1 [lastInsertId] => 7 [msg] => OK )
	 *  </code>
	 */
	
	static public function updateRow($table,$row,$newRow){
		return self::exec(self::updateRowBuilder($table,$row,$newRow));
	}
	
	/** Cuenta Registros
	 *
	 *	@param string $table
	 *  @param array  $row
	 *  
	 *  @desc Cuenta los registro de una tabla dada una condición
	 *  @example
     *	<code>
	 *     $count = Q::countRow("users",array('id'=>1)) ); 
	 *
	 *     print_r($count);     //  ->   1
	 *  </code>
	 */
	
	
	static public function countRow($table,$row){
		$sql = "SELECT count(*) as count FROM {$table} WHERE ".self::filterRow($table,$row);
		$r = self::getOne($sql);
		$count = $r['count'];
		return $count;
	}
	
	
	/** Cuenta Todos los Registros
	 *
	 *	@param string $table
	 *  @param array  $row
	 *  
	 *  @desc Cuenta los registro de una tabla 
	 *  @example
     *	<code>
	 *     $count = Q::countAll("users") ); 
	 *
	 *     print_r($count);     //  ->   3456
	 *  </code>
	 */
	static public function countAll($table){
		$sql = "SELECT count(*) as count FROM {$table} ";
		$row = self::getOne($sql);
		$count = self::getField("count",$row);
		return $count;
	}
	
	
	/** Tiene este Registro
	 *
	 *	@param string $table
	 *  @param array  $row
	 *  
	 *  @desc Verifica si existe el registro en la tabla.
	 *  @example
     *	<code>
	 *    if( Q::hasRow("users",array('id'=>1)) ){ 
	 *           $r=Q::deleteRow("users",array('id'=>1));
	 *    } 
	 *  </code>
	 */
	static public function hasRow($table,$row){
		return ( self::countRow($table,$row) > 0 );
	}
	
	/** Actualiza o Inserta Registro
	 *
	 *	@param string $table
	 *  @param array  $row
	 *  
	 *  @desc Se usa igual que updateRow, la diferencia se encuentra en que si el valor no existe, entonces se inserta.
	 *  @example
     *	<code>
	 *    if( Q::hasRow("users",array('id'=>1)) ){ 
	 *           $r=Q::deleteRow("users",array('id'=>1));
	 *    } 
	 *  </code>
	 */
	
	static public function upsertRow($table,$row,$newRow=null){
		if($newRow==null) $newRow=$row;
		
		if(self::hasRow($table,$row)){
			$r = self::updateRow($table,$row,$newRow);
		}else{
			$r = self::insertRow($table,$newRow);
		}	
		return $r;
	}
	
	/** Obtener Base de datos 
	 *
	 *  
	 *  @desc Devuelve el nombre de la base de datos actual.
	 *  @example
     *	<code>
	 *    $dbname = Q::getDatabase();   //  -> test 
	 *  </code>
	 */
	static public function getDatabase(){
		$sql = " SELECT database() AS name; ";
		$row = self::getOne($sql);
		return self::getField("name",$row);
	}
	
	static public function getParameters($routine){
		$dbname =  self::getDatabase();
		$sql = " SELECT ORDINAL_POSITION,PARAMETER_NAME,PARAMETER_MODE,DATA_TYPE FROM information_schema.PARAMETERS WHERE SPECIFIC_SCHEMA='{$dbname}' AND SPECIFIC_NAME='{$routine}' ";
		return Q::getAll($sql);
		
	}
	
	static public function getRoutineType($routine){
		$dbname = self::getDatabase();
		$sql    = "SELECT ROUTINE_TYPE FROM information_schema.ROUTINES WHERE routine_name = '{$routine}' and routine_schema='{$dbname}'";
		$row    = self::getOne($sql);
		$result = self::getField("ROUTINE_TYPE",$row);
		return $result;
	}
	
	
	static public function isProcedure($routine){
		return self::getRoutineType($routine) == "PROCEDURE";
	}
	
	static public function isFunction($routine){
		return self::getRoutineType($routine) == "FUNCTION";
	}
	
	static public function parameterBuilder($routine,$row){
		$row    = (array)$row;
		$values = array();
		$parameters = self::getParameters($routine); 
		
		if(!empty($row)){
			$is_numeric = is_numeric(key($row)); 
			if($parameters && is_array($parameters)){
				foreach($parameters as $parameter){					
					if($parameter['ORDINAL_POSITION'] >0 ){ //si es un argument y no un returns	
						$key = $is_numeric?$parameter['ORDINAL_POSITION']-1:$parameter['PARAMETER_NAME']; 
						$values[] = isset($row[$key])?"'$row[$key]'" : "NULL" ;
					}
				}//end foreach		
			}//end if parameters
		}//end if empty
		
		$values = implode(',' , $values);
		
		return $values;
	}
	
	static public function callProcedureBuilder($routine,$row){
		$values = self::parameterBuilder($routine,$row);
		$sql = " CALL {$routine}({$values}) ; ";
		return $sql;
	}//end Builder
	
	
	static public function callFunctionBuilder($routine,$row){
		$values = self::parameterBuilder($routine,$row);
		$sql = " SELECT {$routine}({$values}) AS RESULT; ";
		return $sql;
	}//end Builder
	
	
	
	static public function callFunction($routine,$row){
		$sql = self::callFunctionBuilder($routine,$row);
		$result = self::getAll($sql);
		if(isset($result[0])){
				return self::getFirstField($result[0]);
		}
	}//end 
	
	static public function callProcedure($routine,$row){
		$sql = self::callProcedureBuilder($routine,$row);
		return self::getAll($sql);
	}//end
	
	
	/** Llamar
	 *
	 *  @param string $routine
	 *  @param array $row    
	 *  @desc Llama a un procedimiento o función, se comporta diferente según el caso. 
	 *  @return mixed
	 *  @see callFunction
	 *  @see callProcedure
	 *  @see exec
	 *  @see getAll
	 *  @see getStatus
	 *  @example
     *	<code>
	 *    $r = Q::call("hello_world");   
     *    echo $r;  //  -> "Hello";
     *    $rows = Q::call("sp_add_user",array('id'=>5,'username'=>'manager','password'=>sha1('manager')));
     *    print_r(Q::getStatus());
     *    $rows = Q::call("sp_get_user_by_id",array('id'=>7));
     *    print_r($rows);	 
	 *  </code>
	 */
	
	
	static public function call($routine,$row){
		if(self::isFunction($routine)){
			return self::callFunction($routine,$row);
			 
		}else{
			return self::callProcedure($routine,$row);
		} 	
	}//end call
		
}//end class

?>