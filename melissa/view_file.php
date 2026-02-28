<?php

$date = "";

if(isset($_GET["date"])){
    $date = htmlentities($_GET["date"]);
    $linkedin_file = trim(htmlentities(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/static/files/linkedin_$date.csv")));
    if(!$linkedin_file) {
        header("location:/melissa/display_message");
    }
    
    $linkedin_file = str_replace("+", "", $linkedin_file); //so I can be able to search for phone numbers
    $linkedin_file = str_replace(" ", "", $linkedin_file); //so I can be able to search for phone numbers
    $all_active_accounts = explode("\n", $linkedin_file);
    $all_active_accounts = array_map('strtolower', $all_active_accounts);
    $all_active_accounts = array_map('trim', $all_active_accounts);
} else {
    header("location:/melissa/display_message");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkedin Rentals View File</title>

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
    <h1 style = "text-align:center;color:#ff9100"><i class="fa fa-linkedin"></i> Linkedin Rentals - WANL(Melissa)</h1>
    <div>
        <div style="text-align:center;margin:21px 12px;border-bottom:3px solid #888;padding:18px 12px">
            Below is a file showing all active accounts for the week on WANL(Melissa's company). <br /><br />
        </div>

        <h3 style = "text-align:center">
            Week: <?=ucfirst(explode("_", $date)[0])?> to <?=ucfirst(explode("_", $date)[1])?>
        </h3>

        <div style="margin: 24px; 12px">
        <?php
            $i=0; //var_dump($_POST);  echo "<br /><br /> Post_of_Managers_account";
            if(isset($_POST["managers_accounts"])){
                //echo $_POST["managers_accounts"]; echo "<br /><br />";
                $managers_referrals = $_POST["managers_accounts"];
                $managers_referrals = trim($managers_referrals);
                $managers_referrals = preg_replace("/[0-9]+\.|[0-9]+\)|[\)]/", "", $managers_referrals);
                $managers_referrals = str_replace("+", "", $managers_referrals);
                $managers_referrals = str_replace(" ", "", $managers_referrals);
                $managers_referrals_arr = explode("\n", $managers_referrals);
                $managers_referrals_arr = array_map('strtolower', $managers_referrals_arr);
                $managers_referrals_arr = array_map('trim', $managers_referrals_arr);
                $managers_referrals_arr = array_unique($managers_referrals_arr);

                //echo "managers_ref_arr: "; print_r($managers_referrals_arr); echo "<br />";
                
                $man_acct_no = 0;
                $sub_all_act = "";
                foreach ($all_active_accounts as $all_act_acct) {
                    $i++;
                    
                    if(strlen($all_act_acct) > 10) {
                        $sub_all_act = substr($all_act_acct, 0, -6);
                        $sub_all_act = trim($sub_all_act);                    
                        
                        if (preg_grep("/$all_act_acct|$sub_all_act/", $managers_referrals_arr)){          
                            $man_acct_no += 1;
                            echo "<span style='color:green;font-weight:bold'>", $i, ".) ", $all_act_acct, " <i class='fa fa-check-circle'></i></span><br />";
                        } else {
                            echo "<span>", $i, ".) ", $all_act_acct, "</span><br/>";
                        }
                    } else {
                        echo "<span>", $i, ".) ", $all_act_acct, "</span><br/>";
                    }
                }
                             
            echo "<br /><br /><b>Managers Total Active Referrals:</b> ", $man_acct_no;
            } else {
                foreach($all_active_accounts as $all_act_acct) {
                    $i++;
                    echo $i, ".) ", $all_act_acct, "<br />";
                }
            }
            echo "<br /><br /><b>Total active accounts for the week:</b> ", count($all_active_accounts);
        ?>
            <div style="margin:60px 3px"><a class="long-action-button" style="background-color:blue;color:#fff;" href="/melissa/active_accounts/<?=$date?>"> <i class="fa fa-search"></i> &nbsp; Search by managers >> </a></div>
        </div>
    </div>
</body>
</html>