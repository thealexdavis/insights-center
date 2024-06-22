<?php
$page = Page::getCurrentPage();
$countries = [
  "AF" => "Afghanistan",
  "AX" => "Åland Islands",
  "AL" => "Albania",
  "DZ" => "Algeria",
  "AS" => "American Samoa",
  "AD" => "Andorra",
  "AO" => "Angola",
  "AI" => "Anguilla",
  "AQ" => "Antarctica",
  "AG" => "Antigua and Barbuda",
  "AR" => "Argentina",
  "AM" => "Armenia",
  "AW" => "Aruba",
  "AU" => "Australia",
  "AT" => "Austria",
  "AZ" => "Azerbaijan",
  "BS" => "Bahamas",
  "BH" => "Bahrain",
  "BD" => "Bangladesh",
  "BB" => "Barbados",
  "BY" => "Belarus",
  "BE" => "Belgium",
  "BZ" => "Belize",
  "BJ" => "Benin",
  "BM" => "Bermuda",
  "BT" => "Bhutan",
  "BO" => "Bolivia",
  "BA" => "Bosnia and Herzegovina",
  "BW" => "Botswana",
  "BV" => "Bouvet Island",
  "BR" => "Brazil",
  "IO" => "British Indian Ocean Territory",
  "BN" => "Brunei Darussalam",
  "BG" => "Bulgaria",
  "BF" => "Burkina Faso",
  "BI" => "Burundi",
  "KH" => "Cambodia",
  "CM" => "Cameroon",
  "CA" => "Canada",
  "CV" => "Cape Verde",
  "KY" => "Cayman Islands",
  "CF" => "Central African Republic",
  "TD" => "Chad",
  "CL" => "Chile",
  "CN" => "China",
  "CX" => "Christmas Island",
  "CC" => "Cocos (Keeling) Islands",
  "CO" => "Colombia",
  "KM" => "Comoros",
  "CG" => "Congo",
  "CD" => "Congo, The Democratic Republic of The",
  "CK" => "Cook Islands",
  "CR" => "Costa Rica",
  "CI" => "Cote D'ivoire",
  "HR" => "Croatia",
  "CU" => "Cuba",
  "CY" => "Cyprus",
  "CZ" => "Czech Republic",
  "DK" => "Denmark",
  "DJ" => "Djibouti",
  "DM" => "Dominica",
  "DO" => "Dominican Republic",
  "EC" => "Ecuador",
  "EG" => "Egypt",
  "SV" => "El Salvador",
  "GQ" => "Equatorial Guinea",
  "ER" => "Eritrea",
  "EE" => "Estonia",
  "ET" => "Ethiopia",
  "FK" => "Falkland Islands (Malvinas)",
  "FO" => "Faroe Islands",
  "FJ" => "Fiji",
  "FI" => "Finland",
  "FR" => "France",
  "GF" => "French Guiana",
  "PF" => "French Polynesia",
  "TF" => "French Southern Territories",
  "GA" => "Gabon",
  "GM" => "Gambia",
  "GE" => "Georgia",
  "DE" => "Germany",
  "GH" => "Ghana",
  "GI" => "Gibraltar",
  "GR" => "Greece",
  "GL" => "Greenland",
  "GD" => "Grenada",
  "GP" => "Guadeloupe",
  "GU" => "Guam",
  "GT" => "Guatemala",
  "GG" => "Guernsey",
  "GN" => "Guinea",
  "GW" => "Guinea-bissau",
  "GY" => "Guyana",
  "HT" => "Haiti",
  "HM" => "Heard Island and Mcdonald Islands",
  "VA" => "Holy See (Vatican City State)",
  "HN" => "Honduras",
  "HK" => "Hong Kong",
  "HU" => "Hungary",
  "IS" => "Iceland",
  "IN" => "India",
  "ID" => "Indonesia",
  "IR" => "Iran, Islamic Republic of",
  "IQ" => "Iraq",
  "IE" => "Ireland",
  "IM" => "Isle of Man",
  "IL" => "Israel",
  "IT" => "Italy",
  "JM" => "Jamaica",
  "JP" => "Japan",
  "JE" => "Jersey",
  "JO" => "Jordan",
  "KZ" => "Kazakhstan",
  "KE" => "Kenya",
  "KI" => "Kiribati",
  "KP" => "Korea, Democratic People's Republic of",
  "KR" => "Korea, Republic of",
  "KW" => "Kuwait",
  "KG" => "Kyrgyzstan",
  "LA" => "Lao People's Democratic Republic",
  "LV" => "Latvia",
  "LB" => "Lebanon",
  "LS" => "Lesotho",
  "LR" => "Liberia",
  "LY" => "Libyan Arab Jamahiriya",
  "LI" => "Liechtenstein",
  "LT" => "Lithuania",
  "LU" => "Luxembourg",
  "MO" => "Macao",
  "MK" => "Macedonia, The Former Yugoslav Republic of",
  "MG" => "Madagascar",
  "MW" => "Malawi",
  "MY" => "Malaysia",
  "MV" => "Maldives",
  "ML" => "Mali",
  "MT" => "Malta",
  "MH" => "Marshall Islands",
  "MQ" => "Martinique",
  "MR" => "Mauritania",
  "MU" => "Mauritius",
  "YT" => "Mayotte",
  "MX" => "Mexico",
  "FM" => "Micronesia, Federated States of",
  "MD" => "Moldova, Republic of",
  "MC" => "Monaco",
  "MN" => "Mongolia",
  "ME" => "Montenegro",
  "MS" => "Montserrat",
  "MA" => "Morocco",
  "MZ" => "Mozambique",
  "MM" => "Myanmar",
  "NA" => "Namibia",
  "NR" => "Nauru",
  "NP" => "Nepal",
  "NL" => "Netherlands",
  "AN" => "Netherlands Antilles",
  "NC" => "New Caledonia",
  "NZ" => "New Zealand",
  "NI" => "Nicaragua",
  "NE" => "Niger",
  "NG" => "Nigeria",
  "NU" => "Niue",
  "NF" => "Norfolk Island",
  "MP" => "Northern Mariana Islands",
  "NO" => "Norway",
  "OM" => "Oman",
  "PK" => "Pakistan",
  "PW" => "Palau",
  "PS" => "Palestinian Territory, Occupied",
  "PA" => "Panama",
  "PG" => "Papua New Guinea",
  "PY" => "Paraguay",
  "PE" => "Peru",
  "PH" => "Philippines",
  "PN" => "Pitcairn",
  "PL" => "Poland",
  "PT" => "Portugal",
  "PR" => "Puerto Rico",
  "QA" => "Qatar",
  "RE" => "Reunion",
  "RO" => "Romania",
  "RU" => "Russian Federation",
  "RW" => "Rwanda",
  "SH" => "Saint Helena",
  "KN" => "Saint Kitts and Nevis",
  "LC" => "Saint Lucia",
  "PM" => "Saint Pierre and Miquelon",
  "VC" => "Saint Vincent and The Grenadines",
  "WS" => "Samoa",
  "SM" => "San Marino",
  "ST" => "Sao Tome and Principe",
  "SA" => "Saudi Arabia",
  "SN" => "Senegal",
  "RS" => "Serbia",
  "SC" => "Seychelles",
  "SL" => "Sierra Leone",
  "SG" => "Singapore",
  "SK" => "Slovakia",
  "SI" => "Slovenia",
  "SB" => "Solomon Islands",
  "SO" => "Somalia",
  "ZA" => "South Africa",
  "GS" => "South Georgia and The South Sandwich Islands",
  "ES" => "Spain",
  "LK" => "Sri Lanka",
  "SD" => "Sudan",
  "SR" => "Suriname",
  "SJ" => "Svalbard and Jan Mayen",
  "SZ" => "Swaziland",
  "SE" => "Sweden",
  "CH" => "Switzerland",
  "SY" => "Syrian Arab Republic",
  "TW" => "Taiwan, Province of China",
  "TJ" => "Tajikistan",
  "TZ" => "Tanzania, United Republic of",
  "TH" => "Thailand",
  "TL" => "Timor-leste",
  "TG" => "Togo",
  "TK" => "Tokelau",
  "TO" => "Tonga",
  "TT" => "Trinidad and Tobago",
  "TN" => "Tunisia",
  "TR" => "Turkey",
  "TM" => "Turkmenistan",
  "TC" => "Turks and Caicos Islands",
  "TV" => "Tuvalu",
  "UG" => "Uganda",
  "UA" => "Ukraine",
  "AE" => "United Arab Emirates",
  "GB" => "United Kingdom",
  "US" => "United States",
  "UM" => "United States Minor Outlying Islands",
  "UY" => "Uruguay",
  "UZ" => "Uzbekistan",
  "VU" => "Vanuatu",
  "VE" => "Venezuela",
  "VN" => "Viet Nam",
  "VG" => "Virgin Islands, British",
  "VI" => "Virgin Islands, U.S.",
  "WF" => "Wallis and Futuna",
  "EH" => "Western Sahara",
  "YE" => "Yemen",
  "ZM" => "Zambia",
  "ZW" => "Zimbabwe",
];
$actual_link =
  (empty($_SERVER["HTTPS"]) ? "http" : "https") .
  "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$formTitle =
  isset($gatedResourceInfo["gatedFormTitle"]) &&
  strlen($gatedResourceInfo["gatedFormTitle"]) > 0
    ? $gatedResourceInfo["gatedFormTitle"]
    : "Access exclusive insights";
