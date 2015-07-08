<?php
include '../../../../crm/access.php';

/*========*/
$server = 'localhost';
$username = 'root';
$password = 'password';
$database = 'floshowers';

$conn = mysql_connect($server, $username, $password);
if (!$conn) {
    die('SQL connection error...');
}
mysql_select_db($database) or die('SQL Error: database not selected properly...!');
/*========*/




$orderId = base64_decode($_GET['orderId']);

require('../../../app/Mage.php'); //include mage.php
Mage::app();
$currentDate = date("M d, Y H:i:s", Mage::getModel('core/date')->timestamp(time()));
////////To fetch the details of the ORDER by using the ORDER Increment ID////////
$theOrder = Mage::getModel('sales/order')->load($orderId, 'increment_id');

$statusHistoryCollection = $theOrder->getStatusHistoryCollection();
foreach ($statusHistoryCollection as $statusHistory) {
    $statusHistoryData = $statusHistory->getData();
    //var_dump($x);
    if ($statusHistoryData['status'] == "processing") {
        $stepIntoProcessingTimeStamp = date("M d, Y H:i:s", Mage::getModel('core/date')->timestamp($statusHistoryData['created_at']));
    }
}
$timeStamp = $theOrder->getCreatedAtDate();
$ts1 = strtotime($currentDate);
$ts2 = strtotime($stepIntoProcessingTimeStamp);
$seconds_diff = $ts1 - $ts2;
$timeSinceOrder = "<font color='red'>" . intval($seconds_diff / 24 / 3600) . " Days</font>, " . intval(intval($seconds_diff / 3600) % 24) . " Hours And " . intval(intval($seconds_diff % 3600) / 60) . " Minutes";
$billingAddressObject = $theOrder->getBillingAddress();
$billingId = $billingAddressObject->getId();
$billingAddress = Mage::getModel('sales/order_address')->load($billingId);
$billingTelephone = $billingAddress->getTelephone();
$billingEmail = $billingAddress->getEmail();
$billingPin = $billingAddress['postcode'];
$billingName = $billingAddress['firstname'] . " " . $billingAddress['lastname'];
$billingCity = $billingAddress['city'];
$billingStreet = $billingAddress['street'];
$billingState = $billingAddress['region'];
$productImg = "<tr>";


$stypeFromOrder = $theOrder->getShippingMethod(); //////Fetch Order's Shipping Type////////
///////SHIPPING METHOD RENAME///////
if ($stypeFromOrder == "freeshipping_freeshipping") {
    $stypeFromOrder = "Daytime";
} elseif ($stypeFromOrder == "flatrate_flatrate") {
    $stypeFromOrder = "Midnight";
}
///////End To SHIPPING METHOD RENAME///////

$giftMessageId = $theOrder->getGiftMessageId(); //////To fetch Gift Message ID//////
$giftMessage = Mage::getModel('giftmessage/message'); ////////To fetch Object Of All Gift Messages///////
if (!is_null($giftMessageId)) {
    $giftMessage->load($giftMessageId);
    $senderName = $giftMessage->getData('sender');
    $recipientName = $giftMessage->getData('recipient');
    $cardMessage = $giftMessage->getData('message');
}

$shippingAddress = $theOrder->getShippingAddress(); //// To Fetch the Address object for an ORDER////
$shippingId = $shippingAddress->getId(); ////To fetch the Shipping ID////
$address = Mage::getModel('sales/order_address')->load($shippingId); ////To fetch the SHIPPING ADDRESS using the SHIPPING ID////
$name = $address['firstname'] . " " . $address['lastname'];
$pin = $address['postcode'];
$city = $address['city'];
$recipientStreet = $address['street'];
$recipientTelephone = $address['telephone'];
$state = $address['region'];
$items = $theOrder->getAllItems();  ////To get all Ordered Items////
//$itemcount=count($items);
//$name=array();
//$unitPrice=array();
//$sku=array();
//$qty=array();
$countProducts = 1;
$count = 1;
foreach ($items as $itemId => $item) {
    if (intval($count % 5) == 0) {
        $productImg.="</tr><tr>";
    }
    $count++;
    //$itemName = $item->getName();
    //echo $name[$itemId]." ";
    //$unitPrice[]=$item->getPrice();
    //echo $unitPrice[$itemId]." ";
    //$entityId=$item->getId();
    //print_r($item);
    //exit;
    //echo $sku[$itemId]." ";
    $prodId = $item->getProductId();
    $product = Mage::getModel('catalog/product')->load($prodId);
    if (intval($item->getQtyOrdered()) > 1)
        $productImg.="<td>Entity ID: " . $prodId . " | <font color='red'>Quantity = " . intval($item->getQtyOrdered()) . "<br/></font> <img src='" . Mage::helper('catalog/image')->init($product, 'image') . "' width='200' height='200'></td>";
    else
        $productImg.="<td>Entity ID: " . $prodId . "<br/><img src='" . Mage::helper('catalog/image')->init($product, 'image') . "' width='200' height='200'></td>";
    //echo $product->getImage();
    //echo $ids[$itemId]." ";
    $prodOps = $item->getProductOptions(); ////To Fetch Ordered Products' Options' Object////
    $countProducts++;
    //$qty[]=$item->getQtyToInvoice();
    //echo $qty[$itemId]." "."<br>";		
    //var_dump($prodOps);
    foreach ($prodOps['options'] as $option) {  ////To fetch options for each product one by one////
        //var_dump( $option);
        $optionTitle = $option['label'];
        //var_dump($optionId);
        if (stristr($optionTitle, "Date of Delivery(MM/DD/YYYY)") == TRUE || stristr($optionTitle, "Date of Delivery(DD/MM/YYYY)") == TRUE) {
            /////////////////////////////To Fetch just the date in DD-MM-YYYY from YYYY-MM-DD HH:MM:SS///////////////////////////
            $optionRevValue = $option['option_value'];
            $optionValueDateTimeArr = explode(" ", $optionRevValue); ////to break string into Array////
            $optionValueDateNoTime = array_shift($optionValueDateTimeArr); ////to fetch First Element from array////
            $optionValueDateArr = explode("-", $optionValueDateNoTime); ////break string into array////
            $revArr = array_reverse($optionValueDateArr); //// to reverse the array ////
            ////////////////////////End To Fetch just the date in DD-MM-YYYY from YYYY-MM-DD HH:MM:SS/////////////////////
        }
        if (stristr($optionTitle, "Shipping Type") == TRUE) {
            $customShippingMethod = $option['value'];
        }
    }
    $modifiedDOD = dateIfModified($orderId);
    //////To check If date is Modified and SET it to Modified Date If True//////
    if ($modifiedDOD)
        $revArr = explode("-", $modifiedDOD);
    switch ($revArr[1]) { /////To Display Month's Name Instead Of Number//////
        case 1:
            $revArr[1] = "Jan";
            break;
        case 2:
            $revArr[1] = "Feb";
            break;
        case 3:
            $revArr[1] = "March";
            break;
        case 4:
            $revArr[1] = "April";
            break;
        case 5:
            $revArr[1] = "May";
            break;
        case 6:
            $revArr[1] = "June";
            break;
        case 7:
            $revArr[1] = "July";
            break;
        case 8:
            $revArr[1] = "August";
            break;
        case 9:
            $revArr[1] = "Sep";
            break;
        case 10:
            $revArr[1] = "Oct";
            break;
        case 11:
            $revArr[1] = "Nov";
            break;
        case 12:
            $revArr[1] = "Dec";
            break;
    }
    $optionValue = implode("-", $revArr); //// to make a string from array////
    $date = $optionValue;
}


