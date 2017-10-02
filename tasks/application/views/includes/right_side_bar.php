<?php 
 	$logged_in = $this->session->userdata('authenticated');

 	if ($logged_in!==FALSE) {
 	
?>

<a title="My Dashboard"
	class="icon-btn-blue tooltips span5" 
	href="<?php echo base_url('dashboard') ?>" >
	<i class="icon-dashboard"></i>
	<div>My Dashboard</div>
	<?php if($unread!=0){ ?>
		<span class="badge badge-important"><?php echo $unread; ?></span>
	<?php }  ?>

</a>


<a title="Create A New Project"
	class="icon-btn-green tooltips span5" 
	href="<?php echo base_url('project/new/step1') ?>" >
	<i class="icon-magic"></i>
	<div>New Project</div>
</a>

<a title="Manage My Projects"
	style="margin-left:0"
	class="icon-btn-purple tooltips span5" 
	href="<?php echo base_url('project/manage/projects') ?>" >
	<i class="icon-sitemap"></i>
	<div>Manage Projects</div>
</a>


<a title="Manage My Tasks"
	class="icon-btn-yellow tooltips span5" 
	href="<?php echo base_url('project/manage/tasks') ?>" >
	<i class="icon-tasks"></i>
	<div>Manage Tasks</div>
</a>


<a title="Manage Priority Queue"
	style="margin-left:0"
	class="icon-btn-violet tooltips span5" 
	href="<?php echo base_url('priority/') ?>" >

	<i class="icon-retweet"></i>
	<div>Manage Priority Queue</div>
</a>

<a title="Manage My Team"
	class="icon-btn-pink tooltips span5" 
	href="<?php echo base_url('team/manage') ?>" >
	<i class="icon-group"></i>
	<div>Manage Team</div>

	<?php if($team_invites!=0){ ?>
		<span class="badge badge-important"><?php echo $team_invites; ?></span>
	<?php }  ?>

</a>

<?php } ?>