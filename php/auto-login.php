<?php   global $current_user;
                                $second_website_url = 'http://10.35.0.235/rhino'; // put your second website url
                                $date = $_POST['date_expense'];
                                $user_email = $_POST['user_email_expense'];
                                $user_login = $_POST['user_login_expense'];
                                //$user_email = $current_user->user_email;
                                //$user_login = $current_user->user_login;
 
                                if($user_email != ''){
 
                                    $email_encoded = rtrim(strtr(base64_encode($user_email), '+/', '-_'), '='); //email encryption
                                    $user_login_encoded = rtrim(strtr(base64_encode($user_login), '+/', '-_'), '='); //username encryption
				    $url = $second_website_url.'/sso.php?key='.$email_encoded.'&detail='.$user_login_encoded.'&date='.$date;
                                    //echo '<a href="'.$second_website_url.'/sso.php?key='.$email_encoded.'&detail='.$user_login_encoded.'" target="_blank">Link to second website</a>';
				    echo "<script type='text/javascript'>parent.set_expense_url('" . $url . "');</script>";
 
                        	}?> 