if (is_null($recipientName))
    $recipientName = 'NULL';
if (is_null($cardMessage))
    $cardMessage = 'NULL';
if (is_null($senderName))
    $senderName = 'NULL';
if ($date == "")
    $date = "No Date";
?>
<html><head>
        <style>
            #innertable
            {
                position:relative;
                top:0%;
                bottom:0%;
                right:0%;
                left:0%;
                width:100%;
                height:100%;
            }
            #giftCardMessage
            {
                background: #F5D0A9;
                right: 50%;
            }
            .denied
            {
                position:absolute;
                text-align:center;
                top:33%;
                left:33%;
                background-color: #F3E2A9;
                border: 40px solid #F3E2A9;
            }

            .btn-print{
                float: right;
                margin:  4px 5px 0 5px;
                padding: 0 auto;
            }

        </style>
    </head>
<?php



function checkforVoiceMessage($theOrder) {
    $id = $theOrder->getId();
    $query = "SELECT count(floshowers.sales_order_custom.id) as num_count  from floshowers.sales_order_custom WHERE floshowers.sales_order_custom.order_id = '" . $id . "' LIMIT 1"; 
    $result = mysql_query($query); 
    $row = mysql_fetch_array($result);
    if($row['num_count']){
        return TRUE;
    }
     return FALSE;
}

$isVoiceMsg =  checkforVoiceMessage($theOrder);

$message_array = array(
    'order_id' => $orderId,
    'recipientName' => str_replace("&#8203;", "", $recipientName),
    'sendorName' => str_replace("&#8203;", "", $senderName),
    'cardMessage' => str_replace("&#8203;", "", $cardMessage),
    'voiceMsg' =>  $isVoiceMsg === TRUE ? "Please call at +91-8010-386-062 from {$recipientTelephone} to listen voice message." : "",
);



echo "<body>
<table border='0' width='100%'>
	<tr>
	<td colspan='2' bgcolor='#D8D8D8'><font size='4'>
	<div id='giftCardMessage'>
            <font size='5'> Gift Card Message </font> 
            <input class='btn-print' type='button' name='btn-vendor-challan' value='Print Challan' onclick='printVendorChallanOnPDF(" . $orderId . ")'>
            <input class='btn-print' type='button' name='btn-gift-message' value='Print Message' onclick='printGiftMessageOnPDF(" . $orderId . ")'>
                <input class='btn-print' type='button' name='btn-payment-challan' value='Print Payment Challan' onclick='printPaymentChallanOnPDF(" . $orderId . ")'>
        </div><br/><br/>
	Recipient's Name: " . $recipientName . "<br/><br/>
	Message On Card: " . $cardMessage . "<br/><br/>
	Sender's Name: " . $senderName . "</font>
	</td>
	</tr>
	<tr bgcolor='#F5D0A9'>
		<td style='height:80px; width:50%;'><font color='#424242' size='6'>Order No.: " . $orderId . "</font>";
if (fetchVoiceMessageFlag($orderId))
    echo "<img src='voicemsg.gif' width='50' height='50'>";
echo "</td>";
echo "<td style='height:80px; width:50%;'><font color='#424242' size='6'>In Processing From: " . $stepIntoProcessingTimeStamp . "</font><br/><font size='4'>" . $timeSinceOrder . " Since Stepped In Processing</font><br/>Created At Time Stamp: " . $timeStamp . "</font></td>
	</tr>
	<tr>
		<td bgcolor='#D8D8D8'><table>" . $productImg . "</tr></table></td>
		<td bgcolor='#D8D8D8'>
			<div id='innertable'>
				<table border='0' width='100%'>
					<tr>
						<th style='height:150px' bgcolor='#81F781'><font size='4'>Shipping Information</font></th>
						<th style='height:150px' bgcolor='#A9F5F2'><font size='4'>Billing Information</font></th>
					</tr>
					<tr>
						<td bgcolor='#CEF6CE'><font size='4'>
							<b>Recipient's Name: </b>" . $name . "<br/><br/>
							<b>Recipient's Address: </b>" . $recipientStreet . "<br/><br/>" .
 $city . "<br/><br/>" .
 $state . "-" . $pin . "<br/><br/>
							<b>Recipient's Contact No.</b>: " . $recipientTelephone . "</font>
                                                            
                                                       <br/># <input type='button' name='custom_callrecipient' value='Customer Call' onclick='myOptCallConnect(" . $recipientTelephone . ")'>
                                           
							<br/># <input type='button' name='callrecipient' value='Call' onclick='callConnect(" . $recipientTelephone . ")'>
						</td>
						<td bgcolor='#E0F8F7'><font size='4'>
							<b>Sender's Name: </b>" . $billingName . "<br/><br/>
							<b>Sender's Address: </b>" . $billingStreet . "<br/><br/>" .
 $billingCity . "<br/><br/>" .
 $billingState . "-" . $billingPin . "<br/><br/>
							<b>Sender's Contact No.</b>: " . $billingTelephone . "
                                                        
                                                                
                                            <br/># <input type='button' name='custom_callrecipient' value='Customer Call' onclick='myOptCallConnect(" . $billingTelephone . ")'>
                                                                
							<br/># <input type='button' name='callrecipient' value='Call' onclick='callConnect(" . $billingTelephone . ")'>
							<br/><br/>Send Email To <a href='mailto:" . $billingEmail . "' data-rel='external'>" . $billingName . "</a><br/>(" . $billingEmail . ")</font>
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<tr bgcolor='#F5D0A9'>
		<td>
			<font size='6'><b>Date Of Delivery : </b><font color='red'><b>" . $date . "</b></font></br></font>
		</td>
		<td>
			<font size='4'><b>Order Shipping Method : </b><font color='red'>" . $stypeFromOrder . "</font></br>
			<b>Custom Shipping Method : </b><font color='red'>" . $customShippingMethod . "</font></font>
		</td>
	</tr>
