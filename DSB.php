
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>DSB</title>
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
DSB was selected.

<?php
include('Experts.php');

// Get post data
$A=$_POST["Mass"];
$Z=$_POST["Zvalue"];
$Liner=$_POST["Liner_material"];
$Resolve=$_POST["resolving_power"];
$Q=$_POST["Charge"];
$cocktailA = unserialize(base64_decode($_POST["cocktailA"]));
$cocktailZ = unserialize(base64_decode($_POST["cocktailZ"]));
$cocktailQ = unserialize(base64_decode($_POST["cocktailQ"]));

  if($_POST["submit5"] && $Z>0){

    echo "<div class=\"buff\"></div>";
    echo $Element[$Z];
    echo " has an atomic number: ";
    echo $Z;
    echo "<BR>\n";
    echo "This page is for <SUP>";
    echo $A;
    echo "</SUP>";
    echo $Element[$Z];
    echo " in a ";
    echo $Q;
    echo "+ charge state. The values shown are for stripping at ";
    $BeamEnergy=5.0;
    if(isset($_POST["BEnergy"])){$BeamEnergy=$_POST["BEnergy"];}
    echo "<form action=\"DSB.php\" target=\"ISACFrame\" method=\"post\">";
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
      echo "<input type=\"text\" name=\"BEnergy\" value=\"";
      if($BeamEnergy>0){printf("%.1f",$BeamEnergy);}
    echo "\" style=\"width:50px;\">";
    echo " MeV/u. ";
    echo "<input type=\"submit\" name=\"submit5\" value=\"Recalculate\" ></form>";
    echo "<BR>This requires a carbon foil with a thickness greater than \n";
    $thickness=5.9+(22.4*$BeamEnergy)-(1.13*(($BeamEnergy)*($BeamEnergy)));
    echo $thickness;
    echo " microgram/cm<sup>2</sup> to reach the equilibrium charge state.<BR><BR>\n";
    echo "The minimum foil thickness is calculated using the equation given by G.C. Ball et al., Nucl. Instrum. Methods Res. B48, 125 (1990).<BR>";
    echo "The mean charge state is calculated using the equation given by V.S. Nikolaev and I.S. Dmitriev, Phys. Lett. A28, 277 (1968).<BR>";
    echo "The charge-state fraction distributions are calculated using the equations given by Y. Baudinet-Robinet, Nucl. Instrum. Methods 190, 197 (1981).<BR>";
    echo "<div class=\"buff\"></div>";
    echo "<div class=\"t2\"><b>Species</b></div>\n";
    echo "<div class=\"t3\"><b>Charge State</b></div>\n";
    echo "<div class=\"t3\"><b>A/Q Value</b></div>\n";
    echo "<div class=\"t4\"><b>Equilibrium<BR>charge state</b></div>\n";
    echo "<div class=\"t4\"><b>A/Q at q<BR>lower(q)</b></div>\n";
    echo "<div class=\"t4\"><b>A/Q at q<BR>upper(q)</b></div>\n";
    echo "<BR><BR><BR><BR>\n";
    echo "<div class=\"t2\">\n";
    echo "<SUP>"; echo $A; echo "</SUP>"; echo $Element[$Z];
    echo "</div><div class=\"t3\">\n";
    echo $Q;
    echo "</div><div class=\"t3\">\n";
    printf("%.3f",($A/$Q));
    echo "</div><div class=\"t4\">\n";

      // Here we calculate meanQ (equilibrium charge state) by method of Nikolaev and Dmitriev
      // X is an input to the meanQ equation.
    $X=3.86*sqrt(($BeamEnergy*$A)/$A/(pow($Z,0.45)));
    $meanQ=$Z*pow((1+pow($X,(-1/0.6))),-0.6);
      // s is the distribution width, Nikolaev and Dmitriev
    $s=0.5*pow(($meanQ*(1-pow(($meanQ/$Z),(1/0.6)))),0.5); // for Z>20
      // c and nu are factors which go into the determination of the charge state distribution - Shima et al.
    $c=(2*($Z-$meanQ+2))/($s*$s);
    $nu=(2*($Z-$meanQ+2)*($Z-$meanQ+2))/($s*$s);

   // Define the colors
    $plotcaption[1]="Calculated Charge-State Distributions by A/q:<br>\n";
    $plotname[1]="Charge_State_Distributions_Aq.png"; 
    $plotcaption[2]="Calculated Charge-State Distributions by Charge State:<br>\n";
    $plotname[2]="Charge_State_Distributions_CS.png"; 

    $imagenum=1;
// Y WILL BE Fraction, X WILL BE charge state
// SET MINIMUM Y and X
    $ymin[$imagenum]=0.0;
    $xmin[$imagenum]=round($A/$meanQ,2)-0.5;

// SET MAXIMUM Y and X
    $xmax[$imagenum]=round($A/$meanQ,2)+0.5;
    $ymax[$imagenum]=0.6;

// THIS TREATMENT OF X WORKS FOR DEGREES BUT NOT RADIANS!!!
    if($xmax[$imagenum]-round($xmax[$imagenum]) < 0.01){$xmax[$imagenum]=round($xmax[$imagenum]);}
    if($xmax[$imagenum]-round($xmax[$imagenum]) >= 0.01){$xmax[$imagenum]=floor($xmax[$imagenum]+1);}
// if($ymax[$imagenum]-round($ymax[$imagenum]) < 0.01){$ymax[$imagenum]=round($ymax[$imagenum]);}
    if($ymax[$imagenum]-round($ymax[$imagenum]) >= 0.01){$ymax[$imagenum]=floor($ymax[$imagenum]+1);}

// SCALE PARAMETERS FOR GRAPH
    $plotwidth=800;
    $plotheight=round($plotwidth*3/4);
    $plotmarginleft=50;
    $plotmarginbottom=60;
    $plotmarginother=20;
    $plotlegendmargin=60;
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
    imagefilledrectangle($img[$imagenum], 0, 0, $plotwidth-1, $plotheight-1, $color[11]);

    // LEGEND Parameters
    $legendx=$plotwidth-60;
    $legendy=$plotmarginother+20;

    $imagenum=2;
// Y WILL BE Fraction, X WILL BE charge state
// SET MINIMUM Y and X
    $ymin[$imagenum]=0.0;
    $xmin[$imagenum]=floor($Z/2);

// SET MAXIMUM Y and X
    $xmax[$imagenum]=floor($Z+2);
    $ymax[$imagenum]=0.6;
// THIS TREATMENT OF X WORKS FOR DEGREES BUT NOT RADIANS!!!
    if($xmax[$imagenum]-round($xmax[$imagenum]) < 0.01){$xmax[$imagenum]=round($xmax[$imagenum]);}
    if($xmax[$imagenum]-round($xmax[$imagenum]) >= 0.01){$xmax[$imagenum]=floor($xmax[$imagenum]+1);}
// if($ymax[$imagenum]-round($ymax[$imagenum]) < 0.01){$ymax[$imagenum]=round($ymax[$imagenum]);}
    if($ymax[$imagenum]-round($ymax[$imagenum]) >= 0.01){$ymax[$imagenum]=floor($ymax[$imagenum]+1);}

// SCALE PARAMETERS FOR GRAPH
    $plotwidth=800;
    $plotheight=round($plotwidth*3/4);
    $plotmarginleft=50;
    $plotmarginbottom=60;
    $plotmarginother=20;
    $plotlegendmargin=60;
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
    imagefilledrectangle($img[$imagenum], 0, 0, $plotwidth-1, $plotheight-1, $color[11]);

    // Plot the A/q data
    $imagenum=1;
    for($i=floor($Z/2); $i<=$Z+2; $i++){
    $beammass=getthismass($A,$Z);
      $t=$c*($Z-$i+2);
      $F=frac($nu,$t,$c);
      // $x1=($i-$xmin[$imagenum])*$xscale[$imagenum];
      $x1=((($beammass-$i*$emass)/$i)-$xmin[$imagenum])*$xscale[$imagenum];
      $x1=round($x1);
      $x1=$x1+$plotmarginleft;
      $y1=($F-$ymin[$imagenum])*$yscale[$imagenum];
      $y1=round($y1);
      $y1=$plotheight-$plotmarginbottom-$y1;
      if($i>(1+$Z/2) && $i>0){
	$t=$c*($Z-($i-1)+2);
	$F=frac($nu,$t,$c);
        //$x2=(($i-1)-$xmin[$imagenum])*$xscale[$imagenum];
	$x2=((($beammass-($i-1)*$emass)/($i-1))-$xmin[$imagenum])*$xscale[$imagenum];
	$x2=round($x2);
	$x2=$x2+$plotmarginleft;
	$y2=($F-$ymin[$imagenum])*$yscale[$imagenum];
	$y2=round($y2);
	$y2=$plotheight-$plotmarginbottom-$y2;
      }
      else{
	$x2=$x1;
	$y2=$y1;
      }
      if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother-$plotlegendmargin)){continue;}
      if($x2<$plotmarginleft || $x2>($plotwidth-$plotmarginother-$plotlegendmargin)){continue;}
      if($y2>($plotheight-$plotmarginbottom) || $y2<$plotmarginother){continue;}
      //imagefilledrectangle($img[$imagenum],$x1-3,$y1-3,$x1+3,$y1+3,$color[10]);
      imagefilledellipse($img[$imagenum],$x1,$y1,10,10,$color[10]);
      // imageline($img[$imagenum],$x2,$y2,$x1,$y1,$color[10]);
      imagethickline($img[$imagenum],$x2,$y2,$x1,$y1,$color[10],3);
      // Draw legend
      imagefilledellipse($img[$imagenum],$legendx,$legendy,10,10,$color[10]);
      imagethickline($img[$imagenum],$legendx-10,$legendy,$legendx+10,$legendy,$color[10],3);
      $legendname=sprintf("%d%s",$A,$Element[$Z]);
      imagestring($img[$imagenum], 5, $legendx+15, $legendy-7, $legendname, $color[12]);
    }

    // Plot the Charge State data
    $imagenum=2;
    for($i=floor($Z/2); $i<=$Z+2; $i++){
      $beammass=getthismass($A,$Z);
      $t=$c*($Z-$i+2);
      $F=frac($nu,$t,$c);
      $x1=($i-$xmin[$imagenum])*$xscale[$imagenum];
      //  $x1=((($beammass-$i*$emass)/$i)-$xmin[$imagenum])*$xscale[$imagenum];
      $x1=round($x1);
      $x1=$x1+$plotmarginleft;
      $y1=($F-$ymin[$imagenum])*$yscale[$imagenum];
      $y1=round($y1);
      $y1=$plotheight-$plotmarginbottom-$y1;
      if($i>(1+$Z/2) && $i>0){
	$t=$c*($Z-($i-1)+2);
	$F=frac($nu,$t,$c);
        $x2=(($i-1)-$xmin[$imagenum])*$xscale[$imagenum];
	//$x2=((($beammass-($i-1)*$emass)/($i-1))-$xmin[$imagenum])*$xscale[$imagenum];
	$x2=round($x2);
	$x2=$x2+$plotmarginleft;
	$y2=($F-$ymin[$imagenum])*$yscale[$imagenum];
	$y2=round($y2);
	$y2=$plotheight-$plotmarginbottom-$y2;
      }
      else{
	$x2=$x1;
	$y2=$y1;
      }
      if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother-$plotlegendmargin)){continue;}
      if($x2<$plotmarginleft || $x2>($plotwidth-$plotmarginother-$plotlegendmargin)){continue;}
      if($y2>($plotheight-$plotmarginbottom) || $y2<$plotmarginother){continue;}
      // imagefilledrectangle($img[$imagenum],$x1-3,$y1-3,$x1+3,$y1+3,$color[10]);
      imagefilledellipse($img[$imagenum],$x1,$y1,10,10,$color[10]);
      // imageline($img[$imagenum],$x2,$y2,$x1,$y1,$color[10]);
      imagethickline($img[$imagenum],$x2,$y2,$x1,$y1,$color[10],3);
      // Draw legend
      imagefilledellipse($img[$imagenum],$legendx,$legendy,10,10,$color[10]);
      imagethickline($img[$imagenum],$legendx-10,$legendy,$legendx+10,$legendy,$color[10],3);
      $legendname=sprintf("%d%s",$A,$Element[$Z]);
      imagestring($img[$imagenum], 5, $legendx+15, $legendy-7, $legendname, $color[12]);
    }
    $colornum=0;

    $beammass=getthismass($A,$Z);
    printf("%.1f",$meanQ);
    echo "</div><div class=\"t4\">\n";
    printf("(%d) %.3f",floor($meanQ),(($beammass-floor($meanQ)*$emass)/floor($meanQ)));
    echo "</div><div class=\"t4\">\n";
    printf("(%d) %.3f",ceil($meanQ),(($beammass-ceil($meanQ)*$emass)/ceil($meanQ)));
    echo "</div><div class=\"buff\"></div>";

    for($j=0; $j<sizeof($cocktailA); $j++){
      for($k=1; $k<$cocktailZ[$j]; $k++){
	if($cocktailA[$j]==$A && $cocktailZ[$j]==$Z){continue;}
        $thismass=getthismass($cocktailA[$j],$cocktailZ[$j]);
	if(((($thismass-$k*$emass)/$k)>(($beammass-$Q*$emass)/$Q-((($beammass-$Q*$emass)/$Q)*(0.5/$Resolve)))) && ((($thismass-$k*$emass)/$k)<(($beammass-$Q*$emass)/$Q+((($beammass-$Q*$emass)/$Q)*(0.5/$Resolve))))){
	  for($ii=0; $ii<sizeof($BadZ[$Liner]); $ii++){
	    if($cocktailZ[$j]==$BadZ[$Liner][$ii]){
	      echo "<div class=\"t2\">\n";
	      echo "<SUP>"; echo $cocktailA[$j]; echo "</SUP>"; echo $Element[$cocktailZ[$j]];
	      echo "</div><div class=\"t3\">\n";
	      echo $k;
	      echo "</div><div class=\"t3\">\n";
	      printf("%.3f",(($thismass-$k*$emass)/$k));
	      echo "</div><div class=\"t4\">\n";
	      $X=3.86*sqrt(($BeamEnergy*$cocktailA[$j])/$cocktailA[$j]/(pow($cocktailZ[$j],0.45)));
	      $meanQ=$cocktailZ[$j]*pow((1+pow($X,(-1/0.6))),-0.6);
	      printf("%.1f",$meanQ);
	      echo "</div><div class=\"t4\">\n";
	      printf("(%d) %.3f",floor($meanQ),(($thismass-floor($meanQ)*$emass)/floor($meanQ)));
	      echo "</div><div class=\"t4\">\n";
	      printf("(%d) %.3f",ceil($meanQ),(($thismass-ceil($meanQ)*$emass)/ceil($meanQ)));


	      // Plot the A/q data - Got to here correct
	      $imagenum=1;
	      $s=0.5*pow(($meanQ*(1-pow(($meanQ/$cocktailZ[$j]),(1/0.6)))),0.5); // for Z>20
	      $c=(2*($cocktailZ[$j]-$meanQ+2))/($s*$s);
	      $nu=(2*($cocktailZ[$j]-$meanQ+2)*($cocktailZ[$j]-$meanQ+2))/($s*$s);
	      for($i=floor($cocktailZ[$j]/2); $i<=$cocktailZ[$j]+2; $i++){
		$t=$c*($cocktailZ[$j]-$i+2);
		$F=frac($nu,$t,$c);
	       	$x1=(($cocktailA[$j]/$i)-$xmin[$imagenum])*$xscale[$imagenum];
		$x1=round($x1);
		$x1=$x1+$plotmarginleft;
		$y1=($F-$ymin[$imagenum])*$yscale[$imagenum];
		$y1=round($y1);
		$y1=$plotheight-$plotmarginbottom-$y1;
		if($i>(1+$cocktailZ[$j]/2) && $i>0){
		  $t=$c*($cocktailZ[$j]-($i-1)+2);
		  $F=frac($nu,$t,$c);
		  $x2=(($cocktailA[$j]/($i-1))-$xmin[$imagenum])*$xscale[$imagenum];
		  $x2=round($x2);
		  $x2=$x2+$plotmarginleft;
		  $y2=($F-$ymin[$imagenum])*$yscale[$imagenum];
		  $y2=round($y2);
		  $y2=$plotheight-$plotmarginbottom-$y2;
		}
		else{
		  $x2=$x1;
		  $y2=$y1;
		}
		if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother-$plotlegendmargin)){continue;}
		if($x2<$plotmarginleft || $x2>($plotwidth-$plotmarginother-$plotlegendmargin)){continue;}
		if($y2>($plotheight-$plotmarginbottom) || $y2<$plotmarginother){continue;}
		if(floor($colornum/5)==0){imagefilledellipse($img[$imagenum],$x1,$y1,7,7,$color[$colornum%5]);}
	        if(floor($colornum/5)==1){imagefilledrectangle($img[$imagenum],$x1-3,$y1-3,$x1+3,$y1+3,$color[$colornum%5]);}
		if(floor($colornum/5)==2){imageellipse($img[$imagenum],$x1,$y1,7,7,$color[$colornum%5]);}
		imageline($img[$imagenum],$x2,$y2,$x1,$y1,$color[$colornum%5]);
		// Draw legend
		if(floor($colornum/5)==0){imagefilledellipse($img[$imagenum],$legendx,$legendy+(($colornum+1)*30),7,7,$color[$colornum%5]);}
		if(floor($colornum/5)==1){imagefilledrectangle($img[$imagenum],$legendx-3,$legendy+(($colornum+1)*30)-3,$legendx+3,$legendy+(($colornum+1)*30)+3,$color[$colornum%5]);}
		if(floor($colornum/5)==2){imageellipse($img[$imagenum],$legendx,$legendy+(($colornum+1)*30),7,7,$color[$colornum%5]);}
		imageline($img[$imagenum],$legendx-10,$legendy+(($colornum+1)*30),$legendx+10,$legendy+(($colornum+1)*30),$color[$colornum%5]);
		$legendname=sprintf("%d%s",$cocktailA[$j],$Element[$cocktailZ[$j]]);
		imagestring($img[$imagenum], 5, $legendx+15, $legendy+(($colornum+1)*30)-7, $legendname, $color[12]);
	      }

	      // Plot the Charge State data
		$imagenum=2;
	      $s=0.5*pow(($meanQ*(1-pow(($meanQ/$cocktailZ[$j]),(1/0.6)))),0.5); // for Z>20
	      $c=(2*($cocktailZ[$j]-$meanQ+2))/($s*$s); // Happy with c
	      $nu=(2*($cocktailZ[$j]-$meanQ+2)*($cocktailZ[$j]-$meanQ+2))/($s*$s);
	      for($i=floor($cocktailZ[$j]/2); $i<=$cocktailZ[$j]+2; $i++){
		$t=$c*($cocktailZ[$j]-$i+2);
		$F=frac($nu,$t,$c);
		$x1=($i-$xmin[$imagenum])*$xscale[$imagenum];
		$x1=round($x1);
		$x1=$x1+$plotmarginleft;
		$y1=($F-$ymin[$imagenum])*$yscale[$imagenum];
		$y1=round($y1);
		$y1=$plotheight-$plotmarginbottom-$y1;
		if($i>(1+$cocktailZ[$j]/2) && $i>0){
		  $t=$c*($cocktailZ[$j]-($i-1)+2);
		  $F=frac($nu,$t,$c);
		  $x2=(($i-1)-$xmin[$imagenum])*$xscale[$imagenum];
		  $x2=round($x2);
		  $x2=$x2+$plotmarginleft;
		  $y2=($F-$ymin[$imagenum])*$yscale[$imagenum];
		  $y2=round($y2);
		  $y2=$plotheight-$plotmarginbottom-$y2;
		}
		else{
		  $x2=$x1;
		  $y2=$y1;
		}
		if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother-$plotlegendmargin)){continue;}
		if($x2<$plotmarginleft || $x2>($plotwidth-$plotmarginother-$plotlegendmargin)){continue;}
		if($y2>($plotheight-$plotmarginbottom) || $y2<$plotmarginother){continue;}
		if(floor($colornum/5)==0){imagefilledellipse($img[$imagenum],$x1,$y1,7,7,$color[$colornum%5]);}
	        if(floor($colornum/5)==1){imagefilledrectangle($img[$imagenum],$x1-3,$y1-3,$x1+3,$y1+3,$color[$colornum%5]);}
		if(floor($colornum/5)==2){imageellipse($img[$imagenum],$x1,$y1,7,7,$color[$colornum%5]);}
		imageline($img[$imagenum],$x2,$y2,$x1,$y1,$color[$colornum%5]);
		// Draw legend
		if(floor($colornum/5)==0){imagefilledellipse($img[$imagenum],$legendx,$legendy+(($colornum+1)*30),7,7,$color[$colornum%5]);}
		if(floor($colornum/5)==1){imagefilledrectangle($img[$imagenum],$legendx-3,$legendy+(($colornum+1)*30)-3,$legendx+3,$legendy+(($colornum+1)*30)+3,$color[$colornum%5]);}
		if(floor($colornum/5)==2){imageellipse($img[$imagenum],$legendx,$legendy+(($colornum+1)*30),7,7,$color[$colornum%5]);}
		imageline($img[$imagenum],$legendx-10,$legendy+(($colornum+1)*30),$legendx+10,$legendy+(($colornum+1)*30),$color[$colornum%5]);
		$legendname=sprintf("%d%s",$cocktailA[$j],$Element[$cocktailZ[$j]]);
		imagestring($img[$imagenum], 5, $legendx+15, $legendy+(($colornum+1)*30)-7, $legendname, $color[12]);
	      }
	      $colornum++;

	      echo "</div><div class=\"buff\"></div>"; break;
	    }
	  }
	}
      }
    }
  

    $X=3.86*sqrt(($BeamEnergy*$A)/$A/(pow($Z,0.45)));
    $meanQ=$Z*pow((1+pow($X,(-1/0.6))),-0.6);
    $s=0.5*pow(($meanQ*(1-pow(($meanQ/$Z),(1/0.6)))),0.5); // for Z>20
    $c=(2*($Z-$meanQ+2))/($s*$s);
    $nu=(2*($Z-$meanQ+2)*($Z-$meanQ+2))/($s*$s);

