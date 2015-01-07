<?php $this->load->view('services/header'); ?>

		<p>
		The HydroServer Lite Web Services API provide access to scientific data from the ODM database on this server.
		</p>
		<p>
You can connect to the web services with HydroDesktop, HydroExcel and  QGIS software.
		</p>
		<p>
Programmers can use Python, R or other programming language to automate the data retrieval.
		</p>
		<br />
		<div id="base_info">
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo 'index.php/test';?>" class="info_link">REST Service Test</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;You can perform tests on all of the WaterML web service methods on this page. In this case the test for REST Service.
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo 'index.php/cuahsi_1_1.asmx?WSDL';?>" class="info_link">SOAP Service WSDL</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;copy the link to HydroDesktop: <strong><?=base_url()?>index.php/cuahsi_1_1.asmx?WSDL</strong>
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo 'index.php/cuahsi_1_1.asmx';?>" class="info_link">SOAP Web Service</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;Hydroserver SOAP service test page.
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo 'index.php/updatecv.php';?>" class="info_link">Update Controlled Vocabulary</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;Update Controlled Vocabulary from HIS Central.
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo 'index.php/wfs/write_xml?service=WFS&request=GetCapabilities&version=1.0.0';?>" class="info_link">WFS Services</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;WFS 1.0.0.
					</div>
				</div> 
			</div>
			<div class="info_container">
			    <label class="info_label"><a href="<?php echo 'index.php/wfs/write_xml?service=WFS&request=GetCapabilities&version=2.0.0';?>" class="info_link">WFS Services</a></label>
				<div class="info_content">
					<div class="link_desc">
						&nbsp;WFS 2.0.0.
					</div>
				</div> 
			</div>
		</div>
	
<?php $this->load->view('services/footer'); ?>