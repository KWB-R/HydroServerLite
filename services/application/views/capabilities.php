<WFS_Capabilities version="1.0.0"
xmlns="http://www.opengis.net/wfs"
xmlns:ogc="http://www.opengis.net/ogc"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
xsi:schemaLocation="http://www.opengis.net/wfs http://wfs.plansystem.dk:80/geoserver/schemas/wfs/1.0.0/WFS-capabilities.xsd">
   <Service>
		<Name>WebFeatureServer</Name>
		<Title>HydroServerLite WFS</Title>
		<Abstract>Test wfs 1.0.0</Abstract>
		<Keywords>WFS</Keywords>
		<OnlineResource><?php echo base_url();?></OnlineResource>
		<Fees>NONE</Fees>
		<AccessConstraints>NONE</AccessConstraints>
   </Service>
   <Capability>
      <Request>
         <GetCapabilities>
            <DCPType>
               <HTTP>
                  <Get onlineResource="<?php echo base_url();?>?"/>
               </HTTP>
            </DCPType>
            <DCPType>
				<HTTP>
				  <Post onlineResource="<?php echo base_url();; ?>?"/>
				</HTTP>
            </DCPType>
         </GetCapabilities>
         <DescribeFeatureType>
            <SchemaDescriptionLanguage>
               <XMLSCHEMA/>
            </SchemaDescriptionLanguage>
            <DCPType>
               <HTTP>
                  <Get onlineResource="<?php echo base_url();?>?"/>
               </HTTP>
            </DCPType>
            <DCPType>
			   <HTTP>
				  <Post onlineResource="<?php echo base_url();?>?"/>
			   </HTTP>
            </DCPType>
         </DescribeFeatureType>
         <GetFeature>
            <ResultFormat>
               <GML2/>
            </ResultFormat>
            <DCPType>
               <HTTP>
                  <Get onlineResource="<?php echo base_url();?>?"/>
               </HTTP>
            </DCPType>
            <DCPType>
			   <HTTP>
				  <Post onlineResource="<?php echo base_url();?>?"/>
			   </HTTP>
            </DCPType>
         </GetFeature>
         <Transaction>
            <DCPType>
               <HTTP>
                  <Get onlineResource="<?php echo base_url();?>?"/>
               </HTTP>
            </DCPType>
            <DCPType>
			   <HTTP>
				  <Post onlineResource="<?php echo base_url();?>?"/>
			   </HTTP>
            </DCPType>
         </Transaction>
      </Request>
      <VendorSpecificCapabilities>
      </VendorSpecificCapabilities>
   </Capability>

	<FeatureTypeList>
		<Operations>
			<Query/>
			<Insert/>
			<Update/>
			<Delete/>
		</Operations>
    <?php
    
	foreach( $feature_types as $feature_type )
	{
		// STANDARD NAME/TITLE/ABSTRACT attributes for features
//      echo "<FeatureType>\n";
//      echo "<Name>" . $feature_type->SiteName . "</Name>\n";
//      echo "<Abstract>" . $feature_type->SiteName . ' for ' . $feature_type->Organization . "</Abstract>\n";
//	  echo "<Source>" . $feature_type->SourceDescription . "</Source>\n";
//	  echo "</FeatureType>\n";	
	  
		$waterMLURL = htmlspecialchars(base_url() . 'services/' . 'cuahsi_1_1.asmx/GetValuesObject?location=' . $this->config->item('service_code') . ':' . trim($feature_type->SiteCode) . '&variable=' . $this->config->item('service_code') . ':' . trim($feature_type->VariableCode));
		
	  echo "<FeatureType>\n";
      echo "<WaterMLURL>" . $waterMLURL . "</WaterMLURL>\n";
      echo "<GraphURL>" . '' . "</GraphURL>\n";
	  echo "<DownloadURL>" . '' . "</DownloadURL>\n";
	  echo "<BeginDate>" . $feature_type->BeginDateTimeUTC . "</BeginDate>\n";
	  echo "<EndDate>" . $feature_type->EndDateTimeUTC . "</EndDate>\n";
	  echo "<Descriptor>" . $feature_type->SiteName . ' for ' . $feature_type->Organization . "</Descriptor>\n";
	  echo "<Source>" . $feature_type->SourceDescription . "</Source>\n";
	  echo "</FeatureType>\n";
	}
	
	// MANUAL TEST OF FEATURE
//	echo "<FeatureType>";
//      echo "<WaterMLURL>aasdad</WaterMLURL>";
//      echo "<GraphURL>" . 'sdfsdf' . "</GraphURL>";
//	  echo "<DownloadURL>" . 'sdfsdf' . "</DownloadURL>";
//	  echo "<BeginDate>asdf</BeginDate>";
//	  echo "<EndDate>asdfasdf</EndDate>";
//	  echo "<Descriptor>saf</Descriptor>";
//	  echo "<Source>sasdf</Source>";
//	  echo "</FeatureType>";
    ?>
	</FeatureTypeList>
	<ogc:Filter_Capabilities>
		<ogc:Spatial_Capabilities>
			<ogc:Spatial_Operators>
				<ogc:Disjoint/>
				<ogc:Equals/>
				<ogc:DWithin/>
				<ogc:Beyond/>
				<ogc:Intersect/>
				<ogc:Touches/>
				<ogc:Crosses/>
				<ogc:Within/>
				<ogc:Contains/>
				<ogc:Overlaps/>
				<ogc:BBOX/>
			</ogc:Spatial_Operators>
		</ogc:Spatial_Capabilities>
	</ogc:Filter_Capabilities>
</WFS_Capabilities>
