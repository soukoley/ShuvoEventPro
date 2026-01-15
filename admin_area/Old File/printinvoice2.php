<?php
    session_start();
	include ("includes/db.php");
	include ("functions/functions.php");
	require 'fpdf16es/fpdf.php';
	require 'AmountToWord/amountword.php';
		
       
		$table_y = "customer_order";
		
		
		$BL_NO = $_GET['invoice_no'];
		$sql1 		= "SELECT * FROM customers ";
		
		$sql2 		= "SELECT DISTINCT customer_id, order_date FROM customer_order WHERE invoice_no = '$BL_NO'";
		$result2 	= $con->query($sql2);
		$row 		= $result2->fetch_assoc();
		$C_ID		= $row['customer_id'];
		$BL_DATE	= $row['order_date'];
		$BL_DATE 	= date("d-m-Y", strtotime($BL_DATE));
		
		$sql3 = "SELECT * FROM customers WHERE customer_id = $C_ID";
		$sql4 = "SELECT * FROM $table_y WHERE invoice_no = '$BL_NO'";
		$sql5 = "SELECT SUM(due_amount) AS TOT_NET FROM $table_y WHERE invoice_no = '$BL_NO'";
		$sql6 = "SELECT count(*) AS TOT_ITEM FROM $table_y WHERE invoice_no = '$BL_NO'";
		
		$result1 	= $con->query($sql1);		
		$result3 	= $con->query($sql3);
		$result5 	= $con->query($sql5);
		$result6 	= $con->query($sql6);
		$row 		= $result5->fetch_assoc();
		$sumAmt		= $row['TOT_NET'];
		
		$roundoffamt = round($sumAmt,0);
		$diff = round($roundoffamt - $sumAmt,2);
		$finalNet = $sumAmt+$diff;
		if($finalNet > 0){
			$amtWord = convert_digit_to_words(round($finalNet,0));
		} elseif($finalNet < 0){
			$newAdj = abs($finalNet);
			$amtWord = convert_digit_to_words(round($newAdj,0));
		} else{
			$amtWord = 'Zero Only';
		}
		
		// Seller Details
		$result1 = $con->query($sql1);
		if($result1->num_rows > 0) {
			$row = $result1->fetch_assoc();
			$s_gstin	= "";
			$s_pan		= "";		
		} else{
			$s_gstin	= "";
			$s_pan		= "";
		}
		
		// Buyer Details
		$result3 = $con->query($sql3);
		if($result3->num_rows > 0) {
			$row = $result3->fetch_assoc();
			$b_name		= $row['customer_name'];
			$b_addr		= $row['customer_address'];
			$b_contact	= $row['customer_contact'];	
			$b_gstin	= 'UNREGISTERED';
			
		} else{
			$b_name		= "";
			$b_addr		= "";
			$b_contact	= "";
			$b_gstin	= "";
		}
		
		// Number of items
		$row 		= $result6->fetch_assoc();
		$TOT_ITEM	= $row['TOT_ITEM'];
		
		class PDF extends FPDF{
			
			function RotatedText($x, $y, $txt, $angle){
				$this->Rotate($angle,$x,$y);
				$this->Text($x,$y,$txt);
				$this->Rotate(0);
			}
			
			function setSeller($s_gstin, $s_pan, $bl_no, $bl_date){
				$this->s_gstin 	= $s_gstin;
				$this->s_pan 	= $s_pan;
				$this->bl_no 	= $bl_no;
				$this->bl_date 	= $bl_date;
			}
			
			function setBuyer($b_name, $b_addr, $b_contact, $b_gstin){
				$this->b_name 		= $b_name;
				$this->b_addr 		= $b_addr;
				$this->b_contact	= $b_contact;
				$this->b_gstin		= $b_gstin;
			}
			
			function setAmount($amt_word, $rOff, $tot_net){
				$this->amt_word = $amt_word;
				$this->rOff 	= $rOff;
				$this->tot_net 	= $tot_net;
			}
			
			function copyType($copy){
				$this->copy 	= $copy;
			}
			
			// Page header
			function Header(){
				
				//Put the watermark
				$this->SetFont('Arial','B',40);
				//$this->SetTextColor(255,192,203);
				$this->SetTextColor(225,225,208);
				$this->RotatedText(35,155,'K i r a n    E n t e r p r i s e',40);
				$this->SetTextColor(0,0,0);
				
				$this->SetFont('arial', "", 16);
				$this->Rect(4,5,108,21,'D');			
				$this->SetFont('arial', "B", 14);
				//$this->Image("admin_images/logo_small.jpeg",58,6,100,15);
				$this->SetXY(77,5);
				$this->SetFont('arial', "B", 9);
				$this->Cell(37,6,$this->copy,'',0,'C',0);
				
				$this->SetXY(20,15);
				$this->SetFont('arial', "", 9);
				$this->Cell(50,5,"Office : Changghurali, Maju, Howrah-711414",'',0,'R',0);
				$this->SetX(115);
				//$this->Cell(190,5,"",'',0,'L',0);
				$this->SetFont('arial', "", 8.5);
				$this->Ln(4.5);
				$this->Cell(30,5,"Mobile : 8768537777",'',0,'R',0);
				//$this->SetX(60);
				//$this->Cell(85,5,"Mobile : 8777787749, 9748844999, 9830402529, 9831981447",'',0,'C',0);
				//$this->SetX(157);
				//$this->Cell(40,5,"Email : kiran@kirangroups.in",'',0,'L',0);
				
				$this->Rect(4,26.5,108,15.5,'D');
				//$this->Rect(112,21,100,12,'D');
				$this->SetXY(6,28);
				$this->SetFont('arial', "B", 11);
				$this->Cell(75,5,"To, M/s ".$this->b_name,'',0,'L',0);
				$this->SetFont('arial', "", 9);
				$this->SetXY(6,33);
				$this->Cell(75,5,"Mobile : ",'',0,'L',0);
				$this->SetXY(18,33);
				$this->Cell(75,5,$this->b_contact,'',0,'L',0);				// Buyer's Phone no
				//$this->SetXY(6,32);
				//$this->Cell(37,5,"Changghurali, Maju",'',0,'L',0);
				$this->SetXY(6,37);
				$this->Cell(60,5,$this->b_addr,'',0,'L',0);
				$this->SetX(66);
				//$this->Cell(16,5,"GSTIN : ",'',0,'R',0);
				//$this->Cell(28,5,$this->b_gstin,'',0,'L',0);				// Buyer's GSTN
				//$this->SetXY(114,30);
				//$this->Cell(30,5,"GSTIN : ".$this->s_gstin,'',0,'L',0);		// Seller's GSTN
				//$this->SetX(161);
				//$this->SetFont('arial', "B", 11);
				//$this->Cell(70,5,"Inv No. : ".$this->bl_no,'',0,'L',0);
				//$this->SetXY(114,36);
				//$this->SetFont('arial', "", 9);
				//$this->Cell(30,5,"PAN : ".$this->s_pan,'',0,'L',0);
				//$this->SetX(161);
				//$this->SetFont('arial', "B", 11);
				//$this->Cell(70,5,"Date : ".$this->bl_date,'',0,'L',0);
				
				$this->Rect(4,42,108,6,'D');
				$this->SetXY(5,43);
				$this->SetFont('arial', "", 10);
				$this->Cell(5,5,"SL",'',0,'L',0);
				$this->Cell(51,5,"PARTICULARS",'',0,'C',0);
				$this->Cell(12,5,"MRP",'',0,'C',0);
				$this->Cell(8,5,"Rate",'',0,'C',0);
				$this->Cell(8,5,"Qty",'',0,'C',0);
				$this->Cell(16,5,"Net Rate",'',0,'R',0);
				//$this->Cell(14,5,"Qty",'',0,'R',0);
				//$this->Cell(8,5,"Disc",'',0,'C',0);
				//$this->Cell(16,5,"Taxable",'',0,'R',0);
				//$this->Cell(9.5,5,"%",'',0,'C',0);
				//$this->Cell(14,5,"CGST",'',0,'C',0);
				//$this->Cell(9.5,5,"%",'',0,'C',0);
				//$this->Cell(14,5,"SGST",'',0,'C',0);
				//$this->Cell(15,5,"Net Rate",'',0,'R',0);
				//$this->Cell(16,5,"Net Amt",'',0,'R',0);
				$this->Rect(4,48,108,81,'D');
				$this->SetXY(5,49);
			}
			
			function Footer(){
				$this->SetXY(4,165);
				$this->SetFont('Arial', 'B', 9);
				//Page Number
				$this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,0,'C');
				
				$this->SetFont('Arial', 'B', 9);
				$this->Rect(4,140,108,25,'D');	//Rect($x, $y, $w, $h, $style='')
				$this->SetXY(6,141);
				$this->Cell(160,5,"Amount in words : ".$this->amt_word,'',0,'L',0);
				$this->SetFont('Arial', 'BU', 8);
				$this->SetXY(6,146);
				$this->Cell(30,5,"Declaration :",'',0,'L',0);
				$this->SetFont('Arial', '', 8);
				$this->SetXY(6,150);
				$this->Cell(160,5,"1. Goods once sold will not be taken back or exchanged",'',0,'L',0);
				$this->SetXY(6,153.5);
				$this->Cell(160,5,"2. Seller is not responsible for any loss or damage of goods in transit",'',0,'L',0);
				$this->SetXY(6,157);
				$this->Cell(160,5,"3. Interest @18% p.a. will be applicable if the payment made after 30 days from the date of sale made.",'',0,'L',0);
				$this->SetXY(6,160.5);
				$this->Cell(160,5,"4. Buyer undertakes to submit prescribed ST declaration to sender on demand. Disputes if any will be subject to howrah jurisdiction",'',0,'L',0);
				
				$this->SetXY(172,141);
				$this->SetFont('Arial', '', 9);
				$this->Cell(5,5,"For",'',0,'R',0);
				$this->SetXY(177,141);
				$this->SetFont('Arial', 'BU', 9);
				$this->Cell(25,5,"KIRAN ENTERPRISE",'',0,'L',0);
				$this->SetXY(201.5,158);
				$this->SetFont('Arial', 'B', 9);
				$this->Cell(9,5,"E. & O.E",'',0,'R',0);
				//$this->SetXY(170,123);
				//$this->Image("../Image/Kiran_sign.png",181,147,28,10);
			}
		}
		
		// A4 = 210 x 297 ; Legal = 215.9 x 355.6
		$pdf = new PDF("P", "mm", array(210,297));	// custom paper size...use array instead of the name of paper size.
		//$pdf = new PDF("P", "mm", "Legal");	// custom paper size...use array instead of the name of paper size.
		$pdf->setSeller($s_gstin, $s_pan, $BL_NO, $BL_DATE);
		$pdf->setBuyer($b_name, $b_addr, $b_contact, $b_gstin);
		$pdf->setAmount($amtWord, $diff, $sumAmt);
		
		date_default_timezone_set("Asia/Kolkata");
		$date = date("d:m:Y:h:m:s");
		$pdf->SetTitle('Invoice_'.$date);
		$pdf->AliasNbPages();
		
		// ORIGINAL COPY
		
		$pdf->copyType("ORIGINAL COPY");
		$pdf->AddPage();
		
		$y = $pdf->GetY();
		
		$result4 	= $con->query($sql4);
		if($result4->num_rows > 0){	
			$id = 0;	$totTaxable = 0;	$totCGST = 0;	$totSGST = 0;	$totNet = 0;
			while($row = $result4->fetch_assoc()) {		
				if($pdf->GetY() > 126){
					$pdf->AddPage();
					$y = 49;
					$prevTotNet = $totNet;
					$totTaxable = 0;	$totCGST = 0;	$totSGST = 0;	$totNet = 0;
				}
				
				$id 		= $id + 1;
				//$i_name 	= $row['IName'];
				$i_name 	= $row['product_title'];
				//$hsn 		= "111111";
				$qty 		= $row['qty'];
				$due 		= $row['due_amount'];
				//$fqty		= 0;
				$rate 		= $due/$qty;
				$mrp 		= $row['mrp'];
				//$cgst 		= 1;
				//$sgst 		= 1;
				$netAmt 	= $row['due_amount'];
				$taxAmt 	= 0;
				$taxableAmt	= $netAmt - $taxAmt;
				$netRate	= $netAmt/$qty;
				//$cgstAmt	= 0;
				//$sgstAmt	= 0;
				$totTaxable += $taxableAmt;
				//$totCGST 	+= $cgstAmt;
				//$totSGST 	+= $sgstAmt;
				$totNet 	+= $netAmt;
				//$cgstAmt	= sprintf("%.2f", $cgstAmt);
				//$sgstAmt	= sprintf("%.2f", $sgstAmt);
				$taxableAmt	= sprintf("%.2f", $taxableAmt);
				$netRate	= sprintf("%.2f", $netRate);
				$netAmt		= sprintf("%.2f", $netAmt);
				
				
				$pdf->SetFont('arial', "", 8.5);
				$pdf->Cell(5,5,$id,'',0,'L',0);
				$pdf->Cell(51,5,$i_name,'',0,'L',0);
				$pdf->Cell(12,5,$mrp,'',0,'R',0);
				$pdf->Cell(8,5,$rate,'',0,'R',0);
				$pdf->Cell(8,5,$qty,'',0,'C',0);
				//$pdf->Cell(14,5,$rate,'',0,'R',0);
				$pdf->Cell(14,5,$netAmt,'',0,'R',0);
				//$pdf->Cell(16,5,$taxableAmt,'',0,'R',0);
				//$pdf->Cell(9.5,5,$cgst,'',0,'C',0);
				//$pdf->Cell(14,5,$cgstAmt,'',0,'R',0);
				//$pdf->Cell(9.5,5,$sgst,'',0,'C',0);
				//$pdf->Cell(14,5,$sgstAmt,'',0,'R',0);
				//$pdf->Cell(15,5,$netRate,'',0,'R',0);
				//$pdf->Cell(16,5,$netAmt,'',0,'R',0);
				
				if($id == 12 && $TOT_ITEM > 12){
					$pdf->Rect(4,129,108,5,'D');
					$pdf->SetFont('arial', "", 10);
					$pdf->SetXY(37,129.5);
					$pdf->Cell(16,5,sprintf("%.2f", $totTaxable),'',0,'R',0);
					$pdf->SetX(142.5);
					//$pdf->Cell(14,5,sprintf("%.2f", $totCGST),'',0,'R',0);
					$pdf->SetX(166);
					//$pdf->Cell(14,5,sprintf("%.2f", $totSGST),'',0,'R',0);
					$pdf->Cell(31,5,sprintf("%.2f", $totNet),'',0,'R',0);
					
					$pdf->SetFont('arial', "", 11);
					$pdf->Rect(4,134,208,6,'D');
					$pdf->SetXY(5,134);
					$pdf->Cell(208,6,"Go to next page...",'',0,'C',0);
				}
				
				$y = $y+5;
				$pdf->SetXY(5,$y);
				//$id += 1;
				
			}
		}
		
		$pdf->Rect(4,129,108,5,'D');
		$pdf->SetFont('arial', "", 10);
		
		if($TOT_ITEM > 12){			
			$pdf->SetXY(6,129.5);
			$pdf->Cell(100,5,"Previous bill amount : ".$prevTotNet,'',0,'L',0);
		}
		
		$pdf->SetXY(57,129.5);
		$pdf->Cell(16,5,sprintf("%.2f", $totTaxable),'',0,'R',0);
		$pdf->SetX(70);
		$pdf->Cell(14,5,sprintf("%.2f", $totCGST),'',0,'R',0);
		$pdf->SetX(83);
		$pdf->Cell(14,5,sprintf("%.2f", $totSGST),'',0,'R',0);
		$pdf->Cell(15,5,sprintf("%.2f", $totNet),'',0,'R',0);
		
		//$pdf->SetFont('arial', "B", 12);
		$pdf->Rect(4,134,108,6,'D');
		$pdf->SetXY(5,134);
		//$pdf->Cell(95,6,"THANK YOU",'',0,'C',0);
		
		//$pdf->SetFont('arial', "", 9);
		//$pdf->Rect(100,134,44,6,'D');
		//$pdf->SetX(101);
		$pdf->Cell(34,6,"Rounded Off",'',0,'L',0);
		$pdf->Cell(8,6,sprintf("%.2f", $diff),'',0,'R',0);
		
		//$pdf->Rect(144,134,68,6,'D');
		$pdf->SetX(60);
		$pdf->SetFont('arial', "B", 12);
		$pdf->Cell(20,6,"Net Amount",'',0,'L',0);
		$pdf->Cell(32,6,sprintf("%.2f", $finalNet),'',0,'R',0);
		
		// DUPLICATE COPY
		
		$pdf->copyType("DUPLICATE COPY");
		$pdf->AddPage();
		$y = $pdf->GetY();
		
		$result4 	= $con->query($sql4);
		if($result4->num_rows > 0){	
			$id = 0;	$totTaxable = 0;	$totCGST = 0;	$totSGST = 0;	$totNet = 0;
			while($row = $result4->fetch_assoc()) {		
				if($pdf->GetY() > 126){
					$pdf->AddPage();
					$y = 49;
					$prevTotNet = $totNet;
					$totTaxable = 0;	$totCGST = 0;	$totSGST = 0;	$totNet = 0;
				}
				
				$id 		= $id + 1;
				//$i_name 	= $row['IName'];
				$i_name 	= "salt";
				$hsn 		= "111111";
				$qty 		= $row['qty'];
				$fqty		= 0;
				$rate 		= 0;
				$mrp 		= $row['mrp'];
				$cgst 		= 1;
				$sgst 		= 1;
				$netAmt 	= $row['due_amount'];
				$taxAmt 	= 10;
				$taxableAmt	= $netAmt - $taxAmt;
				$netRate	= $netAmt/$qty;
				$cgstAmt	= 0;
				$sgstAmt	= 0;
				$totTaxable += $taxableAmt;
				$totCGST 	+= $cgstAmt;
				$totSGST 	+= $sgstAmt;
				$totNet 	+= $netAmt;
				$cgstAmt	= sprintf("%.2f", $cgstAmt);
				$sgstAmt	= sprintf("%.2f", $sgstAmt);
				$taxableAmt	= sprintf("%.2f", $taxableAmt);
				$netRate	= sprintf("%.2f", $netRate);
				$netAmt		= sprintf("%.2f", $netAmt);
				
				
				$pdf->SetFont('arial', "", 8.5);
				$pdf->Cell(5,5,$id,'',0,'L',0);
				$pdf->Cell(51,5,$i_name,'',0,'L',0);
				$pdf->Cell(12,5,$hsn,'',0,'C',0);
				$pdf->Cell(8,5,$qty,'',0,'C',0);
				$pdf->Cell(8,5,$fqty,'',0,'C',0);
				$pdf->Cell(14,5,$rate,'',0,'R',0);
				$pdf->Cell(14,5,$mrp,'',0,'R',0);
				$pdf->Cell(16,5,$taxableAmt,'',0,'R',0);
				$pdf->Cell(9.5,5,$cgst,'',0,'C',0);
				$pdf->Cell(14,5,$cgstAmt,'',0,'R',0);
				$pdf->Cell(9.5,5,$sgst,'',0,'C',0);
				$pdf->Cell(14,5,$sgstAmt,'',0,'R',0);
				$pdf->Cell(15,5,$netRate,'',0,'R',0);
				$pdf->Cell(16,5,$netAmt,'',0,'R',0);
				
				if($id == 12 && $TOT_ITEM > 12){
					$pdf->Rect(4,129,208,5,'D');
					$pdf->SetFont('arial', "", 10);
					$pdf->SetXY(117,129.5);
					$pdf->Cell(16,5,sprintf("%.2f", $totTaxable),'',0,'R',0);
					$pdf->SetX(142.5);
					$pdf->Cell(14,5,sprintf("%.2f", $totCGST),'',0,'R',0);
					$pdf->SetX(166);
					$pdf->Cell(14,5,sprintf("%.2f", $totSGST),'',0,'R',0);
					$pdf->Cell(31,5,sprintf("%.2f", $totNet),'',0,'R',0);
					
					$pdf->SetFont('arial', "", 11);
					$pdf->Rect(4,134,208,6,'D');
					$pdf->SetXY(5,134);
					$pdf->Cell(208,6,"Go to next page...",'',0,'C',0);
				}
				
				$y = $y+5;
				$pdf->SetXY(5,$y);
				//$id += 1;
				
			}
		}
		
		$pdf->Rect(4,129,208,5,'D');
		$pdf->SetFont('arial', "", 10);
		
		if($TOT_ITEM > 12){			
			$pdf->SetXY(6,129.5);
			$pdf->Cell(100,5,"Previous bill amount : ".$prevTotNet,'',0,'L',0);
		}
		
		$pdf->SetXY(117,129.5);
		$pdf->Cell(16,5,sprintf("%.2f", $totTaxable),'',0,'R',0);
		$pdf->SetX(142.5);
		$pdf->Cell(14,5,sprintf("%.2f", $totCGST),'',0,'R',0);
		$pdf->SetX(166);
		$pdf->Cell(14,5,sprintf("%.2f", $totSGST),'',0,'R',0);
		$pdf->Cell(31,5,sprintf("%.2f", $totNet),'',0,'R',0);
		
		$pdf->SetFont('arial', "B", 12);
		$pdf->Rect(4,134,96,6,'D');
		$pdf->SetXY(5,134);
		$pdf->Cell(95,6,"THANK YOU",'',0,'C',0);
		
		$pdf->SetFont('arial', "", 9);
		$pdf->Rect(100,134,44,6,'D');
		$pdf->SetX(101);
		$pdf->Cell(34,6,"Rounded Off",'',0,'L',0);
		$pdf->Cell(8,6,sprintf("%.2f", $diff),'',0,'R',0);
		
		$pdf->Rect(144,134,68,6,'D');
		$pdf->SetX(145);
		$pdf->SetFont('arial', "B", 12);
		$pdf->Cell(33,6,"Net Amount",'',0,'L',0);
		$pdf->Cell(32,6,sprintf("%.2f", $finalNet),'',0,'R',0);
		
		// DUPLICATE COPY
		
		$pdf->copyType("DUPLICATE COPY");
		$pdf->AddPage();
		$y = $pdf->GetY();
		
		$result4 	= $con->query($sql4);
		if($result4->num_rows > 0){	
			$id = 0;	$totTaxable = 0;	$totCGST = 0;	$totSGST = 0;	$totNet = 0;
			while($row = $result4->fetch_assoc()) {		
				if($pdf->GetY() > 126){
					$pdf->AddPage();
					$y = 49;
					$prevTotNet = $totNet;
					$totTaxable = 0;	$totCGST = 0;	$totSGST = 0;	$totNet = 0;
				}
				
				$id 		= $id + 1;
				//$i_name 	= $row['IName'];
				$i_name 	= "salt";
				$hsn 		= "111111";
				$qty 		= $row['qty'];
				$fqty		= 0;
				$rate 		= 0;
				$mrp 		= $row['mrp'];
				$cgst 		= 1;
				$sgst 		= 1;
				$netAmt 	= $row['due_amount'];
				$taxAmt 	= 10;
				$taxableAmt	= $netAmt - $taxAmt;
				$netRate	= $netAmt/$qty;
				$cgstAmt	= 0;
				$sgstAmt	= 0;
				$totTaxable += $taxableAmt;
				$totCGST 	+= $cgstAmt;
				$totSGST 	+= $sgstAmt;
				$totNet 	+= $netAmt;
				$cgstAmt	= sprintf("%.2f", $cgstAmt);
				$sgstAmt	= sprintf("%.2f", $sgstAmt);
				$taxableAmt	= sprintf("%.2f", $taxableAmt);
				$netRate	= sprintf("%.2f", $netRate);
				$netAmt		= sprintf("%.2f", $netAmt);
				
				
				$pdf->SetFont('arial', "", 8.5);
				$pdf->Cell(5,5,$id,'',0,'L',0);
				$pdf->Cell(51,5,$i_name,'',0,'L',0);
				$pdf->Cell(12,5,$hsn,'',0,'C',0);
				$pdf->Cell(8,5,$qty,'',0,'C',0);
				$pdf->Cell(8,5,$fqty,'',0,'C',0);
				$pdf->Cell(14,5,$rate,'',0,'R',0);
				$pdf->Cell(14,5,$mrp,'',0,'R',0);
				$pdf->Cell(16,5,$taxableAmt,'',0,'R',0);
				$pdf->Cell(9.5,5,$cgst,'',0,'C',0);
				$pdf->Cell(14,5,$cgstAmt,'',0,'R',0);
				$pdf->Cell(9.5,5,$sgst,'',0,'C',0);
				$pdf->Cell(14,5,$sgstAmt,'',0,'R',0);
				$pdf->Cell(15,5,$netRate,'',0,'R',0);
				$pdf->Cell(16,5,$netAmt,'',0,'R',0);
				
				if($id == 12 && $TOT_ITEM > 12){
					$pdf->Rect(4,129,208,5,'D');
					$pdf->SetFont('arial', "", 10);
					$pdf->SetXY(117,129.5);
					$pdf->Cell(16,5,sprintf("%.2f", $totTaxable),'',0,'R',0);
					$pdf->SetX(142.5);
					$pdf->Cell(14,5,sprintf("%.2f", $totCGST),'',0,'R',0);
					$pdf->SetX(166);
					$pdf->Cell(14,5,sprintf("%.2f", $totSGST),'',0,'R',0);
					$pdf->Cell(31,5,sprintf("%.2f", $totNet),'',0,'R',0);
					
					$pdf->SetFont('arial', "", 11);
					$pdf->Rect(4,134,208,6,'D');
					$pdf->SetXY(5,134);
					$pdf->Cell(208,6,"Go to next page...",'',0,'C',0);
				}
				
				$y = $y+5;
				$pdf->SetXY(5,$y);
				//$id += 1;
				
			}
		}
		
		$pdf->Rect(4,129,208,5,'D');
		$pdf->SetFont('arial', "", 10);
		
		if($TOT_ITEM > 12){			
			$pdf->SetXY(6,129.5);
			$pdf->Cell(100,5,"Previous bill amount : ".$prevTotNet,'',0,'L',0);
		}
		
		$pdf->SetXY(117,129.5);
		$pdf->Cell(16,5,sprintf("%.2f", $totTaxable),'',0,'R',0);
		$pdf->SetX(142.5);
		$pdf->Cell(14,5,sprintf("%.2f", $totCGST),'',0,'R',0);
		$pdf->SetX(166);
		$pdf->Cell(14,5,sprintf("%.2f", $totSGST),'',0,'R',0);
		$pdf->Cell(31,5,sprintf("%.2f", $totNet),'',0,'R',0);
		
		$pdf->SetFont('arial', "B", 12);
		$pdf->Rect(4,134,96,6,'D');
		$pdf->SetXY(5,134);
		$pdf->Cell(95,6,"THANK YOU",'',0,'C',0);
		
		$pdf->SetFont('arial', "", 9);
		$pdf->Rect(100,134,44,6,'D');
		$pdf->SetX(101);
		$pdf->Cell(34,6,"Rounded Off",'',0,'L',0);
		$pdf->Cell(8,6,sprintf("%.2f", $diff),'',0,'R',0);
		
		$pdf->Rect(144,134,68,6,'D');
		$pdf->SetX(145);
		$pdf->SetFont('arial', "B", 12);
		$pdf->Cell(33,6,"Net Amount",'',0,'L',0);
		$pdf->Cell(32,6,sprintf("%.2f", $finalNet),'',0,'R',0);
		
		ob_clean();
        $pdf->Output();
        $conn->close();
		
    
?>