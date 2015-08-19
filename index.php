<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>CSB Assistant</title>
<link rel="shortcut icon" href="../../../favicon.ico">
<style type="text/css">
.t1{clear:both; width:25%; float:left; text-align:right; margin:0px 0px 0px 0px;}
.t2{width:5%; float:left; text-align:left; margin:0px 0px 0px 0px;}
.t3{width:8%; float:left; text-align:left; margin:0px 0px 0px 0px;}
.t4{width:15%; float:left; text-align:left; margin:0px 0px 0px 0px;}
.t5{width:53%; float:left; text-align:left; margin:0px 0px 0px 0px;}
.t10{clear:both; width:25%; float:left; text-align:right; margin:0px 0px 0px 0px;}
.t11{width:25%; float:left; text-align:left; margin:0px 0px 0px 0px;}
.t12{width:25%; float:left; text-align:right; margin:0px 0px 0px 0px;}
.t13{width:25%; float:left; text-align:left; margin:0px 0px 0px 0px;}
.t14{width:10%; float:left; text-align:left; margin:0px 0px 0px 0px;}
.t15{width:10%; float:left; text-align:left; margin:0px 0px 0px 0px;}
.c1{height:30px;  margin:0px 5px 0px 0px;}
.c1t{height:30px; margin:3px 5px 0px 0px;}
.c2{height:30px; margin:0px 0px 0px 0px;}
.c10{height:5px; margin:0px 0px 0px 0px;}
.buff{clear:both; width:100%; height:15px;}
</style>
</head>
<body>
<?php
// Adam Garnsworthy
// July 2010
include('Experts.php');

echo "<div style=\"width:100%;\">";
echo "<h1>Charge-State Booster Page</h1>";
echo "<FONT color='#FF0000'><h1>UNDER DEVELOPMENT - PLEASE REPORT ERRORS OR SUGGESTED IMPROVEMENTS</h1></font>";
echo "<p>The Charge-State Booster (CSB) is intended to produce radioactive ion beams in charge states greater than 1+. Stable isotopes are also ionized and produced by this device so must be considered when selecting which beam to extract. This page may help identify which charge-state might be the cleanest.</p>";

  
while( sizeof( $cocktailA ) > 0 ) array_pop( $cocktailA );
while( sizeof( $cocktailZ ) > 0 ) array_pop( $cocktailZ );
while( sizeof( $cocktailQ ) > 0 ) array_pop( $cocktailQ );
while( sizeof( $cocktail2A ) > 0 ) array_pop( $cocktail2A );
while( sizeof( $cocktail2Z ) > 0 ) array_pop( $cocktail2Z );
while( sizeof( $cocktail2Q ) > 0 ) array_pop( $cocktail2Q );
$AQ=$A=$Z=0;
  $Liner=2;
$Resolve=100;
if($_POST['submit2'] && isset($_POST["AQValue"])){$AQ=$_POST["AQValue"]; $Liner=$_POST["Liner_material"]; $Resolve=$_POST["resolving_power"];}
if(($_POST['submit1'] || $_POST['submit4'] || $_POST['submit69']) && isset($_POST["Mass"])){$A=$_POST["Mass"];}
if(($_POST['submit1'] || $_POST['submit4'] || $_POST['submit69']) && isset($_POST["Zvalue"])){$Z=$_POST["Zvalue"]; $Liner=$_POST["Liner_material"]; $Resolve=$_POST["resolving_power"];}
if(($_POST['submit4'] || $_POST['submit69']) && isset($_POST["Charge"])){$Q=$_POST["Charge"]; $Liner=$_POST["Liner_material"]; $Resolve=$_POST["resolving_power"];}
if(($_POST['submit69'])){ $EnergyLoss=$_POST["ELoss"];}
?>

<form name="main" action="./" method="post">

  <div class="t10">
    Select CSB liner material:
    </div>
  <div class="t11">
    <select name="Liner_material">
    <option value="2"<?php if($Liner==2){echo " selected";} ?>>Aluminium</option>
    <option value="1"<?php if($Liner==1){echo " selected";} ?>>Stainless Steel</option>
    </select>
    </div>
  <div class=\"buff\"></div>
  
  <div class="t10"> 
  Select Mass and Element:
    </div>
  <div class="t11">
  <input type="text" name="Mass" value="<?php if($A>0){echo $A;} ?>" style="width:50px;">
<?php

echo "<select name=\"Zvalue\"\n";
for($i=0; $i<=92; $i++){
  echo "<option value=\"";
  echo $i;
  echo "\"";
  if($i==$Z && $Z>0){echo " selected";}
  echo ">";
  echo $Element[$i];
  echo "</option>";
  echo "\n";
 }
echo ">\n<BR>\n";

?>
<script type="text/javascript">
 function set_resolve(power)
  {
    alert("javascript function "+(0.5/power));
  //  alert(power);
  document.main.resolving_power.value=(0.5/power);
  } 