echo "<BR>";

echo "<BR>\n";
echo "<font color='#FF0000'>These charge-state fraction distribution equations are untrustworthy for Z<20. Lighter elements will probably be missing from the plots!</font><BR>\n";
echo "<BR>\n";
echo "<BR>Calculated charge-state fractions for the species of interest, \n";
printf("%d%s:<BR>",$A,$Element[$Z]);
echo "Here 1.0 is equal to 100% and 0.1 would correspond to 10% of beam incident on the stripping foil exiting in that charge state.<BR>\n";
    echo "<div class=\"t2\"><b>Charge State</b></div>\n";
    echo "<div class=\"t3\"><b>A/q</b></div>\n";
    echo "<div class=\"t4\"><b>Fraction</b></div>";
    echo "<BR><BR><BR>\n";
    for($i=1; $i<=$Z+2; $i++){
      $t=$c*($Z-$i+2);
      $F=frac($nu,$t,$c);
      if($F>0.001){
	echo "<div class=\"t2\">";
      echo $i;
      echo "</div><div class=\"t3\">";
      printf("%5.3f",($beammass-$i*$emass)/$i);
      echo "</div><div class=\"t4\">";
      printf("%5.3f",$F);
      echo "</div><BR>";
      }
    }

echo "<BR>\n";
echo "<BR>\n";

