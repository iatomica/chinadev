<?php
require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}

function find_all1($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table)." WHERE openclose = '1'");
   }
}

/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

function find_by_id_catsub($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE products_id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id = ". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
function close_reg($table,$id){
 global $db;
  if(tableExists($table))
   {
    $sql = "UPDATE ".$db->escape($table)." SET";
    $sql .= " openclose = '0'";
    $sql .= " WHERE id = ". $db->escape($id)."";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }

}


/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
 /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
  function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
      if($password_request === $user['password'] ){
        return $user['id'];
      }
    }
   return false;
  }
  /*--------------------------------------------------------------*/
  /* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
     $result = $db->query($sql);
     if($db->num_rows($result)){
       $user = $db->fetch_assoc($result);
       $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ORDER BY u.name ASC";
      $result = find_by_sql($sql);
      return $result;
  }
  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /* Find all Group name
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Find group level
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
   function page_require_level($require_level){
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['user_level']);
     //if user not login
     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Por favor Iniciar sesión...');
            redirect('index.php', false);
      //if Group status Deactive
     elseif($login_level['group_status'] === '0'):
           $session->msg('d','Este nivel de usaurio esta inactivo!');
           redirect('home.php',false);
      //cheackin log in User level and Require level is Less than or equal to
     elseif($current_user['user_level'] <= (int)$require_level):
              return true;
      else:
            $session->msg("d", "¡Lo siento!  no tienes permiso para ver la página.");
            redirect('home.php', false);
        endif;

     }
   /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
  function join_product_table(){
    global $db;
    $sql  =" SELECT p.id,p.desc_english,p.price,p.media_id,p.code,";
    $sql  .=" m.file_name AS image";
    $sql  .=" FROM products p";
    $sql  .=" LEFT JOIN media m ON m.id = p.media_id";
    $sql  .=" ORDER BY p.id ASC";
    return find_by_sql($sql);

   }



    function join_vendor_table(){
    global $db;
    $sql  =" SELECT v.id,v.name,v.phone,v.email";
    $sql  .=" FROM vendors v WHERE openclose = '1'";
    $sql  .=" ORDER BY v.id ASC";
    return find_by_sql($sql);

   }
    function join_client_table(){
    global $db;
    $sql  =" SELECT v.id,v.name,v.phone,v.email";
    $sql  .=" FROM clients v WHERE openclose = '1'";
    $sql  .=" ORDER BY v.id ASC";
    return find_by_sql($sql);

   }
  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

   function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
  function find_all_product_info_by_title($title){
    global $db;
    $sql  = "SELECT * FROM products ";
    $sql .= " WHERE name ='{$title}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }

  function duplicate_codes($code){
    $sql  = "SELECT * FROM products ";
    $sql .= " WHERE code ='{$code}'";
    
   return $db->query($sql);

    }
    
  

  /*--------------------------------------------------------------*/
  /* Function for Update product quantity
  /*--------------------------------------------------------------*/
  function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }
  /*--------------------------------------------------------------*/
  /* Function for Display Recent product Added
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.name,p.sale_price,p.media_id,c.name AS categorie,";
   $sql  .= "m.file_name AS image FROM products p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit){
   global $db;
   $sql  = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
   $sql .= " GROUP BY s.product_id";
   $sql .= " ORDER BY SUM(s.qty) DESC LIMIT ".$db->escape((int)$limit);
   return $db->query($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function find_all_sale(){
   global $db;
   $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " ORDER BY s.date DESC";
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Display Recent sale
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date,$end_date){
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  $sql  = "SELECT s.date, p.name,p.sale_price,p.buy_price,";
  $sql .= "COUNT(s.product_id) AS total_records,";
  $sql .= "SUM(s.qty) AS total_sales,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price,";
  $sql .= "SUM(p.buy_price * s.qty) AS total_buying_price ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}'";
  $sql .= " GROUP BY DATE(s.date),p.name";
  $sql .= " ORDER BY DATE(s.date) DESC";
  return $db->query($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Daily sales report
/*--------------------------------------------------------------*/
function  dailySales($year,$month){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y-%m' ) = '{$year}-{$month}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%e' ),s.product_id";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Monthly sales report
/*--------------------------------------------------------------*/
function  monthlySales($year){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y' ) = '{$year}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%c' ),s.product_id";
  $sql .= " ORDER BY date_format(s.date, '%c' ) ASC";
  return find_by_sql($sql);
}



function find_subcat($table,$idcat){
  global $db;
  if(tableExists($table))
   {
    $sql = "SELECT * FROM ".$db->escape($table);
    $sql .= " WHERE categories_id =".$db->escape($idcat);


     return find_by_sql($sql);
   }
}

function select_categories(){
  global $db;
    $sql = "SELECT * FROM categories ";
    
    $result = $db->query($sql);
    $row = $db->fetch_assoc($result);
    return $result;
   
}

function select_image_($filecode){
  global $db;
    $sql = "SELECT * FROM media where file_name = '".$filecode."'";
    
    $result = $db->query($sql);
    $row = $db->fetch_assoc($result);
     return $row;
   




}
function select_image_pr($table,$photo){
if(tableExists($table))
   {
    global $db;
    $sql = "SELECT id FROM ".$db->escape($table);
    $sql .= " WHERE file_name = "."'".$db->escape($photo)."'";
    $sql .=" LIMIT 1";
    $result = $db->query($sql);
    $row = $db->fetch_assoc($result);
     return $row['id'];
   }

}
function find_id_pr($table,$pr_code){
if(tableExists($table))
   {
    global $db;
    $sql = "SELECT * FROM ".$db->escape($table);
    $sql .= " WHERE id = "."'".$db->escape($pr_code)."'"; 
    $sql .=" LIMIT 1";
    $result = $db->query($sql);
    $row = $db->fetch_assoc($result);
     return $row['id'];
   }

}
function addimg(){
$resi = '1';
  return $resi;
}

function validate_code($code,$client){
  $table = 'products';
  if(tableExists($table))
   {
    global $db;
    $sql = "SELECT id FROM products";
    $sql .= " WHERE clients_id = "."'".$db->escape($client)."'";
    $sql .= " AND code = "."'".$db->escape($code)."'";
    $sql .=" LIMIT 1";
    $result = $db->query($sql);
    
    return $db->num_rows($result) > 0;
   }
    
    
  }

  function joinsqlname($table1,$table2,$id,$name,$id2){
    global $db;
    
    $sql ="SELECT D.".$db->escape($name)." as '".$db->escape($table2)."'";
    $sql .=" FROM ".$db->escape($table1)." E"; 
    $sql .=" JOIN ".$db->escape($table2)." D";
    $sql .=" ON E.".$db->escape($id)." = D.id";
    $sql .=" WHERE E.".$db->escape($id)." = ".$db->escape($id2)." LIMIT 1";
//$sql = "SELECT D.name as clients FROM clientcode E JOIN clients D ON E.clients_id = D.id WHERE E.clients_id = 2 LIMIT 1";

$q = $db->query($sql);
$row = $db->fetch_assoc($q);
return $row[$table2];

}

function name_image($id){
global $db;

$sql = "SELECT d.file_name as image FROM products p JOIN media d ON p.media_id = d.id WHERE p.media_id = ".$db->escape($id)." LIMIT 1";

$q = $db->query($sql);
$row = $db->fetch_assoc($q);
return $row['image'];

}


function obtaing_max_id($tabla){
global $db;
$sql = "SELECT MAX(id) AS id FROM ".$db->escape($tabla)."";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['id'];


//$sql .=

}

function obtaing_max_id_row($tabla){
global $db;
$sql = "SELECT MAX(id) AS id FROM ".$db->escape($tabla)."";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row;


//$sql .=

}

function obtaing_tp_rg($idproduct){

global $db;
$sql = "SELECT DISTINCT p.tp, p.id,p.clients_id FROM detail_po d JOIN purchaseorder p ON d.purchaseorder_id = p.id WHERE d.products_id = '".$db->escape($idproduct)."' AND d.finalized = 0";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return find_by_sql($sql);



}

function sum_po_pr($poid,$prid){
global $db;
$sql = "SELECT SUM(qty_po) from detail_po WHERE purchaseorder_id = ".$db->escape($poid)." AND products_id = ".$db->escape($prid)." AND finalized = 0";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['SUM(qty_po)'];

}

function finalized_items_po($poid,$prid){
global $db;
$sql = "UPDATE detail_po SET finalized = 1, pendent = 0 WHERE products_id = ".$db->escape($prid)." AND purchaseorder_id = ".$poid."";
$db->query($sql);

}

function pendent_items_po($poid,$prid){
  global $db;
  $sql = "SELECT id,qty_po,products_id,pendent FROM detail_po WHERE products_id = ".$prid." AND purchaseorder_id = ".$poid." AND finalized = 0";
  $result= $db->query($sql);
  $row = $db->fetch_assoc($result);
  return $row;
}
//--------
function pendent_detail_po_finalized($poid){
  global $db;
  $sql = "UPDATE detail_po SET finalized = 1 WHERE id = ".$poid."";
  $result= $db->query($sql);

}
function status_rgdt_update($rgdtid){
   global $db;
  $sql = "UPDATE detail_rg SET finalized = 1 WHERE id = ".$rgdtid."";
  $result= $db->query($sql);

}

function status_rg_update($rgid){
   global $db;
  $sql = "UPDATE receivedgoods SET finalized = 1 WHERE id = ".$rgid."";
  $result= $db->query($sql);

}

function pendent_po_update($pendent,$poid){
  global $db;
  $sql ="UPDATE detail_po SET pendent = ".$pendent." WHERE id = ".$poid."";
  $db->query($sql);
}

function sum_rg_pr($poid,$prid){
global $db;
$sql = "SELECT SUM(qty_rg) from detail_rg WHERE purchaseorder_id = ".$db->escape($poid)." AND products_id = ".$db->escape($prid)."";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['SUM(qty_rg)'];

}


function select_id_rg($idrg){
global $db;
$sql = "SELECT * FROM detail_rg WHERE id =".$idrg.""; 
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['qty_rg'];

}

function pendent_po_item_update($idpo){

 global $db;

 $sql = "SELECT finalized,pendent FROM detail_po WHERE id = ".$idpo."";
 $result= $db->query($sql);
 $row = $db->fetch_assoc($result);
 return $row;
}

function obtaingclient_id_rg($clientcode){
global $db;
 $sql = "SELECT clients_id FROM clientcode WHERE clientcode ='".$clientcode."'"; 
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['clients_id'];



}

function obtaing_po_status($poid){

global $db;
 $sql = "SELECT finalized FROM purchaseorder WHERE id ='".$poid."'"; 
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['finalized'];


}

function obtaing_tp_name($poid){
global $db;
 $sql = "SELECT tp FROM purchaseorder WHERE id ='".$poid."'"; 
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['tp'];



}

function obtaing_name_smt($smtid){
 global $db;
 $sql = "SELECT movementtype FROM movements_type WHERE id = '".$smtid."'"; 

  $result = $db->query($sql);
  $row = $db->fetch_assoc($result);
  return $row['movementtype'];

}



function search_stock_movement($smid){
  global $db;
  $sql = "SELECT * FROM detail_sm WHERE stockmovements_id = '".$smid."'"; 

  $result = $db->query($sql);
  $row = $db->fetch_assoc($result);

  return $result;

}


function select_stock($cliid,$prid){

  global $db;
  $sql = "SELECT * FROM stock WHERE products_id = '".$prid."' AND clients_id = '".$cliid."'"; 

  $result = $db->query($sql);
  $row = $db->fetch_assoc($result);

  return $row;

}



function update_stock($qty,$stid){

  global $db;
  $sql = "UPDATE stock SET qty = '".$qty."' WHERE id = '".$stid."'"; 
  $result = $db->query($sql);
  
}
function update_item_status_detail_sm($smdtid,$finalized){

  global $db;
  $sql = "UPDATE detail_sm SET finalized = '".$finalized."' WHERE id = '".$smdtid."'"; 
  $result = $db->query($sql);  



}

function search_stock_movement_finalized($smid){
  global $db;
  $sql = "SELECT * FROM detail_sm WHERE stockmovements_id = '".$smid."' AND finalized = 0"; 

  $result = $db->query($sql);
  return $db->num_rows($result) > 0;

}

function update_status_stockmovement($smid){
  global $db;
  $sql = "UPDATE stockmovements SET finalized = 1 WHERE id = '".$smid."'"; 
  $result = $db->query($sql);

}

function select_receivedgoods($rgid){

  global $db;
  $sql = "SELECT * FROM detail_rg WHERE receivedgoods_id = '".$rgid."' AND finalized = 0"; 

  $result = $db->query($sql);
  $row = $db->fetch_assoc($result);

  return $result;
}

function select_product_po($poid,$prid){

  global $db;
  $sql = "SELECT * FROM detail_po WHERE purchaseorder_id = '".$poid."' AND finalized = '0' AND products_id = '".$prid."'"; 
  
  $result = $db->query($sql);
  $row = $db->fetch_assoc($result);

  return $row;
}

function select_change_status_item_po($poid){

  global $db;
  $sql = "SELECT finalized FROM detail_po WHERE finalized = '0' AND purchaseorder_id = '".$poid."'"; 

  $result = $db->query($sql);
 
 if($db->num_rows($result) > 0){
  return '1';
 }
 else{
  return '0';
 }
}

function select_po_status_change($podtid){

  global $db;
  $sql = "SELECT purchaseorder_id FROM detail_po WHERE id = '".$podtid."'"; 

   $result = $db->query($sql);
  $row = $db->fetch_assoc($result);
  return $row['purchaseorder_id'];
}

function update_status_po($poid){

  global $db;
  $sql = "UPDATE purchaseorder SET finalized = '1' WHERE id = '".$poid."'"; 

  $db->query($sql);
 
  
}

function name_currentacount_type($idtype){
global $db;
 $sql = "SELECT type FROM currentaccount_type WHERE id ='".$idtype."'"; 
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['type'];
}

function name_receivedgoods($idnumbers){
global $db;
 $sql = "SELECT numbers FROM receivedgoods WHERE id ='".$idnumbers."'"; 
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['numbers'];
}

function name_sales($idnumbers){
global $db;
 $sql = "SELECT numbers FROM sales WHERE id ='".$idnumbers."'"; 
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['numbers'];
}

function sum_debit_vendor($idvendor){
global $db;

$sql = "SELECT SUM(debit) FROM currentaccount WHERE vendors_id = '".$idvendor."'";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['SUM(debit)'];


}
 
function sum_credit_vendor($idvendor){
global $db;
$sql = "SELECT SUM(credit) FROM currentaccount WHERE vendors_id = '".$idvendor."'";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['SUM(credit)'];

}
function sum_debit_client($idclient){
global $db;

$sql = "SELECT SUM(debit) FROM currentaccount WHERE clients_id = '".$idclient."'";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['SUM(debit)'];


}
 
function sum_credit_client($idclient){
global $db;
$sql = "SELECT SUM(credit) FROM currentaccount WHERE clients_id = '".$idclient."'";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['SUM(credit)'];

}

function sum_items_rg($idrg){
  global $db;
$sql = "SELECT SUM(price_total) FROM detail_rg WHERE receivedgoods_id = '".$idrg."'";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);

$sql1 = "UPDATE receivedgoods SET total_price ='".$row['SUM(price_total)']."' WHERE id ='".$idrg."'";
$db->query($sql1);

return $row['SUM(price_total)'];
}

