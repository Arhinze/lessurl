<?php
if (isset($_GET["locked"])) {
$date = "";

if(isset($_GET["date"])){
    $date = htmlentities($_GET["date"]);
    
    $linkedin_file = trim(htmlentities(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/static/files/linkedin_$date.csv")));
    if(!$linkedin_file) {
        header("location:/melissa/display_message");
    }
    
    $linkedin_file = str_replace("+", "", $linkedin_file);
    $linkedin_file = str_replace(" ", "", $linkedin_file);
    $all_active_accounts = explode("\n", $linkedin_file);
    $all_active_accounts = array_map('strtolower', $all_active_accounts);
    $all_active_accounts = array_map('trim', $all_active_accounts);
    //echo "<h2>All active accounts:</h2>"; print_r($all_active_accounts);
    
    $managers_referrals = [];
    $output = []; $stripped_off = [];
    $isset_of_ref = false;
    $all_referred_accounts = "";
    
    if(isset($_POST["referred_accounts"])){
        $isset_of_ref = true; //would come in handy later on in html space . .
        $all_referred_accounts = htmlentities($_POST["referred_accounts"]); //preventing sql injection 
        $managers_referrals = trim($all_referred_accounts); //trimming for extra space
        $managers_referrals = preg_replace("/[0-9]+\.|[0-9]+\)|[\)]/", "", $all_referred_accounts); //replacing the accepted number formats with null
        $managers_referrals = str_replace("+", "", $all_referred_accounts);
        $managers_referrals = str_replace(" ", "", $all_referred_accounts);
        $managers_referrals_arr = explode("\n", $managers_referrals);
        $managers_referrals_arr = array_map('strtolower', $managers_referrals_arr);
        $managers_referrals_arr = array_map('trim', $managers_referrals_arr);
        $managers_referrals_arr = array_unique($managers_referrals_arr);
    

        foreach($all_active_accounts as $all_act_acct) { //if(!in_array()) could be used here in place of array_unique
            if(strlen($all_act_acct) > 10) {
                $sub_all_act = substr($all_act_acct, 0, -6);
                $sub_all_act = trim($sub_all_act);
    
                if(preg_grep("/$all_act_acct|$sub_all_act/", $managers_referrals_arr)) {
                    if(!empty($all_act_acct)){ 
                        $output[] = $all_act_acct;
                    } 
                } else {
                    //if(!empty($all_act_acct)){ 
                        $stripped_off[] = $managers_referrals_arr;
                    //}
                }
            }
        }
        
        $i = 0;
    }
} else { //if a date is not specified:
    header("location:/melissa/display_message");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkedin Rentals ~ WANL</title>

    <link rel="stylesheet" href="/static/style.css?$css_version"/>
    <link rel="stylesheet" href="/static/font-awesome-4.7.0/css/font-awesome.min.css"/>


    <style>
        body{
            background-color:#f8f8f8;
            color:#000;/*#888*/
            font-family:tahoma;
            padding:30px 45px;
        }

        .body{
            padding:30px;
        }

        textarea{
            width:90%;
            height:150px;
            border:2px solid #ccc;
            box-sizing:border-box;
            background-color:#f8f8f8;
            border-radius:6px;
            font-size:15px;
            font-family:arial;
            padding:12px 16px;
            margin:15px;
        }

       .long-action-button{
            background-color:#ff9100;
            color:#fff;
            border-radius:5px;
            padding:15px 12px;
            text-align:center;
            width:90%;
            margin: 6px 15px;
        }
    </style>
</head>
<body>
    <h1 style = "text-align:center"><i class="fa fa-linkedin"></i> Linkedin Rentals</h1>
    <div>
        <div style="text-align:center;margin-bottom:24px">
            This program is created for managers to know if their tenants' accounts are still active on WANL(Melissa's company). <br /><br />

            <h3 style = "text-align:center">
                Week: <?=ucfirst(explode("_", $date)[0])?> to <?=ucfirst(explode("_", $date)[1])?>
            </h3>

            <div style="color:#ff9100;font-weight:bold">Accepted numbering formats are: 1. , 1) or 1.)</div>
        </div>
        <div style="text-align:center;font-weight:bold;font-size:18px">
            Input all the emails you wish to seek for.<br />
            (one on each line).
        </div>
    </div>
    <form method = "post" action = "#active_accounts_display">
        <textarea name="referred_accounts"><?=$all_referred_accounts?></textarea>
        <br /><button class="long-action-button" type = "submit"><i class="fa fa-flash"></i> Submit</button><br /><br />
    </form>

    <div style="margin:24px 15px"><a name="active_accounts_display" id="active_accounts_display"></a> 
    <?php
        if($isset_of_ref) {
            echo "<h2 style='text-align:center'>This Manager's Referals' accounts still active for the week are:</h2>";

            if(count($output) == 0) {
                echo "No referrals for this manager the week / Empty field submitted.";
            } else {
                $output = array_unique($output);
                $real_count = count($output);
                if(strpos($date, "UR")) {
                    echo "<h3>*Lifted Restrictions*: $real_count</h3><br />";
                } else {
                    echo "<h3>*Normal Rent*: $real_count</h3><br />";
                }
                echo "<div id='active_emails'>";
                foreach($output as $out_put_) {
                    $i += 1;
                    echo "".$out_put_."<br />";
                }
                echo "<br />$i x $5 = $".($i*5);
                echo "</div>";
            
                echo "<br /><br /><b>Total number is: $i.</b> &nbsp; &nbsp; &nbsp; <!--<span onclick='copyEmails()'>Copy Emails <i class='fa fa-copy'></i></span>-->"; 

                //$stripped_off_new = array_unique($stripped_off);
                //$i2 = 0; 
                //echo "<h3>*Removed Accounts:*</h3>";
                //echo "<div id='active_emails'>";
                //foreach($stripped_off as $str_off) {
                //    $i2 += 1;
                //    echo "".$str_off."<br /> ";
                //}
                //echo "</div>";
            
                //echo "<br /><br /><b>Total accounts stripped off: $i2.</b> &nbsp; &nbsp; &nbsp; <!--<span onclick='copyEmails()'>Copy Emails <i class='fa fa-copy'></i></span>-->"; 
            }
    ?>
        <form>
            
        </form>
        <form method="post" action="/melissa/view_file/<?=$date?>">
            <input type="hidden" name="managers_accounts" value="<?=$all_referred_accounts?>"/>
            <!-- $managers_referrals_arr-->
            
            <button class="long-action-button" style="background-color:blue;color:#fff" type="submit"> <i class="fa fa-file"></i> View on File >> </button>
        </form>
   <?php
        }    
    ?>
    </div>

    <script>
        function copyEmails(){
            x = document.getElementById("active_emails").innerHTML;
            x = x.replaceAll("<br>", "");
            //x = x.replaceAll("</b>", "");
            //x = x.replaceAll("<br>", "");
            //navigator.clipboard.writeText(x.value);
            const textArea = document.createElement('textarea');
            textArea.value = x;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);

            alert("copied emails successfully");
        }
    </script>
</body>
</html>

<?php
    } else {
        echo "<div style='margin-top:30%;text-align:center;font-weight:bold;font-size:60px'>Access locked ): ~ <a href='https://wa.me/2348106961530' style='color:#ff9100'><br />contact the developer</a> <br />to continue using.</div>";
    }
?>