<?php
								$params=$_GET['collab_id'];
								
								$last=isset($_GET['report_id'])?'&report_id='.$_GET['report_id']:'';
								?>
<h2 class="nav-tab-wrapper">
		<a href="?page=collab_message&collab_id=<?php echo $_GET['collab_id'].$last; ?>" class="nav-tab <?php echo ($_GET['page']=='collab_message')?'nav-tab-active':''; ?>">Message</a>
		<a href="?page=collab_files&collab_id=<?php echo $_GET['collab_id'].$last; ?>" class="nav-tab <?php echo ($_GET['page']=='collab_files')?'nav-tab-active':''; ?>">Files</a>
		<a href="?page=collab_teams&collab_id=<?php echo $_GET['collab_id'].$last; ?>" class="nav-tab <?php echo ($_GET['page']=='collab_teams')?'nav-tab-active':''; ?>">Team Members</a>
        <a href="?page=collab_commission&collab_id=<?php echo $_GET['collab_id'].$last; ?>" class="nav-tab <?php echo ($_GET['page']=='collab_commission')?'nav-tab-active':''; ?>">Commission</a>
        <?php
		if(isset($_GET['report_id'])){
		?>
        <a href="?page=collab_details&collab_id=<?php echo $_GET['collab_id']; ?>&report_id=<?php echo $_GET['report_id']; ?>" class="nav-tab <?php echo ($_GET['page']=='collab_details')?'nav-tab-active':''; ?>">Reported Problem</a>
        <?php	
		}
		?>
</h2>
<style type="text/css">
.management-liquid {
float: left!important;
min-width: 75%!important;
margin-top: 3px!important;

}
#nav-menus-frame {
margin-left: 0px!important;
margin-top: 23px;
}
#wpbody-content #menu-settings-column {
display: inline;
width: 281px;
margin-left: 0px!important;
clear:none!important;
float: left;
padding-top: 0;
}
#menu-management-liquid{
width:calc(100% - 291px)!important;
width:-webkit-calc(100% - 291px)%!important;
width:-moz-calc(100% - 291px)%!important;
height:auto!important;
margin-right:10px!important;
min-width:-webkit-calc(100% - 291px)%!important;
min-width:-moz-calc(100% - 291px)%!important;
min-width:calc(100% - 291px)%!important;
min-width:inherit!important;
}
.column-owner {width:180px !important; overflow:hidden }';
</style>
<script>
Edd.options.admin=true;
</script>