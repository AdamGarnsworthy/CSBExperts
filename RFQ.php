
<html>
<body>
RFQ was selected.
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
if(isset($_POST["VBias"])){$Vbias=$_POST["VBias"];} else{$Vbias=12000;}
if(isset($_POST["length"])){$l=$_POST["length"];} else{$l=5.5;}
if(isset($_POST["RFQAccept"])){$RFQaccept=$_POST["RFQAccept"];} else{$RFQaccept=(1.0/35000000.0)/4.0;}
if(isset($_POST["ESpread"])){$Espread=$_POST["ESpread"];} else{$Espread=0.001;}

echo "<BR><BR>\n<FORM action=\"RFQ.php\" target=\"ISACFrame\" method=\"post\">";
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
  echo '<div class="t11">CSB Bias Voltage: ';
    echo "<input type=\"text\" name=\"VBias\" value=\"";
      if($Vbias>0){printf("%.0f",$Vbias);}
    echo "\" style=\"width:50px;\"> Volts";
    echo '<div class="t11">CSB to RFQ Distance: ';
    echo "<input type=\"text\" name=\"length\" value=\"";
      if($l>0){printf("%.1f",$l);}
    echo "\" style=\"width:50px;\"> meters";
 //   echo '<div class="t11">RFQ Acceptance: ';
 //   echo "<input type=\"text\" name=\"RFQAccept\" value=\"";
 //     if($RFQaccept>0){printf("%.1e",$RFQaccept);}
 //   echo "\" style=\"width:50px;\"> secs";
    echo "<input type=\"hidden\"name=\"RFQAccept\" value=\"";
    echo $RFQaccept;
      echo "\">";
  echo '<div class="t11">Energy Spread: ';
   echo "<input type=\"text\" name=\"ESpread\" value=\"";
      if($Espread>0){printf("%.4f",$Espread);}
    echo "\" style=\"width:50px;\">";
    echo '<div class="t12">';
    echo "<input type=\"submit\" name=\"submit7\" value=\"Recalculate\" >";
    echo '</div>';