$formContent =
  isset($gatedResourceInfo["gatedFormCopy"]) &&
  strlen($gatedResourceInfo["gatedFormCopy"]) > 0
    ? $gatedResourceInfo["gatedFormCopy"]
    : "<p>Please fill out the following information to view the insights.</p>";
$formCtaTitle =
  isset($gatedResourceInfo["gatedFormCtaTitle"]) &&
  strlen($gatedResourceInfo["gatedFormCtaTitle"]) > 0
    ? $gatedResourceInfo["gatedFormCtaTitle"]
    : "Get access";
$formImage =
  isset($gatedResourceInfo["gatedFormCtaTitle"]) &&
  ($f = File::getByID($gatedResourceInfo["gatedFormCtaTitle"])) &&
  is_object($f)
    ? $f->getDownloadURL()
    : false;
$formPath =
  isset($gatedResourceInfo["gatedFormCustomId"]) &&
  strlen($gatedResourceInfo["gatedFormCustomId"]) > 0
    ? $gatedResourceInfo["gatedFormCustomId"]
    : "PARDOT_FORM_URL_HANDLER";
$formId = substr($formPath, strrpos($formPath, "/") + 1);
$formImage = false;
if (
  isset($gatedResourceInfo["gatedFormImageId"]) &&
  ($f = File::getByID($gatedResourceInfo["gatedFormImageId"])) &&
  is_object($f)
) {
    $formImage = $f->getDownloadURL();
}
?>