function obtaing_tps_sales($prid,$uxb){
global $db;
$sql = "SELECT p.tp, p.id, p.clients_id FROM detail_rg d JOIN purchaseorder p ON d.purchaseorder_id = p.id WHERE d.products_id = '".$prid."' AND d.uxb_rg = '".$uxb."' AND (invoiced = 0 OR invoiced = 3)";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return find_by_sql($sql);
}

function update_item_products($prid,$cbm,$nw,$gw,$volume,$uxb){
global $db;

$sql = "UPDATE products SET cbm ='".$cbm."', uxb = '".$uxb."', netweight = '".$nw."', grossweight = '".$gw."', volume = '".$volume."' WHERE id ='".$prid."'";
$db->query($sql);

}

function select_sales_items($salesid){

  global $db;
  $sql = "SELECT * FROM detail_sales WHERE sales_id = '".$salesid."'"; 

  $result = $db->query($sql);
  

  return $result;
}


function update_rg_items_sales($tpid,$productid){

  global $db;
  $sql = "UPDATE detail_rg SET invoiced = '1', partial_invoice = '0' WHERE purchaseorder_id ='".$tpid."' AND products_id = '".$productid."'";

  $db->query($sql);

  
}

function update_rg_items_sales_0($tpid,$productid){

  global $db;
  $sql = "UPDATE detail_rg SET invoiced = '0', partial_invoice = '0' WHERE purchaseorder_id ='".$tpid."' AND products_id = '".$productid."'";

  $db->query($sql);

  
}