$imagenum=1;
//DRAW BORDER AROUND GRAPH
    $leftx=$plotmarginleft;
    $rightx=$plotwidth-$plotmarginother-$plotlegendmargin;
    $topy=$plotmarginother;
    $bottomy=$plotheight-$plotmarginbottom;
    imageline($img[$imagenum], $leftx, $topy, $rightx, $topy, $color[12]);
    imageline($img[$imagenum], $rightx, $topy, $rightx, $bottomy, $color[12]);
    imageline($img[$imagenum], $rightx, $bottomy, $leftx, $bottomy, $color[12]);
    imageline($img[$imagenum], $leftx, $bottomy, $leftx, $topy, $color[12]);
    
//DRAW Y AXIS TICKS AND LABELS
    imagestring($img[$imagenum], 5, $leftx-40, 0, "Charge-State Fraction", $color[12]);
    $i=0;
    $y1=0;
    $yunit=0.05;
    while($i*$yunit<=1){
      $efory1=$ymin[$imagenum]+$yunit*$i;
      // $efory1=round($efory1,1);
      $y1=$yunit*$i*$yscale[$imagenum];
      // $y1=round($y1);
      $y1=$bottomy-$y1;
      if($y1>($plotheight-$plotmarginbottom) || $y1<$plotmarginother){$i++; continue;}
      imageline($img[$imagenum], $leftx, $y1, $leftx+5, $y1, $color[12]);
      imageline($img[$imagenum], $rightx, $y1, $rightx-5, $y1, $color[12]);
      if($i%2==0){imagestring($img[$imagenum], 5, $leftx-40, $y1-8, $efory1, $color[12]);}
      $i++;
    }