</table>";
echo "</body></html>";

///////////////End To HTML///////////////		

function dateIfModified($orderId) {
    $q = "SELECT comments_orders_modified_date FROM comments_orders WHERE comments_orders_orderid = '$orderId' LIMIT 1";
    $result_comments_orders = mysql_query($q);
    $value = mysql_fetch_array($result_comments_orders);
    return $value['comments_orders_modified_date'];
}

function fetchVoiceMessageFlag($orderId) {
    $theOrder = Mage::getModel('sales/order')->load($orderId, 'increment_id');
    $actualOrderId = $theOrder->getId();
    $q = "SELECT floshowers.sales_order_custom.id from floshowers.sales_order_custom WHERE floshowers.sales_order_custom.order_id = '" . $actualOrderId . "' LIMIT 1"; ////SQL Query////
    $result_voice_msg = mysql_query($q); ////Result Set for the SQL QUERRY's Result////
    $num_rows = mysql_num_rows($result_voice_msg);
    if ($num_rows > 0) {
        return TRUE;
    } elseif ($num_rows == 0) {
        return FALSE;
    }
}
?>
    <!--
    <script type="text/javascript" src="jquery-ui-1.10.3.custom/js/jquery-1.9.1.js"></script>
    -->
    <script type="text/javascript" src="challan_giftmsg_resource/js/jquery-1.10.2.js"></script>

    <script type="text/javascript" src="challan_giftmsg_resource/js/jspdf/jspdf.js"></script>
    <script type="text/javascript" src="challan_giftmsg_resource/js/jspdf/basic.js"></script>
    <script type="text/javascript" src="challan_giftmsg_resource/js/jspdf/jspdf.debug.js"></script>
    <script type="text/javascript" src="challan_giftmsg_resource/js/jspdf/libs/base64.js"></script>
    <script type="text/javascript" src="challan_giftmsg_resource/js/jspdf/libs/sprintf.js"></script>

    <script type="text/javascript" src="challan_giftmsg_resource/js/jspdf/libs/png_support/png.js"></script>
    <script type="text/javascript" src="challan_giftmsg_resource/js/jspdf/libs/png_support/zlib.js"></script>
    <script type="text/javascript" src="challan_giftmsg_resource/js/jspdf/libs/Deflate/deflate.js"></script>
    <script type="text/javascript" src="challan_giftmsg_resource/js/jspdf/libs/Deflate/adler32cs.js"></script>

    <script type="text/javascript" src="challan_giftmsg_resource/js/jspdf/jspdf.plugin.autoprint.js"></script>
    <script type="text/javascript" src="challan_giftmsg_resource/js/jspdf/jspdf.plugin.png_support.js"></script>
    <script type="text/javascript" src="challan_giftmsg_resource/js/jspdf/jspdf.plugin.addimage.js"></script>
 
    <script type="text/javascript" src="challan_giftmsg_resource/js/jspdf/jspdf.plugin.split_text_to_size.js"></script> 

    <script type="text/javascript" src="vendor.payment.challan.js"></script> 

    <script>

        (function(API) {
            API.myText = function(txt, options, x, y) {
                options = options || {};
                /* Use the options align property to specify desired text alignment
                 * Param x will be ignored if desired text alignment is 'center'.
                 * Usage of options can easily extend the function to apply different text 
                 * styles and sizes 
                 */
                if (options.align === "center") {
                    // Get current font size
                    var fontSize = this.internal.getFontSize();

                    // Get page width
                    var pageWidth = this.internal.pageSize.width;

                    // Get the actual text's width
                    /* You multiply the unit width of your string by your font size and divide
                     * by the internal scale factor. The division is necessary
                     * for the case where you use units other than 'pt' in the constructor
                     * of jsPDF.
                     */
                    txtWidth = this.getStringUnitWidth(txt) * fontSize / this.internal.scaleFactor;

                    // Calculate text's x coordinate
                    x = (pageWidth - txtWidth) / 2;
                }

                // Draw text at x,y
                this.text(txt, x, y);
            };
        })(jsPDF.API);



/////////////////------PART OF VENDOR TABLE  /////////////////////


