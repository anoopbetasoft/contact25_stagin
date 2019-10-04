<?php 

include ("../../config.php");






/*
$sql = 'SELECT * FROM spares where spares.s_id = "'.$_POST['id'].'"';
$query = mysql_query ($sql);
$row = mysql_fetch_assoc ($query);
$num_rows = mysql_num_rows ($query);

if ($num_rows > 0){
	
	do{
		$current_stock = $row['s_qty'];
	}while($row = mysql_fetch_assoc ($query));
	
}
if ($current_stock == 0){
	$extra = ', spares.`s_change` = "RELIST"';
}else{
	$extra = '';
}
$new_stock = $current_stock + $_POST['stock_level'];



$sql = 'UPDATE spares SET s_qty = "'.$new_stock.'" '.$extra.' WHERE s_id = "'.$_POST['id'].'"';
mysql_query($sql);
echo $new_stock;*/

?>

  <p style="font-size:30px; padding:15px; line-height:35px;">
                   
                Now Choose Currency/Delivery Country
                  
              </p> 
                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="text-align:center;" width="33%"><form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="PayPal_Form" target="_top">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="antony.vila@jukeboxmarketing.com">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="<?php echo $_POST['quantity']?> x <?php echo $_POST['descrip']?>">
<input type="hidden" name="quantity" value="<?php echo $_POST['quantity']?>">
<input type="hidden" name="amount" value="<?php echo $_POST['priceUK']?>">
<input type="hidden" name="currency_code" value="GBP">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
<a class="button-big-icon button-red"  style=" cursor:pointer; text-shadow:none;" onclick="document.PayPal_Form.submit();">Buy (<?php echo $_POST['quantity']?> @ &pound;<?php echo $_POST['priceUK']?> each) UK Delivery</a>
</form></td>
    <td style="text-align:center;" width="33%">	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="PayPal_Form_US" target="_top">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="antony.vila@jukeboxmarketing.com">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="<?php echo $_POST['quantity']?> x <?php echo $_POST['descrip']?>">
<input type="hidden" name="quantity" value="<?php echo $_POST['quantity']?>">
<input type="hidden" name="amount" value="<?php echo $_POST['priceUS']?>">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
<a class="button-big-icon button-red"  style=" cursor:pointer; text-shadow:none;" onclick="document.PayPal_Form_US.submit();">Buy (<?php echo $_POST['quantity']?> @ $<?php echo $_POST['priceUS']?> each) USA Delivery</a>
</form></td>
    <td style="text-align:center;" width="33%">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="PayPal_Form_AU" target="_top">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="antony.vila@jukeboxmarketing.com">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="<?php echo $_POST['quantity']?> x <?php echo $_POST['descrip']?>">
<input type="hidden" name="quantity" value="<?php echo $_POST['quantity']?>">
<input type="hidden" name="amount" value="<?php echo $_POST['priceAU']?>">
<input type="hidden" name="currency_code" value="AUD">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
<a class="button-big-icon button-red"  style=" cursor:pointer; text-shadow:none;" onclick="document.PayPal_Form_AU.submit();">Buy (<?php echo $_POST['quantity']?> @ AU$<?php echo $_POST['priceAU']?> each) Australia Delivery</a>
</form>
</td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center;"><form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="PayPal_Form_RW" target="_top">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="antony.vila@jukeboxmarketing.com">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="<?php echo $_POST['quantity']?> x <?php echo $_POST['descrip']?>">
<input type="hidden" name="quantity" value="<?php echo $_POST['quantity']?>">
<input type="hidden" name="amount" value="<?php echo $_POST['priceRW']?>">
<input type="hidden" name="currency_code" value="GBP">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
<a class="button-big-icon button-green"  style=" cursor:pointer; text-shadow:none;" onclick="document.PayPal_Form_RW.submit();">Buy Rest of the World Buy (<?php echo $_POST['quantity']?> @ &pound;<?php echo $_POST['priceRW']?> each) Rest of World Delivery</a>
</form></td>
  </tr>

  
</table>