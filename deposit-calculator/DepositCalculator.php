<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
	<title>Alina Kurliantseva | Deposit Calculator</title>
        <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="PHP, CSS, Adobe Photoshop">
	<meta name="keywords" content="PHP, CSS, Adobe Photoshop">
        <link rel="stylesheet" href="MainStyle.css"/>
    </head>
    
    <body>
        <?php
            extract($_POST);
            if (isset($btnSubmit))
            {   
                $validation = TRUE;
                $errorPrincipalAmount = ValidatePrincipalAmount($PrincipalAmount);
                $errorInterestRate = ValidateInterestRate($InterestRate);
                $errorName = ValidateName($Name);
                $errorPostalCode = ValidatePostalCode($PostalCode);
                $errorPhoneNumber = ValidatePhoneNumber($PhoneNumber);
                $errorEmailAddress = ValidateEmailAddress($EmailAddress);
                $errorContactMethod = ValidateContactMethod($PreferredContactMethod);
                $errorContactTime = ValidateContactTime($PreferredContactMethod, $ContactTime);
                if((strlen($errorPrincipalAmount) != 0) || (strlen($errorInterestRate) != 0) || (strlen($errorName) != 0) ||
                   (strlen($errorPostalCode) != 0) || (strlen($errorPhoneNumber) != 0) || (strlen($errorEmailAddress) != 0) ||
                   (strlen($errorContactTime) != 0) || (strlen($errorContactMethod) != 0))
                {
                    $validation = FALSE;
                }  
            }
            if (isset($btnClear))
            {
                $PrincipalAmount = "";
                $InterestRate = "";
                $Name = "";
                $PostalCode = "";
                $PhoneNumber = "";
                $EmailAddress = "";
                $YearsToDeposit = "";
                $PreferredContactMethod = "";
                unset($ContactTime);                    
            }
        ?>
        <?php 
            if ($validation)
            {
echo <<<HEADER
        <br /><br />
        <h3>Thank you $Name for using our deposit calculation tool.</h3>
        <p>An email about the details of our GIC has been sent to $EmailAddress.</p>      
HEADER;
                print "<i>The following is the result of the calculation:</i><br /><br />";
                print "<table>";
                print "<tr>";
                print "<td>Year</td>";
                print "<td>Principal at Year Start</td>";
                print "<td>Interest for the Year</td>";
                print "</tr>";
                $i = 1;
                do {
                    print "<tr>";
                    print "<td>$i</td>";
                    $PrincipalAmount +=  $InterestForTheYear;
                    printf("<td>%1\$.2f</td>", $PrincipalAmount);
                    $InterestForTheYear = ($PrincipalAmount*$InterestRate)/100;
                    printf("<td>%1\$.2f</td>", $InterestForTheYear);
                    print "</tr>";
                    $i++;
                } while ($i <= $YearsToDeposit);
                print "</table>";
            }
            else {
        ?>
        <h2>Deposit Calculator</h2>
        <div class="profileForm">
            <form method="post" action="<?php echo $_SERVER['PHP_SEFL']; ?>">
                <p class="text-center">Principal Amount:
                    <input type="text" name="PrincipalAmount" value="<?php echo $PrincipalAmount; ?>" />
                    <span class="error"><?php echo $errorPrincipalAmount; ?></span>
                </p>
                <p class="text-center">Interest Rate (%):
                    <input type="text" name="InterestRate" value="<?php echo $InterestRate; ?>" />
                    <span class="error"><?php echo $errorInterestRate; ?></span>
                </p>
                <p class="text-center">Years to Deposit:</p>
                <select name="YearsToDeposit">
                    <?php 
                        for($i = 1; $i <= 20; $i++)
                        {
                            print $YearsToDeposit == $i ? "<option value='$i' selected>$i</option>" : "<option value='$i'>$i</option>";
                        }
                    ?>
                </select>
                <p class="text-center">Name:
                    <input type="text" name="Name" value="<?php echo $Name; ?>" />
                    <span class="error"><?php echo $errorName; ?></span>
                </p>
                <p class="text-center">Postal Code:
                    <input type="text" name="PostalCode" value="<?php echo $PostalCode; ?>" />
                    <span class="error"><?php echo $errorPostalCode; ?></span>
                </p>
                <p class="text-center">Phone Number (nnn-nnn-nnnn):
                    <input type="text" name="PhoneNumber" value="<?php echo $PhoneNumber; ?>" />
                    <span class="error"><?php echo $errorPhoneNumber; ?></span>
                </p>
                <p class="text-center">Email Address:
                    <input type="email" name="EmailAddress" value="<?php echo $EmailAddress; ?>" />
                    <span class="error"><?php echo $errorEmailAddress; ?></span>
                </p><br />
                <p class="text-center">Preferred Contact Method:</p>
                <input type="radio" name="PreferredContactMethod" value="Phone" <?php print($PreferredContactMethod=='Phone' ? 'checked' : '') ?> />Phone<br />
                <input type="radio" name="PreferredContactMethod" value="Email" <?php print($PreferredContactMethod=='Email' ? 'checked' : '') ?> />Email
                <div class="error"><?php echo $errorContactMethod; ?></div><br />
                <p class="text-center">If phone is selected, when can we contact you?</p>
                <p class="text-center"><i>(check all applicable)</i></p>
                <input type="checkbox" name="ContactTime[ ]" value="Morning" <?php print (isset($ContactTime) && in_array('Morning', $ContactTime) ? 'checked' : ''); ?> >Morning<br />
                <input type="checkbox" name="ContactTime[ ]" value="Afternoon" <?php print (isset($ContactTime) && in_array('Afternoon', $ContactTime) ? 'checked' : ''); ?> >Afternoon<br />
                <input type="checkbox" name="ContactTime[ ]" value="Eening" <?php print (isset($ContactTime) && in_array('Evening', $ContactTime) ? 'checked' : ''); ?> >Evening
                <div class="error"><?php echo $errorContactTime; ?></div>
                <div class="margin-auto">
                    <input type="submit" name="btnSubmit" value="Calculate" />
                    <input type="submit" name="btnClear" value="Clear" />
                </div>
            </form>
        </div>        
    </body>
</html>
        <?php
            }
        ?>