(function(API) {

    var rObj = {}
    , hObj = {}
    , data = []
            , dim = []
            , columnCount
            , rowCount
            , width
            , heigth
            , fdata = []
            , sdata = []
            , SplitIndex = []
            , cSplitIndex = []
            , indexHelper = 0
            , heights = []
            , fontSize
            , jg
            , i
            , tabledata = []
            , x
            , y
            , xOffset
            , yOffset
            , iTexts
            , start
            , end
            , ih
            , length
            , lengths
            , row
            , obj
            , value
            , nlines
            , nextStart
            , propObj = {}
    , pageStart = 0;

// Inserts Table Head row

    API.insertHeader = function(data) {
        rObj = {}, hObj = {};
        rObj = data[0];
        for (var key in rObj) {
            hObj[key] = key;
        }
        data.unshift(hObj);
    };

// intialize the dimension array, column count and row count

    API.initilizePDF = function(data, marginConfig, firstpage) {
        if (firstpage) {
            dim = [marginConfig.xstart, marginConfig.tablestart, this.internal.pageSize.width - marginConfig.xstart - 20 - marginConfig.marginright, 250, marginConfig.ystart, marginConfig.marginright];
        } else {
            dim = [marginConfig.xstart, marginConfig.ystart, this.internal.pageSize.width - marginConfig.xstart - 20 - marginConfig.marginright, 250, marginConfig.ystart, marginConfig.marginright];
        }
        columnCount = this.vendorCalColumnCount(data);
        rowCount = data.length;

        width = (dim[2] / columnCount);//+175;  // added 175 manually to fix col height
        height = dim[2] / rowCount;
        dim[3] = this.vendorColumnRowDimension(data, dim);
    };

//draws table on the document 

    API.drawVendorChallanTable = function(table_DATA, marginConfig) {

        fdata = [], sdata = [];
        SplitIndex = [], cSplitIndex = [], indexHelper = 0;
        heights = [];
        //this.setFont("times", "normal");
        fontSize = this.internal.getFontSize();
        if (!marginConfig) {
            maringConfig = {
                xstart: 20,
                ystart: 20,
                tablestart: 20,
                marginright: 20,
                xOffset: 10,
                yOffset: 10
            };
        } else {
            propObj = {
                xstart: 20,
                ystart: 20,
                tablestart: 20,
                marginright: 20,
                xOffset: 10,
                yOffset: 10
            };
            for (var key in propObj) {
                if (!marginConfig[key])
                {
                    marginConfig[key] = propObj[key];
                }
            }
        }

        pageStart = marginConfig.tablestart;
        xOffset = marginConfig.xOffset;
        yOffset = marginConfig.yOffset;
        
        this.initilizePDF(table_DATA, marginConfig, true);
        
        if ((dim[3] + marginConfig.tablestart) > (this.internal.pageSize.height)) {
            jg = 0;
            cSplitIndex = SplitIndex;
            cSplitIndex.push(table_DATA.length);
            for (var ig = 0; ig < cSplitIndex.length; ig++) {
                tabledata = [];
                tabledata = table_DATA.slice(jg, cSplitIndex[ig]);


                this.insertHeader(tabledata);
                this.pdf(tabledata, dim, true, false);
                pageStart = marginConfig.ystart;
                this.initilizePDF(tabledata, marginConfig, false);
                jg = cSplitIndex[ig];
                if ((ig + 1) != cSplitIndex.length) {
                    this.addPage();
                }
            }
        } else {
            this.insertHeader(table_DATA);

//                console.log("Table  Data: ");
//                console.log(table_DATA);

            this.pdf(table_DATA, dim, true, false);
        }
        return nextStart;
    };

//calls methods in a sequence manner required to draw table

    API.pdf = function(table, rdim, hControl, bControl) {
        columnCount = this.vendorCalColumnCount(table);
//
//        console.log("col count");
//        console.log(columnCount);

        rowCount = table.length;
//        console.log("row count");
//        console.log(rowCount);
//        // col row dimension

        rdim[3] = this.vendorColumnRowDimension(table, rdim);

        width = rdim[2] / columnCount;
        height = rdim[2] / rowCount;
        /**
         * to draw row for vendor table.
         */
        this.drawVendorRows(rowCount, rdim, hControl);
        /**
         * to draw column for vendor table.
         */
        this.drawVendorColumns(columnCount, rdim);
        /**
         * to insert data not image for vendor table.
         */

        nextStart = this.insertData(rowCount, columnCount, rdim, table, bControl);
        return nextStart;
    };

//inserts text into the table 

    API.insertData = function(iR, jC, rdim, data, brControl) {
        // xOffset = 10;
        // yOffset = 10;
        
        y = rdim[1] + yOffset;
        for (i = 0; i < iR; i++) {
            obj = data[i];
            // all  items coming here
            //  console.log(obj);
            x = rdim[0] + xOffset;


            var item_count = 0;// custom  variable by amarkant 

            for (var key in obj) {

                if (key.charAt(0) !== '$') {
                    if (obj[key] !== null) {
                        cell = obj[key].toString();
                    } else {
                        cell = '-';
                    }
                    cell = cell + '';

                    // alert(cell);
                    if (((cell.length * fontSize) + xOffset) > (width)) {
                        iTexts = cell.length * fontSize;
                        start = 0;
                        end = 0;
                        ih = 0;
                        if ((brControl) && (i === 0)) {
                            this.setFont(this.getFont().fontName, "bold");
                        }


                        if (item_count === 0) {
                            
                            // for serial number 
                            
                            // x = position to X co-ordinate 
                            // y = position to Y co-ordinate 
                            this.text(x, y + ih, cell);
                            ih += fontSize - 5;
                        }

                        else if (item_count === 1) {
                            // for Item 
                            if(cell === "Item"){
                                 this.setFontSize(10);
                                 this.setFont("helvetica", "bold");
                                 this.text(x - 18, y + ih, cell);
                            }else{
                            
                                var line = this.setFont("times", "normal").setFontSize(9.5).splitTextToSize(cell.trim(), 25);  
                                this.text(x - 18, y + ih, line); 
                            }
                           // this.text(x - 18, y + ih, cell);
                            ih += fontSize - 5;
                        }
                        else if (item_count === 2) {
                            // for vendor description 
                           if(cell === "no vendor description found"){
                                ih += fontSize - 5;
                           }else{
                               
                               if(cell === "Description"){
                                   this.setFontSize(10);
                                   this.setFont("helvetica", "bold");
                                   this.text(x - 30, y + ih, cell);
                               }else{
                                var line = this.setFont("times", "normal").setFontSize(9.5).splitTextToSize(cell, 80);  
                                this.text(x - 30, y + ih, line); 
                              }
                               //this.text(x - 30, y + ih, cell);
                            ih += fontSize - 5;
                           }
                        }
                        else if (item_count === 3) {
                            // for quantity
                            this.text(x + 15, y + ih, cell);
                            ih += fontSize - 5;
                        }
                        item_count++;
                        
                        /** below commented part is of API so do not remove*/

//                        for (j = 0; j < iTexts; j++) {
//                            end += Math.floor(2 * width / fontSize) - Math.ceil(xOffset / fontSize);
//                            alert(cell+ " "+start+ " "+ end);
//                            this.text(x, y + ih, cell.substring(start, end));
//                           
//                            start = end;
//                            ih += fontSize - 5; // for line height eidted by amarkant substracting 5 here. 
//                        }

                    } else {
                        if ((brControl) && (i === 0)) {
                            this.setFont("times", "bold");
                        }
                        // it is also for quantity
                        
                        this.text(x + 15, y, cell);
                        // console.log(cell);
                    }
                    x += rdim[2] / jC;
                }// if close
            }// for close

            this.setFont("times", "normal");
            y += heights[i];
        }
        return y;
    };

//calculates no.of based on the data array

    API.vendorCalColumnCount = function(data) {
        var obj = data[0];

        var i = 0;
        for (var key in obj) {
            if (key.charAt(0) !== '$') {
                ++i;
            }
        }
        return i;
    };

//draws columns based on the caluclated dimensions

    API.drawVendorColumns = function(i, rdim) {
        x = rdim[0]; // x- axis
        y = rdim[1]; // y- axis
        w = rdim[2] / i; // 
        h = rdim[3];

        var diff = 15;
        for (var j = 0; j < i; j++) {
            // edited to fix column width here based on pixel 
            if (j === 1) {
                // for item 
                var item = 12;
                this.rect(x, y, w - item, h);
                x += w;
                x -= item;
            } else if (j === 2) {
                // for vendor description 
                var item = 42;
                this.rect(x, y, w + item, h);
                x += w;
                x += item;
            } else {
                // for rest of i mean for 1st and last  ( for serical no and quantity)
                this.rect(x, y, w - diff, h);
                x += w;
                x -= diff;
            }

            // till edited by amar

//		this.rect(x, y, w, h);
//		x += w;
        }
    };

//calculates dimensions based on the data array and returns y position for further editing of document 



// column row dimension 
    API.vendorColumnRowDimension = function(data, rdim) {
        row = 0;
        x = rdim[0];

        y = rdim[1];
        lengths = [];
        for (var i = 0; i < data.length; i++) {
            obj = data[i];
            // console.log(obj);
            length = 0;
            for (var key in obj) {
                if (obj[key] !== null) {

                    if (length < obj[key].length) {
                        var maxLength = 25; // added by amarkant
                        if (obj[key].length > maxLength) {
                            lengths[row] = maxLength - 15; // all row
                        } else {
                            lengths[row] = obj[key].length;
                        }
                        //   lengths[row] = obj[key].length;
                        length = lengths[row]; //   here handle only data row
                    }
                }
            }
            ++row;
        }
        heights = [];
        for (var i = 0; i < lengths.length; i++) {
            if ((lengths[i] * (fontSize)) > width) {
                nlines = Math.ceil((lengths[i] * (fontSize)) / width);

                // edited by amarkant 
                if (i === 0) {
                    // height of vendor table header , if you want to increase or decrease 
                    // alter here .
                    heights[i] = (nlines) * (fontSize / 2) + fontSize - 15; // row height 
                } else {
                    // height for data part of table 
                    heights[i] = (nlines) * (fontSize / 2) + fontSize - 5; // row height manipulation here
                }
                ///////////

                //heights[i] = (nlines) * (fontSize / 2) + fontSize;
            } else {
                heights[i] = (fontSize + (fontSize / 2));
            }
        }

        value = 0;
        indexHelper = 0;
        SplitIndex = [];
        for (var i = 0; i < heights.length; i++) {
            value += heights[i];
            indexHelper += heights[i];
            if (indexHelper > (this.internal.pageSize.height - pageStart - 30)) {
                SplitIndex.push(i);
                indexHelper = 0;
                pageStart = rdim[4] + 30;
            }
        }
        // console.log(value); 
        //  value is row height 
        return value;
    };

//draw rows based on the length of data array

    API.drawVendorRows = function(i, rdim, hrControl) {
        x = rdim[0];
        y = rdim[1];
        w = rdim[2];
        h = rdim[3] / i;
        for (var j = 0; j < i; j++) {
            if (j === 0 && hrControl) {
                this.setFillColor(182, 192, 192);//colour combination for table header
                this.rect(x, y, w, heights[j], 'F');
                // added by amarkant 
                this.setFontSize(10);
                this.setFont("helvetica", "bold");
                this.setFontSize(10);

            } else {
                this.setFontSize(10);
                this.setFont("helvetica", "bold");
                
                this.setDrawColor(0, 0, 0);//colour combination for table borders you
                this.rect(x, y, w, heights[j]);
            }
            y += heights[j];
        }
    };

//converts table to json
/*
    API.tableToJson = function(id) {
        var table = document.getElementById(id)
                , keys = []
                , rows = table.rows
                , noOfRows = rows.length
                , noOfCells = table.rows[0].cells.length
                , i = 0
                , j = 0
                , data = []
                , obj = {}
        ;

        for (i = 0; i < noOfCells; i++) {
            keys.push(rows[0].cells[i].textContent);
        }

        for (j = 0; j < noOfRows; j++) {
            obj = {};
            for (i = 0; i < noOfCells; i++) {
                try {
                    obj[keys[i]] = rows[j].cells[i].textContent.replace(/^\s+|\s+$/gm, '');
                } catch (ex) {
                    obj[keys[i]] = '';
                }
            }
            data.push(obj);
        }
        return data.splice(1);
    };
*/
}(jsPDF.API));




