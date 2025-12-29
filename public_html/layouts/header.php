<?php $user = current_user();
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
 ?>

<!DOCTYPE html>
  <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title><?php if (!empty($page_title))
           echo remove_junk($page_title);
            elseif(!empty($user))
           echo ucfirst($user['name']);
            else echo "Inventory System";?>
    </title>
   <script src="libs/js/jquery.dataTables.min.js" defer></script>
<script src="libs/js/dataTables.bootstrap.min.js" defer></script>
    <link rel="stylesheet" href="libs/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="libs/css/main.css" /><link rel="stylesheet" href="libs/bootstrap/3.3.4/css/bootstrap.min.css"/>
     <link rel="stylesheet" href="libs/bootstrap/3.3.6/css/bootstrap.min.css"/>
      <link rel="stylesheet" href="libs/css/main.css" /><link rel="stylesheet" href="libs/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="libs/css/main.css" />
    <link type="text/css" rel="stylesheet" href="libs/jquery/jquery-ui-1.12.1.custom/jquery-ui.min.css" />
    <script src="libs/jquery/1.11.2/jquery.min.js"></script>
    <link rel="stylesheet" href="libs/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <script src="libs/bootstrap/3.3.6/js/bootstrap.min.js"></script>            
   <script src="libs/js/jquery.dataTables.min.js" defer></script>
<script src="libs/js/dataTables.bootstrap.min.js" defer></script>
<script type="text/javascript" src="libs/js/maximizephoto.js"></script>
<link rel="stylesheet" href="libs/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="libs/datatables/datatables.min.css"/>
<script type="text/javascript" src="libs/datatables/datatables.min.js" defer></script>
 <style>
  .highlight {
    background-color: yellow;
  }
  </style>



  </head>
  <body>
  <?php  if ($session->isUserLoggedIn(true)): ?>
    <header id="header">
      <div class="logo pull-left"> NOVA - Inventory </div>
      <div class="header-content">
      <div class="header-date pull-left">
        <strong><?php echo date("d/m/Y  g:i a");?></strong>
      </div>
      <div class="pull-right clearfix">
        <ul class="info-menu list-inline list-unstyled">

          <li class="profile">
            <a href="#" data-toggle="dropdown" class="toggle" id="dtusers" name="dtusers">
              <img src="uploads/users/<?php echo $user['image'];?>" alt="user-image" class="img-circle img-inline">
              <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
            </a>
            <ul class="dropdown-menu" id="dgusers" name="dgusers"id="dtusers" name="dtusers" >
              <li>
                  <a href="profile.php?id=<?php echo (int)$user['id'];?>">
                      <i class="glyphicon glyphicon-user"></i>
                      Profile
                  </a>
              </li>
             <li>
                 <a href="edit_account.php" title="edit account">
                     <i class="glyphicon glyphicon-cog"></i>
                     Config
                 </a>
             </li>
             <li class="last">
                 <a href="logout.php">
                     <i class="glyphicon glyphicon-off"></i>
                     Exit
                 </a>
             </li>
           </ul>
          </li>
        </ul>
      </div>
     </div>
    </header>
    <div class="sidebar">
      <?php if($user['user_level'] === '1'): ?>
        <!-- admin menu -->
      <?php include_once('admin_menu.php');?>

      <?php elseif($user['user_level'] === '2'): ?>
        <!-- Special user -->
      <?php include_once('special_menu.php');?>

      <?php elseif($user['user_level'] === '3'): ?>
        <!-- User menu -->
      <?php include_once('user_menu.php');?>

      <?php endif;?>

   </div>
   <div class="page">

  <div class="container-fluid">
<?php endif;?>

