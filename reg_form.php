<?php
include('dbconfig.php');
include('includes/header.php');
if(isset($_GET['username'])){
    $username=$_GET['username'];
    $password=$_GET['password'];
}
else{
    header('Location: register.php');
}
?>
<style>
    div.dropdown-menu.open { width: 100%; } ul.dropdown-menu.inner>li>a { white-space: initial; }
    a:hover, a:active, a:visited:hover{
        color:#bc0203 !important;
        text-decoration: none;
    }
    .hover-color:hover{
        color: #bc0203 !important; 
    }
    .container {
        height: 100vh;
        overflow: auto;
        padding-top: 120px;
    }
    html{
        scroll-behavior: smooth;
    }
    ul {
        list-style-type: none;
        padding: 50px;
        margin-left: -47px;
    }
    ul li{
        padding-top: 25px;
        padding-bottom: 25px;
    }
</style>
<div class="container-fluid" style="background-color:#f5f5f5" width="100%">
    <div class="progress sticky-top col-12" style="height: 5px; " width="100%">
        <div class="progress-bar" id="progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="row pt-4 ">
        <div class="col-2">
            <div id="progress" class="p-4 mt-4 mb-2 sticky-top ">
                <div class="text-center align-items-center">
                    <ul class="align-items-center">
                        <li class="mt-4">
                            <a href="#companyForm" class="row align-items-center hover-color pt-4 text-primary companyPB">
                                <div class="col-6">
                                    <i class="fa fa-building fa-10x" style="font-size: 3em;" aria-hidden="true"></i>
                                </div>
                                <div class="col-6">
                                    <h5>Company</h5>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#contactForm" class="row align-items-center hover-color text-secondary contactPB ">
                                <div class="col-6">
                                    <i class="fa fa-id-card fa-10x" style="font-size: 3em;" aria-hidden="true"></i>
                                </div>
                                <div class="col-6">
                                    <h5>Contact Details</h5>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#ps_card" class="row align-items-center hover-color text-secondary psPB">
                                <div class="col-6">
                                    <i class="fa fa-archive fa-10x " style="font-size: 3em;" aria-hidden="true"></i>
                                </div>
                                <div class="col-6">
                                    <h5 >Products/Services</h5>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#attachForm" class="row align-items-center hover-color text-secondary attachPB">
                                <div class="col-6">
                                    <i class="fa fa-upload fa-10x " style="font-size: 3em;" aria-hidden="true"></i>
                                </div>
                                <div class="col-6">
                                    <h5 >Attachments</h5>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-10">
            <!-- DataTales Example -->
            <form action="reg_code.php" id="reg_form" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="username" value="<?php echo $username;?>">
                <input type="hidden" name="password" value="<?php echo $password?>">
                <!-- SIDE BAR -->
                <!-- company form start -->
                <div class="container" id="companyForm">
                    <div class="card shadow visibleCheck"  id="compChk">
                        <div class="card-header text-primary "><i class="fa fa-building mr-2" aria-hidden="true"></i>Company Details 
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Company Name: *</label>
                                        <input type="text" name="comp_name" minlength="4" maxlength="40" class="form-control check-fields1" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Company Website:</label>
                                        <input type="text" name="website" minlength="5" maxlength="30" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-6">
                                    <label for="">Type of Business: *</label>
                                    <div class="form-group bTypeDiv">
                                        <label class="radio mr-3 ml-4">
                                            <input type="radio" name="bType" value="oem" class="mr-1 check-fields1" required>Manufacturer/OEM (Original Equipment Manufacturer)
                                        </label><br>
                                        <label class="radio mr-3 ml-4">
                                            <input type="radio" name="bType" value="distributor" class="mr-1 check-fields1" required>Authorized Distributor
                                        </label><br>
                                        <label class="radio mr-3 ml-4">
                                            <input type="radio" name="bType" value="trading" class="mr-1 check-fields1" required>Trading
                                        </label><br>
                                        <label class="radio mr-3 ml-4">
                                            <input type="radio" name="bType" value="subcon" class="mr-1 check-fields1" required>Subcontractor
                                        </label>  <br> 
                                        <label class="radio mr-3 ml-4">
                                            <input type="radio" name="bType" value="laborSupply" class="mr-1 check-fields1" required>Labor Supply
                                        </label><br>
                                        <label class="radio mr-3 ml-4">
                                            <input type="radio" name="bType" value="agency" class="mr-1 check-fields1" required>Recruitment Agency
                                        </label>  
                                    </div>
                                    
                                </div>
                                <div class="col-6">
                                    <label for="">Scope of Business Authorized: *</label>
                                    <div class="form-row form-group checkbox-group1  check-fields1" required>
                                        <div class=" col-6">
                                            <input name ="scopeB[]" id="all" class="sba" type="checkbox" value="All">
                                            <label >All over UAE</label><br>
                                            <input name ="scopeB[]" class="sba" type="checkbox" value="Abu Dhabi">
                                            <label>Abu Dhabi</label><br>
                                            <input name ="scopeB[]" class="sba" type="checkbox" value="Ajman">
                                            <label>Ajman</label><br>
                                            <input  name ="scopeB[]" class="sba" type="checkbox" value="Dubai">
                                            <label>Dubai</label><br>
                                        </div>
                                        <div class=" col-6">
                                            <input  name ="scopeB[]" class="sba" type="checkbox" value="Fujairah">
                                            <label>Fujairah</label><br>
                                            <input  name ="scopeB[]" class="sba" type="checkbox" value="Ras al Khaimah">
                                            <label>Ras al Khaimah</label><br>
                                            <input name ="scopeB[]" class="sba" type="checkbox" value="Sharjah">
                                            <label>Sharjah</label><br>
                                            <input name ="scopeB[]" class="sba" type="checkbox" value="Umm Al Quwain">
                                            <label>Umm Al Quwain</label>
                                        </div>
                                    </div>
                                    <label for="">Company Profile: </label> <span class="font-italic">(.pdf)</span>
                                    <input type="file" id="formFile1" size="50" name="comp_profile" class="form-control check-fields5" onchange="return fileValidation()"> 
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- company end -->
                <!-- contact form start -->
                <div class="container" id="contactForm">
                    <div class="card shadow visibleCheck"  id="contChk">
                        <div class="card-header text-primary"><i class="fa fa-id-card mr-2" aria-hidden="true"></i>Contact Details</div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-6 pl-2 pr-5">
                                    <div class="form-group">
                                        <label for="">Contact Person Name: *</label>
                                        <input type="text" minlength="5" maxlength="30" name="cName" class="form-control check-fields2" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Position: *</label>
                                        <input type="text" name="cPos" minlength="5" maxlength="30" class="form-control check-fields2" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Contact Mobile: *</label>
                                        <input type="text" pattern="[0-9]*" name="cMobile" minlength="10" maxlength="10" class="form-control check-fields2" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Contact Landline: *</label>
                                        <input type="number" name="cLand" minlength="5" maxlength="10" class="form-control check-fields2" required>
                                    </div>
                                    <div class="form-group ">
                                        <label for="">Email: *</label>
                                        <input type="email" name="cMail"  minlength="5" maxlength="30"  class="form-control check-fields2" required>
                                    </div>
                                </div>
                                <div class="col-6 pr-2 pl-2">
                                    <div class="form-group">
                                        <label for="">Manager's Name: *</label>
                                        <input type="text" name="mName" minlength="6" maxlength="30" class="form-control check-fields2" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Contact Mobile: *</label>
                                        <input type="text" pattern="[0-9]*" name="mMobile" minlength="10" maxlength="10" class="form-control check-fields2" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Contact Landline: *</label>
                                        <input type="number" name="mLand" minlength="5" maxlength="10" class="form-control check-fields2" required>
                                    </div>
                                    <div class="form-group ">
                                        <label for="">Email: *</label>
                                        <input type="email" name="mMail" minlength="5" maxlength="30"  class="form-control check-fields2" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-6">
                                    <label for="">Receive notifications for: *</label>
                                    <select name="group[]" id="" class="form-control selectpicker" data-live-search="true" required multiple>
                                        <?php
                                            $q_grp="SELECT * FROM email_group WHERE Email_Grp_Status=1 ORDER BY Email_Grp_Name ASC";
                                            $q_grp_run=mysqli_query($connection, $q_grp);
                                            if(mysqli_num_rows($q_grp_run)>0){
                                                while($row_g=mysqli_fetch_assoc($q_grp_run)){
                                                    $grp_id=$row_g['Email_Grp_Id'];
                                                    $grp_name=$row_g['Email_Grp_Name'];
                                                    $options.= '<option value="'.$grp_id.'">'.$grp_name.'</option>';
                                                }
                                                echo $options;
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- contact end -->
                <div class="container" id="ps_card">
                    <!-- product details form start -->
                    <div class="d-none visibleCheck" id="productForm">
                        <div class="card shadow" >
                            <div class="card-header text-primary"><i class="fa fa-archive mr-2" aria-hidden="true"></i>Product Details </div>
                            <div class="card-body">
                                <div class="table table-responsive">
                                    <table class="table table-bordered" id="productTbl">
                                        <tr>
                                            <td class="col-2">
                                                <div class="form-group">
                                                    <label for="">Product Category *</label><br>
                                                    <select name="dept_id[]" id="dept_id" class="form-control check-fields3 dept_opt">
                                                        <option value="">Select Category</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="col-5">
                                                <div class="form-group">
                                                    <label for="mat_op" >Description *</label><br>
                                                    <select id="prod_desc" name="prod_desc[]" class="form-control selectpicker " data-live-search="true" style="z-index:2">  </select>
                                                    <div class="form-row mt-2" >
                                                        <div class="col-4"  class="float-right">
                                                            <label for=""  class="float-right mt-2">If others, please specify:</label> 
                                                        </div>
                                                        <div class="col-8">
                                                            <input type="text" id="input" class="form-control othersInput">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="col-3">
                                                <div class="form-group">
                                                    <label for="">Brand(s)</label><br>
                                                    <input name="prod_Brand[]" minlength="5" maxlength="50" type="text" class="form-control ">
                                                </div>
                                            </td>
                                            <td class="col-2">
                                                <div class="form-group">
                                                    <label for="">Country of Origin</label><br>
                                                    <select id="country_opt" name="country[]" class="form-control selectpicker " data-live-search="true">
                                                        <option value="">Select Country</option>
                                                        <option value="Afganistan">Afghanistan</option>
                                                        <option value="Albania">Albania</option>
                                                        <option value="Algeria">Algeria</option>
                                                        <option value="American Samoa">American Samoa</option>
                                                        <option value="Andorra">Andorra</option>
                                                        <option value="Angola">Angola</option>
                                                        <option value="Anguilla">Anguilla</option>
                                                        <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                                        <option value="Argentina">Argentina</option>
                                                        <option value="Armenia">Armenia</option>
                                                        <option value="Aruba">Aruba</option>
                                                        <option value="Australia">Australia</option>
                                                        <option value="Austria">Austria</option>
                                                        <option value="Azerbaijan">Azerbaijan</option>
                                                        <option value="Bahamas">Bahamas</option>
                                                        <option value="Bahrain">Bahrain</option>
                                                        <option value="Bangladesh">Bangladesh</option>
                                                        <option value="Barbados">Barbados</option>
                                                        <option value="Belarus">Belarus</option>
                                                        <option value="Belgium">Belgium</option>
                                                        <option value="Belize">Belize</option>
                                                        <option value="Benin">Benin</option>
                                                        <option value="Bermuda">Bermuda</option>
                                                        <option value="Bhutan">Bhutan</option>
                                                        <option value="Bolivia">Bolivia</option>
                                                        <option value="Bonaire">Bonaire</option>
                                                        <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                                        <option value="Botswana">Botswana</option>
                                                        <option value="Brazil">Brazil</option>
                                                        <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                                        <option value="Brunei">Brunei</option>
                                                        <option value="Bulgaria">Bulgaria</option>
                                                        <option value="Burkina Faso">Burkina Faso</option>
                                                        <option value="Burundi">Burundi</option>
                                                        <option value="Cambodia">Cambodia</option>
                                                        <option value="Cameroon">Cameroon</option>
                                                        <option value="Canada">Canada</option>
                                                        <option value="Canary Islands">Canary Islands</option>
                                                        <option value="Cape Verde">Cape Verde</option>
                                                        <option value="Cayman Islands">Cayman Islands</option>
                                                        <option value="Central African Republic">Central African Republic</option>
                                                        <option value="Chad">Chad</option>
                                                        <option value="Channel Islands">Channel Islands</option>
                                                        <option value="Chile">Chile</option>
                                                        <option value="China">China</option>
                                                        <option value="Christmas Island">Christmas Island</option>
                                                        <option value="Cocos Island">Cocos Island</option>
                                                        <option value="Colombia">Colombia</option>
                                                        <option value="Comoros">Comoros</option>
                                                        <option value="Congo">Congo</option>
                                                        <option value="Cook Islands">Cook Islands</option>
                                                        <option value="Costa Rica">Costa Rica</option>
                                                        <option value="Cote DIvoire">Cote DIvoire</option>
                                                        <option value="Croatia">Croatia</option>
                                                        <option value="Cuba">Cuba</option>
                                                        <option value="Curaco">Curacao</option>
                                                        <option value="Cyprus">Cyprus</option>
                                                        <option value="Czech Republic">Czech Republic</option>
                                                        <option value="Denmark">Denmark</option>
                                                        <option value="Djibouti">Djibouti</option>
                                                        <option value="Dominica">Dominica</option>
                                                        <option value="Dominican Republic">Dominican Republic</option>
                                                        <option value="East Timor">East Timor</option>
                                                        <option value="Ecuador">Ecuador</option>
                                                        <option value="Egypt">Egypt</option>
                                                        <option value="El Salvador">El Salvador</option>
                                                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                        <option value="Eritrea">Eritrea</option>
                                                        <option value="Estonia">Estonia</option>
                                                        <option value="Ethiopia">Ethiopia</option>
                                                        <option value="Falkland Islands">Falkland Islands</option>
                                                        <option value="Faroe Islands">Faroe Islands</option>
                                                        <option value="Fiji">Fiji</option>
                                                        <option value="Finland">Finland</option>
                                                        <option value="France">France</option>
                                                        <option value="French Guiana">French Guiana</option>
                                                        <option value="French Polynesia">French Polynesia</option>
                                                        <option value="French Southern Ter">French Southern Ter</option>
                                                        <option value="Gabon">Gabon</option>
                                                        <option value="Gambia">Gambia</option>
                                                        <option value="Georgia">Georgia</option>
                                                        <option value="Germany">Germany</option>
                                                        <option value="Ghana">Ghana</option>
                                                        <option value="Gibraltar">Gibraltar</option>
                                                        <option value="Great Britain">Great Britain</option>
                                                        <option value="Greece">Greece</option>
                                                        <option value="Greenland">Greenland</option>
                                                        <option value="Grenada">Grenada</option>
                                                        <option value="Guadeloupe">Guadeloupe</option>
                                                        <option value="Guam">Guam</option>
                                                        <option value="Guatemala">Guatemala</option>
                                                        <option value="Guinea">Guinea</option>
                                                        <option value="Guyana">Guyana</option>
                                                        <option value="Haiti">Haiti</option>
                                                        <option value="Hawaii">Hawaii</option>
                                                        <option value="Honduras">Honduras</option>
                                                        <option value="Hong Kong">Hong Kong</option>
                                                        <option value="Hungary">Hungary</option>
                                                        <option value="Iceland">Iceland</option>
                                                        <option value="Indonesia">Indonesia</option>
                                                        <option value="India">India</option>
                                                        <option value="Iran">Iran</option>
                                                        <option value="Iraq">Iraq</option>
                                                        <option value="Ireland">Ireland</option>
                                                        <option value="Isle of Man">Isle of Man</option>
                                                        <option value="Israel">Israel</option>
                                                        <option value="Italy">Italy</option>
                                                        <option value="Jamaica">Jamaica</option>
                                                        <option value="Japan">Japan</option>
                                                        <option value="Jordan">Jordan</option>
                                                        <option value="Kazakhstan">Kazakhstan</option>
                                                        <option value="Kenya">Kenya</option>
                                                        <option value="Kiribati">Kiribati</option>
                                                        <option value="Korea North">Korea North</option>
                                                        <option value="Korea Sout">Korea South</option>
                                                        <option value="Kuwait">Kuwait</option>
                                                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                        <option value="Laos">Laos</option>
                                                        <option value="Latvia">Latvia</option>
                                                        <option value="Lebanon">Lebanon</option>
                                                        <option value="Lesotho">Lesotho</option>
                                                        <option value="Liberia">Liberia</option>
                                                        <option value="Libya">Libya</option>
                                                        <option value="Liechtenstein">Liechtenstein</option>
                                                        <option value="Lithuania">Lithuania</option>
                                                        <option value="Luxembourg">Luxembourg</option>
                                                        <option value="Macau">Macau</option>
                                                        <option value="Macedonia">Macedonia</option>
                                                        <option value="Madagascar">Madagascar</option>
                                                        <option value="Malaysia">Malaysia</option>
                                                        <option value="Malawi">Malawi</option>
                                                        <option value="Maldives">Maldives</option>
                                                        <option value="Mali">Mali</option>
                                                        <option value="Malta">Malta</option>
                                                        <option value="Marshall Islands">Marshall Islands</option>
                                                        <option value="Martinique">Martinique</option>
                                                        <option value="Mauritania">Mauritania</option>
                                                        <option value="Mauritius">Mauritius</option>
                                                        <option value="Mayotte">Mayotte</option>
                                                        <option value="Mexico">Mexico</option>
                                                        <option value="Midway Islands">Midway Islands</option>
                                                        <option value="Moldova">Moldova</option>
                                                        <option value="Monaco">Monaco</option>
                                                        <option value="Mongolia">Mongolia</option>
                                                        <option value="Montserrat">Montserrat</option>
                                                        <option value="Morocco">Morocco</option>
                                                        <option value="Mozambique">Mozambique</option>
                                                        <option value="Myanmar">Myanmar</option>
                                                        <option value="Nambia">Nambia</option>
                                                        <option value="Nauru">Nauru</option>
                                                        <option value="Nepal">Nepal</option>
                                                        <option value="Netherland Antilles">Netherland Antilles</option>
                                                        <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                                        <option value="Nevis">Nevis</option>
                                                        <option value="New Caledonia">New Caledonia</option>
                                                        <option value="New Zealand">New Zealand</option>
                                                        <option value="Nicaragua">Nicaragua</option>
                                                        <option value="Niger">Niger</option>
                                                        <option value="Nigeria">Nigeria</option>
                                                        <option value="Niue">Niue</option>
                                                        <option value="Norfolk Island">Norfolk Island</option>
                                                        <option value="Norway">Norway</option>
                                                        <option value="Oman">Oman</option>
                                                        <option value="Pakistan">Pakistan</option>
                                                        <option value="Palau Island">Palau Island</option>
                                                        <option value="Palestine">Palestine</option>
                                                        <option value="Panama">Panama</option>
                                                        <option value="Papua New Guinea">Papua New Guinea</option>
                                                        <option value="Paraguay">Paraguay</option>
                                                        <option value="Peru">Peru</option>
                                                        <option value="Phillipines">Philippines</option>
                                                        <option value="Pitcairn Island">Pitcairn Island</option>
                                                        <option value="Poland">Poland</option>
                                                        <option value="Portugal">Portugal</option>
                                                        <option value="Puerto Rico">Puerto Rico</option>
                                                        <option value="Qatar">Qatar</option>
                                                        <option value="Republic of Montenegro">Republic of Montenegro</option>
                                                        <option value="Republic of Serbia">Republic of Serbia</option>
                                                        <option value="Reunion">Reunion</option>
                                                        <option value="Romania">Romania</option>
                                                        <option value="Russia">Russia</option>
                                                        <option value="Rwanda">Rwanda</option>
                                                        <option value="St Barthelemy">St Barthelemy</option>
                                                        <option value="St Eustatius">St Eustatius</option>
                                                        <option value="St Helena">St Helena</option>
                                                        <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                                        <option value="St Lucia">St Lucia</option>
                                                        <option value="St Maarten">St Maarten</option>
                                                        <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                                        <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                                        <option value="Saipan">Saipan</option>
                                                        <option value="Samoa">Samoa</option>
                                                        <option value="Samoa American">Samoa American</option>
                                                        <option value="San Marino">San Marino</option>
                                                        <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                                        <option value="Saudi Arabia">Saudi Arabia</option>
                                                        <option value="Senegal">Senegal</option>
                                                        <option value="Seychelles">Seychelles</option>
                                                        <option value="Sierra Leone">Sierra Leone</option>
                                                        <option value="Singapore">Singapore</option>
                                                        <option value="Slovakia">Slovakia</option>
                                                        <option value="Slovenia">Slovenia</option>
                                                        <option value="Solomon Islands">Solomon Islands</option>
                                                        <option value="Somalia">Somalia</option>
                                                        <option value="South Africa">South Africa</option>
                                                        <option value="Spain">Spain</option>
                                                        <option value="Sri Lanka">Sri Lanka</option>
                                                        <option value="Sudan">Sudan</option>
                                                        <option value="Suriname">Suriname</option>
                                                        <option value="Swaziland">Swaziland</option>
                                                        <option value="Sweden">Sweden</option>
                                                        <option value="Switzerland">Switzerland</option>
                                                        <option value="Syria">Syria</option>
                                                        <option value="Tahiti">Tahiti</option>
                                                        <option value="Taiwan">Taiwan</option>
                                                        <option value="Tajikistan">Tajikistan</option>
                                                        <option value="Tanzania">Tanzania</option>
                                                        <option value="Thailand">Thailand</option>
                                                        <option value="Togo">Togo</option>
                                                        <option value="Tokelau">Tokelau</option>
                                                        <option value="Tonga">Tonga</option>
                                                        <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                                        <option value="Tunisia">Tunisia</option>
                                                        <option value="Turkey">Turkey</option>
                                                        <option value="Turkmenistan">Turkmenistan</option>
                                                        <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                                        <option value="Tuvalu">Tuvalu</option>
                                                        <option value="Uganda">Uganda</option>
                                                        <option value="United Kingdom">United Kingdom</option>
                                                        <option value="Ukraine">Ukraine</option>
                                                        <option value="United Arab Erimates">United Arab Emirates</option>
                                                        <option value="United States of America">United States of America</option>
                                                        <option value="Uraguay">Uruguay</option>
                                                        <option value="Uzbekistan">Uzbekistan</option>
                                                        <option value="Vanuatu">Vanuatu</option>
                                                        <option value="Vatican City State">Vatican City State</option>
                                                        <option value="Venezuela">Venezuela</option>
                                                        <option value="Vietnam">Vietnam</option>
                                                        <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                                        <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                                        <option value="Wake Island">Wake Island</option>
                                                        <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                                        <option value="Yemen">Yemen</option>
                                                        <option value="Zaire">Zaire</option>
                                                        <option value="Zambia">Zambia</option>
                                                        <option value="Zimbabwe">Zimbabwe</option>
                                                        </select>
                                                </div>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <div align="right">
                                        <button type="button" name="add" id="addBtn" class="btn btn-success btn-xs">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- product service det end -->
                    <!-- subcon/labor supply services form start -->
                    <div class="d-none visibleCheck" id="serviceForm">
                        <div class="card shadow  " >
                            <div class="card-header text-primary"><i class="fa fa-archive mr-2" aria-hidden="true"></i>Service Details </div>
                            <div class="card-body">
                                <div class="table table-responsive">
                                    <table  class="table table-bordered" id="serviceTbl">
                                        <tr>
                                            <td class="col-3">
                                                <div class="form-group">
                                                    <label for="">Department *</label><br>
                                                    <select name="sdept_id[]" class="form-control check-fields4 dept_opt" required>
                                                        <option value="">Select Deparment</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="col-3">
                                                <div class="form-group">
                                                    <label for="">Description *</label><br>
                                                    <input name="serv_desc[]" minlength="5" maxlength="35" type="text" class="form-control check-fields4" required>
                                                    <span class="text-sm font-italic">ex. Electrician, Helper</span>
                                                </div>
                                            </td>
                                            <td class="col-3">
                                                <div class="form-group">
                                                    <label for="">Unit</label><br>
                                                    <select name="serv_unit[]"  maxlength="45"  class="form-control check-fields4" required>
                                                        <option selected>Select Unit</option>
                                                        <option value="price/Hour">price/Hour</option>
                                                        <option value="price/Hour">price/Day</option>
                                                        <option value="price/Sq. F">price/Sq. F</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="col-2">
                                                <div class="form-group">
                                                    <label for="">Rate *</label><br>
                                                    <input name="serv_rate[]" type="number" maxlength="20" class="form-control check-fields4" required>
                                                    <span class="text-sm font-italic">price(AED) per Unit</span>
                                                </div>
                                            </td>
                                            <td class="col-2">
                                            </td>
                                        </tr>
                                    </table>
                                    <div align="right">
                                        <button type="button" name="add" id="adBtn" class="btn btn-success btn-xs">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- subcon/labor supply service end -->
                <!-- attachments form start -->
                <div class="container visibleCheck" id="attachForm">
                    <div class="card shadow" id="attChk">
                        <div class="card-header text-primary"><i class="fa fa-upload mr-2" aria-hidden="true"></i>Attachments</div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">TRN: </label>
                                        <input type="number" name="trn" maxlength="15" minlength="15" class="form-control ">
                                    </div>
                                    <label for="">Emirate in Trade License: *</label>
                                    <div class="form-row form-group checkbox-group1 ml-4 check-fields5" required>
                                        <div class="col-6">
                                            <input name="emirateInTL" class="check-fields5" type="radio" value="Abu Dhabi">
                                            <label>Abu Dhabi</label><br>
                                            <input  name="emirateInTL" class="check-fields5" type="radio" value="Ajman">
                                            <label>Ajman</label><br>
                                            <input name="emirateInTL" class="check-fields5" type="radio" value="Dubai">
                                            <label>Dubai</label><br>
                                            <input  name="emirateInTL" class="check-fields5" type="radio" value="Fujairah">
                                            <label>Fujairah</label><br>
                                        </div>
                                        <div class="col-6">
                                            <input  name="emirateInTL" class="check-fields5" type="radio" value="Ras al Khaimah">
                                            <label>Ras al Khaimah</label><br>
                                            <input name="emirateInTL" class="check-fields5" type="radio" value="Sharjah">
                                            <label>Sharjah</label><br>
                                            <input name="emirateInTL" class="check-fields5" type="radio" value="Umm Al Quwain">
                                            <label>Umm Al Quwain</label><br>
                                        </div>
                                    </div>
                                    <!-- <div class="">
                                        <label for="">Receive post notifications for:</label>
                                        <select name="" id="groupOpt" class="form-control selectpiker required col-7 mb-4" required></select>
                                    </div> -->
                                    <!-- <div class="d-none">
                                        <label for="">Receive post notifications for:</label>
                                        <select name="" id="groupOpt" class="form-control selectpiker required col-7 mb-4" required></select>
                                    </div> -->
                                </div>
                                <div class="col-6">
                                    <label for="formFile2" class="form-label">Upload Trade License: *</label> <span class="font-italic">(.pdf)</span>
                                    <input type="file" id="formFile2" size="50" name="file" class="form-control check-fields5" onchange="return fileValidation2()" required> <br>
                                    <label for="">Licence Date of Expiry *</label>
                                    <input type="date" name="date_exp" class="form-control" required><br>
                                    <label for="">Company Stamp: *</label> <span class="font-italic">(.png/.jpeg/.jpg)</span> <br>
                                    <input type="file" id="compStamp" name="compStamp" class="form-control imgFile" class="imgFile" required><br>
                                    
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-6">
                                    <label for="" class="font-weight-bold">Upload Company Authorized Signatories</label><span class="font-italic"> (.png/.jpeg/.jpg)</span><br>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-2">
                                    <label for="" class="mt-1">Name of Signatory 1*</label>
                                </div>
                                <div class="col-4">
                                    <input type="text" name="s1_name" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col-4">
                                    <input type="file" name="s1_1" id="s1_1" class="imgFile" required>
                                </div>
                                <div class="col-4">
                                    <input type="file" name="s1_2" id="s1_2" class="imgFile" required>
                                </div>
                                <div class="col-4">
                                    <input type="file" name="s1_3" id="s1_3" class="imgFile" required>
                                </div>
                            </div>
                            <div class="form-row mt-3">
                                <div class="col-2">
                                    <label for="" class="mt-1">Name of Signatory 2*</label>
                                </div>
                                <div class="col-4">
                                    <input type="text" name="s2_name" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col-4">
                                    <input type="file" name="s2_1" id="s2_1" class="imgFile" required>
                                </div>
                                <div class="col-4">
                                    <input type="file" name="s2_2" id="s2_2" class="imgFile" required>
                                </div>
                                <div class="col-4">
                                    <input type="file" name="s2_3" id="s2_3" class="imgFile" required>
                                </div>
                            </div>
                            <div class="form-row mt-3">
                                <div class="col-2">
                                    <label for="" class="mt-1">Name of Signatory 3*</label>
                                </div>
                                <div class="col-4">
                                    <input type="text" name="s3_name" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col-4">
                                    <input type="file" name="s3_1" id="s3_1" class="imgFile" required>
                                </div>
                                <div class="col-4">
                                    <input type="file" name="s3_2" id="s3_2" class="imgFile" required>
                                </div>
                                <div class="col-4">
                                    <input type="file" name="s3_3" id="s3_3" class="imgFile" required>
                                </div>
                            </div>
                            <div class="form-row mt-4">
                                <div class="form-check">
                                    <input type="checkbox" id="check" name="term" value="1" class=" form-check-input">
                                    <label for="">I Agree to the <span class="text-primary form-check-label">EMT Electromechanical Works LLC</span> Terms and Privacy Policy. <a href="#" class="termsHTML">view</a></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-4 mr-4" align="right">
                        <button class="btn btn-primary submit_load" id="submit"  name="submit" type="submit"> <i class="fas fa-spinner fa-spin mr-2" style="display:none;"></i><span>Submit</span></button>
                    </div>
                </div>
                <!-- attachments end -->
            </form>
            <!-- <form action="reg_code.php" method="post" class="d-none" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="col-4">
                        <input type="text" value="1" name="id";>
                        <input type="file" name="compStamp" id="testing" class="imgFile" required>
                    </div>
                </div>
                <button class="btn btn-primary "id=""  name="testBtn" type="submit">Submit</button>
            </form> -->
        </div>
    </div>
    <div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="agree" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Before you can complete your registration, you must accept the Terms and Privacy Policy</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- <div class="modal-body">
            </div> -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary termsHTML" data-dismiss="modal">View</button>
                <button type="button" id="yes" class="btn btn-primary yes">I Agree</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="termsHTML" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Terms and Conditions</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="text-primary">Introduction</h5> 
    
                <p>These Website Standard Terms and Conditions written on this webpage shall manage your use of our website, emtdubai.ae accessible at https://emtdubai.ae/.</p>
                
                <p>These Terms will be applied fully and affect to your use of this Website. By using this Website, you agreed to accept all terms and conditions written in here. You must not use this Website if you disagree with any of these Website Standard Terms and Conditions. </p>
                
                <p>Minors or people below 18 years old are not allowed to use this Website.</p>
                
                <h5 class="text-primary">Intellectual Property Rights</h5> 
                
                <p>Other than the content you own, under these Terms, EMT Electromechanical Works LLC and/or its licensors own all the intellectual property rights and materials contained in this Website.</p>
                
                <p>You are granted limited license only for purposes of viewing the material contained on this Website.</p>
                
                <h5 class="text-primary">Restrictions</h5> 
                
                <p>You are specifically restricted from all of the following:</p>
                
                <ul>
                    <li>publishing any Website material in any other media;</li>
                    <li>selling, sublicensing and/or otherwise commercializing any Website material;</li>
                    <li>publicly performing and/or showing any Website material;</li>
                    <li>using this Website in any way that is or may be damaging to this Website;</li>
                    <li>using this Website in any way that impacts user access to this Website;</li>
                    <li>using this Website contrary to applicable laws and regulations, or in any way may cause harm to the Website, or to any person or business entity;</li>
                    <li>engaging in any data mining, data harvesting, data extracting or any other similar activity in relation to this Website;</li>
                    <li>using this Website to engage in any advertising or marketing.</li>
                </ul>
                
                <p>Certain areas of this Website are restricted from being access by you and EMT Electromechanical Works LLC may further restrict access by you to any areas of this Website, at any time, in absolute discretion. Any user ID and password you may have for this Website are confidential and you must maintain confidentiality as well.</p>
                
                <h5 class="text-primary">Your Content</h5> 
                
                <p>In these Website Standard Terms and Conditions, "Your Content" shall mean any audio, video text, images or other material you choose to display on this Website. By displaying Your Content, you grant EMT Electromechanical Works LLC a non-exclusive, worldwide irrevocable, sub licensable license to use, reproduce, adapt, publish, translate and distribute it in any and all media.</p>
                
                <p>Your Content must be your own and must not be invading any third-party’s rights. EMT Electromechanical Works LLC reserves the right to remove any of Your Content from this Website at any time without notice.</p>
                
                <h5 class="text-primary">Your Privacy</h5> 
                
                <p>Please read Privacy Policy.</p>
                
                <h5 class="text-primary">No warranties</h5> 
                
                <p>This Website is provided "as is," with all faults, and EMT Electromechanical Works LLC express no representations or warranties, of any kind related to this Website or the materials contained on this Website. Also, nothing contained on this Website shall be interpreted as advising you.</p>
                
                <h5 class="text-primary">Limitation of liability</h5> 
                
                <p>In no event shall EMT Electromechanical Works LLC, nor any of its officers, directors and employees, shall be held liable for anything arising out of or in any way connected with your use of this Website whether such liability is under contract.  EMT Electromechanical Works LLC, including its officers, directors and employees shall not be held liable for any indirect, consequential or special liability arising out of or in any way related to your use of this Website.</p>
                
                <h5 class="text-primary">Indemnification</h5> 
                
                <p>You hereby indemnify to the fullest extent EMT Electromechanical Works LLC from and against any and/or all liabilities, costs, demands, causes of action, damages and expenses arising in any way related to your breach of any of the provisions of these Terms.</p>
                
                <h5 class="text-primary">Severability</h5> 
                
                <p>If any provision of these Terms is found to be invalid under any applicable law, such provisions shall be deleted without affecting the remaining provisions herein.</p>
                
                <h5 class="text-primary">Variation of Terms</h5> 
                
                <p>EMT Electromechanical Works LLC is permitted to revise these Terms at any time as it sees fit, and by using this Website you are expected to review these Terms on a regular basis.</p>
                
                <h5 class="text-primary">Assignment</h5> 
                
                <p>The EMT Electromechanical Works LLC is allowed to assign, transfer, and subcontract its rights and/or obligations under these Terms without any notification. However, you are not allowed to assign, transfer, or subcontract any of your rights and/or obligations under these Terms.</p>
                
                <h5 class="text-primary">Entire Agreement</h5> 
                    
                <p>These Terms constitute the entire agreement between EMT Electromechanical Works LLC and you in relation to your use of this Website, and supersede all prior agreements and understandings.</p>
                
                <h5 class="text-primary">Governing Law & Jurisdiction</h5> 
                
                <p>These Terms will be governed by and interpreted in accordance with the laws of the State of ae, and you submit to the non-exclusive jurisdiction of the state and federal courts located in ae for the resolution of any disputes.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
var count = 1;//PRODUCT TBL
    $('#addBtn').click(function(){
    count = count + 1;
    var country_html=$(document).find("#country_opt").html();
    $.ajax({
        url:'PMS/P_ADMIN/ajax_dept.php',
        method: 'POST',
        data: {},
        success:function(data){
            var html_code = "<tr id='row"+count+"' class='tr_row'>";
            html_code += "<td><select name='dept_id[]' id='cat_opt' class='form-control dept_opt_class'> <option value=''>Select Category</option> </select></td>";
            html_code += "<td><select name='prod_desc[]' id='p_desc"+count+"' class='form-control no-border p_desc selectpicker' data-live-search='true' type='text' data-container='body'></select><div class='form-row mt-2'> <div class='col-4'  class='float-right'> <label for=''  class='float-right mt-2'>If others, please specify:</label>  </div> <div class='col-8'> <input type='text' id='input' class='form-control othersInput'> </div> </div></td>";
            html_code += "<td><input name='prod_Brand[]' class='form-control no-border' type='text'></td>";
            html_code += "<td><select name='country[]' id='ctn_opt' class='form-control selectpicker' data-live-search='true'> <option value=''>Select Country</option> </select></td>";
            html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
            html_code += "</tr>";
            $('#productTbl').append(html_code);
            $(document).find('#row'+count+' #cat_opt').html(data).change();
            $(document).find('#row'+count+' #ctn_opt').html(country_html).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    });
});
$(document).ready(function(){
    var cnt = 1;//SERVICE TBL
    $('#adBtn').click(function(){
    $.ajax({
        url:'PMS/P_ADMIN/ajax_dept.php',
        method: 'POST',
        data: {},
        success:function(data){
            var html_code = "<tr id='row"+cnt+"'>";
            html_code += "<td><select name='sdept_id[]' id='dept_optt' class='form-control '> <option value=''>Select Department</option> </select></td>";
            html_code += "<td><input name='serv_desc[]' class='form-control no-border' type='text'></td>";
            html_code += "<td><select name='serv_unit[]'  class='form-control'> <option selected>Select Unit</option> <option value='price/Hour'>price/Hour</option> <option value='price/Hour'>price/Day</option> <option value='price/Sq. F'>price/Sq. F</option> </select></td>";
            html_code += "<td><input name='serv_rate[]' class='form-control no-border' type='number'></td>";
            html_code += "<td><button type='button' name='remove' data-row='row"+cnt+"' class='btn btn-danger btn-xs remove'>-</button></td>";
            html_code += "</tr>";
            $('#serviceTbl').append(html_code);
            $(document).find('#row'+cnt+' #dept_optt').html(data).change();
            $('.selectpicker').selectpicker('refresh');
            cnt = cnt + 1; 
                }
            });
    });
});
$(document).ready(function(){
    $(document).on('click', '.remove', function(){
        var delete_row = $(this).data("row");
        $('#' + delete_row).remove();
    });   
}); 
$(document).ready(function(){  
    $(document).on('change', '#dept_id', function(){
        var dept_id = $(this).val();
        $.ajax({
            url:'PURCHASE/PM/ajax_purchase.php',
            method: 'POST',
            data: {'dept_id':dept_id},
            success:function(data){
                $(document).find('#prod_desc').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });   
});   
$(document).ready(function(){  
    $(document).on('change', '.dept_opt_class', function(){
        var dept_id = $(this).val();
        var tr_id=$(this).closest('tr').attr('id');
        var count=tr_id.slice(3);
        $.ajax({
            url:'PURCHASE/PM/ajax_purchase.php',
            method: 'POST',
            data: {'dept_id':dept_id},
            success:function(data){
                $(document).find('#p_desc'+count).html(data);
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });   
}); 

$(document).on('input','.othersInput', function(){
    var customInput = $(this).val();
    // alert(customInput);
    var select_id= $(this).closest('td').find('.selectpicker').attr('id');
    $("#"+select_id).append('<option value="'+customInput+'">'+customInput+'</option>');
    $('.selectpicker').selectpicker('refresh');
    $("#"+select_id).val(customInput);
    $('.selectpicker').selectpicker('refresh');
});
$(document).ready(function () {
    var click=0; 
    $('#all').on('click', function() {   
        click++;
        if(click==2){
            $(document).find(".sba").prop("checked", false);
            $(document).find("#all").prop("checked", true);
            click=0;
        }
        else{
            $(document).find(".sba").prop("checked", false);
            $(document).find("#all").prop("checked", false);
        }       
    });
});
$(document).ready(function () {
    $.ajax({
    url:'PMS/P_ADMIN/ajax_dept.php',
    method: 'POST',
    data:{},
    success:function(data){
        $(document).find('.dept_opt').html(data).change();
        $('.selectpicker').selectpicker('refresh');
        }
    });
});
$(document).ready(function () {
    $(document).on("submit", "form", function(e){
        if($('#check').is(":checked")){
            //show loading screen
            $(".btn .fa-spinner").show();
            $("#submit").html("Submitting");
            if($(document).find('#reg_form').submit()){
                $('#submit').prop('disabled', true)
            }
            else{
                $('#submit').prop('disabled', false)
            }
            // $(document.find('form').attr('onsubmit','return true;'));
        }
        else{
            e.preventDefault();
            $("#agree").modal();
            // $('form').attr('onsubmit','return false;');
            // e.preventDefault();
        }
    });
});
$(document).ready(function () {
    $(document).on('click', '#yes', function(){
        // if($('input[name="submit"]:checked'==false))
        $(document).find("#check").prop("checked", true);
        if(("#check").prop("checked", false))
        {
            $(document).find("#check").prop("checked", true);
            $("#agree").modal('hide');
        }
    });
});
$(document).ready(function () {
    $(document).on('click', '.termsHTML', function(){
        $("#termsHTML").modal();
        $('.modal').css('overflow-y', 'auto');
    });
});
$(document).ready(function () {
    $(document).on('click', '.bTypeDiv', function(){
        var btype = $('input[name="bType"]:checked').val();
        if(btype=='subcon' || btype == 'laborSupply' || btype =='agency'){
            $('#serviceForm').removeClass("d-none");
            $('#productForm').addClass("d-none");
            $(document).find('#serviceForm .check-fields4').prop('required',false);
            $(document).find('#productForm .check-fields5').prop('required',true);
        }
        else {
            $('#productForm').removeClass("d-none");
            $('#serviceForm').addClass("d-none");
            $(document).find('#productForm .check-fields5').prop('required',true);
            $(document).find('#serviceForm .check-fields4').prop('required',false);
        }
    });
});
window.addEventListener('scroll', function() {
	var element = document.querySelector('#compChk');
	var position = element.getBoundingClientRect();

	// checking whether fully visible
	if(position.top >= 0 && position.bottom <= window.innerHeight) {
		// visible
        $('.companyPB').removeClass('text-secondary');
        $('.companyPB').addClass('text-primary');
	}
    else{
        $('.companyPB').addClass('text-secondary');
        $('.companyPB').removeClass('text-primary');
    }
});
window.addEventListener('scroll', function() {
	var element = document.querySelector('#contChk');
	var position = element.getBoundingClientRect();
	if(position.top >= 0 && position.bottom <= window.innerHeight) {
        $('.contactPB').removeClass('text-secondary');
        $('.contactPB').addClass('text-primary');
	}
    else{
        $('.contactPB').addClass('text-secondary');
        $('.contactPB').removeClass('text-primary');
    }   
});
window.addEventListener('scroll', function() {
	var element = document.querySelector('#ps_card');
	var position = element.getBoundingClientRect();
	if(position.top >= 0 && position.bottom <= window.innerHeight) {
		$('.psPB').removeClass('text-secondary');
        $('.psPB').addClass('text-primary');
	}
    else{
        $('.psPB').addClass('text-secondary');
        $('.psPB').removeClass('text-primary');
    }
});
window.addEventListener('scroll', function() {
	var element = document.querySelector('#attChk');
	var position = element.getBoundingClientRect();
	if(position.top >= 0 && position.bottom <= window.innerHeight) {
		$('.attachPB').removeClass('text-secondary');
        $('.attachPB').addClass('text-primary');
	}
    else{
        $('.attachPB').addClass('text-secondary');
        $('.attachPB').removeClass('text-primary');
    }
});
window.onscroll = function() {myFunction()};

function myFunction() {
  var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
  var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  var scrolled = (winScroll / height) * 100;
  document.getElementById("progressbar").style.width = scrolled + "%";
}
function fileValidation() {
    var fileInput = document.getElementById('formFile1');
    var filePath = fileInput.value;
    
    // Allowing file type
    var allowedExtensions =
        /(\.pdf)$/i;
        
    if (!allowedExtensions.exec(filePath)) {
        alert('Invalid file type');
        fileInput.value = '';
        return false;
    }
}
function fileValidation2() { // must be pdf
    var fileInput = document.getElementById('formFile2');
    var filePath = fileInput.value;
    
    // Allowing file type
    var allowedExtensions =
        /(\.pdf)$/i;
        
    if (!allowedExtensions.exec(filePath)) {
        alert('Invalid file type. Please choose .pdf file');
        fileInput.value = '';
        return false;
    }
}
$(document).ready(function () {
    $(document).on('change', '.imgFile', function(){
        var id = $(this).attr('id');
        var fileInput = document.getElementById(id);
        var filePath = fileInput.value;
        // Allowing file type
        var allowedExtensions =
            /(\.jpg|\.jpeg|\.png)$/i;
        if (!allowedExtensions.exec(filePath)) {
            alert('Invalid file type. Please choose .png or .jpeg file');
            fileInput.value = '';
            return false;
        }
    });
});
</script>
<?php
include('includes/scripts.php');
?>