<!-- <?php session_start("HMTFid"); ?> -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>CSB</title>
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
CSB was selected.<BR>
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

 $thismass=getthismass($A,$Z);
$AQupper=(($thismass-$Q*$emass)/$Q)+(((($thismass-$Q*$emass)/$Q)*(0.5/$Resolve))*2);
$AQlower=(($thismass-$Q*$emass)/$Q)-(((($thismass-$Q*$emass)/$Q)*(0.5/$Resolve))*2);

echo "<BR>Plot of the Charge-State Booster Background Intensity as measured on August 16th 2011.<BR>";
$IntMax=1e-12;
$IntMaxi=$IntMini=0;
$IntMin=1e-6;
$i=0;
while($BGAQ[$i]<$AQlower){$i++;} $i--; $AQloweri=$i;
for($i=$i; $BGAQ[$i]<$AQupper; $i++)
{
$BGIntLOG[$i]=log10($BGIntensity[$i]);
if($BGIntensity[$i]<$IntMin){$IntMin=$BGIntensity[$i]; $IntMin=$BGIntensity[$i]; $IntMini=$i;}
if($BGIntensity[$i]>$IntMax){$IntMax=$BGIntensity[$i]; $IntMax=$BGIntensity[$i]; $IntMaxi=$i;}
}
$IntMaxLOG=log10($IntMax);
$IntMinLOG=log10($IntMin);
$i++; $AQupperi=$i;
?>

<?php

    echo '<div class="t11">';
    echo "<TABLE ALIGN=LEFT WIDTH=50%>";
    echo '<TR><TD ALIGN="LEFT">Species</TD><TD ALIGN="LEFT">A/q</TD></TR>';
    echo '<TR><TD ALIGN="LEFT"><SUP>'.$A.'</SUP>'.$Element[$Z].'<SUP>'.$Q.'+</SUP></TD><TD ALIGN="LEFT">';
    printf("%.3f",(($thismass-$Q*$emass)/$Q));
    echo '</TD></TR>';

    for($i=0; $i<sizeof($cocktailZ); $i++){
     if($cocktailA[$i]==$A && $cocktailZ[$i]==$Z){continue;}
     $beammass=getthismass($cocktailA[$i],$cocktailZ[$i]);
     echo '<TR><TD ALIGN="LEFT"><SUP>'.$cocktailA[$i].'</SUP>'.$Element[$cocktailZ[$i]].'<SUP>'.$cocktailQ[$i].'+</SUP></TD><TD ALIGN="LEFT">';      printf("%.3f",(($beammass-$cocktailQ[$i]*$emass)/$cocktailQ[$i]));
     echo '</TD></TR>';
    }
    
    echo "\n</TABLE>\n";
    echo "<div class=\"buff\"></div>";
    echo "<div class=\"t11\">";
      
   // Define the colors
    $plotcaption[1]="Measured CSB Background by A/q:<br>\n";
    $plotname[1]="CSB_Background_Aq.png"; 

    $imagenum=1;
// Y WILL BE BGIntensity, X WILL BE BGAQ
// SET MINIMUM Y and X
    $ymin[$imagenum]=$IntMin;
    $yminLOG[$imagenum]=log10($IntMin);
    $xmin[$imagenum]=$AQlower;

// SET MAXIMUM Y and X
    $xmax[$imagenum]=$AQupper;
    $ymax[$imagenum]=$IntMax;
    $ymaxLOG[$imagenum]=log10($IntMax);

// SCALE PARAMETERS FOR GRAPH
    $plotwidth=800;
    $plotheight=round($plotwidth*3/4);
    $plotmarginleft=80;
    $plotmarginbottom=60;
    $plotmarginother=20;
    $plotlegendmargin=60;
    $xscale[$imagenum]=($plotwidth-$plotmarginleft-$plotmarginother-$plotlegendmargin)/($xmax[$imagenum]-$xmin[$imagenum]);
    $yscale[$imagenum]=($plotheight-$plotmarginbottom-$plotmarginother)/($ymaxLOG[$imagenum]-$yminLOG[$imagenum]+2.5);
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

    $colornum=0;

echo "<BR>";


$imagenum=1;
//DRAW BORDER AROUND GRAPH
    $leftx=$plotmarginleft;
    $rightx=$plotwidth-$plotmarginother-$plotlegendmargin;
    $topy=$plotmarginother;
    $bottomy=$plotheight-$plotmarginbottom-$plotmarginother;
    imageline($img[$imagenum], $leftx, $topy, $rightx, $topy, $color[12]);
    imageline($img[$imagenum], $rightx, $topy, $rightx, $bottomy, $color[12]);
    imageline($img[$imagenum], $rightx, $bottomy, $leftx, $bottomy, $color[12]);
    imageline($img[$imagenum], $leftx, $bottomy, $leftx, $topy, $color[12]);
    
      // Draw Magnet Acceptance
      $beammass=getthismass($A,$Z);

      $x1=(((($beammass-$Q*$emass)/$Q-((($beammass-$Q*$emass)/$Q)*(0.5/$Resolve)))-$xmin[$imagenum])*$xscale[$imagenum])+$plotmarginleft;
      $x2=(((($beammass-$Q*$emass)/$Q+((($beammass-$Q*$emass)/$Q)*(0.5/$Resolve)))-$xmin[$imagenum])*$xscale[$imagenum])+$plotmarginleft;
      imageline($img[$imagenum], $x1, $topy, $x1, $bottomy, $color[5]);
      imageline($img[$imagenum], $x2, $topy, $x2, $bottomy, $color[5]);
      imagestring($img[$imagenum], 5, ($x2-$x1)/2+$x1-45, $topy+15, "Transmitted", $color[12]);

    // Plot the CSB BG Intensity vs A/Q data
    $imagenum=1;
    for($i=$AQloweri; $i<=$AQupperi; $i++){
      $x1=(($BGAQ[$i]-$xmin[$imagenum])*$xscale[$imagenum])+$plotmarginleft;
      $y1=($ymaxLOG[$imagenum]+2.5-1-$BGIntLOG[$i])*$yscale[$imagenum];
	$x2=$x1;
	$y2=$y1;
      if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother-$plotlegendmargin)){continue;}
      if($x2<$plotmarginleft || $x2>($plotwidth-$plotmarginother-$plotlegendmargin)){continue;}
      if($y2>($plotheight-$plotmarginbottom) || $y2<$plotmarginother){continue;}
      //imagefilledrectangle($img[$imagenum],$x1-3,$y1-3,$x1+3,$y1+3,$color[10]);
      imagefilledellipse($img[$imagenum],$x1,$y1,5,5,$color[10]);
      // imageline($img[$imagenum],$x2,$y2,$x1,$y1,$color[10]);
      imagethickline($img[$imagenum],$x2,$y2,$x1,$y1,$color[10],2);
    }

