<?php
include('db.php');
// include 'library.php'; // include the library file
// include "classes/class.phpmailer.php"; // include the class name
$output='';
if(isset($_REQUEST['flag']))
{
$json_output=array();
	if($_REQUEST['flag'] == "Register")
	{

		
           $check_exits=mysqli_query($con,"select * from register where email='".mysqli_real_escape_string($con,$_REQUEST['email'])."'");
	  if(mysqli_num_rows($check_exits)==1)
 		{
		    $output = json_encode(array("msg"=>"Already Registered !!","status"=>"0"));				
                }
		else
		{
		    $date = date('d-m-Y H:i:s');

                    $insert_new=mysqli_query($con,"insert into register values('','".mysqli_real_escape_string($con,$_REQUEST['name'])."','".mysqli_real_escape_string($con,$_REQUEST['email'])."','".mysqli_real_escape_string($con,$_REQUEST['password'])."','".mysqli_real_escape_string($con,$_REQUEST['mobile'])."','".mysqli_real_escape_string($con,$_REQUEST['address'])."','$date','1')");
 

		    if($insert_new)
			{
				$output= json_encode(array("msg"=>"Registered Successfully !!","status"=>"1"));
                        }
		}		

   	}
	else if($_REQUEST['flag'] == "Login")
	{

		$check_exits=mysqli_query($con,"select * from register where mobile='".mysqli_real_escape_string($con,$_REQUEST['mobile'])."' and password='".mysqli_real_escape_string($con,$_REQUEST['password'])."'");
		if(mysqli_num_rows($check_exits)==1)
		{
		   $user_details=mysqli_fetch_assoc($check_exits);

                   $Get_Details = array(

									'name'=>$user_details['name'],

                                    'email'=>$user_details['email'],	

									'mobile'=>$user_details['mobile'],

									'address'=>$user_details['address']

									

								);									

			$output = json_encode(array("User_Details"=>$Get_Details,"status"=>"1"));
		}
		else
		{
$Get_Details = array('msg'=>"Invalid Credentials");
			$output = json_encode(array("User_Details"=>$Get_Details,"status"=>"0"));

		}

	}
	else if($_REQUEST['flag'] == "Update")
	{

		$update=mysqli_query($con,"update register set name='".
			mysqli_real_escape_string($con,$_REQUEST['name'])."',email='".mysqli_real_escape_string($con,$_REQUEST['email'])."',password='".mysqli_real_escape_string($con,$_REQUEST['password'])."',mobile='".mysqli_real_escape_string($con,$_REQUEST['mobile'])."',address='".
			mysqli_real_escape_string($con,$_REQUEST['address'])."' where email='".mysqli_real_escape_string($con,$_REQUEST['email'])."'");
	if($update)
{
		 	$output = json_encode(array("msg"=>"Updated Success","status"=>"1"));
                }
         else
         {
                $output = json_encode(array("msg"=>"Not Updated","status"=>"0"));
          }
	}

	else if($_REQUEST['flag'] == "Delete")
	{

		$delete=mysqli_query($con,"delete from register where id='".mysqli_real_escape_string($con,$_REQUEST['id'])."'");
	if($delete)
{
		 	$output = json_encode(array("msg"=>"delete Success","status"=>"1"));
                }
         else
         {
                $output = json_encode(array("msg"=>"Not delete","status"=>"0"));
          }
	}
else if($_REQUEST['flag'] == "Hire")
	{

		$check_exits=mysqli_query($con,"select * from register where type='Hire'");
		if(mysqli_num_rows($check_exits)>=1)
		{
		   $user_details=mysqli_fetch_assoc($check_exits);
                   $Get_Details = array(

									'Type'=>$user_details['type'],
                                    'id'=>$user_details['r_id'],			
                                    'Username'=>$user_details['username'],
									'Email'=>$user_details['email'],
									'Phone'=>$user_details['phone'],
									'status'=>"1"

								);									

			$output = json_encode(array("User_Details"=>$Get_Details,"status"=>"1"));
		}
		else
		{
			$output = json_encode(array("User_Details"=>"Not Found Any One to Hire","status"=>"0"));

		}

	}
else if($_REQUEST['flag'] == "NewProject")
	{

		 $date=date("d/m/Y");
                 $insert_new=mysqli_query($con,"insert into Project values('','$date','".mysqli_real_escape_string($con,$_REQUEST['userId'])."','".mysqli_real_escape_string($con,$_REQUEST['title'])."','".mysqli_real_escape_string($con,$_REQUEST['description'])."','".mysqli_real_escape_string($con,$_REQUEST['amount'])."','".mysqli_real_escape_string($con,$_REQUEST['currencyCode'])."','1')");
	
		    if($insert_new)
			{
				$output= json_encode(array("msg"=>"Submitted Successfully !!","status"=>"1"));
                        }
else
{
$output= json_encode(array("msg"=>"Not Done !!","status"=>"1"));
}
	}
else if($_REQUEST['flag'] == "AllProject")
	{
                 $all=mysqli_query($con,"select * from Project where Uid='".mysqli_real_escape_string($con,$_REQUEST['userId'])."' and status='1'");
	if(mysqli_num_rows($all)>=1)
		{
		    while($project_details=mysqli_fetch_assoc($all))
                      {
                 $count=mysqli_query($con,"select * from Interested where ProjectId='".$project_details['id']."' and status='1'");
                 $value=mysqli_num_rows($count);
                          $Projects[]= array(

									'id'=>$project_details['id'],
                                                                        'date'=>$project_details['pdate'],								

									'uid'=>$project_details['Uid'],

									'title'=>$project_details['PTitle'],

		'des'=>$project_details['PDes'],							
'amount'=>$project_details['PAmount'],
'currency'=>$project_details['PCurrency'],

									'status'=>"1",
'interested'=>$value

								);		
                      }							

			$output = json_encode(array("msg"=>"Project Posted","project_details"=>$Projects,"status"=>"1"));
}
else
{
$output = json_encode(array("msg"=>"No Project Posted yet !!","status"=>"0"));
}
	}
else if($_REQUEST['flag'] == "AllInterested")
	{
                 $all=mysqli_query($con,"select * from Interested where ProjectId='".mysqli_real_escape_string($con,$_REQUEST['pid'])."' and status='1'");
	if(mysqli_num_rows($all)>=1)
		{
		    while($project_details=mysqli_fetch_assoc($all))
                      {
                 $res=mysqli_query($con,"select * from register where r_id='".$project_details['userId']."' and status='1'");
                 $value=mysqli_fetch_assoc($res);
                          $profiles[]= array(

									'id'=>$value['r_id'],
'name'=>$value['username'],
'email'=>$value['email'],							
'phone'=>$value['phone'],	
                                                                        'name'=>$value['username'],								
									'status'=>"1"
								);		
                      }							

			$output = json_encode(array("msg"=>"profiles Interested","profiles_details"=>$profiles,"status"=>"1"));
}
else
{
$output = json_encode(array("msg"=>"Not Interested yet !!","status"=>"0"));
}
	}
else if($_REQUEST['flag'] == "SInterested")
	{
                 $res=mysqli_query($con,"select * from register where r_id='".mysqli_real_escape_string($con,$_REQUEST['id'])."' and status='1'");
                 $value=mysqli_fetch_assoc($res);
                          $profiles[]= array(

									'id'=>$value['r_id'],
                                                                        'name'=>$value['username'],
'email'=>$value['email'],							
'phone'=>$value['phone'],	
									'status'=>"1"
								);		
			$output = json_encode(array("msg"=>"profiles Interested","profiles_details"=>$profiles,"status"=>"1"));
	}
else if($_REQUEST['flag'] == "Projects")
	{
                 $all=mysqli_query($con,"select * from Project where status='1'");
	if(mysqli_num_rows($all)>=1)
		{
                
		    while($project_details=mysqli_fetch_assoc($all))
                      {
 $count=mysqli_query($con,"select * from Interested where ProjectId='".$project_details['id']."' and status='1'");
                 $value=mysqli_num_rows($count);
                          $Projects[]= array(

									'id'=>$project_details['id'],
                                                                        'date'=>$project_details['pdate'],								

									'uid'=>$project_details['Uid'],

									'title'=>$project_details['PTitle'],


'des'=>$project_details['PDes'],									
'amount'=>$project_details['PAmount'],
'currency'=>$project_details['PCurrency'],
'interested'=>$value,
'userinterested'=>$value,
'status'=>"1"

								);		
                      }							

			$output = json_encode(array("msg"=>"Project Posted","project_details"=>$Projects,"status"=>"1"));
}
else
{
$output = json_encode(array("msg"=>"No Project Posted yet !!","status"=>"0"));
}
	}
else if($_REQUEST['flag'] == "ProjectsDetails")
	{
                 $all=mysqli_query($con,"select * from Project where id='".mysqli_real_escape_string($con,$_REQUEST['projectid'])."' and status='1'");
	     if(mysqli_num_rows($all)>=1)
		{
		    while($project_details=mysqli_fetch_assoc($all))
                      {
                          $Projects[]= array(

									'id'=>$project_details['id'],
                                                                        'date'=>$project_details['pdate'],								

									'uid'=>$project_details['Uid'],

									'title'=>$project_details['PTitle'],

'des'=>$project_details['PDes'],									
'amount'=>$project_details['PAmount'],
'currency'=>$project_details['PCurrency'],

									'status'=>"1"

								);		
                      }							

			$output = json_encode(array("msg"=>"Project Posted","project_details"=>$Projects,"status"=>"1"));
               }
              else
              {
                        $output = json_encode(array("msg"=>"No Project Posted yet !!","status"=>"0"));
              }
}
else if($_REQUEST['flag'] == "UPProjectsDetails")
	{
                 $all=mysqli_query($con,"update Project set PTitle='".mysqli_real_escape_string($con,$_REQUEST['title'])."',PDes='".mysqli_real_escape_string($con,$_REQUEST['des'])."',PAmount='".mysqli_real_escape_string($con,$_REQUEST['amount'])."',PCurrency='".mysqli_real_escape_string($con,$_REQUEST['currency'])."' where id='".mysqli_real_escape_string($con,$_REQUEST['projectid'])."'");
	if($all)
{
		 	$output = json_encode(array("msg"=>"Updated Success","status"=>"1"));
                }
         else
         {
                $output = json_encode(array("msg"=>"Not Updated","status"=>"0"));
          }
	}
else if($_REQUEST['flag'] == "UserDetails")
	{
           $all=mysqli_query($con,"select * from register where r_id='".mysqli_real_escape_string($con,$_REQUEST['userid'])."' and status='1'");
	if(mysqli_num_rows($all)>=1)
		{
		    while($user_details=mysqli_fetch_assoc($all))
                      {
                          $users= array(

									'id'=>$user_details['r_id'],
                                    'type'=>$user_details['type'],								
									'username'=>$user_details['username'],
									'email'=>$user_details['email'],									
'phone'=>$user_details['phone'],
'about'=>$user_details['about'],
'location'=>$user_details['location'],
									'status'=>"1"

								);		
                      }							

			$output = json_encode(array("msg"=>"user","user_details"=>$users,"status"=>"1"));
}
else
{
$output = json_encode(array("msg"=>"No user","status"=>"0"));
}
	}
else if($_REQUEST['flag'] == "UPUserDetails")
	{
                 $all=mysqli_query($con,"update register set phone='".mysqli_real_escape_string($con,$_REQUEST['phone'])."',about='".mysqli_real_escape_string($con,$_REQUEST['about'])."',location='".mysqli_real_escape_string($con,$_REQUEST['location'])."' where r_id='".mysqli_real_escape_string($con,$_REQUEST['id'])."'");
	if($all)
         {
		 	$output = json_encode(array("msg"=>"Updated Success","status"=>"1"));
         }
         else
         {
                $output = json_encode(array("msg"=>"Unable To Update !! Some Fields Already Exits","status"=>"0"));
echo mysqli_error($con);
          }
	}
else if($_REQUEST['flag'] == "Interested")
	{
          $all=mysqli_query($con,"select * from Interested where userId='".mysqli_real_escape_string($con,$_REQUEST['userid'])."' and projectId='".mysqli_real_escape_string($con,$_REQUEST['projectid'])."'and status='1'");
	if(mysqli_num_rows($all)==0)
        {
            $date=date("d/m/Y");
            $all=mysqli_query($con,"insert into Interested values('','$date','".mysqli_real_escape_string($con,$_REQUEST['userid'])."','".mysqli_real_escape_string($con,$_REQUEST['projectid'])."','1')");
	if($all)
         {
		 	$output = json_encode(array("msg"=>"Submitted Success","status"=>"1"));
         }
         else
         {
                $output = json_encode(array("msg"=>"Unable To Update !! Some Fields Already Exits","status"=>"0"));
echo mysqli_error($con);
         }
       }
       else
       {
                $output = json_encode(array("msg"=>"Already Interest Submitted","status"=>"0")); 
                echo mysqli_error($con);
        }
     }
else if($_REQUEST['flag'] == "PInterested")
	{
          $all=mysqli_query($con,"select * from Interested where userId='".mysqli_real_escape_string($con,$_REQUEST['userid'])."' and projectId='".mysqli_real_escape_string($con,$_REQUEST['projectid'])."'and status='1'");
	if(mysqli_num_rows($all)==0)
        {
                $output = json_encode(array("msg"=>"Unable To Update !! Some Fields Already Exits","status"=>"0"));
        }
       else
       {
                $output = json_encode(array("msg"=>"Already Interest Submitted","status"=>"0")); 
                echo mysqli_error($con);
       }
     }
	 else if($_REQUEST['flag'] == "CP")
	{
		$check=mysqli_query($con,"select * from register where r_id='".mysqli_real_escape_string($con,$_REQUEST['id'])."'");
		$r=mysqli_fetch_array($check);
		if($r==$_REQUEST['opass'])
		{
            $all=mysqli_query($con,"update register set password='".mysqli_real_escape_string($con,$_REQUEST['pass'])."' where r_id='".mysqli_real_escape_string($con,$_REQUEST['id'])."'");
		if($all)
			 {
				$output = json_encode(array("msg"=>"Updated Success","status"=>"1"));
			 }
			 else
			 {
					$output = json_encode(array("msg"=>"Unable To Update !! Some Fields Already Exits","status"=>"0"));
	echo mysqli_error($con);
			  }
		}
		else
		{
			$output = json_encode(array("msg"=>"Inavlid old Password","status"=>"1"));
		}
	}
else if($_REQUEST['flag'] == "Feedback")

{
$datetime=date('d-m-Y H:i:s');

						$insert_new=mysqli_query($con,"insert into feedback values('','".mysqli_real_escape_string($con,$_REQUEST['userid'])."','".mysqli_real_escape_string($con,$_REQUEST['email'])."','".mysqli_real_escape_string($con,$_REQUEST['title'])."','".mysqli_real_escape_string($con,$_REQUEST['description'])."','1','$datetime')");

						//$lastid=mysqli_insert_id($con);

						

						// $Get_Details[] = array('userID'=>$lastid,

						// 						'teamId'=>$tid,

						// 						'status'=>"1");

						if($insert_new)

						{

$output= json_encode(array("msg"=>"Your Feedback Successfully Recorded...!!!","status"=>"1"));

							

						}


}
else if($_REQUEST['flag'] == "EmailVerify")
{
$check_exits= mysqli_query($con,"select * from register where email='".mysqli_real_escape_string($con,$_REQUEST['email'])."'");
$user_details=mysqli_fetch_assoc($check_exits);
	  
			$output = json_encode(array("msg"=>"Success","Email_validation"=>$user_details['mail_validation']));

}
else if($_REQUEST['flag'] == "ForgetPassword")
{

   $useremail =  $_REQUEST['email'];
   $check_exits= mysqli_query($con,"select * from register where email='$useremail'"); 
   $row = mysqli_fetch_array($check_exits);
$emails = $row['email']; 
if($useremail == $emails)
{
$mail    = new PHPMailer; // call the class
            $mail->IsSMTP();
            $mail->SMTPDebug=1;
            $mail->Host = SMTP_HOST; //Hostname of the mail server
            $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
            $mail->SMTPAuth = true; //Whether to use SMTP authentication
            $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
            $mail->Password = SMTP_PWORD; //Password for SMTP authentication
            $mail->AddReplyTo("demo@csinfotech.co.in", "Hire"); //reply-to address
            $mail->SetFrom("demo@csinfotech.co.in", "Hire"); //From address of the mail
            // put your while loop here like below,
            $mail->Subject = "Welcome To Hire"; //Subject od your mail
            $mail->AddAddress($useremail, $useremail); //To address who will receive this email
            $mail->MsgHTML('Forget Password:-<a href="http://csinfotech.co.in/hire/forgetpassword.php?email='.$useremail.'">Click Here to Change Password</a>');
            $send = $mail->Send(); //Send the mails

       $output = json_encode(array("msg"=>"Success","status"=>"1"));

}
else{

$output = json_encode(array("msg"=>"Not Done","status"=>"0"));
}

}

	echo $output;
}
?>