function update_rg_items_sales_3($tpid,$productid,$qty){

  global $db;
  $sql = "UPDATE detail_rg SET invoiced = '3', partial_invoice ='".$qty."' WHERE purchaseorder_id ='".$tpid."' AND products_id = '".$productid."'";

  $db->query($sql);

  
}

function select_rg_items($tpid,$productid){

  global $db;
  $sql = "SELECT * FROM detail_rg WHERE purchaseorder_id = '".$tpid."' AND products_id = '".$productid."' "; 

  $result = $db->query($sql);
  

  $row = $db->fetch_assoc($result);

  return $row;
}


function sum_items_sales($salesid,$comission,$currencyrate){
  global $db;
$sql = "SELECT SUM(price_total_sales) FROM detail_sales WHERE sales_id = '".$salesid."'";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);

$total_comission = $row['SUM(price_total_sales)'] * $comission / '100';
$total_sc = sum_rmb_sc($salesid);
$total = $row['SUM(price_total_sales)'] + $total_comission + $total_sc;

$total_usd = $total / $currencyrate;




$sql1 = "UPDATE sales SET totalprice_rmb ='".$total."', totalprice_usd = '".$total_usd."' WHERE id ='".$salesid."'";
$db->query($sql1);

return $total;
}



function charge_po_items_excel($cusalias,$cusid){

global $db;
$sql = "SELECT * FROM products p JOIN clientcode c ON c.clientcode = '".$cusalias."' AND c.clients_id = '".$cusid."' where c.products_id = p.id"; 

  $result = $db->query($sql);
  
 $row = $db->fetch_assoc($result);

  return $row;
  

}
function select_sale($id){
global $db;

$sqlQuery = "SELECT * FROM sales WHERE id = '".$id."'";
$result = $db->query($sqlQuery);
$row = $db->fetch_assoc($result);

return $row;


}

