<?PHP
//Generate Nav from DB
/*
groups_menu - Holds permission id and user id
menu - menu... you know.
*/
$nav_text = "";
$islogged = 0;
if(isset($user) && $user->isLoggedIn()) {
    $islogged = 1;
}

function pagePerms($pageid){
    global $db;
    global $user;
    //hasPerm([2],$user->data()->id)
    $theseperms = $db->query("SELECT * FROM `groups_menus` WHERE menu_id = ?", [$pageid])->results();
    if($db->count() === 0){
        //echo "No perms / Global/public " . $db->count();
        return true;
    }
    foreach($theseperms as $tp){
        //echo "Checking " . $pageid . "=" . $tp->group_id . "<br>";
        if($tp->group_id === "0"){
            //echo "Public ID Found";
            return true;
        }
        if(hasPerm([$tp->group_id],$user->data()->id)){
            //echo "Found perm match";
            return true;
        }
    }
    
    return false;
}
//dump($user->data()->id);
//die();
if(Input::get('dashboard')!="true"){
    //Entire Nav bar can be disabled by passing dashboard=true at the end of any URL in this theme
    
$navresults = $db->query("SELECT * FROM `menus` where parent < 0 and (logged_in = ? OR logged_in = 0) order by display_order", [$islogged])->results();

if(isset($main_nav)){
    foreach($navresults as $pk){
        //echo "Menu #" . $pk->id . "<BR>";
        if(pagePerms($pk->id)){
        //die();
            if($pk->dropdown){ 
                $nav_text .= "<li class='nav-item'>
                            <a class='nav-link collapsed' href='#' data-toggle='collapse' data-target='#collapse" . $pk->id . "'
                                aria-expanded='true' aria-controls='collapse" . $pk->id . "'>
                                <i class='" . $pk->icon_class . "'></i>
                                <span>" . ucwords(str_replace("{{", "", str_replace("}}", "", $pk->label)))  . "</span>
                            </a>
                            <div id='collapse" . $pk->id . "' class='collapse' aria-labelledby='heading" . $pk->id . "'
                                data-parent='#accordionSidebar'>
                                <div class='bg-white py-2 collapse-inner rounded'>";
                                
                $childnav = $db->query("SELECT * FROM `menus` where parent = ? and (logged_in = ? OR logged_in = 0) order by display_order", [$pk->id, $islogged])->results();
                foreach($childnav as $ck){
                    $nav_text .= "<a class='collapse-item' href='/" .  $ck->link . "'>" . ucwords(str_replace("{{", "", str_replace("}}", "", $ck->label)))  . "</a>";
                }
                $nav_text .= "</div>
                            </div>
                        </li>"; 
            }else{
                $nav_text .= "<li class='nav-item'>
                                <a class='nav-link' href='/" . $pk->link . "'>
                                    <i class='" . $pk->icon_class . "'></i>
                                    <span>" . ucwords(str_replace("{{", "", str_replace("}}", "", $pk->label)))  . "</span></a>
                            </li>";
                
            }
        }// End Has perm
    }
}
                   

/*
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
        aria-expanded="true" aria-controls="collapseUtilities">
        <i class="fas fa-fw fa-wrench"></i>
        <span>Utilities</span>
    </a>
    <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Custom Utilities:</h6>
            <a class="collapse-item" href="utilities-color.html">Colors</a>
            <a class="collapse-item" href="utilities-border.html">Borders</a>
            <a class="collapse-item" href="utilities-animation.html">Animations</a>
            <a class="collapse-item" href="utilities-other.html">Other</a>
        </div>
    </div>
</li>
*/


?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-project-diagram"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Business <sup>bud</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
<?=$nav_text;?>    
<?php
/*
    <li class="nav-item active">
        <a class="nav-link" href="/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Home</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/payinvoice.php">
            <i class="far fa-credit-card"></i>
            <span>Pay Invoice</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">
<?PHP if(isset($user) && $user->isLoggedIn()) { ?>
    <li class="nav-item">
        <a class="nav-link" href="/users/logout.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">    
    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Components</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="buttons.html">Buttons</a>
                <a class="collapse-item" href="cards.html">Cards</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Utilities</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="utilities-color.html">Colors</a>
                <a class="collapse-item" href="utilities-border.html">Borders</a>
                <a class="collapse-item" href="utilities-animation.html">Animations</a>
                <a class="collapse-item" href="utilities-other.html">Other</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Addons
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Login Screens:</h6>
                <a class="collapse-item" href="login.html">Login</a>
                <a class="collapse-item" href="register.html">Register</a>
                <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Other Pages:</h6>
                <a class="collapse-item" href="404.html">404 Page</a>
                <a class="collapse-item" href="blank.html">Blank Page</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link" href="charts.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Charts</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">




<?PHP }else{ ?>
    <li class="nav-item">
        <a class="nav-link" href="login.php">
    <i class="fas fa-sign-in-alt"></i>
    <span>Login</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
<?PHP } ?>
*/
?>
<!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
<!-- Sidebar Message -->
    <div class="sidebar-card">
        <img class="sidebar-card-illustration mb-2" src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/img/undraw_rocket.svg" alt="">
        <p class="text-center mb-2">Your bud in business</p>
    </div> 
</ul>
<?PHP
}// End Dashboard disabled NAV
?>