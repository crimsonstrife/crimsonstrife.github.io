<?php 

	foreach ($profile_data as $pdata) {

		$fname = $pdata->user_fname;

		$lname = $pdata->user_lname;

		$designation = $pdata->user_designation;

		$phone = $pdata->user_phone;

		$address_1 = $pdata->user_address_1;
		$address_2 = $pdata->user_address_2;



		$old_image_name = $pdata->user_avatar_img;
		$old_image_ext = $pdata->user_avatar_ext;


	}

?>
	<?php if(!empty($message)) { ?>

			<?php echo $message; ?>

	<?php } ?>

	<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>

			<div class="portlet box blue">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-reorder"></i>
									<span class="hidden-480">Update Business Card</span>
								</div>
							</div>


							<div class="portlet-body form">

		<!-- BEGIN LOGIN FORM -->
			<?php echo form_open_multipart(current_url(), 'form-horizontal"'); ?>


			<h3 class="form-title">Business Card Front Side Information</h3>

			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label">First Name</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-text-height"></i>
						<input class="m-wrap placeholder-no-fix tooltips" type="text" value="<?php echo $fname; ?>" name="user_fname"
						title="Your First Name."  />
					</div>
				</div>
			</div>


			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label ">Last Name</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-text-height"></i>
						<input class="m-wrap placeholder-no-fix tooltips" type="text" value="<?php echo $lname; ?>" name="user_lname"
						title="Your Last Name."  />
					</div>
				</div>
			</div>


			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label">Designation/Expertise/Profession</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-text-height"></i>
						<input class="m-wrap placeholder-no-fix tooltips" type="text" value="<?php echo $designation; ?>" name="user_designation"
						title="Your Professional Designation/Expertise/Profession."  />
					</div>
				</div>
			</div>


			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label">Phone Number</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-text-height"></i>
						<input class="m-wrap placeholder-no-fix tooltips" type="text" value="<?php echo $phone; ?>" name="user_phone"
						title="Your Business Phone number (optional)."  />
					</div>
				</div>
			</div>

			<hr />

			<h3 class="form-title">Business Card Back Side Information</h3>

			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label">Address Line 1</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-text-height"></i>
						<input class="m-wrap large placeholder-no-fix tooltips" type="text" value="<?php echo $address_1; ?>" name="user_address_1"
						title="Adress Line 1."  />
					</div>
				</div>
			</div>

			<div class="control-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label">Address Line 2</label>
				<div class="controls">
					<div class="input-icon left">
						<i class="icon-text-height"></i>
						<input class="m-wrap large placeholder-no-fix tooltips" type="text" value="<?php echo $address_2; ?>" name="user_address_2"
						title="Address Line 2 Containing your city and country."  />
					</div>
				</div>
			</div>

			<hr />

			<h3>Your Avatar/Profile Picture </h3><p class="text-error">(Maximum Height : 300px, Maximum Width : 300px)</p>

			<div class="control-group">
			  <label class="control-label">Your Avatar</label>
			  <div class="controls">
			    <div class="fileupload fileupload-new" data-provides="fileupload">

			      <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
			          <?php if(!empty($old_image_name)){ ?>
			            <img src="<?php echo base_url(); ?>uploads/profile_data/<?php echo $old_image_name.$old_image_ext; ?>" alt="<?php echo $fname." ".$lname; ?>" />
			          <?php } else{ ?>
			            <img src="<?php echo base_url()."uploads/profile_data/no_image.gif" ?>" alt="<?php echo $fname." ".$lname; ?>" />
			          <?php } ?>
			      </div>

			      <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
			      
			      <div>
			          <span class="btn btn-file"><span class="fileupload-new">Select image</span>
			          <span class="fileupload-exists">Change</span>
			              <input type="file" class="default" name="userfile" /></span>
			              <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
			      </div>
			    </div>
			   </div>
			</div><br />

		<?php if (!empty($old_image_name)) { ?>
			
			<input type="hidden" name="old_image_name" value="<?php echo $old_image_name; ?>" />
			<input type="hidden" name="old_image_ext" value="<?php echo $old_image_ext; ?>" />
		<?php } ?>


			<div class="form-actions">
				<input type="submit" name="update_profile" class="btn green" value="Update Information" />          
			</div>

		<?php echo form_close(); ?>


							</div>
</div>

