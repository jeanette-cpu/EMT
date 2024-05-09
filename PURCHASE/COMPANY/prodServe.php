<?php
    include('../../security.php');
    include('../../includes/header.php'); 
    include('../../includes/company_navbar.php'); 
    $username=$_SESSION['USERNAME'];
    $query ="SELECT * FROM users WHERE USERNAME='$username'";
    $query_run = mysqli_query($connection, $query);
    $row=mysqli_fetch_assoc($query_run);
    $user_id=$row['USER_ID'];
    $q_company="SELECT * FROM company WHERE User_Id='$user_id' LIMIT 1";
    $q_company_run=mysqli_query($connection,$q_company);
    $row_c=mysqli_fetch_assoc($q_company_run);
    if($q_company_run){
        $comp_type=$row_c['Comp_Type'];
        $comp_id=$row_c['Comp_Id'];
        if($comp_type=='subcon' || $comp_type=='laborSupply'){
            $type="Services";$th1="Service Desc.";$th2="Unit";$th3="Rate";$th4="Department";
        }
        elseif($comp_type=='trading' || $comp_type=='oem' || $comp_type=='distributor'){
            $type="Products";$th1="Product Desc.";$th2="Brand";$th3="Country";$th4="Department";
        }
    }
?>
<style>
    div.dropdown-menu.open { width: 100%; } ul.dropdown-menu.inner>li>a { white-space: initial; }
</style>
<input type="hidden" id="comp_type" value="<?php echo $type?>">
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Company <?php echo $type;?>
            <!-- BUTTON -->
            <button type="button" class="btn btn-primary float-right addnew" data-toggle="modal" data-target="#add">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New
            </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive" >
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                            <th class="d-none"></th>
                            <th><?php echo $th1?></th>
                            <th><?php echo $th2?></th>
                            <th><?php echo $th3?></th>
                            <th>Department</th>
                            <th>Actions</th>
                            <th class="d-none"></th> <!--dept_id -->
                            <th class="d-none"></th> <!--mat_code -->                            
                    </thead>
                    <tbody>
            <?php
                if($comp_type=='subcon' || $comp_type=='laborSupply'){
                    $query_ps="SELECT * FROM service WHERE Comp_Id='$comp_id' AND Serve_Status=1";
                    $query_ps_run=mysqli_query($connection,$query_ps);
                    if(mysqli_num_rows($query_ps_run)>0)
                    {
                        while($row_s=mysqli_fetch_assoc($query_ps_run))
                        {
                        ?>
                            <tr>
                                <td class="d-none"><?php echo $row_s['Service_Id']?></td>
                                <td><?php echo $row_s['Serve_Desc'];?></td>
                                <td><?php echo $row_s['Serve_Unit']?></td>
                                <td><?php echo $row_s['Serve_Rate'] ?></td>
                                <td>
                                    <?php 
                                        $dept_id=$row_s['Dept_Id'];
                                        $q_dept="SELECT * FROM department WHERE Dept_Id='$dept_id'";
                                        $q_dept_run=mysqli_query($connection,$q_dept);
                                        $row_d=mysqli_fetch_assoc($q_dept_run);
                                        echo $row_d['Dept_Name'];
                                    ?>
                                </td>
                                <td class="btn-group">
                                    <button type="button" class="btn btn-success editServeBtn">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </button>
                                    <form action="code.php" method="post">
                                        <input type="hidden" name="serveId" value="<?php echo $row_s['Service_Id'];?>">
                                        <button type="submit" name="delServe" class="btn btn-danger delBtn">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="d-none"><?php echo $dept_id;?></td>
                                <td class="d-none"></td>
                            </tr>
                        <?php
                        }
                    }
                }
                elseif($comp_type=='trading' || $comp_type=='oem' || $comp_type=='distributor')
                {
                    $query_ps="SELECT * FROM product WHERE Comp_Id='$comp_id' AND Prod_Status=1";
                    $query_ps_run=mysqli_query($connection,$query_ps);
                    if(mysqli_num_rows($query_ps_run)>0)
                    {
                        while($row_p=mysqli_fetch_assoc($query_ps_run))
                        {
                            $prod_id=$row_p['Prod_Desc'];
                            $q_mat="SELECT * FROM material WHERE Mat_Id='$prod_id' LIMIT 1";
                            $q_mat_run=mysqli_query($connection,$q_mat);
                            if(mysqli_num_rows($q_mat_run)>0){
                                $row_m=mysqli_fetch_assoc($q_mat_run);
                                $desc=$row_m['Mat_Code'].' '.$row_m['Mat_Desc'];
                                $mat_code=$row_p['Prod_Desc'];
                            }
                            else{
                                $desc=$prod_id;
                                $mat_code='not';
                            }
                        ?>
                            <tr>
                                <td class="d-none"><?php echo $row_p['Product_Id']?></td>
                                <td><?php echo $desc;?></td>
                                <td><?php echo $row_p['Prod_Brand']?></td>
                                <td><?php echo $row_p['Prod_Country'] ?></td>
                                <?php 
                                        $dept_id=$row_p['Dept_Id'];
                                        $q_dept="SELECT * FROM department WHERE Dept_Id='$dept_id'";
                                        $q_dept_run=mysqli_query($connection,$q_dept);
                                        $row_d=mysqli_fetch_assoc($q_dept_run);
                                    ?>
                                <td><?php echo $row_d['Dept_Name']?></td>
                                <td class="btn-group">
                                    <button type="button" class="btn btn-success editProdBtn">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </button>
                                    <form action="code.php" method="post">
                                        <input type="hidden" name="prodId" value="<?php echo $row_p['Product_Id']?>">
                                        <button type="submit" name="delProd" class="btn btn-danger delBtn">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="d-none"><?php echo $dept_id=$row_p['Dept_Id']?></td>
                                <?php 
                                        if($mat_code=='not'){
                                            $mat_code=NULL;
                                        }
                                        else{
                                            $mat_code=$prod_id;
                                        }
                                        //matcode/desc
                                    ?>
                                <td class="d-none">
                                    <?php echo $mat_code?>
                                </td>
                            </tr>
                        <?php
                        }
                    }
                }
                else{
                    echo 'Error Loading Company Details';
                }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add Product-->
