<div class="collabroominfo">
	<div class="collabsec wrcollabinfo">
		<ul class="collabroominfolist">
			<li class="odd first">
				<div>
					<?php $alerts=edd_get_alerts(get_current_user_id()); ?>
					<span class="collabroominfolabel">Workroom Email Address:</span>
					<input type="text" 
						value="<?php echo get_post_meta($params,'Workroom_Email_Address',true).'@'.get_email_domain(); ?>" 
						class="" />
					
					<div class="clear"></div>
				</div>
			</li>
			<li class="even">
				<div>
					<span class="collabroominfolabel">Collaboration Room ID:</span>
					<span class="collabroominfooutput"><?php echo $params; ?></span>
					
					<div class="clear"></div>
				</div>
			</li>
			<li class="odd last">
				<div>
					<span class="collabroominfolabel">Collaboration Status:</span>
					<span class="collabroominfooutput">
						<?php echo get_collaboration_status_by_main_item_id($params); ?>
					</span>
					<!-- ALL STATUSES: Complete, In Progress, In Dispute -->
					<!-- NEW ROOM IS 'In Progress' -->
					<!-- IF MARKED COMPLETE 'Complete' -->
					<!-- IF USER REPORTS PROBLEM 'In Dispute' -->
					
					<div class="clear"></div>
				</div>
			</li>
		</ul>
		
		<div class="clear"></div>
	</div>
	
	<div class="clear"></div>
</div>
