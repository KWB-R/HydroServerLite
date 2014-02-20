<wfs:FeatureCollection
 xmlns:tows='<?php echo base_url(); ?>'
 xmlns:wfs='http://www.opengis.net/wfs'
 xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'
 xmlns:gml='http://www.opengis.net/gml'
 xmlns:xsd='http://www.w3.org/2001/XMLSchema'
 xmlns:ogc='http://www.opengis.net/ogc'
 xmlns:xlink='http://www.w3.org/1999/xlink'
 xmlns:ows='http://www.opengis.net/ows'
 xsi:schemaLocation='<?php echo base_url(); ?>
	<?php echo base_url(); ?>/index.php/wfs/write_xml?VariableID=8&amp;service=WFS&amp;version=1.0.0&amp;request=DescribeFeatureType&amp;Typename=hydroserver-<?php echo $variableID; ?>
   http://www.opengis.net/wfs
   http://schemas.opengis.net/wfs/1.0.0/WFS-basic.xsd
   http://www.opengis.net/gml
   http://schemas.opengis.net/gml/2.1.2/feature.xsd'
>
<gml:boundedBy><gml:null>missing</gml:null></gml:boundedBy>
<?php 
foreach( $features as $feat ) {
?>
	<gml:featureMember>
	<hydroserver-<?php echo $variableID; ?>>
			<tows:site_id><?php echo $feat->SiteID; ?></tows:site_id>
			<tows:name><?php echo $feat->SiteName; ?></tows:name>
			<tows:lon><?php echo $feat->Longitude; ?></tows:lon>
			<tows:lat><?php echo $feat->Latitude; ?></tows:lat>
			<tows:watermlurl><?php echo 'http' ?></tows:watermlurl>
			<tows:graphurl><?php echo ''; ?></tows:graphurl>
			<tows:downloadurl><?php echo ''; ?></tows:downloadurl>
			<tows:begindate><?php echo $feat->BeginDateTimeUTC; ?></tows:begindate>
			<tows:enddate><?php echo $feat->EndDateTimeUTC; ?></tows:enddate>
			<tows:descriptor><?php echo $feat->SiteName; ?></tows:descriptor>
			<tows:source><?php echo $feat->SourceDescription; ?></tows:source>
			<tows:geom><gml:Point srsName="EPSG:4326"><gml:coordinates><?php echo $feat->Longitude . ',' . $feat->Latitude; ?></gml:coordinates></gml:Point></tows:geom>
	</hydroserver-<?php echo $variableID; ?>>
	</gml:featureMember>
<?php
}
?>
</wfs:FeatureCollection>