//DRAW X AXIS TICKS AND LABELS
    $xlabelx=round($plotwidth/2-50);
    imagestring($img[$imagenum], 5, $xlabelx, $plotheight-35, "A/q", $color[12]);
    $i=0;
    $x1=0;
    $xunit=0.1;
while($x1<($plotwidth-$plotmarginother-$plotlegendmargin)){
  $forx1=round($xmin[$imagenum]+($xunit*$i),2);
  // $forx1=sprintf("%4.2f",$xmin[$imagenum]+$xunit*$i);
      $x1=($xunit*$i)*$xscale[$imagenum];
      $x1=$x1+$plotmarginleft;
      if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother-$plotlegendmargin)){$i++; continue;}
      imageline($img[$imagenum], $x1, $bottomy, $x1, $bottomy-5, $color[12]);
      imageline($img[$imagenum], $x1, $topy, $x1, $topy+5, $color[12]);
      if($i%2==0){imagestring($img[$imagenum], 5, $x1-15, $bottomy+5, $forx1, $color[12]);}
      $i++;
    }

    imagepng($img[$imagenum], $plotname[$imagenum]);
     imagedestroy($img[$imagenum]);
    echo $plotcaption[$imagenum];
    echo "<p>\n";
    echo "<a href=\"./";
    echo $plotname[$imagenum];
    echo "\" target=\"_top\"> <img src=\"";
    echo $plotname[$imagenum];
    echo "\" alt=\"Calculated Charge-State Distributions\" width=\"";
    echo 600;
    echo "\"></a>\n";
    echo "</p>\n";