<div class="modal fade" id="addProd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <form action="code.php" method="post">
        <input type="hidden" name="comp_id" value="<?php echo $comp_id;?>">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Products</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="table table-responsive">
        <table class="table table-bordered" id="productTbl">
            <tr class="">
                <td class="col-2">
                    <div class="form-group">
                        <label for="">Product Category *</label><br>
                        <select name="dept_id[]" id="dept_id" class="form-control check-fields3 dept_opt" required>
                            <option value="">Select Category</option>
                        </select>
                    </div>
                </td>
                <td class="col-5">
                    <div class="form-group">
                        <label for="mat_op" >Description *</label><br>
                        <select id="prod_desc" name="prod_desc[]" class="form-control selectpicker " data-live-search="true" style="z-index:2" required>  </select>
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
                        <label for="">Brand </label><br>
                        <input name="prod_Brand[]" minlength="5" maxlength="25" type="text" class="form-control check-fields3">
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
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" name="addProd" class="btn btn-success">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- Modal Add Service-->
<div class="modal fade" id="addServe" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <form action="code.php" method="post">
    <input type="hidden" name="comp_id" value="<?php echo $comp_id;?>">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Services</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
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
                            </div>
                        </td>
                        <td class="col-3">
                            <div class="form-group">
                                <label for="">Unit</label><br>
                                <select name="serv_unit[]"  maxlength="45"  class="form-control check-fields4" required>
                                    <option value="price/Sq. F">price/Sq. F</option>
                                </select>
                            </div>
                        </td>
                        <td class="col-2">
                            <div class="form-group">
                                <label for="">Rate *</label><br>
                                <input name="serv_rate[]" type="number" maxlength="20" class="form-control check-fields4" required>
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
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" name="addServe" class="btn btn-success">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- Modal Edit Product-->
<div class="modal fade" id="editProd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form action="code.php" method="post">
        <input type="hidden" id="prod_id" name="prod_id">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Products</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <label for="">Department: *</label>
          <select name="dept" id="ddept_edit" class="form-control" required>
              <?php 
                $query= "SELECT * from department WHERE Dept_Status=1";
                $query_run = mysqli_query($connection, $query);
                $output='';
                $output .= '<option value="Select Department" disabled>Select Department</option>';
                if(mysqli_num_rows($query_run)>0)
                {
                    while($row = mysqli_fetch_assoc($query_run))
                    {
                        $output .= '<option value="'.$row['Dept_Id'].'">'.$row['Dept_Name'].'</option>';
                    }
                }
                echo $output;
              ?>
            </select>
          <label for="" class="mt-1">Product Description: *</label>
          <select id="prod_ddesc" name="prod_des" class="form-control selectpicker" data-live-search="true" style="z-index:2" required>  
                <?php 
                     $query_p= "SELECT * FROM material where Mat_Status=1";
                     $query_p_run = mysqli_query($connection, $query_p);
                      echo  '<option value="" disabled>Select Material</option>';
                     if(mysqli_num_rows($query_p_run)>0)
                     {
                         while($row1 = mysqli_fetch_assoc($query_p_run))
                         {
                             echo '<option value="'.$row1['Mat_Id'].'">'.$row1['Mat_Code'].' - '.$row1['Mat_Desc'].'</option>';
                         }
                     }
                ?>
          </select>
          <div class="form-row mt-2" >
            <div class="col-4"  class="float-left ">
                <label>If others, please specify:</label> 
            </div>
            <div class="col-8">
                <input type="text" id="input" class="form-control othersInput">
            </div>
        </div>
          <div class="form-row mt-1">
                <div class="col-6">
                    <label for="">Brand:</label>
                    <input id="brand" name="brand" type="text" class="form-control">  
                </div>
                <div class="col-6">
                    <label for="">Country:</label>
                    <!-- <input id="country" name="country" type="text" class="form-control">   -->
                    <select id="country" name="country" class="form-control selectpicker " data-live-search="true">
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
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" name="editProd" class="btn btn-success">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- Modal Edit Serve-->
<div class="modal fade" id="editServe" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form action="code.php" method="post">
        <input type="hidden" name="serve_id" id="serve_id">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Products</h5>
      </div>
      <div class="modal-body">
          <div class="form-row">
                <div class="col-6">
                    <label for="">Department</label>
                    <select name="dept" id="dept_idS" class="form-control" required>
                        <?php 
                        $query= "SELECT * from department WHERE Dept_Status=1";
                        $query_run = mysqli_query($connection, $query);
                        $output='';
                        $output .= '<option value="Select Department" disabled >Select Department</option>';
                        if(mysqli_num_rows($query_run)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_run))
                            {
                                $output .= '<option value="'.$row['Dept_Id'].'">'.$row['Dept_Name'].'</option>';
                            }
                        }
                        echo $output;
                        ?>
                    </select>
                </div>
                <div class="col-6">
                    <label for="">Service Description</label>
                    <input type="text" name="serv_desc" id="serve_desc" class="form-control" required>
                </div>
          </div>
          <div class="form-row mt-2">
            <div class="col-6">
                <label for="">Unit</label>
                <input type="text" name="unit" id="unit" class="form-control" required>
            </div>
            <div class="col-6">
                <label for="">Rate</label>
                <input type="text" name="rate" id="rate" class="form-control" required>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" name="editServe" class="btn btn-success">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
    $(document).on('click','.addnew', function(){
        var comp_type = $('#comp_type').val();
        if(comp_type=='Products'){
            $('#addProd').modal('show');
        }
        if(comp_type=='Services')
        {
            $('#addServe').modal('show');
        }
    });
});
var count = 1;//PRODUCT TBL
    $('#addBtn').click(function(){
    count = count + 1;
    var country_html=$(document).find("#country_opt").html();
    $.ajax({
        url:'../../PMS/P_ADMIN/ajax_dept.php',
        method: 'POST',
        data: {},
        success:function(data){
            var html_code = "<tr id='row"+count+"' class='tr_row '>";
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
$(document).ready(function(){
var cnt = 1;//SERVICE TBL
$('#adBtn').click(function(){
$.ajax({
    url:'../../PMS/P_ADMIN/ajax_dept.php',
    method: 'POST',
    data: {},
    success:function(data){
        var html_code = "<tr id='row"+cnt+"'>";
        html_code += "<td><select name='sdept_id[]' id='dept_optt' class='form-control '> <option value=''>Select Department</option> </select></td>";
        html_code += "<td><input name='serv_desc[]' class='form-control no-border' type='text'></td>";
        html_code += "<td><select name='serv_unit[]'  class='form-control'> <option value=''>price/Sq. F</option> </select></td>";
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
            url:'../PM/ajax_purchase.php',
            method: 'POST',
            data: {'dept_id':dept_id},
            success:function(data){
                $(document).find('#prod_desc').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
    $(document).on('change', '#ddept_edit', function(){
        var dept_id = $(this).val();
        $.ajax({
            url:'../PM/ajax_purchase.php',
            method: 'POST',
            data: {'dept_id':dept_id},
            success:function(data){
                $(document).find('#prod_ddesc').html(data).change();
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
            url:'../PM/ajax_purchase.php',
            method: 'POST',
            data: {'dept_id':dept_id},
            success:function(data){
                $(document).find('#p_desc'+count).html(data);
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });   
}); 
$(document).ready(function(){  
    $(document).on('click', '.editServeBtn', function(){
        $('#editServe').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
        console.log(data);
        $('#serve_id').val(data[0]);
        $('#dept_idS').val(data[6]);
        $('#serve_desc').val(data[1]);
        $('#unit').val(data[2]);
        $('#rate').val(data[3]);

    });  
    $(document).on('click', '.editProdBtn', function(){
        $('#editProd').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();

        $('#prod_id').val(data[0]);
        $('#brand').val(data[2]);
        $('#country').val(data[3]);
        $('#dept').val(data[4]);
        $('#ddept_edit').val(data[6]);
        var mat_id =data[7];
        mat_id =mat_id.trim();
        if(mat_id){
            // alert('not null');
            $('#prod_ddesc').val(mat_id);
            $('.selectpicker').selectpicker('refresh');
        }
        else{
            // alert('null');
            customInput=data[1];
            $("#prod_ddesc").append('<option value="'+customInput+'">'+customInput+'</option>');
            $('.selectpicker').selectpicker('refresh');
            $('#prod_ddesc').val(customInput);
            $('.selectpicker').selectpicker('refresh');
        }
    });   
});
$(document).ready(function(){  
    $(document).on('input', '#input', function(){
        var customInput = $(this).val();
        $("#prod_ddesc").append('<option value="'+customInput+'">'+customInput+'</option>');
        $('.selectpicker').selectpicker('refresh');
        $('#prod_ddesc').val(customInput);
        $('.selectpicker').selectpicker('refresh');
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
    $.ajax({
    url:'../../PMS/P_ADMIN/ajax_dept.php',
    method: 'POST',
    data:{},
    success:function(data){
        $(document).find('.dept_opt').html(data).change();
        $('.selectpicker').selectpicker('refresh');
        }
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>