echo "</FORM>\n<BR>\n";

  if($_POST["submit7"] && $Z>0){

// Get post data
$A=$_POST["Mass"];
$Z=$_POST["Zvalue"];
$Liner=$_POST["Liner_material"];
$Resolve=$_POST["resolving_power"];
$Q=$_POST["Charge"];
$cocktailA = unserialize(base64_decode($_POST["cocktailA"]));
$cocktailZ = unserialize(base64_decode($_POST["cocktailZ"]));
$cocktailQ = unserialize(base64_decode($_POST["cocktailQ"]));
if(isset($_POST["VBias"])){$Vbias=$_POST["VBias"];} else{$Vbias=12000;}
if(isset($_POST["length"])){$l=$_POST["length"];} else{$l=5.5;}
if(isset($_POST["RFQAccept"])){$RFQaccept=$_POST["RFQAccept"];} else{$RFQaccept=(1.0/35000000.0)/4.0;}
if(isset($_POST["ESpread"])){$Espread=$_POST["ESpread"];} else{$Espread=0.001;}

// Calculate velocity of the beam 
    $beammass=getthismass($A,$Z);
echo "<BR><BR>Ion of Interest:<BR>A= ",$A,", Z= ",$Z,", Q= ",$Q,", Atomic Mass= ",$beammass,"AMU, Ion Mass= ",($beammass-($Q*$emass)),"AMU<BR>";
echo "CSB Bias Voltage= ",$Vbias,"Volts, RFQ Acceptance= ",$RFQaccept,"secs<BR>";
$v=sqrt((2*$Vbias)/((($beammass-($Q*$emass))*1.66e-27)/($Q*1.602e-19)));
$t=$l/$v;
echo "Distance from Buncher to RFQ= ",$l,"metres, velocity= ",$v,"m/s, Time-of-Flight=",$t,"secs<BR>","<BR>";
    echo '<div class="t11">';
    echo "<TABLE ALIGN=LEFT WIDTH=50%>";
    echo '<TR><TD ALIGN="LEFT">Species</TD><TD ALIGN="LEFT">Velocity (m/s)</TD><TD ALIGN="LEFT">Time-of-Flight (us)</TD></TR>';
    echo '<TR><TD ALIGN="LEFT"><SUP>'.$A.'</SUP>'.$Element[$Z].'<SUP>'.$Q.'+</SUP></TD><TD ALIGN="LEFT">';
    	       printf("%.5f",$v);
		 echo '</TD><TD ALIGN="LEFT">';
		   printf("%.5f",($t*1e6));
		   echo '</TD></TR>';

   // Define the colors
    $plotcaption[1]="Calculated velocity from CSB:<br>";
    $plotname[1]="velocity.png";

    $imagenum=1;
// Y WILL BE Fraction, X WILL BE charge state
// SET MINIMUM Y and X
    $ymin[$imagenum]=0;
    $xmin[$imagenum]=$t-($t*0.005);

// SET MAXIMUM Y and X
    $xmax[$imagenum]=$t+($t*0.005);
    $ymax[$imagenum]=1.5;

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
    $color[5]=imagecolorallocate($img[$imagenum], 84, 84, 84); // grey
    imagefilledrectangle($img[$imagenum], 0, 0, $plotwidth-1, $plotheight-1, $color[11]);

    // LEGEND Parameters
    $legendx=$plotwidth-60;
    $legendy=$plotmarginother+20;

    // Plot the velocity data for ion of interest
    $imagenum=1;
      $x1=($t-$xmin[$imagenum])*$xscale[$imagenum];
      $x1=$x1+$plotmarginleft;
      $y1=(1.0-$ymin[$imagenum])*$yscale[$imagenum];
      $y1=$plotheight-$plotmarginbottom-$y1;
    
               $x2=$xmin[$imagenum];
               $y2=$plotheight-$plotmarginbottom;
               $j=$t;
               while($j>$x2){ $j-=(($xmax[$imagenum]-$xmin[$imagenum])/500); }
               for($j=$j; $j<$xmax[$imagenum]; $j+=(($xmax[$imagenum]-$xmin[$imagenum])/500)){
               $x1=($j-$xmin[$imagenum])*$xscale[$imagenum];
               $x1=$x1+$plotmarginleft;
               $y1=round(((1.0*exp(-1.0*(pow(($j-$t),2)/(2.0*pow(($Espread*$t),2)))))-$ymin[$imagenum])*$yscale[$imagenum],1);
               $y1=$plotheight-$plotmarginbottom-$y1;
               if($j==$xmin[$imagenum]) continue;
               if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother-$plotlegendmargin)){continue;}
               if($x2<$plotmarginleft || $x2>($plotwidth-$plotmarginother-$plotlegendmargin)){$x2=$plotmarginleft; continue;}
               imageline($img[$imagenum],$x2,$y2,$x1,$y1,$color[10]);
               $x2=$x1; $y2=$y1;
               }

      $x1=($t-$xmin[$imagenum])*$xscale[$imagenum];
      $x1=$x1+$plotmarginleft;
      $y1=(1.0-$ymin[$imagenum])*$yscale[$imagenum];
      $y1=$plotheight-$plotmarginbottom-$y1;

      imagefilledellipse($img[$imagenum],$x1,$y1,10,10,$color[10]);
      // Draw legend
      imagefilledellipse($img[$imagenum],$legendx,$legendy,10,10,$color[10]);
      $legendname=sprintf("%d%s",$A,$Element[$Z]);
      imagestring($img[$imagenum], 5, $legendx+15, $legendy-7, $legendname, $color[12]);
    

	      // Plot the A/q data - Got to here correct
	      $imagenum=1;
	      $colornum=0;
	      for($i=0; $i<sizeof($cocktailZ); $i++){
               if($cocktailA[$i]==$A && $cocktailZ[$i]==$Z){continue;}
               $thismass=getthismass($cocktailA[$i],$cocktailZ[$i]);
               $v=sqrt((2*$Vbias)/((($thismass-($cocktailQ[$i]*$emass))*1.66e-27)/($cocktailQ[$i]*1.602e-19)));
               $t=$l/$v;
               echo '<TR><TD ALIGN="LEFT"><SUP>'.$cocktailA[$i].'</SUP>'.$Element[$cocktailZ[$i]].'<SUP>'.$cocktailQ[$i].'+</SUP></TD><TD ALIGN="LEFT">';
    	       printf("%.5f",$v);
		 echo '</TD><TD ALIGN="LEFT">';
		   printf("%.5f",($t*1e6));
		   echo '</TD></TR>';
	       
               $x2=$xmin[$imagenum];
               $y2=$plotheight-$plotmarginbottom;
               $j=$t;
               while($j>$x2){ $j-=(($xmax[$imagenum]-$xmin[$imagenum])/500); }
               for($j=$j; $j<$xmax[$imagenum]; $j+=(($xmax[$imagenum]-$xmin[$imagenum])/500)){
               $x1=($j-$xmin[$imagenum])*$xscale[$imagenum];
               $x1=$x1+$plotmarginleft;
               $y1=round(((1.0*exp(-1.0*(pow(($j-$t),2)/(2.0*pow(($Espread*$t),2)))))-$ymin[$imagenum])*$yscale[$imagenum],1);
               $y1=$plotheight-$plotmarginbottom-$y1;
               if($j==$xmin[$imagenum]) continue;
               if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother-$plotlegendmargin)){continue;}
               if($x2<$plotmarginleft || $x2>($plotwidth-$plotmarginother-$plotlegendmargin)){$x2=$plotmarginleft; continue;}
               imageline($img[$imagenum],$x2,$y2,$x1,$y1,$color[$colornum%5]);
               $x2=$x1; $y2=$y1;
               }

               $x1=($t-$xmin[$imagenum])*$xscale[$imagenum];
               $x1=$x1+$plotmarginleft;
               $y1=(1.0-$ymin[$imagenum])*$yscale[$imagenum];
               $y1=$plotheight-$plotmarginbottom-$y1;


	//	if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother-$plotlegendmargin)){continue;}
	//	if($x2<$plotmarginleft || $x2>($plotwidth-$plotmarginother-$plotlegendmargin)){continue;}
	//	if($y2>($plotheight-$plotmarginbottom) || $y2<$plotmarginother){continue;}
		if(floor($colornum/5)==0){imagefilledellipse($img[$imagenum],$x1,$y1,7,7,$color[$colornum%5]);}
	        if(floor($colornum/5)==1){imagefilledrectangle($img[$imagenum],$x1-3,$y1-3,$x1+3,$y1+3,$color[$colornum%5]);}
		if(floor($colornum/5)==2){imageellipse($img[$imagenum],$x1,$y1,7,7,$color[$colornum%5]);}
	//	imageline($img[$imagenum],$x2,$y2,$x1,$y1,$color[$colornum%5]);
		// Draw legend
		if(floor($colornum/5)==0){imagefilledellipse($img[$imagenum],$legendx,$legendy+(($colornum+1)*30),7,7,$color[$colornum%5]);}
		if(floor($colornum/5)==1){imagefilledrectangle($img[$imagenum],$legendx-3,$legendy+(($colornum+1)*30)-3,$legendx+3,$legendy+(($colornum+1)*30)+3,$color[$colornum%5]);}
		if(floor($colornum/5)==2){imageellipse($img[$imagenum],$legendx,$legendy+(($colornum+1)*30),7,7,$color[$colornum%5]);}
	//	imageline($img[$imagenum],$legendx-10,$legendy+(($colornum+1)*30),$legendx+10,$legendy+(($colornum+1)*30),$color[$colornum%5]);
		$legendname=sprintf("%d%s",$cocktailA[$i],$Element[$cocktailZ[$i]]);
		imagestring($img[$imagenum], 5, $legendx+15,
	         $legendy+(($colornum+1)*30)-7, $legendname, $color[12]);
	        $colornum++;
	      }
	       echo "\n</TABLE>\n";
    echo "<div class=\"buff\"></div>";
    echo "<div class=\"t11\">";

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

      // Draw RFQ Acceptance
      $beammass=getthismass($A,$Z);
      $v=sqrt((2*$Vbias)/((($beammass-($Q*$emass))*1.66e-27)/($Q*1.602e-19)));
      $t=$l/($v);
      $x1=((($t-($RFQaccept/2))-$xmin[$imagenum])*$xscale[$imagenum])+$plotmarginleft;
      $x2=((($t+($RFQaccept/2))-$xmin[$imagenum])*$xscale[$imagenum])+$plotmarginleft;
      imageline($img[$imagenum], $x1, $topy, $x1, $bottomy, $color[5]);
      imageline($img[$imagenum], $x2, $topy, $x2, $bottomy, $color[5]);
      imagestring($img[$imagenum], 5, ($x2-$x1)/2+$x1-6, $topy+15, "B", $color[12]);
      $x1=((($t+(1/35e6)-($RFQaccept/2))-$xmin[$imagenum])*$xscale[$imagenum])+$plotmarginleft;
      $x2=((($t+(1/35e6)+($RFQaccept/2))-$xmin[$imagenum])*$xscale[$imagenum])+$plotmarginleft;
      imagestring($img[$imagenum], 5, ($x2-$x1)/2+$x1-10, $topy+15, "B+1", $color[12]);
      imageline($img[$imagenum], $x1, $topy, $x1, $bottomy, $color[5]);
      imageline($img[$imagenum], $x2, $topy, $x2, $bottomy, $color[5]);
      $x1=((($t-(1/35e6)-($RFQaccept/2))-$xmin[$imagenum])*$xscale[$imagenum])+$plotmarginleft;
      $x2=((($t-(1/35e6)+($RFQaccept/2))-$xmin[$imagenum])*$xscale[$imagenum])+$plotmarginleft;
      imagestring($img[$imagenum], 5, ($x2-$x1)/2+$x1-10, $topy+15, "B-1", $color[12]);
      imageline($img[$imagenum], $x1, $topy, $x1, $bottomy, $color[5]);
      imageline($img[$imagenum], $x2, $topy, $x2, $bottomy, $color[5]);
      
