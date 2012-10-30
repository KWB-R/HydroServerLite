<?php
//check authority to be here
require_once 'authorization_check.php';

//connect to server and select database
require_once 'database_connection.php';

//get list of TopicCategories to choose from
$sql2 = "Select Term FROM topiccategorycv";

$result2 = @mysql_query($sql2, $connection) or die(mysql_error());

$num2 = @mysql_num_rows($result2);
if ($num2 < 1) {

    $msg2 = "<P><em2>Sorry, no data available.</em></p>";
} else {

    while ($row2 = mysql_fetch_array($result2)) {

        $metaTerm = $row2["Term"];

        $option_block2 .= "<option value=$metaTerm>$metaTerm</option>";
    }
}
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>HydroServer Lite Web Client</title>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        <link rel="bookmark" href="favicon.ico" >

        <link href="styles/main_css.css" rel="stylesheet" type="text/css" media="screen" />
        <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>

        <script type="text/javascript">

            $(document).ready(function(){

                $("#msg").hide();
                $('#country').change(function(){
                    if($(this).val() == 'US'){
                        $('#state').removeAttr('disabled');
                    }                
                    else{ 
                        $('#state').attr('disabled','disabled');
                    }           
                });

            });
        </script>

    </head>
    <body background="images/bkgrdimage.jpg">
        <table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="2"><img src="images/WebClientBanner.png" width="960" height="200" alt="logo" /></td>
            </tr>
            <tr>
                <td colspan="2" align="right" valign="middle" bgcolor="#3c3c3c"><?php require_once 'header.php'; ?></td>
            </tr>
            <tr>
                <td width="240" valign="top" bgcolor="#f2e6d6"><?php echo "$nav"; ?></td>
                <td width="720" valign="top" bgcolor="#FFFFFF"><blockquote><br /><p class="em" align="right">Required fields are marked with an asterick (*).</p><div id="msg"><p class=em2>Source successfully added!</p></div>
                        <h1>Add a New Source</h1>
                        <p>&nbsp;</p>
                        <FORM METHOD="POST" ACTION="" name="addsource" id="addsource">
                            <table width="600" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="108" valign="top"><strong>Organization:</strong></td>
                                    <td colspan="2" valign="top"><input type="text" id="Organization" name="Organization" size="35" maxlength="100"/>*&nbsp;<span class="em">(Ex: McCall Outdoor Science School)</span></td>
                                </tr>
                                <tr>
                                    <td width="108" valign="top">&nbsp;</td>
                                    <td width="22" valign="top">&nbsp;</td>
                                    <td width="470" valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Description:</strong></td>
                                    <td colspan="2" valign="top"><input type="text" id="SourceDescription" name="SourceDescription" size="35" maxlength="200"/>*&nbsp;<span class="em">(Ex: The mission of the MOSS is....)</span></td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td width="22" valign="top">&nbsp;</td>
                                    <td width="470" valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Link to Org:</strong></td>
                                    <td colspan="2" valign="top"><input type="text" id="SourceLink" name="SourceLink" size="35" maxlength="200"/>
                                        &nbsp;<span class="em">(Optional, Ex: http://www.mossidaho.org)</span></td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Contact Name:</strong></td>
                                    <td colspan="2" valign="top"><input type="text" id="ContactName" name="ContactName" size="25" maxlength="200"/>*&nbsp;<span class="em">(Full Name)</span></td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Phone:</strong></td>
                                    <td colspan="2" valign="top"><input type="text" id="Phone" name="Phone" size="12" maxlength="15"/>*&nbsp;<span class="em">(Ex: XXX-XXX-XXXX)</span></td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Email:</strong></td>
                                    <td colspan="2" valign="top"><input type="text" id="Email" name="Email" size="12" maxlength="50"/>*&nbsp;<span class="em">(Ex: info@moss.org)</span></td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Address:</strong></td>
                                    <td colspan="2" valign="top"><input type="text" id="Address" name="Address" size="35" maxlength="100"/>*</td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>City:</strong></td>
                                    <td colspan="2" valign="top"><input type="text" id="City" name="City" size="25" maxlength="100"/>*</td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Country:</strong></td>
                                    <td colspan="2" valign="top"> <select name="country" id="country">
                                            <option value="-1" selected>(please select a country)</option>
                                            <option value="AF">Afghanistan</option>
                                            <option value="AL">Albania</option>
                                            <option value="DZ">Algeria</option>
                                            <option value="AS">American Samoa</option>
                                            <option value="AD">Andorra</option>
                                            <option value="AO">Angola</option>
                                            <option value="AI">Anguilla</option>
                                            <option value="AQ">Antarctica</option>
                                            <option value="AG">Antigua and Barbuda</option>
                                            <option value="AR">Argentina</option>
                                            <option value="AM">Armenia</option>
                                            <option value="AW">Aruba</option>
                                            <option value="AU">Australia</option>
                                            <option value="AT">Austria</option>
                                            <option value="AZ">Azerbaijan</option>
                                            <option value="BS">Bahamas</option>
                                            <option value="BH">Bahrain</option>
                                            <option value="BD">Bangladesh</option>
                                            <option value="BB">Barbados</option>
                                            <option value="BY">Belarus</option>
                                            <option value="BE">Belgium</option>
                                            <option value="BZ">Belize</option>
                                            <option value="BJ">Benin</option>
                                            <option value="BM">Bermuda</option>
                                            <option value="BT">Bhutan</option>
                                            <option value="BO">Bolivia</option>
                                            <option value="BA">Bosnia and Herzegowina</option>
                                            <option value="BW">Botswana</option>
                                            <option value="BV">Bouvet Island</option>
                                            <option value="BR">Brazil</option>
                                            <option value="IO">British Indian Ocean Territory</option>
                                            <option value="BN">Brunei Darussalam</option>
                                            <option value="BG">Bulgaria</option>
                                            <option value="BF">Burkina Faso</option>
                                            <option value="BI">Burundi</option>
                                            <option value="KH">Cambodia</option>
                                            <option value="CM">Cameroon</option>
                                            <option value="CA">Canada</option>
                                            <option value="CV">Cape Verde</option>
                                            <option value="KY">Cayman Islands</option>
                                            <option value="CF">Central African Republic</option>
                                            <option value="TD">Chad</option>
                                            <option value="CL">Chile</option>
                                            <option value="CN">China</option>
                                            <option value="CX">Christmas Island</option>
                                            <option value="CC">Cocos (Keeling) Islands</option>
                                            <option value="CO">Colombia</option>
                                            <option value="KM">Comoros</option>
                                            <option value="CG">Congo</option>
                                            <option value="CD">Congo, the Democratic Republic of the</option>
                                            <option value="CK">Cook Islands</option>
                                            <option value="CR">Costa Rica</option>
                                            <option value="CI">Cote d'Ivoire</option>
                                            <option value="HR">Croatia (Hrvatska)</option>
                                            <option value="CU">Cuba</option>
                                            <option value="CY">Cyprus</option>
                                            <option value="CZ">Czech Republic</option>
                                            <option value="DK">Denmark</option>
                                            <option value="DJ">Djibouti</option>
                                            <option value="DM">Dominica</option>
                                            <option value="DO">Dominican Republic</option>
                                            <option value="TP">East Timor</option>
                                            <option value="EC">Ecuador</option>
                                            <option value="EG">Egypt</option>
                                            <option value="SV">El Salvador</option>
                                            <option value="GQ">Equatorial Guinea</option>
                                            <option value="ER">Eritrea</option>
                                            <option value="EE">Estonia</option>
                                            <option value="ET">Ethiopia</option>
                                            <option value="FK">Falkland Islands (Malvinas)</option>
                                            <option value="FO">Faroe Islands</option>
                                            <option value="FJ">Fiji</option>
                                            <option value="FI">Finland</option>
                                            <option value="FR">France</option>
                                            <option value="FX">France, Metropolitan</option>
                                            <option value="GF">French Guiana</option>
                                            <option value="PF">French Polynesia</option>
                                            <option value="TF">French Southern Territories</option>
                                            <option value="GA">Gabon</option>
                                            <option value="GM">Gambia</option>
                                            <option value="GE">Georgia</option>
                                            <option value="DE">Germany</option>
                                            <option value="GH">Ghana</option>
                                            <option value="GI">Gibraltar</option>
                                            <option value="GR">Greece</option>
                                            <option value="GL">Greenland</option>
                                            <option value="GD">Grenada</option>
                                            <option value="GP">Guadeloupe</option>
                                            <option value="GU">Guam</option>
                                            <option value="GT">Guatemala</option>
                                            <option value="GN">Guinea</option>
                                            <option value="GW">Guinea-Bissau</option>
                                            <option value="GY">Guyana</option>
                                            <option value="HT">Haiti</option>
                                            <option value="HM">Heard and Mc Donald Islands</option>
                                            <option value="VA">Holy See (Vatican City State)</option>
                                            <option value="HN">Honduras</option>
                                            <option value="HK">Hong Kong</option>
                                            <option value="HU">Hungary</option>
                                            <option value="IS">Iceland</option>
                                            <option value="IN">India</option>
                                            <option value="ID">Indonesia</option>
                                            <option value="IR">Iran (Islamic Republic of)</option>
                                            <option value="IQ">Iraq</option>
                                            <option value="IE">Ireland</option>
                                            <option value="IL">Israel</option>
                                            <option value="IT">Italy</option>
                                            <option value="JM">Jamaica</option>
                                            <option value="JP">Japan</option>
                                            <option value="JO">Jordan</option>
                                            <option value="KZ">Kazakhstan</option>
                                            <option value="KE">Kenya</option>
                                            <option value="KI">Kiribati</option>
                                            <option value="KP">Korea, Democratic People's Republic of</option>
                                            <option value="KR">Korea, Republic of</option>
                                            <option value="KW">Kuwait</option>
                                            <option value="KG">Kyrgyzstan</option>
                                            <option value="LA">Lao People's Democratic Republic</option>
                                            <option value="LV">Latvia</option>
                                            <option value="LB">Lebanon</option>
                                            <option value="LS">Lesotho</option>
                                            <option value="LR">Liberia</option>
                                            <option value="LY">Libyan Arab Jamahiriya</option>
                                            <option value="LI">Liechtenstein</option>
                                            <option value="LT">Lithuania</option>
                                            <option value="LU">Luxembourg</option>
                                            <option value="MO">Macau</option>
                                            <option value="MK">Macedonia, The Former Yugoslav Republic of</option>
                                            <option value="MG">Madagascar</option>
                                            <option value="MW">Malawi</option>
                                            <option value="MY">Malaysia</option>
                                            <option value="MV">Maldives</option>
                                            <option value="ML">Mali</option>
                                            <option value="MT">Malta</option>
                                            <option value="MH">Marshall Islands</option>
                                            <option value="MQ">Martinique</option>
                                            <option value="MR">Mauritania</option>
                                            <option value="MU">Mauritius</option>
                                            <option value="YT">Mayotte</option>
                                            <option value="MX">Mexico</option>
                                            <option value="FM">Micronesia, Federated States of</option>
                                            <option value="MD">Moldova, Republic of</option>
                                            <option value="MC">Monaco</option>
                                            <option value="MN">Mongolia</option>
                                            <option value="MS">Montserrat</option>
                                            <option value="MA">Morocco</option>
                                            <option value="MZ">Mozambique</option>
                                            <option value="MM">Myanmar</option>
                                            <option value="NA">Namibia</option>
                                            <option value="NR">Nauru</option>
                                            <option value="NP">Nepal</option>
                                            <option value="NL">Netherlands</option>
                                            <option value="AN">Netherlands Antilles</option>
                                            <option value="NC">New Caledonia</option>
                                            <option value="NZ">New Zealand</option>
                                            <option value="NI">Nicaragua</option>
                                            <option value="NE">Niger</option>
                                            <option value="NG">Nigeria</option>
                                            <option value="NU">Niue</option>
                                            <option value="NF">Norfolk Island</option>
                                            <option value="MP">Northern Mariana Islands</option>
                                            <option value="NO">Norway</option>
                                            <option value="OM">Oman</option>
                                            <option value="PK">Pakistan</option>
                                            <option value="PW">Palau</option>
                                            <option value="PA">Panama</option>
                                            <option value="PG">Papua New Guinea</option>
                                            <option value="PY">Paraguay</option>
                                            <option value="PE">Peru</option>
                                            <option value="PH">Philippines</option>
                                            <option value="PN">Pitcairn</option>
                                            <option value="PL">Poland</option>
                                            <option value="PT">Portugal</option>
                                            <option value="PR">Puerto Rico</option>
                                            <option value="QA">Qatar</option>
                                            <option value="RE">Reunion</option>
                                            <option value="RO">Romania</option>
                                            <option value="RU">Russian Federation</option>
                                            <option value="RW">Rwanda</option>
                                            <option value="KN">Saint Kitts and Nevis</option> 
                                            <option value="LC">Saint LUCIA</option>
                                            <option value="VC">Saint Vincent and the Grenadines</option>
                                            <option value="WS">Samoa</option>
                                            <option value="SM">San Marino</option>
                                            <option value="ST">Sao Tome and Principe</option> 
                                            <option value="SA">Saudi Arabia</option>
                                            <option value="SN">Senegal</option>
                                            <option value="SC">Seychelles</option>
                                            <option value="SL">Sierra Leone</option>
                                            <option value="SG">Singapore</option>
                                            <option value="SK">Slovakia (Slovak Republic)</option>
                                            <option value="SI">Slovenia</option>
                                            <option value="SB">Solomon Islands</option>
                                            <option value="SO">Somalia</option>
                                            <option value="ZA">South Africa</option>
                                            <option value="GS">South Georgia and the South Sandwich Islands</option>
                                            <option value="ES">Spain</option>
                                            <option value="LK">Sri Lanka</option>
                                            <option value="SH">St. Helena</option>
                                            <option value="PM">St. Pierre and Miquelon</option>
                                            <option value="SD">Sudan</option>
                                            <option value="SR">Suriname</option>
                                            <option value="SJ">Svalbard and Jan Mayen Islands</option>
                                            <option value="SZ">Swaziland</option>
                                            <option value="SE">Sweden</option>
                                            <option value="CH">Switzerland</option>
                                            <option value="SY">Syrian Arab Republic</option>
                                            <option value="TW">Taiwan, Province of China</option>
                                            <option value="TJ">Tajikistan</option>
                                            <option value="TZ">Tanzania, United Republic of</option>
                                            <option value="TH">Thailand</option>
                                            <option value="TG">Togo</option>
                                            <option value="TK">Tokelau</option>
                                            <option value="TO">Tonga</option>
                                            <option value="TT">Trinidad and Tobago</option>
                                            <option value="TN">Tunisia</option>
                                            <option value="TR">Turkey</option>
                                            <option value="TM">Turkmenistan</option>
                                            <option value="TC">Turks and Caicos Islands</option>
                                            <option value="TV">Tuvalu</option>
                                            <option value="UG">Uganda</option>
                                            <option value="UA">Ukraine</option>
                                            <option value="AE">United Arab Emirates</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="US">United States</option>
                                            <option value="UM">United States Minor Outlying Islands</option>
                                            <option value="UY">Uruguay</option>
                                            <option value="UZ">Uzbekistan</option>
                                            <option value="VU">Vanuatu</option>
                                            <option value="VE">Venezuela</option>
                                            <option value="VN">Viet Nam</option>
                                            <option value="VG">Virgin Islands (British)</option>
                                            <option value="VI">Virgin Islands (U.S.)</option>
                                            <option value="WF">Wallis and Futuna Islands</option>
                                            <option value="EH">Western Sahara</option>
                                            <option value="YE">Yemen</option>
                                            <option value="YU">Yugoslavia</option>
                                            <option value="ZM">Zambia</option>
                                            <option value="ZW">Zimbabwe</option>
                                        </select>*</td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>

                                    <td valign="top"><strong>State:</strong></td>
                                    <td colspan="2" valign="top"><select name="state" id="state" disabled>
                                            <option value="-1">Select....</option>
                                            <option value="AL">Alabama</option>
                                            <option value="AK">Alaska</option>
                                            <option value="AZ">Arizona</option>
                                            <option value="AR">Arkansas</option>
                                            <option value="CA">California</option>
                                            <option value="CO">Colorado</option>
                                            <option value="CT">Connecticut</option>
                                            <option value="DE">Delaware</option>
                                            <option value="DC">District of Columbia</option>
                                            <option value="FL">Florida</option>
                                            <option value="GA">Georgia</option>
                                            <option value="HI">Hawaii</option>
                                            <option value="ID">Idaho</option>
                                            <option value="IL">Illinois</option>
                                            <option value="IN">Indiana</option>
                                            <option value="IA">Iowa</option>
                                            <option value="KS">Kansas</option>
                                            <option value="KY">Kentucky</option>
                                            <option value="LA">Louisiana</option>
                                            <option value="ME">Maine</option>
                                            <option value="MD">Maryland</option>
                                            <option value="MA">Massachusetts</option>
                                            <option value="MI">Michigan</option>
                                            <option value="MN">Minnesota</option>
                                            <option value="MS">Mississippi</option>
                                            <option value="MO">Missouri</option>
                                            <option value="MT">Montana</option>
                                            <option value="NE">Nebraska</option>
                                            <option value="NV">Nevada</option>
                                            <option value="NH">New Hampshire</option>
                                            <option value="NJ">New Jersey</option>
                                            <option value="NM">New Mexico</option>
                                            <option value="NY">New York</option>
                                            <option value="NC">North Carolina</option>
                                            <option value="ND">North Dakota</option>
                                            <option value="OH">Ohio</option>
                                            <option value="OK">Oklahoma</option>
                                            <option value="OR">Oregon</option>
                                            <option value="PA">Pennsylvania</option>
                                            <option value="RI">Rhode Island</option>
                                            <option value="SC">South Carolina</option>
                                            <option value="SD">South Dakota</option>
                                            <option value="TN">Tennessee</option>
                                            <option value="TX">Texas</option>
                                            <option value="UT">Utah</option>
                                            <option value="VT">Vermont</option>
                                            <option value="VA">Virginia</option>
                                            <option value="WA">Washington</option>
                                            <option value="WV">West Virginia</option>
                                            <option value="WI">Wisconsin</option>
                                            <option value="WY">Wyoming</option>
                                        </select>*</td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Zip Code:</strong></td>
                                    <td colspan="2" valign="top"><input type="text" id="ZipCode" name="ZipCode" size="5" maxlength="8"/>*</td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Citation:</strong></td>
                                    <td colspan="2" valign="top"><input type="text" id="Citation" name="Citation" size="35" maxlength="100"/>&nbsp;<span class="em">(Optional, Ex: Data collected by MOSS scientists and citizen scie...)</span></td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>MetadataID:</strong></td>
                                    <td colspan="2" valign="top"><span class="em">
                                            <input type="text" id="MetadataID" name="MetadataID" size="5" maxlength="8" style="background-color:#999;" disabled/>&nbsp;(This will be auto-generated for you upon submission.)</span></td>
                                </tr>
                                <tr>
                                    <td width="108" valign="top">&nbsp;</td>
                                    <td width="22" valign="top">&nbsp;</td>
                                    <td width="470" valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Topic Category:</strong></td>
                                    <td colspan="2" valign="top"><select name="TopicCategory" id="TopicCategory">
                                            <option value="-1">Select....</option>
<?php echo "$option_block2"; ?>
                                        </select>*&nbsp;<?php echo "$msg2"; ?></td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Title:</strong></td>
                                    <td colspan="2" valign="top"><input type="text" id="Title" name="Title" size="35" maxlength="100"/>*&nbsp;<span class="em">(Ex: Twin Falls High School)</span></td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Abstract:</strong></td>
                                    <td colspan="2" valign="top"><input type="text" id="Abstract" name="Abstract" size="35" maxlength="250"/>*&nbsp;<span class="em">(Ex: High school students/citizen scientists collecting...)</span></td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Metadata Link:</strong></td>
                                    <td colspan="2" valign="top"><input type="text" id="MetadataLink" name="MetadataLink" size="12" maxlength="15"/>
                                        &nbsp;<span class="em">(Optional)</span></td>
                                </tr>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width="108" valign="top">&nbsp;</td>
                                    <td width="22" valign="top">&nbsp;</td>
                                    <td width="470" valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3" valign="top"><input type="SUBMIT" name="submit" value="Add Source" class="button" /></td>
                                </tr>
                            </table></FORM>
                        <p>&nbsp;</p>
                    </blockquote></td>
            </tr>
            <tr>
            <script src="js/footer.js"></script>
        </tr>
    </table>

    <script>

        $("#addsource").submit(function(){

            //Validate all fields
            if(($("#Organization").val())==""){
                alert("Please enter an organization for the source.");
                return false;
            }

            if(($("#SourceDescription").val())==""){
                alert("Please enter a description for the source.");
                return false;
            }

            if(($("#SourceLink").val())!=""){
                var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
                if(!($("#SourceLink").val().match(regexp))){
                    alert("Invalid url for sourcelink");
                    return false;
                }
            }

            if(($("#ContactName").val())==""){
                alert("Please enter a contact name for the source.");
                return false;
            }

            if(($("#Phone").val())==""){
                alert("Please enter a phone number for the contact person.");
                return false;
            }

            //Phone Validation
            var regex = /^((\+?1-)?\d\d\d-)?\d\d\d-\d\d\d\d$/;
            if(!($("#Phone").val().match(regex))){
                alert("Invalid phone number");
                return false;
            }

            if(($("#Email").val())==""){
                alert("Please enter an email address for the source.");
                return false;
            }

            var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;

            if(!($("#Email").val().match(pattern))){
                alert("Invalid email address");
                return false;
            }

            if(($("#Address").val())==""){
                alert("Please enter an address for the source.");
                return false;
            }
	
            if(($("#City").val())==""){
                alert("Please enter a city for the source.");
                return false;
            }
            
            if(($("#country option:selected").val())==-1){
                    alert("Please select a country for the source.");
                    return false;
            }
            
            if(($("#country option:selected").val())== 'US'){
                if(($("#state option:selected").val())==-1){
                    alert("Please select a state for the source.");
                    return false;
                }
            }

            if(($("#ZipCode").val())==""){
                alert("Please enter a zip code for the source.");
                return false;
            }

            if(!($("#ZipCode").val().match(/^\d{5}(-\d{4})?$/))){
                alert("Invalid zip code");
                return false;
            }

            //Validate MetadataID info
            if(($("#TopicCategory option:selected").val())==-1){
                alert("Please select a topic category for the Metadata.");
                return false;
            }

            if(($("#Title").val())==""){
                alert("Please enter a title for the Metadata.");
                return false;
            }

            if(($("#Abstract").val())==""){
                alert("Please enter an Abstract for the Metadata.");
                return false;
            }

            if(($("#MetadataLink").val())!=""){
                var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
                if(!($("#ContactName").val().match(regexp))){
                    alert("Invalid url for Metadata Link");
                    return false;
                }
            }

            //Validation is all complete, so now process it

            $.post("do_add_source.php", $("#addsource").serialize(), function(data){
  
                if(data==1){
                    $("#msg").show(2000);
                    $("#msg").hide(3500);
                    $("#Organization").val("");
                    $("#SourceDescription").val("");
                    $("#SourceLink").val("");
                    $("#ContactName").val("");
                    $("#Phone").val("");
                    $("#Email").val("");
                    $("#Address").val("");
                    $("#City").val("");
                    $("#state").val("-1");
                    $("#ZipCode").val("");
                    $("#Citation").val("");
                    $("#TopicCategory").val("-1");
                    $("#Title").val("");
                    $("#Abstract").val("");
                    $("#MetadataLink").val("");
                    setTimeout(function(){
                        window.open("add_source.php","_self");
                    }, 5000);
                    return true;
                }else{
                    alert("Error during processing! Please refresh the page and try again.");
                    return false;
                }
		
            });
            return false;
        });
    </script>
</body>
</html>