</script>
<BR>
<BR>
<BR>
    <p>
      <input type="submit" name="submit1" value="Show A/Q values">
    </p>
  </div>
  <div class="t12"> 
  or Enter A/Q Value:
   </div>
      <div class="t13">
  <input type="text" name="AQValue" value="<?php if($AQ>0){echo $AQ;} ?>" style="width:50px;">
      <input type="submit" name="submit2" value="Show Species">
  </div>

  <div class=\"buff\"></div>
  
  <div class="t10">
    Enter resolving power:
    </div>
  <div class="t11">

    <!--
    <select name="resolving_power">
    <option value="0.005000"<?php if($Resolve==0.005000){echo " selected";} ?>>100 (Magnet immediately following CSB)</option>
    <option value="0.001667"<?php if($Resolve==0.001667){echo " selected";} ?>>300 (S-bend separation and slits)</option>
    </select>
    <input type="button" value="Test" onclick="set_resolve()" />
-->
    <input type="text" name="resolving_power" value="<?php echo $Resolve; ?>" style="width:50px;">

 
    </div>
  <div class=\"buff\"></div>
  
</form>
<BR>
<BR>

  <?php

  if($_POST['submit1'] && $Z>0){
    $beammass=getthismass($A,$Z);
    echo "<div class=\"buff\"></div>";
    echo $Element[$Z];
    echo " has an atomic number: ";
    echo $Z;
    echo "<BR>\n";
    echo $A,$Element[$Z];
    echo " has an atomic mass of: ";
    echo $beammass," amu.<BR>\n";
    if($Liner>0){
    echo "You have selected ";
    if($Liner==1){echo "a Stainless Steel";} if($Liner==2){echo "an Aluminium";}
    echo " liner for the CSB ECR source.\n<BR>";
    }
    if($Resolve>0){
    echo "You have selected a resolving power of ";
    echo $Resolve;
    echo "\n<BR>";
    }
    echo "<BR>\n<font color='#0000FF'>Blue font indicates species which can currently be delivered to ISAC II (i.e. have an A/Q value between 5 and 6.4 only).</font><BR>\n";
    echo "<font color='#7A37B8'>Purple font indicates species which may be delivered to ISAC II in the future with upgrades (i.e. have an A/Q value between 6.4 and 7 only).</font><BR>\n";
    echo "\"Possible Companions\" includes any stable species with an A/Q value within +/- 0.5% (1/100 resolving power of magnet) of the species of interest. Obviously not all of these stable species will be present and the amount of each species will depend on the operating conditions of the CSB (temperature/pressure etc.) as well as the recent CSB history (i.e. isotopes recently injected into the device).<BR>\n";
    echo "<font color='#FF0000'>Red font indicates elements which are
		known to come from the CSB. (Residual gases and the
		material of the CSB itself).</font><BR>\n";
    echo "The masses used here to calculate A/q values are taken from the AME2003 atomic mass evaluation available at http://www.nndc.bnl.gov/masses.\n<BR>";
    echo "<BR><BR>\n";
    echo "<div class=\"t2\"><b>Species</b></div>\n";
    echo "<div class=\"t3\"><b>Charge State</b></div>\n";
    echo "<div class=\"t4\"><b>A/Q Value</b></div>\n";
    echo "<div class=\"t5\"><b>Possible Companions</b></div>\n";
    echo "<BR>\n";
    echo "<BR>\n";
 for($i=1; $i<=$Z; $i++){
    echo "<div class=\"t2\">\n";
  if((($beammass-$i*$emass)/$i)>4.9 && (($beammass-$i*$emass)/$i)<=6.4){echo "<font color='#0000FF'>";}
  if((($beammass-$i*$emass)/$i)>6.4 && (($beammass-$i*$emass)/$i)<=7){echo "<font color='#7A37B8'>";}
  echo $A;
  echo $Element[$Z];
    echo "</div><div class=\"t3\">\n";
  echo $i;
    echo "</div><div class=\"t4\">\n";
    printf("%.3f",(($beammass-$i*$emass)/$i));
  if((($beammass-$i*$emass)/$i)>4.9 && (($beammass-$i*$emass)/$i)<=7){
    echo "<form action=\"./\" method=\"post\">";
    echo "<input type=\"hidden\" name=\"Mass\" value=\"";
    echo $A;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Zvalue\" value=\"";
    echo $Z;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Charge\" value=\"";
    echo $i;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Liner_material\" value=\"";
    echo $Liner;
      echo "\">";
    printf("<input type=\"hidden\"name=\"resolving_power\" value=\"%d\">",$Resolve);
    echo "<input type=\"hidden\"name=\"ELoss\" value=\"0.0\">";
    echo "<input type=\"submit\" name=\"submit4\" value=\"Accelerate\">";
    echo "<BR><input type=\"submit\" name=\"submit69\" value=\"Apply 2nd Filter\">";
    echo "</font>";
    echo "</div><div class=\"t5\">\n";
      $num=$num2=0;
while( sizeof( $cocktailA ) > 0 ) array_pop( $cocktailA );
while( sizeof( $cocktailZ ) > 0 ) array_pop( $cocktailZ );
while( sizeof( $cocktailQ ) > 0 ) array_pop( $cocktailQ );
while( sizeof( $cocktail2A ) > 0 ) array_pop( $cocktail2A );
while( sizeof( $cocktail2Z ) > 0 ) array_pop( $cocktail2Z );
while( sizeof( $cocktail2Q ) > 0 ) array_pop( $cocktail2Q );

    for($j=0; $j<sizeof($StableZ); $j++){
      $thismass=getthismass($StableA[$j],$StableZ[$j]);
      for($k=1; $k<$StableZ[$j]; $k++){
	if(((($thismass-$k*$emass)/$k)>((($beammass-$i*$emass)/$i)-((($beammass-$i*$emass)/$i)*(0.5/$Resolve)))) && ((($thismass-$k*$emass)/$k)<((($beammass-$i*$emass)/$i)+((($beammass-$i*$emass)/$i)*(0.5/$Resolve))))){
	  $jj=0;
	  for($ii=0; $ii<sizeof($BadZ[$Liner]); $ii++){
               if($StableZ[$j]==$BadZ[$Liner][$ii]){
                   echo "<font color='#FF0000'>"; $jj=1; $cocktailA[$num]=$StableA[$j]; $cocktailZ[$num]=$StableZ[$j]; $cocktailQ[$num]=$k; $num++; break;
                   }}
	  echo "<SUP>"; echo $StableA[$j]; echo "</SUP>"; echo $Element[$StableZ[$j]];
          echo "<SUP>"; echo $k; echo "+</SUP>="; printf("%.3f",(($thismass-$k*$emass)/$k)); echo "  ";
          $cocktail2A[$num2]=$StableA[$j]; $cocktail2Z[$num2]=$StableZ[$j]; $cocktail2Q[$num2]=$k; $num2++;
      if($jj==1){echo "</font>"; }
	}
      }
    }
      
    echo '<input type="hidden" name="cocktailA" value="', base64_encode(serialize($cocktailA)),'">';
    echo '<input type="hidden" name="cocktailZ" value="', base64_encode(serialize($cocktailZ)),'">';
    echo '<input type="hidden" name="cocktailQ" value="', base64_encode(serialize($cocktailQ)),'">';
    echo '<input type="hidden" name="cocktail2A" value="', base64_encode(serialize($cocktail2A)),'">';
    echo '<input type="hidden" name="cocktail2Z" value="', base64_encode(serialize($cocktail2Z)),'">';
    echo '<input type="hidden" name="cocktail2Q" value="', base64_encode(serialize($cocktail2Q)),'">';
    echo "</form>";
    echo "</div></div><div class=\"buff\"></div><BR>\n";
  }
  else{echo "</div><BR>\n";}
 }
  
  echo "<BR><BR><BR>";

  ///////////////////////////////////////////////////
  // I should get the page into an array of lines and then print that page with appropriate php array syntax.
  // Then copy and save it in this file. Then search with a substring, each line to find the correct entry
  // Build the mass from that line and then use it in the calculation.
  // Hmm, is that fats enough? It will be slow. Maybe I should build
  // an array of the masses so I can look them up quickly. 2Dimensional
  // arrays do not work so well in php so how to get around that issue?
  ////////////////////////////////////////////////////
  
  // Get the web page content
//  $page = join( '<BR>', file('http://www.nndc.bnl.gov/masses/mass.mas03round'));

  // Print the page as it is
 //  echo $page;

  // Extract the content into an array, one string per line of the page
//  $page2=explode('<BR>',$page);
 // echo "<BR><BR><BR>";


//  $lastA=0;
//function masschange($thisA)
//  {
//  global $lastA;
//  if($thisA!=$lastA && $thisA%20==0){ echo "};<BR>\$masses",$thisA,"=array(";}
//  else{ echo ", ";}
//  $lastA=$thisA;
//  }
  
  // Extract single words from a single line

// for($i=41; $i<2897; $i++)
//  {
//  $j=0;
//  $line=$page2[$i];
//  $line=str_replace(".","",$line);
//  $line2=str_word_count($line,1,'1234567890');

//  if(strcmp($line2[$j],"0")==0){masschange($line2[$j+1]+$line2[$j+2]); echo ($line2[$j+1]+$line2[$j+2]); echo ","; echo $line2[$j+2]; $j+=4;}else{masschange($line2[$j]+$line2[$j+1]); echo ($line2[$j]+$line2[$j+1]); echo ","; echo $line2[$j+1]; $j+=2;}
//  echo ",";
//  echo $line2[str_word_count($line,0,'1234567890')-3];
//  echo ".";
//  echo $line2[str_word_count($line,0,'1234567890')-2];
//  }
//    echo ");";

}
  // end of submit 1

