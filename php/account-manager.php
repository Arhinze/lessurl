<?php
ini_set("session.use_only_cookies", 1);
include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

$data = false;

function generate_unique_id(){
    //generate random appended_id:
    $code_array = [0,1,2,3,4,5,6,7,8,9];
    shuffle($code_array);
    $code_out = "";
    
    $arr = [0,1,2,3,4,5];
    shuffle($arr);
    
    foreach($arr as $a){
        $code_out .= $code_array[$a];
    }
    
    $user_unique_id = time()."_".$code_out;
    return $user_unique_id;
}

$code_out = explode("_", generate_unique_id())[1];

if(isset($_COOKIE["google_user_email"])) {
    $nge = htmlentities($_COOKIE["google_user_email"]);
    $nge_stmt = $pdo->prepare("SELECT * FROM stethoverflow_users WHERE user_email = ? LIMIT ?, ?");
    $nge_stmt->execute([$nge, 0, 1]);
    $nge_data = $nge_stmt->fetch(PDO::FETCH_OBJ);

    if($nge_data) {//that means user already exists, just delete google cookies, possible old unique_id cookie and set new unique_id cookie:
        //delete google cookies:
        setcookie("google_user_name", "", time()-(48*3600), "/");
        setcookie("google_user_email", "", time()-(48*3600), "/");
        setcookie("google_user_picture", "", time()-(48*3600), "/");

        //delete possible old unique_id cookie:
        setcookie("unique_id", "",  time()-(48*3600), "/");

        //set new unique_id cookie:
        setcookie("unique_id", $nge_data->unique_id,  time()+(48*3600), "/");
    } else {//that means user doesn't exist yet, create(insert) new user and delete google cookies
        //generate_unique_id()
        $user_unique_id = generate_unique_id();

        //create(insert) new user
        $create_user_stmt = $pdo->prepare("INSERT INTO stethoverflow_users(entry_date, real_name, `password`, user_email, profile_picture, unique_id) VALUES(?,?,?,?,?,?)");
        $create_user_stmt->execute([date("Y-m-d H:i:s", time()), htmlentities($_COOKIE["google_user_name"]), "Goo--gle1",htmlentities($_COOKIE["google_user_email"]),htmlentities($_COOKIE["google_user_picture"]),$user_unique_id]);

        //delete google cookies:
        setcookie("google_user_name", "", time()-(48*3600), "/");
        setcookie("google_user_email", "", time()-(48*3600), "/");
        setcookie("google_user_picture", "", time()-(48*3600), "/");

        //delete possible old unique_id cookie:
        setcookie("unique_id", $user_unique_id,  time()-(48*3600), "/");

        //set new unique_id cookie:
        setcookie("unique_id", $user_unique_id,  time()+(48*3600), "/");
    }
}

if((isset($_COOKIE["unique_id"]))){
    $user_unique_id = htmlentities($_COOKIE["unique_id"]);

    $stmt = $pdo->prepare("SELECT * FROM stethoverflow_users WHERE unique_id = ? LIMIT ?, ?");
    $stmt->execute([$user_unique_id, 0, 1]);
  
    $data = $stmt->fetch(PDO::FETCH_OBJ);
} else {
    $user_unique_id = generate_unique_id();
    setcookie("unique_id", $user_unique_id,  time()+(48*3600), "/");
}

// then call 'if data(){ ... }' for all necessary dashboard related page.

//to know if to tell user to sign in or view profile:
$profile_or_sign_in = "";
$profile_picture = $site_url."/static/images/user_icon.png";
if ($data) {
    $profile_picture = $data->profile_picture;
    $profile_or_sign_in = <<<HTML
        <!-- profile menu for logged in user starts -->
        <div class="profile_menu" style="left:0">
            <div style="padding:21px 12px 0 12px">
                <div class="profile_image_div">
                    <a href="$profile_picture"><img src="$profile_picture" class="profile_image"/></a>
                </div>
                <div style="margin:6px;font-size:16px"><b>$data->real_name</b></div>
            </div>
            <div style="margin:15px;border-top:1px solid #888">
                <div><a href="/messages" style="color:#000"><i class="fa fa-commenting-o"></i> &nbsp; Messages</a></div>
                <div><a href="/bookmarks" style="color:#000"><i class="fa fa-bookmark-o"></i> &nbsp; Bookmarks</a></div>
                <div><a href="/drafts" style="color:#000"><i class="fa fa-pencil-square-o"></i> &nbsp; Drafts</a></div>    
                <div id="coming_soon" class="invalid" style="display:none">Feature coming soon</div>
                
                <!-- Logout button -->
            </div>
        </div><!-- profile menu for logged in user ends -->

        <!-- just a dummy div to close profile menu -->
        <div class="profile_menu" style="background-color:#888;opacity:0.1;top:0;right:0;width:30%" onclick="show_div('join_us')"></div>
        <!-- just a dummy div to close profile menu -->

HTML;       
    
} else {
    $profile_or_sign_in = <<<HTML
        <!-- .join_us starts -->
        <div class="join_us">
            <div style="position:relative">
                <div style="position:absolute;float:right;right:12px;top:12px" onclick="show_div('join_us')"><i class="fa fa-times"></i></div>
            </div>
            <div style="font-weight:bold;margin-top:21px">
                Join $site_name
            </div>
            <div>
                <a href="/login"><span class="button" style="padding:6px 21px">Sign up</span></a>
            </div>
            <div>
                <a href="/login"><span class="button" style="padding:6px 21px;border:1px solid #888;background-color:#fff;color:#888">Sign in</span></a>
            </div>
            <div style="text-align:center;border-top:1px solid #888;padding:18px;font-size:12px">
                About Blog Privacy Terms
            </div>
        </div><!-- .join_us ends -->
HTML;

}  

$user_real_name = "Guest user - <a href='/login'>Login</a> to continue";
$user_bio = "Stethoverflow Guest User (2025 - present)";

if ($data) {
    $user_real_name = $data->real_name;
    $user_bio = $data->bio;
}
   
define("PROFILE_OR_SIGN_IN", $profile_or_sign_in);
define("PROFILE_PICTURE", $profile_picture);
define("DATA", $data);