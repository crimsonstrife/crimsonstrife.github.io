<?php

if( ! ini_get('date.timezone') )
{
   date_default_timezone_set('GMT');
} 

error_reporting('E_NONE'); //Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.


$db_config_path = '../application/config/database.php';

// Only load the classes in case the user submitted the form
if($_POST) {

	// Load the classes and create the new objects
	require_once('includes/core_class.php');
	require_once('includes/database_class.php');

	$core = new Core();
	$database = new Database();


	// Validate the post data
	if($core->validate_post($_POST) == true)
	{

		// First create the database, then create tables, then write config file
		if($database->create_database($_POST) == false) {
			$message = $core->show_message('error',"The database could not be created, please check your settings.");
		} else if ($database->create_tables($_POST) == false) {
			$message = $core->show_message('error',"The database tables could not be created, please check your settings.");
		} else if ($core->write_config($_POST) == false) {
			$message = $core->show_message('error',"The database configuration file could not be written, please chmod application/config/database.php file to 777");
		} else if ($core->write_config_config($_POST) == false) {
			$message = $core->show_message('error',"The config file could not be written, please chmod application/config/config.php file to 777");
		}
		// If no errors, redirect to registration page
		if(!isset($message)) {
		  $redir = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
      $redir .= "://".$_SERVER['HTTP_HOST'];
      $redir .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
      $redir = str_replace('install/','',$redir); 
			header( "refresh:5; url=" . $redir ) ;
		}

	}
	else {
		$message = $core->show_message('error','Not all fields have been filled in correctly. The host, username, password, and database name are required.');
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	
		<!--Web description and keywords-->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="TaskFeed - Meant For TeamWork :: by A.B.M Shahnewaz Rifat" />
		<meta name="description" content="Taskfeed is developed by me (A.B.M Shahnewaz Rifat) as one of my dream project. Taskfeed is built with a lot of passion and self realizations. Over the years I've used many project management tools
and while they served the purpose right, I found them bloated or overly complicated at times.I realized that, Task/Project Management softwares can do a world of good if there was a Team Environment. A way to keep it simple, clean and elegant.
Taskfeed is meant for TeamWork.Create your team and manage your projects together! Get notified on the go and have fun organizing your ever so cluttered 
project queue. More features will be added in the future based on user requests. Since this is a single handed project, it may take longer than usual to bring
new and exciting features...Taskfeed Currently features (but is not limited to) 
:Invitation to join Taskfeed/Your Project by email.Creating your own team to work with.Apps for To-Do, Milestones and Discussion.Attachment in discussion.Threaded Discussion View.Email Notification for all major events on a project.Runs on simple Apache Server.User Roles as Project Owner and Contractors.Managing your priority Queue. Task Calendar.Public 3D Business Card! Task and Project Management Panels.The public business card will help to create an eco-system where you can find professional contractors and use Taskfeed as a freelancing resource in future!" />
	<meta content="A.B.M Shahnewaz Rifat" name="author" />
		<meta name = "viewport" content = "width=device-width, initial-scale=1">
		
		<!-- Favicon -->
		<link rel="shortcut icon" href="favicon.ico"/>
		
	
		<title>Install | TaskFeed [PE]</title>
		
		<!-- Fonts -->
		
		
		
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="assets/css/style.css" >
		<link rel="stylesheet" type="text/css" href="assets/css/preset.css" >
		<link rel="stylesheet" type="text/css" href="assets/css/style-responsive.css" >	


		

		<!-- IE -->
		<!--[if lt IE 9]>
			<link href = "assets/styles/ie/ie.css" rel = "stylesheet" type = "text/css" media = "screen" />
			<script src = "assets/scripts/ie/html5.js" type = "text/javascript"></script>
		<![endif]-->		
		


		
	</head>


	<body data-spy="scroll" data-target=".bs-docs-sidebar">
		<div class="container">

   <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
						<ul class="nav">
						
						
							<a class="brand" href="#">Taskfeed :: Meant For TeamWork (V1.46)</a>
						
						
							<li class ='nav-menu-item active'>
								<a href ='#'>Install</a>
							</li>

						</ul>
					</div>
				</div>
			</div>

    <!-- Docs nav
    ================================================== -->
    <div class="row">


	<div class="span3" style="background: #D6D6D6; min-height: 870px; padding-top:40px;">
	    <p class="alert alert-info" >Install Taskfeed :: Meant For TeamWork</p>
	</div>


	<div class="span9" style=" min-height: 430px;" >



 
    <?php if(is_writable($db_config_path)){?>

		  <?php if(isset($message)) {echo '<p class="alert alert-error">' . $message . '</p>';}?>

<div class="bs-docs">
	<p class="alert alert-warning" > Please wait a few seconds after clicking the install button.</p>
	<hr class="soften" />
		<h5 style="color:#0088CC">Please Fill up the following form carefully.</h5>
	<hr class="soften" />

<div class="alert alert-deep" >
	<form class="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	    <div class="control-group alert alert-deep">
	    	<label class="control-label" for="inputHost">Hostname</label>
	    	<div class="controls">
	    		<input name="hostname" type="text" id="inputHost" placeholder="Localhost" >
	    	</div>
	    	<p class="text-success" >This is normally your localhost. Or you can just put your domain name here.</p>
	    </div>

	    <div class="control-group alert alert-deep">
	    	<label class="control-label" for="inputUrl">Base Url</label>
	    	<div class="controls">
	    		<input name="basepath" type="text" id="inputUrl" placeholder="http://www.site.com/folder/" >
	    	</div>
	    	<p class="text-success" >Type in the path of your installation here. This is important. And please
	    		include the trailing slash at the end of the path ('/').</p>
	    </div>

	    <div class="control-group alert alert-deep">
	    	<label class="control-label" for="inputUser">Database Username</label>
	    	<div class="controls">
	    		<input name="username" type="text" id="inputUser" placeholder="Database Username" >
	    	</div>
	    	<p class="text-error" >Your database username. The software will not function at all without a correct
	    		database username.</p>

	    </div>

	    <div class="control-group alert alert-deep">
	    	<label class="control-label" for="inputPass">Database Password</label>
	    	<div class="controls">
	    		<input name="password" type="text" id="inputPass" placeholder="Database Password" >
	    	</div>
	    	<p class="text-error" >Your database password. The software will not function at all without a correct
	    		database password.</p>
	    </div>

	    <div class="control-group alert alert-deep">
	    	<label class="control-label" for="inputDb">Database Name</label>
	    	<div class="controls">
	    		<input name="database" type="text" id="inputDb" placeholder="your_db_name" >
	    	</div>
	    	 <p class="text-error" >The database to be used to install the software.If you have not already created a 
	    	 	database,please go ahead and create one and then come back to the installer.</p>
	    </div>	    	    	    

	    <div class="control-group">
	    	<div class="controls">
	    		<input type="submit" class="btn btn-primary" value="Install Now" />
	    	</div>
	    </div>
	   </form>
</div>


	  <?php } else { ?>
      <p class="alert alert-error">Please make the application/config/database.php file writable. <strong>Example</strong>:<br /><br /><code>chmod 777 application/config/database.php</code></p>
	  <?php } ?>

</div>



	</div> <!-- span 9 ends -->
	</div><!-- row ends here -->
</div> <!-- Container Ends here... -->
    <!-- Footer
    ================================================== -->
    <footer class="footer">
      <div class="container">
        <p>&copy; 2013-2014, All rights reserved by.</p><br />
        <p>Designed &amp; Developed By : A.B.M Shahnewaz Rifat</p>
      </div>
    </footer>