//DRAW Y AXIS TICKS AND LABELS 
$lasty1=1e-19;
    imagestring($img[$imagenum], 5, $leftx-70, 0, "Intensity (A)", $color[12]);
$i=0;
    for($yunit=round($yminLOG[$imagenum]-1.0); $yunit<($ymaxLOG[$imagenum]+1.0); $yunit+=0.2){
      $efory1=pow(10,floor($yunit));
      $y1=($ymaxLOG[$imagenum]+2.5-1-$yunit)*$yscale[$imagenum];
//echo $yunit,", ",$y1,", ",$efory1,"<BR>";
      if($y1>$bottomy || $y1<$topy){continue;}
      if($yunit%1.0==0 && $efory1>$lasty1){$labels[$i]=$efory1; $labelspos[$i]=$y1; $i++; $lasty1=$efory1;}
    }
for($i=0; $i<sizeof($labels); $i++){
imagestring($img[$imagenum], 5, $leftx-70, $labelspos[$i]-8, $labels[$i], $color[12]);
$y1=$labelspos[$i]-$labelspos[$i+1];
for($j=1; $j<9; $j++){
//echo $j,", ",$labelspos[$i],", ",$labelspos[$i+1],", ",$y1,", ",log10($j),", ",(log10($j)*$y1),", ",$labelspos[$i]-(log10($j)*$y1),"<BR>";
      if($labelspos[$i]-(log10($j)*$y1)>$bottomy || $labelspos[$i]-(log10($j)*$y1)<$topy){continue;}
if($j==1){
      imageline($img[$imagenum], $leftx, $labelspos[$i]-(log10($j)*$y1), $leftx+8, $labelspos[$i]-(log10($j)*$y1), $color[12]);
      imageline($img[$imagenum], $rightx, $labelspos[$i]-(log10($j)*$y1), $rightx-8, $labelspos[$i]-(log10($j)*$y1), $color[12]);
}
else{
      imageline($img[$imagenum], $leftx, $labelspos[$i]-(log10($j)*$y1), $leftx+5, $labelspos[$i]-(log10($j)*$y1), $color[12]);
      imageline($img[$imagenum], $rightx, $labelspos[$i]-(log10($j)*$y1), $rightx-5, $labelspos[$i]-(log10($j)*$y1), $color[12]);
}
}
}

//DRAW X AXIS TICKS AND LABELS
    $xlabelx=round($plotwidth/2-50);
    imagestring($img[$imagenum], 5, $xlabelx, $plotheight-35, "A/q", $color[12]);
    $i=0;
    $x1=0;
    $xunit=0.005;
    while($x1<($plotwidth-$plotmarginother-$plotlegendmargin)){
      $forx1=round($xmin[$imagenum]+($xunit*$i),2);
      $x1=(($xunit*$i)*$xscale[$imagenum])+$plotmarginleft;
      if($x1<$plotmarginleft || $x1>($plotwidth-$plotmarginother-$plotlegendmargin)){$i++; continue;}
      imageline($img[$imagenum], $x1, $bottomy, $x1, $bottomy-5, $color[12]);
      imageline($img[$imagenum], $x1, $topy, $x1, $topy+5, $color[12]);
      if($i%(2)==0){imagestring($img[$imagenum], 5, $x1-15, $bottomy+5, $forx1, $color[12]);}
      $i++;
    }

    imagepng($img[$imagenum], $plotname[$imagenum]);
     imagedestroy($img[$imagenum]);
    echo "<p>\n";
    echo "<a href=\"./";
    echo $plotname[$imagenum];
    echo "\" target=\"_top\"> <img src=\"";
    echo $plotname[$imagenum];
    echo "\" alt=\"Measured CSB Background\" width=\"";
    echo $plotwidth;
    echo "\" height=\"";
    echo $plotheight;
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
      if($i%10==0){imagestring($img[$imagenum], 5, $x1-15, $bottomy+5, $i, $color[12]);}
    }

    imagepng($img[$imagenum], $plotname[$imagenum]);
     imagedestroy($img[$imagenum]);


  echo " <form method=post name=\"Chartframeform\" action=\"chart.php\" target=\"ChartFrame\">\n";
    echo "<input type=\"hidden\" name=\"submit6\" value=\"1\" >";
  echo " </form>\n\n";

?>
<script type="text/javascript">
 document.Chartframeform.submit();
</script>

</body>
</html>