<?php 
    function ValidatePrincipalAmount($FieldName)
        {
            $Name = trim($FieldName);
            if (strlen($Name) == 0)
            {
                $errorPrincipalAmount = "Pricincipal Amount field can not be blank.";
            }
            elseif (!is_numeric($Name) || ($Name <= 0))
            {
                $errorPrincipalAmount = "Principal Amount must be numeric and greater than zero.";
            }
            else
            {
                $errorPrincipalAmount = "";
            }
            return $errorPrincipalAmount;
        }
    function ValidateInterestRate($FieldName)
        {
            $Name = trim($FieldName);
            if (strlen($Name) == 0)
            {
                $errorInterestRate = "Interest Rate field can not be blank.";
            }
            elseif (!is_numeric($Name) || ($Name < 0))
            {
                $errorInterestRate = "Interest Rate must be numeric and non-negative.";
            }            
            else
            {
                $errorInterestRate = "";
            }
            return $errorInterestRate;
        }
    function ValidateName($FieldName)
        {
            $Name = trim($FieldName);
            if (strlen($Name) == 0)
            {
                $errorName = "Name field can not be blank.";
            }
            else
            {
                $errorName = "";
            }
            return $errorName;
        }
    function ValidatePostalCode($FieldName)
        {
            $Name = trim($FieldName);
            $Name = str_replace(' ', '', $Name);
            if (strlen($Name) == 0)
            {
                $errorPostalCode = "Postal Code field can not be blank.";
            }
            elseif (!preg_match("#[a-zA-Z][0-9][a-zA-Z][0-9][a-zA-Z][0-9]#", $Name))
            {
                $errorPostalCode = "Postal Code is not valid.";
            }            
            else
            {
                $errorPostalCode = "";
            }
            return $errorPostalCode;
        }
    function ValidatePhoneNumber($FieldName)
        {
            $Name = trim($FieldName);
            if (strlen($Name) == 0)
            {
                $errorPhoneNumber = "Phone Number field can not be blank.";
            }
            elseif (!preg_match("#[2-9][0-9][0-9]-[2-9][0-9][0-9]-[0-9][0-9][0-9][0-9]$#", $Name))
            {
                $errorPhoneNumber = "Phone Number is not valid.";
            }             
            else
            {
                $errorPhoneNumber = "";
            }
            return $errorPhoneNumber;
        }        
    function ValidateEmailAddress($FieldName)
        {
            $Name = trim($FieldName);
            if (strlen($Name) == 0)
            {
                $errorEmailAddress = "Email Address field can not be blank.";
            }
            elseif (!preg_match("#[a-zA-Z0-9\.]*?@[a-zA-Z0-9\.]*?\.[a-zA-Z0-9]{2,4}$#", $Name))
            {
                $errorEmailAddress = "Email Address is not valid.";
            }            
            else
            {
                $errorEmailAddress = "";
            }
            return $errorEmailAddress;
        }
    function ValidateContactMethod($FieldName)
        {
            if (!isset($FieldName))
            {
                $errorContactMethod = "Contact Method must be selected.";
            }
            else
            {
                $errorContactMethod = "";
            }
            return $errorContactMethod;
        }                
    function ValidateContactTime($FieldName1, $FieldName2)
        {
            if ($FieldName1 == 'Phone' && !isset($FieldName2))
            {
                $errorContactTime = "When preferred contact method is phone, you have to select one or more contact times.";
            }
            else
            {
                $errorContactTime = "";
            }
            return $errorContactTime;
        }