function container_sales_excel($id)
{

global $db;

$sqlQuery = "SELECT * FROM container_sales WHERE sales_id = '".$id."'";
$result = $db->query($sqlQuery);


return $result;



}

function items_sales_excel($id)
{

global $db;

$sqlQuery = "SELECT * FROM detail_sales d JOIN products p ON p.id = d.products_id JOIN units u JOIN purchaseorder t WHERE d.sales_id = '".$id."' AND u.id = p.units_id AND t.id = d.tp";
$result = $db->query($sqlQuery);


return $result;



}

function items_sc_sales($idsales)
{

global $db;

$sqlQuery = "SELECT * FROM shipping_cost WHERE sales_id = '".$idsales."'";
$result = $db->query($sqlQuery);

return $result;

}

function itemproducts()
{

global $db;

$sqlQuery = "SELECT * FROM clientcode";
$result = $db->query($sqlQuery);

return $result;

}


function insertstock($idcli,$code,$idpr)
{

global $db;

$sqlQuery = "INSERT INTO stock (qty,stock_deposit_id,products_id,clients_id,codeclient) VALUES ('0','1','".$idpr."','".$idcli."','".$code."')";
 
 $db->query($sqlQuery);



}

function sum_rmb_sc($idsales){
global $db;
$sql = "SELECT SUM(price_rmb) FROM shipping_cost WHERE sales_id = '".$idsales."'";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['SUM(price_rmb)'];

}