$imagenum=2;
//DRAW BORDER AROUND GRAPH
    $leftx=$plotmarginleft;
    $rightx=$plotwidth-$plotmarginother-$plotlegendmargin;
    $topy=$plotmarginother;
    $bottomy=$plotheight-$plotmarginbottom;
    imageline($img[$imagenum], $leftx, $topy, $rightx, $topy, $color[12]);
    imageline($img[$imagenum], $rightx, $topy, $rightx, $bottomy, $color[12]);
    imageline($img[$imagenum], $rightx, $bottomy, $leftx, $bottomy, $color[12]);
    imageline($img[$imagenum], $leftx, $bottomy, $leftx, $topy, $color[12]);
    
//DRAW Y AXIS TICKS AND LABELS
    imagestring($img[$imagenum], 5, $leftx-35, 0, "Charge-State Fraction", $color[12]);
    $i=0;
    $y1=0;
    $yunit=0.05;
    while($i*$yunit<=1){
      $efory1=$ymin[$imagenum]+$yunit*$i;
      // $efory1=round($efory1,1);
      $y1=$yunit*$i*$yscale[$imagenum];
      // $y1=round($y1);
      $y1=$bottomy-$y1;
      if($y1>($plotheight-$plotmarginbottom) || $y1<$plotmarginother){$i++; continue;}
      imageline($img[$imagenum], $leftx, $y1, $leftx+5, $y1, $color[12]);
      imageline($img[$imagenum], $rightx, $y1, $rightx-5, $y1, $color[12]);
      if($i%2==0){imagestring($img[$imagenum], 5, $leftx-40, $y1-8, $efory1, $color[12]);}
      $i++;
    }
