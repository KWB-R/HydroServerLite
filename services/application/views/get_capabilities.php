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
		<OnlineResource><?php echo base_url('wfs/write_xml'); ?></OnlineResource>
		<Fees>NONE</Fees>
		<AccessConstraints>NONE</AccessConstraints>
   </Service>
   <Capability>
      <Request>
         <GetCapabilities>
            <DCPType>
               <HTTP>
                  <Get onlineResource="<?php echo base_url('wfs/write_xml?'); ?>"/>
               </HTTP>
            </DCPType>
            <DCPType>
				<HTTP>
				  <Post onlineResource="<?php echo base_url('wfs/write_xml'); ?>"/>
				</HTTP>
            </DCPType>
         </GetCapabilities>
         <DescribeFeatureType>
            <SchemaDescriptionLanguage>
               <XMLSCHEMA/>
            </SchemaDescriptionLanguage>
            <DCPType>
               <HTTP>
                  <Get onlineResource="<?php echo base_url('wfs/write_xml?'); ?>"/>
               </HTTP>
            </DCPType>
            <DCPType>
			   <HTTP>
				  <Post onlineResource="<?php echo base_url('wfs/write_xml'); ?>"/>
			   </HTTP>
            </DCPType>
         </DescribeFeatureType>
         <GetFeature>
            <ResultFormat>
               <GML2/>
            </ResultFormat>
            <DCPType>
               <HTTP>
                  <Get onlineResource="<?php echo base_url('wfs/write_xml?'); ?>"/>
               </HTTP>
            </DCPType>
            <DCPType>
			   <HTTP>
				  <Post onlineResource="<?php echo base_url('wfs/write_xml'); ?>"/>
			   </HTTP>
            </DCPType>
         </GetFeature>
         <Transaction>
            <DCPType>
               <HTTP>
                  <Get onlineResource="<?php echo base_url('wfs/write_xml?'); ?>"/>
               </HTTP>
            </DCPType>
            <DCPType>
			   <HTTP>
				  <Post onlineResource="<?php echo base_url('wfs/write_xml'); ?>"/>
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
    
	foreach( $sites as $site )
	{
		//	 STANDARD NAME/TITLE/ABSTRACT attributes for features
		
		echo "<FeatureType>\n";
		echo "<Name>" . str_replace(" ", "-", $site->SiteName) . "</Name>\n";
		echo "<Title>" . $site->SiteName . "</Title>\n";
		echo "<SRS>EPSG:" . $site->SiteID . '-' . $variableID . "</SRS>\n";
		echo "<Keywords>" . $site->SiteName . "</Keywords>\n";
		echo "<LatLongBoundingBox minx='$site->Latitude' miny='$site->Longitude' maxx='' maxy='' ></LatLongBoundingBox>\n";
		echo "<Abstract></Abstract>\n";
		echo "</FeatureType>\n";	
	}
    ?>
	</FeatureTypeList>
</WFS_Capabilities>