function sum_usd_sc($idsales){
global $db;
$sql = "SELECT SUM(price_usd) FROM shipping_cost WHERE sales_id = '".$idsales."'";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row['SUM(price_usd)'];

}


function sum_rmb_sc_sales($idsales){
global $db;
$sql = "SELECT SUM(price_rmb) FROM shipping_cost WHERE sales_id = '".$idsales."'";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);
return $row;

}

function finalized_sales($id){

  global $db;
  $sql = "UPDATE sales SET finalized = '1' WHERE id ='".$id."'";

  $db->query($sql);

  
}

function finalized_sales_0($id){

  global $db;
  $sql = "UPDATE sales SET finalized = '0' WHERE id ='".$id."'";

  $db->query($sql);

  
}



function delete_currentacount_sales($id){

  global $db;
  $sql = "DELETE FROM currentaccount where sales_id ='".$id."'";

  $db->query($sql);

  
}

function select_rg_items_3_0($tpid,$productid,$uxb){

  global $db;
  $sql = "SELECT * FROM detail_rg WHERE purchaseorder_id = '".$tpid."' AND products_id = '".$productid."' AND invoiced = 3 AND uxb_rg = $uxb"; 

  $result = $db->query($sql);
  

  

  return $result;
}

function select_rg_items_0_0($tpid,$productid,$uxb){

  global $db;
  $sql = "SELECT * FROM detail_rg WHERE purchaseorder_id = '".$tpid."' AND products_id = '".$productid."' AND invoiced = 0 AND uxb_rg = $uxb"; 

  $result = $db->query($sql);
  

  

  return $result;
}