//DRAW X AXIS TICKS AND LABELS
    $xlabelx=round($plotwidth/2-50);
    imagestring($img[$imagenum], 5, $xlabelx, $plotheight-40, "Charge State", $color[12]);
    $i=0;
    $x1=0;
    while($x1<($plotwidth-$plotmarginother-$plotlegendmargin)){
      $forx1=$xmin[$imagenum]+$i;
      $x1=$i*$xscale[$imagenum];
      $x1=$x1+$plotmarginleft;
      if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother-$plotlegendmargin)){$i++; continue;}
      imageline($img[$imagenum], $x1, $bottomy, $x1, $bottomy-5, $color[12]);
      imageline($img[$imagenum], $x1, $topy, $x1, $topy+5, $color[12]);
      if($i%2==0){imagestring($img[$imagenum], 5, $x1-10, $bottomy+5, $forx1, $color[12]);}
      $i++;
    }

    imagepng($img[$imagenum], $plotname[$imagenum]);
     imagedestroy($img[$imagenum]);
    echo $plotcaption[$imagenum];
    echo "<p>\n";
    echo "<a href=\"./";
    echo $plotname[$imagenum];
    echo "\" target=\"_top\"> <img src=\"";
    echo $plotname[$imagenum];
    echo "\" alt=\"Calculated Charge-State Distributions\" width=\"";
    echo 600;
    echo "\"";
    echo "\"></a>\n";
    echo "</p>\n";


