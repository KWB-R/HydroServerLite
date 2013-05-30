<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller {

	/**
	 * Implement cuahsi service
	 *
	 */
	public function cuahsi()
	{
		$this->load->helper("hydroservices");
		RunService();
  		exit;
	}

	/**
	 * Implement web tester
	 *
	 */
	public function test()
	{
		$this->load->view("test");
	}

	/**
	 * Get Method parameter(s) by ajax
	 *
	 */
	public function method_get_params($method = "") {
		$this->load->helper("hydroservices");

		$content = "";
		if (isset($method) && $method != "") {
	 		switch ($method) {
		      	case "GetSites":
		          	$content .= "
					  	<div class=\"param_container\">
						  	<label>SiteCode</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"site\" name=\"site\" value=\"".SERVICE_CODE.":".get_random_site()."\" title=\"The site code in the format: network:site_code. For example: KALA:SiteCode. Remove Site Code by click 'x' button to show metadata for all sites. To show multiple sites, enter a list of codes separated by comma (,)\" /> 
						  		<a onclick=\"javascript:remove(this);\" class=\"remove\">x</a>
						  	</div>
						</div>";
		          	break;
		      	case "GetSiteInfo":
		          	$content .= "
					  	<div class=\"param_container\">
						  	<label>SiteCode</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"site\" name=\"site\" value=\"".SERVICE_CODE.":".get_random_site()."\" class=\"must\" title=\"The site code in the format: network:site_code. For example: KALA:SiteCode.\" />
						  	</div>
						</div>";
		          	break;
		      	case "GetSiteInfoMultpleObject":
		          	$content .= "
					  	<div class=\"param_container\">
						  	<label>SiteCode</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"site\" name=\"site\" value=\"".SERVICE_CODE.":".get_random_site()."\" class=\"must\" title=\"The site code in the format: network:site_code. For example: KALA:SiteCode. To show multiple sites, enter a list of codes separated by comma (,)\" />
						  	</div>
						</div>";
		          	break;
		      	case "GetSiteInfoObject":
		          	$content .= "
					  	<div class=\"param_container\">
						  	<label>SiteCode</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"site\" name=\"site\" value=\"".SERVICE_CODE.":".get_random_site()."\" class=\"must\" title=\"The site code in the format: network:site_code. For example: KALA:SiteCode.\" />
						  	</div>
						</div>";
		          	break;
		      	case "GetSitesObject":
		          	$content .= "
					  	<div class=\"param_container\">
						  	<label>SiteCode</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"site\" name=\"site\" value=\"".SERVICE_CODE.":".get_random_site()."\" title=\"The site code in the format: network:site_code. For example: KALA:SiteCode. Remove Site Code by click 'x' button to show metadata for all sites. To show multiple sites, enter a list of codes separated by comma (,)\" /> 
						  		<a onclick=\"javascript:remove(this);\" class=\"remove\">x</a>
						  	</div>
						</div>";
		          	break;
		      	case "GetSitesByBoxObject":
		      		$coordinate = get_random_site_coordinate();
		          	$content .= "
					  	<div class=\"param_container\">
						  	<label>West</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"west\" name=\"west\" value=\"".$coordinate["Longitude"]."\" class=\"numeric must\" title=\"Fill with Longitude coordinate.\" />
						  	</div>
					  	</div>
					  	<div class=\"param_container\">
						  	<label>South</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"south\" name=\"south\" value=\"".$coordinate["Latitude"]."\" class=\"numeric must\" title=\"Fill with Latitude coordinate.\" />
						  	</div>
					  	</div>
					  	<div class=\"param_container\">
						  	<label>East</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"east\" name=\"east\" value=\"".$coordinate["Longitude"]."\" class=\"numeric must\" title=\"Fill with Longitude coordinate.\" />
						  	</div>
					  	</div>
					  	<div class=\"param_container\">
						  	<label>North</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"north\" name=\"north\" value=\"".$coordinate["Latitude"]."\" class=\"numeric must\" title=\"Fill with Latitude coordinate.\" />
						  	</div>
					  	</div>
					  	<div class=\"param_container\">
						  	<label>Include Series</label>
						  	<div class=\"content\">
							  	<input type=\"checkbox\" id=\"IncludeSeries\" name=\"IncludeSeries\" title=\"Check for include series.\" />
						  	</div>
					  	</div>";
		          	break;
		      	case "GetValues":
		          	$content .= "
					  	<div class=\"param_container\">
						  	<label>Location</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"location\" name=\"location\" value=\"".SERVICE_CODE.":".get_random_site()."\" class=\"must\" title=\"The location in the format: network:site_code. For example: KALA:SiteCode.\" />
						  	</div>
						</div>
					  	<div class=\"param_container\">
						  	<label>Variable</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"variable\" name=\"variable\" value=\"".SERVICE_CODE.":".get_random_variable()."\" class=\"must\" title=\"The variable in the format: network:variable_code. For example: KALA:IDCS-5-Avg.\" />
						  	</div>
					  	</div>
					  	<div class=\"param_container\">
						  	<label>Start Date</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"startDate\" name=\"startDate\" value=\"\" class=\"datepicker\" /> 
							  	<a onclick=\"javascript:remove(this);\" class=\"remove\">x</a>
						  	</div>
					  	</div>
					  	<div class=\"param_container\">
						  	<label>End Date</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"endDate\" name=\"endDate\" value=\"\" class=\"datepicker\" /> 
							  	<a onclick=\"javascript:remove(this);\" class=\"remove\">x</a>
						  	</div>
					  	</div>";
		          	break;
		      	case "GetValuesObject":
		          	$content .= "
					  	<div class=\"param_container\">
						  	<label>Location</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"location\" name=\"location\" value=\"".SERVICE_CODE.":".get_random_site()."\" class=\"must\" title=\"The location in the format: network:site_code. For example: KALA:SiteCode.\" />
						  	</div>
						</div>
					  	<div class=\"param_container\">
						  	<label>Variable</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"variable\" name=\"variable\" value=\"".SERVICE_CODE.":".get_random_variable()."\" class=\"must\" title=\"The variable in the format: network:variable_code. For example: KALA:IDCS-5-Avg.\" />
						  	</div>
					  	</div>
					  	<div class=\"param_container\">
						  	<label>Start Date</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"startDate\" name=\"startDate\" value=\"\" class=\"datepicker\" /> 
							  	<a onclick=\"javascript:remove(this);\" class=\"remove\">x</a>
						  	</div>
					  	</div>
					  	<div class=\"param_container\">
						  	<label>End Date</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"endDate\" name=\"endDate\" value=\"\" class=\"datepicker\" /> 
							  	<a onclick=\"javascript:remove(this);\" class=\"remove\">x</a>
						  	</div>
					  	</div>";
		          	break;
		      	case "GetValuesForASiteObject":
		          	$content .= "
					  	<div class=\"param_container\">
						  	<label>SiteCode</label>
					  		<div class=\"content\">
							  	<input type=\"text\" id=\"site\" name=\"site\" value=\"".SERVICE_CODE.":".get_random_site()."\" class=\"must\" title=\"The site code in the format: network:site_code. For example: KALA:SiteCode.\" />
						  	</div>
						</div>
					  	<div class=\"param_container\">
						  	<label>Start Date</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"startDate\" name=\"startDate\" value=\"\" class=\"datepicker must\" />
						  	</div>
					  	</div>
					  	<div class=\"param_container\">
						  	<label>End Date</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"endDate\" name=\"endDate\" value=\"\" class=\"datepicker must\" />
						  	</div>
					  	</div>";
		          	break;
		      	case "GetVariables":
		          	break;
		      	case "GetVariablesObject":
		          	break;
		      	case "GetVariableInfo":
		          	$content .= "
					  	<div class=\"param_container\">
						  	<label>Variable</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"variable\" name=\"variable\" value=\"".get_random_variable()."\" class=\"must\" title=\"Fill with variable code.\" />
						  	</div>
					  	</div>";
		          	break;
		      	case "GetVariableInfoObject":
		          	$content .= "
				  		<div class=\"param_container\">
						  	<label>Variable</label>
						  	<div class=\"content\">
							  	<input type=\"text\" id=\"variable\" name=\"variable\" value=\"".get_random_variable()."\" class=\"must\" title=\"Fill with variable code.\" />
						  	</div>
					  	</div>";
		          	break;
		    }
	    }

		echo $content;
	}

	/**
	 * Generate by ajax
	 *
	 */
	public function generate_url() {
		$target = base_url()."services/cuahsi_1_1.asmx/".$_POST["method"];

		$url = "";
		foreach($_POST as $k=>$v) {
			if (is_array($v)) {
				$url .= http_build_query(array($k=>$v), '', '&amp;')."&";
			} else {
				if ($k != "method") {
					if (strtolower($v) != "on") {
						$url .= $k."=".$v."&";
					} else {
						$url .= $k."=true&";
					}
				}
			}
		}

		$url = strlen($url) > 0? substr($url,0,-1):"";

		$url = $target.(strlen($url) > 0? "?".$url:"");

		echo "<div class=\"param_container\"><label>URL</label><div class=\"content\"><a href=\"".$url."\" target=\"_blank\">".$url."</a></div></div>";
	}
}