function update_rg_items_sales_1($id){

  global $db;
  $sql = "UPDATE detail_rg SET invoiced = '1', partial_invoice = '0' WHERE id ='".$id."'";

  $db->query($sql);

  
}

function update_rg_items_sales_3_1($id,$partial){

  global $db;
  $sql = "UPDATE detail_rg SET invoiced = '3', partial_invoice = '".$partial."' WHERE id ='".$id."'";

  $db->query($sql);

  
}

function sum_total_comission($salesid,$comission){
  global $db;
$sql = "SELECT SUM(price_total_sales) FROM detail_sales WHERE sales_id = '".$salesid."'";
$result = $db->query($sql);
$row = $db->fetch_assoc($result);

$total_comission = $row['SUM(price_total_sales)'] * $comission / '100';



return $total_comission;
}

function items_currentaccount($idcustomer)
{

global $db;

$sqlQuery = "SELECT * FROM currentaccount WHERE clients_id = '".$idcustomer."'";
$result = $db->query($sqlQuery);

return $result;

}

function select_products_exports($idcustomer)
{

global $db;

$sqlQuery = "SELECT *, P.id AS pid FROM products P JOIN clientcode C ON P.id = C.products_id JOIN packaging PA ON P.packaging_id = PA.id JOIN price_type PRT ON P.price_type_id = PRT.id JOIN units U ON P.units_id = U.id JOIN moneys M ON P.moneys_id = M.id WHERE C.clients_id = '".$idcustomer."' AND P.openclose='1' AND C.openclose= '1'";
$result1 = $db->query($sqlQuery);
$row = $db->fetch_assoc($result);
//return $row;

return $result1;

}

function select_products_exports_all($idcustomer)
{

global $db;

$sqlQuery = "SELECT *, P.id AS pid FROM products P JOIN packaging PA ON P.packaging_id = PA.id JOIN price_type PRT ON P.price_type_id = PRT.id JOIN units U ON P.units_id = U.id JOIN moneys M ON P.moneys_id = M.id WHERE P.openclose='1' AND C.openclose= '1'";
$result1 = $db->query($sqlQuery);
$row = $db->fetch_assoc($result);
//return $row;

return $result1;

}