$Zupper=$cocktailZ[sizeof($cocktailZ)-1]+1;
$Nupper=$cocktailA[sizeof($cocktailA)-1]-$Zupper+1;

   // Define the colors
    $plotcaption[1]="Chart of Nuclides:<br>\n";
    $plotname[1]="Chart_of_Nuclides.png"; 

    $imagenum=1;
// Y WILL BE BGIntensity, X WILL BE BGAQ
// SET MINIMUM Y and X
    $ymin[$imagenum]=0;
    $xmin[$imagenum]=0;

// SET MAXIMUM Y and X
    $xmax[$imagenum]=$Nupper+2;
    $ymax[$imagenum]=$Zupper;

// SCALE PARAMETERS FOR GRAPH
    $plotwidth=500;
    $plotheight=$plotwidth;
    $plotmarginleft=60;
    $plotmarginbottom=60;
    $plotmarginother=20;
    $xscale[$imagenum]=($plotwidth-$plotmarginleft-$plotmarginother)/($xmax[$imagenum]-$xmin[$imagenum]);
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
    imagefilledrectangle($img[$imagenum], 0, 0, $plotwidth-1, $plotheight-1, $color[11]);

echo "<BR>";


$imagenum=1;
//DRAW BORDER AROUND GRAPH
    $imagenum=1;
    $leftx=$plotmarginleft;
    $rightx=$plotwidth-$plotmarginother;
    $topy=$plotmarginother;
    $bottomy=$plotheight-$plotmarginbottom;
    imageline($img[$imagenum], $leftx, $topy, $rightx, $topy, $color[12]);
    imageline($img[$imagenum], $rightx, $topy, $rightx, $bottomy, $color[12]);
    imageline($img[$imagenum], $rightx, $bottomy, $leftx, $bottomy, $color[12]);
    imageline($img[$imagenum], $leftx, $bottomy, $leftx, $topy, $color[12]);

    // Plot the data for contaminents
    for($i=0; $i<sizeof($cocktailA); $i++){
      if($cocktailA[$i]==$A && $cocktailZ[$i]==$Z){continue;}
      $x1=(($cocktailA[$i]-$cocktailZ[$i])*$xscale[$imagenum])+$plotmarginleft;
      $y1=$bottomy-(($cocktailZ[$i])*$yscale[$imagenum]);
      if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother)){continue;}
      if($y1>$bottomy || $y1<$topy){continue;} imagefilledrectangle($img[$imagenum],$x1,$y1,$x1+($xscale[$imagenum]),$y1+($yscale[$imagenum]),$color[0]);
    }
