<?php 
  require_once('configs/db.php');
  $query = "select * from opos_test";
  $result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html>
  <head>
  <title>...</title>
    <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--font-->
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/font.css">
    <!--style--> 
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/swiper.min.css" >
    <link rel="stylesheet" href="css/style-acadenmy.css"> 
    <link rel="stylesheet" href="css/ai-predictions.css"> 
    <link rel="stylesheet" href="css/style-home.css">  
    
  </head>
 
<body>

<!--header-->
<div class="header">
	<div class="container">
            <a href="home.html" class="logo">The Alchemist</a>
            <ul class="menu-top">
                <li>
                	<a>Al-Trade Subsysterm</a>
                	
                </li>
                <li><a href="#">TA Signals</a></li>
                <li>
                	<a  href="#">Trend Spotter</a>
                    
                </li>
                <li>
                	<a href="#" >AI Predictions</a>
                   
                </li>
                
            </ul>
            <div class="head-right">
                <a href="Sign-in.html" class="btn-login">Login</a>
                 <a href="Sign-up.html" class="btn-signup">Sign Up</a>
            </div>
    </div>
</div>
<!--end header-->
<div class="wapper">
    <div class="banner-top-home">
	    <div class="container">
        	<div class="box-head">
            	<h1 class="title">
                	The Alchemist <br />
                	<span>AI Predictions </span>
                </h1>
                <p class="des" >
                	Using AI to estimate the price of  <br/> trading pairs
                </p>
                <a href="#" class="btn-get"> Tutorial Video</a>
            </div>
        </div>
    
    </div>
	
    
    <div class="box-list-Alchemist">
        <div class="main">
         <marquee onmouseover="this.stop()" onmouseout="this.start()">
           
             <a href="#" class="item">Technical Analytics Signals</a>
             <a href="#" class="item">Trend Spotter</a>
             <a href="#" class="item">AI Predictions</a>
             <a href="#" class="item">Whale Spyder</a>
         </marquee>
     </div>
</div>
   		<div class="container">
        <br>
        <div class=e3901_22769>
            <div class=e3893_34090>
              <div class=e3893_34091>
                <div class=e3893_34092><span  class="e3893_34093">Crypto Currencies</span><span  class="e3893_34094">Forecast Time


                </span><span  class="e3893_34095">Status</span><span  class="e3893_34096">Price at Forecast Time ($)</span><span  class="e3893_34097">1H Prediction</span><span  class="e3893_34098">Total Correct Predictions </span><span  class="e3893_34099">Total Predictions</span><span  class="e3893_34100">Trade on</span></div>
              </div>

              <?php 
                $count = 34100;
                while($row = mysqli_fetch_assoc($result)) {

                  $index = $row['index'];
                  $update_time = $row['update_time'];
                  $pred_price = round($row['pred_price'] * 100) / 100;
                  $last_close = $row['last_close'];
                  $total = $row['total'];

                  $status = round(($pred_price - $last_close) * 100/$last_close * 100) / 100;
                  $style = $status > 0 ? 
                  'background:url(./images/icon-arrow-up.svg) no-repeat center center;' 
                  : 'background:url(./images/icon-arrow-down.svg) no-repeat center center;'

              ?>
               <div class=e3893_<?php echo($count+=1);?>>
                <div class=e3893_<?php echo($count+=1);?>>
                  <div class=e3893_34<?php 
                       $count+=1;
                      if ($index == 0) {
                          echo("103");
                      } elseif ($index == 1) {
                          echo("127");
                      } else {
                          echo("151");
                      }
                     ?>>
                    <!-- thẻ chèn ảnh-->
                     <div class="e3893_<?php echo($count+=1);?>" ></div> 
                  </div>
                  <div class=e3893_<?php echo($count+=1);?> ><span  class="e3893_<?php echo($count+=1);?>">
                    <?php 
                      if($index == 0) {
                        echo("BTCUSDT");
                      } else if($index == 1) {
                        echo("ETH");
                      } else {
                        echo("SOLUSDT");
                      }
                    ?>
                  </span><span  class="e3893_<?php echo($count+=1);?>">
                    <?php 
                      if($index == 0) {
                        echo("Bitcoin");
                      } else if($index == 1) {
                        echo("Etherium");
                      } else {
                        echo("Solana");
                      }
                    ?>
                  </span></div>
                </div>
                <div class=e3893_<?php echo($count+=1);?>><span  class="e3893_<?php echo($count+=1);?>"><?php echo($update_time); ?></span><span  class="e3893_<?php echo($count+=1);?>">20:05:01</span></div>
                <div class=e3893_<?php echo($count+=1);?>>
                  <div class=e3893_<?php echo($count+=1);?>>
                    <div class="ei3893_<?php echo($count);?>_1889_101659">
                      <div class="ei3893_341<?php echo($count);?>_1889_101660">
                        <div class="ei3893_<?php echo($count);?>_1889_101661" style="<?php echo($style);?>"></div>
                        <div class="ei3893_<?php echo($count);?>_1889_101662"></div>
                      </div>
                    </div>
                  </div><span  class="e3893_<?php echo($count+=1);?>"><?php echo($status);?>%</span>
                </div>
                <div class=e3893_<?php echo($count+=1);?>>
                  <div class=e3893_<?php echo($count+=1);?>><span  class="e3893_<?php echo($count+=1);?>"><?php echo($last_close);?></span></div>
                </div>
                <div class=e3893_<?php echo($count+=1);?>>
                  <div class=e3893_<?php echo($count+=1);?>><span  class="e3893_<?php echo($count+=1);?>"><?php echo($pred_price);?></span></div>
                </div><span  class="e3893_<?php echo($count+=1);?>"><?php echo($row['cnt']);?></span><span  class="e3893_<?php echo($count+=1);?>"><?php echo($total);?></span>
                <div class=e3893_<?php echo($count+=1);?>>
                  <div class=e3893_<?php echo($count+=1);?>  ><a target=”_blank” href="https://twitter.com/TheAlchemist_AI"><span  class="ei3893_<?php echo($count);?>_513_143163">Jupiter</span></a></div>
                  <div class=e3893_<?php echo($count+=1);?>><a target=”_blank” href="https://t.me/thealchemistofficial"><span  class="ei3893_<?php echo($count);?>_513_143163">HXRO</span></a></div>
                </div>
              </div>
              <?php 
                }
              ?>
              <div class="e3893_34317"></div>
              <div class="e3893_34318"></div>
              <div class="e3893_34319"></div>
              <div class="e3893_34320"></div>
              <div class="e3893_34321"></div>
              <div class="e3893_34322"></div>
              <div class="e3893_34323"></div>
              <div class="e3893_34324"></div><span  class="e3922_34570">This is a trial version, and we disclaim all liability for any damages you may sustain.</span>
            </div>
          </div>
        
      <br>
        </div>
 
   
   <!--Essentials-->
  <!-- our advisors-->
  
  <!-- Our backers-->
  
   <!--Maybe you like-->
  
    <!--//-->
    