//DRAW Y AXIS TICKS AND LABELS
    imagestring($img[$imagenum], 5, $leftx-40, 0, "Intensity", $color[12]);
    $i=0;
    $y1=0;
    $yunit=0.25;
    while($i*$yunit<=2){
      $efory1=$ymin[$imagenum]+$yunit*$i;
      $y1=$yunit*$i*$yscale[$imagenum];
      $y1=$bottomy-$y1;
      if($y1>($plotheight-$plotmarginbottom) || $y1<$plotmarginother){$i++; continue;}
      imageline($img[$imagenum], $leftx, $y1, $leftx+5, $y1, $color[12]);
      imageline($img[$imagenum], $rightx, $y1, $rightx-5, $y1, $color[12]);
      if($i%2==0){imagestring($img[$imagenum], 5, $leftx-40, $y1-8, $efory1, $color[12]);}
      $i++;
    }
//DRAW X AXIS TICKS AND LABELS
    $xlabelx=round($plotwidth/2-50);
    imagestring($img[$imagenum], 5, $xlabelx, $plotheight-35,
    "Time-of-Flight Buncher-RFQ (us)", $color[12]);
    $i=0;
    $x1=0;
    $xunit=1e-9;
while($x1<($plotwidth-$plotmarginother-$plotlegendmargin)){
  $forx1=round(($xmin[$imagenum]+($xunit*$i))*1e6,3);
  // $forx1=sprintf("%4.2f",$xmin[$imagenum]+$xunit*$i);
      $x1=($xunit*$i)*$xscale[$imagenum];
      $x1=$x1+$plotmarginleft;
      if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother-$plotlegendmargin)){$i++; continue;}
      imageline($img[$imagenum], $x1, $bottomy, $x1, $bottomy-5, $color[12]);
      imageline($img[$imagenum], $x1, $topy, $x1, $topy+5, $color[12]);
      if($i%10==0){
	        imagestring($img[$imagenum], 5, $x1-15, $bottomy+5, $forx1, $color[12]);
                imageline($img[$imagenum], $x1, $bottomy, $x1, $bottomy-10, $color[12]);
                imageline($img[$imagenum], $x1, $topy, $x1, $topy+10, $color[12]);
	         }
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
    echo "\" alt=\"Calculated velocity Distributions\" width=\"";
    echo 400;
    echo "\"></a>\n";
    echo "</p>\n";

}
?>
</body>
</html>