// Plot nucleus of interest
      $x1=(($A-$Z)*$xscale[$imagenum])+$plotmarginleft;
      $y1=$bottomy-(($Z)*$yscale[$imagenum]);
      if($x1>$plotmarginleft && $x1<($plotwidth-$plotmarginother) &&
      $y1<$bottomy && $y1>$topy){
      imagefilledrectangle($img[$imagenum],$x1,$y1,$x1+($xscale[$imagenum]),$y1+($yscale[$imagenum]),$color[10]);
      }
    // Plot stable nuclei boxes
    for($i=0; $i<sizeof($StableZ); $i++){
      if($StableZ[$i]>=$Zupper){break;}
      $x1=(($StableA[$i]-$StableZ[$i])*$xscale[$imagenum])+$plotmarginleft;
      $y1=$bottomy-(($StableZ[$i])*$yscale[$imagenum]);
      if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginleft-$plotmarginother)){continue;}
      if($y1>$bottomy || $y1<$topy){continue;}
      imagerectangle($img[$imagenum],$x1,$y1,$x1+($xscale[$imagenum]),$y1+($yscale[$imagenum]),$color[12]);
    }

    $colornum=0;

//DRAW Y AXIS TICKS AND LABELS 
    imagestring($img[$imagenum], 5, $leftx-40, 0, "Z", $color[12]);
    for($i=0; $i<=$Zupper; $i++){
      $y1=$bottomy-(($i)*$yscale[$imagenum]);
      if($y1>$bottomy || $y1<$topy){continue;}
      imageline($img[$imagenum], $leftx, $y1, $leftx+5, $y1, $color[12]);
      imageline($img[$imagenum], $rightx, $y1, $rightx-5, $y1, $color[12]);
      if($i%5==0){imagestring($img[$imagenum], 5, $leftx-40, $y1-8, $i, $color[12]);}
    }
//DRAW X AXIS TICKS AND LABELS
    $xlabelx=round($plotwidth-50);
    imagestring($img[$imagenum], 5, $xlabelx, $plotheight-35, "N", $color[12]);
    for($i=0; $i<=$Nupper; $i++){
      $x1=(($i)*$xscale[$imagenum])+$plotmarginleft;
      if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother)){$i++; continue;}
      imageline($img[$imagenum], $x1, $bottomy, $x1, $bottomy-5, $color[12]);
      imageline($img[$imagenum], $x1, $topy, $x1, $topy+5, $color[12]);
      if($i%5==0){imagestring($img[$imagenum], 5, $x1-15, $bottomy+5, $i, $color[12]);}
    }

    imagepng($img[$imagenum], $plotname[$imagenum]);
     imagedestroy($img[$imagenum]);


  echo " <form method=post name=\"Chartframeform\" action=\"chart.php\" target=\"ChartFrame\">\n";
    echo "<input type=\"hidden\" name=\"submit6\" value=\"1\" >";
  echo " </form>\n\n";




}
?>
<script type="text/javascript">
 document.Chartframeform.submit();
</script>
</body>
</html>
