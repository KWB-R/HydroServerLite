<?php $this->load->view('services/header'); ?>
		<p>
        <?php echo getTxt('WebServicesIntro1');?>
		</p>
		<p>
          <?php echo getTxt('WebServicesIntro2');?>
		</p>
		<p>
          <?php echo getTxt('WebServicesIntro3');?>
		</p>
		<br />
		<div id="base_info">
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('services/test');?>" class="info_link">REST <?php echo getTxt('WebServices').' '.getTxt('Test');?></a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;
                         <?php echo getTxt('RestTestText');?>
                    
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('services/cuahsi_1_1.asmx?WSDL');?>" class="info_link">SOAP <?php echo getTxt('WebServices');?> WSDL</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;<?php echo getTxt('CopyLink');?> HydroDesktop: <strong><?php echo site_url('services/cuahsi_1_1.asmx?WSDL');?></strong>
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('services/cuahsi_1_1.asmx');?>" class="info_link">SOAP <?php echo getTxt('WebServices');?></a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;Hydroserver SOAP <?php echo getTxt('WebServices').' '.getTxt('Test');?>.
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('services/updatecv');?>" class="info_link"><?php echo getTxt('UpdateCV');?></a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;<?php echo getTxt('UpdateCV').' '.getTxt('From');?> HIS Central.
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('wfs/write_xml?service=WFS&request=GetCapabilities&version=1.0.0') ;?>" class="info_link">WFS  <?php echo getTxt('WebServices');?></a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;WFS 1.0.0.
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('wfs/write_xml?service=WFS&request=GetCapabilities&version=2.0.0');?>" class="info_link">WFS  <?php echo getTxt('WebServices');?></a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;WFS 2.0.0.
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo site_url('services/api');?>" class="info_link">JSON API  <?php echo getTxt('API');?></a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;JSON API for data upload
					</div>
				</div> 
			</div>
		</div>
	
<?php $this->load->view('services/footer'); ?>