</div>

<br><br><br>



 
<div class="banner-top-glossary">
    <div class="container">
        
        <div class="box-head">
            <h1 class="title">
                <span>Your Social Media</span>
            </h1>
                <a href="#" class="btn-get"> Alchemist's Twitter</a> &ensp; <a href="#" class="btn-get">Alchemist's Telegram</a>
                <br>
                <br>
                
                
            
                <h1 class="title">
                    Special 
                    <span>Thanks to </span>
                </h1>
                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Your backers & your sponsors:
                    
                    <div class="box-head">
                        
                        <div class="main">
                            
                            <img class="logo" width="200px" heigh = "500px" src="images/logo/logo-web3space.png" />&emsp;&emsp;
                            <img class="logo" width="100px" heigh = "200px" src="images/logo/logo-amazone.svg" />
                          <br> <br>
                            <img class="logo" width="80px" heigh = "80px" src="images/logo/logo-jupiter.png" />&emsp;&emsp;
                            <img class="logo" width="200px" heigh = "300px" src="images/logo/logo-hxro-2.png" /> 
                            
                          
                           
                        
                   </div>    
                      
                  
               
            </div>
        </div>
    </div>
    
</div>
<!--footer-->
<div class="footer">
	<div class="container">
    	<div class="box-left">
            <a href="#" class="logo-ft"></a>
            <p class="des">
            	Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics.
            </p>
            <div class="box-network">
            	<h2 class="title">Social Media</h2>
                <div class="list">
                	 <a href="#" class="icon wt">Twitter</a>
                      <a href="#" class="icon m">M</a>
                      <a href="#" class="icon youtobe">youtobe</a>
                	<a href="#" class="icon telegam">Telegam</a>
                    <a href="#" class="icon fb">facebook</a>
                </div>
            </div>
        </div>
        <div class="box-right">
              <div class="item">
              	<h2 class="title">About Us</h2>
                <a href="#" class="alink">Whitepaper</a>
                <a href="#" class="alink">Business Contact </a>
                <a href="#" class="alink">Careers</a>
              </div> 
              <div class="item">
              	<h2 class="title">Products</h2>
                <a href="#" class="alink">Al Trade</a>
                <a href="#" class="alink">Dashboard</a>
                <a href="#" class="alink">Social</a>
                <a href="#" class="alink">Marketplace<span class="item-comingson">Coming Soon</span></a>
                <a href="#" class="alink">Box<span class="item-comingson">Coming Soon</span></a>
                <a href="#" class="alink">Launchpad<span class="item-comingson">Coming Soon</span></a>
                <a href="#" class="alink">Trend Spotter<span class="item-comingson">Coming Soon</span> </a>
              </div> 
              <div class="item">
              	<h2 class="title">Learn</h2>
                <a href="#" class="alink">Guide</a>
                <a href="#" class="alink">Academy</a>
                <a href="#" class="alink">News</a>
              </div> 
              <div class="item">
              	<h2 class="title">Support </h2>
                <a href="#" class="alink">Feedback</a>
                <a href="#" class="alink">Support Center</a>
                <a href="#" class="alink">Request</a>
              </div> 
        </div>
        <div class="box-conpy-right">
        	© 2022 The Alchemist - All Rights Reserved
            
        </div>
    </div>
</div>

<a href="#" class="icon-back-top"><i class="icon-up-open-1"></i></a>
<script src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
 <script src="js/swiper.min.js"></script>
<script src="js/defaul.js" type="text/javascript"></script>
<script src="https://kit.fontawesome.com/yourcode.js"></script>
<script>
    var swiper = new Swiper('.swiper-slide-toppic', {
      cssMode: true,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      pagination: {
        el: '.swiper-pagination',
		clickable: true,
      },
      mousewheel: true,
      keyboard: true,
	  autoplaySpeed: 3000,
        autoplay: true,
		slidesPerView: "auto" 
    });
	///
    
  </script>
  <script>
  var swiper = new Swiper('.Alchemist-Trading-slide', {
      slidesPerView: 4,
      spaceBetween: 24,
      loop: true,
      loopFillGroupWithBlank: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
   </script>
</body>
</html>