if($_POST['submit69'] && $Z>0)
  {
while( sizeof( $cocktail2A ) > 0 ) array_pop( $cocktail2A );
while( sizeof( $cocktail2Z ) > 0 ) array_pop( $cocktail2Z );
while( sizeof( $cocktail2Q ) > 0 ) array_pop( $cocktail2Q );
  $cocktail2A = unserialize(base64_decode($_POST["cocktail2A"]));
  $cocktail2Z = unserialize(base64_decode($_POST["cocktail2Z"]));
  $cocktail2Q = unserialize(base64_decode($_POST["cocktail2Q"]));

    $beammass=getthismass($A,$Z);
    echo "<div class=\"buff\"></div>";
    echo $Element[$Z];
    echo " has an atomic number: ";
    echo $Z;
    echo "<BR>\n";
    echo $A,$Element[$Z];
    echo " has an atomic mass of: ";
    echo $beammass," amu.<BR>\n";
    if($Liner>0)
      {
	echo "You have selected ";
	if($Liner==1){echo "a Stainless Steel";} if($Liner==2){echo "an Aluminium";}
	echo " liner for the CSB ECR source.\n<BR>";
      }
    if($Resolve>0)
      {
	echo "You have selected a resolving power of ";
	// if($Resolve==0.00500){echo "100";} if($Resolve==0.001667){echo "300";}
	echo $Resolve;
	echo "\n<BR>";
      }
    echo "<BR>\n<font color='#0000FF'>Blue font indicates species which can currently be delivered to ISAC II (i.e. have an A/Q value between 5 and 6.4 only).</font><BR>\n";
    echo "<font color='#7A37B8'>Purple font indicates species which may be delivered to ISAC II in the future with upgrades (i.e. have an A/Q value between 6.4 and 7 only).</font><BR>\n";
    echo "\"Possible Companions\" includes any stable species with an A/Q value within +/- 0.5% (1/100 resolving power of magnet) of the species of interest. Obviously not all of these stable species will be present and the amount of each species will depend on the operating conditions of the CSB (temperature/pressure etc.) as well as the recent CSB history (i.e. isotopes recently injected into the device).<BR>\n";
    echo "<font color='#FF0000'>Red font indicates elements which are
		known to come from the CSB. (Residual gases and the
		material of the CSB itself).</font><BR>\n";
    echo "The masses used here to calculate A/q values are taken from the AME2003 atomic mass evaluation available at http://www.nndc.bnl.gov/masses.\n<BR>";
    
    echo "<BR><BR>\n";
    echo "<p style=\"font-size:larger\">The first filter applied is for ";
    echo $A,$Element[$Z],$Q;
    echo "+";
    echo", A/Q of ";
    printf("%.3f",(($beammass-$Q*$emass)/$Q));
    echo ". A resolving power of 1/25 is used to transport the cocktail through the DSB section here.";
    echo "<BR>The green windows indicate the resolving power of the RFQ pre-buncher (1/1000) for the first A/q and the DSB pre-buncher (1/400) for the 2nd A/q.";
    echo "<BR>Percentage energy loss used is ";
    echo $EnergyLoss;
    echo "</p><BR>";

    
    echo "<form action=\"./\" method=\"post\">";
    echo "<input type=\"hidden\" name=\"Mass\" value=\"";
    echo $A;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Zvalue\" value=\"";
    echo $Z;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Charge\" value=\"";
    echo $Q;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Liner_material\" value=\"";
    echo $Liner;
      echo "\">";
    printf("<input type=\"hidden\"name=\"resolving_power\" value=\"%d\">",$Resolve);
    echo '<input type="hidden" name="cocktail2A" value="', base64_encode(serialize($cocktail2A)),'">';
    echo '<input type="hidden" name="cocktail2Z" value="', base64_encode(serialize($cocktail2Z)),'">';
    echo '<input type="hidden" name="cocktail2Q" value="', base64_encode(serialize($cocktail2Q)),'">';

    echo "Change percentage energy loss in the stripping foil: <input type=\"text\" name=\"ELoss\" value=\"";
      if($EnergyLoss>0){printf("%.1f",$EnergyLoss);}
    echo "\" style=\"width:50px;\">%.";
    echo "<input type=\"submit\" name=\"submit69\" value=\"Recalculate\">";
    echo "</form>";

    echo "<BR><BR><BR>\n";
    echo "<div class=\"t2\"><b>Species</b></div>\n";
    echo "<div class=\"t3\"><b>Charge State</b></div>\n";
    echo "<div class=\"t4\"><b>A/Q Value</b></div>\n";
    echo "<div class=\"t5\"><b>Possible Companions</b></div>\n";
    echo "<BR>\n";
    echo "<BR>\n";
    for($i=$Q; $i<=$Z; $i++) // Loop through charge states of the beam
      {

   // Define the colors
    $plotcaption[$i]=sprintf("A/q Cocktail - %d%s%d",$A,$Element[$Z],$i);
    $plotname[$i]=sprintf("AQ_Cocktail_%d%s%d_%d.png",$A,$Element[$Z],$Q,$i);

    $imagenum=$i;
// Y WILL BE Fraction, X WILL BE charge state
// SET MINIMUM Y and X
    $ymin[$imagenum]=round($A/$i,2)-((($A)/$i)*(0.5/25));
    $xmin[$imagenum]=round($A/$Q,2)-((($A)/$Q)*(0.5/$Resolve));

// SET MAXIMUM Y and X
    $xmax[$imagenum]=round($A/$Q,2)+((($A)/$Q)*(0.5/$Resolve));
    $ymax[$imagenum]=round($A/$i,2)+((($A)/$i)*(0.5/25));

// SCALE PARAMETERS FOR GRAPH
    $plotwidth=600;
    $plotheight=round($plotwidth*3/4);
    $plotmarginleft=50;
    $plotmarginbottom=60;
    $plotmarginother=20;
    $plotlegendmargin=60;
    $leftx=$plotmarginleft;
    $rightx=$plotwidth-$plotmarginother-$plotlegendmargin;
    $topy=$plotmarginother;
    $bottomy=$plotheight-$plotmarginbottom;
    $xscale[$imagenum]=($plotwidth-$plotmarginleft-$plotmarginother-$plotlegendmargin)/($xmax[$imagenum]-$xmin[$imagenum]);
    $yscale[$imagenum]=($plotheight-$plotmarginbottom-$plotmarginother)/($ymax[$imagenum]-$ymin[$imagenum]);
//  CREATE IMAGE
    $img[$imagenum]=imagecreatetruecolor($plotwidth, $plotheight);
    $color[11]=imagecolorallocate($img[$imagenum], 255, 255, 255); // white
    $color[12]=imagecolorallocate($img[$imagenum], 0, 0, 0); // black
    $color[0]=imagecolorallocate($img[$imagenum], 255, 0, 0); // red
    $color[1]=imagecolorallocate($img[$imagenum], 0, 255, 0); // green
    $color[10]=imagecolorallocate($img[$imagenum], 0, 0, 255); // blue
    $color[2]=imagecolorallocate($img[$imagenum], 255, 255, 0); // yellow
    $color[3]=imagecolorallocate($img[$imagenum], 0, 255, 255); // cyan
    $color[4]=imagecolorallocate($img[$imagenum], 255, 165, 0); // orange
    $color[13]=imagecolorallocate($img[$imagenum], 64, 224, 208); // light blue
    $color[14]=imagecolorallocate($img[$imagenum], 0, 250, 154); // light green
    imagefilledrectangle($img[$imagenum], 0, 0, $plotwidth-1, $plotheight-1, $color[11]);

    // LEGEND Parameters
    $legendx=$plotwidth-60;
    $legendy=$plotmarginother+20;
	      
	echo "<div class=\"t2\">\n";
	echo $A;
	echo $Element[$Z];
	echo "</div><div class=\"t3\">\n";
	echo $i;
	echo "</div><div class=\"t4\">\n";
	  $BeamEnergy=1.4;
	  $X=3.86*(sqrt(($BeamEnergy*$A)/$A)/(pow($Z,0.45)));
	  $meanQ=$Z*pow((1+pow($X,(-1/0.6))),-0.6);
	  $s=0.5*pow(($meanQ*(1-pow(($meanQ/$Z),(1/0.6)))),0.5); // for Z>20
	printf("%.1f",charge_fraction($i,$s,$meanQ));
	  echo "%";
	echo "<BR>This A/Q = ";
	  if($EnergyLoss>0.0){
	  $AQWindow2=((($beammass-$i*$emass)/$i) * (sqrt(1.0-($EnergyLoss/100))));
        	printf("%.3f",$AQWindow2);
	  }else{
	  $AQWindow2=((($beammass-$i*$emass)/$i));
	  printf("%.3f",$AQWindow2);
	  }
	echo "<BR>First A/Q = ";
	$AQWindow1=((($beammass-$Q*$emass)/$Q));
	printf("%.3f",$AQWindow1);
        echo "<BR>";
	echo $A,$Element[$Z],$Q;
	echo "+";
	echo "</div><div class=\"t5\">\n";

	  if($EnergyLoss>0.0){
	  $AQvalue=(($beammass-$i*$emass)/$i)*(sqrt(1.0-($EnergyLoss/100)));
	  }
	  else{
	  $AQvalue=(($beammass-$i*$emass)/$i);
	  }

// Draw A/q acceptance windows
// RFQ pre-buncher phasing Wide window
$x1=(($AQWindow1-($AQWindow1*(0.5/400))-$xmin[$imagenum])*$xscale[$imagenum]);
$x2=(($AQWindow1+($AQWindow1*(0.5/400))-$xmin[$imagenum])*$xscale[$imagenum]);
$x1=$x1+$plotmarginleft;
$x2=$x2+$plotmarginleft;
imagefilledrectangle($img[$imagenum],$x1,$topy+1,$x2,$bottomy-1,$color[13]);
// DSB pre-buncher phasing Wide window
$y1=(($AQWindow2-($AQWindow2*(0.5/200))-$ymin[$imagenum])*$yscale[$imagenum]);
$y2=(($AQWindow2+($AQWindow2*(0.5/200))-$ymin[$imagenum])*$yscale[$imagenum]);
$y1=$plotheight-$plotmarginbottom-$y1;
$y2=$plotheight-$plotmarginbottom-$y2;
imagefilledrectangle($img[$imagenum],$leftx+1,$y1,$rightx-1,$y2,$color[13]);
	  
// RFQ pre-buncher phasing Narrow window
$x1=(($AQWindow1-($AQWindow1*(0.5/1000))-$xmin[$imagenum])*$xscale[$imagenum]);
$x2=(($AQWindow1+($AQWindow1*(0.5/1000))-$xmin[$imagenum])*$xscale[$imagenum]);
$x1=$x1+$plotmarginleft;
$x2=$x2+$plotmarginleft;
imageline($img[$imagenum],$x1, $topy, $x1, $bottomy, $color[10]); // Upper 1st A/q
imageline($img[$imagenum],$x2, $topy, $x2, $bottomy, $color[10]); // Lower 1st A/q 
// DSB pre-buncher phasing Narrow window
$y1=(($AQWindow2-($AQWindow2*(0.5/400))-$ymin[$imagenum])*$yscale[$imagenum]);
$y2=(($AQWindow2+($AQWindow2*(0.5/400))-$ymin[$imagenum])*$yscale[$imagenum]);
$y1=$plotheight-$plotmarginbottom-$y1;
$y2=$plotheight-$plotmarginbottom-$y2;
imageline($img[$imagenum], $leftx, $y1, $rightx,$y1, $color[10]); // Upper 2nd A/q
imageline($img[$imagenum], $rightx,$y2, $leftx, $y2, $color[10]); // Lower 2nd A/q 


	      // Plot the A/q data for the species of interest
	       	$x1=((($beammass-$Q*$emass)/$Q)-$xmin[$imagenum])*$xscale[$imagenum];
		$x1=$x1+$plotmarginleft;
		$y1=($AQvalue-$ymin[$imagenum])*$yscale[$imagenum];
		$y1=$plotheight-$plotmarginbottom-$y1;
		if($x1>$plotmarginleft && $x1<($plotwidth-$plotmarginother-$plotlegendmargin)&& $y1<($plotheight-$plotmarginbottom) && $y1>$plotmarginother){
                $legendname=sprintf("%d%s%d",$A,$Element[$Z],$i);
		imagestring($img[$imagenum], 5, $x1+20, $y1+6, $legendname, $color[10]);
		imagefilledellipse($img[$imagenum],$x1,$y1,30,30,$color[10]);
                 }

	  
	  $num=0;
	for($j=0; $j<sizeof($cocktail2Z); $j++) // Loop through all stable isotopes
	  {
	    
	    $pass_filter1=0;
	    $thismass=getthismass($cocktail2A[$j],$cocktail2Z[$j]);

	  // Start of first A/q test
	    for($k=1; $k<$cocktail2Z[$j]; $k++) // Loop through all charge states of this element
	      {
		if(((($thismass-$k*$emass)/$k)>((($beammass-$Q*$emass)/$Q)-((($beammass-$Q*$emass)/$Q)*(0.5/$Resolve)))) &&((($thismass-$k*$emass)/$k)<((($beammass-$Q*$emass)/$Q)+((($beammass-$Q*$emass)/$Q)*(0.5/$Resolve)))))
		  {
		    $pass_filter1=1;
		  }
	      }
	  // End of first A/q test

	  // Start of loop for 2nd filter test
	  for($k=1; $k<$cocktail2Z[$j]; $k++) // Loop through all charge states of this element
	  {
	   if($EnergyLoss>0.0){
	   $thisAQvalue=((($thismass-$k*$emass)/$k) *(sqrt(1.0-($EnergyLoss/100))));
	   }
	   else{
	   $thisAQvalue=(($thismass-$k*$emass)/$k);
	   }
     	   if(($thisAQvalue>($AQvalue-($AQvalue*(0.5/25)))) &&($thisAQvalue<($AQvalue+($AQvalue*(0.5/25)))))
	           {
		    if($pass_filter1==1)
	             { // Check first filter
	  
		        $jj=0;
		       for($ii=0; $ii<sizeof($BadZ[$Liner]); $ii++) // Check if onthe bad list
		         {
		     	  if($cocktail2Z[$j]==$BadZ[$Liner][$ii])
			   {
			    echo "<font color='#FF0000'>"; $jj=1; $num++; 

	      // Plot the A/q data - Contaminants
	       	$x1=(((($thismass-$k*$emass)/$cocktail2Q[$j]))-$xmin[$imagenum])*$xscale[$imagenum];
		$x1=$x1+$plotmarginleft;
		$y1=($thisAQvalue-$ymin[$imagenum])*$yscale[$imagenum];
		$y1=$plotheight-$plotmarginbottom-$y1;
		if($x1>$plotmarginleft && $x1<($plotwidth-$plotmarginother-$plotlegendmargin) && $y1<($plotheight-$plotmarginbottom) && $y1>$plotmarginother){
	        imagefilledellipse($img[$imagenum],$x1,$y1,30,30,$color[0]);
		// Draw label
		$legendname=sprintf("%d%s",$cocktail2A[$j],$Element[$cocktail2Z[$j]]);
		imagestring($img[$imagenum], 5, $x1+20, $y1-12, $legendname, $color[12]);
	              }

                           break;
	                  }
		      } // End of bad list loop

		    echo "<SUP>"; echo $cocktail2A[$j]; echo "</SUP>"; echo $Element[$cocktail2Z[$j]];
		    echo "<SUP>"; echo $k; echo "+</SUP>="; printf("%.3f",$thisAQvalue); echo "  ";
		    if($jj==1){echo "</font>"; }

                } // End of if pass_filter


		  }// end of if 2nd filter
	      }// end of loop through charge states
	  }
	
	
$imagenum=$i;
//DRAW BORDER AROUND GRAPH
    imageline($img[$imagenum], $leftx, $topy, $rightx, $topy, $color[12]);
    imageline($img[$imagenum], $rightx, $topy, $rightx, $bottomy, $color[12]);
    imageline($img[$imagenum], $rightx, $bottomy, $leftx, $bottomy, $color[12]);
    imageline($img[$imagenum], $leftx, $bottomy, $leftx, $topy, $color[12]);

    
//DRAW Y AXIS TICKS AND LABELS
    imagestringup($img[$imagenum], 5, $leftx-50, 200, "2nd A/q (DSB-SEBT)", $color[12]);
    $ii=0;
    $y1=0;
    $yunit=0.02;
    while($ii*$yunit<=1){
      $efory1=round($ymin[$imagenum]+$yunit*$ii,3);
      // $efory1=round($efory1,1);
      $y1=$yunit*$ii*$yscale[$imagenum];
      // $y1=round($y1);
      $y1=$bottomy-$y1;
      if($y1>($plotheight-$plotmarginbottom) || $y1<$plotmarginother){$ii++; continue;}
      imageline($img[$imagenum], $leftx, $y1, $leftx+5, $y1, $color[12]);
      imageline($img[$imagenum], $rightx, $y1, $rightx-5, $y1, $color[12]);
      if($ii%2==0){imagestring($img[$imagenum], 5, $leftx-35, $y1-8, $efory1, $color[12]);}
      $ii++;
    }
//DRAW X AXIS TICKS AND LABELS
    $xlabelx=round($plotwidth/2-50);
    imagestring($img[$imagenum], 5, $xlabelx, $plotheight-35, "1st A/q (CSB-DSB)", $color[12]);
    $ii=0;
    $x1=0;
    $xunit=0.01;
  while($x1<($plotwidth-$plotmarginother-$plotlegendmargin)){
    $forx1=round($xmin[$imagenum]+($xunit*$ii),3);
    // $forx1=sprintf("%4.2f",$xmin[$imagenum]+$xunit*$ii);
      $x1=($xunit*$ii)*$xscale[$imagenum];
      $x1=$x1+$plotmarginleft;
      if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother-$plotlegendmargin)){$ii++; continue;}
      imageline($img[$imagenum], $x1, $bottomy, $x1, $bottomy-5, $color[12]);
      imageline($img[$imagenum], $x1, $topy, $x1, $topy+5, $color[12]);
      if($ii%2==0){imagestring($img[$imagenum], 5, $x1-15, $bottomy+5, $forx1, $color[12]);}
      $ii++;
    }


	echo "</div><div class=\"c2\">";
    imagepng($img[$imagenum], $plotname[$imagenum]);
     imagedestroy($img[$imagenum]);
    echo "<p>\n";
    echo "<a href=\"./";
    echo $plotname[$imagenum];
    echo "\" target=\"_top\"> <img src=\"";
    echo $plotname[$imagenum];
    echo "\" alt=\"A/q Cocktail\" width=\"";
    echo 200;
    echo "\"></a>\n";
    echo "</p>\n";

    echo "</div><div class=\"buff\"></div><BR>\n";

      }// End of species of interest charge state loop
    echo "<BR><BR><BR>";
  }
  // End of submit 69
  
  if($_POST['submit2'] && $AQ>0){
    echo "<BR>\n";
    echo "<BR>\n";
    echo "<div class=\"buff\"></div>";
    echo "This list includes any stable species with an A/Q value within +/- 0.5% (1/100 resolving power of magnet) of the species of interest entered above, ";
    printf("%.3f",$AQ);
    echo ". Obviously not all of these stable species will be present and the amount of each species will depend on the operating conditions of the CSB (temperature/pressure etc.) as well as the recent CSB history (i.e. isotopes recently injected into the device).<BR>\n";
    echo "<BR>\n";
    echo "<BR>\n";
    echo "</div><div class=\"t3\">\n";
    for($j=0; $j<sizeof($StableZ); $j++){
      $thismass=getthismass($StableA[$j],$StableZ[$j]);
      for($k=1; $k<$StableZ[$j]; $k++){
	if(((($thismass-$k*$emass)/$k)>($AQ-(($AQ)*(0.5/$Resolve)))) && ((($thismass-$k*$emass)/$k)<($AQ+(($AQ)*(0.5/$Resolve))))){
	  $jj=0;
	  for($ii=0; $ii<sizeof($BadZ[$Liner]); $ii++){if($StableZ[$j]==$BadZ[$Liner][$ii]){echo "<font color='#FF0000'>"; $jj=1; break;}}
	  echo "<SUP>"; echo $StableA[$j]; echo "</SUP>"; echo $Element[$StableZ[$j]];
          echo "<SUP>"; echo $k; echo "+</SUP>="; printf("%.3f",(($thismass-$k*$emass)/$k)); echo "   ";
	  if($jj==1){echo "</font>"; }
	}
      }
    }
  echo "</div><BR>\n";
  
  }


  if($_POST['submit4'] && $Z>0){

while( sizeof( $cocktailA ) > 0 ) array_pop( $cocktailA );
while( sizeof( $cocktailZ ) > 0 ) array_pop( $cocktailZ );
while( sizeof( $cocktailQ ) > 0 ) array_pop( $cocktailQ );
  $cocktailA = unserialize(base64_decode($_POST["cocktailA"]));
  $cocktailZ = unserialize(base64_decode($_POST["cocktailZ"]));
  $cocktailQ = unserialize(base64_decode($_POST["cocktailQ"]));
  
  echo " <form method=post name=\"CSBframeform\" action=\"CSB.php\" target=\"ISACFrame\">\n";
    echo "<input type=\"hidden\" name=\"Mass\" value=\"";
    echo $A;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Zvalue\" value=\"";
    echo $Z;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Charge\" value=\"";
    echo $Q;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Liner_material\" value=\"";
    echo $Liner;
      echo "\">";
    echo "<input type=\"hidden\"name=\"resolving_power\" value=\"";
    echo $Resolve;
      echo "\">";
  echo '<input type="hidden" name="cocktailA" value="', base64_encode(serialize($cocktailA)),'">';
  echo '<input type="hidden" name="cocktailZ" value="', base64_encode(serialize($cocktailZ)),'">';
  echo '<input type="hidden" name="cocktailQ" value="', base64_encode(serialize($cocktailQ)),'">';
  echo " </form>\n\n";
  
  echo " <form method=post name=\"RFQframeform\" action=\"RFQ.php\" target=\"ISACFrame\">\n";
    echo "<input type=\"hidden\" name=\"Mass\" value=\"";
    echo $A;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Zvalue\" value=\"";
    echo $Z;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Charge\" value=\"";
    echo $Q;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Liner_material\" value=\"";
    echo $Liner;
      echo "\">";
    echo "<input type=\"hidden\"name=\"resolving_power\" value=\"";
    echo $Resolve;
      echo "\">";
  echo '<input type="hidden" name="cocktailA" value="', base64_encode(serialize($cocktailA)),'">';
  echo '<input type="hidden" name="cocktailZ" value="', base64_encode(serialize($cocktailZ)),'">';
  echo '<input type="hidden" name="cocktailQ" value="', base64_encode(serialize($cocktailQ)),'">';
    echo "<input type=\"hidden\" name=\"submit7\" value=\"1\" >";
  echo " </form>\n\n";

  echo " <form method=post name=\"DSBframeform\" action=\"DSB.php\" target=\"ISACFrame\">\n";
    echo "<input type=\"hidden\" name=\"Mass\" value=\"";
    echo $A;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Zvalue\" value=\"";
    echo $Z;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Charge\" value=\"";
    echo $Q;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Liner_material\" value=\"";
    echo $Liner;
      echo "\">";
    echo "<input type=\"hidden\"name=\"resolving_power\" value=\"";
    echo $Resolve;
      echo "\">";
  echo '<input type="hidden" name="cocktailA" value="', base64_encode(serialize($cocktailA)),'">';
  echo '<input type="hidden" name="cocktailZ" value="', base64_encode(serialize($cocktailZ)),'">';
  echo '<input type="hidden" name="cocktailQ" value="', base64_encode(serialize($cocktailQ)),'">';
  echo " </form>\n\n";
  
  echo " <form method=post name=\"SEBTframeform\" action=\"SEBT.php\" target=\"ISACFrame\">\n";
    echo "<input type=\"hidden\" name=\"Mass\" value=\"";
    echo $A;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Zvalue\" value=\"";
    echo $Z;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Charge\" value=\"";
    echo $Q;
      echo "\">";
    echo "<input type=\"hidden\"name=\"Liner_material\" value=\"";
    echo $Liner;
      echo "\">";
    echo "<input type=\"hidden\"name=\"resolving_power\" value=\"";
    echo $Resolve;
      echo "\">";
  echo '<input type="hidden" name="cocktailA" value="', base64_encode(serialize($cocktailA)),'">';
  echo '<input type="hidden" name="cocktailZ" value="', base64_encode(serialize($cocktailZ)),'">';
  echo '<input type="hidden" name="cocktailQ" value="', base64_encode(serialize($cocktailQ)),'">';
    echo "<input type=\"hidden\" name=\"submit5\" value=\"1\" >";
  
  echo " </form>\n\n";
  
  
    echo "<BR>\n";
    echo "<BR>\n";
    echo "<div class=\"buff\"></div>\n";
    echo "<TABLE WIDTH=100%>\n";
    echo "<TR>";
    echo "<TD WIDTH=50%>";
    echo "Select a region on the map of ISAC:<BR>";
    echo "<img src=\"./ISAC_Schematic.jpg\" alt=\"ISAC\" usemap=\"ISACmap\" />";
    echo "<map name=\"ISACmap\">";
    echo "<area shape=\"rect\" coords=\"28,176,76,194\" onclick=\"document.CSBframeform.submit();\" alt=\"CSB\" />";
    echo "<area shape=\"poly\" coords=\"2,140,2,181,24,181,24,152,47,140,2,140\" onclick=\"document.RFQframeform.submit();\" alt=\"RFQ\" />";
    echo "<area shape=\"rect\" coords=\"46,54,99,97\" onclick=\"document.DSBframeform.submit();\" alt=\"DSB\" />";
    echo "<area shape=\"rect\" coords=\"140,48,211,76\" onclick=\"document.SEBTframeform.submit();\" alt=\"SEBT\" />";
    echo "</map><BR>";
     echo "<input type=\"button\" value=\"CSB\" onclick=\"document.CSBframeform.submit();\" />";
     echo "<input type=\"button\" value=\"RFQ\" onclick=\"document.RFQframeform.submit();\" />";
     echo "<input type=\"button\" value=\"DSB\" onclick=\"document.DSBframeform.submit();\" />";
     echo "<input type=\"button\" value=\"SEBT\" onclick=\"document.SEBTframeform.submit();\" />";
    echo "</TD>";
    echo "<TD WIDTH=50%>";
    echo "<iframe name=\"ChartFrame\" src=\"chart.php\" align=center width=100% height=320 frameborder=0>";
    echo "<p>Your browser does not support iframes!</p>";
    echo "</iframe>";
    echo "</TD>\n";
    echo "</TR>\n";  
    echo "</TABLE>\n";
  
    echo "<iframe name=\"ISACFrame\" src=\"default.htm\" align=center width=100% height=6000 frameborder=0>";
    echo "<p>Your browser does not support iframes!</p>";
    echo "</iframe>";
    
  }




     ?>

</div>
</body>
</html>