<div id="gated_content_form" data-formtype="default" class="<?php if (
  $formImage
) {
    echo "has_img";
} ?>" data-formid="<?php echo $formId; ?>">
    <p class="back"><a id="gated_back" href="/insights">Back</a></p>
    <?php if ($formImage) {
    echo '<div class="img_col" style="background-image: url(' .
        $formImage .
        ');"></div><div class="content_col">';
} ?>
    <div class="container">
        <h3><?php echo $formTitle; ?></h3>
        <?php echo $formContent; ?>
        <div class="pfah-wrapper">
            <form data-action="<?php echo $formPath; ?>" class="pfah-form" id="gated_form_obj">
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="First Name" class="pfah-input" required>
                    <label for="first_name">First Name <span>*</span></label>
                </div>
                <div class="form-group">
                    <input type="text" name="last_name" placeholder="Last Name" class="pfah-input" required>
                    <label for="last_name">Last Name <span>*</span></label>
                </div>
                <div class="form-group">
                    <input type="text" name="company" placeholder="Company Name" class="pfah-input" required>
                    <label for="company">Company <span>*</span></label>
                </div>
                <div class="form-group">
                    <input type="text" name="job_title" placeholder="Job Title" class="pfah-input" required>
                    <label for="job_title">Job Title <span>*</span></label>
                </div>
                <div class="form-group">
                    <input type="text" name="email" placeholder="Email" class="pfah-input" required>
                    <label for="email">Email <span>*</span></label>
                </div>
                <div class="form-group">
                    <select name="country" class="pfah-input select" required>
                        <option placeholder disabled selected value="">Country</option>
                        <?php foreach ($countries as $key => $value) {
    echo "<option value='" .
                            $key .
                            "'>" .
                            $value .
                            "</option>";
} ?>
                    </select>
                </div>
                <div class="form-group gdpr-box">
                    <p><strong>Would you like to hear from us in the future?</strong></p>
                    <p>Stay connected!</p>
                    <label for="gdpr_choice">I would like more information.</label>
                    <p>
                        <input type="radio" name="gdpr_choice" id="gdpr_accept" value="1"> Accept
                    </p>
                    <p>
                        <input type="radio" name="gdpr_choice" id="gdpr_decline" value="2"> Decline
                    </p>
                </div>
                <div class="form-group">
                    <input type="hidden" name="gated_source" id="gated_source" class="pfah-input">
                    <input type="hidden" name="GDPR_Consent_Source" id="GDPR_Consent_Source" value="<?php echo $gatedResourceInfo[
                      "gatedFormPardotName"
                    ]; ?>">
                    <input type="hidden" name="GDPR_Consent_Verbiage" id="GDPR_Consent_Verbiage" value="">
                    <input type="hidden" name="GDPR_Consent_Status" id="GDPR_Consent_Status" value="">
                    <input type="hidden" name="GDPR_Consent_Date" id="GDPR_Consent_Date" value="<?php echo date(
                        "Y-m-d"
                    ); ?>">
                    <input id="website" name="website" type="hidden" value="">
                    <input id="un" name="un" type="hidden" value="">
                    <button type="submit" class="btn submit" class="pfah-input" id="gated-submit-btn"><?php echo $formCtaTitle; ?></button>
                </div>
            </form>
            <aside class="pfah-done">
                <div class="pfah-done-text">
                    <h3>Success!</h3>
                    <p>Thank you for requesting access. If the page does not reload automatically within ten seconds please refresh to see this exclusive content.</p>
                </div>
            </aside>
            <aside class="pfah-error">
                <span class="pfah-error-text">There was an error submitting the form. Please reload and try again.</span>
            </aside>
        </div>
    </div>
    <?php if ($formImage) {
                        echo "</div>";
                    } ?>
</div>

<script>
setTimeout(() => {
    document.getElementById('gated_source').value = window.location.href;
}, 2000);
</script>