/////////////////--------------- till PART OF VENDOR TABLE -------------//////////////////////
    </script>


  
     
     
    <script type="text/javascript">  
        function callConnect(pno)
        {
            var url = "callconnect.php";
            $.ajax({
                async: false,
                type: "POST",
                url: url,
                data: {'pno': pno},
                success: function(responseText) {
                    //$("#forwardedTo_"+orderId).replaceWith("<td style = 'height:100px;'>"+newVendor+"</td>");
                }
            });
        }

//////////////////////////////////// CARD MESSAGE START HERE    /////////////////////////////

        function printGiftMessageOnPDF(order_id) {

            var json = <?php echo json_encode($message_array) ?>;
            var recipientName = json['recipientName'].trim();//+"You don't it just needs to get the PSF";;
            var sendorName = json['sendorName'].trim();
            var recipient="";
            var sendor ="";
         
            // var height=8.5 , width = 5.8;
            var height=8.5 , width = 5.8;
            var pdf = new jsPDF('p','in',[height, width]);
             
            var card_message = json['cardMessage'].trim();
        
           
            if(recipientName !== "NULL" ){
                recipient = recipientName;
            }
            
            if(sendorName !== "NULL"){
                sendor = sendorName;
            }
           
                
            var message_len = card_message.length;
           //   alert(message_len);
            if(card_message === "NULL"){
                 pdf.setFontSize(28);
                 pdf.setFont("times", "bolditalic");
                 pdf.myText("Best Wishes...", {align: "center"}, width/3, height/2+1);
                 pdf.setFontSize(16);
                 pdf.setFont("helvetica", "normal");
                 pdf.myText("Flaberry Team", {align: "center"}, width/3, height-2);
                 pdf.autoPrint("card_mesage");
                 pdf.output("dataurlnewwindow"); 
                 return true;
            }
            
            
          if(json['voiceMsg']){
            var voicemsg = pdf.setFont("times", "italic").setFontSize(10).splitTextToSize(json['voiceMsg'],4.5);  
            pdf.text(0.8, 0.875, voicemsg); 

          }
            
           
           
            // if message is   <= 125 character long.
            if(message_len <= 125){  
                var msgFontSize=14,recpFontSize = 16;
                
                var recipientVerticalOffset =0;
                if(message_len > 100){
                     recipientVerticalOffset=height/2 + 1.2;
                }else if(message_len > 50 && message_len <= 100){
                    recipientVerticalOffset=height/2 + 1.3;
                }else if(message_len <= 50){
                     recipientVerticalOffset=height/2 + 1.5;
                }
               
                var lines="",verticalOffset=0;
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
                pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
                recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;
                
                verticalOffset = recipientVerticalOffset + 0.25; // inches on a 8.5 x 11 inch sheet.
                lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
                pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
                verticalOffset += (lines.length + 0.5) * msgFontSize / 72;
                
                recipientVerticalOffset=0;
                recipientVerticalOffset += verticalOffset+0.5;
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
                pdf.text(0.8, (recipientVerticalOffset + recpFontSize / 72), recipientLine);

            }else if(message_len > 125 && message_len <= 250){
               var msgFontSize=14,recpFontSize = 16;
                
                var recipientVerticalOffset=0;//height/2;
                if(message_len <= 150){
                    recipientVerticalOffset =  height/2 + 1;
                }
                else if(message_len > 150 && message_len <= 200){
                    recipientVerticalOffset =  height/2 + 0.75;
                }
                else if(message_len > 200){
                    recipientVerticalOffset =  height/2 + 0.5;
                }
                
                var lines="",verticalOffset=0;
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
                pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
                recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;
                
                verticalOffset = recipientVerticalOffset + 0.25; // inches on a 8.5 x 11 inch sheet.
                lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
                pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
                verticalOffset += (lines.length + 0.5) * msgFontSize / 72;
                
                recipientVerticalOffset=0;
                recipientVerticalOffset += verticalOffset+0.5;
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
                pdf.text(0.8, (recipientVerticalOffset + recpFontSize / 72), recipientLine);

            }
            else if(message_len > 250 && message_len <= 350){
                   ///.
                var recipientVerticalOffset= 0;//=height/2;
                var msgFontSize=14,recpFontSize = 16;
                var lines="",verticalOffset=0;
                
                if(message_len > 300){
                    recipientVerticalOffset=height/2 + 0.35;
                }else if(message_len > 250 && message_len <= 300){
                    recipientVerticalOffset=height/2 + 0.45;
                }
                
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
                pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
                recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;
                
                verticalOffset = recipientVerticalOffset + 0.25; // inches on a 8.5 x 11 inch sheet.
                lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
                pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
                verticalOffset += (lines.length + 0.5) * msgFontSize / 72;
                
                recipientVerticalOffset=0;
                recipientVerticalOffset += verticalOffset+0.5;
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
                pdf.text(0.8, (recipientVerticalOffset + recpFontSize / 72), recipientLine);

            }
            else if(message_len > 350 && message_len <= 500){
                //alert(height/2);
               //  pdf.line(0.8, height/2, 5.0, height/2); 
               var recipientVerticalOffset=0;
               
                if(message_len >350 && message_len<=400){
                     recipientVerticalOffset = height/2 +0.2;
                }else{
                     recipientVerticalOffset = height/2 +0.1; /// edit +0.1 to minus // -0.15
                }
                
                var msgFontSize=14,recpFontSize = 16;
                var lines="",verticalOffset=0;
                
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
                 
                pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
                recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;
                
                verticalOffset = recipientVerticalOffset +0.2; // inches on a 8.5 x 11 inch sheet. // edit
                
                lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
                
                pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
                verticalOffset += (lines.length + 0.5) * msgFontSize / 72; 
                recipientVerticalOffset=0;
                recipientVerticalOffset += verticalOffset+0.35; // edited 0.375
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
                pdf.text(0.8, (recipientVerticalOffset + recpFontSize / 72), recipientLine);

            }
            else if(message_len > 500 && message_len <= 800){
                   // pdf.line(0.8, height/2, 5.0, height/2); 
                  ///.
                    var recipientVerticalOffset=0;
                    var msgFontSize=14,recpFontSize = 16;
                    var lines="",verticalOffset=0;
                    
                    // shift upward when in recipient have much content
                    if(recipient.length <=75){
                        recipientVerticalOffset=(height/2) - 0.75;
                    }else if(recipient.length>75 && recipient.length <=100){
                        recipientVerticalOffset=(height/2)-0.9;
                    }else if(recipient.length>100 && recipient.length <=150){
                        recipientVerticalOffset=(height/2) - 1.15;
                    }else if(recipient.length>150){
                        // rare case .
                        recipientVerticalOffset=(height/2) - 1.5;
                    }

                    var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
                    pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
                    recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;
                    verticalOffset = recipientVerticalOffset +0.1;
                    
                    lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
                    pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
                    verticalOffset += (lines.length + 0.5) * msgFontSize / 72;
                    
                    recipientVerticalOffset=0;
                    recipientVerticalOffset += verticalOffset+0.45;
                    var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
                    pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);   

                
            } else if(message_len > 800 && message_len <= 1000){
                /// test
                //pdf.line(0.8, height/2, 5.0, height/2); 
                 var recipientVerticalOffset=0;
               
                if(message_len >800 && message_len<=900){
                     recipientVerticalOffset = height/2-1.75 ;//0.5;
                }else{
                    var recipientVerticalOffset=(height/2)-2.25;
                }
               
                var msgFontSize=14,recpFontSize = 16;
                var lines="",verticalOffset=0;
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
                pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
                recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;
                
                verticalOffset = recipientVerticalOffset + 0.2;
                lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
                pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
                verticalOffset += (lines.length + 0.5) * msgFontSize / 72;
                recipientVerticalOffset=0;
                recipientVerticalOffset += verticalOffset + 0.75;
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
                pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
                
            }
            
            else if(message_len > 1000 && message_len <= 1300){
                /////
                //pdf.line(0.8, height/2, 5.0, height/2);
                var recipientVerticalOffset=1;//(height/2) - 3 ;
                var msgFontSize=14,recpFontSize = 16;
                var lines="",verticalOffset=0;
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
                pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
                recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;
               
                verticalOffset = recipientVerticalOffset+0.25; // inches on a 8.5 x 11 inch sheet.
                lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
                pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
                verticalOffset += (lines.length + 0.5) * msgFontSize / 72;
                recipientVerticalOffset=0;
                
                if(sendor.length < 50){
                    recipientVerticalOffset += verticalOffset+0.85;
                }else{
                    recipientVerticalOffset += verticalOffset+0.7;
                }
                
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
                pdf.text(0.8, (recipientVerticalOffset + recpFontSize / 72), recipientLine);   
                
            }
             else if(message_len > 1300 && message_len <=1500){
                 ///.
                var recipientVerticalOffset = 0.5;
                var msgFontSize=14,recpFontSize = 16;
                var lines="",verticalOffset=0;
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
                pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
                recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;
                
                verticalOffset = recipientVerticalOffset+0.25; // inches on a 8.5 x 11 inch sheet.
                lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
                pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
                verticalOffset += (lines.length + 0.5) * msgFontSize / 72;
                recipientVerticalOffset=0;
                recipientVerticalOffset += verticalOffset+ 0.75;
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
                pdf.text(0.8, (recipientVerticalOffset + recpFontSize / 72), recipientLine);   
            
            }else if(message_len > 1500 && message_len <=1800){
                ///.
               // pdf.line(0.8, height/2, 5.0, height/2); 
                var recipientVerticalOffset = 0;
                var msgFontSize=13,recpFontSize = 16;
                var lines="",verticalOffset=0;
                
                if(card_message.length <=1600 && recipient.length<=50){
                    recipientVerticalOffset = 1.35;
                }else if(card_message.length >1600 && card_message.length <=1700 && recipient.length<=50){
                    recipientVerticalOffset = 1.5;
                }else{
                    recipientVerticalOffset = 1.0;
                }
               
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(recipient, 4.5);  
                pdf.text(0.8, recipientVerticalOffset + recpFontSize / 72, recipientLine);
                recipientVerticalOffset += (recipientLine.length + 0.5) * recpFontSize / 72;
                
                verticalOffset = recipientVerticalOffset+0.2; 
                lines = pdf.setFont("times", "italic").setFontSize(msgFontSize).splitTextToSize(card_message, 4.5); 
                pdf.text(0.8, verticalOffset + msgFontSize / 72, lines);
                verticalOffset += (lines.length + 0.5) * msgFontSize / 72;
                recipientVerticalOffset=0;
                
                if(sendor.length<=50){
                    recipientVerticalOffset += verticalOffset +0.85;
                }else{
                    recipientVerticalOffset += verticalOffset + 0.75;
                }
                var recipientLine = pdf.setFont("times", "bolditalic").setFontSize(recpFontSize).splitTextToSize(sendor, 4.5);  
                pdf.text(0.8, (recipientVerticalOffset + recpFontSize / 72), recipientLine); 
            }else if(message_len > 1800){
                alert("Sorry ! Unable to print due to too much text content...! Thank You.");
                return false;
            }
            
            
           
            pdf.autoPrint("card_mesage");
            pdf.output("dataurlnewwindow"); 
}     
       //////////////////////////////////// CARD MESSAGE END HERE ///////////////////////////////     
            
            
       ////////////////////////////////// VENDOR CHALLAN WORK START-/////////////////////////////////////
  
        function printVendorChallanOnPDF(order_id) {
            var valid_orderid = $.isNumeric(order_id);
            if (valid_orderid) {
                $.ajax({
                    type: "POST",
                    url: "get_delivery_order_challan_details.php",
                    data: "action=getOrderDetails&order_id=" + order_id,
                    success: function(response) {
                        var jsonData = $.parseJSON(response);
                        console.log(jsonData);

                        var address = jsonData['address'];
                        var goodsData = [];
                        var imageData = [];

                        for (var index = 0; index < jsonData['goods_desc'].length; index++) {

                            // for vendor description 
                            var vendorDesc_string = "";
                            var vendordesc = jsonData['goods_desc'][index]['vendor_description'];

                            if (vendordesc === null) {
                                vendorDesc_string="no vendor description found";
                            } else {
                                vendorDesc_string = vendordesc;
                            }

                            // for items 
                           
                            var itemName = jsonData['goods_desc'][index]['item'];
                            // To store add image of ordered products.....
                            imageData.push({
                                "image": jsonData['goods_desc'][index]['image']
                            });

                            // to add data of ordered product
                            goodsData.push({
                                // DONT ALTER OR REMOVE SPACE HERE ATLEAST OF IMAGE I AM USING FOR PIXEL CALCULATION.
                                "Image": "             ", 
                                "Item": itemName,
                                "Description": vendorDesc_string,
                                "Quantity": jsonData['goods_desc'][index]['qty']
                            });

                        }

                        // send to generate vendor challan PDF 
                        generateDeliveryChallanPDF(order_id, address, goodsData, imageData);
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            } else {

                alert(" Invalid Order Id.");
            }

        }



        function generateDeliveryChallanPDF(order_id, address, goodsData, imageData) {
            /**
             * Set PDF document page size , orientation 
             */

            var pdfdoc = new jsPDF('p', 'mm', 'a4', true);
            var pdfMinX = 15, pdfMinY = 20, pdfMaxX = 180, pdfMaxY = 263;
            var titleMaxHeight = 35;

            /*
             * set pdf properties it is optional.
             */
            pdfdoc.setProperties({
                title: 'Order Delivery Challan',
                subject: 'PDF to generate Order Delivery  ',
                author: 'Flaberry Service Pvt. Ltd.',
                keywords: 'generated, javascript, web 2.0, ajax',
                creator: 'MEEE'
            });

            pdfdoc.rect(pdfMinX, pdfMinY, pdfMaxX, pdfMaxY); // outer border create a rectangle square 

            // for heading
            pdfdoc.setFontSize(24);
            pdfdoc.setFont("helvetica", "bold");
            pdfdoc.myText("flaberry.com", {align: "center"}, 2, 15);

            pdfdoc.setFontSize(16);
            pdfdoc.setFont("times", "bold");
            pdfdoc.myText("DELIVERY CHALLAN", {align: "center"}, (5 * pdfMinX), titleMaxHeight - 8);

            //  pdfdoc.text((5 * pdfMinX), titleMaxHeight - 8, 'DELIVERY CHALLAN');
            pdfdoc.setFontSize(10);
            pdfdoc.setFont("helvetica");
            pdfdoc.text((6 * pdfMinX) + 2, titleMaxHeight - 3, 'Flaberry.com');
            pdfdoc.line(pdfMinX, titleMaxHeight, pdfMinX + pdfMaxX, titleMaxHeight);// x1,y1,x2,y2
            pdfdoc.setLineWidth(0.1);


            // for recipient address
            var MidDiv = (pdfMinX + pdfMaxX) / 2;
            var addressHorizontalLineHeight = (2 * titleMaxHeight) + pdfMinX;
            // --------------->>>>>>>>>>>>>line
            pdfdoc.line(pdfMinX, addressHorizontalLineHeight - 8, pdfMinX + pdfMaxX, addressHorizontalLineHeight - 8);// x1,y1,x2,y2
            ////// ^^^^^^^^^^
            pdfdoc.line(MidDiv + 5, titleMaxHeight, MidDiv + 5, ((2 * titleMaxHeight) + pdfMinX) - 8); // vertical line 


            pdfdoc.text(pdfMinX + 5, titleMaxHeight + 5, 'Consignee Name & Address : ');
            pdfdoc.text(pdfMinX + 5, titleMaxHeight + 12, 'Recipient\'s Name :');
            pdfdoc.setFont("helvetica", "normal");
            pdfdoc.text(pdfMinX + 5, titleMaxHeight + 17, address['name'].trim());
            pdfdoc.setFont("helvetica", "bold");
            pdfdoc.text(pdfMinX + 5, titleMaxHeight + 30, 'Contact Number : ');
            pdfdoc.setFont("helvetica", "normal");
            if(address['telephone'] === null || address['telephone'] === "undefined"){
            }else{
                pdfdoc.text((4 * pdfMinX) - 8, titleMaxHeight + 30, address['telephone']);
            }
            pdfdoc.setFont("helvetica", "bold");
            
            pdfdoc.text(MidDiv + 10, titleMaxHeight + 5, 'Order No                : ' + order_id);
            pdfdoc.text(MidDiv + 10, titleMaxHeight + 12, 'Recipient\'s Home / Office Address :');
            pdfdoc.setFont("helvetica", "normal");

            var street_address = "";

            if (address['streetfull'] === null) {
            } else {
               street_address = address['streetfull'];
            }

            if (address['companyname'] === null || address['companyname'] === "undefined") {
               
               var rec_address = address['name']+ "\n" + street_address +"\n"+address['region']+" , "+ address['country_id'] +" - "+address['postcode'] ;
               var line = pdfdoc.setFont("helvetica", "normal").setFontSize(10).splitTextToSize(rec_address, 85);  
               pdfdoc.text(MidDiv + 10, titleMaxHeight + 17, line); 
               pdfdoc.setFont("helvetica", "bold");

            } else {
               var rec_address = address['name']+ "\n"+address['companyname']+"\n" + street_address +"\n"+address['region']+" , "+ address['country_id'] +" - "+address['postcode'] ;
               var line = pdfdoc.setFont("helvetica", "normal").setFontSize(10).splitTextToSize(rec_address, 85);  
               pdfdoc.text(MidDiv + 10, titleMaxHeight + 17, line); 
               pdfdoc.setFont("helvetica", "bold");
            }

            // receiver

            var receiverHorizontalLineHeight = (2 * addressHorizontalLineHeight) - 25;//125
            ////////////^^^^^^^^^^^^^^line
            pdfdoc.line(MidDiv + 5, ((2 * titleMaxHeight) - 8 + pdfMinX), MidDiv + 5, receiverHorizontalLineHeight - 20); // vertical line 

            /////--------------- line ->>>>>>.
            pdfdoc.line(pdfMinX, receiverHorizontalLineHeight - 20, pdfMinX + pdfMaxX, receiverHorizontalLineHeight - 20);// x1,y1,x2,y2

            pdfdoc.setLineWidth(0.1);


            pdfdoc.text(pdfMinX + 5, addressHorizontalLineHeight - 2, 'Receiver\'s Name :            ');
            pdfdoc.line(pdfMinX + 5, addressHorizontalLineHeight + 5, MidDiv, addressHorizontalLineHeight + 5);
            pdfdoc.text(pdfMinX + 5, addressHorizontalLineHeight + 12, 'Relation with recipient :  ');
            pdfdoc.line(pdfMinX + 5, addressHorizontalLineHeight + 21, MidDiv, addressHorizontalLineHeight + 21);
            pdfdoc.text(pdfMinX + 5, addressHorizontalLineHeight + 28, 'Phone/Mobile no :          ');
            pdfdoc.line(pdfMinX + 5, addressHorizontalLineHeight + 36, MidDiv, addressHorizontalLineHeight + 36);
            pdfdoc.text(MidDiv + 10, addressHorizontalLineHeight, 'Date                            :  ' + address['dod'].trim());

            // to break in multi-line user comments.

             pdfdoc.text(MidDiv + 10, addressHorizontalLineHeight + 8, 'Special instruction    : ');
            if (address['customer_comment'] === null) {

            } else {
              /*
               var line = pdfdoc.setFont("helvetica", "normal").setFontSize(9.5).splitTextToSize(address['customer_comment'], 85);  
               pdfdoc.text(MidDiv + 10, addressHorizontalLineHeight + 13, line); 
             */
            }
            pdfdoc.setFontSize(10);
            pdfdoc.setFont("helvetica", "bold");

            pdfdoc.text(MidDiv + 10, addressHorizontalLineHeight + 35, 'Delivery Type             : ');
            pdfdoc.setFont("helvetica", "normal");
            pdfdoc.text(MidDiv + 50, addressHorizontalLineHeight + 35, address['shipping_type']);
            pdfdoc.setFont("helvetica", "bold");


            // goods
            var descYaxis = 135;
            var descXaxis = 100;
            pdfdoc.myText("DESCRIPTION OF GOODS", {align: "center"}, descXaxis, descYaxis);

            var fontSize = 10, height = descYaxis + 15;
            pdfdoc.setFont("helvetica", "normal");
            pdfdoc.setFontSize(fontSize);

            // To create image on PDF....
            var imageHeight = height + 1.125;
            for (var indexImage = 0; indexImage < imageData.length; indexImage++) {
                pdfdoc.addImage(imageData[indexImage]['image'], 'JPEG', 18, imageHeight, 25, 18);
                imageHeight += 20;
            }
            // To create image on PDF end 


            // to generate vendor data table 
            height = pdfdoc.drawVendorChallanTable(goodsData, {
                xstart: pdfMinX + 1, // margin from left side
                ystart: 0, // 
                tablestart: height - 10, // position from top to 
                marginright: pdfMinX - 19, // table right margin
                xOffset: 5, //
                yOffset: 5 // an offset of tbale cell avlue
            });


            // to fix at a perticular height = 241
            pdfdoc.setFont("helvetica", "bold");
            pdfdoc.line(pdfMinX, 241, pdfMinX + pdfMaxX, 241);
            pdfdoc.myText("Feedback from Customer (Mandatory)", {align: "center"}, 100, 245);

            // bottom part data items....
            height = 263;
            pdfdoc.setFontSize(10);
            pdfdoc.line(pdfMinX, height, pdfMinX + pdfMaxX, height);// x1,y1,x2,y2

            pdfdoc.setFont("helvetica", "bolditalic");
            height += 3;
            pdfdoc.text(pdfMinX + 1, height + 1, 'This is certify that all the above above-mentained materials have been received in good condition.');

            // for name & signature of receiver | company seals
            pdfdoc.setFontSize(11);
            height += 10;
            pdfdoc.setFont("helvetica", "bold");
            pdfdoc.text(150, height - 1, 'Authorised Signatory');
            pdfdoc.setFont("helvetica", "normal");
            
             pdfdoc.text(155, height + 3.5, address['logged_in_auth_user_name']);
             pdfdoc.setFont("helvetica", "bold");

            pdfdoc.text(pdfMinX + 1, height - 1, 'Name & Signature of Receiver');
           
            // for customer support
            pdfdoc.setFontSize(16);
            pdfdoc.setFont("times", "bold");
            pdfdoc.myText("CUSTOMER SUPPORT +91-8010760760", {align: "center"}, 100, height + 13);
 
            pdfdoc.autoPrint("vendor_challan");
            pdfdoc.output("dataurlnewwindow");
        }

    </script>

    
    
    
    
    <script type="text/javascript">
         function myOptCallConnect(phone_no) {
            var cust_phone_no = $.isNumeric(phone_no);
            if (cust_phone_no) {
                $.ajax({
                    type: "POST",
                    url: "myoperator/myoperatorconn.php",
                    data: "action=connectToCustomer&phone_no=" + phone_no,
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            } else {
                alert(" Invalid phone no.");
            }
        }
     </script>  