function select_cat_sub($id)
{

global $db;

$sqlQuery = "SELECT *, c.name AS cname, s.name as sname FROM productscatsub p JOIN categories c ON p.categories_id = c.id JOIN subcategories s ON p.subcategories_id = s.id where p.products_id = '".$id."'";
$result2 = $db->query($sqlQuery);
$row = $db->fetch_assoc($result2);
return $row;



}

function select_cat_sub_1($id)
{

global $db;

$sqlQuery = "SELECT *, c.name AS cname, s.name as sname FROM productscatsub p JOIN categories c ON p.categories_id = c.id JOIN subcategories s ON p.subcategories_id = s.id where p.products_id = '".$id."'";
$db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;



}

function select_maker_export($idmaker){

  global $db;
  $sql = "SELECT * FROM vendors WHERE id = '".$idmaker."' "; 

  $result = $db->query($sql);
  
  return $result;
}


function select_maker_export_all(){

  global $db;
  $sql = "SELECT * FROM vendors"; 

  $result = $db->query($sql);

  return $result;
}

function select_customer($idcustomer){

  global $db;
  $sql = "SELECT * FROM clients WHERE id = '".$idcustomer."'"; 

  $result = $db->query($sql);
  $row = $db->fetch_assoc($result);
  return $row;
  
}


function select_units_rd($prid){
  global $db;
    $sql = "SELECT u.unittype FROM products p  JOIN units u ON p.units_id = u.id  where p.id = '".$prid."'";
    
    $result = $db->query($sql);
    $row = $db->fetch_assoc($result);
     return $row['unittype'];
   

}

function select_currency($id){

  global $db;
  $sql = "SELECT * FROM moneys WHERE id = '".$id."'"; 

  $result = $db->query($sql);
  $row = $db->fetch_assoc($result);
  return $row;
  
}

function select_subcategorie($cate){

  global $db;
  $sqlQuery = "SELECT id,name,categories_id FROM subcategories WHERE categories_id = '".$cate."'";
  $result1 = $db->query($sqlQuery);
//$row = $db->fetch_assoc($result);
//return $row;

  return $result1;
  
}

function select_subcategorie1($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table)."");
   }
}


function select_subcategorie3($name){

  global $db;
  $sql = "SELECT * FROM subcategories WHERE name = '".$name."'"; 

  $result = $db->query($sql);
  $row = $db->fetch_assoc($result);
  return $row;

  //return $result;
}

function select_categorie_products($idproduct){
      
      global $db;
      $sqlQuery = "SELECT * FROM productscatsub WHERE products_id = '".$idproduct."'";
      $result = $db->query($sqlQuery);
      $row = $db->fetch_assoc($result);
      
      $return = ($db->num_rows($result) > 0) ? true : false;
      return $return;

}

function select_product_rd($prid){

    global $db;
    $sql = "SELECT media_id FROM products where id = '".$prid."'";
    
    $result = $db->query($sql);
    $row = $db->fetch_assoc($result);
     return $row['media_id'];



}
      /*
    $sql ="SELECT D.".$db->escape($name)." as '".$db->escape($table2)."'";
    $sql .=" FROM ".$db->escape($table1)." E"; 
    $sql .=" JOIN ".$db->escape($table2)." D";
    $sql .=" ON E.".$db->escape($id)." = D.id";
    $sql .=" WHERE E.".$db->escape($id)." = 2 LIMIT 1";

    $row = $db->fetch_assoc($sql);
    return $row['clients'];
    
  }
 
/*

$table1,$table2,$id,$name
  function insert_clientscodes($client,$clientcode){
    globaL $db;
    $sql = "INSERT INTO clientscode (";
    $sql .= "clientcode,client_id,products_id)";
    $sql .= " VALUES";
    $sql .= " (".$db->escape($clientcode)).",".$db->escape($client).",29)";
    $result = $db->query($sql);


  }

*/


?>
